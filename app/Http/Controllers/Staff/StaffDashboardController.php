<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Billing;
use App\Models\BillItem;
use App\Models\Prescription;
use App\Models\LabResult;
use App\Services\BillingService;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    /**
     * Dashboard Index
     */
    public function index()
    {
        $todayAppointments = Appointment::whereDate('appointment_date', today())
            ->with('patient', 'doctor')
            ->orderBy('appointment_date')
            ->get();

        $totalPatients = Patient::count();

        // Get available doctors (those with appointments or all if none have appointments)
        $availableDoctors = Doctor::withCount('appointments')->count();

        $pendingAppointments = Appointment::whereIn('status', ['pending', null])
            ->count();

        $appointmentsToday = $todayAppointments->count();

        $upcomingAppointments = Appointment::where('appointment_date', '>', today())
            ->orderBy('appointment_date')
            ->with('patient', 'doctor')
            ->take(10)
            ->get();

        $malePatients = Patient::where('gender', 'male')->count();
        $femalePatients = Patient::where('gender', 'female')->count();

        $totalDoctors = Doctor::count();

        $billings = Billing::count();
        $totalBillingAmount = Billing::sum('amount') ?? 0;
        $paidAmount = Billing::where('payment_status', 'paid')->sum('amount') ?? 0;
        $pendingBillings = Billing::where('payment_status', 'pending')->count();

        $patients = Patient::take(20)->get();
        $doctors = Doctor::with('appointments')->take(10)->get();

        return view('staff.dashboard', compact(
            'todayAppointments',
            'totalPatients',
            'availableDoctors',
            'pendingAppointments',
            'appointmentsToday',
            'upcomingAppointments',
            'malePatients',
            'femalePatients',
            'totalDoctors',
            'billings',
            'totalBillingAmount',
            'paidAmount',
            'pendingBillings',
            'patients',
            'doctors'
        ));
    }

    /**
     * Appointments Management
     */
    public function appointments()
    {
        $appointments = Appointment::with('patient', 'doctor')
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('staff.appointments', compact('appointments', 'patients', 'doctors'));
    }

    public function appointmentDetail($id)
    {
        $appointment = Appointment::with('patient', 'doctor')->findOrFail($id);
        return view('staff.appointment-detail', compact('appointment'));
    }

    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason' => 'nullable|string',
        ]);

        // Check if doctor is available
        $doctor = Doctor::findOrFail($validated['doctor_id']);
        if (!$doctor->isAvailableToday()) {
            return redirect()->route('staff.appointments')
                ->with('error', 'Cannot book appointment: ' . ($doctor->getUnavailabilityMessage() ?? 'Doctor is not available today'));
        }

        Appointment::create($validated);

        return redirect()->route('staff.appointments')->with('success', 'Appointment created successfully!');
    }

    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|in:pending,completed,cancelled',
            'appointment_date' => 'nullable|date',
            'appointment_time' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('staff.appointments')->with('success', 'Appointment updated successfully!');
    }

    public function deleteAppointment($id)
    {
        try {
            Appointment::findOrFail($id)->delete();
            return redirect()->route('staff.appointments')->with('success', 'Appointment deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('staff.appointments')->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }

    public function cancelAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => request()->input('cancellation_reason')
        ]);
        return redirect()->route('staff.appointments')->with('success', 'Appointment cancelled successfully!');
    }

    public function getAvailableTimeSlots(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $appointmentDate = $request->input('appointment_date');
        
        \Log::info('Staff Slot Request:', ['doctor_id' => $doctorId, 'appointment_date' => $appointmentDate]);
        
        if (!$doctorId || !$appointmentDate) {
            return response()->json([
                'error' => 'Doctor ID and appointment date are required',
                'slots' => []
            ], 400);
        }

        // Check if doctor is unavailable on this date
        $unavailability = \App\Models\DoctorUnavailability::where('doctor_id', $doctorId)
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

    /**
     * Patients Management
     */
    public function patients()
    {
        $patients = Patient::paginate(15);
        $appointments = Appointment::all();
        $prescriptions = Prescription::all();
        return view('staff.patients', compact('patients', 'appointments', 'prescriptions'));
    }

    public function patientDetail($id)
    {
        $patient = Patient::with('appointments', 'prescriptions', 'labResults', 'medicalReports')->findOrFail($id);
        $appointments = $patient->appointments()->paginate(10);
        
        return view('staff.patient-detail', compact('patient', 'appointments'));
    }

    public function storePatientHistory(Request $request, $id)
    {
        // This could store check-in, notes, or vital signs
        $patient = Patient::findOrFail($id);
        
        // Update patient with additional information if needed
        $patient->update($request->only(['address', 'phone', 'email']));

        return redirect()->route('staff.patient.detail', $id)->with('success', 'Patient information updated!');
    }

    /**
     * Show Edit Patient Form
     */
    public function editPatient($id)
    {
        $patient = Patient::findOrFail($id);
        return view('staff.patient-edit', compact('patient'));
    }

    /**
     * Update Patient Information
     */
    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $patient->update($validated);

        return redirect()->route('staff.patient.detail', $id)->with('success', 'Patient information updated successfully!');
    }

    /**
     * Doctor Schedule
     */
    public function doctors()
    {
        $doctors = Doctor::with('appointments')->paginate(12);
        return view('staff.doctors', compact('doctors'));
    }

    public function doctorSchedule($id)
    {
        $doctor = Doctor::with(['appointments.patient'])->findOrFail($id);
        $appointments = $doctor->appointments()->with('patient')->orderBy('appointment_date')->orderBy('appointment_time')->get();

        return view('staff.doctor-schedule', compact('doctor', 'appointments'));
    }

    /**
     * Billing Management
     */
    public function billings()
    {
        // Get billings that are NOT paid (exclude paid status)
        // Only for patients with completed appointments
        $billings = Billing::with('patient')
            ->whereIn('payment_status', ['pending', 'overdue'])
            ->orderBy('billing_date', 'desc')
            ->paginate(15);

        // Calculate statistics for unpaid/pending billings only
        $totalBillingAmount = Billing::whereIn('payment_status', ['pending', 'overdue'])->sum('amount') ?? 0;
        $paidAmount = Billing::where('payment_status', 'paid')->sum('amount') ?? 0;
        $pendingAmount = Billing::whereIn('payment_status', ['pending', 'overdue'])->sum('amount') ?? 0;
        
        // Get eligible patients for billing (with completed appointments and no paid billing)
        $patientsWithCompletedAppointments = Patient::whereHas('appointments', function($query) {
            $query->where('status', 'completed');
        })
        ->whereDoesntHave('billings', function($query) {
            $query->where('payment_status', 'paid');
        })
        ->get();

        return view('staff.billings', compact('billings', 'totalBillingAmount', 'paidAmount', 'pendingAmount', 'patientsWithCompletedAppointments'));
    }

    /**
     * Show the billing creation form with completed appointments
     */
    public function createBilling()
    {
        // Get completed appointments without existing billings
        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('status', 'completed')
            ->whereDoesntHave('billings')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('staff.create-billing', compact('appointments'));
    }

    public function billingDetail($id)
    {
        $billing = Billing::with(['patient', 'billItems'])->findOrFail($id);
        return view('staff.billing-detail', compact('billing'));
    }

    public function storeBilling(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'nullable|numeric|min:0',
            'billing_date' => 'required|date',
            'payment_status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
            'cgst_rate' => 'nullable|numeric|min:0|max:100',
            'sgst_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $patient_id = $validated['patient_id'];

        // Check if patient has completed appointments
        $completedAppointments = Appointment::where('patient_id', $patient_id)
            ->where('status', 'completed')
            ->count();

        if ($completedAppointments == 0) {
            return redirect()->route('staff.billings')
                ->with('error', 'Cannot create billing for patient without completed appointments!');
        }

        // Check if patient already has a paid billing (to prevent duplicate billing)
        $paidBilling = Billing::where('patient_id', $patient_id)
            ->where('payment_status', 'paid')
            ->exists();

        if ($paidBilling) {
            return redirect()->route('staff.billings')
                ->with('info', 'This patient already has a paid billing. No additional billing needed.');
        }

        $cgst_rate = $validated['cgst_rate'] ?? 9;
        $sgst_rate = $validated['sgst_rate'] ?? 9;

        // Calculate billing total including prescriptions and lab charges
        $billingData = Billing::calculateBillingTotal($patient_id, $cgst_rate, $sgst_rate);

        $billing = new Billing();
        $billing->patient_id = $patient_id;
        $billing->billing_date = $validated['billing_date'];
        $billing->payment_status = $validated['payment_status'];
        $billing->description = $validated['description'] ?? 'Medical Services';
        $billing->staff_id = auth()->id();

        // Set billing amounts
        $billing->subtotal = $billingData['subtotal'];
        $billing->cgst = $billingData['cgst'];
        $billing->sgst = $billingData['sgst'];
        $billing->total_tax = $billingData['total_tax'];
        $billing->amount = $billingData['total'];

        // Archive billing items
        $billing->billing_items = $billing->archiveBillingItems();

        $billing->save();

        return redirect()->route('staff.billings')->with('success', 'Billing record created successfully with prescriptions and lab charges!');
    }

    public function updateBilling(Request $request, $id)
    {
        $billing = Billing::findOrFail($id);

        $validated = $request->validate([
            'subtotal' => 'nullable|numeric|min:0',
            'cgst' => 'nullable|numeric|min:0',
            'sgst' => 'nullable|numeric|min:0',
            'total_tax' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'billing_date' => 'nullable|date',
            'payment_status' => 'nullable|in:pending,paid,overdue',
            'description' => 'nullable|string',
        ]);

        // Update all billing fields
        $billing->update($validated);

        return redirect()->route('staff.billings')->with('success', 'Billing record updated successfully!');
    }

    public function deleteBilling($id)
    {
        try {
            Billing::findOrFail($id)->delete();
            return redirect()->route('staff.billings')->with('success', 'Billing record deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('staff.billings')->with('error', 'Failed to delete billing record: ' . $e->getMessage());
        }
    }

    public function sendBillingEmail(Request $request, $id)
    {
        try {
            $billing = Billing::with('patient')->findOrFail($id);
            
            $validated = $request->validate([
                'email' => 'required|email',
                'message' => 'nullable|string',
                'include_payment_terms' => 'nullable|boolean'
            ]);

            $email = $validated['email'];
            $message = $validated['message'] ?? '';
            $includePaymentTerms = $request->has('include_payment_terms');

            // Get billing items
            $billingItems = json_decode($billing->billing_items, true) ?? [];

            // Prepare email data
            $emailData = [
                'billing' => $billing,
                'patient' => $billing->patient,
                'prescriptions' => $billingItems['prescriptions'] ?? [],
                'lab_results' => $billingItems['lab_results'] ?? [],
                'custom_message' => $message,
                'include_payment_terms' => $includePaymentTerms
            ];

            // Send email
            \Mail::send('emails.billing-invoice', $emailData, function($mail) use ($email, $billing) {
                $mail->to($email)
                    ->subject('Invoice #BIL-' . str_pad($billing->id, 5, '0', STR_PAD_LEFT) . ' - Hospital Management System');
            });

            return redirect()->route('staff.billing.detail', $id)->with('success', 'Invoice sent successfully to patient email!');
        } catch (\Exception $e) {
            return redirect()->route('staff.billing.detail', $id)->with('error', 'Failed to send invoice: ' . $e->getMessage());
        }
    }

    /**
     * Search & Suggestions
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $patients = Patient::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();

        $appointments = Appointment::with('patient', 'doctor')
            ->whereHas('patient', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->get();

        return view('staff.search', compact('patients', 'appointments'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q');

        $patients = Patient::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->select('id', 'name', 'email')
            ->take(10)
            ->get();

        return response()->json($patients);
    }

    /**
     * Medicines Management (Optional)
     */
    public function medicines()
    {
        $medicines = \App\Models\Medicine::orderBy('created_at', 'desc')->paginate(15);
        
        $totalMedicines = \App\Models\Medicine::count();
        $totalValue = \App\Models\Medicine::sum(\DB::raw('stock_quantity * unit_price')) ?? 0;
        $lowStockItems = \App\Models\Medicine::where('stock_quantity', '<', 20)->count();
        $expiredItems = \App\Models\Medicine::where('expiry_date', '<', now())->count();

        return view('staff.medicines', compact('medicines', 'totalMedicines', 'totalValue', 'lowStockItems', 'expiredItems'));
    }

    public function createMedicine()
    {
        $medicineNames = [
            'Aspirin',
            'Paracetamol',
            'Ibuprofen',
            'Amoxicillin',
            'Lisinopril',
            'Metformin',
            'Atorvastatin',
            'Amlodipine',
            'Omeprazole',
            'Simvastatin',
            'Losartan',
            'Furosemide',
            'Ciprofloxacin',
            'Cephalexin',
            'Azithromycin',
            'Ketoconazole',
            'Fluconazole',
            'Griseofulvin',
            'Acyclovir',
            'Lamivudine'
        ];

        $manufacturers = [
            'Abbott India',
            'Alkem Laboratories',
            'Cipla Limited',
            'Dr. Reddy\'s Laboratories',
            'Glenmark Pharmaceuticals',
            'Glaxo SmithKline',
            'Ipca Laboratories',
            'Lupin Limited',
            'Merck Serono',
            'MedIcare Inc',
            'PainRelief Ltd',
            'HealthPlus',
            'Pharma Care',
            'Medicine Valley',
            'Global Health Solutions'
        ];

        $categories = [
            'Painkiller',
            'Antibiotic',
            'Antiviral',
            'Antiseptic',
            'Cardiovascular',
            'Diabetes',
            'Digestive',
            'Neural',
            'Respiratory',
            'Skin Care',
            'Vitamin & Supplements',
            'Other'
        ];

        return view('staff.medicines.create', compact('medicineNames', 'manufacturers', 'categories'));
    }

    public function storeMedicine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'required|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0.01',
            'expiry_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['staff_id'] = auth('staff')->id();
        \App\Models\Medicine::create($validated);

        return redirect()->route('staff.medicines')->with('success', 'Medicine added successfully!');
    }

    /**
     * Lab Results Management (Optional)
     */
    public function labResults()
    {
        return view('staff.lab-results');
    }

    public function unavailableDoctors()
    {
        $unavailabilities = \App\Models\DoctorUnavailability::with('doctor')
            ->orderBy('unavailable_date', 'desc')
            ->paginate(15);

        return view('staff.unavailable-doctors', compact('unavailabilities'));
    }

    public function affectedAppointments($unavailabilityId)
    {
        $unavailability = \App\Models\DoctorUnavailability::with('doctor')->findOrFail($unavailabilityId);
        
        $unavailabilityService = new \App\Services\UnavailabilityService();
        $affectedAppointments = $unavailabilityService->findAffectedAppointments(
            $unavailability->doctor,
            $unavailability
        );

        return view('staff.affected-appointments', compact('unavailability', 'affectedAppointments'));
    }

    public function rescheduleAppointment(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $appointment->update([
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => 'scheduled'
        ]);

        // Send notification to patient
        \App\Models\Notification::create([
            'notifiable_type' => 'App\Models\Patient',
            'notifiable_id' => $appointment->patient_id,
            'type' => 'appointment_rescheduled',
            'title' => 'Appointment Rescheduled',
            'message' => "Your appointment with Dr. {$appointment->doctor->name} has been rescheduled to {$appointment->appointment_date->format('d M Y')} at {$appointment->appointment_time}.",
            'related_type' => Appointment::class,
            'related_id' => $appointment->id
        ]);

        return redirect()->route('staff.appointment.detail', $appointmentId)->with('success', 'Appointment rescheduled successfully!');
    }

    /**
     * Staff Profile & Password Management
     */
    public function profile()
    {
        if (!session('staff_logged_in')) {
            return redirect()->route('staff.login');
        }

        $staff = \App\Models\Staff::findOrFail(session('staff_id'));
        return view('staff.profile', compact('staff'));
    }

    public function showChangePasswordForm()
    {
        if (!session('staff_logged_in')) {
            return redirect()->route('staff.login');
        }

        return view('staff.change-password');
    }

    public function changePassword(Request $request)
    {
        if (!session('staff_logged_in')) {
            return redirect()->route('staff.login');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $staff = \App\Models\Staff::findOrFail(session('staff_id'));

        // Verify current password
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $staff->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Validate new password complexity
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $request->new_password)) {
            return back()->withErrors(['new_password' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long.']);
        }

        // Update password
        $staff->update(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)]);

        return redirect()->route('staff.change-password')->with('success', 'Password changed successfully!');
    }

    /**
     * Get appointment details via AJAX for billing form
     * 
     * Fetches patient name, doctor name, consultation fee, and related lab reports
     */
    public function getAppointmentDetails($appointmentId)
    {
        try {
            $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointmentId);

            // Check if billing already exists for this appointment
            $existingBilling = Billing::where('appointment_id', $appointmentId)->exists();

            if ($existingBilling) {
                return response()->json([
                    'success' => false,
                    'message' => 'A billing record already exists for this appointment.',
                    'error' => 'duplicate_billing'
                ], 422);
            }

            // Get lab reports with prices for this patient
            $labReports = LabResult::where('patient_id', $appointment->patient_id)
                ->where('status', 'completed')
                ->where('charge', '>', 0)
                ->get()
                ->map(function($lab) {
                    return [
                        'id' => $lab->id,
                        'test_name' => $lab->test_name,
                        'result' => $lab->result,
                        'charge' => (float) $lab->charge,
                    ];
                });

            // Get prescriptions with prices for this patient related to the appointment
            $prescriptions = Prescription::where('patient_id', $appointment->patient_id)
                ->where('doctor_id', $appointment->doctor_id)
                ->get()
                ->map(function($prescription) {
                    return [
                        'id' => $prescription->id,
                        'medicine_name' => $prescription->medicine_name,
                        'dosage' => $prescription->dosage,
                        'frequency' => $prescription->frequency,
                        'price' => (float) $prescription->price ?? 0,
                    ];
                });

            // Calculate consultation fee
            $consultationFee = (float) ($appointment->doctor->consultation_fee ?? 0);

            return response()->json([
                'success' => true,
                'appointment_id' => $appointment->id,
                'patient' => [
                    'id' => $appointment->patient->id,
                    'name' => $appointment->patient->name ?? $appointment->patient->full_name,
                    'email' => $appointment->patient->email,
                    'phone' => $appointment->patient->phone,
                ],
                'doctor' => [
                    'id' => $appointment->doctor->id,
                    'name' => $appointment->doctor->name,
                    'specialization' => $appointment->doctor->specialization,
                ],
                'consultation_fee' => $consultationFee,
                'lab_reports' => $labReports,
                'prescriptions' => $prescriptions,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found.',
                'error' => 'appointment_not_found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching appointment details.',
                'error' => 'server_error',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Create billing from appointment with line items
     * 
     * Stores bills and bill_items separately for better tracking
     */
    public function createBillingFromAppointment(Request $request)
    {
        try {
            // Comprehensive validation
            $validated = $request->validate([
                'appointment_id' => 'required|integer|exists:appointments,id',
                'lab_items' => 'array',
                'lab_items.*' => 'array',
                'lab_items.*.id' => 'required|integer|exists:lab_results,id',
                'lab_items.*.charge' => 'required|numeric|min:0',
                'extra_charges' => 'nullable|numeric|min:0',
                'extra_charges_description' => 'nullable|string|max:255',
                'payment_status' => 'in:pending,paid',
                'notes' => 'nullable|string|max:1000',
            ], [
                'appointment_id.required' => 'Appointment is required.',
                'appointment_id.exists' => 'Invalid appointment selected.',
                'lab_items.*.charge.numeric' => 'Lab test charges must be numeric.',
                'lab_items.*.charge.min' => 'Lab test charges cannot be negative.',
                'extra_charges.numeric' => 'Extra charges must be numeric.',
                'extra_charges.min' => 'Extra charges cannot be negative.',
            ]);

            // Validate appointment eligibility
            $validation = BillingService::validateAppointmentForBilling($validated['appointment_id']);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 422);
            }

            // Prepare lab items
            $labItems = [];
            if (!empty($validated['lab_items'])) {
                foreach ($validated['lab_items'] as $labItem) {
                    $lab = LabResult::findOrFail($labItem['id']);
                    
                    // Validate lab result is completed and has charge
                    if ($lab->status !== 'completed') {
                        return response()->json([
                            'success' => false,
                            'message' => 'Lab test ' . $lab->test_name . ' is not completed.'
                        ], 422);
                    }

                    $labItems[] = [
                        'id' => $lab->id,
                        'charge' => (float) $labItem['charge']
                    ];
                }
            }

            // Create billing using service
            $billing = BillingService::createBillingFromAppointment(
                $validated['appointment_id'],
                $labItems,
                (float) ($validated['extra_charges'] ?? 0),
                $validated['payment_status'] ?? 'pending',
                $validated['notes'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Billing record created successfully with all items.',
                'billing_id' => $billing->id,
                'redirect' => route('staff.billing.detail', $billing->id)
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Required resource not found.',
                'error' => 'not_found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred while creating the billing record.',
                'error' => 'server_error',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
