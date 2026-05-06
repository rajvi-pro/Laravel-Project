<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMS Dashboard Report - {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}</title>
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
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #0d6efd;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            margin: 5px 0;
            font-size: 12px;
        }
        
        .section {
            margin: 30px 0;
            page-break-inside: avoid;
        }
        
        .section h2 {
            background-color: #0d6efd;
            color: white;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .metric-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }
        
        .metric-card label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .metric-card .value {
            font-size: 24px;
            font-weight: 700;
            color: #0d6efd;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin: 15px 0;
        }
        
        table thead {
            background-color: #e7f1ff;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #0d6efd;
            font-weight: 600;
            color: #0d6efd;
        }
        
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        
        .note {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            font-size: 12px;
            color: #856404;
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
            
            .section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>HMS - Dashboard Report</h1>
            <p><strong>Hospital Management System</strong></p>
            <p>Report Period: <strong>{{ $startDate->format('d M Y') }}</strong> to <strong>{{ $endDate->format('d M Y') }}</strong></p>
            <p style="margin-top: 10px; color: #999;">Generated on {{ now()->format('d M Y \a\t H:i:s') }}</p>
        </div>

        <!-- Summary Metrics -->
        <div class="section">
            <h2>📊 Summary Metrics</h2>
            <div class="metrics-grid">
                <div class="metric-card">
                    <label>Total Patients</label>
                    <div class="value">{{ $totalPatients }}</div>
                </div>
                <div class="metric-card">
                    <label>Total Doctors</label>
                    <div class="value">{{ $totalDoctors }}</div>
                </div>
                <div class="metric-card">
                    <label>Total Staff</label>
                    <div class="value">{{ $totalStaff }}</div>
                </div>
                <div class="metric-card">
                    <label>Total Appointments</label>
                    <div class="value">{{ $appointments->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="section">
            <h2>💰 Financial Summary</h2>
            <div class="metrics-grid">
                <div class="metric-card">
                    <label>Paid Revenue</label>
                    <div class="value">₹{{ number_format($paidRevenue, 2) }}</div>
                </div>
                <div class="metric-card">
                    <label>Pending Revenue</label>
                    <div class="value">₹{{ number_format($pendingRevenue, 2) }}</div>
                </div>
                <div class="metric-card">
                    <label>Total Billings</label>
                    <div class="value">{{ $billings->count() }}</div>
                </div>
                <div class="metric-card">
                    <label>Total Revenue</label>
                    <div class="value">₹{{ number_format($paidRevenue + $pendingRevenue, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Billing Details -->
        <div class="section">
            <h2>📋 Billing Records ({{ $billings->count() }})</h2>
            @if($billings->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billings->take(50) as $billing)
                        <tr>
                            <td>INV-{{ str_pad($billing->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $billing->patient->name ?? 'N/A' }}</td>
                            <td>{{ $billing->appointment && $billing->appointment->doctor ? $billing->appointment->doctor->name : 'N/A' }}</td>
                            <td>₹{{ number_format($billing->amount, 2) }}</td>
                            <td>
                                @if($billing->payment_status === 'paid')
                                    <strong style="color: #28a745;">Paid</strong>
                                @elseif($billing->payment_status === 'pending')
                                    <strong style="color: #ffc107;">Pending</strong>
                                @else
                                    <strong style="color: #dc3545;">{{ ucfirst($billing->payment_status) }}</strong>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($billing->billing_date)->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($billings->count() > 50)
                <div class="note">
                    <strong>Note:</strong> Showing first 50 billing records. Total records: {{ $billings->count() }}
                </div>
                @endif
            @else
                <p style="color: #999; text-align: center; padding: 20px;">No billing records found for the selected period.</p>
            @endif
        </div>

        <!-- Appointment Details -->
        <div class="section">
            <h2>📅 Appointments ({{ $appointments->count() }})</h2>
            @if($appointments->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments->take(50) as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->doctor->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</td>
                            <td>{{ $appointment->appointment_time }}</td>
                            <td>
                                @if($appointment->status === 'completed')
                                    <strong style="color: #28a745;">Completed</strong>
                                @elseif($appointment->status === 'scheduled')
                                    <strong style="color: #0d6efd;">Scheduled</strong>
                                @elseif($appointment->status === 'cancelled')
                                    <strong style="color: #dc3545;">Cancelled</strong>
                                @else
                                    <strong>{{ ucfirst($appointment->status) }}</strong>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($appointments->count() > 50)
                <div class="note">
                    <strong>Note:</strong> Showing first 50 appointment records. Total records: {{ $appointments->count() }}
                </div>
                @endif
            @else
                <p style="color: #999; text-align: center; padding: 20px;">No appointments found for the selected period.</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This report was generated from HMS (Hospital Management System)</p>
            <p>For inquiries, please contact the system administrator.</p>
        </div>
    </div>

    <script>
        // Auto-trigger print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
