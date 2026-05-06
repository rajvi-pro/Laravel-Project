<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePrescriptionRequest;
use App\Http\Requests\CreateMedicalReportRequest;
use App\Http\Requests\CreateLabResultRequest;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\MedicalReport;
use App\Models\LabResult;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\DoctorUnavailability;
use App\Models\DoctorSchedule;
use App\Services\ScheduleValidationService;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $doctor = Doctor::findOrFail($doctorId);
        
        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->with('patient')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');
        
        $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();
        $totalPrescriptions = Prescription::where('doctor_id', $doctorId)->count();

        // Determine availability from active doctor unavailability records, not only raw DB flag.
        $isUnavailableByRecord = DoctorUnavailability::where('doctor_id', $doctorId)
            ->whereDate('unavailable_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereDate('end_date', '>=', now()->toDateString())
                  ->orWhereNull('end_date');
            })
            ->exists();

        $isAvailableToday = !$isUnavailableByRecord && $doctor->isAvailableToday();
        $unavailableMessage = $isUnavailableByRecord ? ($doctor->unavailable_message ?? 'Doctor has set an active unavailability') : null;

        session(['doctor_availability' => [
            'is_available_today' => $isAvailableToday,
            'unavailable_message' => $unavailableMessage
        ]]);

        return view('doctor.dashboard', compact('todayAppointments', 'totalPatients', 'totalAppointments', 'totalPrescriptions'));
    }

    public function patients()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $patients = Patient::whereHas('appointments', function($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->with(['appointments' => function($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId)->latest()->limit(1);
        }])->paginate(15);

        return view('doctor.patients', compact('patients'));
    }

    public function patientDetails($id)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $patient = Patient::with(['appointments', 'medicalReports', 'prescriptions'])->findOrFail($id);
        
        return view('doctor.patient-details', compact('patient'));
    }

    public function appointments()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->paginate(20);

        return view('doctor.appointments', compact('appointments'));
    }

    public function appointmentDetail($appointmentId)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $appointment = Appointment::with('patient')->findOrFail($appointmentId);
        if ($appointment->doctor_id != session('doctor_id')) {
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized.');
        }

        // Get last checkup date for this patient
        $lastCheckup = Appointment::where('patient_id', $appointment->patient_id)
            ->where('status', 'completed')
            ->whereDate('appointment_date', '<', $appointment->appointment_date)
            ->orderBy('appointment_date', 'desc')
            ->first();

        // Get prescriptions for this patient by this doctor
        $prescriptions = Prescription::where('patient_id', $appointment->patient_id)
            ->where('doctor_id', session('doctor_id'))
            ->orderBy('prescribed_date', 'desc')
            ->get();

        // Get medical reports for this patient by this doctor
        $medicalReports = MedicalReport::where('patient_id', $appointment->patient_id)
            ->where('doctor_id', session('doctor_id'))
            ->orderBy('report_date', 'desc')
            ->get();

        // Get lab results for this patient by this doctor
        $labResults = LabResult::where('patient_id', $appointment->patient_id)
            ->where('doctor_id', session('doctor_id'))
            ->orderBy('test_date', 'desc')
            ->get();

        return view('doctor.appointment-detail', compact('appointment', 'lastCheckup', 'prescriptions', 'medicalReports', 'labResults'));
    }

    public function addPrescription(Request $request, $id)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $validated = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'instructions' => 'nullable|string'
        ]);

        $validated['patient_id'] = $id;
        $validated['doctor_id'] = session('doctor_id');
        $validated['prescribed_date'] = now();

        Prescription::create($validated);

        return redirect()->route('doctor.patient.details', $id)->with('success', 'Prescription added successfully!');
    }

    public function prescriptions()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');

        $patients = Patient::orderBy('name')->get();

        $prescriptions = Prescription::where('doctor_id', $doctorId)
            ->with(['patient'])
            ->orderBy('prescribed_date', 'desc')
            ->paginate(20);

        return view('doctor.prescriptions', compact('prescriptions', 'patients'));
    }

    public function showCreatePrescriptionForm()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $patients = Patient::whereHas('appointments', function($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->orderBy('name')->get();
        $hasPatients = $patients->count() > 0;
        return view('doctor.prescription-create', compact('patients', 'hasPatients'));
    }

    public function prescriptionDetail($prescriptionId)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $prescription = Prescription::where('doctor_id', $doctorId)
            ->with('patient')
            ->findOrFail($prescriptionId);

        return view('doctor.prescription-detail', compact('prescription'));
    }

    public function createPrescription(CreatePrescriptionRequest $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $validated = $request->validated();

        $validated['doctor_id'] = session('doctor_id');
        $validated['prescribed_date'] = now();

        Prescription::create($validated);

        // Redirect to appointment detail if appointment_id was provided
        if ($request->has('appointment_id')) {
            return redirect()->route('doctor.appointment.detail', $request->input('appointment_id'))
                ->with('success', 'Prescription created successfully!');
        }

        return redirect()->route('doctor.prescriptions')->with('success', 'Prescription created successfully!');
    }

    public function medicalReports()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');

        $patients = Patient::orderBy('name')->get();

        $medicalReports = MedicalReport::where('doctor_id', $doctorId)
            ->with(['patient'])
            ->orderBy('report_date', 'desc')
            ->paginate(20);

        return view('doctor.medical-reports-simple', compact('medicalReports', 'patients'));
    }

    public function showCreateMedicalReportForm()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $patients = Patient::whereHas('appointments', function($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->orderBy('name')->get();
        $hasPatients = $patients->count() > 0;
        return view('doctor.medical-report-create', compact('patients', 'hasPatients'));
    }

    public function medicalReportsSimple()
    {
        return $this->medicalReports();
    }

    public function createMedicalReport(CreateMedicalReportRequest $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $validated = $request->validated();

        $validated['doctor_id'] = session('doctor_id');
        $validated['report_date'] = now();

        MedicalReport::create($validated);

        // Redirect to appointment detail if appointment_id was provided
        if ($request->has('appointment_id')) {
            return redirect()->route('doctor.appointment.detail', $request->input('appointment_id'))
                ->with('success', 'Medical report created successfully!');
        }

        return redirect()->route('doctor.medical-reports')->with('success', 'Medical report created successfully!');
    }

    public function labResults()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');

        $patients = Patient::orderBy('name')->get();

        $labResults = LabResult::where('doctor_id', $doctorId)
            ->with(['patient'])
            ->orderBy('test_date', 'desc')
            ->paginate(20);

        return view('doctor.lab-results-simple', compact('labResults', 'patients'));
    }

    public function showCreateLabResultForm()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $patients = Patient::whereHas('appointments', function($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->orderBy('name')->get();
        $hasPatients = $patients->count() > 0;
        return view('doctor.lab-result-create', compact('patients', 'hasPatients'));
    }

    public function labResultsSimple()
    {
        return $this->labResults();
    }

    public function createLabResult(CreateLabResultRequest $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $validated = $request->validated();

        $validated['doctor_id'] = session('doctor_id');
        $validated['test_date'] = now();

        LabResult::create($validated);

        // Redirect to appointment detail if appointment_id was provided
        if ($request->has('appointment_id')) {
            return redirect()->route('doctor.appointment.detail', $request->input('appointment_id'))
                ->with('success', 'Lab result created successfully!');
        }

        return redirect()->route('doctor.lab-results')->with('success', 'Lab result created successfully!');
    }

    public function search(Request $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $query = trim($request->input('q', ''));
        if ($query === '') {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        $patients = Patient::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%')
              ->orWhere('phone', 'like', '%' . $query . '%')
              ->orWhere('city', 'like', '%' . $query . '%')
              ->orWhere('state', 'like', '%' . $query . '%');
        })
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN email LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->get();

        $doctors = Doctor::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('specialization', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%')
              ->orWhere('phone', 'like', '%' . $query . '%');
        })
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN specialization LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->get();

        $staff = Staff::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%')
              ->orWhere('phone', 'like', '%' . $query . '%')
              ->orWhere('role', 'like', '%' . $query . '%')
              ->orWhere('department', 'like', '%' . $query . '%');
        })
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN role LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->get();

        return view('doctor.search', compact('patients', 'doctors', 'staff', 'query'));
    }

    public function searchSuggestions(Request $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $query = trim($request->input('q', ''));
        if ($query === '') {
            return response()->json(['suggestions' => []]);
        }

        // Smart search: matches both prefix and partial, prioritizes prefix matches
        $patients = Patient::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%');
        })
        ->select('id', 'name', 'email')
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN email LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return ['id' => $item->id, 'name' => $item->name, 'type' => 'Patient', 'detail' => $item->email];
        });

        $doctors = Doctor::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('specialization', 'like', '%' . $query . '%');
        })
        ->select('id', 'name', 'specialization', 'email')
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN specialization LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return ['id' => $item->id, 'name' => $item->name, 'type' => 'Doctor', 'detail' => $item->specialization ?: $item->email];
        });

        $staff = Staff::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%');
        })
        ->select('id', 'name', 'email')
        ->orderByRaw("CASE WHEN name LIKE ? THEN 0 WHEN email LIKE ? THEN 1 ELSE 2 END", [$query . '%', $query . '%'])
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return ['id' => $item->id, 'name' => $item->name, 'type' => 'Staff', 'detail' => $item->email];
        });

        $suggestions = $patients->merge($doctors)->merge($staff)->take(8);

        return response()->json(['suggestions' => $suggestions]);
    }

    public function updateAvailability(Request $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $doctor = Doctor::findOrFail($doctorId);

        $validated = $request->validate([
            'is_available_today' => 'required|boolean',
            'unavailable_message' => 'nullable|string|max:500'
        ]);

        $validated['availability_date'] = now()->toDateString();

        $doctor->update($validated);

        return redirect()->route('doctor.dashboard')->with('success', 'Availability updated successfully!');
    }

    public function unavailabilityForm()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $doctor = Doctor::findOrFail($doctorId);
        $unavailabilities = $doctor->unavailabilities()->orderBy('unavailable_date', 'desc')->get();

        return view('doctor.mark-unavailable', compact('doctor', 'unavailabilities'));
    }

    public function markUnavailable(Request $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $doctor = Doctor::findOrFail($doctorId);

        $validated = $request->validate([
            'unavailable_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:unavailable_date',
            'reason' => 'nullable|string|max:500'
        ]);

        $unavailabilityService = new \App\Services\UnavailabilityService();
        $result = $unavailabilityService->markUnavailable(
            $doctor,
            $validated['unavailable_date'],
            $validated['end_date'],
            $validated['reason'] ?? null
        );

        // Update immediate doctor availability if today is within the unavailability range
        $today = now()->toDateString();
        $startDate = \Carbon\Carbon::parse($validated['unavailable_date'])->toDateString();
        $endDate = \Carbon\Carbon::parse($validated['end_date'])->toDateString();

        $isUnavailableToday = ($today >= $startDate && $today <= $endDate);

        $doctor->update([
            'is_available_today' => !$isUnavailableToday,
            'unavailable_message' => $validated['reason'] ?? null,
            'availability_date' => now()->toDateString(),
        ]);

        $affectedCount = $result['affected_appointments']->count();
        $message = $affectedCount > 0 
            ? "Marked unavailable! {$affectedCount} appointment(s) marked for rescheduling and notifications sent."
            : "Marked unavailable successfully!";

        return redirect()->route('doctor.unavailable.form')->with('success', $message);
    }

    public function removeUnavailability($unavailabilityId)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $unavailability = \App\Models\DoctorUnavailability::where('doctor_id', $doctorId)
            ->findOrFail($unavailabilityId);

        $unavailabilityService = new \App\Services\UnavailabilityService();
        $unavailabilityService->cancelUnavailability($unavailability);

        return redirect()->route('doctor.unavailable.form')->with('success', 'Unavailability removed!');
    }

    /**
     * Doctor Profile & Password Management
     */
    public function profile()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctor = Doctor::findOrFail(session('doctor_id'));
        return view('doctor.profile', compact('doctor'));
    }

    public function showChangePasswordForm()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        return view('doctor.change-password');
    }

    public function changePassword(Request $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $doctor = Doctor::findOrFail(session('doctor_id'));

        // Verify current password
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $doctor->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Validate new password complexity
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $request->new_password)) {
            return back()->withErrors(['new_password' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long.']);
        }

        // Update password
        $doctor->update(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)]);

        return redirect()->route('doctor.change-password')->with('success', 'Password changed successfully!');
    }

    // ============ SCHEDULE MANAGEMENT ============

    /**
     * Display doctor's schedule list
     */
    public function scheduleList()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->orderBy('day_of_week')
            ->paginate(15);

        // Map day names for display
        $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('doctor.schedule-list', compact('schedules', 'dayNames'));
    }

    /**
     * Show create schedule form
     */
    public function createSchedule()
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('doctor.schedule-create', compact('days'));
    }

    /**
     * Store a new schedule
     */
    public function storeSchedule(Request $request)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'is_active' => 'nullable|boolean',
        ]);

        $doctorId = session('doctor_id');

        // Validate schedule
        $errors = \App\Services\ScheduleValidationService::validateSchedule(
            $doctorId,
            $request->day_of_week,
            $request->start_time,
            $request->end_time,
            $request->break_start,
            $request->break_end
        );

        if (!empty($errors)) {
            return back()->withInput()->withErrors(['validation' => implode(' ', $errors)]);
        }

        DoctorSchedule::create([
            'doctor_id' => $doctorId,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('doctor.schedule-list')->with('success', 'Schedule created successfully!');
    }

    /**
     * Show edit schedule form
     */
    public function editSchedule($id)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $schedule = DoctorSchedule::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('doctor.schedule-edit', compact('schedule', 'days'));
    }

    /**
     * Update schedule
     */
    public function updateSchedule(Request $request, $id)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $schedule = DoctorSchedule::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'is_active' => 'nullable|boolean',
        ]);

        // Validate schedule (excluding current schedule from overlap check)
        $errors = \App\Services\ScheduleValidationService::validateSchedule(
            $doctorId,
            $request->day_of_week,
            $request->start_time,
            $request->end_time,
            $request->break_start,
            $request->break_end,
            $schedule->id
        );

        if (!empty($errors)) {
            return back()->withInput()->withErrors(['validation' => implode(' ', $errors)]);
        }

        $schedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_start' => $request->break_start,
            'break_end' => $request->break_end,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('doctor.schedule-list')->with('success', 'Schedule updated successfully!');
    }

    /**
     * Delete schedule
     */
    public function deleteSchedule($id)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $schedule = DoctorSchedule::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $schedule->delete();

        return redirect()->route('doctor.schedule-list')->with('success', 'Schedule deleted successfully!');
    }

    /**
     * Toggle schedule active/inactive status
     */
    public function toggleScheduleStatus($id)
    {
        if (!session('doctor_logged_in')) {
            return redirect()->route('doctor.login');
        }

        $doctorId = session('doctor_id');
        $schedule = DoctorSchedule::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $schedule->update(['is_active' => !$schedule->is_active]);

        $message = $schedule->is_active ? 'Schedule activated successfully!' : 'Schedule deactivated successfully!';
        return redirect()->route('doctor.schedule-list')->with('success', $message);
    }
}