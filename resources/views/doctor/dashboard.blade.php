@extends('layouts.doctor-layout')

@section('title', 'Doctor Dashboard')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        border-radius: 12px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .dashboard-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        font-size: 1.05rem;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #667eea;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.blue {
        border-top-color: #3b82f6;
    }

    .stat-card.green {
        border-top-color: #10b981;
    }

    .stat-card.purple {
        border-top-color: #8b5cf6;
    }

    .stat-card.red {
        border-top-color: #ef4444;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }

    .stat-icon {
        font-size: 2rem;
        opacity: 0.15;
        float: right;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 10px 10px 0 0;
        padding: 20px;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
    }

    .card-body {
        padding: 25px;
    }

    .appointment-item {
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
        transition: all 0.3s;
    }

    .appointment-item:hover {
        background: #f9fafb;
        border-color: #667eea;
    }

    .appointment-time {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 10px;
    }

    .appointment-patient {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .appointment-details {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .quick-action-btn {
        display: inline-block;
        padding: 12px 20px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        margin-bottom: 10px;
        width: 100%;
        text-align: center;
    }

    .quick-action-btn:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    /* Availability Status Card Styling */
    .availability-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .availability-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        color: white;
    }

    .availability-card-header h6 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .availability-card-body {
        padding: 25px;
    }

    .availability-section {
        margin-bottom: 20px;
    }

    .availability-section:last-child {
        margin-bottom: 0;
    }

    .availability-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: block;
    }

    .availability-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .availability-btn {
        padding: 12px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        width: 100%;
        text-align: center;
    }

    .availability-btn-available {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .availability-btn-available:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        color: white;
        text-decoration: none;
    }

    .availability-btn-unavailable {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .availability-btn-unavailable:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        color: white;
        text-decoration: none;
    }

    .availability-status {
        padding: 15px;
        border-radius: 8px;
        background: #f3f4f6;
        border-left: 4px solid #667eea;
        margin-top: 15px;
    }

    .availability-status-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .availability-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .availability-status-badge.available {
        background: #d1fae5;
        color: #065f46;
    }

    .availability-status-badge.unavailable {
        background: #fee2e2;
        color: #7f1d1d;
    }

    .availability-reason {
        margin-top: 8px;
        font-size: 0.85rem;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 2rem;
        }

        .availability-buttons {
            flex-direction: row;
        }

        .availability-btn {
            flex: 1;
        }
    }
</style>

<div class="container-fluid">
    <div class="dashboard-header">
        <h2>👋 Welcome back, {{ session('doctor_name') ?? 'Doctor' }}!</h2>
        <p>Here's an overview of your today's activities and schedule</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card blue">
                <div class="stat-icon">📅</div>
                <div class="stat-label">Today's Appointments</div>
                <div class="stat-value">{{ $todayAppointments->count() ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card green">
                <div class="stat-icon">👥</div>
                <div class="stat-label">Active Patients</div>
                <div class="stat-value">{{ $totalPatients ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card purple">
                <div class="stat-icon">💊</div>
                <div class="stat-label">Total Prescriptions</div>
                <div class="stat-value">{{ $totalPrescriptions ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card red">
                <div class="stat-icon">📋</div>
                <div class="stat-label">Medical Reports</div>
                <div class="stat-value">{{ $totalMedicalReports ?? 0 }}</div>
            </div>
        </div>
    </div>



    <!-- SECTION 1: Today's Schedule (Full Width) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📅 Today's Schedule</h5>
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    @if($todayAppointments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">😊</div>
                            <p>No appointments scheduled for today.</p>
                            <p style="color: #d1d5db; font-size: 0.9rem;">Take a break and stay hydrated!</p>
                        </div>
                    @else
                        @foreach($todayAppointments as $appt)
                            <div class="appointment-item">
                                <div class="appointment-time">
                                    🕐 {{ date('g:i A', strtotime($appt->appointment_time)) }}
                                </div>
                                <div class="appointment-patient">
                                    👤 {{ $appt->patient->name ?? 'Unknown Patient' }}
                                </div>
                                <div class="appointment-details">
                                    📞 {{ $appt->patient->phone ?? 'N/A' }}
                                </div>
                                <a href="{{ route('doctor.appointment.detail', $appt->id) }}" class="btn btn-sm btn-primary">View Details</a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2 & 3: Quick Profile (Left) and Quick Actions (Right) -->
    <div class="row mb-4">
        <!-- Quick Profile Card -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">👨‍⚕️ Your Profile</h5>
                </div>
                <div class="card-body">
                    <div style="text-align: center;">
                        <div style="font-size: 3rem; margin-bottom: 15px;">👨‍⚕️</div>
                        <p style="color: #6b7280; margin-bottom: 8px; font-size: 0.9rem;">Doctor Profile</p>
                        <p style="font-weight: 700; color: #1f2937; font-size: 1.2rem;">{{ session('doctor_name') ?? 'Doctor' }}</p>
                        <p style="font-size: 0.9rem; color: #9ca3af; margin-bottom: 20px;">{{ session('doctor_email') ?? 'Email' }}</p>
                        
                        <!-- Doctor Availability Status -->
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                            <span class="availability-label" style="display: block; margin-bottom: 12px;">Current Status</span>
                            <div class="availability-status" style="margin-bottom: 15px;">
                                @if(session('doctor_availability.is_available_today', true))
                                    <span class="availability-status-badge available">
                                        <i class="fas fa-check-circle"></i> Available Today
                                    </span>
                                @else
                                    <span class="availability-status-badge unavailable">
                                        <i class="fas fa-times-circle"></i> Not Available
                                    </span>
                                    @if(session('doctor_availability.unavailable_message'))
                                        <div class="availability-reason">
                                            Reason: {{ session('doctor_availability.unavailable_message') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="availability-buttons" style="gap: 8px;">
                                <!-- Available Button -->
                                <form method="POST" action="{{ route('doctor.availability.update') }}" style="margin: 0; flex: 1;">
                                    @csrf
                                    <input type="hidden" name="is_available_today" value="1">
                                    <input type="hidden" name="unavailable_message" value="">
                                    <button type="submit" class="availability-btn availability-btn-available" style="flex: 1;">
                                        <i class="fas fa-check-circle"></i> Available Today
                                    </button>
                                </form>

                                <!-- Unavailable Button -->
                                <a href="{{ route('doctor.unavailable.form') }}" class="availability-btn availability-btn-unavailable" style="flex: 1;">
                                    <i class="fas fa-calendar-times"></i> Mark Unavailable
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">⚡ Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('doctor.patients') }}" class="quick-action-btn">
                        👥 View My Patients
                    </a>
                    <a href="{{ route('doctor.appointments') }}" class="quick-action-btn">
                        📅 All Appointments
                    </a>
                    <a href="{{ route('doctor.prescriptions') }}" class="quick-action-btn">
                        💊 Create Prescription
                    </a>
                    <a href="{{ route('doctor.medical-reports') }}" class="quick-action-btn">
                        📋 Medical Reports
                    </a>
                    <a href="{{ route('doctor.lab-results') }}" class="quick-action-btn">
                        🧪 Lab Results
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

