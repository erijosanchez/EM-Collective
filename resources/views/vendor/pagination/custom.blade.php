@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-1" role="navigation">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
    <span class="px-3 py-2 text-stone/40 text-xs cursor-not-allowed select-none">←</span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-stone hover:text-carbon text-xs transition-colors">←</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
        <span class="px-3 py-2 text-stone text-xs">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <span class="px-3 py-2 bg-carbon text-cream text-xs">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="px-3 py-2 text-stone hover:text-carbon border border-transparent hover:border-stone/30 text-xs transition-colors">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-stone hover:text-carbon text-xs transition-colors">→</a>
    @else
    <span class="px-3 py-2 text-stone/40 text-xs cursor-not-allowed select-none">→</span>
    @endif
</nav>
@endif
