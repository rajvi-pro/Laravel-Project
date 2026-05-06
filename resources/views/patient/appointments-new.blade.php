@extends('layouts.patient-layout')

@section('page-title', 'Appointments')
@section('title', 'My Appointments - HMS')

@section('content')
<style>
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-book {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        border: none;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-book:hover {
        box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
        padding: 20px;
        font-weight: 700;
        font-size: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        background-color: #f8f9fa;
        border-bottom: 2px solid #0d47a1;
    }

    thead th {
        padding: 15px 20px;
        font-weight: 700;
        color: #1a1a1a;
        text-align: left;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr {
        border-bottom: 1px solid #eee;
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background-color: #f5f7fa;
    }

    tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #1a1a1a;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-scheduled {
        background-color: #e3f2fd;
        color: #0d47a1;
    }

    .badge-completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .badge-cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    .badge-needs-reschedule {
        background-color: #fff3cd;
        color: #856404;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .reschedule-alert {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 15px;
        color: #856404;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-right: 5px;
    }

    .action-btn-view {
        background: #0d47a1;
        color: white;
    }

    .action-btn-view:hover {
        background: #0a3d91;
        color: white;
        text-decoration: none;
    }

    .action-btn-cancel {
        background: #ffcdd2;
        color: #c62828;
    }

    .action-btn-cancel:hover {
        background: #ef9a9a;
        color: #c62828;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state p {
        font-size: 16px;
        margin-bottom: 20px;
    }
</style>

<div class="page-header">
    <h2><i class="fas fa-calendar-check" style="color: #0d47a1; margin-right: 10px;"></i> My Appointments</h2>
    <a href="{{ route('patient.doctor-select') }}" class="btn-book">
        <i class="fas fa-plus"></i> Book Appointment
    </a>
</div>

<div class="table-container">
    @if($appointments && count($appointments) > 0)
        <div class="table-header">
            Appointment List
        </div>
        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td><strong>{{ $appointment->doctor->name ?? 'N/A' }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} @ {{ date('H:i', strtotime($appointment->appointment_time)) }}</td>
                        <td>{{ $appointment->reason ?? 'N/A' }}</td>
                        <td>
                            @if($appointment->status == 'completed')
                                <span class="badge badge-completed">Completed</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="badge badge-cancelled">Cancelled</span>
                            @elseif($appointment->status == 'needs_reschedule')
                                <span class="badge badge-needs-reschedule"><i class="fas fa-exclamation-circle"></i> Needs Reschedule</span>
                            @else
                                <span class="badge badge-scheduled">Scheduled</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('patient.appointment.detail', $appointment->id) }}" class="action-btn action-btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($appointment->status == 'needs_reschedule')
                                <a href="{{ route('patient.appointment.manualReschedule', $appointment->id) }}" class="action-btn" style="background: #ffc107; color: #000;">
                                    <i class="fas fa-calendar"></i> Reschedule
                                </a>
                            @elseif($appointment->status != 'completed' && $appointment->status != 'cancelled')
                                <a href="{{ route('patient.appointment.cancel.form', $appointment->id) }}" class="action-btn action-btn-cancel">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>No appointments scheduled yet</p>
            <a href="{{ route('patient.doctor-select') }}" class="btn-book">
                <i class="fas fa-plus"></i> Book Your First Appointment
            </a>
        </div>
    @endif
</div>

@endsection
