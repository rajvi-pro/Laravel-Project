<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientAuthController extends Controller
{
    public function showRegister()
    {
        return view('patient.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);
            $patient = Patient::create($validated);
            
            \Log::info('Patient registered', [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'database' => env('DB_DATABASE')
            ]);
            
            return redirect()->route('patient.login')->with('success', 'Registration successful! Your account has been created. Please login with your credentials.');
        } catch (\Exception $e) {
            \Log::error('Registration error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['email' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function showLogin()
    {
        return view('patient.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $patient = Patient::where('email', $request->email)->first();

        if ($patient && Hash::check($request->password, $patient->password)) {
            session([
                'patient_logged_in' => true,
                'patient_id' => $patient->id,
                'patient_name' => $patient->name,
                'patient_email' => $patient->email
            ]);
            return redirect()->route('patient.dashboard')->with('success', 'Welcome back, ' . $patient->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        session()->forget(['patient_logged_in', 'patient_id', 'patient_name', 'patient_email']);
        return redirect()->route('welcome')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPasswordForm()
    {
        return view('patient.forgot-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:patients,email',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $patient = Patient::where('email', $request->email)->first();
        $patient->password = Hash::make($request->new_password);
        $patient->save();

        return redirect()->route('patient.login')->with('success', 'Password changed successfully. Please login with your new password.');
    }
}