@extends('layouts.app')

@section('title', 'Agile Hardware Resource Library: Papers, Case Studies & Presentations | ' . config('app.name'))
@section('meta_description', 'Resource Library of white papers, case studies, conference presentations, and books on Agile hardware development and Scrum at scale by Dr. Kevin Thompson, Ph.D.')
@section('meta_keywords', 'agile hardware research, scrum case studies, embedded systems agile, agile transformation papers, agile hardware white papers, Kevin Thompson')
@section('og_type', 'website')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/frontend/papers.css') }}">
<style>
    /* Ensure the anchor tags behave exactly like the buttons did */
    .filter-btn {
        text-decoration: none;
        display: inline-block;
    }
</style>
@endpush

@section('content')

<x-page-header page="papers" />

{{-- DYNAMIC SERVER-SIDE FILTER MENU — Featured is the default landing view --}}
<div class="filter-container reveal rv1">
    <nav class="filter-menu" aria-label="Resource categories">
        @if($hasFeatured)
            <a href="{{ route('papers') }}"
               class="filter-btn {{ $currentFilter === 'featured' ? 'active' : '' }}"
               @if($currentFilter === 'featured') aria-current="page" @endif>
               Featured
            </a>
        @endif

        <a href="{{ route('papers', ['category' => 'all']) }}"
           class="filter-btn {{ $currentFilter === 'all' ? 'active' : '' }}"
           @if($currentFilter === 'all') aria-current="page" @endif>
           All Documents
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('papers', ['category' => $cat->slug]) }}"
               class="filter-btn {{ $currentFilter === $cat->slug ? 'active' : '' }}"
               @if($currentFilter === $cat->slug) aria-current="page" @endif>
               {{ $cat->name }}
            </a>
        @endforeach
    </nav>
</div>

<section class="content-section">
    <div class="papers-grid" id="papers-container">

        {{-- DYNAMIC CARDS — each card links to the resource's own page, never straight to the file --}}
        @forelse($papers as $paper)
            <article class="paper-card {{ $paper->is_featured ? 'is-featured' : '' }}">
                <div class="paper-meta">
                    <span class="paper-category-tag">{{ $paper->category?->name ?? 'Document' }}</span>
                    {{ $paper->sub_category }}
                    @if($paper->is_featured)
                        <span class="paper-featured-badge" title="Featured resource">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Featured
                        </span>
                    @endif
                </div>
                <h2 class="paper-title">
                    <a href="{{ route('papers.show', $paper->slug) }}" class="paper-title-link">{{ $paper->title }}</a>
                </h2>

                {{-- Unescaped output so TinyMCE HTML renders correctly --}}
                <div class="paper-desc">
                    {!! $paper->description !!}
                </div>

                <a href="{{ route('papers.show', $paper->slug) }}" class="download-btn" aria-label="Open the resource page for {{ $paper->title }}">
                    <span>{{ $paper->is_book ? 'About the Book' : 'View Resource' }}</span>
                    <span class="paper-type-hint">{{ $paper->type_label }}@if($paper->file_size_label) &middot; {{ $paper->file_size_label }}@endif</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </article>
        @empty
            {{-- SERVER-SIDE NO RESULTS MESSAGE --}}
            <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: var(--white); border: 1px dashed var(--ivory3);">
                <h3>No resources found.</h3>
                <p style="color: var(--muted);">Please try selecting a different category.</p>
            </div>
        @endforelse

    </div>
</section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: the Resource Library collection. The ItemList carries EVERY active
     resource (not just the rendered tab) so crawlers discover all resource URLs from here. --}}
@php
    $_papersJsonLd = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => 'Agile Hardware Resource Library — Research Papers, Case Studies & Presentations',
        'description' => 'White papers, case studies, conference presentations, and books on Agile hardware development and Scrum at scale by Dr. Kevin Thompson, Ph.D.',
        'url'         => route('papers'),
        'author'      => ['@id' => url('/') . '/#person'],
        'publisher'   => ['@id' => url('/') . '/#organization'],
        'about'       => ['Agile hardware development', 'Embedded systems', 'Scrum', 'Agile transformation'],
        'inLanguage'  => 'en-US',
        'mainEntity'  => [
            '@type'           => 'ItemList',
            'numberOfItems'   => $allPapers->count(),
            'itemListElement' => $allPapers->values()->map(fn ($p, $i) => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $p->title,
                'url'      => route('papers.show', $p->slug),
            ])->all(),
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_papersJsonLd !!}</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Keep the scroll reveal animations
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('in');
          revealObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
});
</script>
@endpush
