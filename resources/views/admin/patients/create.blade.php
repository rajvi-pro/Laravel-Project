@extends('admin-layout')

@section('title', 'Register Patient')

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
            <h5 class="mb-0">Register New Patient</h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545; margin-bottom: 20px;">
                    <strong><i class="fas fa-exclamation-circle"></i> Registration Error!</strong>
                    <hr>
                    <ul style="margin-bottom: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li style="margin-bottom: 8px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.patients.store') }}">
                @csrf

                <!-- Name and Email Row -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Phone and Gender Row -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" value="{{ old('phone') }}" placeholder="03001234567" pattern="[0-9]{10}" maxlength="10">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">-- Select --</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Date of Birth and Blood Group Row -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                            id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="blood_group" class="form-label">Blood Group</label>
                        <select class="form-select @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group">
                            <option value="">-- Select --</option>
                            <option value="O+" {{ old('blood_group') === 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') === 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="A+" {{ old('blood_group') === 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') === 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') === 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') === 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group') === 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') === 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                        @error('blood_group')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Address Row -->
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                        id="address" name="address" rows="3" placeholder="Street address">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- City, State, Pincode Row -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                            id="city" name="city" value="{{ old('city') }}">
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror" 
                            id="state" name="state" value="{{ old('state') }}">
                        @error('state')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control @error('pincode') is-invalid @enderror" 
                            id="pincode" name="pincode" value="{{ old('pincode') }}">
                        @error('pincode')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Emergency Contact Row -->
                <div class="mb-3">
                    <label for="emergency_contact" class="form-label">Emergency Contact</label>
                    <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                        id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}" 
                        placeholder="Name and phone number">
                    @error('emergency_contact')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Row -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                        id="password" name="password" placeholder="Minimum 6 characters" required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Register Patient
                    </button>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
