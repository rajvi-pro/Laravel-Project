<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class AppointmentService
{
    public function getAppointmentsByDoctor($doctorId): Collection
    {
        return Appointment::where('doctor_id', $doctorId)
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->get();
    }

    public function getAppointmentsByPatient($patientId): Collection
    {
        return Appointment::where('patient_id', $patientId)
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->get();
    }

    public function getUpcomingAppointments($doctorId, $days = 7): Collection
    {
        return Appointment::where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [now(), now()->addDays($days)])
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date')
            ->get();
    }

    public function createAppointment(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function updateAppointment(Appointment $appointment, array $data): Appointment
    {
        $appointment->update($data);
        return $appointment;
    }

    public function cancelAppointment(Appointment $appointment): bool
    {
        return $appointment->update(['status' => 'cancelled']);
    }

    public function getAvailableSlots($doctorId, $date): array
    {
        $bookedAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->toArray();

        // Check if doctor has a schedule for this date
        $dateObj = Carbon::parse($date);
        $dayOfWeek = $dateObj->format('N'); // 1-7 (Monday=1, Sunday=7)
        $dayName = strtolower($dateObj->format('l')); // 'monday', 'tuesday', etc. (for backwards compatibility)
        
        $doctorSchedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where(function($query) use ($dayOfWeek, $dayName) {
                // Support both integer (1-7) and string ('monday') day formats
                $query->where('day_of_week', $dayOfWeek)
                      ->orWhere('day_of_week', $dayName);
            })
            ->where('is_active', true)
            ->first();

        // If doctor has no schedule for this day, return empty slots
        if (!$doctorSchedule) {
            return [];
        }

        // All available slots
        $allSlots = [
            ['time' => '09:00', 'display' => '9:00 AM - 10:00 AM'],
            ['time' => '10:00', 'display' => '10:00 AM - 11:00 AM'],
            ['time' => '11:00', 'display' => '11:00 AM - 12:00 PM'],
            ['time' => '12:00', 'display' => 'BREAK (12:00 PM - 2:00 PM)', 'disabled' => true],
            ['time' => '14:00', 'display' => '2:00 PM - 3:00 PM'],
            ['time' => '15:00', 'display' => '3:00 PM - 4:00 PM'],
            ['time' => '16:00', 'display' => '4:00 PM - 5:00 PM'],
            ['time' => '17:00', 'display' => '5:00 PM - 6:00 PM'],
            ['time' => '18:00', 'display' => '6:00 PM - 7:00 PM']
        ];

        // Filter slots based on doctor's schedule
        $slots = [];
        foreach ($allSlots as $slot) {
            // Skip disabled slots (breaks)
            if (isset($slot['disabled']) && $slot['disabled']) {
                continue;
            }

            // Check if slot time is within doctor's working hours
            $slotTime = $slot['time'];
            if (!$this->isSlotWithinWorkingHours($slotTime, $doctorSchedule)) {
                continue;
            }

            // Check if slot is already booked
            if (in_array($slotTime, $bookedAppointments)) {
                continue;
            }

            $slots[] = $slot;
        }

        return $slots;
    }

    /**
     * Check if a time slot falls within doctor's working hours and not during breaks
     */
    private function isSlotWithinWorkingHours($slotTime, $doctorSchedule): bool
    {
        $slotHour = intval(substr($slotTime, 0, 2));
        $slotMinute = intval(substr($slotTime, 3, 2));
        
        // Get doctor's working hours
        $startTime = $doctorSchedule->start_time;
        $endTime = $doctorSchedule->end_time;
        $breakStart = $doctorSchedule->break_start;
        $breakEnd = $doctorSchedule->break_end;

        // Convert to comparable format (hour as decimal)
        $slotDecimal = $slotHour + ($slotMinute / 60);
        $startDecimal = intval(substr($startTime, 0, 2)) + (intval(substr($startTime, 3, 2)) / 60);
        $endDecimal = intval(substr($endTime, 0, 2)) + (intval(substr($endTime, 3, 2)) / 60);
        
        // Check if slot is within working hours
        if ($slotDecimal < $startDecimal || $slotDecimal >= $endDecimal) {
            return false;
        }

        // Check if slot falls during break
        if ($breakStart && $breakEnd) {
            $breakStartDecimal = intval(substr($breakStart, 0, 2)) + (intval(substr($breakStart, 3, 2)) / 60);
            $breakEndDecimal = intval(substr($breakEnd, 0, 2)) + (intval(substr($breakEnd, 3, 2)) / 60);
            
            if ($slotDecimal >= $breakStartDecimal && $slotDecimal < $breakEndDecimal) {
                return false;
            }
        }

        return true;
    }

}