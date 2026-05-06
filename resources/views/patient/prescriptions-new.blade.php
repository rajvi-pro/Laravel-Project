@extends('layouts.patient-layout')

@section('page-title', 'Prescriptions')
@section('title', 'My Prescriptions - HMS')

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

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-active {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .badge-inactive {
        background-color: #f5f5f5;
        color: #666;
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
</style>

<div class="page-header">
    <h2><i class="fas fa-prescription-bottle" style="color: #0d47a1;"></i> My Prescriptions</h2>
</div>

<div class="table-container">
    @if($prescriptions && count($prescriptions) > 0)
        <div class="table-header">
            Active Prescriptions
        </div>
        <table>
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Dosage</th>
                    <th>Prescribed By</th>
                    <th>Prescribed Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescriptions as $prescription)
                    <tr>
                        <td><strong>{{ $prescription->medicine_name ?? 'N/A' }}</strong></td>
                        <td>{{ $prescription->dosage ?? 'N/A' }}</td>
                        <td>{{ $prescription->doctor->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($prescription->start_date)->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('patient.prescription.detail', $prescription->id) }}" class="action-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="fas fa-prescription-bottle"></i>
            <p>No prescriptions available</p>
        </div>
    @endif
</div>

@endsection
