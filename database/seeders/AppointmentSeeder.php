<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = [
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'appointment_date' => Carbon::today()->addDays(2),
                'appointment_time' => '10:00:00',
                'reason' => 'Regular checkup for heart condition',
                'status' => 'scheduled'
            ],
            [
                'patient_id' => 2,
                'doctor_id' => 3,
                'appointment_date' => Carbon::today()->addDays(1),
                'appointment_time' => '14:00:00',
                'reason' => 'Child vaccination',
                'status' => 'scheduled'
            ],
            [
                'patient_id' => 3,
                'doctor_id' => 4,
                'appointment_date' => Carbon::today(),
                'appointment_time' => '09:00:00',
                'reason' => 'Knee pain consultation',
                'status' => 'scheduled'
            ],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}