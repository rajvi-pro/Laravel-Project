<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Dashboard') - HMS</title>
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

        .staff-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 30px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            padding: 0 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .sidebar-brand h4 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 10px;
            flex-grow: 1;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.25);
            color: white;
            font-weight: 600;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 20px 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            background-color: #f5f7fa;
        }

        /* Header */
        .staff-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .staff-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .staff-header-right {
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
            background: #1565c0;
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

        .table-subheader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f5f7fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-subheader h5 {
            margin: 0;
            font-size: 14px;
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
            background-color: #f5f7fa;
            padding: 15px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
        }

        table td {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #1a1a1a;
        }

        table tbody tr {
            transition: background-color 0.2s ease;
        }

        table tbody tr:hover {
            background-color: #f5f7fa;
        }

        /* Professional Buttons in Tables */
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-outline-primary {
            border: 1px solid #1565c0;
            color: #1565c0;
            background: white;
        }

        .btn-outline-primary:hover {
            background: #1565c0;
            color: white;
            box-shadow: 0 2px 8px rgba(21, 101, 192, 0.2);
        }

        .btn-outline-danger {
            border: 1px solid #e53935;
            color: #e53935;
            background: white;
        }

        .btn-outline-danger:hover {
            background: #e53935;
            color: white;
            box-shadow: 0 2px 8px rgba(229, 57, 53, 0.2);
        }

        /* Badge Styling */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            display: inline-block;
        }

        .badge-success {
            background-color: #c8e6c9;
            color: #2e7d32;
        }

        .badge-warning {
            background-color: #ffe0b2;
            color: #e65100;
        }

        .badge-danger {
            background-color: #ffcdd2;
            color: #c62828;
        }

        .badge-info {
            background-color: #b3e5fc;
            color: #006064;
        }

        /* Page Header - Professional */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header i {
            color: #1565c0;
        }

        .btn-book {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0a3580 0%, #1250a0 100%);
            box-shadow: 0 5px 15px rgba(21, 101, 192, 0.3);
            color: white;
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

            .staff-header {
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
    <div class="staff-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h4><i class="fas fa-hospital"></i> HMS Staff</h4>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('staff.dashboard') }}" class="@if(request()->routeIs('staff.dashboard')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.appointments') }}" class="@if(request()->routeIs('staff.appointments*')) active @endif">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.patients') }}" class="@if(request()->routeIs('staff.patients*')) active @endif">
                        <i class="fas fa-users"></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.doctors') }}" class="@if(request()->routeIs('staff.doctors')) active @endif">
                        <i class="fas fa-stethoscope"></i>
                        <span>Doctor Schedule</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.billings') }}" class="@if(request()->routeIs('staff.billings*')) active @endif">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Billing</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staff.change-password') }}" class="@if(request()->routeIs('staff.change-password')) active @endif">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('staff.logout') }}" method="POST">
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
            <div class="staff-header">
                <div>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="staff-header-right">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(session('staff_name', 'S'), 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <h5>{{ session('staff_name', 'Staff Member') }}</h5>
                            <p>{{ session('staff_role', 'Staff') }}</p>
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
