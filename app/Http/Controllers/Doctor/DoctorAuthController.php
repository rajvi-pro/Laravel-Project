<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorAuthController extends Controller
{
    public function showLogin()
    {
        return view('doctor.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $doctor = Doctor::where('email', $request->email)->first();

        \Log::info('Doctor login attempt', [
            'email' => $request->email,
            'doctor_exists' => $doctor ? true : false,
            'doctor_id' => $doctor->id ?? null
        ]);

        if ($doctor && Hash::check($request->password, $doctor->password)) {
            session([
                'doctor_logged_in' => true,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'doctor_email' => $doctor->email
            ]);
            \Log::info('Doctor logged in successfully', ['doctor_id' => $doctor->id, 'email' => $doctor->email]);
            return redirect()->route('doctor.dashboard')->with('success', 'Welcome back, Dr. ' . $doctor->name . '!');
        }

        \Log::warning('Doctor login failed', ['email' => $request->email]);
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        session()->forget(['doctor_logged_in', 'doctor_id', 'doctor_name', 'doctor_email']);
        return redirect()->route('welcome')->with('success', 'You have been logged out successfully.');
    }
}