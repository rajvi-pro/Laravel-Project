@extends('admin-layout')

@section('title', 'Edit Patient - ' . $patient->name)

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}" class="active"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
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
        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Patients
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Patient Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $patient->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $patient->email) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $patient->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($patient->date_of_birth)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="">Choose</option>
                            <option value="male" {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <input type="text" name="blood_group" id="blood_group" class="form-control" value="{{ old('blood_group', $patient->blood_group) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ old('emergency_contact', $patient->emergency_contact) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $patient->address) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
