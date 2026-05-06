@extends('admin-layout')

@section('title', 'Create Billing')

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
        <h1>Create New Billing</h1>
        <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">← Back to Billing</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.billing.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                        <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                            <option value="">-- Select Patient --</option>
                            @foreach($patients ?? [] as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="appointment_id" class="form-label">Appointment</label>
                        <select class="form-select @error('appointment_id') is-invalid @enderror" id="appointment_id" name="appointment_id">
                            <option value="">-- Select Appointment (Optional) --</option>
                            @foreach($appointments ?? [] as $appointment)
                                <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                    {{ $appointment->patient->name ?? 'N/A' }} - {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('appointment_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="staff_id" class="form-label">Staff Member</label>
                        <select class="form-select @error('staff_id') is-invalid @enderror" id="staff_id" name="staff_id">
                            <option value="">-- Select Staff (Optional) --</option>
                            @foreach($staff ?? [] as $staffMember)
                                <option value="{{ $staffMember->id }}" {{ old('staff_id') == $staffMember->id ? 'selected' : '' }}>
                                    {{ $staffMember->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('staff_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                              rows="3" required placeholder="Enter billing description...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                            <option value="">-- Select Status --</option>
                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="">-- Select Method --</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="billing_date" class="form-label">Billing Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('billing_date') is-invalid @enderror" 
                               id="billing_date" name="billing_date" value="{{ old('billing_date', date('Y-m-d')) }}" required>
                        @error('billing_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" 
                              rows="3" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Billing
                    </button>
                    <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
