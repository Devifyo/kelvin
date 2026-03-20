@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 11rem 4.5rem 6rem;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.6) 0%, transparent 70%);
    z-index: 0;
}
.header-content {
    max-width: 1180px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}
.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3rem, 5vw, 4.5rem);
    font-weight: 300;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 1.5rem;
}
.page-title em { font-style: italic; color: var(--copper3); }
.page-subtitle {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: 1.05rem;
    color: rgba(250,247,242,.85); /* FIX: Bumped opacity from .6 to .85 for dark background contrast */
    max-width: 600px;
    line-height: 1.8;
    font-weight: 400; /* FIX: Bumped weight for readability */
}

/* ─────────────────────────────────────────
   CONSULTING SECTION
───────────────────────────────────────── */
.content-section {
    padding: 7rem 4.5rem;
    background: var(--ivory);
}
.content-wrap {
    max-width: 1180px;
    margin: 0 auto;
}

.kicker {
    display: inline-flex;
    align-items: center;
    gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem;
    font-weight: 800; /* FIX: Bumped from 700 to 800 */
    letter-spacing: .3em;
    text-transform: uppercase;
    color: #7a4b1f; /* FIX: Darkened copper to explicitly pass WCAG 4.5:1 ratio */
    margin-bottom: 1.1rem;
}
.kicker::before { content: ''; width: 24px; height: 1px; background: var(--copper); }

.section-h {
    font-size: clamp(2rem, 3.2vw, 2.85rem);
    font-weight: 400;
    line-height: 1.1;
    color: var(--slate);
    margin-bottom: 1.1rem;
}
.section-h em { font-style: italic; color: #7a4b1f; } /* FIX: Accessible darkened copper */

.ornament {
    width: 40px; height: 1.5px;
    background: linear-gradient(90deg, var(--copper), transparent);
    margin: 1.5rem 0 3rem;
}

/* Consulting Cards Grid */
.consulting-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
    margin-bottom: 6rem;
}

.service-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 3rem 2.5rem;
    position: relative;
    box-shadow: var(--card-shadow);
    transition: transform .3s ease, box-shadow .3s ease;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
    border-color: rgba(181,114,42,.2);
}
.service-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: var(--copper);
    transform: scaleX(0); transform-origin: left;
    transition: transform .4s cubic-bezier(.4,0,.2,1);
}
.service-card:hover::before { transform: scaleX(1); }

.service-icon {
    width: 54px; height: 54px;
    border: 1px solid rgba(181,114,42,.3);
    display: flex; align-items: center; justify-content: center;
    color: var(--copper);
    margin-bottom: 2rem;
    background: var(--ivory);
}

.service-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--slate);
    margin-bottom: 1.25rem;
    line-height: 1.2;
}

.service-desc {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--charcoal, #2c3a4a); /* FIX: Explicitly use charcoal instead of the lighter body-text */
    line-height: 1.8;
    font-weight: 400; /* FIX: Bumped from 300 to 400 for better contrast parsing */
}
.service-desc p { margin-bottom: 1rem; }
.service-desc p:last-child { margin-bottom: 0; }

/* ─────────────────────────────────────────
   TRAINING SECTION
───────────────────────────────────────── */
.training-section {
    background: var(--white);
    border-top: 1px solid var(--ivory3);
    padding: 7rem 4.5rem;
}
.training-wrap {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 6rem;
    align-items: start;
}
.training-intro p {
    font-family: -apple-system, sans-serif;
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--charcoal, #2c3a4a); /* FIX: Enforced dark text */
    font-weight: 400; /* FIX: Readability bump */
    margin-bottom: 2rem;
}

.course-list {
    display: flex;
    flex-direction: column;
    gap: .75rem; /* Tightened slightly for a sleeker list */
}
.course-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.75rem;
    background: var(--ivory);
    border: 1px solid var(--ivory3);
    font-family: -apple-system, sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--slate);
    position: relative;
    overflow: hidden;
    transition: all .32s cubic-bezier(.4, 0, .2, 1);
    text-decoration: none;
}
.course-link::before {
    content: ''; 
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: var(--copper);
    transform: scaleY(0); transform-origin: bottom;
    transition: transform .32s cubic-bezier(.4, 0, .2, 1);
}
.course-link:hover {
    background: var(--white);
    border-color: rgba(181,114,42,.25);
    transform: translateX(5px);
    box-shadow: var(--card-shadow);
    color: var(--slate);
}
.course-link svg {
    color: var(--copper2);
    opacity: 0;
    transform: translateX(-8px);
    transition: opacity .32s ease, transform .32s cubic-bezier(.4, 0, .2, 1);
    position: relative;
    z-index: 1;
}
.course-link:hover svg {
    opacity: 1;
    transform: none;
}
.course-link:hover svg {
    transform: translateX(4px);
}
.course-link:hover::before {
    transform: scaleY(1);
}

@media(max-width: 1100px) {
    .consulting-grid { grid-template-columns: 1fr; gap: 2rem; }
    .training-wrap { grid-template-columns: 1fr; gap: 4rem; }
}
@media(max-width: 700px) {
    .page-header { padding: 9rem 2.5rem 4rem; }
    .content-section, .training-section { padding: 5rem 2.5rem; }
    .service-card { padding: 2.5rem 1.5rem; }
}

/* ─────────────────────────────────────────
   NUMBERED LIST CONSISTENCY (MATCHES HOME PAGE)
───────────────────────────────────────── */
.svc-num {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    color: rgba(181, 114, 42, 0.5); /* Muted copper to match homepage */
    font-weight: 700;
    flex-shrink: 0;
    margin-right: 0.5rem;
}

/* Specific sizing for the big consulting cards */
.service-title {
    display: flex;
    align-items: baseline;
}
.service-title .svc-num {
    font-size: 1.25rem;
    width: 28px;
    text-align: left;
}

/* Specific sizing for the training list links */
.course-link-inner {
    display: flex;
    align-items: center;
}
.course-link .svc-num {
    font-size: 0.85rem;
    width: 22px;
    text-align: right;
    margin-right: 1rem;
}
</style>
@endpush

@section('content')

{{-- ─────────────────────────────────────────
     PAGE HEADER
───────────────────────────────────────── --}}
<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker" style="color:var(--copper2);">Our Expertise</div>
        <h1 class="page-title">Consulting &amp; <em>Training</em></h1>
        <p class="page-subtitle">We offer both consulting and training services. All services are provided on-site at client locations.</p>
    </div>
</section>

<div class="strip"></div>

{{-- ─────────────────────────────────────────
     CONSULTING SERVICES
───────────────────────────────────────── --}}
<section class="content-section">
    <div class="content-wrap">
        
        <div class="reveal">
            <div class="kicker">Strategic Guidance</div>
            <h2 class="section-h">Consulting <em>Services</em></h2>
            <div class="ornament"></div>
        </div>

        <div class="consulting-grid">
            
           @foreach($consultingServices as $index => $service)
                {{-- Dynamically stagger the animation delay --}}
                <div class="service-card reveal rv{{ ($index % 3) + 1 }}">
                    
                    <div class="service-icon">
                        @if($service->featured_image)
                            {{-- 1. Display Uploaded Image/Icon File --}}
                            <img src="{{ asset('storage/' . $service->featured_image) }}" 
                                alt="{{ $service->title }} icon" 
                                style="width: 24px; height: 24px; object-fit: contain;">
                        @elseif($service->icon)
                            {{-- 2. Display Raw SVG Code (Preserves theme colors) --}}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                {!! $service->icon !!}
                            </svg>
                        @else
                            {{-- 3. Fallback to hardcoded match logic --}}
                            @php
                                $iconPath = match($service->slug) {
                                    'agile-assessment-services' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>',
                                    'agile-advisory-engagement-coaching' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
                                    'agile-transformation-consulting' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
                                    default => '<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>'
                                };
                            @endphp
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                {!! $iconPath !!}
                            </svg>
                        @endif
                    </div>

                    <h3 class="service-title">
                        <span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}.</span>
                        <span>{{ $service->title }}</span>
                    </h3>
                    
                    <div class="service-desc markdown-content">
                        {{-- Markdown support for lists and formatting --}}
                        {!! \Illuminate\Support\Str::markdown($service->content) !!}
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</section>

{{-- ─────────────────────────────────────────
     TRAINING CLASSES
───────────────────────────────────────── --}}
<section class="training-section">
    <div class="training-wrap">
        
        <div class="training-intro reveal">
            <div class="kicker">Education & Growth</div>
            <h2 class="section-h">Training <em>Classes</em></h2>
            <div class="ornament"></div>
            <p>The following classes and presentations are available. Each class is designed to address specific needs within your organization, from executive briefings to deep-dive team frameworks.</p>
        </div>

        <div class="course-list reveal rv1">
            
            @foreach($trainingClasses as $class)
                {{-- Dynamically generate the route using the class's SEO slug --}}
                <a href="{{ route('training', $class->slug) }}" class="course-link">
                    <span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    {{ $class->title }}
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            @endforeach

        </div>

    </div>
</section>

@endsection

@push('scripts')

@endpush