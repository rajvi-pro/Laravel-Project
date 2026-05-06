<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddTestDoctor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctor:add-test';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Create a test doctor account for manual login';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if doctor already exists
        $existingDoctor = Doctor::where('email', 'doctor.test@hospital.com')->first();
        
        if ($existingDoctor) {
            $this->error('Test doctor already exists!');
            return 1;
        }

        try {
            $doctor = Doctor::create([
                'name' => 'Dr. Test Account',
                'email' => 'doctor.test@hospital.com',
                'phone' => '+1-555-0199',
                'specialization' => 'General Practice',
                'qualification' => 'MD',
                'experience_years' => 5,
                'consultation_fee' => 100.00,
                'password' => Hash::make('password')
            ]);

            $this->info('✓ Test doctor account created successfully!');
            $this->newLine();
            $this->info('Login Credentials:');
            $this->line('  Email: doctor.test@hospital.com');
            $this->line('  Password: password');
            $this->newLine();
            $this->comment('Doctor ID: ' . $doctor->id);
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error creating test doctor: ' . $e->getMessage());
            return 1;
        }
    }
}
