@extends('admin-layout')

@section('title', 'Manage Billing')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <a href="{{ route('admin.appointments.index') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('admin.billing.index') }}" class="active"><i class="bi bi-cash-coin"></i> Billing</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Premium Attractive Header Bar with Blue Gradient & Stats -->
    <div style="background: linear-gradient(135deg, #0d6efd 0%, #0a4a9f 50%, #0c3fa8 100%); border-radius: 16px; padding: 32px; margin-bottom: 32px; box-shadow: 0 8px 32px rgba(13, 110, 253, 0.2), 0 2px 8px rgba(13, 110, 253, 0.15); position: relative; overflow: hidden;">
        <!-- Animated Background Elements -->
        <div style="position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div style="position: absolute; bottom: -30%; left: -5%; width: 250px; height: 250px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; filter: blur(40px);"></div>
        
        <div class="d-flex justify-content-between align-items-center mb-4" style="position: relative; z-index: 1;">
            <div>
                <h5 style="color: rgba(255, 255, 255, 0.85); font-weight: 600; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">💰 Financial Management</h5>
                <h1 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin: 0; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);">Billing Management</h1>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; margin: 12px 0 0 0; font-weight: 500;">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div style="text-align: right;">
                    <p style="color: #ffffff; font-weight: 700; margin: 0; font-size: 0.95rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                    <p style="color: rgba(255, 255, 255, 0.85); font-size: 0.85rem; margin: 4px 0 0 0;">Administrator</p>
                </div>
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.15) 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.5rem; border: 3px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), inset 0 1px 2px rgba(255, 255, 255, 0.3);">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Stat Cards with Glass Morphism -->
        <div class="row mt-4" style="position: relative; z-index: 1;">
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-invoice" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">TOTAL BILLINGS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $totalBillings ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-money-bill-wave" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">TOTAL REVENUE</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">₹<span style="font-size: 1.8rem;">{{ number_format($totalRevenue ?? 0, 0) }}</span></h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-hourglass-end" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">PENDING AMOUNT</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">₹<span style="font-size: 1.8rem;">{{ number_format($pendingAmount ?? 0, 0) }}</span></h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-check-circle" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">PAID RECORDS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $paidBillings ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="position: relative; z-index: 1;">
            <div></div>
            <a href="{{ route('admin.billing.create') }}" class="btn btn-light" style="font-weight: 700; padding: 12px 28px; font-size: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; border: none;">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Create Billing
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Staff Filter Dropdown -->
            <form method="GET" action="" class="mb-3 row g-3 align-items-center">
                <div class="col-auto">
                    <label for="staff_id" class="col-form-label">Filter by Staff:</label>
                </div>
                <div class="col-auto">
                    <select name="staff_id" id="staff_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Staff --</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff->id }}" {{ (isset($staffId) && $staffId == $staff->id) ? 'selected' : '' }}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div style="overflow-x: auto;">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Billing ID</th>
                            <th>Patient</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Staff</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                            <th>Billing Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billings ?? [] as $billing)
                            <tr>
                                <td><strong>#{{ $billing->id }}</strong></td>
                                <td>{{ $billing->patient->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($billing->description, 30) }}</td>
                                <td><strong>₹{{ number_format($billing->amount, 2) }}</strong></td>
                                <td>{{ $billing->staff->name ?? '-' }}</td>
                                <td>
                                    @if($billing->payment_status === 'paid')
                                        <span class="badge bg-success">✅ Paid</span>
                                    @elseif($billing->payment_status === 'pending')
                                        <span class="badge bg-warning">⏳ Pending</span>
                                    @else
                                        <span class="badge bg-danger">❌ Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($billing->payment_method) }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('d M Y') }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('admin.billing.show', $billing->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.billing.edit', $billing->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No billing records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($billings) && $billings->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $billings->links() }}
        </div>
    @endif
</div>
@endsection
