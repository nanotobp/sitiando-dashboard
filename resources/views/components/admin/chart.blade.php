<div class="card chart-card shadow-sm border-0 rounded-xl bg-[var(--bg-card)]">
    @if(isset($title))
        <h3 class="mb-3">{{ $title }}</h3>
    @endif

    <canvas id="{{ $id }}"></canvas>
</div>
