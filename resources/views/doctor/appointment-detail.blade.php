@extends('layouts.doctor-layout')

@section('title', 'Appointment Details')

@section('content')
<style>
    .detail-header {
        background: white;
        padding: 25px 30px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .detail-header h1 {
        color: #1a1a1a;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .detail-header p {
        color: #666;
        margin-bottom: 0;
    }

    .detail-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .detail-card h5 {
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #0f61cc;
    }

    .detail-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0f61cc;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 1rem;
        color: #1a1a1a;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-scheduled {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
    }

    .btn-primary {
        background: #0f61cc;
        color: white;
    }

    .btn-primary:hover {
        background: #0a3b96;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(15, 97, 204, 0.3);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
        border: 1px solid #d0d0d0;
    }

    .btn-secondary:hover {
        background: #e5e5e5;
    }

    @media (max-width: 768px) {
        .detail-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <div class="detail-header">
        <h1>{{ $appointment->patient->name ?? 'N/A' }}</h1>
        <p>Appointment Details</p>
    </div>

    <div class="detail-card">
        <h5>📅 Appointment Information</h5>
        <div class="detail-row">
            <div class="detail-item">
                <div class="detail-label">📅 Date</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, M d, Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">🕐 Time</div>
                <div class="detail-value">{{ date('g:i A', strtotime($appointment->appointment_time)) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">📌 Status</div>
                <div>
                    @if($appointment->status === 'scheduled')
                        <span class="status-badge status-scheduled">Scheduled</span>
                    @elseif($appointment->status === 'completed')
                        <span class="status-badge status-completed">Completed</span>
                    @else
                        <span class="status-badge status-cancelled">{{ ucfirst($appointment->status) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h5>📋 Additional Details</h5>
        <div class="detail-row">
            <div class="detail-item">
                <div class="detail-label">📝 Reason for Visit</div>
                <div class="detail-value">{{ $appointment->reason ?? 'General Checkup' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">📞 Patient Phone</div>
                <div class="detail-value">{{ $appointment->patient->phone ?? 'N/A' }}</div>
            </div>
            @if($appointment->notes)
            <div class="detail-item">
                <div class="detail-label">📌 Notes</div>
                <div class="detail-value">{{ $appointment->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    @if($lastCheckup)
    <div class="detail-card">
        <h5>📆 Last Checkup History</h5>
        <div class="detail-row">
            <div class="detail-item">
                <div class="detail-label">🔍 Last Checkup Date</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($lastCheckup->appointment_date)->format('l, M d, Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">⏱️ Time</div>
                <div class="detail-value">{{ date('g:i A', strtotime($lastCheckup->appointment_time)) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">📝 Previous Reason</div>
                <div class="detail-value">{{ $lastCheckup->reason ?? 'General Checkup' }}</div>
            </div>
        </div>
    </div>
    @else
    <div class="detail-card">
        <h5>📆 Last Checkup History</h5>
        <p class="text-muted">This is the patient's first appointment.</p>
    </div>
    @endif

    @if($prescriptions->count() > 0)
    <div class="detail-card">
        <h5>💊 Prescriptions</h5>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Medicine</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Dosage</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Frequency</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Duration</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px;">{{ $prescription->medicine_name }}</td>
                        <td style="padding: 12px;">{{ $prescription->dosage }}</td>
                        <td style="padding: 12px;">{{ $prescription->frequency }}</td>
                        <td style="padding: 12px;">{{ $prescription->duration }}</td>
                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($prescription->prescribed_date)->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($medicalReports->count() > 0)
    <div class="detail-card">
        <h5>📄 Medical Reports</h5>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Diagnosis</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Symptoms</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Treatment</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicalReports as $report)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px; max-width: 150px;">{{ Str::limit($report->diagnosis, 50) }}</td>
                        <td style="padding: 12px; max-width: 150px;">{{ Str::limit($report->symptoms, 50) }}</td>
                        <td style="padding: 12px; max-width: 150px;">{{ Str::limit($report->treatment, 50) }}</td>
                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($labResults->count() > 0)
    <div class="detail-card">
        <h5>🧪 Lab Results</h5>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Test Name</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Result</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($labResults as $result)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px;">{{ $result->test_name }}</td>
                        <td style="padding: 12px; max-width: 150px;">{{ Str::limit($result->result, 50) }}</td>
                        <td style="padding: 12px;">
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; background: {{ $result->status === 'completed' ? '#dcfce7' : '#fef08a' }}; color: {{ $result->status === 'completed' ? '#166534' : '#854d0e' }};">
                                {{ ucfirst($result->status) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($result->test_date)->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="action-buttons" style="margin-top: 20px;">
        <a href="{{ route('doctor.appointments') }}" class="btn-action btn-secondary">← Back to Appointments</a>
        <a href="{{ route('doctor.prescription.create.form') }}?patient_id={{ $appointment->patient->id }}&appointment_id={{ $appointment->id }}" 
           class="btn-action btn-primary">💊 Add Prescription</a>
        <a href="{{ route('doctor.medical-report.create.form') }}?patient_id={{ $appointment->patient->id }}&appointment_id={{ $appointment->id }}" 
           class="btn-action btn-primary">📄 Add Medical Report</a>
        <a href="{{ route('doctor.lab-result.create.form') }}?patient_id={{ $appointment->patient->id }}&appointment_id={{ $appointment->id }}" 
           class="btn-action btn-primary">🧪 Add Lab Report</a>
    </div>
</div>
@endsection
