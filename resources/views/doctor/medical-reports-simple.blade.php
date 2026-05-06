@extends('layouts.doctor-layout')

@section('title', 'Medical Reports (Simple)')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #9333ea 100%);
        color: white;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.15);
    }

    .header-section h1 {
        margin: 0;
        font-size: 2.1rem;
        font-weight: 800;
    }

    .btn-create {
        background: linear-gradient(135deg,#7c3aed,#4f46e5);
        color: #fff;
        border-radius: 8px;
        border: none;
        padding: 0.72rem 1.1rem;
        font-weight: 700;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
    }

    .btn-create:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, .35);
    }

    .container-fluid {
        background: #f8fafc;
        min-height: calc(100vh - 72px);
        padding: 24px 32px;
    }

    .card, .prescription-table {
        border-radius: 12px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.08);
    }

    .prescription-table {
        background: #fff;
        padding: 20px;
    }

    .empty-state {
        text-align: center;
        margin: 45px 0;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 3.8rem;
        margin-bottom: 16px;
        display: block;
    }

    .table th, .table td {
        vertical-align: middle;
        border-top: 0;
    }

    .action-btn-view {
        background: #4f46e5;
        color: #fff;
    }
</style>
<div class="container-fluid">
    <div class="header-section">
        <h1>📋 Medical Reports</h1>
        <a href="{{ route('doctor.medical-report.create.form') }}" class="btn-create">➕ Create Medical Report</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="h5">Saved Reports</h2>
            @if($medicalReports->isEmpty())
                <div class="text-muted">No saved medical reports yet.</div>
            @else
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Diagnosis</th>
                            <th>Symptoms</th>
                            <th>Treatment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalReports as $report)
                            <tr>
                                <td>{{ $report->report_date?->format('Y-m-d') ?? $report->created_at->format('Y-m-d') }}</td>
                                <td>{{ optional($report->patient)->name ?? 'N/A' }}</td>
                                <td>{{ $report->diagnosis }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($report->symptoms, 80) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($report->treatment, 80) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $medicalReports->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
</div>
@endsection