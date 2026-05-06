@extends('auth-layout')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h2>Patient Registration</h2>
        <p class="text-muted">Create your account</p>
    </div>

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

    <form method="POST" action="{{ route('patient.register.submit') }}" id="registrationForm">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

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

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-login" id="registerBtn">Register</button>
        <div id="loadingMsg" style="display:none; text-align:center; margin-top:10px;">
            <small class="text-muted">✓ Registration successful! Redirecting to login...</small>
        </div>

        <div class="mt-3 text-center">
            <p class="text-muted">Already have an account? <a href="{{ route('patient.login') }}">Login here</a></p>
        </div>
    </form>
</div>

<script>
    document.getElementById('registrationForm').addEventListener('submit', function() {
        document.getElementById('registerBtn').style.display = 'none';
        document.getElementById('loadingMsg').style.display = 'block';
    });
</script>
@endsection
