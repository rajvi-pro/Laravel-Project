@extends('layouts.admin-layout')

@section('title', $doctor->name . ' - Schedule')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .page-header h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .btn-back {
        background: #e0e0e0;
        color: #333;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #d0d0d0;
    }

    .doctor-info-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        border-left: 4px solid #0f61cc;
    }

    .doctor-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
    }

    .doctor-name {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .doctor-spec {
        font-size: 13px;
        color: #999;
        margin: 5px 0 0 0;
    }

    .doctor-contact {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
        font-size: 13px;
        color: #666;
    }

    .doctor-contact div {
        margin: 5px 0;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .metric-card {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 6px;
        border-left: 3px solid #0f61cc;
    }

    .metric-label {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .metric-value {
        font-size: 24px;
        font-weight: 700;
        color: #0f61cc;
    }

    .schedules-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .schedules-table thead {
        background: #f5f5f5;
        border-bottom: 2px solid #e0e0e0;
    }

    .schedules-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #666;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .schedules-table td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .schedules-table tr:hover {
        background: #fafafa;
    }

    .day-badge {
        display: inline-block;
        background: #e3f2fd;
        color: #0f61cc;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    .time-badge {
        display: inline-block;
        background: #f3e5f5;
        color: #7b1fa2;
        padding: 6px 12px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-badge.active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.inactive {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .empty-state {
        padding: 60px 40px;
        text-align: center;
        color: #999;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        color: #666;
        margin: 10px 0;
    }

    .info-box {
        background: #e3f2fd;
        border-left: 4px solid #0f61cc;
        padding: 15px;
        border-radius: 6px;
        color: #0f61cc;
        margin-bottom: 30px;
        font-size: 13px;
    }

    .info-box strong {
        font-weight: 700;
    }
</style>

<div class="page-header">
    <h2>{{ $doctor->name }} - Schedule</h2>
    <a href="{{ route('admin.schedules') }}" class="btn-back">← Back to Schedules</a>
</div>

<div class="doctor-info-card">
    <div class="doctor-header">
        <div>
            <h3 class="doctor-name">{{ $doctor->name }}</h3>
            <p class="doctor-spec">{{ $doctor->specialization ?? 'General Practitioner' }}</p>
        </div>
    </div>

    <div class="doctor-contact">
        <div><strong>📧 Email:</strong> {{ $doctor->email }}</div>
        @if($doctor->phone)
            <div><strong>📞 Phone:</strong> {{ $doctor->phone }}</div>
        @endif
        @if($doctor->qualification)
            <div><strong>🎓 Qualification:</strong> {{ $doctor->qualification }}</div>
        @endif
    </div>

    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-label">Total Appointments</div>
            <div class="metric-value">{{ $appointmentCount }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Upcoming Appointments</div>
            <div class="metric-value">{{ $upcomingAppointmentCount }}</div>
        </div>
    </div>
</div>

<div class="info-box">
    ℹ️ <strong>Read-Only Access:</strong> This is a view-only schedule. Doctors manage their schedules from the Doctor Panel. Active schedules appear in appointment booking.
</div>

@if (count($schedules) > 0)
    <table class="schedules-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Working Hours</th>
                <th>Break Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schedules as $schedule)
                <tr>
                    <td>
                        <span class="day-badge">
                            {{ ucfirst($dayNames[$schedule->day_of_week] ?? $schedule->day_of_week) }}
                        </span>
                    </td>
                    <td>
                        <span class="time-badge">
                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                        </span>
                    </td>
                    <td>
                        @if ($schedule->break_start && $schedule->break_end)
                            <span class="time-badge">
                                {{ $schedule->break_start }} - {{ $schedule->break_end }}
                            </span>
                        @else
                            <span style="color: #999;">No break</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $schedule->is_active ? 'active' : 'inactive' }}">
                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty-state">
        <div class="empty-state-icon">📅</div>
        <h3>No Schedules Set Up</h3>
        <p>This doctor has not configured their schedule yet.</p>
        <p>They can add schedules from their Doctor Panel.</p>
    </div>
@endif

<div style="background: white; padding: 20px; border-radius: 10px; border-left: 4px solid #999; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
    <h4 style="margin-top: 0; color: #1a1a1a;">How Schedules Work</h4>
    <ul style="margin: 10px 0; padding-left: 20px; color: #666; font-size: 13px;">
        <li><strong>Active Schedules:</strong> Only active schedules show available slots for appointment booking</li>
        <li><strong>Inactive Schedules:</strong> When deactivated, no appointments can be booked on that day</li>
        <li><strong>Working Hours:</strong> Patients and staff can only book appointments during these hours</li>
        <li><strong>Break Times:</strong> Appointments cannot be booked during break hours</li>
        <li><strong>Doctor Unavailability:</strong> Separate from schedules, doctors can mark themselves unavailable for specific date ranges</li>
    </ul>
</div>
@endsection
