@extends('layouts.doctor-layout')

@section('title', 'Create Prescription')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Create Prescription</h1>
        <a href="{{ route('doctor.prescriptions') }}" class="btn btn-outline-secondary">← Back to Prescriptions</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('doctor.prescription.create') }}" id="create-prescription-form">
                @csrf
                @if(request()->get('appointment_id'))
                    <input type="hidden" name="appointment_id" value="{{ request()->get('appointment_id') }}">
                @endif

                <div class="mb-3">
                    <label for="patient_id" class="form-label">Select Patient</label>
                    <select id="patient_id" name="patient_id" class="form-select" required {{ !$hasPatients ? 'disabled' : '' }}>
                        <option value="" disabled selected>Choose a patient...</option>
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

                <div class="mb-3">
                    <label for="medicine_name" class="form-label">Medicine Name</label>
                    <input type="text" id="medicine_name" name="medicine_name" value="{{ old('medicine_name') }}" class="form-control" placeholder="e.g., Amoxicillin" required {{ !$hasPatients ? 'disabled' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="dosage" class="form-label">Dosage</label>
                    <input type="text" id="dosage" name="dosage" value="{{ old('dosage') }}" class="form-control" placeholder="e.g., 500mg" required {{ !$hasPatients ? 'disabled' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="frequency" class="form-label">Frequency</label>
                    <input type="text" id="frequency" name="frequency" value="{{ old('frequency') }}" class="form-control" placeholder="e.g., Twice a day" required {{ !$hasPatients ? 'disabled' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration</label>
                    <input type="text" id="duration" name="duration" value="{{ old('duration') }}" class="form-control" placeholder="e.g., 7 days" required {{ !$hasPatients ? 'disabled' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="instructions" class="form-label">Instructions</label>
                    <textarea id="instructions" name="instructions" class="form-control" rows="3" placeholder="Special instructions..." {{ !$hasPatients ? 'disabled' : '' }}>{{ old('instructions') }}</textarea>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" {{ !$hasPatients ? 'disabled' : '' }}>Create Prescription</button>
                    <a href="{{ route('doctor.prescriptions') }}" class="btn btn-secondary">Cancel</a>
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
                    <strong>You cannot add prescriptions at this time.</strong>
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
</script>
@endif

<script>
    // Pre-fill patient ID from URL query parameter (fallback)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const patientId = urlParams.get('patient_id');
        
        if (patientId) {
            const patientSelect = document.getElementById('patient_id');
            if (patientSelect && !patientSelect.value) {
                patientSelect.value = patientId;
                patientSelect.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endsection
