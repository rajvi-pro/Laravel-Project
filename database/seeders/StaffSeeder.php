<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'name' => 'Ramesh Kumar',
                'email' => 'ramesh.kumar@hospital.com',
                'phone' => '9876543220',
                'role' => 'Nurse',
                'department' => 'General Ward',
                'password' => Hash::make('Staff@123')
            ],
            [
                'name' => 'Anjali Verma',
                'email' => 'anjali.verma@hospital.com',
                'phone' => '9876543221',
                'role' => 'Receptionist',
                'department' => 'Front Desk',
                'password' => Hash::make('Staff@123')
            ],
            [
                'name' => 'Suresh Pandey',
                'email' => 'suresh.pandey@hospital.com',
                'phone' => '9876543222',
                'role' => 'Lab Technician',
                'department' => 'Laboratory',
                'password' => Hash::make('Staff@123')
            ],
            [
                'name' => 'Kavya Nair',
                'email' => 'kavya.nair@hospital.com',
                'phone' => '9876543223',
                'role' => 'Pharmacist',
                'department' => 'Pharmacy',
                'password' => Hash::make('Staff@123')
            ],
            [
                'name' => 'Rohan Desai',
                'email' => 'rohan.desai@hospital.com',
                'phone' => '9876543224',
                'role' => 'Nurse',
                'department' => 'ICU',
                'password' => Hash::make('Staff@123')
            ],
        ];

        foreach ($staff as $member) {
            Staff::updateOrCreate(
                ['email' => $member['email']],
                $member
            );
        }
    }
}