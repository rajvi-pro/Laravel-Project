@extends('layouts.doctor-layout')

@section('title', 'Prescriptions')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #9333ea 100%);
        color: white;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.15);
    }

    .header-section h1 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 800;
    }

    .btn-create {
        background: linear-gradient(135deg,#7c3aed,#4f46e5);
        color: #fff;
        border-radius: 8px;
        border: none;
        padding: 0.6rem 1rem;
        font-weight: 700;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
    }

    .btn-create:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(79, 70, 229, .35);
    }

    .prescription-table {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.08);
    }

    .empty-state {
        text-align: center;
        margin: 38px 0;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        margin-bottom: 14px;
        display: block;
    }

    .stat-card {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.83rem;
        margin-bottom: 2px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #22356f;
    }

    .action-btn-view {
        background: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.45rem 0.7rem;
        font-size: 0.85rem;
    }

    .action-btn-view:hover {
        background: #3d34d6;
    }

    .table th, .table td {
        vertical-align: middle;
    }
</style>

<div class="container-fluid">
    <div class="header-section">
        <div>
            <h1>💊 Prescriptions</h1>
        </div>
        <a href="{{ route('doctor.prescription.create.form') }}" class="btn-create">
            ➕ Create New Prescription
        </a>
    </div>

    @if($prescriptions->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">💊</div>
            <h2 style="color: #6b7280; font-size: 1.5rem;">No prescriptions yet</h2>
            <p>You haven't created any prescriptions. Click the button above to get started.</p>
            <button class="action-btn action-btn-view" data-bs-toggle="modal" data-bs-target="#createPrescriptionModal" style="margin-top: 20px;">
                Create Prescription
            </button>
        </div>
    @else
        <div class="row mb-3">
            <div class="col-12">
                <div class="stat-card">
                    <div class="stat-label">📊 Total Prescriptions</div>
                    <div class="stat-value">{{ $prescriptions->count() }}</div>
                </div>
            </div>
        </div>

        <div class="prescription-table table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Patient</th>
                        <th scope="col">Medicine</th>
                        <th scope="col">Dosage</th>
                        <th scope="col">Frequency</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                        <tr>
                            <td class="patient-info">👤 {{ $prescription->patient->name ?? 'N/A' }}</td>
                            <td>{{ $prescription->medicine_name ?? '—' }}</td>
                            <td>{{ $prescription->dosage ?? '—' }}</td>
                            <td>{{ $prescription->frequency ?? '—' }}</td>
                            <td>{{ $prescription->duration ?? '—' }}</td>
                            <td>
                                <div class="date-badge">
                                    📅 {{ \Carbon\Carbon::parse($prescription->prescribed_date)->format('M d, Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('doctor.prescription.detail', $prescription->id) }}" class="action-btn action-btn-view">👁️ View</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
