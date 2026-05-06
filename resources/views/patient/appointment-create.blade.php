@extends('layouts.patient-layout')

@section('title', 'Book Appointment')

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="bi bi-calendar-check"></i> Book Appointment
        </h1>
        <a href="{{ isset($doctor) ? route('patient.doctor-select') : route('patient.appointments') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> {{ isset($doctor) ? 'Back to Doctors' : 'Back to Appointments' }}
        </a>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('patient.appointment.store') }}">
                @csrf

                <!-- Doctor Selection (Only if not pre-selected) -->
                @if(!isset($doctor))
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person-fill text-primary"></i> Select Doctor
                        </label>
                        <select id="doctor_select" name="doctor_id" class="form-select form-select-lg" required onchange="checkPatientDoctorAvailability()">
                            <option value="">-- Choose a doctor --</option>
                            @foreach($doctors as $doc)
                                @php
                                    $isAvailable = $doc->isAvailableToday();
                                    $unavailabilityMsg = !$isAvailable ? $doc->getUnavailabilityMessage() : null;
                                @endphp
                                <option value="{{ $doc->id }}" 
                                    {{ old('doctor_id') == $doc->id ? 'selected' : '' }}
                                    data-available="{{ $isAvailable ? 'true' : 'false' }}"
                                    data-message="{{ $unavailabilityMsg ?? '' }}">
                                    <strong>{{ $doc->name }}</strong> — {{ $doc->specialization }}
                                    @if(!$isAvailable)
                                        (⚠️ Not Available Today)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small id="patient_availability_message" class="text-danger d-block mt-2" style="display: none;"></small>
                    </div>
                @else
                    <!-- Doctor Pre-selected -->
                    <div class="mb-4">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle-fill"></i> <strong>Doctor Selected:</strong> Dr. {{ $doctor->name }}
                            <br>
                            <small>{{ $doctor->specialization }}</small>
                            <a href="{{ route('patient.doctor-select') }}" class="alert-link ms-2">Change doctor?</a>
                        </div>
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}" required>
                    </div>
                @endif

                <!-- Date Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar2-event text-primary"></i> Select Date
                    </label>
                    <input type="date" id="appointment_date" name="appointment_date" class="form-control form-control-lg" 
                           value="{{ old('appointment_date') }}" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           onchange="loadAvailableTimeSlots()" 
                           required>
                    <div id="availabilityAlert"></div>
                </div>

                <!-- Time Slot Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-clock text-primary"></i> Select Time Slot
                    </label>

                    <!-- Loading Message -->
                    <div id="slotsLoadingMessage" class="alert alert-info alert-dismissible fade show" style="display: none;">
                        <i class="bi bi-hourglass-split"></i> Loading available time slots...
                    </div>

                    <!-- Unavailable Message -->
                    <div id="slotsUnavailableMessage" class="alert alert-danger alert-dismissible fade show" style="display: none;"></div>

                    <!-- Time Slots Container -->
                    <div id="timeSlotsContainer" style="display: none;">
                        <input type="hidden" id="appointment_time" name="appointment_time" required>

                        <!-- Morning Slots -->
                        <div id="morningSection" class="mb-4" style="display: none;">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-sunrise text-warning me-2" style="font-size: 1.5rem;"></i>
                                <h6 class="mb-0 text-uppercase text-muted">Morning Slots</h6>
                                <span class="badge bg-warning-light text-dark ms-2" id="morningCount"></span>
                            </div>
                            <div class="row g-2" id="morningSlots"></div>
                        </div>

                        <!-- Afternoon Slots -->
                        <div id="afternoonSection" class="mb-4" style="display: none;">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-sun text-success me-2" style="font-size: 1.5rem;"></i>
                                <h6 class="mb-0 text-uppercase text-muted">Afternoon Slots</h6>
                                <span class="badge bg-success-light text-dark ms-2" id="afternoonCount"></span>
                            </div>
                            <div class="row g-2" id="afternoonSlots"></div>
                        </div>

                        <!-- Evening Slots -->
                        <div id="eveningSection" class="mb-4" style="display: none;">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-moon-stars text-info me-2" style="font-size: 1.5rem;"></i>
                                <h6 class="mb-0 text-uppercase text-muted">Evening Slots</h6>
                                <span class="badge bg-info-light text-dark ms-2" id="eveningCount"></span>
                            </div>
                            <div class="row g-2" id="eveningSlots"></div>
                        </div>

                        <!-- Selected Slot Display -->
                        <div id="selectedSlot" class="mt-4"></div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-chat-left-text text-primary"></i> Reason for Visit (Optional)
                    </label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Describe your symptoms or reason for consultation...">{{ old('reason') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Clear
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" onclick="return validateForm()">
                        <i class="bi bi-check-circle"></i> Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        const timeInput = document.getElementById('appointment_time').value;
        if (!timeInput) {
            alert('Please select a time slot');
            return false;
        }
        return true;
    }

    function attachSlotEventListeners() {
        document.querySelectorAll('.slot-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all buttons
                document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Set the hidden input value
                const timeValue = this.getAttribute('data-time');
                document.getElementById('appointment_time').value = timeValue;
                
                // Update selected slot display
                const slotText = this.textContent.trim();
                const slotDiv = document.getElementById('selectedSlot');
                slotDiv.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> <strong>Selected Time:</strong> ${slotText}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Clear selection" onclick="clearTimeSlotSelection()"></button>
                    </div>
                `;
            });
        });
    }

    function clearTimeSlotSelection() {
        document.getElementById('appointment_time').value = '';
        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('selectedSlot').innerHTML = '';
    }

    function loadAvailableTimeSlots() {
        const dateInput = document.getElementById('appointment_date').value;
        
        // Try to get doctor ID from dropdown, or from hidden input (for pre-selected doctor)
        let doctorId = null;
        const doctorSelect = document.getElementById('doctor_select');
        if (doctorSelect) {
            doctorId = doctorSelect.value;
        } else {
            // For pre-selected doctor in quick-book
            const hiddenDoctorInput = document.querySelector('input[name="doctor_id"]');
            if (hiddenDoctorInput) {
                doctorId = hiddenDoctorInput.value;
            }
        }
        
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const loadingMsg = document.getElementById('slotsLoadingMessage');
        const unavailableMsg = document.getElementById('slotsUnavailableMessage');
        
        // Reset UI
        timeSlotsContainer.style.display = 'none';
        loadingMsg.style.display = 'none';
        unavailableMsg.style.display = 'none';
        document.getElementById('selectedSlot').innerHTML = '';
        document.getElementById('appointment_time').value = '';
        
        // Validate inputs
        if (!dateInput || !doctorId) {
            return;
        }

        // Show loading message
        loadingMsg.style.display = 'block';

        // Fetch available slots
        fetch('{{ route("patient.getAvailableSlots") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                doctor_id: doctorId,
                appointment_date: dateInput
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingMsg.style.display = 'none';

            if (data.unavailable) {
                unavailableMsg.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> <strong>Doctor Unavailable:</strong> ${data.reason}`;
                unavailableMsg.style.display = 'block';
                timeSlotsContainer.style.display = 'none';
                return;
            }

            if (!data.slots || data.slots.length === 0) {
                unavailableMsg.innerHTML = `<i class="bi bi-x-circle-fill"></i> <strong>No Slots Available:</strong> All time slots are booked for this doctor on the selected date.`;
                unavailableMsg.style.display = 'block';
                timeSlotsContainer.style.display = 'none';
                return;
            }

            // Group slots by period
            const morningSlots = data.slots.filter(s => s.period === 'Morning');
            const afternoonSlots = data.slots.filter(s => s.period === 'Afternoon');
            const eveningSlots = data.slots.filter(s => s.period === 'Evening');

            // Render morning slots
            const morningSection = document.getElementById('morningSection');
            const morningContainer = document.getElementById('morningSlots');
            if (morningSlots.length > 0) {
                morningContainer.innerHTML = morningSlots.map(slot => 
                    `<div class="col-md-4 col-sm-6">
                        <button type="button" class="slot-btn btn btn-outline-warning w-100" data-time="${slot.time}">
                            <i class="bi bi-sunrise"></i> ${slot.display}
                        </button>
                    </div>`
                ).join('');
                morningSection.style.display = 'block';
                document.getElementById('morningCount').textContent = `${morningSlots.length} slots`;
            } else {
                morningSection.style.display = 'none';
            }

            // Render afternoon slots
            const afternoonSection = document.getElementById('afternoonSection');
            const afternoonContainer = document.getElementById('afternoonSlots');
            if (afternoonSlots.length > 0) {
                afternoonContainer.innerHTML = afternoonSlots.map(slot => 
                    `<div class="col-md-4 col-sm-6">
                        <button type="button" class="slot-btn btn btn-outline-success w-100" data-time="${slot.time}">
                            <i class="bi bi-sun"></i> ${slot.display}
                        </button>
                    </div>`
                ).join('');
                afternoonSection.style.display = 'block';
                document.getElementById('afternoonCount').textContent = `${afternoonSlots.length} slots`;
            } else {
                afternoonSection.style.display = 'none';
            }

            // Render evening slots
            const eveningSection = document.getElementById('eveningSection');
            const eveningContainer = document.getElementById('eveningSlots');
            if (eveningSlots.length > 0) {
                eveningContainer.innerHTML = eveningSlots.map(slot => 
                    `<div class="col-md-4 col-sm-6">
                        <button type="button" class="slot-btn btn btn-outline-info w-100" data-time="${slot.time}">
                            <i class="bi bi-moon-stars"></i> ${slot.display}
                        </button>
                    </div>`
                ).join('');
                eveningSection.style.display = 'block';
                document.getElementById('eveningCount').textContent = `${eveningSlots.length} slots`;
            } else {
                eveningSection.style.display = 'none';
            }

            // Show container and attach listeners
            timeSlotsContainer.style.display = 'block';
            attachSlotEventListeners();
        })
        .catch(error => {
            console.error('Error loading available slots:', error);
            loadingMsg.style.display = 'none';
            unavailableMsg.innerHTML = `<i class="bi bi-bug-fill"></i> <strong>Error:</strong> Failed to load available time slots. Please try again.`;
            unavailableMsg.style.display = 'block';
        });
    }

    function checkDoctorAvailability() {
        loadAvailableTimeSlots();
    }

    function checkPatientDoctorAvailability() {
        const select = document.getElementById('doctor_select');
        const selected = select.options[select.selectedIndex];
        const isAvailable = selected.getAttribute('data-available') === 'true';
        const message = selected.getAttribute('data-message');
        const messageDiv = document.getElementById('patient_availability_message');

        if (!isAvailable && message) {
            messageDiv.textContent = '⚠️ Doctor not available today: ' + message;
            messageDiv.style.display = 'block';
        } else {
            messageDiv.style.display = 'none';
        }

        // Load available slots if date is already selected
        if (document.getElementById('appointment_date').value) {
            loadAvailableTimeSlots();
        }
    }

    // Event listeners
    document.getElementById('appointment_date').addEventListener('change', loadAvailableTimeSlots);
    
    // Only add event listener for doctor_select if it exists
    const doctorSelect = document.getElementById('doctor_select');
    if (doctorSelect) {
        doctorSelect.addEventListener('change', checkPatientDoctorAvailability);
    }

    // Load slots on page load if both doctor and date are already selected
    window.addEventListener('load', function() {
        const appointmentDate = document.getElementById('appointment_date').value;
        
        // Get doctor ID from either dropdown or hidden input
        let doctorId = null;
        const doctorSelectElement = document.getElementById('doctor_select');
        if (doctorSelectElement) {
            doctorId = doctorSelectElement.value;
        } else {
            const hiddenDoctorInput = document.querySelector('input[name="doctor_id"]');
            if (hiddenDoctorInput) {
                doctorId = hiddenDoctorInput.value;
            }
        }
        
        if (doctorId && appointmentDate) {
            loadAvailableTimeSlots();
        }
    });
</script>

<style>
    .slot-btn {
        padding: 12px 15px;
        font-weight: 600;
        border: 2px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .slot-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .slot-btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
    }

    .btn-outline-warning {
        color: #ff9800;
        border-color: #ff9800;
    }

    .btn-outline-warning:hover:not(.active) {
        background-color: #fff3cd;
        border-color: #ff9800;
        color: #ff9800;
    }

    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
    }

    .btn-outline-success:hover:not(.active) {
        background-color: #d4edda;
        border-color: #28a745;
        color: #28a745;
    }

    .btn-outline-info {
        color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-outline-info:hover:not(.active) {
        background-color: #d1ecf1;
        border-color: #17a2b8;
        color: #17a2b8;
    }

    .badge.bg-warning-light {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    .badge.bg-success-light {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .badge.bg-info-light {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
    }

    .card {
        border-radius: 12px;
    }

    .form-label {
        color: #1a1a1a;
        margin-bottom: 0.8rem;
    }

    .form-select-lg, .form-control-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }

    .col-md-4 .slot-btn {
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .slot-btn {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
    }
</style>

@endsection
