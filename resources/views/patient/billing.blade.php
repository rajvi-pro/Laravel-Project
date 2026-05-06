@extends('layouts.patient-layout')

@section('title', 'My Billings')

@section('content')
<div class="container-fluid mt-4">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">💳 My Billing Statements</h1>
        <div>
            @if($billings->count() > 0)
                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">🖨️ Print</button>
            @endif
        </div>
    </div>

    @if($billings->isEmpty())
        <div class="alert alert-info text-center py-5">
            <h5>No billing records found</h5>
            <p>You don't have any billing statements yet.</p>
        </div>
    @else
        <!-- Billing Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Total Billed</h6>
                        <h3 class="text-primary">₹{{ number_format($totalAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Paid</h6>
                        <h3 class="text-success">₹{{ number_format($paidAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Pending</h6>
                        <h3 class="text-warning">₹{{ number_format($pendingAmount, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Total Records</h6>
                        <h3 class="text-info">{{ $billings->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billings Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">📋 Billing Details</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>📅 Date</th>
                            <th>👨‍⚕️ Doctor / Service</th>
                            <th>📝 Description</th>
                            <th>💰 Amount</th>
                            <th>📌 Status</th>
                            <th>📄 Action</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        <tr>
                            <td>
                                <small>{{ $billing->billing_date ? \Carbon\Carbon::parse($billing->billing_date)->format('M d, Y') : 'N/A' }}</small>
                            </td>
                            <td>
                                @if($billing->appointment)
                                    {{ $billing->appointment->doctor->name ?? 'N/A' }}
                                @else
                                    {{ $billing->description ?? 'Service' }}
                                @endif
                            </td>
                            <td>
                                <small>{{ Str::limit($billing->description, 30) }}</small>
                            </td>
                            <td>
                                <strong>₹{{ number_format($billingAmount, 2) }}</strong>
                            </td>
                            <td>
                                @if($billing->payment_status === 'paid')
                                    <span class="badge bg-success">✅ Paid</span>
                                @elseif($billing->payment_status === 'pending')
                                    <span class="badge bg-warning">⏳ Pending</span>
                                @else
                                    <span class="badge bg-danger">❌ {{ ucfirst($billing->payment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('patient.billing.view', $billing->id) }}" class="btn btn-sm btn-primary" title="View Bill">
                                    👁️ View
                                </a>
                                <a href="{{ route('patient.billing.pdf', $billing->id) }}" class="btn btn-sm btn-secondary" title="Download PDF">
                                    📥 PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $billings->links() }}
        </div>
    @endif
</div>

<style>
    @media print {
        .btn, .d-flex {
            display: none !important;
        }
        .card {
            break-inside: avoid;
        }
    }
</style>
@endsection
