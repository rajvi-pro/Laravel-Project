<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'General Consultation', 'description' => 'Basic doctor consultation', 'price' => 50.00, 'staff_id' => 1],
            ['name' => 'Blood Test', 'description' => 'Complete blood count (CBC)', 'price' => 30.00, 'staff_id' => 2],
            ['name' => 'X-Ray', 'description' => 'Standard radiography', 'price' => 100.00, 'staff_id' => 2],
            ['name' => 'Medication Dispensing', 'description' => 'Pharmacy medication service', 'price' => 20.00, 'staff_id' => 3],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
