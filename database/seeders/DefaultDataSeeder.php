<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\Patient;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add default admin if doesn't exist
        if (Admin::count() == 0) {
            Admin::create([
                'email' => 'admin@hms.local',
                'password' => Hash::make('Admin@123'),
                'name' => 'Administrator',
            ]);
            echo "✓ Default admin created\n";
        }

        // Add default doctors with Indian names
        if (Doctor::count() == 0) {
            $doctors = [
                [
                    'name' => 'Dr. Rajesh Kumar',
                    'email' => 'rajesh.kumar@hms.local',
                    'phone' => '9876543210',
                    'specialization' => 'Cardiology',
                    'qualification' => 'MD, DM(Cardiology)',
                    'experience_years' => 15,
                    'consultation_fee' => 500,
                    'password' => Hash::make('Doctor@123'),
                    'is_available_today' => true,
                ],
                [
                    'name' => 'Dr. Priya Sharma',
                    'email' => 'priya.sharma@hms.local',
                    'phone' => '9876543211',
                    'specialization' => 'Pediatrics',
                    'qualification' => 'MD(Pediatrics)',
                    'experience_years' => 12,
                    'consultation_fee' => 400,
                    'password' => Hash::make('Doctor@123'),
                    'is_available_today' => true,
                ],
                [
                    'name' => 'Dr. Anil Patel',
                    'email' => 'anil.patel@hms.local',
                    'phone' => '9876543212',
                    'specialization' => 'Orthopedics',
                    'qualification' => 'MS(Ortho)',
                    'experience_years' => 18,
                    'consultation_fee' => 450,
                    'password' => Hash::make('Doctor@123'),
                    'is_available_today' => true,
                ],
                [
                    'name' => 'Dr. Neha Gupta',
                    'email' => 'neha.gupta@hms.local',
                    'phone' => '9876543213',
                    'specialization' => 'Dermatology',
                    'qualification' => 'MD(Dermatology)',
                    'experience_years' => 10,
                    'consultation_fee' => 350,
                    'password' => Hash::make('Doctor@123'),
                    'is_available_today' => true,
                ],
                [
                    'name' => 'Dr. Vikram Singh',
                    'email' => 'vikram.singh@hms.local',
                    'phone' => '9876543214',
                    'specialization' => 'General Surgery',
                    'qualification' => 'MS(Surgery)',
                    'experience_years' => 20,
                    'consultation_fee' => 600,
                    'password' => Hash::make('Doctor@123'),
                    'is_available_today' => true,
                ],
            ];

            foreach ($doctors as $doctor) {
                Doctor::create($doctor);
            }
            echo "✓ Default doctors created\n";
        }

        // Add default staff with Indian names
        if (Staff::count() == 0) {
            $staff = [
                [
                    'name' => 'Ramesh Kumar',
                    'email' => 'ramesh.kumar@hms.local',
                    'phone' => '9876543220',
                    'role' => 'Nurse',
                    'department' => 'General Ward',
                    'password' => Hash::make('Staff@123'),
                ],
                [
                    'name' => 'Anjali Verma',
                    'email' => 'anjali.verma@hms.local',
                    'phone' => '9876543221',
                    'role' => 'Receptionist',
                    'department' => 'Front Desk',
                    'password' => Hash::make('Staff@123'),
                ],
                [
                    'name' => 'Suresh Pandey',
                    'email' => 'suresh.pandey@hms.local',
                    'phone' => '9876543222',
                    'role' => 'Lab Technician',
                    'department' => 'Laboratory',
                    'password' => Hash::make('Staff@123'),
                ],
                [
                    'name' => 'Kavya Nair',
                    'email' => 'kavya.nair@hms.local',
                    'phone' => '9876543223',
                    'role' => 'Pharmacist',
                    'department' => 'Pharmacy',
                    'password' => Hash::make('Staff@123'),
                ],
                [
                    'name' => 'Rohan Desai',
                    'email' => 'rohan.desai@hms.local',
                    'phone' => '9876543224',
                    'role' => 'Nurse',
                    'department' => 'ICU',
                    'password' => Hash::make('Staff@123'),
                ],
            ];

            foreach ($staff as $staffMember) {
                Staff::create($staffMember);
            }
            echo "✓ Default staff created\n";
        }

        // Add default patients with Indian names
        if (Patient::count() == 0) {
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
                    'password' => Hash::make('Patient@123'),
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
                    'password' => Hash::make('Patient@123'),
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
                    'password' => Hash::make('Patient@123'),
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
                    'password' => Hash::make('Patient@123'),
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
                    'password' => Hash::make('Patient@123'),
                ],
            ];

            foreach ($patients as $patient) {
                Patient::create($patient);
            }
            echo "✓ Default patients created\n";
        }

        echo "✓ Database seeding completed successfully!\n";
    }
}
