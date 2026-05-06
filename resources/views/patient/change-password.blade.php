@extends('layouts.patient-layout')

@section('page-title', 'Change Password')
@section('title', 'Change Password - HMS')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lock"></i> Change Password</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('patient.change-password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-6">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" required placeholder="Enter your current password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-6">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" required placeholder="Enter your new password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-6">Confirm New Password</label>
                            <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                   name="new_password_confirmation" required placeholder="Confirm your new password">
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-lock-fill"></i> Change Password
                            </button>
                            <a href="{{ route('patient.profile') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-muted">
                            <a href="{{ route('patient.forgot-password') }}" class="text-decoration-none">
                                <i class="bi bi-question-circle"></i> Forgot your password? Reset it here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 1.5rem;
        font-weight: 600;
    }

    .form-label {
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }
</style>
@endsection