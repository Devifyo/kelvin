@extends('layouts.app')

@section('title', $service->seoTitle())
@section('meta_description', $service->meta_description ?: $service->short_description)
@section('meta_keywords', $service->meta_keywords ?: 'agile training, scrum training, agile hardware development, Kevin Thompson')
@section('og_type', 'article')
@if($service->featured_image)
    @section('og_image', asset('storage/' . $service->featured_image))
    @section('og_image_alt', $service->title . ' — Training Class')
@endif

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 12rem 4.5rem 6rem;
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
.kicker::before { content: ''; width: 24px; height: 1px; background: var(--copper2); }

.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3rem, 4.5vw, 4rem);
    font-weight: 300;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 1.5rem;
    max-width: 900px;
}
.page-title em { font-style: italic; color: var(--copper3); }

.short-desc {
    font-family: -apple-system, sans-serif;
    font-size: 1.1rem;
    color: rgba(250,247,242,.7);
    max-width: 700px;
    line-height: 1.8;
    font-weight: 300;
}

/* ─────────────────────────────────────────
   COURSE LAYOUT
───────────────────────────────────────── */
.course-section {
    padding: 7rem 4.5rem;
    background: var(--ivory);
}
.course-grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 5rem;
    align-items: stretch;
}

/* MAIN CONTENT */
.course-main h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    color: var(--slate);
    margin: 0 0 1.25rem;
    font-weight: 400;
    line-height: 1.2;
}
.course-main h2 em { font-style: italic; color: var(--copper); }

.ornament {
    width: 60px; height: 1.5px;
    background: linear-gradient(90deg, var(--copper), transparent);
    margin-bottom: 2rem;
}

.course-body p {
    font-family: 'Palatino Linotype', 'Book Antiqua', Palatino, Georgia, serif;
    font-size: 1.15rem;
    line-height: 1.9;
    color: var(--charcoal);
    margin-bottom: 1.75rem;
}

/* Highlight Box for Objectives */
.objectives-box {
    background: var(--white);
    border: 1px solid var(--ivory3);
    border-left: 3px solid var(--copper);
    padding: 2.5rem;
    margin: 3.5rem 0;
    box-shadow: var(--card-shadow);
}
.objectives-box h3 {
    font-family: -apple-system, sans-serif;
    font-size: .85rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--slate);
    margin-bottom: 1rem;
}
.objectives-box p {
    font-family: 'Palatino Linotype', serif;
    font-size: 1.2rem;
    font-style: italic;
    line-height: 1.7;
    color: var(--charcoal);
    margin: 0;
}

/* Curriculum Topics Layout */
.topics-container {
    margin-top: 4rem;
}
.topics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem 2rem;
}
.topic-group h3 {
    font-family: -apple-system, sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--slate);
    margin-bottom: 1rem;
    padding-bottom: .5rem;
    border-bottom: 1px solid var(--copper);
    display: inline-block;
}
.topic-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.topic-item {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    font-family: -apple-system, sans-serif;
    font-size: .9rem;
    color: var(--body-text);
    line-height: 1.4;
}
.topic-icon {
    color: var(--copper2);
    margin-top: .15rem;
    flex-shrink: 0;
}

/* SIDEBAR META */
.course-sidebar {
    position: sticky;
    top: 140px;
    align-self: start;
}
.meta-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    box-shadow: var(--card-shadow);
    padding: 2.5rem;
}
.meta-item {
    margin-bottom: 2rem;
}
.meta-item:last-child {
    margin-bottom: 0;
}
.meta-label {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-family: -apple-system, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--copper_dark);
    margin-bottom: .75rem;
}
.meta-value {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    line-height: 1.6;
    color: var(--slate);
    font-weight: 500;
}

.sidebar-cta {
    margin-top: 2rem;
    display: block;
    text-align: center;
    width: 100%;
    padding: 1.1rem 2rem;
    background: linear-gradient(135deg, var(--copper), var(--copper2));
    color: var(--white);
    font-family: -apple-system, sans-serif;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    transition: all .35s;
    box-shadow: 0 4px 24px rgba(181,114,42,.35);
    text-decoration: none;
}
.sidebar-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 36px rgba(181,114,42,.5);
    background: linear-gradient(135deg, var(--copper2), var(--copper3));
    color: var(--slate);
}

@media(max-width: 900px) {
    .course-grid { grid-template-columns: 1fr; gap: 4rem; }
    .course-sidebar { position: static; align-self: auto; }
    .topics-grid { grid-template-columns: 1fr; gap: 2rem; }
    .page-header { padding: 10rem 2.5rem 4rem; }
    .course-section { padding: 5rem 2.5rem; }
}
.markdown-content p {
        margin-bottom: 1.25rem;
        color: var(--charcoal, #2c3a4a);
        line-height: 1.8;
    }
    .markdown-content p:last-child {
        margin-bottom: 0;
    }
    
    /* Premium Custom Bullet Points */
    .markdown-content ul {
        margin: 1.25rem 0 1.5rem 1.5rem;
        padding: 0;
        list-style: none;
    }
    .markdown-content li {
        position: relative;
        margin-bottom: 0.75rem;
        color: var(--charcoal, #2c3a4a);
        line-height: 1.7;
        font-weight: 400;
    }
    .markdown-content li::before {
        content: '•';
        position: absolute;
        left: -1.25rem;
        color: var(--copper);
        font-size: 1.2rem;
        line-height: 1;
        top: 0.1rem;
    }
    .markdown-content em {
        font-style: italic;
        color: rgba(181,114,42, 0.9); /* Darkened copper for readability */
        font-size: 0.92rem;
        display: block; /* Pushes the note to its own line naturally */
        margin-top: 1rem;
        padding-left: 1rem;
        border-left: 2px solid rgba(181,114,42, 0.3); /* Subtle copper line on the left */
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
    <section class="page-header">
        <div class="header-content reveal">
            <a href="{{ route('services.training') }}" class="kicker" style="text-decoration: none;">← Back to Curriculum</a>
            
            <h1 class="page-title">{!! str_replace(' with ', ' <em>with</em> ', e($service->title)) !!}</h1>
            
            <p class="short-desc">{{ $service->short_description }}</p>
        </div>
    </section>

    <div class="strip"></div>

    <section class="course-section">
        <div class="course-grid">
            
            <div class="course-main reveal">
                
                <h2>Course <em>Overview</em></h2>
                <div class="ornament"></div>

                {{-- Added 'markdown-content' class and Str::markdown() --}}
                <div class="course-body markdown-content">
                    @php
                        // Fixes the (*) so the Markdown parser doesn't break the italics
                        $safeContent = str_replace('(*)', '(\*)', $service->content);
                    @endphp
                    
                    {!! \Illuminate\Support\Str::markdown($safeContent) !!}
                </div>

                @if($service->learning_objectives)
                <div class="objectives-box reveal rv1 markdown-content">
                    <h3>Learning Objectives</h3>
                    {!! \Illuminate\Support\Str::markdown($service->learning_objectives) !!}
                </div>
                @endif

                @if(!empty($service->topics))
                <div class="topics-container reveal rv2">
                    <h2>Curriculum &amp; <em>Topics</em></h2>
                    <div class="ornament"></div>
                    
                    <div class="topics-grid">
                        
                        @foreach($service->topics as $groupTitle => $subTopics)
                            <div class="topic-group" @if($loop->last) style="grid-column: 1 / -1;" @endif>
                                
                                <h3>{{ $groupTitle }}</h3>
                                
                                <div class="topic-list" @if($loop->last) style="display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;" @endif>
                                    
                                    @foreach($subTopics as $topic)
                                        <div class="topic-item">
                                            <svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg> 
                                            {{ $topic }}
                                        </div>
                                    @endforeach
                                    
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                @endif

            </div>

            <aside class="course-sidebar reveal">
                <div class="meta-card">
                    
                    @if($service->length)
                    <div class="meta-item">
                        <div class="meta-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Course Length
                        </div>
                        <div class="meta-value">{{ $service->length }}</div>
                    </div>
                    @endif

                    @if($service->audience)
                    <div class="meta-item">
                        <div class="meta-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Target Audience
                        </div>
                        <div class="meta-value">{{ $service->audience }}</div>
                    </div>
                    @endif

                    @if($service->prerequisites)
                    <div class="meta-item">
                        <div class="meta-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Prerequisites
                        </div>
                        <div class="meta-value">{{ $service->prerequisites }}</div>
                    </div>
                    @endif

                    <a href="{{ route('contact') }}?course={{ $service->slug }}" class="sidebar-cta">Request Booking</a>

                </div>
            </aside>

        </div>
    </section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: Course schema — provider/instructor referenced by @id --}}
@php
    $_courseDescription = $service->meta_description
        ?: ($service->short_description ?: 'Instructor-led Agile training class taught by Dr. Kevin Thompson, Ph.D.');

    $_courseInstance = [
        '@type'      => 'CourseInstance',
        'courseMode' => 'onsite',
        'instructor' => ['@id' => url('/') . '/#person'],
    ];
    if (! empty($service->length)) {
        $_courseInstance['courseWorkload'] = $service->length;
    }

    $_course = [
        '@context'         => 'https://schema.org',
        '@type'            => 'Course',
        'name'             => $service->title,
        'description'      => strip_tags($_courseDescription),
        'url'              => url()->current(),
        'provider'         => ['@id' => url('/') . '/#organization'],
        'instructor'       => ['@id' => url('/') . '/#person'],
        'educationalLevel' => 'Professional',
        // Only claim the modes we actually publish an instance for — declaring
        // 'online' with no online hasCourseInstance is a validation mismatch.
        'courseMode'       => ['onsite'],
        'inLanguage'       => 'en-US',
        'hasCourseInstance'=> [$_courseInstance],
        'offers'           => [
            '@type'        => 'Offer',
            // Google only accepts Paid / Free / Subscription here — a free-text
            // value fails Course validation for the whole page.
            'category'     => 'Paid',
            'url'          => url('/contact-us'),
            'availability' => 'https://schema.org/InStock',
        ],
    ];

    // Omit `image` entirely when there is no featured image — a literal null
    // is a validation error, whereas an absent optional property is fine.
    if (! empty($service->featured_image)) {
        $_course['image'] = asset('storage/' . $service->featured_image);
    }

    // The syllabus already lives in the database and renders on the page, but
    // was invisible to search engines. Expose it through the standard Course
    // properties so the markup reflects what the page actually teaches.
    if (! empty($service->topics) && is_array($service->topics)) {
        $_teaches = collect($service->topics)
            ->flatMap(fn ($subTopics, $groupTitle) => array_merge([$groupTitle], (array) $subTopics))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($_teaches) {
            $_course['teaches'] = $_teaches;
        }
    }

    if (! empty($service->prerequisites)) {
        $_course['coursePrerequisites'] = strip_tags($service->prerequisites);
    }

    if (! empty($service->audience)) {
        $_course['audience'] = [
            '@type'            => 'Audience',
            'audienceType'     => strip_tags($service->audience),
        ];
    }

    if (! empty($service->learning_objectives)) {
        $_course['abstract'] = trim(preg_replace('/\s+/', ' ', strip_tags($service->learning_objectives)));
    }

    $_courseJsonLd = json_encode($_course, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_courseJsonLd !!}</script>
@php
    $_trainingCrumbs = json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Agile Training Classes', 'item' => route('training')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $service->title, 'item' => url()->current()],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">{!! $_trainingCrumbs !!}</script>
@endpush
