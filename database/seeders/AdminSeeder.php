<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Rajesh Administrator',
                'email' => 'admin@hms.local',
                'password' => Hash::make('Admin@123'),
            ],
            [
                'name' => 'Priya Super Admin',
                'email' => 'superadmin@hms.local',
                'password' => Hash::make('SuperAdmin@123'),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}
