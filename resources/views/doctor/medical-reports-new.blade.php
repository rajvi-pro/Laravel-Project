@extends('layouts.doctor-layout')

@section('title', 'Medical Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">📋 Medical Reports</h1>
            <p class="text-muted">Create and view medical reports quickly.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal">➕ Create New Report</button>
    </div>

    <div class="card mb-3">
        <div class="card-body p-3">
            <h5>Existing Reports</h5>
            @if($medicalReports->isEmpty())
                <p class="mb-0">No reports yet.</p>
            @else
                <table class="table table-striped table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Diagnosis</th>
                            <th>Symptoms</th>
                            <th>Treatment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicalReports as $report)
                        <tr>
                            <td>{{ $report->report_date?->format('Y-m-d') ?? $report->created_at->format('Y-m-d') }}</td>
                            <td>{{ optional($report->patient)->name ?? 'N/A' }}</td>
                            <td>{{ $report->diagnosis ?? 'N/A' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->symptoms, 80) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->treatment, 80) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $medicalReports->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="createReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Medical Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('doctor.medical-report.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">Choose a patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Diagnosis</label>
                        <input type="text" name="diagnosis" value="{{ old('diagnosis') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Symptoms</label>
                        <textarea name="symptoms" class="form-control" rows="3" required>{{ old('symptoms') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Treatment</label>
                        <textarea name="treatment" class="form-control" rows="3" required>{{ old('treatment') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('createReportModal'));
        modal.show();
    });
</script>
@endif
@endsection
