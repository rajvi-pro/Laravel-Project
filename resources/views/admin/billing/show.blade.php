@extends('admin-layout')

@section('title', 'Billing Details')

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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Billing Details</h1>
        <div>
            <a href="{{ route('admin.billing.edit', $billing->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Billing Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Billing ID</h6>
                            <p><strong>#{{ $billing->id }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Billing Date</h6>
                            <p><strong>{{ \Carbon\Carbon::parse($billing->billing_date)->format('d M Y') }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Patient</h6>
                            <p>
                                <strong>{{ $billing->patient->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $billing->patient->email ?? 'N/A' }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Amount</h6>
                            <p><strong style="font-size: 1.5rem; color: #28a745;">₹{{ number_format($billing->amount, 2) }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Appointment</h6>
                            <p>
                                @if($billing->appointment)
                                    <strong>{{ $billing->appointment->id }}</strong><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($billing->appointment->appointment_date)->format('d M Y H:i') }}</small>
                                @else
                                    <span class="text-muted">No appointment linked</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Staff Member</h6>
                            <p>
                                @if($billing->staff)
                                    <strong>{{ $billing->staff->name }}</strong>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $billing->description }}</p>
                    </div>

                    @if($billing->notes)
                        <div class="mb-3">
                            <h6 class="text-muted">Notes</h6>
                            <p>{{ $billing->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Payment Status</h6>
                        @if($billing->payment_status === 'paid')
                            <span class="badge bg-success" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                                <i class="bi bi-check-circle"></i> Paid
                            </span>
                        @elseif($billing->payment_status === 'pending')
                            <span class="badge bg-warning" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                                <i class="bi bi-clock"></i> Pending
                            </span>
                        @else
                            <span class="badge bg-danger" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                                <i class="bi bi-x-circle"></i> Cancelled
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Payment Method</h6>
                        <div class="badge bg-secondary" style="font-size: 0.95rem; padding: 0.5rem 1rem;">
                            {{ ucfirst($billing->payment_method) }}
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted">Amount</h6>
                        <p style="font-size: 1.8rem; color: #28a745; font-weight: bold;">₹{{ number_format($billing->amount, 2) }}</p>
                    </div>

                    @if($billing->due_date)
                        <div class="mb-3">
                            <h6 class="text-muted">Due Date</h6>
                            <p>
                                {{ \Carbon\Carbon::parse($billing->due_date)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($billing->due_date)->isPast() && $billing->payment_status !== 'paid')
                                    <br><small class="text-danger"><i class="bi bi-exclamation-circle"></i> Overdue</small>
                                @endif
                            </p>
                        </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted">Created</h6>
                        <p class="small text-muted">{{ $billing->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Last Updated</h6>
                        <p class="small text-muted">{{ $billing->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
