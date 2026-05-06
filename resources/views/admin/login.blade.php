@extends('auth-layout')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h2>Admin Login</h2>
        <p class="text-muted">Hospital Management System</p>
    </div>

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-login">Login</button>

        <div class="mt-3 text-center">
            <small class="text-muted">Demo: admin@hms.local / Admin@123</small>
        </div>
    </form>
</div>
@endsection
