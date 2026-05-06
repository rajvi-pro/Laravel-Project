@extends('auth-layout')

@section('content')
<style>
    .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .login-container {
        display: flex;
        width: 100%;
        max-width: 1000px;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .login-left {
        flex: 1;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: white;
    }

    .login-left h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .login-left p {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 30px;
        opacity: 0.95;
    }

    .feature-list {
        list-style: none;
        padding: 0;
    }

    .feature-list li {
        padding: 12px 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    .feature-list li::before {
        content: "✓";
        display: inline-block;
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        margin-right: 15px;
        font-weight: bold;
    }

    .login-right {
        flex: 1;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-right h2 {
        font-size: 2rem;
        color: #1f2937;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .login-right .subtitle {
        color: #6b7280;
        margin-bottom: 40px;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        display: block;
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .btn-login {
        width: 100%;
        padding: 12px 20px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    .login-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        text-align: center;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .demo-credentials {
        background: #f0f4ff;
        border-left: 4px solid #4f46e5;
        padding: 15px;
        border-radius: 6px;
        margin-top: 25px;
        font-size: 0.9rem;
    }

    .demo-credentials strong {
        color: #1f2937;
        display: block;
        margin-bottom: 8px;
    }

    .demo-credentials code {
        background: white;
        padding: 2px 6px;
        border-radius: 3px;
        color: #4f46e5;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
        }

        .login-left {
            padding: 40px 30px;
            border-radius: 0;
        }

        .login-right {
            padding: 40px 30px;
        }

        .login-left h1 {
            font-size: 1.75rem;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-container">
        <!-- Left Side -->
        <div class="login-left">
            <div>
                <h1>Healthcare Made Easy</h1>
                <p>Manage your patient appointments, medical reports, and prescriptions all in one place.</p>
                <ul class="feature-list">
                    <li>Manage Patient Appointments</li>
                    <li>Create Medical Reports</li>
                    <li>Issue Prescriptions</li>
                    <li>View Lab Results</li>
                </ul>
            </div>
        </div>

        <!-- Right Side -->
        <div class="login-right">
            <h2>Doctor Login</h2>
            <p class="subtitle">Sign in to your account</p>

            <form method="POST" action="{{ route('doctor.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="your.email@hospital.com"
                        required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login">Sign In</button>

                <!-- Demo Credentials -->
                <div class="demo-credentials">
                    <strong>📝 Test Credentials:</strong>
                    Email: <code>rajesh.kumar@hospital.com</code><br>
                    Password: <code>Doctor@123</code>
                </div>
            </form>

            <div class="login-footer">
                <p>For technical support, contact the hospital IT department</p>
            </div>
        </div>
    </div>
</div>
@endsection
