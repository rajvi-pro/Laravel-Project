@extends('layouts.doctor-layout')

@section('title', 'Prescription Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Prescription Details</h1>
        <a href="{{ route('doctor.prescriptions') }}" class="btn btn-outline-secondary">← Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-muted">Patient</h5>
                    <h4>{{ $prescription->patient->name ?? 'N/A' }}</h4>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Prescription Date</h5>
                    <h4>{{ $prescription->prescribed_date?->format('M d, Y') ?? $prescription->created_at->format('M d, Y') }}</h4>
                </div>
            </div>

            <hr>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-muted">Medicine</h5>
                    <p class="lead">{{ $prescription->medicine_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Dosage</h5>
                    <p class="lead">{{ $prescription->dosage ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-muted">Frequency</h5>
                    <p class="lead">{{ $prescription->frequency ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Duration</h5>
                    <p class="lead">{{ $prescription->duration ?? 'N/A' }}</p>
                </div>
            </div>

            @if($prescription->instructions)
                <hr>
                <div class="mt-3">
                    <h5 class="text-muted">Instructions</h5>
                    <p>{{ $prescription->instructions }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
