@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/frontend/papers.css') }}">
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Knowledge &amp; Research</div>
        <h1 class="page-title">Papers &amp; <em>Presentations</em></h1>
        <p class="page-subtitle">A comprehensive collection of insights, methodologies, and findings from our extensive engagements in Agile hardware and software development.</p>
    </div>
</section>

{{-- DYNAMIC FILTER MENU --}}
<div class="filter-container reveal rv1">
    <div class="filter-menu">
        <button class="filter-btn active" data-filter="all">All Documents</button>
        @foreach($categories as $cat)
            <button class="filter-btn" data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
        @endforeach
    </div>
</div>

<section class="content-section">
    <div class="papers-grid" id="papers-container">

        {{-- DYNAMIC CARDS --}}
        @foreach($papers as $paper)
            <div class="paper-card" data-category="{{ $paper->category?->slug ?? 'uncategorized' }}">
                <div class="paper-meta">
                    <span class="paper-category-tag">{{ $paper->category?->name ?? 'Document' }}</span>
                    {{ $paper->sub_category }}
                </div>
                <h2 class="paper-title">{{ $paper->title }}</h2>
                <div class="paper-desc">
                    {{ $paper->description }}
                </div>
                
                @if($paper->file_path)
                <a href="{{ $paper->file_url }}" target="_blank" download class="download-btn">
                    Download PDF
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                </a>
                @else
                <a href="#" class="download-btn" style="opacity: 0.5; pointer-events: none;">
                    File Pending
                </a>
                @endif
            </div>
        @endforeach

        <div class="no-results" id="no-results-msg" style="display: none; grid-column: 1 / -1; text-align: center; padding: 4rem; background: var(--white); border: 1px dashed var(--ivory3);">
            <h3>No documents found.</h3>
            <p style="color: var(--muted);">Please try selecting a different category.</p>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
// Vanilla JS Filtering Logic (Hooked to Dynamic Categories)
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.paper-card');
    const noResultsMsg = document.getElementById('no-results-msg');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');
            let visibleCount = 0;

            // Filter cards
            cards.forEach(card => {
                card.style.animation = 'none';
                card.offsetHeight; /* trigger reflow */
                card.style.animation = null; 

                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show 'no results' message if empty
            noResultsMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    });
});
</script>
@endpush