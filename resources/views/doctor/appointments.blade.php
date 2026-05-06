@extends('layouts.doctor-layout')

@section('title', 'Appointments')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .page-header h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .metric-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #0f61cc;
    }

    .metric-card.scheduled {
        border-left-color: #ff9800;
    }

    .metric-card.completed {
        border-left-color: #26a69a;
    }

    .metric-card.cancelled {
        border-left-color: #e53935;
    }

    .metric-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .metric-value {
        font-size: 32px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .metric-card.scheduled .metric-value {
        color: #ff9800;
    }

    .metric-card.completed .metric-value {
        color: #26a69a;
    }

    .metric-card.cancelled .metric-value {
        color: #e53935;
    }

    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 30px;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 700;
        color: #1a1a1a;
        font-size: 1.1rem;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e0e0e0;
    }

    .table-container table th {
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-container table td {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .table-container table tbody tr:hover {
        background: #f8f9fa;
        transition: background 0.2s ease;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: white;
    }

    .badge.scheduled {
        background: #ff9800;
    }

    .badge.completed {
        background: #26a69a;
    }

    .badge.cancelled {
        background: #e53935;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-action {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-action i {
        font-size: 1.1rem;
    }

    .btn-action.primary {
        background: #0f61cc;
        color: white;
    }

    .btn-action.primary:hover {
        background: #0a3b96;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 97, 204, 0.3);
    }

    .btn-action.secondary {
        background: #3b82f6;
        color: white;
    }

    .btn-action.secondary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-action.tertiary {
        background: #8b5cf6;
        color: white;
    }

    .btn-action.tertiary:hover {
        background: #7c3aed;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .btn-action.quaternary {
        background: #ec4899;
        color: white;
    }

    .btn-action.quaternary:hover {
        background: #db2777;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
    }

    .empty-state {
        background: white;
        border-radius: 10px;
        padding: 60px 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #999;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #ccc;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-container table {
            font-size: 0.9rem;
        }

        .table-container table th,
        .table-container table td {
            padding: 10px;
        }

        .action-buttons {
            gap: 8px;
        }

        .btn-action {
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .btn-action i {
            font-size: 1rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h2><i class="fas fa-calendar-check"></i> My Appointments</h2>
</div>

@if($appointments->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">📭</div>
        <h3>No appointments found</h3>
        <p>You don't have any scheduled appointments at the moment.</p>
    </div>
@else
    <!-- Metrics Grid -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-label">Total Appointments</div>
            <div class="metric-value">{{ $appointments->count() }}</div>
        </div>
        <div class="metric-card scheduled">
            <div class="metric-label">Scheduled</div>
            <div class="metric-value">{{ $appointments->where('status', 'scheduled')->count() }}</div>
        </div>
        <div class="metric-card completed">
            <div class="metric-label">Completed</div>
            <div class="metric-value">{{ $appointments->where('status', 'completed')->count() }}</div>
        </div>
        <div class="metric-card cancelled">
            <div class="metric-label">Cancelled</div>
            <div class="metric-value">{{ $appointments->where('status', 'cancelled')->count() }}</div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="table-container">
        <div class="table-header"><i class="fas fa-list"></i> All Appointments</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar"></i> Date</th>
                        <th><i class="fas fa-clock"></i> Time</th>
                        <th><i class="fas fa-user"></i> Patient Name</th>
                        <th><i class="fas fa-phone"></i> Contact</th>
                        <th><i class="fas fa-clipboard"></i> Reason</th>
                        <th><i class="fas fa-flag"></i> Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ date('g:i A', strtotime($appointment->appointment_time)) }}</td>
                            <td><strong>{{ $appointment->patient->name ?? 'Unknown' }}</strong></td>
                            <td>{{ $appointment->patient->phone ?? 'N/A' }}</td>
                            <td>{{ $appointment->reason ?? 'General Checkup' }}</td>
                            <td>
                                <span class="badge {{ strtolower($appointment->status) }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('doctor.appointment.detail', $appointment->id) }}" class="btn-action primary" title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection
