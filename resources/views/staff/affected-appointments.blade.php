@extends('layouts.staff-layout')

@section('page-title', 'Affected Appointments')
@section('title', 'Affected Appointments - Staff Portal')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-calendar-exc"></i> Affected Appointments</h2>
    </div>

    <div style="margin-bottom: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px;">
        <h5 style="margin-top: 0; colorreplace: #856404; font-weight: 700;"><i class="fas fa-exclamation-triangle"></i> Doctor Unavailability Details</h5>
        <div style="line-height: 1.8;">
            <p style="margin: 5px 0;"><strong>Doctor:</strong> {{ $unavailability->doctor->name }}</p>
            <p style="margin: 5px 0;"><strong>Specialization:</strong> {{ $unavailability->doctor->specialization }}</p>
            <p style="margin: 5px 0;"><strong>From Date:</strong> {{ $unavailability->unavailable_date->format('d M Y') }}</p>
            <p style="margin: 5px 0;"><strong>To Date:</strong> {{ ($unavailability->end_date ?? $unavailability->unavailable_date)->format('d M Y') }}</p>
            @if($unavailability->reason)
                <p style="margin: 5px 0;"><strong>Reason:</strong> {{ $unavailability->reason }}</p>
            @endif
        </div>
    </div>

    @if($affectedAppointments->count() > 0)
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="card-header" style="background-color: #dc3545; color: white; padding: 15px; border-radius: 10px 10px 0 0;">
                <h5 style="margin: 0; font-weight: 700;">Appointments Needing Rescheduling ({{ $affectedAppointments->count() }})</h5>
            </div>

            <div class="card-body" style="padding: 15px;">
                <div style="overflow-x: auto;">
                    <table class="table table-hover table-striped table-sm" style="font-size: 0.9rem;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 10px; font-weight: 600;">Patient</th>
                                <th style="padding: 10px; font-weight: 600;">Contact</th>
                                <th style="padding: 10px; font-weight: 600;">Original Date</th>
                                <th style="padding: 10px; font-weight: 600;">Original Time</th>
                                <th style="padding: 10px; font-weight: 600; text-align: center; width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affectedAppointments as $appointment)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 10px; font-weight: 500;">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                    <td style="padding: 10px; font-size: 0.85rem;">
                                        {{ $appointment->patient->email ?? 'N/A' }}<br>
                                        {{ $appointment->patient->phone ?? 'N/A' }}
                                    </td>
                                    <td style="padding: 10px;">{{ $appointment->appointment_date->format('d M Y') }}</td>
                                    <td style="padding: 10px;">{{ $appointment->appointment_time }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $appointment->id }}" style="padding: 5px 10px; font-size: 0.8rem;">
                                            <i class="fas fa-calendar-check"></i> Reschedule
                                        </button>
                                    </td>
                                </tr>

                                <!-- Reschedule Modal -->
                                <div class="modal fade" id="rescheduleModal{{ $appointment->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white" style="border-bottom: none;">
                                                <h6 class="modal-title" style="font-weight: 700;">Reschedule Appointment - {{ $appointment->patient->name }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('staff.reschedule-appointment', $appointment->id) }}" method="POST">
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
                                                        <label for="new_doctor_{{ $appointment->id }}" class="form-label" style="font-weight: 600;">Doctor</label>
                                                        <select id="new_doctor_{{ $appointment->id }}" name="new_doctor_id" class="form-control">
                                                            <option value="">Keep Same Doctor</option>
                                                            @foreach(\App\Models\Doctor::all() as $doctor)
                                                                <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }} ({{ $doctor->specialization }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-check-circle"></i> Reschedule
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
        <div class="alert alert-success" role="alert" style="border-radius: 8px; padding: 15px;">
            <i class="fas fa-check-circle"></i> No appointments are affected by this unavailability.
        </div>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ route('staff.unavailable-doctors') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@endsection
