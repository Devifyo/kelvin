@if ($paginator->hasPages())
<nav class="site-pagination" role="navigation" aria-label="Pagination Navigation">

    <div class="site-pagination__controls">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="site-pagination__btn site-pagination__btn--disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="site-pagination__btn" aria-label="@lang('pagination.previous')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="site-pagination__dots" aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="site-pagination__btn site-pagination__btn--active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="site-pagination__btn" aria-label="@lang('Go to page :page', ['page' => $page])">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="site-pagination__btn" aria-label="@lang('pagination.next')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        @else
            <span class="site-pagination__btn site-pagination__btn--disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif

    </div>

    {{-- Results info --}}
    <span class="site-pagination__info">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </span>
</nav>

<style>
.site-pagination {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
}
.site-pagination__controls {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-wrap: wrap;
    justify-content: center;
}
.site-pagination__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 0 0.6rem;
    border-radius: 6px;
    border: 1px solid var(--ivory3);
    background: var(--white);
    color: var(--slate);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    line-height: 1;
    transition: all 0.2s cubic-bezier(.4,0,.2,1);
}
.site-pagination__btn:hover:not(.site-pagination__btn--disabled):not(.site-pagination__btn--active) {
    border-color: var(--copper);
    color: var(--copper);
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(181,114,42,.12);
}
.site-pagination__btn--active {
    background: var(--copper);
    border-color: var(--copper);
    color: var(--white);
    cursor: default;
    box-shadow: 0 4px 14px rgba(181,114,42,.28);
}
.site-pagination__btn--disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background: var(--ivory);
}
.site-pagination__dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: 0.05em;
}
.site-pagination__info {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: var(--muted);
}
@media (max-width: 480px) {
    .site-pagination__btn, .site-pagination__dots { min-width: 38px; height: 38px; }
}
</style>
@endif
