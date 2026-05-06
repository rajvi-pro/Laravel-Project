<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBillingController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $staffList = Staff::select('id', 'name')->orderBy('name')->get();
        $staffId = $request->input('staff_id');

        $billingsQuery = Billing::with(['patient', 'appointment', 'staff'])
            ->orderBy('billing_date', 'desc');

        if ($staffId) {
            $billingsQuery->where('staff_id', $staffId);
        }

        $billings = $billingsQuery->paginate(15);

        $totalBillings = Billing::count();
        $totalRevenue = Billing::where('payment_status', 'paid')->sum('amount');
        $pendingAmount = Billing::where('payment_status', 'pending')->sum('amount');
        $paidBillings = Billing::where('payment_status', 'paid')->count();

        return view('admin.billing.index', compact(
            'billings',
            'totalBillings',
            'totalRevenue',
            'pendingAmount',
            'paidBillings',
            'staffList',
            'staffId'
        ));
    }

    public function create()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        $appointments = Appointment::with(['patient'])->orderBy('appointment_date', 'desc')->get();
        $staff = Staff::select('id', 'name')->orderBy('name')->get();

        return view('admin.billing.create', compact('patients', 'appointments', 'staff'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'staff_id' => 'nullable|exists:staff,id',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'payment_method' => 'required|in:cash,card,online,cheque',
            'billing_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:billing_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Billing::create($validated);

        return redirect()->route('admin.billing.index')
            ->with('success', 'Billing record created successfully!');
    }

    public function show($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $billing = Billing::withTrashed()->with(['patient', 'appointment', 'staff'])->findOrFail($id);

        return view('admin.billing.show', compact('billing'));
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $billing = Billing::withTrashed()->findOrFail($id);
        $patients = Patient::withTrashed()->select('id', 'name')->orderBy('name')->get();
        $appointments = Appointment::withTrashed()->with(['patient'])->orderBy('appointment_date', 'desc')->get();
        $staff = Staff::withTrashed()->select('id', 'name')->orderBy('name')->get();

        return view('admin.billing.edit', compact('billing', 'patients', 'appointments', 'staff'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $billing = Billing::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'staff_id' => 'nullable|exists:staff,id',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,cancelled',
            'payment_method' => 'required|in:cash,card,online,cheque',
            'billing_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:billing_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $billing->update($validated);

        return redirect()->route('admin.billing.show', $billing->id)
            ->with('success', 'Billing record updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            Billing::findOrFail($id)->delete();

            return redirect()->route('admin.billing.index')
                ->with('success', 'Billing record deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.billing.index')
                ->with('error', 'Failed to delete billing record: ' . $e->getMessage());
        }
    }

    /**
     * Get revenue analytics data
     */
    public function analytics()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $monthlyRevenue = DB::table('billings')
            ->selectRaw('DATE_FORMAT(billing_date, "%b") as month, DATE_FORMAT(billing_date, "%m") as month_num, SUM(amount) as revenue')
            ->where('payment_status', 'paid')
            ->whereYear('billing_date', now()->year)
            ->groupBy(DB::raw('DATE_FORMAT(billing_date, "%m"), DATE_FORMAT(billing_date, "%b")'))
            ->orderBy('month_num')
            ->get();

        $paymentMethods = DB::table('billings')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();

        $appointmentStats = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.billing.analytics', compact(
            'monthlyRevenue',
            'paymentMethods',
            'appointmentStats'
        ));
    }
}
