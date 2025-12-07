<div {{ $attributes->merge(['class' => 'card shadow-sm border-0 rounded-xl bg-[var(--bg-card)]']) }}>
    @if(isset($title))
        <div class="px-4 pt-4 pb-2 border-b border-[var(--border-subtle)]">
            <h3 class="text-[13px] uppercase tracking-wide text-[var(--text-muted)] font-semibold">
                {{ $title }}
            </h3>
        </div>
    @endif

    <div class="p-4">
        {{ $slot }}
    </div>
</div>
