<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\DoctorUnavailability;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\Carbon;

class UnavailabilityService
{
    /**
     * Mark doctor as unavailable and handle affected appointments
     */
    public function markUnavailable(Doctor $doctor, $startDate, $endDate, $reason = null)
    {
        // Create unavailability record
        $unavailability = DoctorUnavailability::create([
            'doctor_id' => $doctor->id,
            'unavailable_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $reason
        ]);

        // Find affected appointments
        $affectedAppointments = $this->findAffectedAppointments($doctor, $unavailability);

        // Update affected appointments and send notifications
        foreach ($affectedAppointments as $appointment) {
            $appointment->update(['status' => 'needs_reschedule']);

            // Send notifications
            $this->sendRescheduleNotifications($appointment, $doctor, $reason);
        }

        return [
            'unavailability' => $unavailability,
            'affected_appointments' => $affectedAppointments
        ];
    }

    /**
     * Find appointments affected by unavailability (date range based)
     */
    public function findAffectedAppointments(Doctor $doctor, DoctorUnavailability $unavailability)
    {
        $startDate = $unavailability->unavailable_date->toDateString();
        $endDate = ($unavailability->end_date ?? $unavailability->unavailable_date)->toDateString();

        $query = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed');

        return $query->get();
    }

    /**
     * Send notifications to Admin, Staff, and Patient
     */
    public function sendRescheduleNotifications(Appointment $appointment, Doctor $doctor, $reason = null)
    {
        // Notify Admin
        $admins = Admin::all();
        foreach ($admins as $admin) {
            Notification::create([
                'notifiable_type' => Admin::class,
                'notifiable_id' => $admin->id,
                'type' => 'doctor_unavailable',
                'title' => 'Doctor Unavailable',
                'message' => "Dr. {$doctor->name} is unavailable on {$appointment->appointment_date->format('d M Y')} at {$appointment->appointment_time}. Patient {$appointment->patient->name}'s appointment needs rescheduling." . ($reason ? " Reason: {$reason}" : ""),
                'related_type' => Appointment::class,
                'related_id' => $appointment->id
            ]);
        }

        // Notify Staff
        $staffMembers = Staff::all();
        foreach ($staffMembers as $staff) {
            Notification::create([
                'notifiable_type' => Staff::class,
                'notifiable_id' => $staff->id,
                'type' => 'doctor_unavailable',
                'title' => 'Doctor Unavailable',
                'message' => "Dr. {$doctor->name} is unavailable on {$appointment->appointment_date->format('d M Y')} at {$appointment->appointment_time}. Patient {$appointment->patient->name}'s appointment needs rescheduling." . ($reason ? " Reason: {$reason}" : ""),
                'related_type' => Appointment::class,
                'related_id' => $appointment->id
            ]);
        }

        // Notify Patient
        Notification::create([
            'notifiable_type' => 'App\Models\Patient',
            'notifiable_id' => $appointment->patient_id,
            'type' => 'appointment_reschedule',
            'title' => 'Appointment Needs Rescheduling',
            'message' => "Dear {$appointment->patient->name}, Dr. {$doctor->name} is unavailable on {$appointment->appointment_date->format('d M Y')} at {$appointment->appointment_time}. Your appointment has been marked for rescheduling. Please contact us to reschedule, cancel, or choose another doctor." . ($reason ? " Reason for unavailability: {$reason}" : ""),
            'related_type' => Appointment::class,
            'related_id' => $appointment->id
        ]);
    }

    /**
     * Cancel unavailability and restore appointments if needed
     */
    public function cancelUnavailability(DoctorUnavailability $unavailability)
    {
        $unavailability->delete();
        // Note: Appointments marked as 'needs_reschedule' are not automatically restored
        // Admin/Staff must manually restore them if the unavailability is cancelled
        return true;
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($notifiable)
    {
        return $notifiable->notifications()
            ->whereNull('read_at')
            ->count();
    }
}
