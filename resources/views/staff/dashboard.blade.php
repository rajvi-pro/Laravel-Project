@extends('layouts.staff-layout')

@section('page-title', 'Dashboard')
@section('title', 'Staff Dashboard - HMS')

@section('content')
    <!-- Welcome Section -->
    <div style="margin-bottom: 30px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 5px 0; color: #1a1a1a;">Welcome Back</h2>
            <p style="margin: 0; color: #999; font-size: 14px;">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div style="display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap;">
        <a href="{{ route('staff.appointments') }}" class="btn btn-primary" style="gap: 8px; display: inline-flex; align-items: center;">
            <i class="fas fa-plus"></i> New Appointment
        </a>
    </div>

    <!-- Metrics Grid -->
    <div class="row mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="metric-card blue">
            <div class="metric-label">Today's Appointments</div>
            <div class="metric-value">{{ $todayAppointments->count() ?? 0 }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Total Patients</div>
            <div class="metric-value">{{ $totalPatients ?? 0 }}</div>
        </div>
        <div class="metric-card green">
            <div class="metric-label">Available Doctors</div>
            <div class="metric-value">{{ $availableDoctors ?? 0 }}</div>
        </div>
        <div class="metric-card orange">
            <div class="metric-label">Pending Appointments</div>
            <div class="metric-value">{{ $pendingAppointments ?? 0 }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-container">
        <h5 style="margin-bottom: 20px; font-weight: 700; color: #1a1a1a;">Quick Actions</h5>
        <div class="quick-actions-grid">
            <a href="{{ route('staff.appointments') }}" class="action-btn">
                <i class="fas fa-tasks"></i>
                Manage Appointments
            </a>
            <a href="{{ route('staff.patients') }}" class="action-btn">
                <i class="fas fa-user-injured"></i>
                View Patients
            </a>
            <a href="{{ route('staff.doctors') }}" class="action-btn">
                <i class="fas fa-clock"></i>
                Doctor Schedule
            </a>
            <a href="{{ route('staff.billings') }}" class="action-btn">
                <i class="fas fa-receipt"></i>
                Patient Billing
            </a>
            <a href="{{ route('staff.appointments') }}" class="action-btn">
                <i class="fas fa-calendar-plus"></i>
                New Appointment
            </a>
        </div>
    </div>

    <!-- Upcoming Appointments & Patient Statistics -->
    <div class="row mt-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
        <!-- Upcoming Appointments Section -->
        <div class="table-container">
            <div class="table-header">
                <h5>Upcoming Appointments (7 Days)</h5>
                <a href="{{ route('staff.appointments') }}" class="view-all-link">View All</a>
            </div>
            @if($upcomingAppointments && $upcomingAppointments->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingAppointments->take(5) as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->name ?? $appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $appointment->doctor->name ?? 'Unassigned' }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($appointment->status == 'completed') #26a69a
                                        @elseif($appointment->status == 'pending') #ff9800
                                        @elseif($appointment->status == 'cancelled') #e53935
                                        @else #1565c0
                                        @endif
                                    ">
                                        {{ ucfirst($appointment->status ?? 'Scheduled') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <p style="margin: 0;">No upcoming appointments in the next 7 days</p>
                </div>
            @endif
        </div>

        <!-- Patient Statistics Section -->
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h5 style="margin-bottom: 20px; font-weight: 700; color: #1a1a1a;">Patient Statistics</h5>
            <div style="display: flex; align-items: center; justify-content: space-around; margin-bottom: 20px;">
                <div style="text-align: center;">
                    <div style="font-size: 28px; font-weight: 700; color: #1565c0;">{{ $totalPatients ?? 0 }}</div>
                    <div style="font-size: 12px; color: #999; text-transform: uppercase; margin-top: 5px;">Total Patients</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 28px; font-weight: 700; color: #ff6b6b;">{{ $malePatients ?? 0 }}</div>
                    <div style="font-size: 12px; color: #999; text-transform: uppercase; margin-top: 5px;">Male</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 28px; font-weight: 700; color: #ee5a6f;">{{ $femalePatients ?? 0 }}</div>
                    <div style="font-size: 12px; color: #999; text-transform: uppercase; margin-top: 5px;">Female</div>
                </div>
            </div>
            <div style="border-top: 1px solid #e0e0e0; padding-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: #666; font-size: 13px;">Quick Stats:</span>
                </div>
                <div style="font-size: 12px; color: #666; line-height: 1.8;">
                    <div>Today's Appointments: <strong>{{ $appointmentsToday ?? 0 }}</strong></div>
                    <div>Pending Actions: <strong>{{ $pendingAppointments ?? 0 }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Billing Summary Section -->
    <div class="row mt-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h5 style="margin: 0; font-weight: 700; color: #1a1a1a;">Billing Summary</h5>
                <a href="{{ route('staff.billings') }}" class="view-all-link" style="font-size: 12px;">View All</a>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <div style="font-size: 12px; color: #999; text-transform: uppercase; margin-bottom: 5px;">Total Billings</div>
                    <div style="font-size: 24px; font-weight: 700; color: #1a1a1a;">{{ $billings ?? 0 }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: #999; text-transform: uppercase; margin-bottom: 5px;">Total Amount</div>
                    <div style="font-size: 24px; font-weight: 700; color: #26a69a;">₹{{ number_format($totalBillingAmount ?? 0, 2) }}</div>
                </div>
            </div>

            <div style="border-top: 1px solid #e0e0e0; padding-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666; font-size: 13px;">Paid Amount</span>
                    <span style="color: #26a69a; font-weight: 600; font-size: 13px;">₹{{ number_format($paidAmount ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #666; font-size: 13px;">Pending Billings</span>
                    <span style="color: #ff9800; font-weight: 600; font-size: 13px;">{{ $pendingBillings ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments Section -->
    <div class="row mt-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
        <div class="table-container">
            <div class="table-header">
                <h5>Today's Appointments</h5>
                <a href="{{ route('staff.appointments') }}" class="view-all-link">View All</a>
            </div>
            @if($todayAppointments && $todayAppointments->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayAppointments->take(5) as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->name ?? $appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $appointment->doctor->name ?? 'Unassigned' }}</td>
                                <td>{{ $appointment->appointment_time ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($appointment->status == 'completed') #26a69a
                                        @elseif($appointment->status == 'pending') #ff9800
                                        @elseif($appointment->status == 'cancelled') #e53935
                                        @else #1565c0
                                        @endif
                                    ">
                                        {{ ucfirst($appointment->status ?? 'Scheduled') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <p style="margin: 0;">No appointments scheduled for today</p>
                </div>
            @endif
        </div>
    </div>

@endsection

