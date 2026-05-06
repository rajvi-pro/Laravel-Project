<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class AdminPatientController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $patientsQuery = Patient::withCount(['appointments', 'medicalReports', 'prescriptions'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('location')) {
            $loc = '%' . trim($request->location) . '%';
            $patientsQuery->where(function ($q) use ($loc) {
                $q->where('city', 'like', $loc)
                    ->orWhere('address', 'like', $loc);
            });
        }

        if ($request->filled('state')) {
            $patientsQuery->where('state', 'like', '%' . trim($request->state) . '%');
        }

        $patients = $patientsQuery->paginate(15)->appends($request->only('location', 'state'));

        return view('admin.patients.index', compact('patients'));

    }

    public function create()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:patients,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'blood_group' => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $patient = Patient::create($validated);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', 'Patient registered successfully!');
    }

    public function show($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $patient = Patient::withTrashed()->with(['appointments.doctor', 'medicalReports.doctor', 'prescriptions.doctor'])
            ->findOrFail($id);
        
        return view('admin.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $patient = Patient::withTrashed()->findOrFail($id);

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $patient = Patient::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:patients,email,' . $patient->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'blood_group' => 'nullable|string|max:10',
            'emergency_contact' => 'nullable|string|max:100',
        ]);

        $patient->update($validated);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', 'Patient information updated successfully.');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $patient = Patient::findOrFail($id);
            $patientName = $patient->name;
            $patient->delete();

            return redirect()->route('admin.patients.index')
                ->with('success', "Patient '{$patientName}' deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.patients.index')
                ->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }
}