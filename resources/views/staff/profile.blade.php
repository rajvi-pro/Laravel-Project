@extends('layouts.staff-layout')

@section('page-title', 'Staff Profile')
@section('title', 'Staff Profile - HMS')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Staff Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Name</strong></label>
                            <p class="form-control-plaintext">{{ $staff->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Email</strong></label>
                            <p class="form-control-plaintext">{{ $staff->email }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Phone</strong></label>
                            <p class="form-control-plaintext">{{ $staff->phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Role</strong></label>
                            <p class="form-control-plaintext">{{ $staff->role }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Department</strong></label>
                            <p class="form-control-plaintext">{{ $staff->department }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Shift</strong></label>
                            <p class="form-control-plaintext">{{ ucfirst($staff->shift ?? 'Not specified') }}</p>
                        </div>
                    </div>

                    <!-- Password Change Note -->
                    <div class="alert alert-info mt-4">
                        <strong>Note:</strong> To change your password, please visit the <a href="{{ route('staff.change-password') }}" class="alert-link">Change Password</a> page.
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
