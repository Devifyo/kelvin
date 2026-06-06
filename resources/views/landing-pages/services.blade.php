@extends('layouts.app')

{{-- ─── SEO ─────────────────────────────────────────────────────────────────── --}}
@section('title', 'Consulting & Training Services | ' . config('app.name'))
@section('meta_description', 'Agile assessments, advisory coaching, and transformation programs for hardware and R&D organizations. Engagements led by Dr. Kevin Thompson, Ph.D.')
@section('meta_keywords', 'agile hardware consulting, agile transformation, agile assessment, agile coaching, hardware scrum, Kevin Thompson')
@section('og_type', 'website')

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
    color: rgba(250,247,242,.85);
    max-width: 600px;
    line-height: 1.8;
    font-weight: 400;
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
    font-weight: 800;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: #7a4b1f;
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
.section-h em { font-style: italic; color: #7a4b1f; }

.ornament {
    width: 40px; height: 1.5px;
    background: linear-gradient(90deg, var(--copper), transparent);
    margin: 1.5rem 0 2rem;
}

.section-lead {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: 1.05rem;
    color: var(--charcoal, #2c3a4a);
    line-height: 1.8;
    max-width: 680px;
    margin-bottom: 3.5rem;
    font-weight: 400;
}

/* Consulting Cards Grid */
.consulting-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-bottom: 0;
}

.service-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 2.75rem 2.25rem;
    position: relative;
    box-shadow: var(--card-shadow);
    transition: transform .3s ease, box-shadow .3s ease;
    display: flex;
    flex-direction: column;
}
.service-card:hover {
    transform: translateY(-4px);
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

/* Icon box */
.service-icon {
    width: 50px; height: 50px;
    border: 1px solid rgba(181,114,42,.25);
    display: flex; align-items: center; justify-content: center;
    color: var(--copper);
    margin-bottom: 1.75rem;
    background: var(--ivory);
    flex-shrink: 0;
}
.service-icon svg {
    width: 22px;
    height: 22px;
    color: var(--copper);
    flex-shrink: 0;
}
.service-icon img {
    width: 22px;
    height: 22px;
    object-fit: contain;
}

/* Card title */
.service-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.7rem;
    font-weight: 600;
    color: var(--slate);
    margin-bottom: .5rem;
    line-height: 1.15;
    display: flex;
    align-items: baseline;
    gap: .5rem;
}

/* Short description line under title */
.service-short-desc {
    font-family: -apple-system, sans-serif;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #7a4b1f;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--ivory3);
}

/* Main description body */
.service-desc {
    font-family: -apple-system, sans-serif;
    font-size: .925rem;
    color: #3a4a5a;
    line-height: 1.85;
    font-weight: 400;
    flex: 1;
}
.service-desc p { margin-bottom: .9rem; }
.service-desc p:last-child { margin-bottom: 0; }
.service-desc ul {
    list-style: none;
    padding: 0;
    margin: .5rem 0;
}
.service-desc ul li {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    padding: .35rem 0;
    font-size: .9rem;
    line-height: 1.65;
    color: #3a4a5a;
    border-bottom: 1px solid rgba(181,114,42,.08);
}
.service-desc ul li:last-child { border-bottom: none; }
.service-desc ul li::before {
    content: '';
    width: 4px; height: 4px;
    border-radius: 50%;
    background: var(--copper);
    flex-shrink: 0;
    margin-top: .6rem;
}

/* Numbered label on title */
.svc-num {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    color: rgba(181, 114, 42, 0.45);
    font-weight: 700;
    flex-shrink: 0;
}
.service-title .svc-num {
    font-size: 1rem;
}

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
    color: var(--charcoal, #2c3a4a);
    font-weight: 400;
    margin-bottom: 2rem;
}

.course-list {
    display: flex;
    flex-direction: column;
    gap: .6rem;
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
    flex-shrink: 0;
}
.course-link:hover svg {
    opacity: 1;
    transform: translateX(4px);
}
.course-link:hover::before { transform: scaleY(1); }

/* Numbered label on training links */
.course-link .svc-num {
    font-size: 0.8rem;
    width: 22px;
    text-align: right;
    margin-right: .75rem;
}

@media(max-width: 1100px) {
    .consulting-grid { grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .training-wrap { grid-template-columns: 1fr; gap: 4rem; }
}
@media(max-width: 700px) {
    .page-header { padding: 9rem 2.5rem 4rem; }
    .content-section, .training-section { padding: 5rem 2.5rem; }
    .service-card { padding: 2rem 1.5rem; }
    .consulting-grid { grid-template-columns: 1fr; }
}

/* Mobile */
@media(max-width: 576px) {
    .page-header { padding: 7rem 1.25rem 3rem; }
    .page-subtitle { font-size: .95rem; line-height: 1.7; }
    .kicker { font-size: .6rem; letter-spacing: .25em; }
    .content-section, .training-section { padding: 3.5rem 1.25rem; }
    .section-lead { font-size: 1rem; margin-bottom: 2.5rem; }
    .consulting-grid { gap: 1.25rem; }
    .service-card { padding: 1.85rem 1.4rem; }
    .service-title { font-size: 1.5rem; }
    .training-wrap { gap: 2.5rem; }
    .training-intro p { font-size: 1rem; }
    .course-link { padding: 1rem 1.1rem; font-size: .92rem; }
}

/* Small phones */
@media(max-width: 380px) {
    .page-header { padding: 6.5rem 1rem 2.5rem; }
    .content-section, .training-section { padding: 3rem 1rem; }
    .service-card { padding: 1.6rem 1.2rem; }
    .service-title { font-size: 1.4rem; }
    .course-link { padding: .9rem 1rem; font-size: .88rem; }
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
            <p class="section-lead">We work directly with your organization to diagnose delivery issues, coach teams, and drive lasting improvement. Every engagement is scoped and tailored to the specific needs of the client.</p>
        </div>

        <div class="consulting-grid">

            @foreach($consultingServices as $index => $service)
                <div class="service-card reveal rv{{ ($index % 3) + 1 }}">

                    <div class="service-icon">
                        @if($service->featured_image)
                            <img src="{{ asset('storage/' . $service->featured_image) }}"
                                alt="{{ $service->title }} icon">
                        @elseif($service->icon)
                            {!! $service->icon !!}
                        @else
                            @php
                                $iconPath = match($service->slug) {
                                    'agile-assessment-services'          => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>',
                                    'agile-advisory-engagement-coaching' => '<path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
                                    'agile-transformation-consulting'    => '<path d="M3 12a9 9 0 019-9 9.75 9.75 0 016.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 01-9 9 9.75 9.75 0 01-6.74-2.74L3 16"/><path d="M3 21v-5h5"/>',
                                    default                              => '<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>',
                                };
                            @endphp
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                {!! $iconPath !!}
                            </svg>
                        @endif
                    </div>

                    <h3 class="service-title">
                        <span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}.</span>
                        <span>{{ $service->title }}</span>
                    </h3>

                    @if($service->short_description)
                        <div class="service-short-desc">{{ $service->short_description }}</div>
                    @else
                        <div class="ornament" style="margin:1rem 0 1.5rem;"></div>
                    @endif

                    <div class="service-desc">
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
            <div class="kicker">Education &amp; Growth</div>
            <h2 class="section-h">Training <em>Classes</em></h2>
            <div class="ornament"></div>
            <p>The following classes and presentations are available. Each is designed to address specific needs within your organization — from executive briefings to deep-dive team frameworks.</p>
        </div>

        <div class="course-list reveal rv1">

            @foreach($trainingClasses as $class)
                <a href="{{ route('training', $class->slug) }}" class="course-link">
                    <span style="display:flex;align-items:center;gap:.75rem;">
                        <span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        {{ $class->title }}
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            @endforeach

        </div>

    </div>
</section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: Service — provider referenced by @id from layout --}}
@php
    $_svcJsonLd = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => 'Agile Consulting & Transformation for Hardware Organizations',
        'description' => 'Agile assessments, advisory coaching, and transformation programs for hardware and R&D organizations. Engagements led by Dr. Kevin Thompson, Ph.D.',
        'provider'    => ['@id' => url('/') . '/#organization'],
        'areaServed'  => 'Worldwide',
        'serviceType' => ['Agile Consulting', 'Agile Transformation', 'Agile Assessment', 'Agile Coaching'],
        'audience'    => ['@type' => 'BusinessAudience', 'audienceType' => 'R&D and hardware engineering organizations'],
        'url'         => url()->current(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_svcJsonLd !!}</script>
@endpush
