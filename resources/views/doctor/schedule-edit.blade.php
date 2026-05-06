@extends('layouts.doctor-layout')

@section('title', 'Edit Schedule')

@section('content')
<style>
    .edit-schedule-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .page-header-edit {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .page-header-edit h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .btn-back-edit {
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

    .btn-back-edit:hover {
        background: #d0d0d0;
        transform: translateY(-1px);
    }

    .form-card-edit {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #0f61cc;
    }

    .alert-edit {
        padding: 15px 20px;
        margin-bottom: 30px;
        border-radius: 6px;
        border-left: 4px solid;
    }

    .alert-error-edit {
        background: #ffebee;
        color: #c62828;
        border-left-color: #e53935;
    }

    .alert-error-edit ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }

    .alert-error-edit li {
        margin: 5px 0;
        font-size: 13px;
    }

    .section-title-edit {
        font-size: 13px;
        font-weight: 700;
        color: #0f61cc;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 30px 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .section-title-edit:first-child {
        margin-top: 0;
    }

    .form-group-edit {
        margin-bottom: 25px;
    }

    .form-group-edit label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group-edit label .required {
        color: #e53935;
        font-weight: 700;
    }

    .form-row-edit {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .form-row-edit.full {
        grid-template-columns: 1fr;
    }

    .time-input-wrapper {
        position: relative;
    }

    .form-group-edit input[type="time"],
    .form-group-edit select {
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

    .form-group-edit input[type="time"]:focus,
    .form-group-edit select:focus {
        outline: none;
        border-color: #0f61cc;
        box-shadow: 0 0 0 3px rgba(15, 97, 204, 0.1);
        background: #f9fbfd;
    }

    .form-group-edit input[type="time"]::placeholder {
        color: #999;
    }

    .time-format-hint {
        font-size: 12px;
        color: #999;
        margin-top: 6px;
        font-style: italic;
    }

    .time-preview {
        display: inline-block;
        background: #e3f2fd;
        color: #0f61cc;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
    }

    .checkbox-group-edit {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 4px solid #0f61cc;
    }

    .checkbox-group-edit input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        cursor: pointer;
        accent-color: #0f61cc;
    }

    .checkbox-group-edit label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: #333;
        font-size: 14px;
    }

    .form-actions-edit {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-submit-edit {
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

    .btn-submit-edit:hover {
        background: #0a4aa8;
        box-shadow: 0 4px 12px rgba(15, 97, 204, 0.3);
        transform: translateY(-2px);
    }

    .btn-cancel-edit {
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

    .btn-cancel-edit:hover {
        background: #d0d0d0;
        transform: translateY(-2px);
    }

    .info-box-edit {
        background: #e3f2fd;
        border-left: 4px solid #0f61cc;
        padding: 15px;
        border-radius: 6px;
        color: #0f61cc;
        margin-bottom: 25px;
        font-size: 13px;
    }

    .info-box-edit strong {
        font-weight: 700;
    }

    .day-display {
        display: inline-block;
        background: #f0f4ff;
        color: #0f61cc;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        margin-top: 6px;
    }

    .time-slot-summary {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        border-left: 4px solid #26a69a;
    }

    .time-slot-summary-title {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .time-slot-display {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        font-family: 'Courier New', monospace;
    }

    @media (max-width: 600px) {
        .form-row-edit {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-card-edit {
            padding: 20px;
        }

        .form-actions-edit {
            flex-direction: column-reverse;
        }

        .btn-submit-edit,
        .btn-cancel-edit {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-schedule-container">
    <div class="page-header-edit">
        <h2>📝 Edit Schedule</h2>
        <a href="{{ route('doctor.schedule-list') }}" class="btn-back-edit">← Back to Schedules</a>
    </div>

    @if ($errors->any())
        <div class="alert-edit alert-error-edit">
            <strong>⚠️ Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card-edit">
        <div class="info-box-edit">
            💡 <strong>Tip:</strong> Set your working hours for this day. Break times are optional. Only this schedule can be active per day.
        </div>

        <form method="POST" action="{{ route('doctor.schedule-update', $schedule->id) }}" id="scheduleForm">
            @csrf
            @method('PUT')

            <!-- Schedule Details Section -->
            <div class="section-title-edit">📅 Schedule Details</div>

            <div class="form-group-edit">
                <label for="day_of_week">
                    Day of Week <span class="required">*</span>
                </label>
                <select id="day_of_week" name="day_of_week" required onchange="updateDayDisplay()">
                    <option value="">-- Select a day --</option>
                    @foreach ($days as $day)
                        <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) === $day ? 'selected' : '' }}>
                            {{ ucfirst($day) }}
                        </option>
                    @endforeach
                </select>
                <div class="day-display" id="dayDisplay">
                    {{ ucfirst($schedule->day_of_week ?? 'Select a day') }}
                </div>
            </div>

            <!-- Working Hours Section -->
            <div class="section-title-edit">⏰ Working Hours</div>

            <div class="form-row-edit">
                <div class="form-group-edit">
                    <label for="start_time">
                        Start Time <span class="required">*</span>
                    </label>
                    <div class="time-input-wrapper">
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time', $schedule->start_time) }}" 
                               required
                               onchange="updateTimeSlotDisplay()">
                        <div class="time-format-hint">24-hour format (e.g., 09:00)</div>
                    </div>
                </div>

                <div class="form-group-edit">
                    <label for="end_time">
                        End Time <span class="required">*</span>
                    </label>
                    <div class="time-input-wrapper">
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time', $schedule->end_time) }}" 
                               required
                               onchange="updateTimeSlotDisplay()">
                        <div class="time-format-hint">Must be after start time</div>
                    </div>
                </div>
            </div>

            <div class="time-slot-summary" id="timeSlotSummary" style="display: none;">
                <div class="time-slot-summary-title">Your Working Hours</div>
                <div class="time-slot-display" id="timeSlotDisplay"></div>
            </div>

            <!-- Break Time Section -->
            <div class="section-title-edit">☕ Break Time (Optional)</div>

            <div class="form-row-edit">
                <div class="form-group-edit">
                    <label for="break_start">
                        Break Start Time
                    </label>
                    <div class="time-input-wrapper">
                        <input type="time" id="break_start" name="break_start" 
                               value="{{ old('break_start', $schedule->break_start) }}"
                               onchange="updateTimeSlotDisplay()">
                        <div class="time-format-hint">e.g., 12:00 (leave empty for no break)</div>
                    </div>
                </div>

                <div class="form-group-edit">
                    <label for="break_end">
                        Break End Time
                    </label>
                    <div class="time-input-wrapper">
                        <input type="time" id="break_end" name="break_end" 
                               value="{{ old('break_end', $schedule->break_end) }}"
                               onchange="updateTimeSlotDisplay()">
                        <div class="time-format-hint">e.g., 13:00 (leave empty for no break)</div>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="section-title-edit">✅ Availability Status</div>

            <div class="checkbox-group-edit">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                <label for="is_active">
                    This schedule is <strong>active</strong> and available for patient appointments
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions-edit">
                <a href="{{ route('doctor.schedule-list') }}" class="btn-cancel-edit">
                    ✕ Cancel
                </a>
                <button type="submit" class="btn-submit-edit">
                    ✓ Update Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDayDisplay() {
        const daySelect = document.getElementById('day_of_week');
        const dayDisplay = document.getElementById('dayDisplay');
        if (daySelect.value) {
            dayDisplay.textContent = daySelect.options[daySelect.selectedIndex].text;
        }
    }

    function updateTimeSlotDisplay() {
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        const breakStart = document.getElementById('break_start').value;
        const breakEnd = document.getElementById('break_end').value;

        if (startTime && endTime) {
            const summary = document.getElementById('timeSlotSummary');
            const display = document.getElementById('timeSlotDisplay');
            
            let timeText = `${formatTime(startTime)} → ${formatTime(endTime)}`;
            
            if (breakStart && breakEnd) {
                timeText += ` (Break: ${formatTime(breakStart)} - ${formatTime(breakEnd)})`;
            }
            
            display.textContent = timeText;
            summary.style.display = 'block';
        } else {
            document.getElementById('timeSlotSummary').style.display = 'none';
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
        updateDayDisplay();
        updateTimeSlotDisplay();
    });
</script>
@endsection
