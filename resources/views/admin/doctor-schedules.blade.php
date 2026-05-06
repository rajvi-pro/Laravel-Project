@extends('layouts.admin-layout')

@section('title', 'Doctor Schedules')

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

    .filter-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .filter-form {
        display: flex;
        gap: 15px;
        align-items: flex-end;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group select:focus {
        outline: none;
        border-color: #0f61cc;
    }

    .btn-filter {
        background: #0f61cc;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: #0a4aa8;
    }

    .doctor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .doctor-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-left: 4px solid #0f61cc;
    }

    .doctor-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .doctor-name {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .doctor-spec {
        font-size: 12px;
        color: #999;
        margin-bottom: 12px;
    }

    .doctor-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid #f0f0f0;
    }

    .stat-item {
        font-size: 12px;
    }

    .stat-label {
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 10px;
    }

    .stat-value {
        color: #0f61cc;
        font-weight: 700;
        font-size: 16px;
        margin-top: 3px;
    }

    .view-schedule-btn {
        display: inline-block;
        background: #e3f2fd;
        color: #0f61cc;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        margin-top: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
        width: 100%;
        text-align: center;
        border: none;
        cursor: pointer;
    }

    .view-schedule-btn:hover {
        background: #bbdefb;
        color: #0a4aa8;
    }

    .schedules-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
</style>

<div class="page-header">
    <h2>Doctor Schedules Management</h2>
</div>

<div class="filter-card">
    <form method="GET" action="{{ route('admin.schedules') }}" class="filter-form">
        <div class="form-group" style="flex: 1; min-width: 250px;">
            <label for="doctor_id">Select Doctor</label>
            <select id="doctor_id" name="doctor_id">
                <option value="">-- All Doctors --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $selectedDoctorId == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }} ({{ $doctor->specialization ?? 'General' }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-filter">View Schedule</button>
    </form>
</div>

@if ($selectedDoctor && $schedules && count($schedules) > 0)
    <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <h3 style="margin: 0 0 15px 0; color: #1a1a1a;">
            {{ $selectedDoctor->name }}
            <span style="font-size: 14px; color: #999; font-weight: 400;">
                ({{ $selectedDoctor->specialization ?? 'General' }})
            </span>
        </h3>
        <p style="margin: 0; color: #666; font-size: 14px;">📧 {{ $selectedDoctor->email }}</p>
    </div>

    <table class="schedules-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Time Slot</th>
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
                            <span style="color: #999;">—</span>
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

    <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-left: 4px solid #0f61cc; border-radius: 6px; color: #0f61cc;">
        <strong>ℹ️ Note:</strong> As an admin, you have read-only access to doctor schedules. Doctors can manage their own schedules from the Doctor Panel.
    </div>

@elseif ($selectedDoctor && (!$schedules || count($schedules) == 0))
    <div class="empty-state">
        <div class="empty-state-icon">📅</div>
        <h3>No Schedules Found</h3>
        <p>{{ $selectedDoctor->name }} has not set up any schedules yet.</p>
    </div>

@else
    <div class="empty-state">
        <div class="empty-state-icon">👨‍⚕️</div>
        <h3>Select a Doctor</h3>
        <p>Choose a doctor from the filter above to view their schedule.</p>
    </div>
@endif

<div style="margin-top: 40px; padding: 20px; background: #f9f9f9; border-radius: 10px; border-left: 4px solid #999;">
    <h4 style="margin-top: 0;">Schedule Information</h4>
    <ul style="margin: 10px 0; padding-left: 20px; color: #666; font-size: 13px;">
        <li>Each doctor can set their working hours and break times for each day of the week</li>
        <li>Active schedules are used to generate available appointment slots for patients and staff</li>
        <li>Inactive schedules will not show available slots on that day</li>
        <li>Doctors cannot schedule appointments outside their working hours</li>
        <li>Break times are automatically excluded from available appointment slots</li>
    </ul>
</div>
@endsection
