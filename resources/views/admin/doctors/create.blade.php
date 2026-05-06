@extends('admin-layout')

@section('title', 'Register Doctor')

@section('sidebar')
<nav aria-label="Main navigation">
    <a href="{{ route('admin.dashboard') }}" aria-label="Go to Dashboard"><i class="bi bi-speedometer2" aria-hidden="true"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}" aria-label="Go to Patients"><i class="bi bi-people" aria-hidden="true"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}" class="active" aria-label="Go to Doctors (current page)"><i class="bi bi-person-check" aria-hidden="true"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}" aria-label="Go to Staff"><i class="bi bi-person-badge" aria-hidden="true"></i> Staff</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100" aria-label="Logout from admin panel">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Doctors
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Register New Doctor</h1>
        </div>
        <div class="card-body" style="max-height: 85vh; overflow-y: auto;">
            <form method="POST" action="{{ route('admin.doctors.store') }}">
                @csrf

                <h5 class="mb-4">
                    <i class="bi bi-person-fill" aria-hidden="true"></i> Basic Information
                </h5>

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="03001234567" pattern="[0-9]{10}" maxlength="10" required inputmode="numeric">
                        <small class="form-text text-muted d-block">Must be exactly 10 digits</small>
                        @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Specialization -->
                    <div class="col-md-6 mb-3">
                        <label for="specialization" class="form-label">Specialization *</label>
                        <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="specialization" name="specialization" value="{{ old('specialization') }}" placeholder="e.g., Cardiology, Neurology" required>
                        @error('specialization')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Qualification -->
                    <div class="col-md-6 mb-3">
                        <label for="qualification" class="form-label">Qualification *</label>
                        <input type="text" class="form-control @error('qualification') is-invalid @enderror" id="qualification" name="qualification" value="{{ old('qualification') }}" placeholder="e.g., MBBS, MD" required>
                        @error('qualification')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Experience Years -->
                    <div class="col-md-6 mb-3">
                        <label for="experience_years" class="form-label">Years of Experience *</label>
                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years" value="{{ old('experience_years') }}" min="0" required>
                        @error('experience_years')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Consultation Fee -->
                    <div class="col-md-6 mb-3">
                        <label for="consultation_fee" class="form-label">Consultation Fee (Rs.) *</label>
                        <input type="number" class="form-control @error('consultation_fee') is-invalid @enderror" id="consultation_fee" name="consultation_fee" value="{{ old('consultation_fee') }}" step="0.01" min="0">
                        @error('consultation_fee')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min 8 chars: uppercase, lowercase, digit" required>
                        <small class="form-text text-muted d-block">Must contain uppercase, lowercase, digit, and be at least 8 characters</small>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Working Schedule Section -->
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-4">
                        <i class="bi bi-calendar-check"></i> Doctor's Weekly Schedule
                    </h5>
                    
                    <div id="scheduleContainer">
                        <div class="schedule-card mb-3 p-3 border rounded" style="background-color: #f8f9fa; position: relative;" id="schedule_0">
                            <div class="row gx-2 gy-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Day *</label>
                                    <select name="schedules[0][day_of_week]" class="form-select form-select-sm" required>
                                        <option value="">Select</option>
                                        <option value="1">Monday</option>
                                        <option value="2">Tuesday</option>
                                        <option value="3">Wednesday</option>
                                        <option value="4">Thursday</option>
                                        <option value="5">Friday</option>
                                        <option value="6">Saturday</option>
                                        <option value="7">Sunday</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Start Time *</label>
                                    <input type="time" name="schedules[0][start_time]" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-bold">End Time *</label>
                                    <input type="time" name="schedules[0][end_time]" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Break Start</label>
                                    <input type="time" name="schedules[0][break_start]" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-bold">Break End</label>
                                    <input type="time" name="schedules[0][break_end]" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-bold d-block">Active</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="schedules[0][is_active]" class="form-check-input" value="1" id="active_0" checked>
                                        <label class="form-check-label small" for="active_0">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeSchedule(0)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-success mt-3" onclick="addSchedule()">
                        <i class="bi bi-plus-circle"></i> Add Another Day
                    </button>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-5 pt-4 border-top d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Register Doctor
                    </button>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-label {
        font-weight: 500;
        color: #333;
    }
    
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    /* Schedule card styling */
    .schedule-card {
        border-left: 4px solid #0d47a1;
        transition: all 0.2s ease;
    }

    .schedule-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .schedule-card .form-label {
        font-size: 0.85rem;
    }
</style>

<script>
    let scheduleCount = 1;

    function addSchedule() {
        const container = document.getElementById('scheduleContainer');
        const newSchedule = document.createElement('div');
        newSchedule.id = `schedule_${scheduleCount}`;
        newSchedule.className = 'schedule-card mb-3 p-3 border rounded';
        newSchedule.style.backgroundColor = '#f8f9fa';
        newSchedule.style.position = 'relative';
        newSchedule.innerHTML = `
            <div class="row gx-2 gy-3">
                <div class="col-md-2">
                    <label class="form-label fw-bold">Day *</label>
                    <select name="schedules[${scheduleCount}][day_of_week]" class="form-select form-select-sm" required>
                        <option value="">Select</option>
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                        <option value="7">Sunday</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Start Time *</label>
                    <input type="time" name="schedules[${scheduleCount}][start_time]" class="form-control form-control-sm" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">End Time *</label>
                    <input type="time" name="schedules[${scheduleCount}][end_time]" class="form-control form-control-sm" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Break Start</label>
                    <input type="time" name="schedules[${scheduleCount}][break_start]" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Break End</label>
                    <input type="time" name="schedules[${scheduleCount}][break_end]" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold d-block">Active</label>
                    <div class="form-check">
                        <input type="checkbox" name="schedules[${scheduleCount}][is_active]" class="form-check-input" value="1" id="active_${scheduleCount}" checked>
                        <label class="form-check-label small" for="active_${scheduleCount}">Yes</label>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeSchedule(${scheduleCount})">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newSchedule);
        scheduleCount++;
    }

    function removeSchedule(index) {
        const element = document.getElementById(`schedule_${index}`);
        if (element) {
            element.remove();
            console.log(`Schedule row ${index} removed`);
        } else {
            console.warn(`Schedule element with ID schedule_${index} not found`);
        }
    }
</script>
@endsection
