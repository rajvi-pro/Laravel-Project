@extends('layouts.staff-layout')

@section('page-title', 'Patient Details')
@section('title', 'Patient Details - Staff Portal')

@section('content')
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.patients') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
        <a href="{{ route('staff.patient.edit', $patient->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil"></i> Edit Patient Information
        </a>
    </div>

    <!-- Patient Overview -->
    <div class="table-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Basic Information -->
            <div>
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Basic Information</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Full Name:</strong>
                        {{ $patient->name ?? $patient->full_name ?? 'N/A' }}
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Patient ID:</strong>
                        {{ $patient->id ?? $patient->medical_id ?? 'N/A' }}
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Date of Birth:</strong>
                        @if($patient->date_of_birth)
                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('F d, Y') }} ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years)
                        @else
                            N/A
                        @endif
                    </p>
                    <p style="margin: 0; font-size: 16px;">
                        <strong>Gender:</strong>
                        {{ ucfirst($patient->gender ?? 'N/A') }}
                    </p>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Contact Information</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Phone:</strong>
                        {{ $patient->phone ?? 'N/A' }}
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Email:</strong>
                        {{ $patient->email ?? 'N/A' }}
                    </p>
                    <p style="margin: 0; font-size: 16px;">
                        <strong>Address:</strong>
                        {{ $patient->address ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Medical Summary -->
            <div>
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Medical Summary</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Total Appointments:</strong>
                        {{ $patient->appointments->count() ?? 0 }}
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 16px;">
                        <strong>Total Prescriptions:</strong>
                        {{ $patient->prescriptions->count() ?? 0 }}
                    </p>
                    <p style="margin: 0; font-size: 16px;">
                        <strong>Medical Reports:</strong>
                        {{ $patient->medicalReports->count() ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments History -->
    <div class="table-container">
        <div class="table-header">
            <h5>Appointment History</h5>
        </div>
        @if($appointments && $appointments->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Doctor</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                    <br>
                                    <small>{{ $appointment->appointment_time ?? 'TBA' }}</small>
                                </td>
                                <td>{{ $appointment->doctor->name ?? 'Unassigned' }}</td>
                                <td>{{ $appointment->reason ?? '-' }}</td>
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
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div style="margin-top: 20px;">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <p style="margin: 0;">No appointments found</p>
            </div>
        @endif
    </div>

    <!-- Medical Prescriptions -->
    <div class="table-container" style="margin-top: 30px;">
        <div class="table-header">
            <h5>Medical Prescriptions</h5>
        </div>
        @if($patient->prescriptions && $patient->prescriptions->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Duration</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->prescriptions as $prescription)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($prescription->created_at)->format('M d, Y') }}</td>
                                <td>{{ $prescription->doctor->name ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ $prescription->medicine->name ?? $prescription->medicine_name ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $prescription->dosage ?? 'N/A' }}</td>
                                <td>{{ $prescription->duration ?? 'N/A' }}</td>
                                <td>{{ $prescription->instructions ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <p style="margin: 0;">No prescriptions found</p>
            </div>
        @endif
    </div>

    <!-- Lab Results -->
    <div class="table-container" style="margin-top: 30px;">
        <div class="table-header">
            <h5>Lab Results</h5>
        </div>
        @if($patient->labResults && $patient->labResults->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Test Date</th>
                            <th>Test Name</th>
                            <th>Result Value</th>
                            <th>Normal Range</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->labResults as $result)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($result->test_date ?? $result->created_at)->format('M d, Y') }}</td>
                                <td>{{ $result->test_name ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ $result->result_value ?? 'N/A' }}</strong>
                                    @if($result->unit)
                                        <small>{{ $result->unit }}</small>
                                    @endif
                                </td>
                                <td>{{ $result->normal_range ?? '-' }}</td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if(strtolower($result->status ?? '') == 'normal') #26a69a
                                        @elseif(strtolower($result->status ?? '') == 'abnormal') #e53935
                                        @elseif(strtolower($result->status ?? '') == 'critical') #d32f2f
                                        @else #ff9800
                                        @endif
                                    ">
                                        {{ ucfirst($result->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td>{{ $result->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <p style="margin: 0;">No lab results found</p>
            </div>
        @endif
    </div>

@endsection
