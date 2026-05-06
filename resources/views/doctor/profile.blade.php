@extends('layouts.doctor-layout')

@section('page-title', 'Doctor Profile')
@section('title', 'Doctor Profile - HMS')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Doctor Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Name</strong></label>
                            <p class="form-control-plaintext">{{ $doctor->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Email</strong></label>
                            <p class="form-control-plaintext">{{ $doctor->email }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Phone</strong></label>
                            <p class="form-control-plaintext">{{ $doctor->phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Specialization</strong></label>
                            <p class="form-control-plaintext">{{ $doctor->specialization }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Qualification</strong></label>
                            <p class="form-control-plaintext">{{ $doctor->qualification }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Experience</strong></label>
                            <p class="form-control-plaintext">{{ $doctor->experience_years }} years</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Consultation Fee</strong></label>
                            <p class="form-control-plaintext">Rs. {{ $doctor->consultation_fee }}</p>
                        </div>
                    </div>

                    <!-- Password Change Note -->
                    <div class="alert alert-info mt-4">
                        <strong>Note:</strong> To change your password, please visit the <a href="{{ route('doctor.change-password') }}" class="alert-link">Change Password</a> page.
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
