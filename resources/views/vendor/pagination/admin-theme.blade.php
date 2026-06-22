@if ($paginator->hasPages())
@php($manyPages = $paginator->lastPage() > 7)
<nav class="admin-pagination {{ $manyPages ? 'admin-pagination--many' : '' }}" aria-label="Pagination">

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

        {{-- Page numbers (full list — hidden on very small screens) --}}
        <span class="admin-pagination__pages">
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
        </span>

        {{-- Compact indicator — shown instead of the numbers on very small screens --}}
        <span class="admin-pagination__current">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

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

        {{-- Jump-to-page — only worth showing once there are many pages --}}
        @if ($manyPages)
            <form class="admin-pagination__jump"
                  x-data="{ p: '{{ $paginator->currentPage() }}', max: {{ $paginator->lastPage() }} }"
                  @submit.prevent="$wire.gotoPage(Math.min(max, Math.max(1, parseInt(p) || 1)), '{{ $paginator->getPageName() }}')">
                <label class="admin-pagination__jump-label" for="jump-{{ $paginator->getPageName() }}">Go to</label>
                <input id="jump-{{ $paginator->getPageName() }}" type="number" min="1" max="{{ $paginator->lastPage() }}"
                       x-model="p" class="admin-pagination__jump-input" aria-label="Jump to page number"
                       @keydown.enter.stop.prevent="$wire.gotoPage(Math.min(max, Math.max(1, parseInt(p) || 1)), '{{ $paginator->getPageName() }}')">
                <span class="admin-pagination__jump-total">/ {{ $paginator->lastPage() }}</span>
                <button type="submit" class="admin-pagination__btn admin-pagination__jump-go">Go</button>
            </form>
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

.admin-pagination__pages {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    flex-wrap: wrap;
    justify-content: center;
}

.admin-pagination__current {
    display: none; /* desktop: numbers are shown instead */
    align-items: center;
    padding: 0 0.6rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--slate);
    white-space: nowrap;
}

.admin-pagination__jump {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-left: 0.5rem;
    padding-left: 0.65rem;
    border-left: 1px solid var(--ivory3);
}

.admin-pagination__jump-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--muted);
    white-space: nowrap;
}

.admin-pagination__jump-input {
    width: 58px;
    height: 36px;
    padding: 0 0.4rem;
    text-align: center;
    border: 1px solid var(--ivory3);
    border-radius: 8px;
    background: var(--white);
    color: var(--slate);
    font-size: 0.85rem;
    font-weight: 600;
    font-family: inherit;
    outline: none;
    transition: border-color 0.18s;
    /* hide native number spinners for a cleaner look */
    -moz-appearance: textfield;
}
.admin-pagination__jump-input::-webkit-outer-spin-button,
.admin-pagination__jump-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.admin-pagination__jump-input:focus { border-color: var(--copper); }

.admin-pagination__jump-total {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--muted);
    white-space: nowrap;
}

.admin-pagination__jump-go { cursor: pointer; }

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

/* ── Responsive: stack and center instead of dropping controls to the left ── */
@media (max-width: 640px) {
    .admin-pagination {
        flex-direction: column;
        justify-content: center;
        text-align: center;
        gap: 0.85rem;
    }
    .admin-pagination__controls {
        justify-content: center;
        width: 100%;
        flex-wrap: wrap;
    }
    /* Let the jump box sit on its own centered line under the arrows */
    .admin-pagination__jump {
        margin-left: 0;
        padding-left: 0;
        border-left: none;
        width: 100%;
        justify-content: center;
    }
}

/* ── Small screens: only collapse into a compact "Page X of Y" indicator when
      there are MANY pages. Short pagers (≤7 pages) keep their numbers — they
      fit fine on a phone, so "Page 1 of 6" would just hide useful controls. ── */
@media (max-width: 560px) {
    .admin-pagination--many .admin-pagination__pages { display: none; }
    .admin-pagination--many .admin-pagination__current { display: inline-flex; }
}

/* Tiny screens: shrink the buttons so short pagers still fit on one line */
@media (max-width: 400px) {
    .admin-pagination__btn,
    .admin-pagination__dots {
        min-width: 30px;
        height: 30px;
        font-size: 0.78rem;
        padding: 0 0.25rem;
    }
}
</style>
@endif
