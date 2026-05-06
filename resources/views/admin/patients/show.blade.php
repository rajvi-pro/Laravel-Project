@extends('admin-layout')

@section('title', 'Patient Details - ' . $patient->name)

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}" class="active"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Patients
        </a>
    </div>

    <!-- Patient Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Patient Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $patient->name }}</p>
                    <p><strong>Email:</strong> {{ $patient->email }}</p>
                    <p><strong>Phone:</strong> {{ $patient->phone }}</p>
                    <p><strong>Date of Birth:</strong> 
                        @if($patient->date_of_birth)
                            {{ $patient->date_of_birth->format('d M Y') }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Gender:</strong> 
                        @if($patient->gender)
                            {{ ucfirst($patient->gender) }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                    <p><strong>Blood Group:</strong> 
                        @if($patient->blood_group)
                            <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                    <p><strong>Address:</strong> 
                        @if($patient->address)
                            {{ $patient->address }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                    <p><strong>Emergency Contact:</strong> 
                        @if($patient->emergency_contact)
                            {{ $patient->emergency_contact }}
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </p>
                </div>
            </div>
            <hr>
            <p><small class="text-muted"><strong>Registered:</strong> {{ $patient->created_at->format('d M Y, h:i A') }}</small></p>
        </div>
    </div>

    <!-- Appointments Section -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Appointments ({{ count($patient->appointments) }})</h5>
        </div>
        <div class="card-body">
            @if($patient->appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('d M Y, h:i A') }}</td>
                                    <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($appointment->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>{{ $appointment->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">No appointments found.</div>
            @endif
        </div>
    </div>

    <!-- Medical Reports Section -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-file-earmark-medical"></i> Medical Reports ({{ count($patient->medicalReports) }})</h5>
        </div>
        <div class="card-body">
            @if($patient->medicalReports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->medicalReports as $report)
                                <tr>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>{{ $report->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ substr($report->diagnosis ?? 'N/A', 0, 50) }}{{ strlen($report->diagnosis ?? '') > 50 ? '...' : '' }}</td>
                                    <td>{{ substr($report->treatment ?? 'N/A', 0, 50) }}{{ strlen($report->treatment ?? '') > 50 ? '...' : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">No medical reports found.</div>
            @endif
        </div>
    </div>

    <!-- Prescriptions Section -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-capsule"></i> Prescriptions ({{ count($patient->prescriptions) }})</h5>
        </div>
        <div class="card-body">
            @if($patient->prescriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Medicines</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->prescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->created_at->format('d M Y') }}</td>
                                    <td>{{ $prescription->doctor->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($prescription->medicines)
                                            {{ substr($prescription->medicines, 0, 40) }}{{ strlen($prescription->medicines) > 40 ? '...' : '' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ substr($prescription->instructions ?? 'N/A', 0, 40) }}{{ strlen($prescription->instructions ?? '') > 40 ? '...' : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">No prescriptions found.</div>
            @endif
        </div>
    </div>
</div>

<style>
    .badge {
        padding: 0.4rem 0.6rem;
    }
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table-sm {
        font-size: 0.85rem;
    }
</style>
@endsection
