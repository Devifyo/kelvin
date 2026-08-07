@extends('layouts.app')

@section('title', 'Agile & Scrum Training for Hardware Teams | Kevin Thompson, Ph.D.')
@section('meta_description', 'Live, instructor-led Scrum and Agile classes for hardware, embedded, and R&D teams. Taught by Dr. Kevin Thompson - CSP, PMI-ACP, PMP-aligned curricula.')
@section('meta_keywords', 'agile training, scrum training, agile hardware training, hardware scrum, kanban training, agile portfolio management, Kevin Thompson')

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
    text-align: center;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.6) 0%, transparent 70%);
    z-index: 0;
}
.header-content {
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}
.kicker {
    display: inline-flex;
    align-items: center;
    gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--copper2);
    margin-bottom: 1.1rem;
}
.kicker::before, .kicker::after { 
    content: ''; width: 24px; height: 1px; background: var(--copper2); 
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
.short-desc {
    font-family: -apple-system, sans-serif;
    font-size: 1.1rem;
    color: rgba(250,247,242,.7);
    line-height: 1.8;
    font-weight: 300;
    max-width: 700px;
    margin: 0 auto;
}

/* ─────────────────────────────────────────
   TRAINING INDEX LAYOUT
───────────────────────────────────────── */
.training-wrapper {
    background: var(--ivory);
    padding: 6rem 4.5rem 7rem;
}
.content-container {
    max-width: 1180px;
    margin: 0 auto;
}

/* Two-column responsive grid */
.course-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.75rem;
}

/* Course Cards */
.course-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 2.5rem 2.4rem;
    box-shadow: 0 10px 30px rgba(26,35,50,.03);
    transition: transform .4s ease, box-shadow .4s ease, border-color .4s ease;
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
    position: relative;
    overflow: hidden;
}
.course-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--copper);
    transform: scaleX(0); transform-origin: left;
    transition: transform .4s ease;
}
.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 42px rgba(26,35,50,.08);
    border-color: rgba(181,114,42,.2);
}
.course-card:hover::before { transform: scaleX(1); }

.course-card h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.55rem;
    font-weight: 500;
    color: var(--slate);
    line-height: 1.25;
    margin: 0;
}
.course-card p {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .96rem;
    line-height: 1.7;
    color: var(--body-text, #4a5568);
    margin: 0;
    /* Clamp to ~3 lines for grid balance */
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Card Meta (Bottom Row) */
.card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: auto;
    padding-top: 1.4rem;
    border-top: 1px solid var(--ivory3);
}
.meta-info {
    display: flex;
    flex-direction: column;
    gap: .45rem;
    min-width: 0;
}
.meta-item {
    display: flex;
    align-items: center;
    gap: .45rem;
    font-family: -apple-system, sans-serif;
    font-size: .72rem;
    font-weight: 600;
    color: var(--muted, #718096);
    letter-spacing: .05em;
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.meta-item svg { color: var(--copper); flex-shrink: 0; }

/* Buttons */
.btn-outline {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .65rem 1.2rem;
    background: transparent;
    color: var(--slate);
    font-family: -apple-system, sans-serif;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    border: 1.5px solid var(--ivory3);
    transition: all .35s;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
}
.btn-outline:hover {
    border-color: var(--copper);
    color: var(--copper);
    background: var(--ivory);
}

@media(max-width: 900px) {
    .course-grid { grid-template-columns: 1fr; gap: 1.25rem; }
}
@media(max-width: 768px) {
    .page-header { padding: 9rem 2.5rem 4rem; }
    .training-wrapper { padding: 4rem 1.5rem 5rem; }
    .course-card { padding: 2rem 1.6rem; }
    .card-footer { flex-direction: column; align-items: flex-start; gap: 1rem; }
}

/* Mobile */
@media(max-width: 576px) {
    .page-header { padding: 7rem 1.25rem 3rem; }
    .short-desc { font-size: .95rem; line-height: 1.7; }
    .kicker { font-size: .6rem; letter-spacing: .25em; }
    .training-wrapper { padding: 3.5rem 1.25rem; }
    .course-card { padding: 2rem 1.5rem; margin-bottom: 2rem; gap: 1.1rem; }
    .course-card h2 { font-size: 1.7rem; }
    .course-card p { font-size: 1rem; }
    .card-footer { padding-top: 1.5rem; gap: 1.5rem; }
    .btn-outline { width: 100%; justify-content: center; }
}

/* Small phones */
@media(max-width: 380px) {
    .page-header { padding: 6.5rem 1rem 2.5rem; }
    .training-wrapper { padding: 3rem 1rem; }
    .course-card { padding: 1.7rem 1.25rem; }
    .course-card h2 { font-size: 1.55rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Curriculum</div>
        <h1 class="page-title">Training <em>Classes</em></h1>
        <p class="short-desc">We provide comprehensive on-site training to empower your teams, from hands-on engineers to the C-suite, with the Agile skills needed to ship faster and reduce risk.</p>
    </div>
</section>

<div class="strip"></div>

<section class="training-wrapper">
    <div class="content-container">
        <div class="course-grid">
            @forelse($trainingClasses as $index => $class)
                <div class="course-card reveal rv{{ ($index % 3) + 1 }}">
                    <h2>{{ $class->title }}</h2>

                    @if($class->short_description)
                        <p>{{ $class->short_description }}</p>
                    @endif

                    <div class="card-footer">
                        <div class="meta-info">
                            @if(! empty($class->length))
                                <div class="meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    {{ rtrim($class->length, '.') }}
                                </div>
                            @endif

                            @if(! empty($class->audience))
                                <div class="meta-item" title="{{ $class->audience }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    {{ \Illuminate\Support\Str::limit(trim(strtok($class->audience, ',')), 32) }}
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('training', ['slug' => $class->slug]) }}" class="btn-outline" aria-label="View details for {{ $class->title }}">Details →</a>
                    </div>
                </div>
            @empty
                <div class="course-card" style="grid-column: 1 / -1;">
                    <h2>No training classes available</h2>
                    <p>The training catalog is being updated. Please <a href="{{ url('/contact-us') }}">contact us</a> to schedule a custom engagement.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@include('landing-pages.partials.page-faqs', [
    'page'    => 'training',
    'ctaText' => 'Book a class',
    'ctaUrl'  => route('contact'),
])

@endsection

@push('scripts')
{{-- Page-level JSON-LD: Course list for the training catalog --}}
@php
    $_courseList = [];
    if (! empty($trainingClasses)) {
        $i = 1;
        foreach ($trainingClasses as $class) {
            $_courseList[] = [
                '@type'    => 'ListItem',
                'position' => $i++,
                'item'     => array_filter([
                    '@type'       => 'Course',
                    'name'        => $class->title,
                    'url'         => url('/agile-training-classes/' . $class->slug),
                    'description' => strip_tags($class->short_description ?: ($class->meta_description ?: '')),
                    'provider'    => ['@id' => url('/') . '/#organization'],
                    'instructor'  => ['@id' => url('/') . '/#person'],
                    'audience'    => $class->audience
                        ? ['@type' => 'Audience', 'audienceType' => $class->audience]
                        : null,
                ]),
            ];
        }
    }
    $_trainingListJsonLd = json_encode([
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Agile & Scrum Training Catalog',
        'description'     => 'Instructor-led training classes by Dr. Kevin Thompson, Ph.D.',
        'url'             => url()->current(),
        'numberOfItems'   => count($_courseList),
        'itemListElement' => $_courseList,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_trainingListJsonLd !!}</script>

<script>
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
</script>
@endpush