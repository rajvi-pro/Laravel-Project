@extends('layouts.staff-layout')

@section('page-title', 'Billing Details')
@section('title', 'Billing Invoice - Staff Portal')

@section('content')
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.billings') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Billings
        </a>
        <a href="#" class="btn btn-primary btn-sm" onclick="window.print(); return false;">
            <i class="fas fa-print"></i> Print Invoice
        </a>
        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#emailModal" title="Send Invoice to Patient">
            <i class="fas fa-envelope"></i> Send Email
        </button>
    </div>

    <!-- Invoice -->
    <div class="table-container" style="max-width: 800px;">
        <div style="padding: 30px; background: white; border: 1px solid #e0e0e0; border-radius: 8px;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #1565c0;">
                <h2 style="margin: 0; color: #1565c0; font-size: 28px; font-weight: 700;">
                    <i class="fas fa-receipt"></i> INVOICE
                </h2>
                <p style="margin: 0; color: #999; font-size: 12px;">{{ \Carbon\Carbon::now()->format('M d, Y') }}</p>
            </div>

            <!-- Patient Information -->
            <div style="margin-bottom: 30px;">
                <p style="margin: 0 0 5px 0; color: #999; font-weight: 600; font-size: 11px; text-transform: uppercase;">BILLED TO</p>
                <p style="margin: 0 0 3px 0; font-weight: 700; font-size: 15px;">{{ $billing->patient->name ?? $billing->patient->full_name ?? 'N/A' }}</p>
                <p style="margin: 0 0 3px 0; font-size: 13px; color: #666;">Patient ID: {{ $billing->patient->id ?? $billing->patient->medical_id ?? 'N/A' }}</p>
                <p style="margin: 0 0 3px 0; font-size: 13px; color: #666;">{{ $billing->patient->phone ?? 'N/A' }}</p>
                <p style="margin: 0; font-size: 13px; color: #666;">{{ $billing->patient->email ?? 'N/A' }}</p>
            </div>

            <!-- Billing Details -->
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <p style="margin: 0 0 5px 0; color: #999; font-weight: 600; font-size: 11px; text-transform: uppercase;">Billing Date</p>
                        <p style="margin: 0; font-weight: 600; font-size: 14px;">
                            {{ \Carbon\Carbon::parse($billing->billing_date ?? now())->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; color: #999; font-weight: 600; font-size: 11px; text-transform: uppercase;">Invoice #</p>
                        <p style="margin: 0; font-weight: 600; font-size: 14px;">BIL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; color: #999; font-weight: 600; font-size: 11px; text-transform: uppercase;">Status</p>
                        <p style="margin: 0;">
                            <span class="badge" style="background-color: 
                                @if($billing->payment_status == 'paid') #26a69a
                                @elseif($billing->payment_status == 'pending') #ff9800
                                @elseif($billing->payment_status == 'overdue') #e53935
                                @else #1565c0
                                @endif
                            ">
                                {{ ucfirst($billing->payment_status ?? 'Pending') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p style="margin: 0 0 5px 0; color: #999; font-weight: 600; font-size: 11px; text-transform: uppercase;">Total Amount</p>
                        <p style="margin: 0; font-weight: 700; font-size: 16px; color: #1565c0;">
                            ₹{{ number_format($billing->amount ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Billing Items -->
            <div style="margin-bottom: 30px;">
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Billing Items</h5>

                <!-- NEW: Bill Items from Table (Appointment-Based) -->
                @if($billing->billItems && $billing->billItems->count() > 0)
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
                        <thead>
                            <tr style="background: #f5f5f5; border-bottom: 2px solid #0d47a1;">
                                <th style="padding: 12px; text-align: left; font-weight: 700;">Description</th>
                                <th style="padding: 12px; text-align: center; font-weight: 700;">Qty</th>
                                <th style="padding: 12px; text-align: right; font-weight: 700;">Amount</th>
                                <th style="padding: 12px; text-align: right; font-weight: 700;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billing->billItems as $item)
                                <tr style="border-bottom: 1px solid #e0e0e0;">
                                    <td style="padding: 12px;">
                                        <div style="font-weight: 600;">{{ $item->description }}</div>
                                        <small style="color: #999;">
                                            @if($item->type === 'consultation')
                                                <span style="background: #e3f2fd; color: #0d47a1; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">CONSULTATION</span>
                                            @elseif($item->type === 'lab_test')
                                                <span style="background: #f3e5f5; color: #6a1b9a; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">LAB TEST</span>
                                            @elseif($item->type === 'extra_charge')
                                                <span style="background: #fff3e0; color: #ff9800; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">EXTRA CHARGE</span>
                                            @else
                                                <span style="background: #f0f0f0; color: #666; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">{{ strtoupper(str_replace('_', ' ', $item->type)) }}</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">{{ $item->quantity }}</td>
                                    <td style="padding: 12px; text-align: right;">₹{{ number_format($item->amount, 2) }}</td>
                                    <td style="padding: 12px; text-align: right; font-weight: 600;">₹{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <!-- LEGACY: Billing Items from JSON (Backward Compatibility) -->
                    @php
                        $billingItems = json_decode($billing->billing_items, true);
                        $prescriptions = $billingItems['prescriptions'] ?? [];
                        $labResults = $billingItems['lab_results'] ?? [];
                    @endphp

                    @if(count($prescriptions) > 0)
                        <div style="margin-bottom: 20px;">
                            <p style="margin: 0 0 10px 0; color: #666; font-weight: 600; font-size: 12px;">PRESCRIPTIONS</p>
                            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                <thead>
                                    <tr style="background: #f5f5f5; border-bottom: 1px solid #e0e0e0;">
                                        <th style="padding: 8px; text-align: left;">Medicine</th>
                                        <th style="padding: 8px; text-align: left;">Dosage</th>
                                        <th style="padding: 8px; text-align: left;">Frequency</th>
                                        <th style="padding: 8px; text-align: right;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                        <tr style="border-bottom: 1px solid #e0e0e0;">
                                            <td style="padding: 8px;">{{ $prescription['medicine_name'] }}</td>
                                            <td style="padding: 8px;">{{ $prescription['dosage'] }}</td>
                                            <td style="padding: 8px;">{{ $prescription['frequency'] }}</td>
                                            <td style="padding: 8px; text-align: right; font-weight: 600;">₹{{ number_format($prescription['price'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Lab Results Section -->
                    @if(count($labResults) > 0)
                        <div style="margin-bottom: 20px;">
                            <p style="margin: 0 0 10px 0; color: #666; font-weight: 600; font-size: 12px;">LAB TEST CHARGES</p>
                            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                <thead>
                                    <tr style="background: #f5f5f5; border-bottom: 1px solid #e0e0e0;">
                                        <th style="padding: 8px; text-align: left;">Test Name</th>
                                        <th style="padding: 8px; text-align: left;">Result</th>
                                        <th style="padding: 8px; text-align: right;">Charge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($labResults as $lab)
                                        <tr style="border-bottom: 1px solid #e0e0e0;">
                                            <td style="padding: 8px;">{{ $lab['test_name'] }}</td>
                                            <td style="padding: 8px;">{{ $lab['result'] }}</td>
                                            <td style="padding: 8px; text-align: right; font-weight: 600;">₹{{ number_format($lab['charge'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if(count($prescriptions) == 0 && count($labResults) == 0)
                        <div style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox"></i> No billing items found
                        </div>
                    @endif
                @endif
            </div>

            <!-- Summary with Taxes -->
            <div style="border-top: 2px solid #e0e0e0; padding-top: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666; font-weight: 600;">Subtotal:</span>
                    <span style="font-weight: 700;">₹{{ number_format($billing->subtotal ?? $billing->amount ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666; font-weight: 600;">CGST (9%):</span>
                    <span style="font-weight: 700;">₹{{ number_format($billing->cgst ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666; font-weight: 600;">SGST (9%):</span>
                    <span style="font-weight: 700;">₹{{ number_format($billing->sgst ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span style="color: #666; font-weight: 600;">Total Tax:</span>
                    <span style="font-weight: 700;">₹{{ number_format($billing->total_tax ?? 0, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; background: #f0f4ff; padding: 15px; border-radius: 8px; border-top: 2px solid #1565c0;">
                    <span style="color: #1565c0; font-weight: 700; font-size: 16px;">TOTAL:</span>
                    <span style="font-weight: 700; font-size: 16px; color: #1565c0;">₹{{ number_format($billing->amount ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Description -->
            @if($billing->description)
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                    <p style="margin: 0 0 10px 0; color: #999; font-weight: 600; font-size: 11px; text-transform: uppercase;">Notes</p>
                    <p style="margin: 0; color: #1a1a1a; line-height: 1.6;">
                        {{ $billing->description }}
                    </p>
                </div>
            @endif

            <!-- Footer -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center; color: #999; font-size: 12px;">
                <p style="margin: 0;">Hospital Management System - Staff Portal</p>
                <p style="margin: 5px 0 0 0;">{{ \Carbon\Carbon::now()->format('F d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Email Invoice Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-envelope"></i> Send Invoice to Patient</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('staff.billing.send-email', $billing->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            The invoice will be sent to: <strong>{{ $billing->patient->email ?? 'N/A' }}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Patient Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $billing->patient->email ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Custom Message (Optional)</label>
                            <textarea class="form-control" name="message" rows="3" placeholder="Add any special instructions or notes..."></textarea>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="include_payment_terms" id="include_payment_terms" checked>
                            <label class="form-check-label" for="include_payment_terms">
                                Include payment terms and instructions
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-paper-plane"></i> Send Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
