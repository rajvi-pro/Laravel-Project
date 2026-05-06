@extends('admin-layout')

@section('title', 'Edit Doctor')

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
        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-secondary btn-sm" aria-label="Back to Doctor Details">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Back to Details
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h1 class="h4 mb-0"><i class="bi bi-pencil-square" aria-hidden="true"></i> Edit Doctor - {{ $doctor->name }}</h1>
        </div>
        <div class="card-body" style="max-height: 85vh; overflow-y: auto;">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert" aria-labelledby="validation-errors-title">
                    <h2 id="validation-errors-title" class="alert-heading">
                        <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> Validation Errors
                    </h2>
                    <ul class="mb-0" aria-live="polite">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.doctors.update', $doctor->id) }}" novalidate>
                @csrf
                @method('PUT')

                <fieldset>
                    <legend class="mb-4 fs-6 fw-bold">Basic Information</legend>

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span aria-label="required">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $doctor->name) }}" 
                                required
                                aria-required="true"
                                @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                            @error('name')
                                <div id="name-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span aria-label="required">*</span></label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $doctor->email) }}" 
                                required
                                aria-required="true"
                                aria-describedby="email-help"
                                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            <small id="email-help" class="form-text text-muted d-block">Enter a valid email address</small>
                            @error('email')
                                <div id="email-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number <span aria-label="required">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', $doctor->phone) }}" 
                                pattern="[0-9]{10}" 
                                maxlength="10" 
                                required
                                aria-required="true"
                                aria-describedby="phone-help"
                                inputmode="numeric"
                                @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                            <small id="phone-help" class="form-text text-muted d-block">Must be exactly 10 digits</small>
                            @error('phone')
                                <div id="phone-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div class="col-md-6 mb-3">
                            <label for="specialization" class="form-label">Specialization <span aria-label="required">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('specialization') is-invalid @enderror" 
                                id="specialization" 
                                name="specialization" 
                                value="{{ old('specialization', $doctor->specialization) }}" 
                                required
                                aria-required="true"
                                @error('specialization') aria-invalid="true" aria-describedby="specialization-error" @enderror>
                            @error('specialization')
                                <div id="specialization-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Qualification -->
                        <div class="col-md-6 mb-3">
                            <label for="qualification" class="form-label">Qualification <span aria-label="required">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('qualification') is-invalid @enderror" 
                                id="qualification" 
                                name="qualification" 
                                value="{{ old('qualification', $doctor->qualification) }}" 
                                required
                                aria-required="true"
                                @error('qualification') aria-invalid="true" aria-describedby="qualification-error" @enderror>
                            @error('qualification')
                                <div id="qualification-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Experience Years -->
                        <div class="col-md-6 mb-3">
                            <label for="experience_years" class="form-label">Years of Experience <span aria-label="required">*</span></label>
                            <input 
                                type="number" 
                                class="form-control @error('experience_years') is-invalid @enderror" 
                                id="experience_years" 
                                name="experience_years" 
                                value="{{ old('experience_years', $doctor->experience_years) }}" 
                                min="0" 
                                required
                                aria-required="true"
                                @error('experience_years') aria-invalid="true" aria-describedby="experience_years-error" @enderror>
                            @error('experience_years')
                                <div id="experience_years-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Consultation Fee -->
                        <div class="col-md-6 mb-3">
                            <label for="consultation_fee" class="form-label">Consultation Fee (Rs.) <span aria-label="required">*</span></label>
                            <input 
                                type="number" 
                                class="form-control @error('consultation_fee') is-invalid @enderror" 
                                id="consultation_fee" 
                                name="consultation_fee" 
                                value="{{ old('consultation_fee', $doctor->consultation_fee) }}" 
                                step="0.01" 
                                min="0"
                                aria-required="true"
                                @error('consultation_fee') aria-invalid="true" aria-describedby="consultation_fee-error" @enderror>
                            @error('consultation_fee')
                                <div id="consultation_fee-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Created Date -->
                        <div class="col-md-6 mb-3">
                            <label for="created_at" class="form-label">Registered On</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="created_at"
                                value="{{ $doctor->created_at->format('d M Y, h:i A') }}" 
                                disabled
                                aria-disabled="true"
                                aria-label="Doctor registration date, cannot be edited">
                        </div>
                    </div>
                </fieldset>

                <!-- Working Schedule Section -->
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-4">
                        <i class="bi bi-calendar-check"></i> Doctor's Weekly Schedule
                    </h5>
                    
                    <div id="scheduleContainer">
                        @forelse($doctor->schedules as $index => $schedule)
                            <div class="schedule-card mb-3 p-3 border rounded" id="schedule_{{ $index }}" style="background-color: #f8f9fa; position: relative;">
                                <div class="row gx-2 gy-3">
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Day *</label>
                                        <select name="schedules[{{ $index }}][day_of_week]" class="form-select form-select-sm" required>
                                            <option value="">Select</option>
                                            <option value="monday" {{ strtolower($schedule->day_of_week) == 'monday' || $schedule->day_of_week == 1 ? 'selected' : '' }}>Monday</option>
                                            <option value="tuesday" {{ strtolower($schedule->day_of_week) == 'tuesday' || $schedule->day_of_week == 2 ? 'selected' : '' }}>Tuesday</option>
                                            <option value="wednesday" {{ strtolower($schedule->day_of_week) == 'wednesday' || $schedule->day_of_week == 3 ? 'selected' : '' }}>Wednesday</option>
                                            <option value="thursday" {{ strtolower($schedule->day_of_week) == 'thursday' || $schedule->day_of_week == 4 ? 'selected' : '' }}>Thursday</option>
                                            <option value="friday" {{ strtolower($schedule->day_of_week) == 'friday' || $schedule->day_of_week == 5 ? 'selected' : '' }}>Friday</option>
                                            <option value="saturday" {{ strtolower($schedule->day_of_week) == 'saturday' || $schedule->day_of_week == 6 ? 'selected' : '' }}>Saturday</option>
                                            <option value="sunday" {{ strtolower($schedule->day_of_week) == 'sunday' || $schedule->day_of_week == 7 ? 'selected' : '' }}>Sunday</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Start Time *</label>
                                        <input type="time" name="schedules[{{ $index }}][start_time]" class="form-control form-control-sm" value="{{ $schedule->start_time }}" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">End Time *</label>
                                        <input type="time" name="schedules[{{ $index }}][end_time]" class="form-control form-control-sm" value="{{ $schedule->end_time }}" required>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Break Start</label>
                                        <input type="time" name="schedules[{{ $index }}][break_start]" class="form-control form-control-sm" value="{{ $schedule->break_start ?? '' }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Break End</label>
                                        <input type="time" name="schedules[{{ $index }}][break_end]" class="form-control form-control-sm" value="{{ $schedule->break_end ?? '' }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-bold d-block">Active</label>
                                        <div class="form-check">
                                            <input type="checkbox" name="schedules[{{ $index }}][is_active]" class="form-check-input" value="1" id="active_{{ $index }}" {{ $schedule->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="active_{{ $index }}">Yes</label>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="schedules[{{ $index }}][id]" value="{{ $schedule->id }}">
                                
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeSchedule({{ $index }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <div class="schedule-card mb-3 p-3 border rounded" id="schedule_0" style="background-color: #f8f9fa; position: relative;">
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
                        @endforelse
                    </div>

                    <button type="button" class="btn btn-sm btn-success mt-3" onclick="addSchedule()">
                        <i class="bi bi-plus-circle"></i> Add Another Day
                    </button>
                </div>

                <!-- Password Reset Section -->
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-3">
                        <i class="bi bi-key-fill"></i> Reset Password (Optional)
                    </h5>
                    <div class="alert alert-info small mb-4" role="alert">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        Leave password fields blank to keep the current password unchanged.
                    </div>

                    <div class="row">
                        <!-- New Password -->
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input 
                                type="password" 
                                class="form-control @error('new_password') is-invalid @enderror" 
                                id="new_password" 
                                name="new_password" 
                                placeholder="Leave blank to keep current password"
                                aria-describedby="password-help"
                                @error('new_password') aria-invalid="true" aria-describedby="new_password-error" @enderror>
                            <small id="password-help" class="form-text text-muted d-block mt-1">Must contain uppercase, lowercase, digit, and be at least 8 characters</small>
                            @error('new_password')
                                <div id="new_password-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                id="new_password_confirmation" 
                                name="new_password_confirmation" 
                                placeholder="Confirm new password"
                                @error('new_password_confirmation') aria-invalid="true" aria-describedby="new_password_confirmation-error" @enderror>
                            @error('new_password_confirmation')
                                <div id="new_password_confirmation-error" class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-5 pt-4 border-top d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-success" aria-label="Save all changes to doctor information">
                        <i class="bi bi-check-circle" aria-hidden="true"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-secondary" aria-label="Cancel editing and return to doctor details">
                        <i class="bi bi-x-circle" aria-hidden="true"></i> Cancel
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
        outline: 2px solid #007bff;
    }
    
    /* Ensure proper contrast for alerts */
    .alert-danger {
        background-color: #f8d7da;
        border: 2px solid #721c24;
        color: #721c24;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border: 2px solid #856404;
        color: #856404;
    }
    
    /* Schedule item styling */
    .schedule-item {
        position: relative;
    }
    
    .schedule-item .btn-danger {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    /* Ensure required fields are visually distinct */
    [aria-required="true"],
    [required] {
        box-shadow: inset 2px 0 0 #dc3545;
    }
    
    /* Focus visible for keyboard navigation */
    .form-control:focus-visible,
    .btn:focus-visible,
    a:focus-visible {
        outline: 3px solid #007bff;
        outline-offset: 2px;
    }
    
    /* Better contrast for disabled fields */
    .form-control:disabled {
        background-color: #e9ecef;
        color: #545454;
        opacity: 1;
    }
    
    /* Ensure form text is readable */
    .form-text {
        color: #666 !important;
        font-size: 0.9rem;
    }
    
    /* Error messages with better visibility */
    .invalid-feedback {
        display: block !important;
        color: #721c24 !important;
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    /* Fieldset styling for better semantic structure */
    fieldset {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    legend {
        width: auto;
        padding: 0 0.5rem;
        margin-left: -0.5rem;
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

    /* Icon visibility improvements */
    .bi {
        color: inherit;
    }
</style>

<script>
    let scheduleCount = {{ isset($doctor->schedules) ? count($doctor->schedules) : 1 }};

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
