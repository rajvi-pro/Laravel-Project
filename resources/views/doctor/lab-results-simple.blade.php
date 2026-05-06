@extends('layouts.doctor-layout')

@section('title', 'Lab Results (Simple)')

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
        <h1>🧪 Lab Results</h1>
        <a href="{{ route('doctor.lab-result.create.form') }}" class="btn-create">➕ Add Lab Result</a>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="h5">Existing Lab Results</h2>
            @if($labResults->isEmpty())
                <div class="text-muted">No lab results found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Result</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($labResults as $result)
                                <tr>
                                    <td>{{ $result->test_date?->format('Y-m-d') ?? $result->created_at->format('Y-m-d') }}</td>
                                    <td>{{ optional($result->patient)->name ?? 'N/A' }}</td>
                                    <td>{{ $result->test_name }}</td>
                                    <td>{{ $result->result }}</td>
                                    <td>{{ ucfirst($result->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $labResults->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
</div>
@endsection