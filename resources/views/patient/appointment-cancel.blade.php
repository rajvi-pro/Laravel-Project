@extends('admin-layout')

@section('title', 'Cancel Appointment')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}" class="active"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}"><i class="bi bi-file-text"></i> Medical History</a>
    <a href="{{ route('patient.prescriptions') }}"><i class="bi bi-prescription"></i> Prescriptions</a>
    <a href="{{ route('patient.lab-results') }}"><i class="bi bi-beaker"></i> Lab Results</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('patient.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Cancel Appointment</h1>
        <a href="{{ route('patient.appointments') }}" class="btn btn-outline-secondary">← Back to Appointments</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Appointment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Doctor:</strong> {{ $appointment->doctor->name ?? 'N/A' }}</p>
                            <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date:</strong> {{ $appointment->appointment_date ?? 'N/A' }}</p>
                            <p><strong>Time:</strong> {{ $appointment->appointment_time ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Reason:</strong> {{ $appointment->reason ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-x-circle"></i> Cancellation Notice</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Important:</strong> Cancelling this appointment cannot be undone. Please provide a reason for cancellation.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Cancellation Reason</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('patient.appointment.cancel', $appointment->id) }}">
                @csrf

                <div class="mb-3">
                    <label for="cancellation_reason" class="form-label">Please provide a reason for cancelling this appointment <span class="text-danger">*</span></label>
                    <textarea
                        id="cancellation_reason"
                        name="cancellation_reason"
                        class="form-control"
                        rows="5"
                        placeholder="e.g., I have a scheduling conflict, I need to reschedule for a different time, Emergency situation, etc."
                        required
                    >{{ old('cancellation_reason') }}</textarea>
                    <div class="form-text">This information will help us improve our services.</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('patient.appointments') }}" class="btn btn-secondary">Keep Appointment</a>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')">
                        <i class="bi bi-x-circle"></i> Cancel Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection