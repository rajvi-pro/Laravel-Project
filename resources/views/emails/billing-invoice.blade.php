<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #BIL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1565c0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1565c0;
            margin: 0;
            font-size: 32px;
        }
        .header p {
            color: #999;
            margin: 5px 0 0 0;
        }
        .patient-info {
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .patient-info p {
            margin: 5px 0;
        }
        .patient-info strong {
            color: #1a1a1a;
        }
        .billing-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .detail-box {
            display: inline-block;
            width: 23%;
            margin-right: 2%;
            vertical-align: top;
        }
        .detail-label {
            color: #999;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .detail-value {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a1a;
        }
        .items-section {
            margin-bottom: 30px;
        }
        .items-section h3 {
            color: #1a1a1a;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table thead {
            background: #f5f5f5;
            border-bottom: 2px solid #ddd;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #666;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        table tbody tr:hover {
            background: #fafafa;
        }
        .summary {
            border-top: 2px solid #e0e0e0;
            padding-top: 15px;
            margin-bottom: 30px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .summary-row.total {
            font-size: 16px;
            font-weight: bold;
            color: #1565c0;
            border-top: 2px solid #1565c0;
            padding-top: 10px;
            margin-top: 10px;
        }
        .payment-info {
            background: #e3f2fd;
            border-left: 4px solid #1565c0;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 3px;
        }
        .payment-info h4 {
            margin: 0 0 10px 0;
            color: #1565c0;
        }
        .payment-info p {
            margin: 5px 0;
            font-size: 13px;
            color: #666;
        }
        .custom-message {
            background: #fffde7;
            border-left: 4px solid #fbc02d;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 3px;
        }
        .custom-message p {
            margin: 0;
            color: #333;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #999;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .status-paid {
            background: #26a69a;
        }
        .status-pending {
            background: #ff9800;
        }
        .status-overdue {
            background: #e53935;
        }
        .medical-services {
            background: #f0f4ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .medical-services h4 {
            color: #1565c0;
            margin: 0 0 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💳 INVOICE</h1>
            <p>Hospital Management System</p>
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <strong>BILLED TO:</strong><br>
            <strong style="font-size: 16px;">{{ $patient->name ?? $patient->full_name ?? 'N/A' }}</strong><br>
            Patient ID: {{ $patient->id ?? $patient->medical_id ?? 'N/A' }}<br>
            Phone: {{ $patient->phone ?? 'N/A' }}<br>
            Email: {{ $patient->email ?? 'N/A' }}
        </div>

        <!-- Billing Details -->
        <div class="billing-details">
            <div class="detail-box">
                <div class="detail-label">Billing Date</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($billing->billing_date ?? now())->format('M d, Y') }}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label">Invoice #</div>
                <div class="detail-value">BIL-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ $billing->payment_status ?? 'pending' }}">
                        {{ ucfirst($billing->payment_status ?? 'Pending') }}
                    </span>
                </div>
            </div>
            <div class="detail-box">
                <div class="detail-label">Total Amount</div>
                <div class="detail-value" style="color: #1565c0;">₹{{ number_format($billing->amount ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- Medical Services -->
        @if(count($prescriptions) > 0 || count($lab_results) > 0)
            <div class="items-section">
                <h3>💊 Medical Services & Charges</h3>

                <!-- Prescriptions -->
                @if(count($prescriptions) > 0)
                    <div style="margin-bottom: 20px;">
                        <strong style="color: #666; font-size: 12px;">PRESCRIBED MEDICINES</strong>
                        <table>
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th style="text-align: right;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription['medicine_name'] ?? 'N/A' }}</td>
                                        <td>{{ $prescription['dosage'] ?? 'N/A' }}</td>
                                        <td>{{ $prescription['frequency'] ?? 'N/A' }}</td>
                                        <td style="text-align: right; font-weight: bold;">₹{{ number_format($prescription['price'] ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Lab Tests -->
                @if(count($lab_results) > 0)
                    <div style="margin-bottom: 20px;">
                        <strong style="color: #666; font-size: 12px;">LAB TEST CHARGES</strong>
                        <table>
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Result Status</th>
                                    <th style="text-align: right;">Charge</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lab_results as $lab)
                                    <tr>
                                        <td>{{ $lab['test_name'] ?? 'N/A' }}</td>
                                        <td>{{ $lab['result'] ?? 'Pending' }}</td>
                                        <td style="text-align: right; font-weight: bold;">₹{{ number_format($lab['charge'] ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

        <!-- Summary with Taxes -->
        <div class="summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₹{{ number_format($billing->subtotal ?? $billing->amount ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>CGST Tax (9%):</span>
                <span>₹{{ number_format($billing->cgst ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>SGST Tax (9%):</span>
                <span>₹{{ number_format($billing->sgst ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Total Tax:</span>
                <span>₹{{ number_format($billing->total_tax ?? 0, 2) }}</span>
            </div>
            <div class="summary-row total">
                <span>TOTAL AMOUNT DUE:</span>
                <span>₹{{ number_format($billing->amount ?? 0, 2) }}</span>
            </div>
        </div>

        <!-- Payment Terms -->
        @if($include_payment_terms)
            <div class="payment-info">
                <h4>💳 Payment Instructions</h4>
                <p><strong>Status:</strong> {{ ucfirst($billing->payment_status ?? 'Pending') }}</p>
                <p>
                    @if($billing->payment_status == 'pending')
                        Please arrange payment at your earliest convenience. You can make the payment through cash, card, or online transfer at the hospital reception.
                    @elseif($billing->payment_status == 'paid')
                        Thank you! We have received your payment. This invoice is now settled.
                    @else
                        This invoice is now overdue. Please contact the hospital immediately to settle the payment.
                    @endif
                </p>
                <p><strong>For inquiries:</strong> Contact the billing department at the hospital.</p>
            </div>
        @endif

        <!-- Custom Message -->
        @if($custom_message)
            <div class="custom-message">
                <p><strong>Note from Hospital:</strong><br>{{ $custom_message }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated invoice generated by Hospital Management System.</p>
            <p>Please retain this invoice for your records. For any questions, contact the billing department.</p>
            <p>Generated on: {{ \Carbon\Carbon::now()->format('F d, Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
