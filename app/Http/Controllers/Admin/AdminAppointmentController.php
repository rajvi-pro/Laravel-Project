<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    protected $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointments = Appointment::with(['patient','doctor'])->orderBy('appointment_date','desc')->paginate(20);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function show($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointment = Appointment::withTrashed()->with(['patient','doctor'])->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointment = Appointment::with(['patient','doctor'])->findOrFail($id);
        // Don't allow rescheduling of completed or cancelled appointments
        if ($appointment->status === 'completed' || $appointment->status === 'cancelled') {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Cannot reschedule a ' . $appointment->status . ' appointment.');
        }
        $availableSlots = $this->service->getAvailableSlots($appointment->doctor_id, $appointment->appointment_date);
        return view('admin.appointments.edit', compact('appointment', 'availableSlots'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $appointment = Appointment::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $data = [
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
        ];

        // Mark as rescheduled when edited by admin
        if ($appointment->status !== 'cancelled' && $appointment->status !== 'completed') {
            $data['status'] = 'rescheduled';
        }

        $this->service->updateAppointment($appointment, $data);

        return redirect()->route('admin.appointments.edit', $appointment->id)
            ->with('success', 'Appointment rescheduled successfully!')
            ->with('reschedule_redirect', true);
    }

    public function getAvailableSlots($doctorId, $date)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $slots = $this->service->getAvailableSlots($doctorId, $date);
            return response()->json(['success' => true, 'slots' => $slots]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
