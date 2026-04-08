@if ($paginator->hasPages())
<nav class="admin-pagination" aria-label="Pagination">

    {{-- Results info --}}
    <span class="admin-pagination__info">
        Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </span>

    <div class="admin-pagination__controls">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="admin-pagination__btn admin-pagination__btn--disabled" aria-disabled="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
        @else
            <button wire:click="previousPage('{{ $paginator->getPageName() }}')" class="admin-pagination__btn" aria-label="Previous page">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="admin-pagination__dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="admin-pagination__btn admin-pagination__btn--active" aria-current="page">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="admin-pagination__btn" aria-label="Go to page {{ $page }}">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage('{{ $paginator->getPageName() }}')" class="admin-pagination__btn" aria-label="Next page">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        @else
            <span class="admin-pagination__btn admin-pagination__btn--disabled" aria-disabled="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
        @endif

    </div>
</nav>

<style>
.admin-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.admin-pagination__info {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--muted);
    letter-spacing: 0.01em;
}

.admin-pagination__controls {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.admin-pagination__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.5rem;
    border-radius: 8px;
    border: 1px solid var(--ivory3);
    background: var(--white);
    color: var(--slate);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.18s ease;
    line-height: 1;
    font-family: inherit;
}

.admin-pagination__btn:hover:not(.admin-pagination__btn--disabled):not(.admin-pagination__btn--active) {
    background: var(--ivory);
    border-color: var(--copper3);
    color: var(--copper);
}

.admin-pagination__btn--active {
    background: var(--copper);
    border-color: var(--copper);
    color: var(--white);
    cursor: default;
    box-shadow: 0 2px 8px rgba(181, 114, 42, 0.3);
}

.admin-pagination__btn--disabled {
    opacity: 0.38;
    cursor: default;
    background: var(--ivory);
}

.admin-pagination__dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: 0.05em;
}
</style>
@endif
