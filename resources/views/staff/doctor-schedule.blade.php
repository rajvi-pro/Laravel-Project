@extends('layouts.staff-layout')

@section('page-title', 'Doctor Full Schedule')
@section('title', 'Doctor Full Schedule - Staff Portal')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-calendar-alt"></i> Doctor Full Schedule</h2>
    </div>

    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h4 style="margin: 0; font-weight: 700;">{{ $doctor->name ?? 'Doctor' }}</h4>
            <p style="margin: 2px 0 0 0; color: #666; font-size: 13px;">{{ $doctor->specialization ?? 'General' }}</p>
        </div>
        <a href="{{ route('staff.doctors') }}" class="btn btn-secondary btn-sm">Back to Doctor Schedule</a>
    </div>

    <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px;">
        <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h5 style="margin: 0;">Appointment List</h5>
                <small style="color: #6c757d;">Total appointments: {{ $appointments->count() }}</small>
            </div>
            <div>
                <span style="background: #e2e6ea; color: #41464b; border-radius: 12px; padding: 5px 12px; font-size: 13px;">Doctor ID: #{{ $doctor->id }}</span>
            </div>
        </div>

        @if($appointments->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ optional($appointment->appointment_date)->format('d M Y') ?? '-' }}</td>
                                <td>{{ $appointment->appointment_time ?? '-' }}</td>
                                <td>{{ optional($appointment->patient)->name ?? 'Unknown' }}</td>
                                <td style="max-width: 220px; overflow-wrap: anywhere;">{{ $appointment->reason ?? '-' }}</td>
                                <td>
                                    <span style="font-weight: 600; color: {{ $appointment->status === 'cancelled' ? '#d63384' : ($appointment->status === 'completed' ? '#198754' : '#0d6efd') }};">
                                        {{ ucfirst($appointment->status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding: 16px; background: #f8f9fa; border: 1px dashed #ced4da; border-radius: 6px; text-align: center; color: #6c757d;">
                No appointments scheduled for this doctor yet.
            </div>
        @endif
    </div>
@endsection