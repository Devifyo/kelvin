@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER (Matches Blog/Papers)
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 12rem 4.5rem 8rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%);
    z-index: 0;
}
.header-content { max-width: 900px; margin: 0 auto; position: relative; z-index: 1; }
.kicker { display: inline-flex; align-items: center; justify-content: center; gap: .75rem; font-family: -apple-system, sans-serif; font-size: .75rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: var(--copper); margin-bottom: 1.5rem; }
.kicker::before, .kicker::after { content: ''; width: 30px; height: 1px; background: var(--copper); }
.page-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(3rem, 6vw, 4.8rem); font-weight: 400; color: var(--white); line-height: 1.1; margin-bottom: 1.5rem; }
.page-title em { font-style: italic; color: var(--copper3); }
.page-subtitle { font-family: -apple-system, sans-serif; font-size: 1.1rem; color: rgba(250,247,242,.7); max-width: 650px; line-height: 1.8; font-weight: 300; margin: 0 auto; }

/* ─────────────────────────────────────────
   FILTER BAR 
───────────────────────────────────────── */
.filter-container {
    padding: 2rem 4.5rem;
    border-bottom: 1px solid var(--ivory3);
    display: flex;
    justify-content: center;
    position: relative;
    top: 0px; /* Adjust this number to fix the scroll gap! */
    z-index: 150;
    backdrop-filter: blur(10px);
    background: rgba(242, 236, 227, 0.9);
}
.filter-menu {
    display: flex;
    gap: .5rem;
    background: var(--white);
    padding: .5rem;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(26,35,50,.05);
    border: 1px solid var(--ivory3);
    flex-wrap: wrap;
    justify-content: center;
}
.filter-btn {
    font-family: -apple-system, sans-serif;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--slate);
    padding: .8rem 1.75rem;
    border-radius: 50px;
    transition: all .3s ease;
    text-decoration: none; 
    display: inline-block; 
}
.filter-btn:hover {
    color: var(--copper);
}
.filter-btn.active {
    background: var(--slate);
    color: var(--white);
    box-shadow: 0 4px 12px rgba(26,35,50,.15);
}

/* ─────────────────────────────────────────
   MEDIA GRID & CARDS
───────────────────────────────────────── */
.content-section { padding: 5rem 4.5rem 8rem; background: var(--ivory); min-height: 60vh; }
.media-grid { max-width: 1180px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5rem; }

.media-card { display: flex; flex-direction: column; background: var(--white); border: 1px solid var(--ivory3); box-shadow: 0 10px 25px rgba(26,35,50,.03); transition: transform .4s, box-shadow .4s; position: relative; text-decoration: none; overflow: hidden; }
.media-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(26,35,50,.08); border-color: rgba(181,114,42,.2); }

/* Thumbnail & Glass Play Button */
.media-img-wrap { width: 100%; height: 220px; position: relative; overflow: hidden; border-bottom: 1px solid var(--ivory3); background: var(--slate); display: flex; align-items: center; justify-content: center; }
.media-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; opacity: 0.85; }
.media-card:hover .media-img-wrap img { transform: scale(1.05); opacity: 1; }

.play-overlay { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 64px; height: 64px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; transition: all 0.3s; z-index: 2; box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
.play-overlay svg { width: 24px; height: 24px; fill: var(--white); margin-left: 4px; transition: transform 0.3s; }
.media-card:hover .play-overlay { background: var(--copper); border-color: var(--copper); transform: translate(-50%, -50%) scale(1.1); }

/* Typography */
.media-content { padding: 2rem; display: flex; flex-direction: column; flex-grow: 1; }
.media-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.media-tag { font-family: -apple-system, sans-serif; font-size: .7rem; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: var(--copper); background: rgba(181,114,42,.1); padding: .3rem .8rem; border-radius: 4px; }
.media-date { font-family: -apple-system, sans-serif; font-size: .75rem; color: var(--muted); font-weight: 600; }
.media-title { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 600; color: var(--slate); line-height: 1.25; margin-bottom: 1rem; transition: color .3s; }
.media-card:hover .media-title { color: var(--copper); }
.media-desc { font-family: -apple-system, sans-serif; font-size: .95rem; color: var(--body-text); line-height: 1.6; font-weight: 300; margin-bottom: 2rem; flex-grow: 1; }
.media-platform { font-family: -apple-system, sans-serif; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--slate); display: flex; align-items: center; gap: .5rem; margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--ivory3); }
.media-platform svg { color: var(--copper); }

@media(max-width: 1024px) { .media-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 900px) {
    .filter-container { padding: 1.5rem; }
}
@media(max-width: 768px) { 
    .page-header { padding: 10rem 2.5rem 5rem; } 
    .content-section { padding: 3rem 2.5rem 5rem; } 
    .media-grid { grid-template-columns: 1fr; gap: 2rem; } 
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Media &amp; Appearances</div>
        <h1 class="page-title">Podcasts &amp; <em>Webinars</em></h1>
        <p class="page-subtitle">Watch and listen to Dr. Kevin Thompson's latest interviews, keynotes, and deep-dives into Agile hardware transformation.</p>
    </div>
</section>

{{-- DYNAMIC SERVER-SIDE FILTER MENU --}}
<div class="filter-container reveal rv1">
    <div class="filter-menu">
        <a href="{{ route('podcasts-webinars', ['type' => 'all']) }}" 
           class="filter-btn {{ $currentFilter === 'all' ? 'active' : '' }}">
           All Media
        </a>
        <a href="{{ route('podcasts-webinars', ['type' => 'podcast']) }}" 
           class="filter-btn {{ $currentFilter === 'podcast' ? 'active' : '' }}">
           Podcasts
        </a>
        <a href="{{ route('podcasts-webinars', ['type' => 'webinar']) }}" 
           class="filter-btn {{ $currentFilter === 'webinar' ? 'active' : '' }}">
           Webinars
        </a>
        <a href="{{ route('podcasts-webinars', ['type' => 'interview']) }}" 
           class="filter-btn {{ $currentFilter === 'interview' ? 'active' : '' }}">
           Interviews
        </a>
    </div>
</div>

<section class="content-section">
    <div class="media-grid">

        {{-- DYNAMIC CARDS --}}
        @forelse($mediaItems as $item)
            <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" class="media-card reveal rv2">
                <div class="media-img-wrap">
                    @if($item->thumbnail_url)
                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" loading="lazy">
                    @else
                        {{-- Fallback Gradient if no image is uploaded --}}
                        <div style="width:100%; height:100%; background: linear-gradient(135deg, var(--slate), var(--charcoal));"></div>
                    @endif
                    
                    <div class="play-overlay">
                        <svg viewBox="0 0 24 24"><path d="M5 3l14 9-14 9V3z"/></svg>
                    </div>
                </div>

                <div class="media-content">
                    <div class="media-meta">
                        <span class="media-tag">{{ ucfirst($item->type) }}</span>
                        <span class="media-date">{{ $item->published_date ? $item->published_date->format('M d, Y') : '' }}</span>
                    </div>
                    
                    <h3 class="media-title">{{ $item->title }}</h3>
                    
                    <div class="media-desc">
                        {{ $item->description }}
                    </div>
                    
                    <div class="media-platform">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        Listen / Watch on {{ $item->platform ?: 'External Site' }}
                    </div>
                </div>
            </a>
        @empty
            {{-- SERVER-SIDE NO RESULTS MESSAGE --}}
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: var(--white); border: 1px dashed var(--ivory3); border-radius: 12px;">
                <h3 style="font-family: 'Cormorant Garamond'; font-size: 2rem; color: var(--slate); margin-bottom: 0.5rem;">No podcasts or webinars found.</h3>
                <p style="color: var(--muted);">Please try selecting a different filter or check back soon.</p>
            </div>
        @endforelse

    </div>
</section>

@endsection

@push('scripts')
@endpush