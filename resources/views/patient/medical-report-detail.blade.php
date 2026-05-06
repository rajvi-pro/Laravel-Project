@extends('admin-layout')

@section('title', 'Medical Report Details')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}" class="active"><i class="bi bi-file-medical"></i> Medical History</a>
    <a href="{{ route('patient.prescriptions') }}"><i class="bi bi-prescription"></i> Prescriptions</a>
    <a href="{{ route('patient.lab-results') }}"><i class="bi bi-beaker"></i> Lab Results</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('patient.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Medical Report Details</h1>
        <a href="{{ route('patient.medical-history') }}" class="btn btn-outline-secondary">← Back to History</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-muted">Report Date</h5>
                    <h4>{{ $report->report_date?->format('M d, Y') ?? $report->created_at->format('M d, Y') }}</h4>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Doctor</h5>
                    <h4>{{ optional($report->doctor)->name ?? 'N/A' }}</h4>
                </div>
            </div>

            <hr>

            <div class="mb-4">
                <h5 class="text-muted">Diagnosis</h5>
                <p class="lead">{{ $report->diagnosis ?? 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h5 class="text-muted">Symptoms</h5>
                <p>{{ $report->symptoms ?? 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <h5 class="text-muted">Treatment</h5>
                <p>{{ $report->treatment ?? 'N/A' }}</p>
            </div>

            @if($report->notes)
                <div class="mb-4">
                    <h5 class="text-muted">Notes</h5>
                    <p>{{ $report->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
