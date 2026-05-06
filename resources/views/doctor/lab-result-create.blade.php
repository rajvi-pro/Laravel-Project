@extends('layouts.doctor-layout')

@section('title', 'Add Lab Result')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Add Lab Result</h1>
        <div>
            <a href="{{ route('doctor.lab-results') }}" class="btn btn-outline-secondary me-2">← Dashboard</a>
            <a href="{{ route('doctor.lab-results-simple') }}" class="btn btn-outline-primary">Existing Results</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('doctor.lab-result.create') }}">
                @csrf
                @if(request()->get('appointment_id'))
                    <input type="hidden" name="appointment_id" value="{{ request()->get('appointment_id') }}">
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient</label>
                        <select class="form-select" name="patient_id" required {{ !$hasPatients ? 'disabled' : '' }}>
                            <option value="">Select patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}"
                                    @if(old('patient_id') == $patient->id)
                                        selected
                                    @elseif(request()->get('patient_id') == $patient->id)
                                        selected
                                    @endif
                                >{{ $patient->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('patient_id'))
                            <div class="alert alert-danger mt-2" role="alert">
                                {{ $errors->first('patient_id') }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Test Name</label>
                        <input type="text" class="form-control" name="test_name" value="{{ old('test_name') }}" placeholder="e.g., Blood Test" required {{ !$hasPatients ? 'disabled' : '' }}>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Result</label>
                        <input type="text" class="form-control" name="result" value="{{ old('result') }}" placeholder="e.g., 120 mg/dL" required {{ !$hasPatients ? 'disabled' : '' }}>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Normal Range</label>
                        <input type="text" class="form-control" name="normal_range" value="{{ old('normal_range') }}" placeholder="e.g., 70-110 mg/dL" {{ !$hasPatients ? 'disabled' : '' }}>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required {{ !$hasPatients ? 'disabled' : '' }}>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Optional" {{ !$hasPatients ? 'disabled' : '' }}>{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" {{ !$hasPatients ? 'disabled' : '' }}>Save Lab Result</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for No Patients -->
@if(!$hasPatients)
<div class="modal fade" id="noPatientModal" tabindex="-1" aria-labelledby="noPatientModalLabel" aria-hidden="false" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="noPatientModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> No Patients Available
                </h5>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    <strong>You cannot add lab results at this time.</strong>
                </p>
                <p class="mb-3">
                    We found that you don't have any patients with appointments. You can only add prescriptions, medical reports, and lab results for patients you have appointments with.
                </p>
                <p class="mb-3">
                    Please schedule an appointment with a patient first, or check your appointments list.
                </p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('doctor.appointments') }}" class="btn btn-primary">View Appointments</a>
                <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">Go to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('noPatientModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    });

    // Pre-fill patient ID from URL query parameter (fallback)
    const urlParams = new URLSearchParams(window.location.search);
    const patientId = urlParams.get('patient_id');
    
    if (patientId) {
        const patientSelect = document.querySelector('select[name="patient_id"]');
        if (patientSelect && !patientSelect.value) {
            patientSelect.value = patientId;
            patientSelect.dispatchEvent(new Event('change'));
        }
    }
</script>
@endif
@endsection
