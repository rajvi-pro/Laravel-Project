@extends('admin-layout')

@section('title', 'View Doctor - ' . $doctor->name)

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}" class="active"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Doctors
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Doctor Details</h5>
            <span class="badge bg-light text-dark">ID: {{ $doctor->id }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Full Name</h6>
                    <p class="fs-5 fw-semibold">{{ $doctor->name }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Email Address</h6>
                    <p class="fs-5">{{ $doctor->email }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Phone Number</h6>
                    <p class="fs-5">{{ $doctor->phone }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Specialization</h6>
                    <p class="fs-5">
                        <span class="badge bg-info text-dark">{{ $doctor->specialization }}</span>
                    </p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Qualification</h6>
                    <p class="fs-5">{{ $doctor->qualification }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Years of Experience</h6>
                    <p class="fs-5">{{ $doctor->experience_years }} years</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Consultation Fee (Rs.)</h6>
                    <p class="fs-5">₹{{ number_format($doctor->consultation_fee, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Registered On</h6>
                    <p class="fs-5">{{ $doctor->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <hr class="my-4">

            <!-- Doctor's Weekly Schedule -->
            <div class="mb-4">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-calendar-check"></i> Working Schedule
                </h6>
                @if($doctor->schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Break</th>
                                    <th>Status</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $dayNames = [
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday'
                                    ];
                                @endphp
                                @foreach($doctor->schedules as $schedule)
                                    <tr>
                                        <td><strong>{{ $dayNames[strtolower($schedule->day_of_week)] ?? ucfirst($schedule->day_of_week) }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</td>
                                        <td>
                                            @if($schedule->break_start && $schedule->break_end)
                                                {{ \Carbon\Carbon::parse($schedule->break_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->break_end)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">No break</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($schedule->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.schedule-edit', $schedule->id) }}" class="btn btn-info" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.schedule-delete', $schedule->id) }}" style="display: inline;" onsubmit="return confirm('Delete this schedule?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> No schedule configured for this doctor yet. Use the edit button to add working hours.
                    </div>
                @endif
            </div>

            <hr class="my-4">

            <!-- Action Buttons -->
            <div class="d-flex gap-2" style="margin-top: 2rem;">
                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-warning" style="cursor: pointer; pointer-events: auto;">
                    <i class="bi bi-pencil-square"></i> Edit Doctor Information
                </a>
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary" style="cursor: pointer; pointer-events: auto;">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .btn {
        cursor: pointer !important;
        pointer-events: auto !important;
    }
    
    .btn:hover {
        text-decoration: none !important;
    }
    
    .card-header {
        border-bottom: 2px solid #0d6efd;
    }
    
    p {
        margin-bottom: 0;
    }
</style>

<script>
    // Ensure all action buttons work
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Edit Doctor Information button
        const editButtons = document.querySelectorAll('a[href*="/edit"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            });
        });
        
        // Handle Back to List button
        const backButtons = document.querySelectorAll('a[href*="/doctors"]');
        backButtons.forEach(button => {
            if (button.textContent.includes('Back to List')) {
                button.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href) {
                        window.location.href = href;
                    }
                });
            }
        });
    });
</script>
@endsection
