<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        // Get all doctors
        $doctors = Doctor::all();

        foreach ($doctors as $index => $doctor) {
            // Check if schedules already exist for this doctor
            $existingCount = DoctorSchedule::where('doctor_id', $doctor->id)->count();
            
            if ($existingCount > 0) {
                $this->command->info("Skipping doctor {$doctor->id} - already has {$existingCount} schedules");
                continue;
            }

            // Assign different schedules based on doctor index to show variability
            if ($index % 3 == 0) {
                // Morning shift: 9 AM to 12 PM (3 hours)
                foreach ($days as $day) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                        'start_time' => '09:00',
                        'end_time' => '12:00',
                        'break_start' => null,
                        'break_end' => null,
                        'is_active' => true
                    ]);
                }
                $this->command->info("Created morning shift for doctor {$doctor->id} ({$doctor->name})");
            } elseif ($index % 3 == 1) {
                // Afternoon shift: 2 PM to 7 PM (5 hours) with 2:30 PM break
                foreach ($days as $day) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                        'start_time' => '14:00',
                        'end_time' => '19:00',
                        'break_start' => '14:30',
                        'break_end' => '15:00',
                        'is_active' => true
                    ]);
                }
                $this->command->info("Created afternoon shift for doctor {$doctor->id} ({$doctor->name})");
            } else {
                // Full day shift: 9 AM to 7 PM with lunch break 12-2 PM (8 hours)
                foreach ($days as $day) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                        'start_time' => '09:00',
                        'end_time' => '19:00',
                        'break_start' => '12:00',
                        'break_end' => '14:00',
                        'is_active' => true
                    ]);
                }
                $this->command->info("Created full day shift for doctor {$doctor->id} ({$doctor->name})");
            }
        }

        $totalSchedules = DoctorSchedule::count();
        $this->command->info("Total schedules in database: $totalSchedules");
    }
}

