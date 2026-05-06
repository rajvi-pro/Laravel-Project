<?php

namespace App\Services;

use App\Models\DoctorSchedule;
use Carbon\Carbon;

class ScheduleValidationService
{
    /**
     * Validate that no time slots overlap for the doctor on a given day
     */
    public static function validateNoOverlap($doctorId, $dayOfWeek, $startTime, $endTime, $excludeId = null)
    {
        $query = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $overlappingSchedules = $query->get();

        foreach ($overlappingSchedules as $schedule) {
            // Check if time slots overlap
            if ($this->timesOverlap($startTime, $endTime, $schedule->start_time, $schedule->end_time)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if two time ranges overlap
     */
    private function timesOverlap($start1, $end1, $start2, $end2)
    {
        $start1 = strtotime($start1);
        $end1 = strtotime($end1);
        $start2 = strtotime($start2);
        $end2 = strtotime($end2);

        return !($end1 <= $start2 || $end2 <= $start1);
    }

    /**
     * Validate that end time is after start time
     */
    public static function validateTimeRange($startTime, $endTime)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);

        if ($end <= $start) {
            return false;
        }

        return true;
    }

    /**
     * Validate that break times are within the working hours
     */
    public static function validateBreakTimes($startTime, $endTime, $breakStart = null, $breakEnd = null)
    {
        if (!$breakStart || !$breakEnd) {
            return true; // Break times are optional
        }

        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $bStart = strtotime($breakStart);
        $bEnd = strtotime($breakEnd);

        // Break start and end must be within working hours
        if ($bStart < $start || $bEnd > $end) {
            return false;
        }

        // Break end must be after break start
        if ($bEnd <= $bStart) {
            return false;
        }

        return true;
    }

    /**
     * Validate that schedule is set for future dates only (for date-specific schedules)
     */
    public static function validateFutureDate($dateString)
    {
        // If day_of_week (like 'monday'), it's always valid
        if (in_array(strtolower($dateString), ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])) {
            return true;
        }

        // If it's a specific date, check it's in the future
        try {
            $date = Carbon::createFromFormat('Y-m-d', $dateString);
            return $date->isAfter(Carbon::now());
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all validation errors for a schedule
     */
    public static function validateSchedule($doctorId, $dayOfWeek, $startTime, $endTime, $breakStart = null, $breakEnd = null, $excludeId = null)
    {
        $errors = [];

        // Validate time range
        if (!self::validateTimeRange($startTime, $endTime)) {
            $errors[] = 'End time must be after start time.';
        }

        // Validate break times
        if (!self::validateBreakTimes($startTime, $endTime, $breakStart, $breakEnd)) {
            $errors[] = 'Break times must be within working hours and break end must be after break start.';
        }

        // Validate future date
        if (!self::validateFutureDate($dayOfWeek)) {
            $errors[] = 'Schedule must be set for current or future dates only.';
        }

        // Validate no overlaps
        if (!self::validateNoOverlap($doctorId, $dayOfWeek, $startTime, $endTime, $excludeId)) {
            $errors[] = 'This time slot overlaps with an existing schedule.';
        }

        return $errors;
    }
}
