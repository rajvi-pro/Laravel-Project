<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\MedicalReport;
use App\Models\Prescription;
use App\Models\LabResult;
use App\Models\Billing;
use App\Models\DoctorUnavailability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientDashboardController extends Controller
{
    // ...existing code...

    // Manual Reschedule Page (GET)
    public function manualRescheduleForm($appointmentId)
    {
        if ($check = $this->checkLogin()) return $check;
        $appointment = Appointment::with('doctor')->findOrFail($appointmentId);
        // Only allow if appointment belongs to logged-in patient
        if ($appointment->patient_id != session('patient_id')) {
            return redirect()->route('patient.appointments')->with('error', 'Unauthorized.');
        }
        return view('patient.appointment-manual-reschedule', compact('appointment'));
    }

    // Manual Reschedule Save (POST)
    public function manualRescheduleSave(Request $request, $appointmentId)
    {
        if ($check = $this->checkLogin()) return $check;
        $appointment = Appointment::findOrFail($appointmentId);
        if ($appointment->patient_id != session('patient_id')) {
            return redirect()->route('patient.appointments')->with('error', 'Unauthorized.');
        }
        
        // Validate time slot format (HH:mm)
        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => ['required', 'date_format:H:i'],
        ]);
        
        // Check if doctor is unavailable on the selected date
        $unavailability = DoctorUnavailability::where('doctor_id', $appointment->doctor_id)
            ->where('unavailable_date', '<=', $validated['appointment_date'])
            ->where(function($query) use ($validated) {
                $query->where('end_date', '>=', $validated['appointment_date'])
                      ->orWhereNull('end_date');
            })
            ->first();
        
        if ($unavailability) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Doctor is unavailable on this date: {$unavailability->reason}")
                ->with('unavailable_warning', "Doctor is unavailable: {$unavailability->reason}");
        }
        
        $appointment->appointment_date = $validated['appointment_date'];
        $appointment->appointment_time = $validated['appointment_time'];
        $appointment->status = 'scheduled';
        $appointment->save();
        return redirect()->route('patient.appointments')->with('success', 'Appointment rescheduled successfully!');
    }
    
    // Check Doctor Availability (AJAX)
    public function checkAvailability(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $appointmentDate = $request->input('appointment_date');
        
        $unavailability = DoctorUnavailability::where('doctor_id', $doctorId)
            ->where('unavailable_date', '<=', $appointmentDate)
            ->where(function($query) use ($appointmentDate) {
                $query->where('end_date', '>=', $appointmentDate)
                      ->orWhereNull('end_date');
            })
            ->first();
        
        if ($unavailability) {
            return response()->json([
                'unavailable' => true,
                'reason' => $unavailability->reason
            ]);
        }
        
        return response()->json(['unavailable' => false]);
    }

    public function getAvailableTimeSlots(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $appointmentDate = $request->input('appointment_date');
        
        \Log::info('Slot Request:', ['doctor_id' => $doctorId, 'appointment_date' => $appointmentDate]);
        
        if (!$doctorId || !$appointmentDate) {
            return response()->json([
                'error' => 'Doctor ID and appointment date are required',
                'slots' => []
            ], 400);
        }

        // Check if doctor is unavailable on this date
        $unavailability = DoctorUnavailability::where('doctor_id', $doctorId)
            ->where('unavailable_date', '<=', $appointmentDate)
            ->where(function($query) use ($appointmentDate) {
                $query->where('end_date', '>=', $appointmentDate)
                      ->orWhereNull('end_date');
            })
            ->first();

        if ($unavailability) {
            \Log::info('Doctor unavailable:', ['reason' => $unavailability->reason]);
            return response()->json([
                'unavailable' => true,
                'reason' => $unavailability->reason,
                'slots' => []
            ]);
        }

        // Get doctor's schedule for this day
        $date = \Carbon\Carbon::parse($appointmentDate);
        $dayOfWeek = $date->format('N'); // 1-7 (Monday=1, Sunday=7)
        $dayName = strtolower($date->format('l')); // 'monday', 'tuesday', etc. (for backwards compatibility)

        \Log::info('Looking for schedule:', ['doctor_id' => $doctorId, 'day_of_week' => $dayOfWeek, 'day_name' => $dayName]);

        // Get doctor schedule for this specific day of week (support both formats)
        $schedule = \App\Models\DoctorSchedule::where('doctor_id', $doctorId)
            ->where(function($q) use ($dayOfWeek, $dayName) {
                $q->where('day_of_week', $dayOfWeek)
                  ->orWhere('day_of_week', $dayName);
            })
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            // Check how many schedules exist for this doctor
            $scheduleCount = \App\Models\DoctorSchedule::where('doctor_id', $doctorId)->count();
            \Log::info('No schedule found. Total schedules for doctor:', ['count' => $scheduleCount, 'doctor_id' => $doctorId]);
            
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $dayNameLabel = $dayNames[$dayOfWeek] ?? 'this day';
            
            return response()->json([
                'unavailable' => true,
                'reason' => 'Doctor does not work on ' . $dayNameLabel . 's',
                'debug' => "No schedules found for this doctor. Total in DB: $scheduleCount",
                'slots' => []
            ]);
        }

        \Log::info('Schedule found:', ['start' => $schedule->start_time, 'end' => $schedule->end_time]);

        // Generate time slots (1 hour duration)
        $allSlots = [];
        $current = \Carbon\Carbon::parse($schedule->start_time);
        $endTime = \Carbon\Carbon::parse($schedule->end_time);
        $breakStart = $schedule->break_start ? \Carbon\Carbon::parse($schedule->break_start) : null;
        $breakEnd = $schedule->break_end ? \Carbon\Carbon::parse($schedule->break_end) : null;

        while ($current->lessThan($endTime)) {
            $slotEnd = $current->copy()->addHour();

            // Skip if slot falls within break time
            if ($breakStart && $breakEnd) {
                if ($current->lessThan($breakEnd) && $slotEnd->greaterThan($breakStart)) {
                    $current = $breakEnd->copy();
                    continue;
                }
            }

            // Skip if slot goes beyond end time
            if ($slotEnd->greaterThan($endTime)) {
                break;
            }

            $timeStr = $current->format('H:i');
            $displayStr = $current->format('g:i A') . ' - ' . $slotEnd->format('g:i A');
            $period = ($current->hour < 12) ? 'Morning' : (($current->hour < 17) ? 'Afternoon' : 'Evening');

            $allSlots[] = [
                'time' => $timeStr,
                'display' => $displayStr,
                'period' => $period
            ];

            $current->addHour();
        }

        // Get booked appointments for this doctor on this date
        $bookedAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $appointmentDate)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->toArray();

        \Log::info('Generated slots:', ['count' => count($allSlots), 'booked' => count($bookedAppointments)]);

        // Filter available slots
        $availableSlots = array_filter($allSlots, function($slot) use ($bookedAppointments) {
            return !in_array($slot['time'], $bookedAppointments);
        });

        // Reset array keys
        $availableSlots = array_values($availableSlots);

        return response()->json([
            'available' => true,
            'slots' => $availableSlots,
            'doctorName' => \App\Models\Doctor::find($doctorId)->name ?? 'Doctor',
            'bookedCount' => count($bookedAppointments),
            'availableCount' => count($availableSlots),
            'workingHours' => $schedule->start_time . ' - ' . $schedule->end_time
        ]);
    }
    
    private function checkLogin()
    {
        if (!session('patient_logged_in')) {
            return redirect()->route('patient.login')->with('error', 'Please login first.');
        }
        return null;
    }

    public function index()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $patientId = session('patient_id');
        
        // Fetch patient data from database
        $patient = Patient::find($patientId);
        if (!$patient) {
            return redirect('/patient/login')->with('error', 'Patient record not found');
        }
        
        $appointments = Appointment::where('patient_id', $patientId)
            ->with('doctor')
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date', 'asc')
            ->get();

        $upcomingAppointments = $appointments->count();
        $totalAppointments = Appointment::where('patient_id', $patientId)->count();
        $totalReports = MedicalReport::where('patient_id', $patientId)->count();
        $totalPrescriptions = Prescription::where('patient_id', $patientId)->count();
        $activePrescriptions = $totalPrescriptions;

        // Fetch actual prescription records
        $prescriptions = Prescription::where('patient_id', $patientId)
            ->with('doctor')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch actual lab result records
        $labResultsArray = LabResult::where('patient_id', $patientId)
            ->with('doctor')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $labResults = LabResult::where('patient_id', $patientId)->count();

        // Fetch actual billing records
        $billings = \App\Models\Billing::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Billing metrics
        $totalBillings = \App\Models\Billing::where('patient_id', $patientId)->count();
        $pendingBillings = \App\Models\Billing::where('patient_id', $patientId)->where('payment_status', 'pending')->count();

        return view('patient.dashboard', compact('patient', 'appointments', 'upcomingAppointments', 'totalAppointments', 'totalReports', 'totalPrescriptions', 'totalBillings', 'pendingBillings', 'prescriptions', 'labResultsArray', 'billings', 'activePrescriptions', 'labResults'));
    }

    public function appointments()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $patientId = session('patient_id');
        $appointments = Appointment::where('patient_id', $patientId)
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.appointments-new', compact('appointments'));
    }

    public function profile()
    {
        if ($check = $this->checkLogin()) return $check;

        $patientId = session('patient_id');
        $patient = Patient::find($patientId);
        
        $requiresFullProfile = empty($patient->phone)
            || empty($patient->date_of_birth)
            || empty($patient->gender)
            || empty($patient->address)
            || empty($patient->city)
            || empty($patient->state)
            || empty($patient->pincode)
            || empty($patient->blood_group)
            || empty($patient->emergency_contact);

        if ($requiresFullProfile) {
            return redirect()->route('patient.profile.edit');
        }

        // Fetch metrics data
        $totalAppointments = Appointment::where('patient_id', $patientId)->count();
        $upcomingAppointments = Appointment::where('patient_id', $patientId)
            ->whereDate('appointment_date', '>=', today())
            ->count();
        $labResults = LabResult::where('patient_id', $patientId)->count();
        
        // Fetch family members
        $familyMembers = $patient->familyMembers()->get();

        return view('patient.profile-view', compact('patient', 'totalAppointments', 'upcomingAppointments', 'labResults', 'familyMembers'));
    }

    public function editProfile()
    {
        if ($check = $this->checkLogin()) return $check;

        $patient = Patient::find(session('patient_id'));
        $requiresFullProfile = empty($patient->phone)
            || empty($patient->date_of_birth)
            || empty($patient->gender)
            || empty($patient->address)
            || empty($patient->city)
            || empty($patient->state)
            || empty($patient->pincode)
            || empty($patient->blood_group)
            || empty($patient->emergency_contact);

        return view('patient.profile-edit', compact('patient', 'requiresFullProfile'));
    }

    public function editProfileView()
    {
        if ($check = $this->checkLogin()) return $check;

        $patient = Patient::find(session('patient_id'));
        return view('patient.profile-edit-view', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $patient = Patient::find(session('patient_id'));

        if ($request->filled('date_of_birth')) {
            try {
                $dob = Carbon::parse($request->input('date_of_birth'));
                if ($dob->isFuture()) {
                    return back()->with('birthday_error', 'Date of birth must be in the past.')->withInput();
                }
            } catch (Exception $e) {
                return back()->with('birthday_error', 'Invalid date format for Date of Birth.')->withInput();
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|min:8|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|digits_between:4,10',
            'blood_group' => 'required|string|max:10',
            'emergency_contact' => 'required|string|min:8|max:20',
        ]);

        $patient->update($validated);

        if ($request->has('save_profile')) {
            $message = 'Profile saved!';
        } else {
            $message = 'Profile updated!';
        }

        return redirect()->route('patient.profile')->with('success', $message);
    }

    public function createAppointmentForm()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $doctors = Doctor::orderBy('name')->get();
        return view('patient.appointment-create', compact('doctors'));
    }

    // Simple doctor selection for quick booking
    public function selectDoctor()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $doctors = Doctor::orderBy('specialization', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        return view('patient.doctor-select', compact('doctors'));
    }

    // Quick book appointment directly from doctor
    public function quickBookAppointment($doctorId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $doctor = Doctor::findOrFail($doctorId);
        $doctors = Doctor::orderBy('name')->get();
        
        return view('patient.appointment-create', compact('doctors', 'doctor'));
    }

    public function storeAppointment(PatientAppointmentRequest $request)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $validated = $request->validated();

        // Check if doctor is available
        $doctor = Doctor::findOrFail($validated['doctor_id']);
        if (!$doctor->isAvailableToday()) {
            return redirect()->route('patient.appointment.create.form')
                ->with('error', 'Cannot book appointment: ' . ($doctor->getUnavailabilityMessage() ?? 'Doctor is not available today'));
        }

        Appointment::create([
            'patient_id' => session('patient_id'),
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'scheduled'
        ]);

        return redirect()->route('patient.appointments')->with('success', 'Appointment booked!');
    }

    public function appointmentDetail($appointmentId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $appointment = Appointment::with('doctor')->findOrFail($appointmentId);
        
        if ($appointment->patient_id != session('patient_id')) {
            return redirect()->route('patient.appointments')->with('error', 'Unauthorized.');
        }

        return view('patient.appointment-detail', compact('appointment'));
    }

    public function showCancelAppointmentForm($appointmentId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $appointment = Appointment::with('doctor')->findOrFail($appointmentId);
        
        if ($appointment->patient_id != session('patient_id')) {
            return redirect()->route('patient.appointments')->with('error', 'Unauthorized.');
        }

        if ($appointment->status == 'completed' || $appointment->status == 'cancelled') {
            return redirect()->route('patient.appointments')->with('error', 'This appointment cannot be cancelled.');
        }

        return view('patient.appointment-cancel', compact('appointment'));
    }

    public function cancelAppointment(Request $request, $appointmentId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $appointment = Appointment::findOrFail($appointmentId);
        
        if ($appointment->patient_id != session('patient_id')) {
            return redirect()->route('patient.appointments')->with('error', 'Unauthorized.');
        }

        if ($appointment->status == 'completed' || $appointment->status == 'cancelled') {
            return redirect()->route('patient.appointments')->with('error', 'This appointment cannot be cancelled.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return redirect()->route('patient.appointments')->with('success', 'Appointment cancelled successfully.');
    }

    public function medicalHistory()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $patientId = session('patient_id');
        $medicalReports = MedicalReport::where('patient_id', $patientId)
            ->with('doctor')
            ->orderBy('report_date', 'desc')
            ->get();

        return view('patient.medical-history-new', compact('medicalReports'));
    }

    public function medicalReportDetail($reportId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $report = MedicalReport::findOrFail($reportId);
        
        if ($report->patient_id != session('patient_id')) {
            return redirect()->route('patient.medical-history')->with('error', 'Unauthorized.');
        }

        return view('patient.medical-report-detail', compact('report'));
    }

    public function prescriptions()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $patientId = session('patient_id');
        $prescriptions = Prescription::where('patient_id', $patientId)
            ->with('doctor')
            ->orderBy('prescribed_date', 'desc')
            ->get();

        return view('patient.prescriptions-new', compact('prescriptions'));
    }

    public function prescriptionDetail($prescriptionId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $prescription = Prescription::with('doctor')->findOrFail($prescriptionId);
        return view('patient.prescription-detail', compact('prescription'));
    }

    public function labResults()
    {
        if ($check = $this->checkLogin()) return $check;
        
        $patientId = session('patient_id');
        $labResults = LabResult::where('patient_id', $patientId)
            ->with('doctor')
            ->orderBy('test_date', 'desc')
            ->get();

        return view('patient.lab-results-new', compact('labResults'));
    }

    public function labResultDetail($labResultId)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $labResult = LabResult::findOrFail($labResultId);
        return view('patient.lab-result-detail', compact('labResult'));
    }

    public function showChangePasswordForm()
    {
        if ($check = $this->checkLogin()) return $check;
        
        return view('patient.change-password');
    }

    public function changePassword(Request $request)
    {
        if ($check = $this->checkLogin()) return $check;
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::find(session('patient_id'));

        if (!Hash::check($request->current_password, $patient->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $patient->password = Hash::make($request->new_password);
        $patient->save();

        return redirect()->route('patient.profile')->with('success', 'Password changed successfully!');
    }

    public function showAddMemberForm()
    {
        if ($check = $this->checkLogin()) return $check;

        return view('patient.add-member');
    }

    public function addMember(Request $request)
    {
        if ($check = $this->checkLogin()) return $check;

        $validated = $request->validate([
            'member_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'member_date_of_birth' => 'required|date|before:today',
            'member_gender' => 'required|in:male,female,other',
            'member_phone' => 'nullable|string|max:20',
            'member_blood_group' => 'nullable|string|max:10',
            'member_notes' => 'nullable|string|max:1000',
        ]);

        $patientId = session('patient_id');
        
        // Store family member in database
        \App\Models\FamilyMember::create([
            'patient_id' => $patientId,
            'member_name' => $validated['member_name'],
            'relationship' => $validated['relationship'],
            'date_of_birth' => $validated['member_date_of_birth'],
            'gender' => $validated['member_gender'],
            'phone' => $validated['member_phone'] ?? null,
            'blood_group' => $validated['member_blood_group'] ?? null,
            'notes' => $validated['member_notes'] ?? null,
        ]);
        
        return redirect()->route('patient.profile')->with('success', 'Family member added successfully!');
    }

    public function doctorSearch(Request $request)
    {
        if ($check = $this->checkLogin()) return $check;

        $query = $request->input('q', '');
        if (trim($query) === '') {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $doctors = Doctor::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('specialization', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%');
        })
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN specialization LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->get();

        return view('patient.doctor-search', compact('doctors', 'query'));
    }

    public function doctorSuggestions(Request $request)
    {
        if ($check = $this->checkLogin()) return $check;

        $query = trim($request->input('q', ''));
        if ($query === '') {
            return response()->json(['suggestions' => []]);
        }

        $doctors = Doctor::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('specialization', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%');
        })
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN specialization LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->limit(8)
        ->get(['id', 'name', 'specialization', 'email'])
        ->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'type' => 'Doctor',
                'detail' => $doctor->specialization ?: $doctor->email,
            ];
        });

        return response()->json(['suggestions' => $doctors]);
    }

    // Billing Methods
    public function billings()
    {
        if ($check = $this->checkLogin()) return $check;

        $patientId = session('patient_id');
        $billings = \App\Models\Billing::where('patient_id', $patientId)
            ->with('appointment', 'patient', 'staff')
            ->orderBy('billing_date', 'desc')
            ->paginate(15);

        $totalAmount = \App\Models\Billing::where('patient_id', $patientId)->sum('amount');
        $paidAmount = \App\Models\Billing::where('patient_id', $patientId)->where('payment_status', 'paid')->sum('amount');
        $pendingAmount = $totalAmount - $paidAmount;

        return view('patient.billing', compact('billings', 'totalAmount', 'paidAmount', 'pendingAmount'));
    }

    public function billingDetail($billingId)
    {
        if ($check = $this->checkLogin()) return $check;

        $billing = \App\Models\Billing::findOrFail($billingId);
        
        // Ensure patient can only view their own bills
        if ($billing->patient_id != session('patient_id')) {
            return redirect()->route('patient.billings')->with('error', 'Unauthorized access.');
        }

        $billing->load('appointment', 'appointment.doctor', 'patient', 'staff');
        return view('patient.billing-detail', compact('billing'));
    }

    public function generateBillingPdf($billingId)
    {
        if ($check = $this->checkLogin()) return $check;

        $billing = \App\Models\Billing::findOrFail($billingId);
        
        // Ensure patient can only download their own bills
        if ($billing->patient_id != session('patient_id')) {
            return redirect()->route('patient.billings')->with('error', 'Unauthorized access.');
        }

        $billing->load('appointment', 'appointment.doctor', 'patient', 'staff');
        
        // Return PDF view for printing/downloading
        return view('patient.billing-pdf', compact('billing'));
    }

    public function markBillingAsPaid($billingId)
    {
        if ($check = $this->checkLogin()) return $check;

        $patientId = session('patient_id');
        $billing = \App\Models\Billing::findOrFail($billingId);
        
        // Ensure patient can only mark their own bills as paid
        if ($billing->patient_id != $patientId) {
            return redirect()->route('patient.billings')->with('error', 'Unauthorized access.');
        }

        // Check if already paid
        if ($billing->payment_status === 'paid') {
            return redirect()->route('patient.billing.view', $billing->id)
                ->with('info', 'This bill is already marked as paid.');
        }

        // Update billing status to paid
        $billing->update([
            'payment_status' => 'paid',
            'payment_method' => 'patient_portal'
        ]);

        return redirect()->route('patient.billing.view', $billing->id)
            ->with('success', '✅ Bill marked as paid successfully!');
    }
}



