<!-- Button Component -->
<button type="{{ $type ?? 'button' }}" class="btn btn-{{ $color ?? 'primary' }} {{ $class ?? '' }}" {{ $attributes }}>
    {{ $slot }}
</button>
