@php
    // Group slots by period
    $morning = [];
    $afternoon = [];
    $evening = [];

    foreach($slots as $slot) {
        $hour = intval(substr($slot['time'], 0, 2));
        if ($hour < 12) {
            $morning[] = $slot;
        } elseif ($hour < 17) {
            $afternoon[] = $slot;
        } else {
            $evening[] = $slot;
        }
    }
@endphp

<div class="slots-grouped">
    @if(count($morning) > 0)
        <div class="slot-period">
            <div class="slot-period-title">
                ☀️ Morning
            </div>
            <div class="slot-period-grid">
                @foreach($morning as $slot)
                    <label class="slot-option">
                        <input type="radio" name="appointment_time" value="{{ $slot['time'] }}" required>
                        <span class="slot-label">{{ $slot['display'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($afternoon) > 0)
        <div class="slot-period">
            <div class="slot-period-title">
                🌤️ Afternoon
            </div>
            <div class="slot-period-grid">
                @foreach($afternoon as $slot)
                    <label class="slot-option">
                        <input type="radio" name="appointment_time" value="{{ $slot['time'] }}" required>
                        <span class="slot-label">{{ $slot['display'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($evening) > 0)
        <div class="slot-period">
            <div class="slot-period-title">
                🌙 Evening
            </div>
            <div class="slot-period-grid">
                @foreach($evening as $slot)
                    <label class="slot-option">
                        <input type="radio" name="appointment_time" value="{{ $slot['time'] }}" required>
                        <span class="slot-label">{{ $slot['display'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif
</div>
