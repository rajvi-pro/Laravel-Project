@extends('auth-layout')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h2>Reset Admin Password</h2>
        <p class="text-muted">Hospital Management System</p>
    </div>

    <form method="POST" action="{{ route('admin.forgot-password.update') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" required>
            @error('new_password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-login">Reset Password</button>

        <div class="mt-3 text-center">
            <a href="{{ route('admin.login') }}" class="btn btn-link">Back to Login</a>
        </div>
    </form>
</div>
@endsection
