@extends('layouts.staff-layout')

@section('page-title', 'Appointment Details')
@section('title', 'Appointment Details - Staff Portal')

@section('content')
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.appointments') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Appointments
        </a>
    </div>

    <div class="table-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <!-- Patient Information -->
            <div>
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Patient Information</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0;">
                        <strong>Name:</strong>
                        {{ $appointment->patient->name ?? $appointment->patient->full_name ?? 'N/A' }}
                    </p>
                    <p style="margin: 0 0 10px 0;">
                        <strong>Patient ID:</strong>
                        {{ $appointment->patient->id ?? $appointment->patient->medical_id ?? 'N/A' }}
                    </p>
                    <p style="margin: 0 0 10px 0;">
                        <strong>Age:</strong>
                        @if($appointment->patient->date_of_birth)
                            {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} years
                        @else
                            N/A
                        @endif
                    </p>
                    <p style="margin: 0;">
                        <strong>Contact:</strong>
                        {{ $appointment->patient->phone ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Appointment Information -->
            <div>
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Appointment Information</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0;">
                        <strong>Doctor:</strong>
                        {{ $appointment->doctor->name ?? 'Unassigned' }}
                    </p>
                    <p style="margin: 0 0 10px 0;">
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') ?? 'N/A' }}
                    </p>
                    <p style="margin: 0 0 10px 0;">
                        <strong>Time:</strong>
                        {{ $appointment->appointment_time ?? 'N/A' }}
                    </p>
                    <p style="margin: 0;">
                        <strong>Status:</strong>
                        <span class="badge" style="background-color: 
                            @if($appointment->status == 'completed') #26a69a
                            @elseif($appointment->status == 'pending') #ff9800
                            @elseif($appointment->status == 'cancelled') #e53935
                            @else #1565c0
                            @endif
                        ">
                            {{ ucfirst($appointment->status ?? 'Scheduled') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Visit Reason -->
            <div>
                <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Visit Details</h5>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0;">
                        <strong>Reason:</strong>
                    </p>
                    <p style="margin: 5px 0 0 0; color: #666;">
                        {{ $appointment->reason ?? 'No reason specified' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Update Appointment Section -->
        <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid #e0e0e0;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Status Update -->
                <div>
                    <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">Update Status</h5>
                    <form action="{{ route('staff.appointment.update', $appointment->id) }}" method="POST" style="max-width: 500px;">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="pending" @if($appointment->status == 'pending') selected @endif>Pending</option>
                                <option value="completed" @if($appointment->status == 'completed') selected @endif>Completed</option>
                                <option value="cancelled" @if($appointment->status == 'cancelled') selected @endif>Cancelled</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Status
                        </button>
                    </form>
                </div>

                <!-- Reschedule Appointment -->
                <div>
                    <h5 style="margin-bottom: 15px; font-weight: 700; color: #1a1a1a;">📅 Reschedule Appointment</h5>
                    <form action="{{ route('staff.appointment.reschedule', $appointment->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Select New Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control" 
                                   value="{{ old('appointment_date', $appointment->appointment_date) }}" 
                                   min="{{ date('Y-m-d') }}"
                                   onchange="loadAvailableTimeSlots()" required>
                            @if($errors->has('appointment_date'))
                                <div class="text-danger mt-2">{{ $errors->first('appointment_date') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">🕐 Select New Time Slot</label>
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
                            @if($errors->has('appointment_time'))
                                <div class="text-danger mt-2">{{ $errors->first('appointment_time') }}</div>
                            @endif
                        </div>

                        <div id="selectedSlot" class="mt-2"></div>

                        <button type="submit" class="btn btn-success" id="rescheduleBtn" disabled>
                            <i class="fas fa-clock"></i> Reschedule Appointment
                        </button>
                    </form>
                </div>
            </div>
        </div>

@endsection

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
                
                // Enable reschedule button
                document.getElementById('rescheduleBtn').disabled = false;
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
        document.getElementById('rescheduleBtn').disabled = true;
        
        // Validate inputs
        if (!dateInput) {
            return;
        }

        // Show loading message
        loadingMsg.style.display = 'block';

        // Fetch available slots
        fetch('{{ route("staff.appointment.available-slots") }}', {
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
            console.log('Data received:', data);
            loadingMsg.style.display = 'none';

            if (data.unavailable) {
                // Doctor is completely unavailable on this date
                unavailableMsg.textContent = '❌ ' + data.reason;
                unavailableMsg.style.display = 'block';
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

            // Show break if there are afternoon slots
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

    // Load slots on page load if date is already set
    window.addEventListener('load', function() {
        const dateInput = document.getElementById('appointment_date').value;
        if (dateInput) {
            loadAvailableTimeSlots();
        }
    });
</script>
