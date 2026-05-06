@extends('admin-layout')

@section('title', 'Edit Staff')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}" class="active"><i class="bi bi-person-badge"></i> Staff</a>
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
        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Staff
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Staff Member - {{ $staff->name }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $staff->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $staff->phone) }}" pattern="[0-9]{10}" maxlength="10" required>
                        <small class="form-text text-muted">Must be exactly 10 digits</small>
                    </div>

                    <!-- Role -->
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role *</label>
                        <input type="text" class="form-control" id="role" name="role" value="{{ old('role', $staff->role) }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Department -->
                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Department *</label>
                        <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $staff->department) }}" required>
                    </div>

                    <!-- Shift -->
                    <div class="col-md-6 mb-3">
                        <label for="shift" class="form-label">Shift</label>
                        <select class="form-control" id="shift" name="shift">
                            <option value="">Select Shift</option>
                            <option value="morning" {{ old('shift', $staff->shift) == 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="afternoon" {{ old('shift', $staff->shift) == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                            <option value="evening" {{ old('shift', $staff->shift) == 'evening' ? 'selected' : '' }}>Evening</option>
                            <option value="night" {{ old('shift', $staff->shift) == 'night' ? 'selected' : '' }}>Night</option>
                        </select>
                    </div>
                </div>

                <!-- Password Reset Section -->
                <div class="alert alert-warning">
                    <strong>Reset Password:</strong> Use the form below to reset this staff member's password.
                </div>

                <div class="row">
                    <!-- New Password -->
                    <div class="col-md-6 mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="Leave blank to keep current password">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password">
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Staff Member
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
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
</style>
@endsection
