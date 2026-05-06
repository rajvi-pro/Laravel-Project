@extends('layouts.doctor-layout')

@section('title', 'Patient Details')

@section('content')
<style>
    .patient-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        color: white;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .patient-info-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .section-title {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.3rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .info-value {
        color: #6b7280;
        font-size: 1rem;
    }

    .tab-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .tab-buttons {
        display: flex;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .tab-btn {
        flex: 1;
        padding: 15px 20px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-weight: 600;
        color: #6b7280;
        transition: all 0.3s;
        text-align: center;
    }

    .tab-btn.active {
        background: #667eea;
        color: white;
    }

    .tab-btn:hover:not(.active) {
        background: #e5e7eb;
    }

    .tab-content {
        padding: 25px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .record-card {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #667eea;
    }

    .record-date {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .record-content {
        color: #374151;
        line-height: 1.6;
    }

    .btn-add {
        background: #667eea;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }

    .btn-add:hover {
        background: #5a67d8;
        color: white;
        text-decoration: none;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #374151;
    }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 10px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        font-size: 1rem;
        pointer-events: auto !important;
        position: relative !important;
        z-index: 3001 !important;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn-submit {
        background: #48bb78;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-submit:hover {
        background: #38a169;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }
</style>

<div class="patient-header">
    <div>
        <h1>{{ $patient->name }}</h1>
        <p>Patient ID: #{{ $patient->id }}</p>
    </div>
    <div>
        <a href="{{ route('doctor.patients') }}" class="btn-add" style="background: #6b7280;">← Back to Patients</a>
    </div>
</div>

<div class="patient-info-card">
    <h2 class="section-title">📋 Basic Information</h2>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $patient->email }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Phone</div>
            <div class="info-value">{{ $patient->phone ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Date of Birth</div>
            <div class="info-value">
                @if($patient->date_of_birth)
                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}
                    ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years old)
                @else
                    N/A
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Gender</div>
            <div class="info-value">{{ $patient->gender ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Blood Group</div>
            <div class="info-value">{{ $patient->blood_group ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Emergency Contact</div>
            <div class="info-value">{{ $patient->emergency_contact ?? 'N/A' }}</div>
        </div>
    </div>
    @if($patient->address)
    <div class="info-item" style="grid-column: 1 / -1;">
        <div class="info-label">Address</div>
        <div class="info-value">{{ $patient->address }}</div>
    </div>
    @endif
</div>

<div class="tab-container">
    <div class="tab-buttons">
        <button class="tab-btn active" onclick="showTab('appointments')">Appointments</button>
        <button class="tab-btn" onclick="showTab('prescriptions')">Prescriptions</button>
        <button class="tab-btn" onclick="showTab('medical-reports')">Medical Reports</button>
        <button class="tab-btn" onclick="showTab('lab-results')">Lab Results</button>
    </div>

    <div class="tab-content">
        <!-- Appointments Tab -->
        <div id="appointments" class="tab-pane active">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="section-title" style="margin: 0; border: none;">📅 Appointments</h3>
            </div>
            @forelse($patient->appointments->sortByDesc('appointment_date') as $appointment)
            <div class="record-card">
                <div class="record-date">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} at {{ $appointment->appointment_time }}</div>
                <div class="record-content">
                    <strong>Doctor:</strong> {{ $appointment->doctor->name ?? 'N/A' }}<br>
                    <strong>Reason:</strong> {{ $appointment->reason ?? 'N/A' }}<br>
                    <strong>Status:</strong> <span style="color: {{ $appointment->status == 'completed' ? '#48bb78' : ($appointment->status == 'cancelled' ? '#f56565' : '#ed8936') }};">{{ ucfirst($appointment->status ?? 'pending') }}</span><br>
                    @if($appointment->notes)<strong>Notes:</strong> {{ $appointment->notes }}@endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">📅</div>
                <h3>No appointments found</h3>
                <p>This patient has no appointment records.</p>
            </div>
            @endforelse
        </div>

        <!-- Prescriptions Tab -->
        <div id="prescriptions" class="tab-pane">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="section-title" style="margin: 0; border: none;">💊 Prescriptions</h3>
                <button class="btn-add" onclick="showAddForm('prescription')">+ Add Prescription</button>
            </div>
            @forelse($patient->prescriptions->sortByDesc('prescribed_date') as $prescription)
            <div class="record-card">
                <div class="record-date">{{ \Carbon\Carbon::parse($prescription->prescribed_date)->format('M d, Y') }}</div>
                <div class="record-content">
                    <strong>Medicine:</strong> {{ $prescription->medicine_name }}<br>
                    <strong>Dosage:</strong> {{ $prescription->dosage }}<br>
                    <strong>Frequency:</strong> {{ $prescription->frequency }}<br>
                    <strong>Duration:</strong> {{ $prescription->duration }}<br>
                    @if($prescription->instructions)<strong>Instructions:</strong> {{ $prescription->instructions }}@endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">💊</div>
                <h3>No prescriptions found</h3>
                <p>This patient has no prescription records.</p>
            </div>
            @endforelse
        </div>

        <!-- Medical Reports Tab -->
        <div id="medical-reports" class="tab-pane">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="section-title" style="margin: 0; border: none;">📋 Medical Reports</h3>
                <button class="btn-add" onclick="showAddForm('medical-report')">+ Add Medical Report</button>
            </div>
            @forelse($patient->medicalReports->sortByDesc('report_date') as $report)
            <div class="record-card">
                <div class="record-date">{{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}</div>
                <div class="record-content">
                    <strong>Diagnosis:</strong> {{ $report->diagnosis }}<br>
                    <strong>Symptoms:</strong> {{ $report->symptoms }}<br>
                    <strong>Treatment:</strong> {{ $report->treatment }}<br>
                    @if($report->notes)<strong>Notes:</strong> {{ $report->notes }}@endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No medical reports found</h3>
                <p>This patient has no medical report records.</p>
            </div>
            @endforelse
        </div>

        <!-- Lab Results Tab -->
        <div id="lab-results" class="tab-pane">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 class="section-title" style="margin: 0; border: none;">🧪 Lab Results</h3>
                <button class="btn-add" onclick="showAddForm('lab-result')">+ Add Lab Result</button>
            </div>
            @forelse($patient->labResults->sortByDesc('test_date') as $labResult)
            <div class="record-card">
                <div class="record-date">{{ \Carbon\Carbon::parse($labResult->test_date)->format('M d, Y') }}</div>
                <div class="record-content">
                    <strong>Test:</strong> {{ $labResult->test_name }}<br>
                    <strong>Result:</strong> {{ $labResult->result }}<br>
                    @if($labResult->normal_range)<strong>Normal Range:</strong> {{ $labResult->normal_range }}<br>@endif
                    <strong>Status:</strong> <span style="color: {{ $labResult->status == 'completed' ? '#48bb78' : '#ed8936' }};">{{ ucfirst($labResult->status) }}</span><br>
                    @if($labResult->notes)<strong>Notes:</strong> {{ $labResult->notes }}@endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">🧪</div>
                <h3>No lab results found</h3>
                <p>This patient has no lab result records.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Add Forms (Hidden by default) -->
    <div id="prescription-form" style="display: none; background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); margin-top: 20px; position: relative; z-index: 3000;">
    <h3>Add Prescription</h3>
    <form method="POST" action="{{ route('doctor.prescription.create') }}">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <div class="form-group">
            <label class="form-label">Medicine Name</label>
            <input type="text" name="medicine_name" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Dosage</label>
            <input type="text" name="dosage" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Frequency</label>
            <input type="text" name="frequency" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Duration</label>
            <input type="text" name="duration" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Instructions</label>
            <textarea name="instructions" class="form-textarea"></textarea>
        </div>
        <button type="submit" class="btn-submit">Add Prescription</button>
        <button type="button" onclick="hideForm()" style="background: #6b7280; margin-left: 10px;">Cancel</button>
    </form>
</div>

<div id="medical-report-form" style="display: none; background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); margin-top: 20px;">
    <h3>Add Medical Report</h3>
    <form method="POST" action="{{ route('doctor.medical-report.create') }}">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <div class="form-group">
            <label class="form-label">Diagnosis</label>
            <input type="text" name="diagnosis" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Symptoms</label>
            <textarea name="symptoms" class="form-textarea" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Treatment</label>
            <textarea name="treatment" class="form-textarea" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-textarea"></textarea>
        </div>
        <button type="submit" class="btn-submit">Add Medical Report</button>
        <button type="button" onclick="hideForm()" style="background: #6b7280; margin-left: 10px;">Cancel</button>
    </form>
</div>

<div id="lab-result-form" style="display: none; background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); margin-top: 20px;">
    <h3>Add Lab Result</h3>
    <form method="POST" action="{{ route('doctor.lab-result.create') }}">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <div class="form-group">
            <label class="form-label">Test Name</label>
            <input type="text" name="test_name" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Result</label>
            <textarea name="result" class="form-textarea" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Normal Range</label>
            <input type="text" name="normal_range" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-textarea"></textarea>
        </div>
        <button type="submit" class="btn-submit">Add Lab Result</button>
        <button type="button" onclick="hideForm()" style="background: #6b7280; margin-left: 10px;">Cancel</button>
    </form>
</div>

<script>
    function showTab(tabName) {
        // Hide all tab panes
        const tabPanes = document.querySelectorAll('.tab-pane');
        tabPanes.forEach(pane => pane.classList.remove('active'));

        // Remove active class from all tab buttons
        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => btn.classList.remove('active'));

        // Show selected tab
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');

        // Hide any open forms
        hideForm();
    }

    function showAddForm(formType) {
        hideForm();
        document.getElementById(formType + '-form').style.display = 'block';
    }

    function hideForm() {
        const forms = document.querySelectorAll('[id$="-form"]');
        forms.forEach(form => form.style.display = 'none');
    }
</script>
@endsection