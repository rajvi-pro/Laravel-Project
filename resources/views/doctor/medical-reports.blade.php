@extends('layouts.doctor-layout')

@section('title', 'Medical Reports')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        color: white;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section h1 {
        margin: 0;
        font-weight: 700;
        font-size: 2rem;
    }

    .btn-create {
        background: white;
        color: #667eea;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-create:hover {
        background: #f3f4f6;
    }

    .report-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border-left: 5px solid #667eea;
        transition: all 0.3s ease;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .report-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
    }

    .report-date {
        background: #f3f4f6;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #374151;
        font-weight: 600;
    }

    .report-patient {
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .report-content {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        margin: 15px 0;
        line-height: 1.6;
        color: #374151;
    }

    .report-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .header-section h1 {
            font-size: 1.5rem;
        }

        .report-header {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<div class="container-fluid">
    <div class="header-section">
        <div>
            <h1>📋 Medical Reports</h1>
        </div>
        <a href="#" class="btn-create" data-bs-toggle="modal" data-bs-target="#createReportModal">
            ➕ Create New Report
        </a>
    </div>
    @if($medicalReports->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h2 style="color: #6b7280; font-size: 1.5rem;">No medical reports</h2>
            <p>You haven't created any medical reports yet. Create your first report to get started.</p>
            <button class="action-btn action-btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal" style="margin-top: 20px;">
                Create Medical Report
            </button>
        </div>
    @else
        @foreach($medicalReports as $report)
            <div class="report-card">
                <div class="report-header">
                    <div>
                        <div class="report-title">📄 {{ $report->diagnosis ?? 'Medical Report' }}</div>
                        <div class="report-patient">👤 Patient: {{ $report->patient->name ?? 'N/A' }}</div>
                    </div>
                    <div class="report-date">{{ \Carbon\Carbon::parse($report->created_at)->format('M d, Y') }}</div>
                </div>

                <div class="report-content">
                    <strong>Symptoms:</strong><br>
                    {{ $report->symptoms ?? 'No symptoms recorded' }}
                </div>

                @if($report->treatment)
                    <div class="report-content">
                        <strong>Treatment:</strong><br>
                        {{ $report->treatment }}
                    </div>
                @endif

                <div class="report-actions">
                    <a href="#" class="action-btn action-btn-primary">👁️ View Full Report</a>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Create Report Modal -->
<div class="modal fade" id="createReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 12px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <h5 class="modal-title">➕ Create Medical Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <form method="POST" action="{{ route('doctor.medical-report.create') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Select Patient</label>
                        <select class="form-select" name="patient_id" required style="border-radius: 6px; border: 2px solid #e5e7eb;">
                            <option value="" disabled {{ old('patient_id') ? '' : 'selected' }}>Choose a patient...</option>
                            @forelse($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                            @empty
                                <option disabled>No patients available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Diagnosis</label>
                        <input type="text" class="form-control" name="diagnosis" value="{{ old('diagnosis') }}" placeholder="e.g., Hypertension" required style="border-radius: 6px; border: 2px solid #e5e7eb;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Symptoms</label>
                        <textarea class="form-control" name="symptoms" rows="4" placeholder="Describe patient symptoms..." required style="border-radius: 6px; border: 2px solid #e5e7eb;">{{ old('symptoms') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Treatment</label>
                        <textarea class="form-control" name="treatment" rows="3" placeholder="Recommended treatment plan..." required style="border-radius: 6px; border: 2px solid #e5e7eb;">{{ old('treatment') }}</textarea>
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="padding: 10px 20px; background: #f3f4f6; color: #374151; border-radius: 6px; border: none; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 6px; border: none; font-weight: 600;">Create Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('createReportModal');
            if (modalEl) {
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    </script>
@endif

@endsection
