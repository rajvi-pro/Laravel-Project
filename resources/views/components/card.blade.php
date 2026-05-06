<!-- Card Component -->
<div class="card {{ $class ?? '' }}">
    <div class="card-header">
        <h5 class="mb-0">{{ $title }}</h5>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
