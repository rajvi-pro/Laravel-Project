@extends('admin-layout')

@section('title', 'My Prescriptions')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}"><i class="bi bi-file-text"></i> Medical History</a>
    <a href="{{ route('patient.prescriptions') }}" class="active"><i class="bi bi-prescription"></i> Prescriptions</a>
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
    <h1 class="mb-4">My Prescriptions</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Active Prescriptions</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prescriptions ?? [] as $prescription)
                                <tr>
                                    <td>{{ $prescription->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($prescription->prescribed_date)->format('Y-m-d') ?? 'N/A' }}</td>
                                    <td>{{ $prescription->medicine_name ?? 'N/A' }}</td>
                                    <td>{{ $prescription->dosage ?? 'N/A' }}</td>
                                    <td>{{ $prescription->frequency ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('patient.prescription.detail', $prescription->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No prescriptions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
