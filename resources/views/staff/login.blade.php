<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6b46c1 0%, #8b5cf6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #6b46c1 0%, #8b5cf6 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-header i {
            font-size: 36px;
        }

        .login-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1a1a1a;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #1a1a1a;
        }

        .form-control:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #999;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 10;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6b46c1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .remember-me input {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            margin: 0;
            cursor: pointer;
            font-size: 14px;
            color: #666;
        }

        .login-footer {
            background: #f9f9f9;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }

        .login-footer a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #c62828;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .form-control.is-invalid {
            border-color: #c62828;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }

        @media (max-width: 576px) {
            .login-header {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1>
                    Staff Login
                </h1>
                <p>Hospital Management System</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Alerts -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('staff.login.submit') }}" method="POST">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            required
                        >
                        @error('email')
                            <span class="error-message" style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password
                        </label>
                        <div class="password-toggle">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password"
                                required
                            >
                            <span class="password-toggle-icon" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="error-message" style="display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        <label for="remember">Use your staff credentials</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-btn">
                        Login
                    </button>

                    <!-- Demo Credentials -->
                    <div style="background: #f0f7ff; border-left: 4px solid #8b5cf6; padding: 12px; margin-top: 20px; border-radius: 4px; font-size: 13px;">
                        <strong>📝 Test Credentials:</strong><br>
                        Email: <code>ramesh.kumar@hospital.com</code><br>
                        Password: <code>Staff@123</code>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>
                    Having trouble? <a href="#"><i class="fas fa-question-circle"></i> Get Help</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
