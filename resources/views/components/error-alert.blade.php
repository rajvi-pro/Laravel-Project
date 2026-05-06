@props(['title' => 'Error', 'message' => '', 'type' => 'danger', 'dismissible' => true])

@if($message || $slot->isNotEmpty())
    <div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert" style="border-left: 4px solid {{ $type === 'danger' ? '#dc3545' : ($type === 'warning' ? '#ffc107' : '#17a2b8') }};">
        @if($title)
            <strong>{{ $title }}:</strong>
        @endif
        {{ $message ?? $slot }}
        @if($dismissible)
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        @endif
    </div>
@endif
