@extends('layouts.app')

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