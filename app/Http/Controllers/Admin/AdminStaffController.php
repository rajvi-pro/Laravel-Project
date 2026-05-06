<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $staff = Staff::orderBy('created_at', 'desc')->paginate(15);
        $doctors = Doctor::select('id','name','email','specialization')->withCount('appointments')->orderBy('name')->take(8)->get();
        $patients = Patient::select('id','name','email','phone')->withCount(['appointments','medicalReports','prescriptions'])->orderBy('created_at','desc')->take(8)->get();

        return view('admin.staff.index', compact('staff','doctors','patients'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        return view('admin.staff.create');
    }

    public function store(CreateStaffRequest $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);
        Staff::create($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully!');
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $staff = Staff::withTrashed()->findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validated();

        // Handle password reset if new password is provided
        if (!empty($request->input('new_password'))) {
            $newPassword = $request->input('new_password');
            $confirmPassword = $request->input('new_password_confirmation');
            
            // Manual password validation
            if ($newPassword !== $confirmPassword) {
                return back()->withErrors(['new_password_confirmation' => 'Passwords do not match.'])->withInput();
            }
            
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)) {
                return back()->withErrors(['new_password' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long.'])->withInput();
            }
            
            $validated['password'] = Hash::make($newPassword);
        }

        $staff = Staff::withTrashed()->findOrFail($id);
        $staff->update($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $staff = Staff::findOrFail($id);
            $staffName = $staff->name;
            $staff->delete();
            return redirect()->route('admin.staff.index')->with('success', "Staff member '{$staffName}' deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.staff.index')->with('error', 'Failed to delete staff member: ' . $e->getMessage());
        }
    }
}