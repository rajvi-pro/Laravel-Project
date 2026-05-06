@extends('admin-layout')

@section('title', 'My Lab Results')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}"><i class="bi bi-file-text"></i> Medical History</a>
    <a href="{{ route('patient.prescriptions') }}"><i class="bi bi-prescription"></i> Prescriptions</a>
    <a href="{{ route('patient.lab-results') }}" class="active"><i class="bi bi-beaker"></i> Lab Results</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('patient.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">My Lab Results</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lab Test Results</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Test Name</th>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($labResults ?? [] as $result)
                                <tr>
                                    <td>{{ $result->test_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($result->test_date)->format('Y-m-d') ?? 'N/A' }}</td>
                                    <td>{{ $result->doctor->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($result->status === 'completed')
                                            <span class="badge bg-success">✅ Completed</span>
                                        @else
                                            <span class="badge bg-warning">⏳ Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($result->result == 'normal')
                                            <span class="badge bg-success">Normal</span>
                                        @elseif($result->result == 'abnormal')
                                            <span class="badge bg-danger">Abnormal</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('patient.lab-result.detail', $result->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No lab results found</td>
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
