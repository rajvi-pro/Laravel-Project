<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'name' => 'Arjun Singh',
                'email' => 'arjun.singh@email.com',
                'phone' => '9876543230',
                'date_of_birth' => '1985-05-15',
                'gender' => 'male',
                'address' => '123 Green Street',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'blood_group' => 'O+',
                'password' => Hash::make('Patient@123')
            ],
            [
                'name' => 'Pooja Menon',
                'email' => 'pooja.menon@email.com',
                'phone' => '9876543231',
                'date_of_birth' => '1990-08-22',
                'gender' => 'female',
                'address' => '456 Oak Avenue',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560001',
                'blood_group' => 'A+',
                'password' => Hash::make('Patient@123')
            ],
            [
                'name' => 'Deepak Sharma',
                'email' => 'deepak.sharma@email.com',
                'phone' => '9876543232',
                'date_of_birth' => '1988-03-10',
                'gender' => 'male',
                'address' => '789 Pine Road',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'blood_group' => 'B+',
                'password' => Hash::make('Patient@123')
            ],
            [
                'name' => 'Sneha Deshmukh',
                'email' => 'sneha.deshmukh@email.com',
                'phone' => '9876543233',
                'date_of_birth' => '1992-11-07',
                'gender' => 'female',
                'address' => '321 Maple Lane',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'pincode' => '411001',
                'blood_group' => 'AB+',
                'password' => Hash::make('Patient@123')
            ],
            [
                'name' => 'Nikhil Iyer',
                'email' => 'nikhil.iyer@email.com',
                'phone' => '9876543234',
                'date_of_birth' => '1987-06-18',
                'gender' => 'male',
                'address' => '654 Cedar Street',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'pincode' => '600001',
                'blood_group' => 'O-',
                'password' => Hash::make('Patient@123')
            ],
        ];

        foreach ($patients as $patient) {
            Patient::updateOrCreate(
                ['email' => $patient['email']],
                $patient
            );
        }
    }
}