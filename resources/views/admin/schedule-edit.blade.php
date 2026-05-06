@extends('admin-layout')

@section('title', 'Edit Schedule - ' . $doctor->name)

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
        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Doctor
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Schedule - {{ $doctor->name }}</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.schedule-update', $schedule->id) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                    <select id="day_of_week" name="day_of_week" class="form-select" required>
                        <option value="">-- Select a day --</option>
                        @foreach ($days as $day)
                            <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) === $day ? 'selected' : '' }}>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" id="start_time" name="start_time" class="form-control" 
                           value="{{ old('start_time', $schedule->start_time) }}" required>
                    <small class="form-text text-muted">E.g., 09:00</small>
                </div>

                <div class="col-md-6">
                    <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" id="end_time" name="end_time" class="form-control" 
                           value="{{ old('end_time', $schedule->end_time) }}" required>
                    <small class="form-text text-muted">E.g., 17:00</small>
                </div>

                <div class="col-md-6">
                    <label for="break_start" class="form-label">Break Start Time (Optional)</label>
                    <input type="time" id="break_start" name="break_start" class="form-control" 
                           value="{{ old('break_start', $schedule->break_start) }}">
                    <small class="form-text text-muted">E.g., 12:00</small>
                </div>

                <div class="col-md-6">
                    <label for="break_end" class="form-label">Break End Time (Optional)</label>
                    <input type="time" id="break_end" name="break_end" class="form-control" 
                           value="{{ old('break_end', $schedule->break_end) }}">
                    <small class="form-text text-muted">E.g., 13:00</small>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" class="form-check-input" 
                               value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            This schedule is active and available for appointments
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <hr>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Schedule
                    </button>
                    <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-secondary">
                        <i class="bi bi-xmark-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 2px solid #0d6efd;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endsection
