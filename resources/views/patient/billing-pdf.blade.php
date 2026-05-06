<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: white;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .header-left h2 {
            color: #0d6efd;
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .header-left p {
            color: #666;
            margin: 3px 0;
            font-size: 12px;
        }
        
        .header-right {
            text-align: right;
        }
        
        .header-right h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .header-right p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        
        .row {
            display: flex;
            margin-bottom: 30px;
            gap: 50px;
        }
        
        .row-item {
            flex: 1;
        }
        
        .row-item h6 {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 13px;
        }
        
        .row-item p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        
        .row-item strong {
            color: #333;
        }
        
        .items-section {
            margin: 40px 0;
        }
        
        .items-section h6 {
            font-weight: 600;
            color: #333;
            margin: 20px 0 10px 0;
            font-size: 13px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        
        table thead {
            background-color: #f8f9fa;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #333;
        }
        
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #dee2e6;
            color: #666;
        }
        
        table .text-end {
            text-align: right;
        }
        
        .summary-section {
            margin-top: 40px;
            float: right;
            width: 40%;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        .summary-table tr {
            border: none;
        }
        
        .summary-table th {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            text-align: right;
            font-weight: 600;
            background: #f8f9fa;
        }
        
        .summary-table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #dee2e6;
        }
        
        .total-row th,
        .total-row td {
            background-color: #e7f1ff;
            border-top: 2px solid #0d6efd;
            font-size: 15px;
            color: #0d6efd;
            font-weight: 700;
        }
        
        .payment-status {
            clear: both;
            margin-top: 40px;
            padding: 15px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            color: #155724;
            font-size: 12px;
        }
        
        .payment-status.pending {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        
        .payment-status.cancelled {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .footer {
            clear: both;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        
        .notes {
            margin: 20px 0;
            padding: 12px;
            background: #f8f9fa;
            border-left: 3px solid #0d6efd;
            font-size: 12px;
        }
        
        .notes strong {
            color: #333;
        }

        .download-notice {
            background: #e7f1ff;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .container {
                max-width: 100%;
                padding: 0;
                margin: 0;
            }

            .download-notice {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Download Notice -->
        <div class="download-notice">
            <strong>💡 Tip:</strong> Use your browser's Print function (Ctrl+P or Cmd+P) and select "Save as PDF" to download this invoice.
        </div>

        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h2>HMS</h2>
                <p>Hospital Management System</p>
                <p>📍 123 Health Street</p>
                <p>📞 +880 1XXX XXX XXX</p>
                <p>📧 billing@hms.local</p>
            </div>
            <div class="header-right">
                <h3>INVOICE</h3>
                <p><strong>Invoice #:</strong> {{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date:</strong> {{ $billing->billing_date ? \Carbon\Carbon::parse($billing->billing_date)->format('M d, Y') : date('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('M d, Y') : 'On Demand' }}</p>
            </div>
        </div>

        <!-- Bill To & Service Provider -->
        <div class="row">
            <div class="row-item">
                <h6>Bill To:</h6>
                <p><strong>{{ $billing->patient->name ?? 'N/A' }}</strong></p>
                <p>📞 {{ $billing->patient->phone ?? 'N/A' }}</p>
                <p>📧 {{ $billing->patient->email ?? 'N/A' }}</p>
                <p>Patient ID: {{ $billing->patient_id }}</p>
            </div>
            <div class="row-item">
                <h6>Service Provider:</h6>
                @if($billing->appointment && $billing->appointment->doctor)
                    <p><strong>Dr. {{ $billing->appointment->doctor->name }}</strong></p>
                    <p>Specialist</p>
                @else
                    <p><strong>HMS Services</strong></p>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div class="items-section">
            @php
                $billingItems = json_decode($billing->billing_items, true);
                $prescriptions = $billingItems['prescriptions'] ?? [];
                $labResults = $billingItems['lab_results'] ?? [];
            @endphp

            @if(count($prescriptions) > 0)
                <h6>Prescriptions</h6>
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription['medicine_name'] }}</td>
                            <td>{{ $prescription['dosage'] }}</td>
                            <td>{{ $prescription['frequency'] }}</td>
                            <td class="text-end">₹{{ number_format($prescription['price'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if(count($labResults) > 0)
                <h6>Lab Test Charges</h6>
                <table>
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Result</th>
                            <th class="text-end">Charge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labResults as $lab)
                        <tr>
                            <td>{{ $lab['test_name'] }}</td>
                            <td>{{ $lab['result'] }}</td>
                            <td class="text-end">₹{{ number_format($lab['charge'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Summary -->
        @php
            $subtotal = 0;
            foreach($prescriptions as $prescription) {
                $subtotal += $prescription['price'] ?? 0;
            }
            foreach($labResults as $lab) {
                $subtotal += $lab['charge'] ?? 0;
            }
            if ($subtotal == 0) {
                $subtotal = $billing->subtotal ?? $billing->amount ?? 0;
            }
            $cgst = $subtotal * 0.09;
            $sgst = $subtotal * 0.09;
            $totalTax = $cgst + $sgst;
            $totalAmount = $subtotal + $totalTax;
        @endphp

        <div class="summary-section">
            <table class="summary-table">
                <tr style="background-color: #f8f9fa;">
                    <th>Subtotal:</th>
                    <td>₹{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>CGST (9%):</th>
                    <td>₹{{ number_format($cgst, 2) }}</td>
                </tr>
                <tr>
                    <th>SGST (9%):</th>
                    <td>₹{{ number_format($sgst, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Tax:</th>
                    <td>₹{{ number_format($totalTax, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <th>TOTAL AMOUNT:</th>
                    <td>₹{{ number_format($totalAmount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Payment Status -->
        <div class="payment-status @if($billing->payment_status === 'pending') pending @elseif($billing->payment_status !== 'paid') cancelled @endif">
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

        <!-- Notes -->
        @if($billing->notes)
        <div class="notes">
            <strong>Notes:</strong> {{ $billing->notes }}
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated invoice. No signature required.</p>
            <p>For queries, please contact billing@hms.local</p>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
