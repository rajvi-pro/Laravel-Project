
@extends('admin-layout')

@section('title', 'Reschedule Appointment')

@section('content')
<style>
    .sidebar { 
        display: none !important; 
    }
    
    .topbar {
        display: none !important;
    }
    
    .main-content { 
        margin-left: 0 !important; 
        min-height: auto !important;
        height: auto !important;
        max-height: none !important;
        padding: 28px !important;
        overflow: visible !important;
        display: block !important;
    }
    
    html, body {
        height: auto !important;
        overflow: auto !important;
    }
</style>

<div class="container-fluid" style="padding: 0; margin: 0;">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545; margin-bottom: 24px;">
            <strong><i class="fas fa-exclamation-circle"></i> Rescheduling Error!</strong>
            <hr style="margin: 12px 0;">
            <ul style="margin-bottom: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom: 8px;">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 style="margin-bottom: 8px; font-size: 32px; font-weight: 700;">Reschedule Appointment</h1>
            <p style="margin: 0; color: #6c757d; font-size: 14px;">Appointment ID: <strong>#{{ $appointment->id }}</strong></p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Header Cards Row -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card card-panel" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);">
                <div class="card-body" style="padding: 24px;">
                    <h6 style="color: #6c757d; font-weight: 600; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px;">
                        <i class="fas fa-info-circle" style="color: #0d6efd;"></i> Current Appointment
                    </h6>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <p style="color: #6c757d; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Patient</p>
                            <p style="font-size: 16px; font-weight: 600; margin: 0; color: #212529;">{{ $appointment->patient->name }}</p>
                        </div>
                        <div>
                            <p style="color: #6c757d; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Doctor</p>
                            <p style="font-size: 16px; font-weight: 600; margin: 0; color: #212529;">{{ $appointment->doctor->name }}</p>
                            <p style="font-size: 13px; color: #6c757d; margin: 4px 0 0 0;">{{ $appointment->doctor->specialization }}</p>
                        </div>
                        <div>
                            <p style="color: #6c757d; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Current Date</p>
                            <p style="font-size: 16px; font-weight: 600; margin: 0; color: #212529;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p style="color: #6c757d; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; font-weight: 600;">Current Time</p>
                            <p style="font-size: 16px; font-weight: 600; margin: 0; color: #212529;">{{ date('h:i A', strtotime($appointment->appointment_time)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-panel" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);">
                <div class="card-body" style="padding: 24px;">
                    <h6 style="color: #6c757d; font-weight: 600; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px;">
                        <i class="fas fa-clock" style="color: #28a745;"></i> Doctor Availability
                    </h6>
                    <div>
                        <p style="color: #6c757d; font-size: 12px; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Working Hours</p>
                        <p style="font-size: 16px; font-weight: 600; margin: 0 0 12px 0; color: #212529;">
                            <i class="fas fa-calendar-check" style="color: #0d6efd; margin-right: 8px;"></i>
                            9:00 AM - 7:00 PM
                        </p>
                        <p style="font-size: 13px; color: #6c757d; margin: 0; padding: 8px 12px; background: #fff3cd; border-left: 3px solid #ffc107; border-radius: 4px;">
                            <i class="fas fa-pause-circle" style="margin-right: 6px;"></i>
                            Break: 12:00 PM - 2:00 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card card-panel" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div class="card-body" style="padding: 40px;">
            <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}">
                @csrf
                @method('PUT')

                <!-- Date Selection -->
                <div class="form-section mb-5">
                    <h6 style="color: #212529; font-weight: 700; font-size: 16px; margin-bottom: 20px;">
                        <span style="background: #0d6efd; color: white; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; margin-right: 10px;">1</span>
                        Select New Date
                    </h6>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                        <input type="date" name="appointment_date" id="appointmentDate" class="form-control" 
                               value="{{ old('appointment_date', $appointment->appointment_date) }}" required
                               style="font-size: 16px; padding: 12px 16px; border: 2px solid #dee2e6;">
                        <p style="color: #6c757d; font-size: 13px; margin-top: 8px; margin-bottom: 0;">
                            <i class="fas fa-info-circle"></i> Select the new date for the appointment
                        </p>
                    </div>
                </div>

                <!-- Time Slot Selection -->
                <div class="form-section mb-5">
                    <h6 style="color: #212529; font-weight: 700; font-size: 16px; margin-bottom: 20px;">
                        <span style="background: #0d6efd; color: white; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; margin-right: 10px;">2</span>
                        Select Available Time Slot
                    </h6>
                    <div class="available-slots">
                        @if(count($availableSlots) > 0)
                            <div id="slotContainer">
                                @include('admin.appointments._slot_grid', ['slots' => $availableSlots])
                            </div>
                        @else
                            <div class="alert alert-warning" style="border-left: 4px solid #ffc107; background: #fff8e1;">
                                <i class="fas fa-calendar-times"></i> No available slots for this date. Please select a different date.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-section mb-5">
                    <h6 style="color: #212529; font-weight: 700; font-size: 16px; margin-bottom: 20px;">
                        <span style="background: #6c757d; color: white; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; margin-right: 10px;">3</span>
                        Additional Notes (Optional)
                    </h6>
                    <textarea class="form-control" name="notes" rows="4" 
                              style="font-size: 14px; padding: 12px 16px; border: 2px solid #dee2e6;"
                              placeholder="Add any notes about this rescheduling...">{{ old('notes', $appointment->notes) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 12px; margin-top: 40px; padding-top: 24px; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; font-weight: 600; padding: 14px 24px;">
                        <i class="fas fa-save"></i> Save New Schedule
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-secondary btn-lg" style="flex: 1; font-weight: 600; padding: 14px 24px;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<style>
    .form-section {
        position: relative;
    }

    .slot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        padding: 0;
    }

    .slot-option {
        margin: 0;
        position: relative;
    }

    .slot-option input[type="radio"] {
        display: none;
    }

    .slot-label {
        display: block;
        padding: 16px 14px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
        font-weight: 500;
        color: #495057;
        font-size: 14px;
        line-height: 1.4;
    }

    .slot-label:hover {
        border-color: #0d6efd;
        background: #f0f6ff;
        transform: translateY(-2px);
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15);
    }

    .slot-option input[type="radio"]:checked + .slot-label {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        font-weight: 600;
    }

    .slot-period {
        margin-bottom: 24px;
    }

    .slot-period-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }

    .slot-period-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .card-panel {
        border-radius: 12px;
    }

    .btn-lg {
        border-radius: 8px;
        font-size: 15px;
    }
</style>

<script>
    @if(session('reschedule_redirect'))
        setTimeout(function() {
            window.location.href = '{{ route('admin.dashboard') }}';
        }, 1800);
    @endif

    document.getElementById('appointmentDate').addEventListener('change', function() {
        const date = this.value;
        const doctorId = '{{ $appointment->doctor_id }}';
        
        if (!date) return;

        // Fetch available slots via AJAX
        fetch(`/admin/appointments-slots/${doctorId}/${date}`)
            .then(response => response.json())
            .then(data => {
                const slotContainer = document.getElementById('slotContainer');
                
                if (data.success && data.slots.length > 0) {
                    let html = renderSlots(data.slots);
                    slotContainer.innerHTML = html;
                } else {
                    slotContainer.innerHTML = '<div class="alert alert-warning" style="border-left: 4px solid #ffc107; background: #fff8e1;"><i class="fas fa-calendar-times"></i> No available slots for this date. Please select a different date.</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                document.getElementById('slotContainer').innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error loading available slots</div>';
            });
    });

    function renderSlots(slots) {
        // Group slots by period
        const grouped = {
            'Morning': [],
            'Afternoon': [],
            'Evening': []
        };

        slots.forEach(slot => {
            const hour = parseInt(slot.time.split(':')[0]);
            if (hour < 12) grouped['Morning'].push(slot);
            else if (hour < 17) grouped['Afternoon'].push(slot);
            else grouped['Evening'].push(slot);
        });

        let html = '';
        Object.entries(grouped).forEach(([period, periodSlots]) => {
            if (periodSlots.length > 0) {
                html += `
                    <div class="slot-period">
                        <div class="slot-period-title">
                            ${period === 'Morning' ? '☀️' : period === 'Afternoon' ? '🌤️' : '🌙'} ${period}
                        </div>
                        <div class="slot-period-grid">
                `;
                periodSlots.forEach(slot => {
                    html += `
                        <label class="slot-option">
                            <input type="radio" name="appointment_time" value="${slot.time}" required>
                            <span class="slot-label">${slot.display}</span>
                        </label>
                    `;
                });
                html += `
                        </div>
                    </div>
                `;
            }
        });

        return html;
    }
</script>
