@extends('layouts.patient-layout')

@section('page-title', 'Edit Profile')
@section('title', 'Edit Profile - HMS')

@section('content')
<style>
    .page-title {
        color: #1a1a1a;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        max-width: 800px;
    }

    .form-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 25px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #0d47a1;
    }

    .form-label {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 14px;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
    }

    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 13px;
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
        outline: none;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row.full {
        grid-template-columns: 1fr;
    }

    .btn-save {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-right: 10px;
    }

    .btn-save:hover {
        box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .btn-cancel {
        background: white;
        border: 2px solid #ddd;
        color: #1a1a1a;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        border-color: #0d47a1;
        color: #0d47a1;
    }

    .alert {
        border-radius: 6px;
        margin-bottom: 20px;
        padding: 15px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .text-danger {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<h2 class="page-title">Edit Profile</h2>

<div class="form-card">
    <h3><i class="fas fa-user-edit" style="color: #0d47a1; margin-right: 10px;"></i> Personal Information</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('patient.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div>
                <label class="form-label">Full Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $patient->name ?? '') }}" required>
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Email Address *</label>
                <input type="email" class="form-control" name="email" value="{{ $patient->email ?? '' }}" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
            </div>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label">Phone *</label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $patient->phone ?? '') }}" pattern="[0-9]{10}" maxlength="10" required>
                <small class="form-text text-muted">Must be exactly 10 digits</small>
                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Date of Birth *</label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}" required>
                @error('date_of_birth') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label">Gender *</label>
                <select class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male" @if(old('gender', strtolower($patient->gender ?? '')) == 'male') selected @endif>Male</option>
                    <option value="female" @if(old('gender', strtolower($patient->gender ?? '')) == 'female') selected @endif>Female</option>
                    <option value="other" @if(old('gender', strtolower($patient->gender ?? '')) == 'other') selected @endif>Other</option>
                </select>
                @error('gender') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Blood Group *</label>
                <select class="form-select @error('blood_group') is-invalid @enderror" name="blood_group" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+" @if(old('blood_group', $patient->blood_group ?? '') == 'A+') selected @endif>A+</option>
                    <option value="A-" @if(old('blood_group', $patient->blood_group ?? '') == 'A-') selected @endif>A-</option>
                    <option value="B+" @if(old('blood_group', $patient->blood_group ?? '') == 'B+') selected @endif>B+</option>
                    <option value="B-" @if(old('blood_group', $patient->blood_group ?? '') == 'B-') selected @endif>B-</option>
                    <option value="O+" @if(old('blood_group', $patient->blood_group ?? '') == 'O+') selected @endif>O+</option>
                    <option value="O-" @if(old('blood_group', $patient->blood_group ?? '') == 'O-') selected @endif>O-</option>
                    <option value="AB+" @if(old('blood_group', $patient->blood_group ?? '') == 'AB+') selected @endif>AB+</option>
                    <option value="AB-" @if(old('blood_group', $patient->blood_group ?? '') == 'AB-') selected @endif>AB-</option>
                </select>
                @error('blood_group') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row full">
            <div>
                <label class="form-label">Address *</label>
                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2" required>{{ old('address', $patient->address ?? '') }}</textarea>
                @error('address') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label">City *</label>
                <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city', $patient->city ?? '') }}" required>
                @error('city') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">State *</label>
                <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state', $patient->state ?? '') }}" required>
                @error('state') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label">Pincode *</label>
                <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" value="{{ old('pincode', $patient->pincode ?? '') }}" required>
                @error('pincode') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Emergency Contact *</label>
                <input type="tel" class="form-control @error('emergency_contact') is-invalid @enderror" name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact ?? '') }}" required>
                @error('emergency_contact') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" name="save_profile" value="1" class="btn-save">
                <i class="fas fa-save"></i>
                {{ $requiresFullProfile ?? false ? 'Save Profile' : 'Update Profile' }}
            </button>
            <a href="{{ route('patient.profile') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

@endsection
