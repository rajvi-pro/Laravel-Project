<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\DoctorUnavailability;
use App\Models\DoctorSchedule;
use App\Models\Notification;
use App\Services\ScheduleValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Get date range from request or use current month (default 1-month view)
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();

        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalStaff = Staff::count();
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $availableDoctors = Doctor::where('is_available_today', true)->count();
        $pendingAppointments = Appointment::where('status', 'scheduled')->count();
        
        // Revenue Data
        $totalRevenue = Billing::where('payment_status', 'paid')
            ->whereBetween('billing_date', [$startDate, $endDate])
            ->sum('amount');
        $pendingAmount = Billing::where('payment_status', 'pending')
            ->whereBetween('billing_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->limit(5)
            ->get();

        // Daily Revenue Chart Data - shows 1 month only
        $dailyRevenueRecords = DB::table('billings')
            ->selectRaw('DATE_FORMAT(billing_date, "%Y-%m-%d") as date, DATE_FORMAT(billing_date, "%d") as day, SUM(amount) as revenue')
            ->where('payment_status', 'paid')
            ->whereBetween('billing_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE_FORMAT(billing_date, "%Y-%m-%d")'), DB::raw('DATE_FORMAT(billing_date, "%d")'))
            ->orderBy('date')
            ->get();

        // Create array for all days in the range
        $days = collect();
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $days->push((object)[
                'date' => $current->format('Y-m-d'),
                'day' => $current->format('d'),
                'revenue' => 0,
            ]);
            $current->addDay();
        }

        $monthlyRevenue = $days->map(function ($day) use ($dailyRevenueRecords) {
            $match = $dailyRevenueRecords->firstWhere('date', $day->date);
            if ($match) {
                $day->revenue = (float) $match->revenue;
            }
            return $day;
        });

        // If there is no data yet, use demo data for visual graph
        if ($monthlyRevenue->sum('revenue') == 0) {
            $demoCount = $monthlyRevenue->count();
            $demoData = array_fill(0, $demoCount, rand(300, 800));
            $monthlyRevenue = $monthlyRevenue->map(function ($day, $idx) use ($demoData) {
                $day->revenue = $demoData[$idx] ?? 0;
                return $day;
            });
        }


        // Appointment Status Chart Data
        $appointmentStatus = DB::table('appointments')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        if (empty($appointmentStatus)) {
            $appointmentStatus = [
                'completed' => 8,
                'scheduled' => 14,
                'cancelled' => 2,
                'rescheduled' => 1,
            ];
        }

        // Staff Distribution by Role
        $staffDistribution = DB::table('staff')
            ->selectRaw('role, COUNT(*) as count')
            ->whereNotNull('role')
            ->groupBy('role')
            ->get();

        if ($staffDistribution->isEmpty()) {
            $staffDistribution = collect([
                (object)['role' => 'Lab Technician', 'count' => 4],
                (object)['role' => 'Nurse', 'count' => 4],
                (object)['role' => 'Pharmacist', 'count' => 3],
                (object)['role' => 'Receptionist', 'count' => 2],
            ]);
        }

        // If this is still one-item placeholder, keep it simple.
        if ($staffDistribution->count() == 1 && $staffDistribution->first()->role === 'Unspecified') {
            $staffDistribution = collect([
                (object)['role' => 'Unspecified', 'count' => 1]
            ]);
        }

        // Operational Metrics
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $scheduledAppointments = Appointment::where('status', 'scheduled')->count();
        $cancelledAppointments = Appointment::where('status', 'cancelled')->count();
        $totalAppointmentsCount = Appointment::count();
        
        $appointmentCompletionRate = $totalAppointmentsCount > 0 
            ? round(($completedAppointments / $totalAppointmentsCount) * 100, 2) 
            : 0;

        $avgDoctorsPerDay = $totalAppointments > 0 
            ? round($totalDoctors / max(1, $totalAppointments / 30), 2) 
            : 0;

        if ($appointmentCompletionRate == 0 && $totalAppointmentsCount == 0) {
            // demo statuses for analytics if empty
            $appointmentCompletionRate = 85;
            $totalAppointments = 25;
            $scheduledAppointments = 15;
            $completedAppointments = 8;
            $cancelledAppointments = 2;
            $avgDoctorsPerDay = 72;
        }


        $newPatientsThisMonth = Patient::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Doctor unavailability alert is now manual (not database-driven)
        // Set your manual alert messages here if needed
        $unavailableDoctors = [];

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'totalStaff',
            'totalAppointments',
            'todayAppointments',
            'availableDoctors',
            'pendingAppointments',
            'totalRevenue',
            'pendingAmount',
            'recentAppointments',
            'monthlyRevenue',
            'appointmentStatus',
            'staffDistribution',
            'completedAppointments',
            'scheduledAppointments',
            'cancelledAppointments',
            'appointmentCompletionRate',
            'avgDoctorsPerDay',
            'newPatientsThisMonth',
            'unavailableDoctors',
            'startDate',
            'endDate'
        ));
    }

    public function search(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
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

        return view('admin.search', compact('patients', 'doctors', 'staff', 'query'));
    }

    public function searchSuggestions(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
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

    public function unavailableDoctors()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $unavailabilities = \App\Models\DoctorUnavailability::with('doctor')
            ->orderBy('unavailable_date', 'desc')
            ->paginate(15);

        return view('admin.unavailable-doctors', compact('unavailabilities'));
    }

    public function affectedAppointments($unavailabilityId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $unavailability = \App\Models\DoctorUnavailability::with('doctor')->findOrFail($unavailabilityId);
        
        $unavailabilityService = new \App\Services\UnavailabilityService();
        $affectedAppointments = $unavailabilityService->findAffectedAppointments(
            $unavailability->doctor,
            $unavailability
        );

        return view('admin.affected-appointments', compact('unavailability', 'affectedAppointments'));
    }

    public function rescheduleAppointment(Request $request, $appointmentId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointment = Appointment::findOrFail($appointmentId);

        $validated = $request->validate([
            'new_date' => 'required|date|after_or_equal:today',
            'new_time' => 'required|date_format:H:i',
            'new_doctor_id' => 'nullable|exists:doctors,id'
        ]);

        if ($request->filled('new_doctor_id')) {
            $appointment->update([
                'doctor_id' => $validated['new_doctor_id'],
                'appointment_date' => $validated['new_date'],
                'appointment_time' => $validated['new_time'],
                'status' => 'scheduled'
            ]);
        } else {
            $appointment->update([
                'appointment_date' => $validated['new_date'],
                'appointment_time' => $validated['new_time'],
                'status' => 'scheduled'
            ]);
        }

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

        return redirect()->route('admin.affected-appointments', $appointment->id)->with('success', 'Appointment rescheduled successfully!');
    }

    public function exportExcel(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();

        // Fetch data for Excel export
        $patients = Patient::all();
        $doctors = Doctor::all();
        $staff = Staff::all();
        $billings = Billing::whereBetween('billing_date', [$startDate, $endDate])->get();
        $appointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->get();

        // Calculate metrics
        $paidRevenue = $billings->where('payment_status', 'paid')->sum('amount');
        $pendingRevenue = $billings->where('payment_status', 'pending')->sum('amount');
        $totalRevenue = $paidRevenue + $pendingRevenue;
        $completedAppointments = $appointments->where('status', 'completed')->count();
        $scheduledAppointments = $appointments->where('status', 'scheduled')->count();
        $cancelledAppointments = $appointments->where('status', 'cancelled')->count();

        // Create professional CSV content with better formatting
        $csvContent = "HMS - HOSPITAL MANAGEMENT SYSTEM\n";
        $csvContent .= "DASHBOARD REPORT\n";
        $csvContent .= "Report Period: " . $startDate->format('d M Y') . " to " . $endDate->format('d M Y') . "\n";
        $csvContent .= "Generated on: " . now()->format('d M Y \a\t H:i:s') . "\n";
        $csvContent .= "=========================================================================\n\n";

        // EXECUTIVE SUMMARY
        $csvContent .= "EXECUTIVE SUMMARY,VALUE\n";
        $csvContent .= "Total Patients," . $patients->count() . "\n";
        $csvContent .= "Total Doctors," . $doctors->count() . "\n";
        $csvContent .= "Total Staff," . $staff->count() . "\n";
        $csvContent .= "Total Appointments (Period)," . $appointments->count() . "\n";
        $csvContent .= "Completed Appointments," . $completedAppointments . "\n";
        $csvContent .= "Scheduled Appointments," . $scheduledAppointments . "\n";
        $csvContent .= "Cancelled Appointments," . $cancelledAppointments . "\n";
        $csvContent .= ",,\n";
        $csvContent .= "FINANCIAL SUMMARY,AMOUNT (₹)\n";
        $csvContent .= "Paid Revenue," . number_format($paidRevenue, 2) . "\n";
        $csvContent .= "Pending Revenue," . number_format($pendingRevenue, 2) . "\n";
        $csvContent .= "Total Revenue," . number_format($totalRevenue, 2) . "\n";
        $csvContent .= "Total Billing Records," . $billings->count() . "\n";
        $csvContent .= "\n\n";

        // STAFF DISTRIBUTION
        $staffByRole = $staff->groupBy('role')->map(function ($group) {
            return $group->count();
        });
        $csvContent .= "STAFF DISTRIBUTION BY ROLE,COUNT\n";
        foreach ($staffByRole as $role => $count) {
            $csvContent .= "\"" . ($role ?? 'Unassigned') . "\"," . $count . "\n";
        }
        $csvContent .= "\n\n";

        // PATIENTS SECTION
        $csvContent .= "PATIENTS DATABASE,,,\n";
        $csvContent .= "ID,Name,Email,Phone,City/State,Date Registered\n";
        foreach ($patients as $patient) {
            $regDate = $patient->created_at ? Carbon::parse($patient->created_at)->format('d M Y') : 'N/A';
            $csvContent .= "{$patient->id},\"" . $patient->name . "\",\"" . $patient->email . "\",\"" . $patient->phone . "\",\"" . $patient->city . ", " . $patient->state . "\",\"" . $regDate . "\"\n";
        }
        $csvContent .= "\n\n";

        // DOCTORS SECTION
        $csvContent .= "DOCTORS DATABASE,,,\n";
        $csvContent .= "ID,Name,Specialization,Qualification,Email,Phone,Consultation Fee (₹),License\n";
        foreach ($doctors as $doctor) {
            $csvContent .= "{$doctor->id},\"" . $doctor->name . "\",\"" . ($doctor->specialization ?? 'N/A') . "\",\"" . ($doctor->qualification ?? 'N/A') . "\",\"" . $doctor->email . "\",\"" . $doctor->phone . "\",\"" . ($doctor->consultation_fee ?? 0) . "\",\"" . ($doctor->license ?? 'N/A') . "\"\n";
        }
        $csvContent .= "\n\n";

        // STAFF SECTION
        $csvContent .= "STAFF DATABASE,,,\n";
        $csvContent .= "ID,Name,Department,Role,Email,Phone,Status\n";
        foreach ($staff as $member) {
            $csvContent .= "{$member->id},\"" . $member->name . "\",\"" . ($member->department ?? 'N/A') . "\",\"" . ($member->role ?? 'N/A') . "\",\"" . $member->email . "\",\"" . $member->phone . "\",\"Active\"\n";
        }
        $csvContent .= "\n\n";

        // BILLING RECORDS SECTION
        $csvContent .= "BILLING RECORDS,,,\n";
        $csvContent .= "Invoice #,Patient Name,Doctor Name,Amount (₹),Tax (₹),Total (₹),Payment Status,Billing Date,Notes\n";
        foreach ($billings as $billing) {
            $patientName = $billing->patient->name ?? 'N/A';
            $doctorName = $billing->appointment && $billing->appointment->doctor ? $billing->appointment->doctor->name : 'N/A';
            $tax = $billing->total_tax ?? 0;
            $billingDate = $billing->billing_date ? Carbon::parse($billing->billing_date)->format('d M Y') : 'N/A';
            $csvContent .= "\"INV-" . str_pad($billing->id, 6, '0', STR_PAD_LEFT) . "\",\"$patientName\",\"$doctorName\",\"" . number_format($billing->amount, 2) . "\",\"" . number_format($tax, 2) . "\",\"" . number_format(($billing->amount + $tax), 2) . "\",\"" . ucfirst($billing->payment_status) . "\",\"" . $billingDate . "\",\"" . ($billing->notes ?? '') . "\"\n";
        }
        $csvContent .= "\n\n";

        // APPOINTMENTS SECTION (if any in period)
        if ($appointments->count() > 0) {
            $csvContent .= "APPOINTMENTS (PERIOD),,,\n";
            $csvContent .= "ID,Patient,Doctor,Date,Time,Status,Reason\n";
            foreach ($appointments->take(100) as $appointment) {
                $appointmentDate = Carbon::parse($appointment->appointment_date)->format('d M Y');
                $csvContent .= "{$appointment->id},\"" . ($appointment->patient->name ?? 'N/A') . "\",\"" . ($appointment->doctor->name ?? 'N/A') . "\",\"" . $appointmentDate . "\",\"" . $appointment->appointment_time . "\",\"" . ucfirst($appointment->status) . "\",\"" . ($appointment->reason ?? '') . "\"\n";
            }
            if ($appointments->count() > 100) {
                $csvContent .= "** Showing 100 of " . $appointments->count() . " appointments **\n";
            }
        }

        // Footer
        $csvContent .= "\n\nEND OF REPORT\n";
        $csvContent .= "For inquiries, contact: billing@hms.local\n";
        $csvContent .= "System: Hospital Management System (HMS)\n";

        // Generate filename
        $filename = 'HMS_Dashboard_Report_' . $startDate->format('d_M_Y') . '_to_' . $endDate->format('d_M_Y') . '.csv';

        // Return as download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportPdf(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : now();

        // Fetch dashboard data for PDF
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $totalStaff = Staff::count();
        $appointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])->get();
        $billings = Billing::whereBetween('billing_date', [$startDate, $endDate])->get();
        
        $paidRevenue = $billings->where('payment_status', 'paid')->sum('amount');
        $pendingRevenue = $billings->where('payment_status', 'pending')->sum('amount');

        // Return PDF view with proper Carbon objects
        return view('admin.dashboard-pdf', compact(
            'totalPatients',
            'totalDoctors',
            'totalStaff',
            'appointments',
            'billings',
            'paidRevenue',
            'pendingRevenue',
            'startDate',
            'endDate'
        ));
    }

    // ============ DOCTOR SCHEDULE MANAGEMENT (READ-ONLY) ============

    /**
     * View all doctors' schedules (read-only admin view)
     */
    public function viewSchedules(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $doctors = Doctor::all();
        $selectedDoctorId = $request->get('doctor_id');
        $schedules = null;
        $selectedDoctor = null;

        if ($selectedDoctorId) {
            $selectedDoctor = Doctor::findOrFail($selectedDoctorId);
            $schedules = DoctorSchedule::where('doctor_id', $selectedDoctorId)
                ->orderBy('day_of_week')
                ->get();
        }

        $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.doctor-schedules', compact('doctors', 'schedules', 'selectedDoctor', 'dayNames'));
    }

    /**
     * View detailed schedule for a specific doctor (read-only)
     */
    public function viewDoctorSchedule($doctorId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $doctor = Doctor::findOrFail($doctorId);
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->orderBy('day_of_week')
            ->get();

        $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        // Count total appointments for this doctor
        $appointmentCount = Appointment::where('doctor_id', $doctorId)->count();
        $upcomingAppointmentCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', '>=', now())
            ->count();

        return view('admin.doctor-schedule-detail', compact(
            'doctor',
            'schedules',
            'dayNames',
            'appointmentCount',
            'upcomingAppointmentCount'
        ));
    }

    /**
     * Show edit form for a schedule (admin can edit)
     */
    public function editSchedule($scheduleId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $schedule = DoctorSchedule::findOrFail($scheduleId);
        $doctor = $schedule->doctor;
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        return view('admin.schedule-edit', compact('schedule', 'doctor', 'days'));
    }

    /**
     * Update schedule (admin can edit)
     */
    public function updateSchedule(Request $request, $scheduleId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $schedule = DoctorSchedule::findOrFail($scheduleId);

        $request->validate([
            'day_of_week' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'is_active' => 'nullable|boolean',
        ]);

        // Validate schedule using service (excluding current schedule)
        $errors = \App\Services\ScheduleValidationService::validateSchedule(
            $schedule->doctor_id,
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

        return redirect()->route('admin.doctors.show', $schedule->doctor_id)
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Delete schedule (admin can delete)
     */
    public function deleteSchedule($scheduleId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $schedule = DoctorSchedule::findOrFail($scheduleId);
        $doctorId = $schedule->doctor_id;
        $schedule->delete();

        return redirect()->route('admin.doctors.show', $doctorId)
            ->with('success', 'Schedule deleted successfully!');
    }
}