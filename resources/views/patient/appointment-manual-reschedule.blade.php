@extends('layouts.patient-layout')
@section('title', 'Manual Reschedule Appointment')
@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📅 Reschedule Your Appointment</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Current Appointment Details</h5>
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>👨‍⚕️ Doctor:</strong> {{ $appointment->doctor->name ?? 'N/A' }}</p>
                                <p><strong>📅 Current Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>🕐 Current Time:</strong> {{ date('g:i A', strtotime($appointment->appointment_time)) }}</p>
                                <p><strong>📌 Status:</strong> <span class="badge bg-info">{{ ucfirst($appointment->status) }}</span></p>
                            </div>
                        </div>
                    </div>

                    @if(session('unavailable_warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            ⚠️ {{ session('unavailable_warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <hr>

                    <form method="POST" action="{{ route('patient.appointment.manualRescheduleSave', $appointment->id) }}" id="rescheduleForm">
                        @csrf
                        <div class="mb-4">
                            <label for="appointment_date" class="form-label fw-bold">📅 Select New Date</label>
                            <input type="date" class="form-control form-control-lg" id="appointment_date" name="appointment_date" 
                                   value="{{ old('appointment_date') }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   onchange="checkDoctorAvailability()" required>
                            @if($errors->has('appointment_date'))
                                <div class="text-danger mt-2">{{ $errors->first('appointment_date') }}</div>
                            @endif
                            <div id="availabilityAlert"></div>
                        </div>

                        <div class="mb-4">
                            <label for="appointment_time" class="form-label fw-bold">🕐 Select New Time Slot</label>
                            <div id="slotsLoadingMessage" style="display: none;" class="alert alert-info">
                                ⏳ Loading available time slots...
                            </div>
                            <div id="slotsUnavailableMessage" style="display: none;" class="alert alert-danger">
                                ❌ No time slots available for this doctor on the selected date.
                            </div>
                            <div class="time-slots-container" id="timeSlotsContainer" style="display: none;">
                                <div class="btn-group-vertical w-100" role="group">
                                    <input type="hidden" id="appointment_time" name="appointment_time" required>
                                    <div class="d-grid gap-2" id="slotsGrid">
                                        <!-- Slots will be dynamically inserted here -->
                                    </div>
                                </div>
                            </div>
                            <div id="selectedSlot" class="mt-2"></div>
                            @if($errors->has('appointment_time'))
                                <div class="text-danger mt-2">{{ $errors->first('appointment_time') }}</div>
                            @endif
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>💾 Save Changes</button>
                            <a href="{{ route('patient.appointments') }}" class="btn btn-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Doctor Unavailable Modal -->
<div class="modal fade" id="doctorUnavailableModal" tabindex="-1" role="dialog" aria-labelledby="doctorUnavailableLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="doctorUnavailableLabel">
                    <i class="fas fa-exclamation-circle"></i> Doctor Is Unavailable
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-center mb-3">
                    <i class="fas fa-calendar-times" style="font-size: 48px; color: #dc3545;"></i>
                </p>
                <p class="text-center fs-5 mb-3">
                    <strong>The doctor is unavailable on the selected date.</strong>
                </p>
                <p class="text-center text-muted mb-0">
                    Please select a different date to reschedule your appointment.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check"></i> Okay
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .slot-btn {
        text-align: left;
        padding: 12px 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .slot-btn:hover {
        background-color: #e7f1ff;
        border-color: #0d6efd;
    }
    
    .slot-btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .time-slots-container {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
</style>

<script>
    const doctorId = '{{ $appointment->doctor_id }}';

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
                document.getElementById('selectedSlot').innerHTML = `<div class="alert alert-success mb-0">✅ Selected: <strong>${slotText}</strong></div>`;
                
                // Enable submit button
                document.getElementById('submitBtn').disabled = false;
            });
        });
    }

    function loadAvailableTimeSlots() {
        const dateInput = document.getElementById('appointment_date').value;
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const slotsGrid = document.getElementById('slotsGrid');
        const loadingMsg = document.getElementById('slotsLoadingMessage');
        const unavailableMsg = document.getElementById('slotsUnavailableMessage');
        
        // Reset UI
        timeSlotsContainer.style.display = 'none';
        loadingMsg.style.display = 'none';
        unavailableMsg.style.display = 'none';
        document.getElementById('selectedSlot').innerHTML = '';
        document.getElementById('appointment_time').value = '';
        document.getElementById('submitBtn').disabled = true;
        
        // Validate inputs
        if (!dateInput) {
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
                // Doctor is completely unavailable on this date - Show popup
                const unavailableModal = new bootstrap.Modal(document.getElementById('doctorUnavailableModal'), {
                    keyboard: false,
                    backdrop: 'static'
                });
                unavailableModal.show();
                return;
            }

            if (!data.slots || data.slots.length === 0) {
                // No available slots (all booked)
                unavailableMsg.textContent = '❌ No available time slots for this doctor on the selected date. All slots are booked.';
                unavailableMsg.style.display = 'block';
                return;
            }

            // Group slots by period
            const morningSlots = data.slots.filter(s => s.period === 'Morning');
            const afternoonSlots = data.slots.filter(s => s.period === 'Afternoon');
            const eveningSlots = data.slots.filter(s => s.period === 'Evening');

            let html = '';

            // Render morning slots
            if (morningSlots.length > 0) {
                html += '<div class="text-muted small ms-2 mt-2">Morning Slots</div>';
                morningSlots.forEach(slot => {
                    html += `<button type="button" class="slot-btn btn btn-outline-primary text-start" data-time="${slot.time}">${slot.display}</button>`;
                });
            }

            // Show break if there are afternoon slots (indicating a break exists between morning and afternoon)
            if (morningSlots.length > 0 && (afternoonSlots.length > 0 || eveningSlots.length > 0)) {
                html += '<div class="text-muted small ms-2 mt-2"><span class="badge bg-warning">Break Time</span></div>';
            }

            // Render afternoon slots
            if (afternoonSlots.length > 0) {
                html += '<div class="text-muted small ms-2 mt-2">Afternoon Slots</div>';
                afternoonSlots.forEach(slot => {
                    html += `<button type="button" class="slot-btn btn btn-outline-primary text-start" data-time="${slot.time}">${slot.display}</button>`;
                });
            }

            // Render evening slots
            if (eveningSlots.length > 0) {
                html += '<div class="text-muted small ms-2 mt-2">Evening Slots</div>';
                eveningSlots.forEach(slot => {
                    html += `<button type="button" class="slot-btn btn btn-outline-primary text-start" data-time="${slot.time}">${slot.display}</button>`;
                });
            }

            // Update the grid and show container
            slotsGrid.innerHTML = html;
            timeSlotsContainer.style.display = 'block';

            // Attach event listeners to new buttons
            attachSlotEventListeners();
        })
        .catch(error => {
            console.error('Error loading available slots:', error);
            loadingMsg.style.display = 'none';
            unavailableMsg.textContent = '❌ Error loading available time slots. Please try again.';
            unavailableMsg.style.display = 'block';
        });
    }

    function checkDoctorAvailability() {
        loadAvailableTimeSlots();
    }

    // Trigger slot loading when date changes
    document.getElementById('appointment_date').addEventListener('change', loadAvailableTimeSlots);

    // Load slots on page load if a date has been set
    window.addEventListener('load', function() {
        if (document.getElementById('appointment_date').value) {
            loadAvailableTimeSlots();
        }
    });
</script>
@endsection
