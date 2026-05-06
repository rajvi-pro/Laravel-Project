@extends('layouts.staff-layout')

@section('page-title', 'Search Results')
@section('title', 'Search Results - Staff Portal')

@section('content')
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Search Results Summary -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <p style="margin: 0; color: #666;">
            <strong>Search Results</strong> - Found {{ ($patients->count() ?? 0) + ($appointments->count() ?? 0) }} matches
        </p>
    </div>

    <!-- Patients Results -->
    @if($patients && $patients->count() > 0)
        <div class="table-container">
            <div class="table-header">
                <h5><i class="fas fa-users"></i> Patients ({{ $patients->count() }})</h5>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Medical ID</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td><strong>{{ $patient->name ?? $patient->full_name ?? 'N/A' }}</strong></td>
                                <td>{{ $patient->id ?? $patient->medical_id ?? '-' }}</td>
                                <td>
                                    @if($patient->date_of_birth)
                                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ ucfirst($patient->gender ?? 'N/A') }}</td>
                                <td>{{ $patient->phone ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('staff.patient.detail', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Appointments Results -->
    @if($appointments && $appointments->count() > 0)
        <div class="table-container" style="margin-top: 30px;">
            <div class="table-header">
                <h5><i class="fas fa-calendar-check"></i> Appointments ({{ $appointments->count() }})</h5>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->name ?? $appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $appointment->doctor->name ?? 'Unassigned' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                    <br>
                                    <small>{{ $appointment->appointment_time ?? 'TBA' }}</small>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($appointment->status == 'completed') #26a69a
                                        @elseif($appointment->status == 'pending') #ff9800
                                        @elseif($appointment->status == 'cancelled') #e53935
                                        @else #1565c0
                                        @endif
                                    ">
                                        {{ ucfirst($appointment->status ?? 'Scheduled') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('staff.appointment.detail', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- No Results -->
    @if((!$patients || $patients->count() == 0) && (!$appointments || $appointments->count() == 0))
        <div class="table-container">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <p style="margin: 0;">No results found</p>
            </div>
        </div>
    @endif

@endsection
