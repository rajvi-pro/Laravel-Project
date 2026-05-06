<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Doctor Dashboard') - HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .doctor-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f61cc 0%, #0a3b96 100%);
            color: white;
            padding: 25px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 0 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 15px;
        }

        .sidebar-brand h4 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 8px;
            flex-grow: 1;
        }

        .sidebar-menu li {
            margin: 4px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 11px 15px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.25s ease;
            font-size: 14px;
            font-weight: 500;
            margin: 0 6px;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: white;
        }

        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-menu i {
            width: 18px;
            margin-right: 12px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-footer {
            padding: 15px 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        .logout-btn {
            width: calc(100% - 12px);
            margin: 0 6px;
            padding: 10px 15px;
            background-color: #e74c3c;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.25s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
            color: white;
            transform: translateY(-1px);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            background-color: #f5f7fa;
        }

        /* Header */
        .doctor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .doctor-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .doctor-header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #0f61cc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }

        .user-info h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .user-info p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }

        /* Content Cards */
        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-top: 4px solid #00bcd4;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .metric-card.blue {
            border-top-color: #1565c0;
        }

        .metric-card.green {
            border-top-color: #26a69a;
        }

        .metric-card.orange {
            border-top-color: #ff9800;
        }

        .metric-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .metric-value {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a1a;
        }

        /* Tables - Professional Styling */
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 20px;
            font-weight: 700;
            font-size: 16px;
            margin: 0;
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

        /* Buttons */
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

            .doctor-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .doctor-header-right {
                flex-direction: column;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="doctor-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <h4><i class="bi bi-hospital"></i> HMS Doctor</h4>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.appointments') }}" class="{{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Appointments
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.patients') }}" class="{{ request()->routeIs('doctor.patients*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Patients
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.prescriptions') }}" class="{{ request()->routeIs('doctor.prescriptions*') ? 'active' : '' }}">
                        <i class="bi bi-prescription2"></i> Prescriptions
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.medical-reports') }}" class="{{ request()->routeIs('doctor.medical-reports*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i> Medical Reports
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.lab-results') }}" class="{{ request()->routeIs('doctor.lab-results*') ? 'active' : '' }}">
                        <i class="fa fa-vial"></i> Lab Results
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.schedule-list') }}" class="{{ request()->routeIs('doctor.schedule*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-week"></i> Schedule Management
                    </a>
                </li>
            </ul>

            <!-- Settings Section -->
            <div style="border-top: 1px solid rgba(255,255,255,0.2); margin-top: 15px; padding-top: 15px;">
                <ul class="sidebar-menu" style="flex-grow: 0;">
                    <li>
                        <a href="{{ route('doctor.profile') }}" class="{{ request()->routeIs('doctor.profile') ? 'active' : '' }}">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('doctor.change-password') }}" class="{{ request()->routeIs('doctor.change-password') ? 'active' : '' }}">
                            <i class="bi bi-lock"></i> Change Password
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('doctor.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
