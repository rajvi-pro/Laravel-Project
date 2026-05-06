@extends('admin-layout')

@section('title', 'Medical History')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}" class="active"><i class="bi bi-file-text"></i> Medical History</a>
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
    <h1 class="mb-4">My Medical History</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Medical Reports</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicalReports ?? [] as $report)
                                <tr>
                                    <td>{{ $report->report_date ?? 'N/A' }}</td>
                                    <td>{{ $report->doctor->name ?? 'N/A' }}</td>
                                    <td>{{ $report->diagnosis ?? 'N/A' }}</td>
                                    <td>{{ substr($report->notes ?? '', 0, 50) }}...</td>
                                    <td>
                                        <a href="{{ route('patient.medical-report.detail', $report->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No medical reports found</td>
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
