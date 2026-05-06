@extends('layouts.patient-layout')

@section('page-title', 'Medical History')
@section('title', 'My Medical History - HMS')

@section('content')
<style>
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .page-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        color: white;
        padding: 20px;
        font-weight: 700;
        font-size: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        background-color: #f8f9fa;
        border-bottom: 2px solid #0d47a1;
    }

    thead th {
        padding: 15px 20px;
        font-weight: 700;
        color: #1a1a1a;
        text-align: left;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr {
        border-bottom: 1px solid #eee;
        transition: all 0.2s ease;
    }

    tbody tr:hover {
        background-color: #f5f7fa;
    }

    tbody td {
        padding: 15px 20px;
        font-size: 14px;
        color: #1a1a1a;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #0d47a1;
        color: white;
    }

    .action-btn:hover {
        background: #0a3d91;
        color: white;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state p {
        font-size: 16px;
    }

    .history-card {
        background: white;
        border-radius: 8px;
        border-left: 4px solid #0d47a1;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .history-date {
        font-size: 12px;
        font-weight: 700;
        color: #0d47a1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .history-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .history-desc {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .history-doctor {
        font-size: 13px;
        color: #999;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }
</style>

<div class="page-header">
    <h2><i class="fas fa-file-medical" style="color: #0d47a1;"></i> Medical History</h2>
</div>

@if($medicalReports && count($medicalReports) > 0)
    <div style="display: grid; gap: 15px;">
        @foreach($medicalReports as $report)
            <div class="history-card">
                <div class="history-date">{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</div>
                <div class="history-title">{{ $report->diagnosis ?? 'N/A' }}</div>
                <div class="history-desc">{{ $report->notes ?? 'No additional notes' }}</div>
                <div class="history-doctor">
                    <strong>Doctor:</strong> {{ $report->doctor->name ?? 'N/A' }} | 
                    <strong>Status:</strong> {{ ucfirst($report->status ?? 'Completed') }}
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-file-medical"></i>
        <p>No medical history records available</p>
    </div>
@endif

@endsection
