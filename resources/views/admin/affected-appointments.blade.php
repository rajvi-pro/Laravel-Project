@extends('admin-layout')

@section('title', 'Affected Appointments')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <a href="{{ route('admin.appointments.index') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('admin.unavailable-doctors') }}"><i class="bi bi-calendar-times"></i> Unavailable Doctors</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid" style="padding: 15px;">
    <h2 style="font-size: 1.3rem; font-weight: 700; color: #333; margin-bottom: 20px;">Affected Appointments</h2>

    <div style="margin-bottom: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px;">
        <h5 style="margin-top: 0; color: #856404;"><i class="bi bi-exclamation-triangle"></i> Doctor Unavailability Details</h5>
        <p style="margin: 10px 0;">
            <strong>Doctor:</strong> {{ $unavailability->doctor->name }}<br>
            <strong>Specialization:</strong> {{ $unavailability->doctor->specialization }}<br>
            <strong>From Date:</strong> {{ $unavailability->unavailable_date->format('d M Y') }}<br>
            <strong>To Date:</strong> {{ ($unavailability->end_date ?? $unavailability->unavailable_date)->format('d M Y') }}<br>
            @if($unavailability->reason)
                <strong>Reason:</strong> {{ $unavailability->reason }}<br>
            @endif
        </p>
    </div>

    @if($affectedAppointments->count() > 0)
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin: 0;">
            <div class="card-header" style="background-color: #dc3545; color: white; padding: 10px 15px; border-radius: 8px 8px 0 0;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">Appointments Needing Rescheduling ({{ $affectedAppointments->count() }})</h5>
            </div>

            <div class="card-body" style="padding: 10px;">
                <div style="overflow-x: auto;">
                    <table class="table table-striped table-sm" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 10px; font-weight: 600;">Patient Name</th>
                                <th style="padding: 10px; font-weight: 600;">Email</th>
                                <th style="padding: 10px; font-weight: 600;">Phone</th>
                                <th style="padding: 10px; font-weight: 600;">Original Date</th>
                                <th style="padding: 10px; font-weight: 600;">Original Time</th>
                                <th style="padding: 10px; font-weight: 600; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affectedAppointments as $appointment)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 10px; font-weight: 500;">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                    <td style="padding: 10px; font-size: 0.85rem;">{{ $appointment->patient->email ?? 'N/A' }}</td>
                                    <td style="padding: 10px;">{{ $appointment->patient->phone ?? 'N/A' }}</td>
                                    <td style="padding: 10px;">{{ $appointment->appointment_date->format('d M Y') }}</td>
                                    <td style="padding: 10px;">{{ $appointment->appointment_time }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $appointment->id }}" style="padding: 4px 8px; font-size: 0.8rem;">
                                            <i class="bi bi-calendar-check"></i> Reschedule
                                        </button>
                                    </td>
                                </tr>

                                <!-- Reschedule Modal -->
                                <div class="modal fade" id="rescheduleModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h6 class="modal-title">Reschedule Appointment - {{ $appointment->patient->name }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.reschedule-appointment', $appointment->id) }}" method="POST">
                                                <div class="modal-body" style="padding: 15px;">
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <label for="new_date_{{ $appointment->id }}" class="form-label" style="font-weight: 600;">New Date</label>
                                                        <input type="date" id="new_date_{{ $appointment->id }}" name="new_date" class="form-control" required />
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="new_time_{{ $appointment->id }}" class="form-label" style="font-weight: 600;">New Time</label>
                                                        <input type="time" id="new_time_{{ $appointment->id }}" name="new_time" class="form-control" required />
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label for="new_doctor_{{ $appointment->id }}" class="form-label" style="font-weight: 600;">Doctor (Keep Same or Choose Another)</label>
                                                        <select id="new_doctor_{{ $appointment->id }}" name="new_doctor_id" class="form-control">
                                                            <option value="">Same Doctor</option>
                                                            @foreach(\App\Models\Doctor::all() as $doctor)
                                                                <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }} ({{ $doctor->specialization }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-check-circle"></i> Reschedule
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle"></i> No appointments are affected by this unavailability.
        </div>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ route('admin.unavailable-doctors') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Unavailable Doctors
        </a>
    </div>
</div>
@endsection