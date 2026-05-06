<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DoctorSeeder::class,
            DoctorScheduleSeeder::class,
            StaffSeeder::class,
            PatientSeeder::class,
            ServiceSeeder::class,
            AppointmentSeeder::class,
            MedicineSeeder::class,
        ]);
    }
}