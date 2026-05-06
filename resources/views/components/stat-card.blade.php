<!-- Stat Card Component -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <p class="card-text display-4">{{ $value }}</p>
        @if($description ?? null)
            <small class="text-muted">{{ $description }}</small>
        @endif
    </div>
</div>
