@extends('layouts.patient-layout')

@section('page-title', 'Appointment Details')
@section('title', 'Appointment Details - HMS')

@section('content')
<style>
    .detail-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-back {
        background: white;
        border: 2px solid #0d47a1;
        color: #0d47a1;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #0d47a1;
        color: white;
    }

    .detail-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .detail-section {
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .detail-section:last-child {
        border-bottom: none;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #0d47a1;
    }

    .detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0d47a1;
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 600;
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

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
    }

    .action-btn-primary:hover {
        background: linear-gradient(135deg, #0a3d91 0%, #0e509e 100%);
        color: white;
        text-decoration: none;
        box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
    }

    .action-btn-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }

    .action-btn-warning:hover {
        background: #ffc107;
        color: #fff;
        text-decoration: none;
    }

    .action-btn-danger {
        background: #ffcdd2;
        color: #c62828;
        border: 1px solid #ef9a9a;
    }

    .action-btn-danger:hover {
        background: #ef9a9a;
        color: #c62828;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .detail-row {
            grid-template-columns: 1fr;
        }

        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="detail-header">
    <h2><i class="fas fa-calendar-check" style="color: #0d47a1; margin-right: 10px;"></i> Appointment Details</h2>
    <a href="{{ route('patient.appointments') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Appointments
    </a>
</div>

<div class="detail-container">
    <div class="detail-section">
        <h3 class="section-title">📅 Appointment Information</h3>
        <div class="detail-row">
            <div class="detail-item">
                <div class="detail-label">Doctor</div>
                <div class="detail-value">{{ $appointment->doctor->name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Date</div>
                <div class="detail-value">{{ optional($appointment->appointment_date)->format('M d, Y') ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Time</div>
                <div class="detail-value">{{ date('g:i A', strtotime($appointment->appointment_time)) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @if($appointment->status === 'scheduled')
                        <span class="badge badge-scheduled">Scheduled</span>
                    @elseif($appointment->status === 'completed')
                        <span class="badge badge-completed">Completed</span>
                    @else
                        <span class="badge badge-cancelled">Cancelled</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h3 class="section-title">📋 Additional Information</h3>
        <div class="detail-row">
            <div class="detail-item">
                <div class="detail-label">Reason for Visit</div>
                <div class="detail-value">{{ $appointment->reason ?? 'General Checkup' }}</div>
            </div>
            @if($appointment->status == 'cancelled' && $appointment->cancellation_reason)
            <div class="detail-item">
                <div class="detail-label">Cancellation Reason</div>
                <div class="detail-value">{{ $appointment->cancellation_reason }}</div>
            </div>
            @endif
        </div>
    </div>

    @if($appointment->status !== 'cancelled' && $appointment->appointment_date > now())
    <div class="detail-section">
        <h3 class="section-title">⚙️ Actions</h3>
        <div class="action-buttons">
            <a href="{{ route('patient.appointment.manualReschedule', $appointment->id) }}" class="action-btn action-btn-warning">
                <i class="fas fa-calendar-alt"></i> Reschedule
            </a>
            <a href="{{ route('patient.appointment.cancel.form', $appointment->id) }}" class="action-btn action-btn-danger">
                <i class="fas fa-times"></i> Cancel Appointment
            </a>
        </div>
    </div>
    @elseif($appointment->status == 'cancelled')
    <div class="detail-section">
        <p style="color: #c62828; font-weight: 600; margin: 0;">
            <i class="fas fa-exclamation-circle"></i> This appointment has been cancelled.
        </p>
    </div>
    @else
    <div class="detail-section">
        <p style="color: #666; font-weight: 600; margin: 0;">
            <i class="fas fa-info-circle"></i> This appointment is in the past and cannot be modified.
        </p>
    </div>
    @endif
</div>
@endsection
