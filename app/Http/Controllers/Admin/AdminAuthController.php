<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = trim($request->email ?? '');
        $password = trim($request->password ?? '');

        // Database lookup
        $admin = Admin::where('email', $email)->first();
        
        if ($admin && Hash::check($password, $admin->password)) {
            session([
                'admin_logged_in' => true,
                'admin_user' => explode('@', $email)[0],
                'admin_email' => $email,
                'admin_name' => $admin->name
            ]);
            return redirect()->route('admin.dashboard')->with('login_success', 'Login successful! Welcome ' . $admin->name);
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_user', 'admin_email', 'admin_name']);
        return redirect()->route('welcome')->with('success', 'You have been logged out successfully.');
    }
}
