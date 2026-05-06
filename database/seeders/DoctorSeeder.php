<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dr. Rajesh Kumar',
                'email' => 'rajesh.kumar@hospital.com',
                'phone' => '9876543210',
                'specialization' => 'Cardiology',
                'qualification' => 'MD, DM(Cardiology)',
                'experience_years' => 15,
                'consultation_fee' => 500.00,
                'password' => Hash::make('Doctor@123')
            ],
            [
                'name' => 'Dr. Priya Sharma',
                'email' => 'priya.sharma@hospital.com',
                'phone' => '9876543211',
                'specialization' => 'Pediatrics',
                'qualification' => 'MD(Pediatrics)',
                'experience_years' => 12,
                'consultation_fee' => 400.00,
                'password' => Hash::make('Doctor@123')
            ],
            [
                'name' => 'Dr. Anil Patel',
                'email' => 'anil.patel@hospital.com',
                'phone' => '9876543212',
                'specialization' => 'Orthopedics',
                'qualification' => 'MS(Ortho)',
                'experience_years' => 18,
                'consultation_fee' => 450.00,
                'password' => Hash::make('Doctor@123')
            ],
            [
                'name' => 'Dr. Neha Gupta',
                'email' => 'neha.gupta@hospital.com',
                'phone' => '9876543213',
                'specialization' => 'Dermatology',
                'qualification' => 'MD(Dermatology)',
                'experience_years' => 10,
                'consultation_fee' => 350.00,
                'password' => Hash::make('Doctor@123')
            ],
            [
                'name' => 'Dr. Vikram Singh',
                'email' => 'vikram.singh@hospital.com',
                'phone' => '9876543214',
                'specialization' => 'General Surgery',
                'qualification' => 'MS(Surgery)',
                'experience_years' => 20,
                'consultation_fee' => 600.00,
                'password' => Hash::make('Doctor@123')
            ],
            [
                'name' => 'Dr. Anjali Desai',
                'email' => 'anjali.desai@hospital.com',
                'phone' => '9876543215',
                'specialization' => 'Neurology',
                'qualification' => 'MD(Neurology)',
                'experience_years' => 14,
                'consultation_fee' => 550.00,
                'password' => Hash::make('Doctor@123')
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::updateOrCreate(
                ['email' => $doctor['email']],
                $doctor
            );
        }
    }
}