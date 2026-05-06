@extends('layouts.patient-layout')

@section('page-title', 'My Profile')
@section('title', 'Patient Profile - HMS')

@section('content')
<style>
    .page-title {
        color: #1a1a1a;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .metrics-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .metric-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        text-align: center;
        border-top: 4px solid #0d47a1;
    }

    .metric-number {
        font-size: 36px;
        font-weight: 700;
        color: #0d47a1;
        margin: 0;
        line-height: 1;
    }

    .metric-text {
        font-size: 13px;
        color: #666;
        margin: 10px 0 0 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .main-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 25px;
    }

    .profile-card {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #0d47a1;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        flex-shrink: 0;
    }

    .avatar-info h5 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 5px 0;
    }

    .avatar-info p {
        font-size: 13px;
        color: #666;
        margin: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 11px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .btn-edit {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .side-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .side-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 15px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #0d47a1;
    }

    .side-item {
        margin-bottom: 15px;
        font-size: 13px;
    }

    .side-label {
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
        margin-bottom: 4px;
        display: block;
    }

    .side-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .btn-change-password {
        background: #ffc107;
        border: none;
        color: #1a1a1a;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .btn-change-password:hover {
        background: #ffb300;
        color: #1a1a1a;
    }

    @media (max-width: 1024px) {
        .metrics-row {
            grid-template-columns: 1fr;
        }

        .main-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<h2 class="page-title">Dashboard</h2>

<!-- Metrics Row -->
<div class="metrics-row">
    <div class="metric-card">
        <p class="metric-number">{{ $totalAppointments ?? 0 }}</p>
        <p class="metric-text">Total Appointments</p>
    </div>
    <div class="metric-card">
        <p class="metric-number">{{ $upcomingAppointments ?? 0 }}</p>
        <p class="metric-text">Upcoming Visits</p>
    </div>
    <div class="metric-card">
        <p class="metric-number">{{ $labResults ?? 0 }}</p>
        <p class="metric-text">Reports Available</p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="main-grid">
    <!-- Profile Details -->
    <div class="profile-card">
        <h3 class="card-title">
            <i class="fas fa-user-circle" style="color: #0d47a1;"></i> Profile Details
        </h3>

        <!-- Avatar Section -->
        <div class="info-header">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="avatar-info">
                <h5>{{ $patient->name }}</h5>
                <p>{{ $patient->email }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Full Name</span>
                <span class="info-value">{{ $patient->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date of Birth</span>
                <span class="info-value">{{ optional($patient->date_of_birth)->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $patient->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Gender</span>
                <span class="info-value">{{ ucfirst($patient->gender) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value">{{ $patient->phone }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Address</span>
                <span class="info-value">{{ $patient->address }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">City</span>
                <span class="info-value">{{ $patient->city }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">State</span>
                <span class="info-value">{{ $patient->state }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Blood Group</span>
                <span class="info-value">{{ $patient->blood_group }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Pincode</span>
                <span class="info-value">{{ $patient->pincode }}</span>
            </div>
        </div>

        <div class="info-item">
            <span class="info-label">Emergency Contact</span>
            <span class="info-value">{{ $patient->emergency_contact }}</span>
        </div>

        <a href="{{ route('patient.profile.edit-view') }}" class="btn-edit">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>

    <!-- Account Information Sidebar -->
    <div class="side-card">
        <h4 class="side-card-title">Account Information</h4>
        
        <div class="side-item">
            <span class="side-label">Member Since</span>
            <span class="side-value">{{ $patient->created_at->format('M d, Y') }}</span>
        </div>

        <div class="side-item">
            <span class="side-label">Last Updated</span>
            <span class="side-value">{{ $patient->updated_at->format('M d, Y') }}</span>
        </div>

        <a href="{{ route('patient.change-password') }}" class="btn-change-password">
            Change Password
        </a>
    </div>

    <!-- Family Members Section -->
    @if($familyMembers && $familyMembers->count() > 0)
        <div class="side-card" style="margin-top: 20px;">
            <h4 class="side-card-title">Family Members</h4>
            @foreach($familyMembers as $member)
                <div class="side-item" style="border-bottom: 1px solid #eee; padding-bottom: 12px; margin-bottom: 12px;">
                    <span class="side-label">{{ $member->member_name }}</span>
                    <span class="side-value" style="font-size: 13px; color: #666;">{{ $member->relationship }}</span>
                    @if($member->phone)
                        <div style="font-size: 12px; color: #999; margin-top: 4px;">
                            <i class="fas fa-phone"></i> {{ $member->phone }}
                        </div>
                    @endif
                    @if($member->blood_group)
                        <div style="font-size: 12px; color: #999; margin-top: 2px;">
                            <i class="fas fa-droplet"></i> {{ $member->blood_group }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
