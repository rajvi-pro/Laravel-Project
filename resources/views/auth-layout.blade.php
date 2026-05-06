<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hospital Management System')</title>
    @php
        $manifestPath = public_path('build/manifest.json');
        $isViteDev = app()->environment('local') && env('VITE_DEV_SERVER_URL');
    @endphp
    @if (file_exists($manifestPath) || $isViteDev)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Vite manifest missing and dev server not configured; fallback to compiled assets -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script defer src="{{ asset('js/app.js') }}"></script>
    @endif

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

        .auth-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h2 {
            color: #1a1a1a;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #999;
            font-size: 14px;
            margin: 0;
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

        .form-control.is-invalid {
            border-color: #c62828;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }

        .invalid-feedback {
            color: #c62828;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .auth-footer {
            margin-top: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }

        .auth-footer p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }

        .auth-footer a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .text-muted {
            color: #666 !important;
        }

        .mt-3 {
            margin-top: 1.5rem !important;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
