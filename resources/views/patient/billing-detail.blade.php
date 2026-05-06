@extends('layouts.patient-layout')

@section('title', 'Billing Statement')

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
        <h1 class="h3">📄 Billing Statement</h1>
        <div>
            <a href="{{ route('patient.billing.pdf', $billing->id) }}" class="btn btn-outline-primary">📥 Download as PDF</a>
            <a href="{{ route('patient.billings') }}" class="btn btn-outline-secondary">← Back</a>
        </div>
    </div>

    <div class="card shadow-sm" id="invoice">
        <div class="card-body p-5">
            <!-- Invoice Header -->
            <div class="row mb-4 pb-4 border-bottom">
                <div class="col-md-6">
                    <h2 style="color: #0d6efd; font-weight: 700;">HMS</h2>
                    <p style="color: #666;">Hospital Management System</p>
                    <p style="font-size: 12px; color: #999;">
                        📍 123 Health Street<br>
                        📞 +880 1XXX XXX XXX<br>
                        📧 billing@hms.local
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 style="color: #333;">INVOICE</h5>
                    <p style="margin: 5px 0; color: #666;">
                        <strong>Invoice #:</strong> {{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}<br>
                        <strong>Date:</strong> {{ $billing->billing_date ? \Carbon\Carbon::parse($billing->billing_date)->format('M d, Y') : date('M d, Y') }}<br>
                        <strong>Due Date:</strong> {{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('M d, Y') : 'On Demand' }}
                    </p>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 style="color: #333; font-weight: 600;">Bill To:</h6>
                    <p style="margin: 5px 0; color: #666;">
                        <strong>{{ $billing->patient->name ?? 'N/A' }}</strong><br>
                        📞 {{ $billing->patient->phone ?? 'N/A' }}<br>
                        📧 {{ $billing->patient->email ?? 'N/A' }}<br>
                        Patient ID: {{ $billing->patient_id }}
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 style="color: #333; font-weight: 600;">Service Provider:</h6>
                    <p style="margin: 5px 0; color: #666;">
                        @if($billing->appointment && $billing->appointment->doctor)
                            <strong>Dr. {{ $billing->appointment->doctor->name }}</strong><br>
                            Specialist
                        @else
                            <strong>HMS Services</strong>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Invoice Items & Billing Details -->
            <div class="row mb-4">
                <div class="col-12">
                    @php
                        $billingItems = json_decode($billing->billing_items, true);
                        $prescriptions = $billingItems['prescriptions'] ?? [];
                        $labResults = $billingItems['lab_results'] ?? [];
                    @endphp

                    <!-- Prescriptions Table -->
                    @if(count($prescriptions) > 0)
                    <h6 style="color: #333; font-weight: 600; margin-top: 20px; margin-bottom: 10px;">Prescriptions</h6>
                    <table class="table table-borderless" style="font-size: 13px; margin-bottom: 20px;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Medicine</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Dosage</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Frequency</th>
                                <th class="text-end" style="padding: 12px; border-bottom: 2px solid #dee2e6;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescriptions as $prescription)
                            <tr>
                                <td style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">{{ $prescription['medicine_name'] }}</td>
                                <td style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">{{ $prescription['dosage'] }}</td>
                                <td style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">{{ $prescription['frequency'] }}</td>
                                <td class="text-end" style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">₹{{ number_format($prescription['price'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                    <!-- Lab Results Table -->
                    @if(count($labResults) > 0)
                    <h6 style="color: #333; font-weight: 600; margin-top: 20px; margin-bottom: 10px;">Lab Test Charges</h6>
                    <table class="table table-borderless" style="font-size: 13px; margin-bottom: 20px;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Test Name</th>
                                <th style="padding: 12px; border-bottom: 2px solid #dee2e6;">Result</th>
                                <th class="text-end" style="padding: 12px; border-bottom: 2px solid #dee2e6;">Charge</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($labResults as $lab)
                            <tr>
                                <td style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">{{ $lab['test_name'] }}</td>
                                <td style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">{{ $lab['result'] }}</td>
                                <td class="text-end" style="padding: 10px 12px; border-bottom: 1px solid #dee2e6;">₹{{ number_format($lab['charge'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                    <!-- Summary with Taxes -->
                    @php
                        // Calculate totals from billing items
                        $subtotal = 0;
                        
                        // Sum prescription prices
                        foreach($prescriptions as $prescription) {
                            $subtotal += $prescription['price'] ?? 0;
                        }
                        
                        // Sum lab test charges
                        foreach($labResults as $lab) {
                            $subtotal += $lab['charge'] ?? 0;
                        }
                        
                        // If subtotal is still 0, use stored values
                        if ($subtotal == 0) {
                            $subtotal = $billing->subtotal ?? $billing->amount ?? 0;
                        }
                        
                        // Calculate taxes
                        $cgst = $subtotal * 0.09; // 9% CGST
                        $sgst = $subtotal * 0.09; // 9% SGST
                        $totalTax = $cgst + $sgst;
                        $totalAmount = $subtotal + $totalTax;
                    @endphp
                    
                    <table class="table table-borderless" style="font-size: 14px; margin-top: 30px;">
                        <tfoot>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 12px; text-align: right;">Subtotal:</th>
                                <th class="text-end" style="padding: 12px;">₹{{ number_format($subtotal, 2) }}</th>
                            </tr>
                            <tr>
                                <th style="padding: 12px; text-align: right;">CGST (9%):</th>
                                <th class="text-end" style="padding: 12px;">₹{{ number_format($cgst, 2) }}</th>
                            </tr>
                            <tr>
                                <th style="padding: 12px; text-align: right;">SGST (9%):</th>
                                <th class="text-end" style="padding: 12px;">₹{{ number_format($sgst, 2) }}</th>
                            </tr>
                            <tr>
                                <th style="padding: 12px; text-align: right; color: #666;">Total Tax:</th>
                                <th class="text-end" style="padding: 12px; color: #666;">₹{{ number_format($totalTax, 2) }}</th>
                            </tr>
                            <tr style="background-color: #e7f1ff; border-top: 2px solid #0d6efd;">
                                <th style="padding: 15px 12px; text-align: right; font-size: 16px; color: #0d6efd;">TOTAL AMOUNT:</th>
                                <th class="text-end" style="padding: 15px 12px; font-size: 16px; color: #0d6efd;">₹{{ number_format($totalAmount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert @if($billing->payment_status === 'paid') alert-success @elseif($billing->payment_status === 'pending') alert-warning @else alert-danger @endif mb-0">
                        <strong>Payment Status:</strong>
                        @if($billing->payment_status === 'paid')
                            ✅ Paid on {{ $billing->updated_at->format('M d, Y') }}
                            @if($billing->payment_method)
                                via {{ ucfirst($billing->payment_method) }}
                            @endif
                        @elseif($billing->payment_status === 'pending')
                            ⏳ Payment Pending - Please arrange payment as soon as possible
                        @else
                            ❌ {{ ucfirst($billing->payment_status) }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($billing->notes)
            <div class="row mb-4">
                <div class="col-12">
                    <h6 style="color: #333; font-weight: 600;">Notes:</h6>
                    <p style="color: #666; margin: 0;">{{ $billing->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="row mt-5 pt-4 border-top text-center">
                <div class="col-12">
                    <p style="color: #999; font-size: 12px; margin: 0;">
                        This is a computer-generated invoice. No signature required.<br>
                        For queries, please contact billing@hms.local
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .d-flex {
            display: none !important;
        }
        #invoice {
            box-shadow: none !important;
        }
        body {
            background: white !important;
        }
    }
</style>
@endsection
