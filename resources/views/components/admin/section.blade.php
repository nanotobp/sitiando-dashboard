<div class="section-block">
    <h2 class="subtitle">{{ $title }}</h2>

    @if(isset($text))
        <p class="subtitle-text">{{ $text }}</p>
    @endif

    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
