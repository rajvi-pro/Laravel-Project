@extends('layouts.doctor-layout')

@section('title', 'Create Schedule')

@section('content')
<style>
    .create-schedule-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .page-header-create {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .page-header-create h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .btn-back-create {
        background: #e0e0e0;
        color: #333;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-back-create:hover {
        background: #d0d0d0;
        transform: translateY(-1px);
    }

    .form-card-create {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #0f61cc;
    }

    .alert-create {
        padding: 15px 20px;
        margin-bottom: 30px;
        border-radius: 6px;
        border-left: 4px solid;
    }

    .alert-error-create {
        background: #ffebee;
        color: #c62828;
        border-left-color: #e53935;
    }

    .alert-error-create ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }

    .alert-error-create li {
        margin: 5px 0;
        font-size: 13px;
    }

    .section-title-create {
        font-size: 13px;
        font-weight: 700;
        color: #0f61cc;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 30px 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-title-create:first-child {
        margin-top: 0;
    }

    .form-group-create {
        margin-bottom: 25px;
    }

    .form-group-create label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group-create label .required {
        color: #e53935;
        font-weight: 700;
    }

    .form-row-create {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .form-row-create.full {
        grid-template-columns: 1fr;
    }

    .time-input-wrapper-create {
        position: relative;
    }

    .form-group-create input[type="time"],
    .form-group-create select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Courier New', monospace;
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
        color: #1a1a1a;
    }

    .form-group-create input[type="time"]:focus,
    .form-group-create select:focus {
        outline: none;
        border-color: #0f61cc;
        box-shadow: 0 0 0 3px rgba(15, 97, 204, 0.1);
        background: #f9fbfd;
    }

    .time-format-hint-create {
        font-size: 12px;
        color: #999;
        margin-top: 6px;
        font-style: italic;
    }

    .checkbox-group-create {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 4px solid #0f61cc;
    }

    .checkbox-group-create input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        cursor: pointer;
        accent-color: #0f61cc;
    }

    .checkbox-group-create label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: #333;
        font-size: 14px;
    }

    .form-actions-create {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit-create {
        background: #0f61cc;
        color: white;
        padding: 12px 32px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit-create:hover {
        background: #0a4aa8;
        box-shadow: 0 4px 12px rgba(15, 97, 204, 0.3);
        transform: translateY(-2px);
    }

    .btn-cancel-create {
        background: #e0e0e0;
        color: #333;
        padding: 12px 32px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-cancel-create:hover {
        background: #d0d0d0;
        transform: translateY(-2px);
    }

    .info-box-create {
        background: #e8f5e9;
        border-left: 4px solid #26a69a;
        padding: 15px;
        border-radius: 6px;
        color: #1b5e20;
        margin-bottom: 25px;
        font-size: 13px;
    }

    .info-box-create strong {
        font-weight: 700;
    }

    .time-slot-summary-create {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        border-left: 4px solid #26a69a;
    }

    .time-slot-summary-title-create {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .time-slot-display-create {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        font-family: 'Courier New', monospace;
    }

    @media (max-width: 600px) {
        .form-row-create {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-card-create {
            padding: 20px;
        }

        .form-actions-create {
            flex-direction: column-reverse;
        }

        .btn-submit-create,
        .btn-cancel-create {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="create-schedule-container">
    <div class="page-header-create">
        <h2>➕ Create New Schedule</h2>
        <a href="{{ route('doctor.schedule-list') }}" class="btn-back-create">← Back to Schedules</a>
    </div>

    @if ($errors->any())
        <div class="alert-create alert-error-create">
            <strong>⚠️ Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card-create">
        <div class="info-box-create">
            ✓ <strong>Create a new schedule:</strong> Define your working hours for a specific day. Patients and staff will only see available slots based on your schedule.
        </div>

        <form method="POST" action="{{ route('doctor.schedule-store') }}" id="scheduleFormCreate">
            @csrf

            <!-- Schedule Details Section -->
            <div class="section-title-create">📅 Schedule Details</div>

            <div class="form-group-create">
                <label for="day_of_week">
                    Day of Week <span class="required">*</span>
                </label>
                <select id="day_of_week" name="day_of_week" required>
                    <option value="">-- Select a day --</option>
                    @foreach ($days as $day)
                        <option value="{{ $day }}" {{ old('day_of_week') === $day ? 'selected' : '' }}>
                            {{ ucfirst($day) }}
                        </option>
                    @endforeach
                </select>
                <div class="time-format-hint-create">Select the day of the week this schedule applies to</div>
            </div>

            <!-- Working Hours Section -->
            <div class="section-title-create">⏰ Working Hours</div>

            <div class="form-row-create">
                <div class="form-group-create">
                    <label for="start_time">
                        Start Time <span class="required">*</span>
                    </label>
                    <div class="time-input-wrapper-create">
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time') }}" 
                               required
                               onchange="updateTimeSlotSummary()">
                        <div class="time-format-hint-create">24-hour format (e.g., 09:00)</div>
                    </div>
                </div>

                <div class="form-group-create">
                    <label for="end_time">
                        End Time <span class="required">*</span>
                    </label>
                    <div class="time-input-wrapper-create">
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time') }}" 
                               required
                               onchange="updateTimeSlotSummary()">
                        <div class="time-format-hint-create">Must be after start time</div>
                    </div>
                </div>
            </div>

            <div class="time-slot-summary-create" id="timeSlotSummaryCreate" style="display: none;">
                <div class="time-slot-summary-title-create">Your Working Hours Preview</div>
                <div class="time-slot-display-create" id="timeSlotDisplayCreate"></div>
            </div>

            <!-- Break Time Section -->
            <div class="section-title-create">☕ Break Time (Optional)</div>

            <div class="form-row-create">
                <div class="form-group-create">
                    <label for="break_start">
                        Break Start Time
                    </label>
                    <div class="time-input-wrapper-create">
                        <input type="time" id="break_start" name="break_start" 
                               value="{{ old('break_start') }}"
                               onchange="updateTimeSlotSummary()">
                        <div class="time-format-hint-create">e.g., 12:00 (optional)</div>
                    </div>
                </div>

                <div class="form-group-create">
                    <label for="break_end">
                        Break End Time
                    </label>
                    <div class="time-input-wrapper-create">
                        <input type="time" id="break_end" name="break_end" 
                               value="{{ old('break_end') }}"
                               onchange="updateTimeSlotSummary()">
                        <div class="time-format-hint-create">e.g., 13:00 (optional)</div>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="section-title-create">✅ Availability Status</div>

            <div class="checkbox-group-create">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                <label for="is_active">
                    This schedule is <strong>active</strong> and available for patient appointments
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions-create">
                <a href="{{ route('doctor.schedule-list') }}" class="btn-cancel-create">
                    ✕ Cancel
                </a>
                <button type="submit" class="btn-submit-create">
                    ✓ Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateTimeSlotSummary() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const breakStart = document.getElementById('break_start').value;
        const breakEnd = document.getElementById('break_end').value;

        if (startTime && endTime) {
            const summary = document.getElementById('timeSlotSummaryCreate');
            const display = document.getElementById('timeSlotDisplayCreate');
            
            let timeText = `${formatTime(startTime)} → ${formatTime(endTime)}`;
            
            if (breakStart && breakEnd) {
                timeText += ` (Break: ${formatTime(breakStart)} - ${formatTime(breakEnd)})`;
            }
            
            display.textContent = timeText;
            summary.style.display = 'block';
        } else {
            document.getElementById('timeSlotSummaryCreate').style.display = 'none';
        }
    }

    function formatTime(time) {
        const [hours, minutes] = time.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTimeSlotSummary();
    });
</script>
@endsection
