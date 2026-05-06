@extends('admin-layout')

@section('title', 'My Appointments')

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
        <h1>My Appointments</h1>
        <a href="{{ route('patient.doctor-select') }}" class="btn btn-primary">+ Book Appointment</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments ?? [] as $appointment)
                        <tr>
                            <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->appointment_date ?? 'N/A' }}</td>
                            <td>{{ $appointment->appointment_time ?? 'N/A' }}</td>
                            <td>{{ $appointment->reason ?? 'N/A' }}</td>
                            <td>
                                @if($appointment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($appointment->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-primary">Scheduled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('patient.appointment.detail', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                @if($appointment->status != 'completed' && $appointment->status != 'cancelled' && $appointment->appointment_date > now())
                                    <a href="{{ route('patient.appointment.manualReschedule', $appointment->id) }}" class="btn btn-sm btn-warning ms-1">Reschedule</a>
                                    <a href="{{ route('patient.appointment.cancel.form', $appointment->id) }}" class="btn btn-sm btn-danger ms-1">Cancel</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No appointments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
