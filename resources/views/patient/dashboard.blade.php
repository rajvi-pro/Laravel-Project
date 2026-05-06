@extends('layouts.patient-layout')

@section('page-title', 'Dashboard')
@section('title', 'Patient Dashboard - HMS')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}"><i class="bi bi-file-text"></i> Medical History</a>
    <a href="{{ route('patient.prescriptions') }}"><i class="bi bi-prescription"></i> Prescriptions</a>
    <a href="{{ route('patient.lab-results') }}"><i class="bi bi-beaker"></i> Lab Results</a>
    <a href="{{ route('patient.billings') }}"><i class="bi bi-receipt"></i> Billings</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('patient.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="py-4 mb-3 rounded shadow-sm" style="background: linear-gradient(90deg,#6f42c1,#0d6efd); color: #fff;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">  Good day, {{ session('patient_name') ?? 'Patient' }}!</h2>
                <small class="opacity-75"> Here’s a summary of your health dashboard</small>
            </div>
            <div>
                <a href="{{ route('patient.doctor-select') }}" class="btn btn-light btn-lg">Book Appointment</a>
            </div>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="row mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="metric-card blue">
            <div class="metric-label">Upcoming Appointments</div>
            <div class="metric-value">{{ $upcomingAppointments ?? 0 }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Total Appointments</div>
            <div class="metric-value">{{ $totalAppointments ?? 0 }}</div>
        </div>
        <div class="metric-card green">
            <div class="metric-label">Active Prescriptions</div>
            <div class="metric-value">{{ $activePrescriptions ?? 0 }}</div>
        </div>
        <div class="metric-card orange">
            <div class="metric-label">Lab Results</div>
            <div class="metric-value">{{ $labResults ?? 0 }}</div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Pending Bills</div>
            <div class="metric-value" style="color: #fa6c3d;">{{ $pendingBillings ?? 0 }}</div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                    <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="card-body">
                    @if($appointments->isEmpty())
                        <div class="text-center py-5 text-muted">No upcoming appointments. Book one to get started.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($appointments as $appointment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $appointment->doctor->name ?? 'N/A' }}</strong>
                                        <div class="small text-muted">{{ $appointment->appointment_date->toFormattedDateString() }} • {{ date('H:i', strtotime($appointment->appointment_time)) }}</div>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">{{ ucfirst($appointment->status) }}</span>
                                        <a href="{{ route('patient.appointment.detail', $appointment->id) }}" class="btn btn-sm btn-link">Details</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('patient.doctor-select') }}" class="btn btn-primary w-100 mb-2">Book Appointment</a>
                    <a href="{{ route('patient.profile') }}" class="btn btn-outline-secondary w-100 mb-2">Edit Profile</a>
                    <a href="{{ route('patient.medical-history') }}" class="btn btn-outline-info w-100">Medical History</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Prescriptions Section -->
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-prescription"></i> Recent Prescriptions</h5>
                    <a href="{{ route('patient.prescriptions') }}" class="btn btn-sm btn-light">All</a>
                </div>
                <div class="card-body">
                    @if($prescriptions->isEmpty())
                        <div class="text-center py-4 text-muted">No prescriptions yet.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($prescriptions as $prescription)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <strong style="font-size: 14px;">{{ $prescription->medicine_name ?? 'N/A' }}</strong>
                                        <div class="small text-muted" style="font-size: 12px;">
                                            By: Dr. {{ $prescription->doctor->name ?? 'N/A' }}
                                        </div>
                                        <div class="small text-muted" style="font-size: 12px;">
                                            {{ $prescription->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <a href="{{ route('patient.prescription.detail', $prescription->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Lab Results Section -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-beaker"></i> Recent Lab Results</h5>
                    <a href="{{ route('patient.lab-results') }}" class="btn btn-sm btn-outline-warning">All</a>
                </div>
                <div class="card-body">
                    @if($labResults == 0)
                        <div class="text-center py-4 text-muted">No lab results yet.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($labResultsArray as $result)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <strong style="font-size: 14px;">{{ $result->test_name ?? 'N/A' }}</strong>
                                        <div class="small text-muted" style="font-size: 12px;">
                                            By: Dr. {{ $result->doctor->name ?? 'N/A' }}
                                        </div>
                                        <div class="small text-muted" style="font-size: 12px;">
                                            {{ $result->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <a href="{{ route('patient.lab-result.detail', $result->id) }}" class="btn btn-sm btn-outline-warning" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Billings Section -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Recent Billings</h5>
                    <a href="{{ route('patient.billings') }}" class="btn btn-sm btn-light">All</a>
                </div>
                <div class="card-body">
                    @if($billings->isEmpty())
                        <div class="text-center py-4 text-muted">No billings yet.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($billings as $billing)
                                @php
                                    // Calculate billing amount from items if not stored
                                    $billingAmount = $billing->amount ?? 0;
                                    
                                    if ($billingAmount == 0 && $billing->billing_items) {
                                        $billingItems = json_decode($billing->billing_items, true);
                                        $itemsSubtotal = 0;
                                        
                                        // Sum prescription prices
                                        if (isset($billingItems['prescriptions'])) {
                                            foreach($billingItems['prescriptions'] as $prescription) {
                                                $itemsSubtotal += $prescription['price'] ?? 0;
                                            }
                                        }
                                        
                                        // Sum lab charges
                                        if (isset($billingItems['lab_results'])) {
                                            foreach($billingItems['lab_results'] as $lab) {
                                                $itemsSubtotal += $lab['charge'] ?? 0;
                                            }
                                        }
                                        
                                        // Calculate with 18% GST (9% CGST + 9% SGST)
                                        $billingAmount = $itemsSubtotal * 1.18;
                                    }
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <strong style="font-size: 14px;">{{ $billing->bill_number ?? 'Bill #' . $billing->id }}</strong>
                                        <div class="small text-muted" style="font-size: 12px;">
                                            Amount: <strong>₹{{ number_format($billingAmount, 2) }}</strong>
                                        </div>
                                        <div class="small" style="font-size: 12px;">
                                            <span class="badge bg-{{ $billing->payment_status == 'paid' ? 'success' : ($billing->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($billing->payment_status ?? 'pending') }}
                                            </span>
                                        </div>
                                        <div class="small text-muted" style="font-size: 12px;">
                                            {{ $billing->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="btn-group-vertical btn-group-sm" role="group">
                                        <a href="{{ route('patient.billing.view', $billing->id) }}" class="btn btn-outline-danger" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('patient.billing.pdf', $billing->id) }}" class="btn btn-outline-secondary" title="Print" target="_blank">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unavailable Doctor Notification Modal -->
@if(!empty($unavailableDoctors))
<div class="modal fade" id="unavailabilityModal" tabindex="-1" role="dialog" aria-labelledby="unavailabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="unavailabilityModalLabel">⚠️ Doctor Unavailable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 15px;">
                    <strong>We noticed that one or more of your doctors are currently unavailable:</strong>
                </p>
                <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 15px;">
                    @foreach($affectedAppointments as $appt)
                        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ffe69c;">
                            <strong>Dr. {{ $appt->doctor->name }}</strong>
                            <br>
                            <small>📅 Your appointment on {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</small>
                            <br>
                            <small style="color: #856404;">
                                ⏸️ Status: May be affected
                            </small>
                        </div>
                    @endforeach
                </div>
                <p style="color: #666;">
                    Please check with your doctor or reschedule your appointment if needed. Click the button below to view and manage your appointments.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('patient.appointments') }}" class="btn btn-warning">View My Appointments</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Show unavailability modal on page load
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('unavailabilityModal'), {
            keyboard: false,
            backdrop: 'static'
        });
        var unavailabilityModal = document.getElementById('unavailabilityModal');
        if (unavailabilityModal) {
            modal.show();
        }
    });
</script>
@endif
@endsection
