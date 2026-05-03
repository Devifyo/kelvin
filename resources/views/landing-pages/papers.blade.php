@extends('layouts.app')

@section('title', 'Papers & Presentations | Kelvin Enterprise')
@section('meta_description', 'Explore Kelvin Enterprise\'s collection of Agile research papers and presentations — covering Agile hardware development, embedded systems, Scrum methodologies, and real-world transformation case studies by Kevin Thompson PhD.')
@section('meta_keywords', 'Agile research papers, Agile presentations, Agile hardware development, embedded systems Agile, Scrum methodology, Agile case studies, Kevin Thompson PhD, Kelvin Enterprise, Agile transformation research, hardware Agile papers')
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

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Knowledge &amp; Research</div>
        <h1 class="page-title">Papers &amp; <em>Presentations</em></h1>
        <p class="page-subtitle">A comprehensive collection of insights, methodologies, and findings from our extensive engagements in Agile hardware and software development.</p>
    </div>
</section>

{{-- DYNAMIC SERVER-SIDE FILTER MENU --}}
<div class="filter-container reveal rv1">
    <div class="filter-menu">
        <a href="{{ route('papers', ['category' => 'all']) }}" 
           class="filter-btn {{ $currentFilter === 'all' ? 'active' : '' }}">
           All Documents
        </a>
        
        @foreach($categories as $cat)
            <a href="{{ route('papers', ['category' => $cat->slug]) }}" 
               class="filter-btn {{ $currentFilter === $cat->slug ? 'active' : '' }}">
               {{ $cat->name }}
            </a>
        @endforeach
    </div>
</div>

<section class="content-section">
    <div class="papers-grid" id="papers-container">

        {{-- DYNAMIC CARDS --}}
        @forelse($papers as $paper)
            <div class="paper-card">
                <div class="paper-meta">
                    <span class="paper-category-tag">{{ $paper->category?->name ?? 'Document' }}</span>
                    {{ $paper->sub_category }}
                </div>
                <h2 class="paper-title">{{ $paper->title }}</h2>
                
                {{-- Unescaped output so TinyMCE HTML renders correctly --}}
                <div class="paper-desc">
                    {!! $paper->description !!}
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
        @empty
            {{-- SERVER-SIDE NO RESULTS MESSAGE --}}
            <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: var(--white); border: 1px dashed var(--ivory3);">
                <h3>No documents found.</h3>
                <p style="color: var(--muted);">Please try selecting a different category.</p>
            </div>
        @endforelse

    </div>
</section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: Research papers collection structured data --}}
@php
    $_papersJsonLd = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => 'Papers & Presentations | Kelvin Enterprise',
        'description' => 'A comprehensive collection of Agile research papers and presentations by Kevin Thompson PhD — covering Agile hardware development, embedded systems, Scrum methodologies, and real-world transformation case studies.',
        'url'         => url()->current(),
        'author'      => [
            '@type' => 'Person',
            'name'  => 'Kevin Thompson PhD',
        ],
        'publisher'   => [
            '@type' => 'Organization',
            'name'  => 'Kelvin Enterprise',
            'url'   => url('/'),
        ],
        'about'       => ['Agile Development', 'Hardware Agile', 'Embedded Systems', 'Scrum', 'Agile Transformation'],
        'inLanguage'  => 'en-US',
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