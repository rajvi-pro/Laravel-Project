@extends('layouts.staff-layout')

@section('page-title', 'Edit Patient')
@section('title', 'Edit Patient Information - Staff Portal')

@section('content')
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.patient.detail', $patient->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Patient
        </a>
    </div>

    <div class="table-container" style="max-width: 700px;">
        <h3 style="margin-bottom: 30px; font-weight: 700; color: #1a1a1a;">
            <i class="bi bi-pencil-square"></i> Edit Patient Information
        </h3>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-circle"></i> Validation Error
                </h5>
                <ul class="mb-0" style="padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Patient Basic Info (Read-only for reference) -->
        <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h5 style="margin-bottom: 15px; font-weight: 600; color: #666;">Patient Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <p style="margin: 0; font-size: 14px; color: #666;"><strong>Full Name:</strong></p>
                    <p style="margin: 0; font-size: 16px;">{{ $patient->name ?? $patient->full_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p style="margin: 0; font-size: 14px; color: #666;"><strong>Patient ID:</strong></p>
                    <p style="margin: 0; font-size: 16px;">{{ $patient->id ?? $patient->medical_id ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p style="margin: 0; font-size: 14px; color: #666;"><strong>Date of Birth:</strong></p>
                    <p style="margin: 0; font-size: 16px;">
                        @if($patient->date_of_birth)
                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('F d, Y') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-2">
                    <p style="margin: 0; font-size: 14px; color: #666;"><strong>Gender:</strong></p>
                    <p style="margin: 0; font-size: 16px;">{{ ucfirst($patient->gender ?? 'N/A') }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('staff.patient.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" style="font-weight: 600; color: #333;">
                        <i class="bi bi-telephone"></i> Phone Number
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        name="phone" 
                        value="{{ old('phone', $patient->phone ?? '') }}"
                        placeholder="Enter phone number"
                    >
                    @error('phone')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" style="font-weight: 600; color: #333;">
                        <i class="bi bi-envelope"></i> Email Address
                    </label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        name="email" 
                        value="{{ old('email', $patient->email ?? '') }}"
                        placeholder="Enter email address"
                    >
                    @error('email')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-weight: 600; color: #333;">
                    <i class="bi bi-geo-alt"></i> Street Address
                </label>
                <input 
                    type="text" 
                    class="form-control @error('address') is-invalid @enderror" 
                    name="address" 
                    value="{{ old('address', $patient->address ?? '') }}"
                    placeholder="Enter street address"
                >
                @error('address')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="mt-4" style="display: flex; gap: 10px;">
                <button 
                    type="submit" 
                    class="btn btn-success" 
                    style="padding: 12px 30px; font-size: 1rem; font-weight: 600; border-radius: 6px;"
                >
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
                <a 
                    href="{{ route('staff.patient.detail', $patient->id) }}" 
                    class="btn btn-secondary" 
                    style="padding: 12px 30px; font-size: 1rem; font-weight: 600; border-radius: 6px;"
                >
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
