@extends('layouts.patient-layout')

@section('page-title', 'Prescription Details')
@section('title', 'Prescription Details - HMS')

@section('content')
<style>
    .detail-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-header h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-back {
        background: white;
        border: 2px solid #0d47a1;
        color: #0d47a1;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #0d47a1;
        color: white;
    }

    .detail-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .detail-section {
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .detail-section:last-child {
        border-bottom: none;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 20px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #0d47a1;
    }

    .detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0d47a1;
    }

    .detail-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .detail-row {
            grid-template-columns: 1fr;
        }

        .detail-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="detail-header">
    <h2><i class="fas fa-prescription-bottle" style="color: #0d47a1; margin-right: 10px;"></i> Prescription Details</h2>
    <a href="{{ route('patient.prescriptions') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Prescriptions
    </a>
</div>

<div class="detail-container">
    <div class="detail-section">
        <h3 class="section-title">💊 Prescription Information</h3>
        <div class="detail-row">
            <div class="detail-item">
                <div class="detail-label">Medicine Name</div>
                <div class="detail-value">{{ $prescription->medicine_name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Dosage</div>
                <div class="detail-value">{{ $prescription->dosage ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Frequency</div>
                <div class="detail-value">{{ $prescription->frequency ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value">{{ $prescription->duration ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Prescribed Date</div>
                <div class="detail-value">{{ $prescription->prescribed_date?->format('M d, Y') ?? $prescription->created_at->format('M d, Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Prescribed By</div>
                <div class="detail-value">{{ optional($prescription->doctor)->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($prescription->instructions)
    <div class="detail-section">
        <h3 class="section-title">📝 Instructions</h3>
        <div class="detail-item detail-row" style="display: block; grid-template-columns: unset;">
            <div class="detail-value">{{ $prescription->instructions }}</div>
        </div>
    </div>
    @endif
</div>
@endsection
