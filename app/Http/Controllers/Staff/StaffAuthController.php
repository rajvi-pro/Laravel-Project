<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffAuthController extends Controller
{
    /**
     * Show Staff Login Form
     */
    public function showLogin()
    {
        return view('staff.login');
    }

    /**
     * Handle Staff Login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Verify staff credentials (you can modify this to use a Staff model if you have one)
        // This is a simple hardcoded example - replace with actual staff authentication logic
        
        // Get staff from database (assumes Staff model exists)
        $staff = \App\Models\Staff::where('email', $validated['email'])->first();
        
        if ($staff && \Illuminate\Support\Facades\Hash::check($validated['password'], $staff->password)) {
            // Store staff session
            session([
                'staff_logged_in' => true,
                'staff_id' => $staff->id,
                'staff_name' => $staff->name ?? $staff->first_name,
                'staff_email' => $staff->email,
                'staff_role' => $staff->role ?? 'Staff',
            ]);

            return redirect()->route('staff.dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    /**
     * Handle Staff Logout
     */
    public function logout()
    {
        session()->forget([
            'staff_logged_in',
            'staff_id',
            'staff_name',
            'staff_email',
            'staff_role',
        ]);

        session()->flush();

        return redirect()->route('welcome')->with('success', 'Logged out successfully!');
    }
}
