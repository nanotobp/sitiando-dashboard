<div class="card kpi-card shadow-sm border-0 rounded-xl bg-[var(--bg-card)]">
    <div class="kpi-header">
        <h3>{{ $label }}</h3>

        <p class="kpi-number">{{ $value }}</p>

        @if(isset($change))
            <span class="kpi-change {{ $change >= 0 ? 'positive' : 'negative' }}">
                {{ $change }}%
            </span>
        @endif
    </div>

    @if(isset($sparkId))
        <div class="kpi-sparkline">
            <canvas id="{{ $sparkId }}"></canvas>
        </div>
    @endif
</div>
