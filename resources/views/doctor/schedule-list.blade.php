@extends('layouts.doctor-layout')

@section('title', 'Schedule Management')

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

    .btn-create {
        background: #0f61cc;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        background: #0a4aa8;
        box-shadow: 0 4px 12px rgba(15, 97, 204, 0.3);
    }

    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 6px;
        border-left: 4px solid;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left-color: #26a69a;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border-left-color: #e53935;
    }

    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
    }

    .schedule-table thead {
        background: #f5f5f5;
        border-bottom: 2px solid #e0e0e0;
    }

    .schedule-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #666;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .schedule-table td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .schedule-table tr:hover {
        background: #fafafa;
    }

    .day-badge {
        display: inline-block;
        background: #e3f2fd;
        color: #0f61cc;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    .time-badge {
        display: inline-block;
        background: #f3e5f5;
        color: #7b1fa2;
        padding: 6px 12px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-badge.active {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.inactive {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #29b6f6;
        color: white;
    }

    .btn-edit:hover {
        background: #0277bd;
    }

    .btn-toggle-active {
        background: #fbc02d;
        color: white;
    }

    .btn-toggle-active:hover {
        background: #f57f17;
    }

    .btn-delete {
        background: #ef5350;
        color: white;
    }

    .btn-delete:hover {
        background: #c62828;
    }

    .empty-state {
        padding: 60px 40px;
        text-align: center;
        color: #999;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        color: #666;
        margin: 10px 0;
    }

    .pagination-container {
        padding: 20px;
        text-align: center;
    }

    .pagination {
        display: inline-flex;
        gap: 5px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #0f61cc;
        text-decoration: none;
        cursor: pointer;
    }

    .pagination a:hover {
        background: #e3f2fd;
    }

    .pagination .active {
        background: #0f61cc;
        color: white;
        border-color: #0f61cc;
    }
</style>

<div class="page-header">
    <h2>Schedule Management</h2>
    <a href="{{ route('doctor.schedule-create') }}" class="btn-create">+ Create Schedule</a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <strong>Error:</strong>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="table-container">
    @if ($schedules->count() > 0)
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time Slot</th>
                    <th>Break Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr>
                        <td>
                            <span class="day-badge">
                                {{ ucfirst($dayNames[$schedule->day_of_week] ?? $schedule->day_of_week) }}
                            </span>
                        </td>
                        <td>
                            <span class="time-badge">
                                {{ $schedule->start_time }} - {{ $schedule->end_time }}
                            </span>
                        </td>
                        <td>
                            @if ($schedule->break_start && $schedule->break_end)
                                <span class="time-badge">
                                    {{ $schedule->break_start }} - {{ $schedule->break_end }}
                                </span>
                            @else
                                <span style="color: #999;">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ $schedule->is_active ? 'active' : 'inactive' }}">
                                {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('doctor.schedule-edit', $schedule->id) }}" class="btn-sm btn-edit">Edit</a>
                                <form method="POST" action="{{ route('doctor.schedule-toggle', $schedule->id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-sm btn-toggle-active">
                                        {{ $schedule->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('doctor.schedule-delete', $schedule->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($schedules->hasPages())
            <div class="pagination-container">
                {{ $schedules->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📅</div>
            <h3>No Schedules Created Yet</h3>
            <p>Create your first schedule to manage your availability.</p>
            <a href="{{ route('doctor.schedule-create') }}" class="btn-create" style="margin-top: 20px;">Create Your First Schedule</a>
        </div>
    @endif
</div>
@endsection
