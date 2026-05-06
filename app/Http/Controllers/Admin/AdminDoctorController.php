<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDoctorController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $doctors = Doctor::withCount('appointments')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        return view('admin.doctors.create');
    }

    public function store(CreateDoctorRequest $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validated();

        // Manual password validation for security
        $password = $validated['password'];
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            return back()->withErrors(['password' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long.'])->withInput();
        }

        $validated['password'] = Hash::make($validated['password']);
        $doctor = Doctor::create($validated);

        // Handle schedule data from form
        $schedules = $request->input('schedules', []);
        if ($schedules && is_array($schedules) && count($schedules) > 0) {
            // Use provided schedules instead of default
            foreach ($schedules as $schedule) {
                if ($schedule['day_of_week'] && $schedule['start_time'] && $schedule['end_time']) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $schedule['day_of_week'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                        'break_start' => $schedule['break_start'] ?? null,
                        'break_end' => $schedule['break_end'] ?? null,
                        'is_active' => isset($schedule['is_active']) ? true : false,
                    ]);
                }
            }
        } else {
            // Create default schedule if none provided
            $this->createDefaultSchedule($doctor->id);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully! Schedule configured.');
    }

    public function show($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $doctor = Doctor::with('schedules')->withTrashed()->findOrFail($id);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Prevent doctor from editing their own profile
        if (session('doctor_logged_in') && session('doctor_id') == $id) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Doctors are not allowed to edit their own profile. Only admin can make changes.');
        }

        $doctor = Doctor::with('schedules')->withTrashed()->findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(UpdateDoctorRequest $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Prevent doctor from editing their own profile
        if (session('doctor_logged_in') && session('doctor_id') == $id) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Doctors are not allowed to edit their own profile. Only admin can make changes.');
        }

        $validated = $request->validated();

        // Handle password reset if new password is provided
        if (!empty($request->input('new_password'))) {
            $newPassword = $request->input('new_password');
            $confirmPassword = $request->input('new_password_confirmation');
            
            // Manual password validation
            if ($newPassword !== $confirmPassword) {
                return back()->withErrors(['new_password_confirmation' => 'Passwords do not match.'])->withInput();
            }
            
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)) {
                return back()->withErrors(['new_password' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long.'])->withInput();
            }
            
            $validated['password'] = Hash::make($newPassword);
        }

        $doctor = Doctor::withTrashed()->findOrFail($id);
        $doctor->update($validated);

        // Handle schedule updates
        $schedules = $request->input('schedules', []);
        if ($schedules && is_array($schedules)) {
            // Delete existing schedules first
            DoctorSchedule::where('doctor_id', $doctor->id)->delete();
            
            // Create/update schedules from form
            foreach ($schedules as $schedule) {
                if ($schedule['day_of_week'] && $schedule['start_time'] && $schedule['end_time']) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $schedule['day_of_week'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                        'break_start' => $schedule['break_start'] ?? null,
                        'break_end' => $schedule['break_end'] ?? null,
                        'is_active' => isset($schedule['is_active']) ? true : false,
                    ]);
                }
            }
        } else {
            // Ensure schedule exists if none provided
            $this->ensureScheduleExists($doctor->id);
        }

        return redirect()->route('admin.doctors.show', $doctor->id)->with('success', 'Doctor updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $doctor = Doctor::findOrFail($id);
            $doctorName = $doctor->name;
            $doctor->delete();
            return redirect()->route('admin.doctors.index')->with('success', "Doctor '{$doctorName}' deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.doctors.index')->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }

    /**
     * Create default working schedule for a doctor (all 7 days, 9 AM - 7 PM with break)
     */
    private function createDefaultSchedule($doctorId)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            DoctorSchedule::firstOrCreate(
                [
                    'doctor_id' => $doctorId,
                    'day_of_week' => $day,
                ],
                [
                    'start_time' => '09:00',
                    'end_time' => '19:00',
                    'break_start' => '12:00',
                    'break_end' => '14:00',
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Ensure schedule exists for doctor (create missing days)
     */
    private function ensureScheduleExists($doctorId)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $existingDays = DoctorSchedule::where('doctor_id', $doctorId)
            ->pluck('day_of_week')
            ->map(fn($day) => strtolower($day))
            ->toArray();

        foreach ($days as $day) {
            if (!in_array($day, $existingDays)) {
                DoctorSchedule::create([
                    'doctor_id' => $doctorId,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '19:00',
                    'break_start' => '12:00',
                    'break_end' => '14:00',
                    'is_active' => true,
                ]);
            }
        }
    }
}