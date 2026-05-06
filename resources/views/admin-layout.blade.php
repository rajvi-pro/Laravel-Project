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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --muted: #6b7280;
            --primary: #0d6efd;
            --accent: #7c3aed;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 50%, #90caf9 100%);
            color: #111827;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 50%, #0d47a1 100%);
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            padding: 24px 16px;
            color: #ffffff;
            overflow-y: auto;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            border-right: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 3px 0 15px rgba(13, 71, 161, 0.4);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .main-content {
            position: relative;
            z-index: 0;
            margin-left: 280px;
            min-height: 100vh;
            padding: 28px;
            background: linear-gradient(135deg, #f0f4f8 0%, #e8eef7 50%, #e1e8f0 100%);
            box-shadow: inset 0 1px 2px rgba(13, 71, 161, 0.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding: 16px 12px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .brand .logo {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #ffffff;
            font-size: 22px;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25), inset 0 1px 2px rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .brand div:last-child {
            flex: 1;
        }

        .brand h5 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px !important;
            letter-spacing: 0.8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .brand small {
            font-size: 12px;
            opacity: 0.9;
            display: block;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0);
        }

        .sidebar a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            transform: scaleY(0);
            transform-origin: center;
            transition: transform 0.3s ease;
        }

        .sidebar a i {
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateX(6px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .sidebar a:hover::before {
            transform: scaleY(1);
        }

        .sidebar a:hover i {
            transform: scale(1.15);
        }

        .sidebar a.active {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25), inset 0 0 0 1px rgba(255, 255, 255, 0.3);
            border-left: 4px solid #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .sidebar a.active::before {
            transform: scaleY(1);
        }

        .sidebar hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 16px 0;
        }

        .sidebar form {
            margin-top: auto;
        }

        .sidebar form button {
            width: 100%;
            padding: 12px 16px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .sidebar form button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
        }

        .sidebar form button:hover {
            background: linear-gradient(135deg, #c82333 0%, #b01d2a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
        }

        .sidebar form button:hover::before {
            left: 100%;
        }

        .sidebar form button:active {
            transform: translateY(0);
        }

        .sidebar form button i {
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        .sidebar form button span {
            position: relative;
            z-index: 1;
        }

        .sidebar-profile-card {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            padding: 12px 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            color: #ffffff;
            font-size: 13px;
        }

        /* Professional Table Styling */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(13, 71, 161, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            overflow: hidden;
            width: 100%;
            border: 1px solid rgba(13, 71, 161, 0.08);
            transition: all 0.3s ease;
        }

        .table-container:hover {
            box-shadow: 0 6px 25px rgba(13, 71, 161, 0.15), 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 20px 25px;
            font-weight: 700;
            font-size: 16px;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.3px;
        }

        .card {
            border: 1px solid rgba(13, 71, 161, 0.08) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(13, 71, 161, 0.1) !important;
            margin-bottom: 20px;
            background: white !important;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 6px 25px rgba(13, 71, 161, 0.15) !important;
            transform: translateY(-2px);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f5f7fa !important;
        }

        .table th {
            background-color: #f5f7fa !important;
            padding: 15px 20px !important;
            text-align: left;
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #1a1a1a !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0 !important;
        }

        .table td {
            padding: 15px 20px !important;
            border-bottom: 1px solid #e0e0e0 !important;
            font-size: 14px;
            color: #1a1a1a;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f5f7fa !important;
        }

        /* Professional Buttons */
        .btn {
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%) !important;
            border: none !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0a3580 0%, #1250a0 100%) !important;
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
            transform: translateY(-2px);
            color: white !important;
        }

        .btn-info {
            background: #0d47a1 !important;
            border: none !important;
            color: white !important;
        }

        .btn-info:hover {
            background: #0a3580 !important;
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
            color: white !important;
        }

        .btn-warning {
            background: white !important;
            border: 1px solid #ff9800 !important;
            color: #ff9800 !important;
        }

        .btn-warning:hover {
            background: #ff9800 !important;
            color: white !important;
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
        }

        .btn-danger {
            background: white !important;
            border: 1px solid #e53935 !important;
            color: #e53935 !important;
        }

        .btn-danger:hover {
            background: #e53935 !important;
            color: white !important;
            box-shadow: 0 5px 15px rgba(229, 57, 53, 0.3);
        }

        .btn-sm {
            padding: 6px 12px !important;
            font-size: 12px !important;
        }

        /* Page Header - Professional */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header i {
            color: #ffffff;
        }

        /* Badge Styling */
        .badge {
            padding: 6px 12px !important;
            border-radius: 20px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            display: inline-block;
        }

        .badge-success {
            background-color: #c8e6c9 !important;
            color: #2e7d32 !important;
        }

        .badge-warning {
            background-color: #ffe0b2 !important;
            color: #e65100 !important;
        }

        .badge-danger {
            background-color: #ffcdd2 !important;
            color: #c62828 !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
            color: #ddd;
        }

        .sidebar-profile-card .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #ffffff;
        }

        .sidebar-profile-card small {
            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .search-input {
            max-width: 420px;
            width: 420px;
            min-width: 350px;
            flex-shrink: 0;
        }

        .search-input .form-control {
            transition: all 0.3s ease;
            border: 2px solid rgba(13, 71, 161, 0.15);
            border-radius: 10px;
            padding: 10px 16px;
            background: white;
            box-shadow: 0 2px 8px rgba(13, 71, 161, 0.08);
        }

        .search-input .form-control:focus {
            box-shadow: 0 4px 16px rgba(13, 71, 161, 0.2);
            border-color: #0d47a1;
            background: #f8f9fc;
            outline: none;
        }

        #search-loading {
            width: 38px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        #search-loading.active {
            opacity: 1;
        }

        .card-panel {
            background: var(--card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }

        .muted {
            color: var(--muted);
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: #0b5ed7;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-bottom: 20px;
            }

            .main-content {
                margin-left: 0;
                min-height: auto;
                padding: 16px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <div class="logo">H</div>
            <div>
                <h5 class="mb-0 text-white">
                    @if(session('admin_logged_in'))
                        HMS Admin
                    @elseif(session('doctor_logged_in'))
                        HMS Doctor
                    @elseif(session('patient_logged_in'))
                        HMS Patient
                    @elseif(session('staff_logged_in'))
                        HMS Staff
                    @else
                        HMS Admin
                    @endif
                </h5>
                <small class="text-white-50">Hospital Management</small>
            </div>
        </div>

        <nav>
            @if(session('admin_logged_in'))
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
                <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.*') ? 'active' : '' }}"><i class="fa fa-user-injured"></i> Patients</a>
                <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"><i class="fa fa-user-md"></i> Doctors</a>
                <a href="{{ route('admin.staff.index') }}" class="{{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"><i class="fa fa-user-tie"></i> Staff</a>
                <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"><i class="fa fa-calendar-check"></i> Appointments</a>
                <a href="{{ route('admin.billing.index') }}" class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}"><i class="fa fa-file-invoice-dollar"></i> Billing</a>
                <hr class="bg-light">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></button>
                </form>
            @elseif(session('doctor_logged_in'))
                <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
                <a href="{{ route('doctor.appointments') }}" class="{{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}"><i class="fa fa-calendar-check"></i> Appointments</a>
                <a href="{{ route('doctor.patients') }}" class="{{ request()->routeIs('doctor.patients*') ? 'active' : '' }}"><i class="fa fa-people"></i> Patients</a>
                <a href="{{ route('doctor.prescriptions') }}" class="{{ request()->routeIs('doctor.prescriptions*') ? 'active' : '' }}"><i class="fa fa-prescription"></i> Prescriptions</a>
                <a href="{{ route('doctor.medical-reports') }}" class="{{ request()->routeIs('doctor.medical-reports*') ? 'active' : '' }}"><i class="fa fa-file-medical"></i> Reports</a>
                <a href="{{ route('doctor.lab-results') }}" class="{{ request()->routeIs('doctor.lab-results*') ? 'active' : '' }}"><i class="fa fa-vial"></i> Lab Results</a>
                <hr class="bg-light">
                <form method="POST" action="{{ route('doctor.logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></button>
                </form>
            @elseif(session('patient_logged_in'))
                <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
                <a href="{{ route('patient.profile') }}" class="{{ request()->routeIs('patient.profile*') ? 'active' : '' }}"><i class="fa fa-user"></i> My Profile</a>
                <a href="{{ route('patient.appointments') }}" class="{{ request()->routeIs('patient.appointments*') ? 'active' : '' }}"><i class="fa fa-calendar-check"></i> Appointments</a>
                <a href="{{ route('patient.medical-history') }}" class="{{ request()->routeIs('patient.medical-history*') ? 'active' : '' }}"><i class="fa fa-file-medical"></i> Medical History</a>
                <a href="{{ route('patient.prescriptions') }}" class="{{ request()->routeIs('patient.prescriptions*') ? 'active' : '' }}"><i class="fa fa-prescription"></i> Prescriptions</a>
                <a href="{{ route('patient.lab-results') }}" class="{{ request()->routeIs('patient.lab-results*') ? 'active' : '' }}"><i class="fa fa-vial"></i> Lab Results</a>
                <hr class="bg-light">
                <form method="POST" action="{{ route('patient.logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></button>
                </form>
            @elseif(session('staff_logged_in'))
                <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
                <a href="{{ route('staff.appointments') }}" class="{{ request()->routeIs('staff.appointments*') ? 'active' : '' }}"><i class="fa fa-calendar-check"></i> Appointments</a>
                <a href="{{ route('staff.patients') }}" class="{{ request()->routeIs('staff.patients*') ? 'active' : '' }}"><i class="fa fa-people"></i> Patients</a>
                <a href="{{ route('staff.doctors') }}" class="{{ request()->routeIs('staff.doctors*') ? 'active' : '' }}"><i class="fa fa-user-md"></i> Doctors</a>
                <hr class="bg-light">
                <form method="POST" action="{{ route('staff.logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></button>
                </form>
            @else
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
                <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.*') ? 'active' : '' }}"><i class="fa fa-user-injured"></i> Patients</a>
                <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"><i class="fa fa-user-md"></i> Doctors</a>
                <a href="{{ route('admin.staff.index') }}" class="{{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"><i class="fa fa-user-tie"></i> Staff</a>
                <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"><i class="fa fa-calendar-check"></i> Appointments</a>
                <a href="{{ route('admin.billing.index') }}" class="{{ request()->routeIs('admin.billing.*') ? 'active' : '' }}"><i class="fa fa-file-invoice-dollar"></i> Billing</a>
                <hr class="bg-light">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></button>
                </form>
            @endif
        </nav>

        <div class="sidebar-profile-card">
            <div class="profile-avatar"><i class="fa fa-user-shield"></i></div>
            <div>
                <strong>
                    @if(session('admin_logged_in'))
                        Admin
                    @elseif(session('doctor_logged_in'))
                        Doctor
                    @elseif(session('staff_logged_in'))
                        Staff
                    @elseif(session('patient_logged_in'))
                        Patient
                    @else
                        User
                    @endif
                </strong>
                <small>Super Admin</small>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle"><i class="fa fa-bars"></i></button>
                <h4 class="mb-0">@yield('page_title', 'Dashboard')</h4>
                <small class="muted ms-2">@yield('page_subtitle')</small>
            </div>

            <div class="d-flex align-items-center gap-3">
                @if(session('patient_logged_in'))
                    <div class="position-relative w-100">
                        <form id="doctor-search-form" class="w-100 d-flex" action="{{ route('patient.doctor-search') }}" method="GET" autocomplete="off">
                            <div class="input-group search-input">
                                <span class="input-group-text bg-white border-0"><i class="fa fa-search muted"></i></span>
                                <input id="doctor-search-input" name="q" type="search" class="form-control form-control-sm border-0" placeholder="Search doctors..." value="{{ request('q') }}" aria-label="Search doctors">
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                        </form>

                        <div id="doctor-search-suggestions" class="list-group position-absolute w-100 bg-white border rounded mt-1" style="z-index: 3000; display: none;"></div>
                    </div>
                @elseif(session('doctor_logged_in'))
                    <div class="position-relative w-100">
                        <form id="general-search-form" class="w-100 d-flex" action="{{ route('doctor.search') }}" method="GET" autocomplete="off">
                            <div class="input-group search-input">
                                <span class="input-group-text bg-white border-0"><i class="fa fa-search muted"></i></span>
                                <input id="general-search-input" name="q" type="search" class="form-control form-control-sm border-0" placeholder="Search patients, doctors, staff..." value="{{ request('q') }}" aria-label="Search">
                                <div id="search-loading" class="input-group-text bg-white border-0"><i class="fa fa-spinner fa-spin"></i></div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                        </form>

                        <div id="general-search-suggestions" class="list-group position-absolute w-100 bg-white border rounded mt-1 shadow-sm" style="z-index: 3000; display: none; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                @elseif(session('staff_logged_in'))
                    <div class="position-relative w-100">
                        <form id="staff-search-form" class="w-100 d-flex" action="{{ route('staff.search') }}" method="GET" autocomplete="off">
                            <div class="input-group search-input">
                                <span class="input-group-text bg-white border-0"><i class="fa fa-search muted"></i></span>
                                <input id="staff-search-input" name="q" type="search" class="form-control form-control-sm border-0" placeholder="Search patients, doctors, staff..." value="{{ request('q') }}" aria-label="Search">
                                <div id="staff-search-loading" class="input-group-text bg-white border-0"><i class="fa fa-spinner fa-spin"></i></div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                        </form>

                        <div id="staff-search-suggestions" class="list-group position-absolute w-100 bg-white border rounded mt-1 shadow-sm" style="z-index: 3000; display: none; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                @endif

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        
                        <div class="d-none d-sm-block text-end">
                            
                            
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            (function () {
                let searchInput, suggestionsBox, searchForm, timeoutId, selectedIndex = -1, currentSuggestions = [], loadingIndicator;
                let isPatient = {{ session('patient_logged_in') ? 'true' : 'false' }};
                let isDoctor = {{ session('doctor_logged_in') ? 'true' : 'false' }};
                let isStaff = {{ session('staff_logged_in') ? 'true' : 'false' }};

                if (isPatient) {
                    searchInput = document.getElementById('doctor-search-input');
                    suggestionsBox = document.getElementById('doctor-search-suggestions');
                    searchForm = document.getElementById('doctor-search-form');
                } else if (isDoctor) {
                    searchInput = document.getElementById('general-search-input');
                    suggestionsBox = document.getElementById('general-search-suggestions');
                    searchForm = document.getElementById('general-search-form');
                    loadingIndicator = document.getElementById('search-loading');
                } else if (isStaff) {
                    searchInput = document.getElementById('staff-search-input');
                    suggestionsBox = document.getElementById('staff-search-suggestions');
                    searchForm = document.getElementById('staff-search-form');
                    loadingIndicator = document.getElementById('staff-search-loading');
                }
                function hideSuggestions() {
                    if (suggestionsBox) suggestionsBox.style.display = 'none';
                }

                function highlightSuggestion(index) {
                    if (!suggestionsBox) return;
                    const items = suggestionsBox.querySelectorAll('.list-group-item');
                    items.forEach((el, i) => {
                        el.classList.toggle('active', i === index);
                    });
                }

                function showSuggestions(items) {
                    if (!suggestionsBox) return;
                    if (!items || items.length === 0) {
                        currentSuggestions = [];
                        selectedIndex = -1;
                        suggestionsBox.style.display = 'none';
                        return;
                    }

                    currentSuggestions = items;
                    selectedIndex = -1;

                    const searchRoute = isPatient ? '{{ route('patient.doctor-search') }}' : '{{ route('doctor.search') }}';
                    suggestionsBox.innerHTML = items.map((item, i) => {
                        const icon = item.type === 'Patient' ? 'fa-user-injured' : item.type === 'Doctor' ? 'fa-user-md' : 'fa-users';
                        return `
                        <a href="${searchRoute}?q=${encodeURIComponent(item.name)}" data-index="${i}" class="list-group-item list-group-item-action p-2 d-flex align-items-center">
                            <i class="fa ${icon} me-2 text-muted"></i>
                            <div>
                                <strong>${item.name}</strong><br>
                                <small class="text-muted">${item.type}: ${item.detail}</small>
                            </div>
                        </a>
                    `}).join('');

                    suggestionsBox.querySelectorAll('a').forEach(el => {
                        el.addEventListener('mouseenter', function () {
                            selectedIndex = parseInt(this.getAttribute('data-index'), 10);
                            highlightSuggestion(selectedIndex);
                        });

                        el.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            window.location.href = this.getAttribute('href');
                        });
                    });

                    suggestionsBox.style.display = 'block';
                }

                function fetchSuggestions(query) {
                    if (query.length < 1) {
                        hideSuggestions();
                        if (loadingIndicator) loadingIndicator.classList.remove('active');
                        return;
                    }

                    if (loadingIndicator) loadingIndicator.classList.add('active');

                    const suggestionsRoute = isPatient ? '{{ route('patient.doctor-suggestions') }}' : isDoctor ? '{{ route('doctor.search.suggestions') }}' : '{{ route('staff.search.suggestions') }}';

                    fetch(suggestionsRoute + "?q=" + encodeURIComponent(query), {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (loadingIndicator) loadingIndicator.classList.remove('active');
                        if (data && Array.isArray(data.suggestions)) {
                            showSuggestions(data.suggestions);
                        } else {
                            hideSuggestions();
                        }
                    })
                    .catch(() => {
                        if (loadingIndicator) loadingIndicator.classList.remove('active');
                        hideSuggestions();
                    });
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        const term = this.value.trim();
                        clearTimeout(timeoutId);
                        timeoutId = setTimeout(function () { fetchSuggestions(term); }, 250);
                    });

                    searchInput.addEventListener('keydown', function (event) {
                        const items = suggestionsBox ? suggestionsBox.querySelectorAll('.list-group-item') : [];
                        if (event.key === 'ArrowDown') {
                            event.preventDefault();
                            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                            highlightSuggestion(selectedIndex);
                        } else if (event.key === 'ArrowUp') {
                            event.preventDefault();
                            selectedIndex = Math.max(selectedIndex - 1, 0);
                            highlightSuggestion(selectedIndex);
                        } else if (event.key === 'Enter') {
                            if (selectedIndex >= 0 && items[selectedIndex]) {
                                event.preventDefault();
                                window.location.href = items[selectedIndex].getAttribute('href');
                            }
                        }
                    });

                    searchInput.addEventListener('blur', function () {
                        setTimeout(hideSuggestions, 200);
                    });
                }

                if (searchForm) {
                    searchForm.addEventListener('submit', function () {
                        hideSuggestions();
                    });
                }
            })();
        </script>

    <!-- Error/Success Modal Components -->
    @include('components.error-modals')

    <!-- Hidden validation errors container -->
    <div id="validationErrors" style="display: none;">
        @json($errors->getMessages())
    </div>
</html>
