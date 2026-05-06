<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Dashboard') - HMS</title>
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

        .patient-wrapper {
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
        .patient-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .patient-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .patient-header-right {
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
            background: rgba(255, 255, 255, 0.25);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .user-info h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        .user-info p {
            margin: 0;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
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

        /* Quick Actions */
        .quick-actions-container {
            margin-bottom: 30px;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .action-btn {
            background: white;
            border: 2px solid #e0e0e0;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #1a1a1a;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .action-btn i {
            font-size: 28px;
            color: #1565c0;
        }

        .action-btn:hover {
            border-color: #1565c0;
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.15);
            transform: translateY(-3px);
            color: #1565c0;
        }

        /* Tables */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .view-all-link {
            color: #1565c0;
            font-size: 12px;
            text-decoration: none;
            font-weight: 600;
        }

        .view-all-link:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #f9f9f9;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            border-bottom: 2px solid #e0e0e0;
        }

        table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #1a1a1a;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            border: none;
        }

        .btn-primary:hover {
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
                padding: 20px 0;
            }

            .sidebar-brand h4 {
                font-size: 20px;
            }

            .sidebar-menu a span {
                display: none;
            }

            .main-content {
                margin-left: 80px;
                padding: 20px;
            }

            .patient-header {
                flex-direction: column;
                text-align: center;
            }

            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="patient-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h4><i class="fas fa-hospital"></i> HMS Patient</h4>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('patient.dashboard') }}" class="@if(request()->routeIs('patient.dashboard')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.profile') }}" class="@if(request()->routeIs('patient.profile*')) active @endif">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.appointments') }}" class="@if(request()->routeIs('patient.appointments*')) active @endif">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.medical-history') }}" class="@if(request()->routeIs('patient.medical-history')) active @endif">
                        <i class="fas fa-file-medical"></i>
                        <span>Medical History</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.prescriptions') }}" class="@if(request()->routeIs('patient.prescriptions*')) active @endif">
                        <i class="fas fa-pills"></i>
                        <span>Prescriptions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.lab-results') }}" class="@if(request()->routeIs('patient.lab-results*')) active @endif">
                        <i class="fas fa-flask"></i>
                        <span>Lab Results</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.change-password') }}" class="@if(request()->routeIs('patient.change-password')) active @endif">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('patient.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="patient-header">
                <div>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="patient-header-right">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(session('patient_name', 'P'), 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <h5>{{ session('patient_name', 'Patient') }}</h5>
                            <p>Patient</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
