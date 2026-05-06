@extends('admin-layout')

@section('title', 'Admin Dashboard')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <a href="{{ route('admin.appointments.index') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('admin.billing.index') }}"><i class="bi bi-cash-coin"></i> Billing</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Attractive Header Bar with Background -->
    <div style="background: linear-gradient(135deg, #0d6efd 0%, #0a3b96 100%); border-radius: 12px; padding: 28px; margin-bottom: 32px; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 style="color: rgba(255, 255, 255, 0.8); font-weight: 500; margin-bottom: 8px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Dashboard</h5>
                <h1 style="color: #ffffff; font-weight: 700; font-size: 2.2rem; margin: 0;">Admin Dashboard</h1>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; margin: 8px 0 0 0;">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="text-align: right;">
                    <p style="color: #ffffff; font-weight: 600; margin: 0; font-size: 0.95rem;">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.85rem; margin: 4px 0 0 0;">Administrator</p>
                </div>
                <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.3rem; border: 2px solid rgba(255, 255, 255, 0.4);">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Stat Cards with Labels inside Header -->
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div style="padding: 18px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-weight: 500; margin: 0; text-transform: uppercase; letter-spacing: 0.3px;">TODAY'S APPOINTMENTS</p>
                    <h2 style="color: #ffffff; font-weight: 700; margin: 10px 0 0 0; font-size: 2.2rem;">{{ $todayAppointments ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 18px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-weight: 500; margin: 0; text-transform: uppercase; letter-spacing: 0.3px;">TOTAL PATIENTS</p>
                    <h2 style="color: #ffffff; font-weight: 700; margin: 10px 0 0 0; font-size: 2.2rem;">{{ $totalPatients ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 18px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-weight: 500; margin: 0; text-transform: uppercase; letter-spacing: 0.3px;">AVAILABLE DOCTORS</p>
                    <h2 style="color: #ffffff; font-weight: 700; margin: 10px 0 0 0; font-size: 2.2rem;">{{ $availableDoctors ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 18px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.8rem; font-weight: 500; margin: 0; text-transform: uppercase; letter-spacing: 0.3px;">PENDING APPOINTMENTS</p>
                    <h2 style="color: #ffffff; font-weight: 700; margin: 10px 0 0 0; font-size: 2.2rem;">{{ $pendingAppointments ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Header with Search and Range Picker -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h5 style="color: #333; font-weight: 600; margin: 0;">Dashboard Analytics</h5>
        </div>
        <div style="display: flex; flex-direction: row; gap: 10px; align-items: center; flex-wrap: wrap;">
            <form id="admin-range-form" action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                <input type="date" class="form-control" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" style="max-width: 140px;">
                <span class="input-group-text">to</span>
                <input type="date" class="form-control" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" style="max-width: 140px;">
                <button type="submit" class="btn btn-primary ms-1">Apply</button>
                <a href="{{ route('admin.export.excel', ['start_date' => request('start_date', date('Y-m-01')), 'end_date' => request('end_date', date('Y-m-d'))]) }}" class="btn btn-success ms-1" download>📊 Export Excel</a>
                <a href="{{ route('admin.export.pdf', ['start_date' => request('start_date', date('Y-m-01')), 'end_date' => request('end_date', date('Y-m-d'))]) }}" class="btn btn-danger ms-1" target="_blank">📄 Export PDF</a>
            </form>
            <div class="position-relative" style="min-width: 300px;">
                <form id="admin-search-form" action="{{ route('admin.search') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input id="admin-search-input" type="text" name="q" class="form-control border-start-0" placeholder="Search patients, doctors..." value="{{ request('q') }}">
                        <div id="admin-search-loading" class="input-group-text bg-white border-0" style="display: none;"><i class="fa fa-spinner fa-spin"></i></div>
                    </div>
                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
                <div id="admin-search-suggestions" class="list-group position-absolute w-100 bg-white border rounded mt-1 shadow-sm" style="z-index: 3000; display: none; max-height: 300px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>

    <!-- Unavailability Alerts -->
    @if(!empty($unavailableDoctors) && count($unavailableDoctors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 25px;">
            <h5 class="alert-heading mb-2">
                <i class="fas fa-exclamation-circle"></i> Doctor Unavailability Alert
            </h5>
            <p class="mb-2">The following doctors are currently unavailable and cannot accept appointments:</p>
            <ul style="margin-bottom: 0; padding-left: 20px;">
                @foreach($unavailableDoctors as $unavail)
                    <li>
                        <strong>{{ $unavail->doctor->name }}</strong> 
                        ({{ $unavail->unavailable_date->format('M d, Y') }} to {{ $unavail->end_date->format('M d, Y') }})
                        @if($unavail->reason)
                            - <em>{{ $unavail->reason }}</em>
                        @endif
                    </li>
                @endforeach
            </ul>
            <hr>
            <p style="font-size: 14px; margin-bottom: 0;">
                <strong>Action Required:</strong> Patients with appointments during this period should be notified and asked to reschedule.
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Analytics and Reports Section - Match Screenshot Layout -->
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <h5 style="color: #333; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem;">Analytics and Reports</h5>
            <!-- Removed lower search, moved range picker to navbar search -->
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="card shadow-sm mb-2" style="border-radius: 12px; min-height: 260px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size: 1.2rem; font-weight: 700; color: #198754;"><i class="bi bi-graph-up"></i> Revenue Trend (Last 30 Days)</span>
                        </div>
                        <div style="height: 220px;">
                            <canvas id="revenueChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm mb-2" style="border-radius: 12px; min-height: 260px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size: 1.2rem; font-weight: 700; color: #0d6efd;"><i class="bi bi-bar-chart"></i> Monthly Appointment Status</span>
                        </div>
                        <div style="height: 220px;">
                            <canvas id="appointmentChart" height="200"></canvas>
                        </div>
                        <div class="mt-2" id="appointmentStatusLegend"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm" style="border-radius: 12px; min-height: 180px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #ffc107; margin-bottom: 12px;"><i class="bi bi-pie-chart"></i> Staff Distribution</div>
                        <div style="width: 100%; height: 100px;">
                            <canvas id="staffChart" height="100"></canvas>
                        </div>
                        <div class="d-flex justify-content-center mt-3" style="font-size: 0.95rem;">
                            <span class="me-2"><span style="color:#007bff;">●</span> Lab Technician</span>
                            <span class="me-2"><span style="color:#28a745;">●</span> Nurse</span>
                            <span class="me-2"><span style="color:#ffc107;">●</span> Pharmacist</span>
                            <span><span style="color:#dc3545;">●</span> Receptionist</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm" style="border-radius: 12px; min-height: 180px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #198754; margin-bottom: 12px;"><i class="bi bi-check-circle"></i> Completion Rate</div>
                        <div style="font-weight: 700; color: #198754; font-size: 2rem;">{{ $appointmentCompletionRate }}%</div>
                        <div class="progress w-100" style="height: 10px; background-color: #e9ecef;">
                            <div class="progress-bar" style="width: {{ $appointmentCompletionRate }}%; background-color: #198754;"></div>
                        </div>
                        <div style="font-size: 1rem; color: #888; margin-top: 8px;">Appointments Completed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm" style="border-radius: 12px; min-height: 180px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #0d6efd; margin-bottom: 12px;"><i class="bi bi-person-check"></i> Doctor Utilization</div>
                        <div style="font-weight: 700; color: #0d6efd; font-size: 2rem;">{{ min(round($avgDoctorsPerDay, 0), 100) }}%</div>
                        <div class="progress w-100" style="height: 10px; background-color: #e9ecef;">
                            <div class="progress-bar" style="width: {{ min($avgDoctorsPerDay, 100) }}%; background-color: #0d6efd;"></div>
                        </div>
                        <div style="font-size: 1rem; color: #888; margin-top: 8px;">Average Utilization Rate</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm" style="border-radius: 12px; min-height: 180px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #333; margin-bottom: 12px;">Key Metrics</div>
                        <div class="d-flex flex-column align-items-center">
                            <div style="color: #0d6efd; font-size: 1.5rem; font-weight: 700;">{{ round($newPatientsThisMonth / 4) }}</div>
                            <div style="font-size: 1rem; color: #999;">New/Wk</div>
                            <div style="color: #198754; font-size: 1.5rem; font-weight: 700;">{{ $todayAppointments }}</div>
                            <div style="font-size: 1rem; color: #999;">Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="row">
        <div class="col-md-12">
            <div class="card h-100 shadow-sm" style="border: none; border-radius: 10px;">
                <div class="card-header bg-white border-bottom pb-3" style="border-radius: 10px 10px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" style="font-weight: 600; color: #333;"><i class="bi bi-calendar-check" style="color: #0d6efd;"></i> Recent Appointments</h6>
                        <div style="width: 200px;">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control border-start-0" placeholder="Search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div style="overflow-x: auto;">
                        @if($recentAppointments && $recentAppointments->count() > 0)
                            <table class="table table-hover mb-0" style="border-collapse: separate; border-spacing: 0;">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="border: none; font-weight: 600; color: #333; padding: 12px;">Patient</th>
                                        <th style="border: none; font-weight: 600; color: #333; padding: 12px;">Doctor</th>
                                        <th style="border: none; font-weight: 600; color: #333; padding: 12px;">Date</th>
                                        <th style="border: none; font-weight: 600; color: #333; padding: 12px;">Time</th>
                                        <th style="border: none; font-weight: 600; color: #333; padding: 12px;">Status</th>
                                        <th style="border: none; font-weight: 600; color: #333; padding: 12px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td style="border: none; padding: 12px; color: #333; font-weight: 500;">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td style="border: none; padding: 12px; color: #666;">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                            <td style="border: none; padding: 12px; color: #666;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</td>
                                            <td style="border: none; padding: 12px; color: #666;">{{ $appointment->appointment_time }}</td>
                                            <td style="border: none; padding: 12px;">
                                                @if($appointment->status === 'completed')
                                                    <span class="badge" style="background-color: #28a745; color: #fff; padding: 8px 12px; font-weight: 600; font-size: 0.875rem; border-radius: 4px; display: inline-block;">✓ Completed</span>
                                                @elseif($appointment->status === 'cancelled')
                                                    <span class="badge" style="background-color: #dc3545; color: #fff; padding: 8px 12px; font-weight: 600; font-size: 0.875rem; border-radius: 4px; display: inline-block;">✗ Cancelled</span>
                                                @elseif($appointment->status === 'scheduled')
                                                    <span class="badge" style="background-color: #17a2b8; color: #fff; padding: 8px 12px; font-weight: 600; font-size: 0.875rem; border-radius: 4px; display: inline-block;">📅 Scheduled</span>
                                                @elseif($appointment->status === 'rescheduled')
                                                    <span class="badge" style="background-color: #ffc107; color: #000; padding: 8px 12px; font-weight: 600; font-size: 0.875rem; border-radius: 4px; display: inline-block;">🔄 Rescheduled</span>
                                                @else
                                                    <span class="badge" style="background-color: #6c757d; color: #fff; padding: 8px 12px; font-weight: 600; font-size: 0.875rem; border-radius: 4px; display: inline-block;">{{ ucfirst($appointment->status) }}</span>
                                                @endif
                                            </td>
                                            <td style="border: none; padding: 12px;">
                                                <button onclick="window.location.href='{{ route('admin.appointments.edit', $appointment->id) }}'" class="btn btn-sm" style="background-color: #ffc107; color: #000; border: none; font-weight: 600; border-radius: 5px; padding: 5px 12px;">
                                                    Reschedule
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                                <p class="mt-3">No recent appointments</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12) !important;
        transform: translateY(-2px);
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .progress {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 3px;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    #staffChart {
        max-width: 100% !important;
        max-height: 50px !important;
    }
    
    .card:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-0.5px);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Options - Compact
        const compactChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.parsed.y.toLocaleString('en-IN');
                        }
                    }
                }
            },
            scales: {
                y: {
                    display: false,
                    beginAtZero: true
                },
                x: {
                    display: false
                }
            }
        };

        // Revenue Trend Chart - Compact
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [
                        @foreach($monthlyRevenue as $data)
                            '{{ $data->date }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: [
                            @foreach($monthlyRevenue as $data)
                                {{ $data->revenue }},
                            @endforeach
                        ],
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.08)',
                        borderWidth: 1,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#198754',
                        pointRadius: 0.8,
                        pointHoverRadius: 2,
                        pointBorderWidth: 0
                    }]
                },
                options: compactChartOptions
            });
        }

        // Appointment Status Chart - Full Bar with Legend
        const appointmentCtx = document.getElementById('appointmentChart');
        if (appointmentCtx) {
            const appointmentStatus = {
                completed: {{ $appointmentStatus['completed'] ?? 0 }},
                scheduled: {{ $appointmentStatus['scheduled'] ?? 0 }},
                cancelled: {{ $appointmentStatus['cancelled'] ?? 0 }},
                rescheduled: {{ $appointmentStatus['rescheduled'] ?? 0 }}
            };
            const statusLabels = ['Completed', 'Scheduled', 'Cancelled', 'Rescheduled'];
            const statusColors = ['#198754', '#0d6efd', '#dc3545', '#ffc107'];
            const statusShort = ['C', 'S', 'X', 'R'];
            const chart = new Chart(appointmentCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        label: 'Appointments',
                        data: [
                            appointmentStatus.completed,
                            appointmentStatus.scheduled,
                            appointmentStatus.cancelled,
                            appointmentStatus.rescheduled
                        ],
                        backgroundColor: statusColors,
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        y: {
                            display: true,
                            beginAtZero: true,
                            title: { display: true, text: 'Count' }
                        },
                        x: {
                            display: true,
                            title: { display: false }
                        }
                    }
                }
            });
            // Add legend below chart
            const legendDiv = document.getElementById('appointmentStatusLegend');
            if (legendDiv) {
                legendDiv.innerHTML = statusLabels.map((label, i) =>
                    `<span style="display:inline-block;margin-right:16px;font-size:0.95rem;"><span style="display:inline-block;width:14px;height:14px;background:${statusColors[i]};border-radius:3px;margin-right:6px;"></span>${label}</span>`
                ).join('');
            }
        }

        // Staff Distribution Chart - Compact
        const staffCtx = document.getElementById('staffChart');
        if (staffCtx) {
            new Chart(staffCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($staffDistribution as $staff)
                            '{{ substr($staff->role, 0, 3) }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($staffDistribution as $staff)
                                {{ $staff->count }},
                            @endforeach
                        ],
                        backgroundColor: [
                            '#0d6efd',
                            '#198754',
                            '#ffc107',
                            '#dc3545',
                            '#20c997',
                            '#6f42c1'
                        ],
                        borderColor: '#fff',
                        borderWidth: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 0
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<script>
    (function () {
        let searchInput = document.getElementById('admin-search-input');
        let suggestionsBox = document.getElementById('admin-search-suggestions');
        let searchForm = document.getElementById('admin-search-form');
        let loadingIndicator = document.getElementById('admin-search-loading');
        let timeoutId, selectedIndex = -1, currentSuggestions = [];

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

            const searchRoute = '{{ route('admin.search') }}';
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
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                return;
            }

            if (loadingIndicator) loadingIndicator.style.display = 'flex';

            fetch('{{ route('admin.search.suggestions') }}' + "?q=" + encodeURIComponent(query), {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                if (data && Array.isArray(data.suggestions)) {
                    showSuggestions(data.suggestions);
                } else {
                    hideSuggestions();
                }
            })
            .catch(() => {
                if (loadingIndicator) loadingIndicator.style.display = 'none';
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

@endsection
