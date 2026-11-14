@extends('layouts.app')

{{-- Full-text HTML version of a Resource Library document.
     SEO structure deliberately mirrors blog-show.blade.php: title, description,
     og:type article, article:* metas, BreadcrumbList and an Article-family
     JSON-LD block. The layout owns <title>, canonical and the meta tags. --}}
@section('title', $paper->readSeoTitle())
@section('meta_description', $paper->readSeoDescription())
@section('meta_keywords', $paper->meta_keywords ?: 'agile hardware development, ' . strtolower($paper->category?->name ?? 'resource') . ', scrum for hardware, Kevin Thompson')
@section('og_type', 'article')
@if($paper->featured_image_url)
    @section('og_image', url($paper->featured_image_url))
    @section('og_image_alt', $paper->title)
@endif

{{-- Article Open Graph tags + breadcrumb structured data (head) --}}
@push('schema')
    @if($paper->created_at)<meta property="article:published_time" content="{{ $paper->created_at->toIso8601String() }}">@endif
    @if($paper->updated_at)<meta property="article:modified_time" content="{{ $paper->updated_at->toIso8601String() }}">@endif
    <meta property="article:author" content="Dr. Kevin Thompson">
    @if($paper->category)<meta property="article:section" content="{{ $paper->category->name }}">@endif
    @php
        $_crumbItems = array_values(array_filter([
            ['name' => 'Home', 'item' => url('/')],
            ['name' => 'Resource Library', 'item' => route('papers')],
            $paper->category
                ? ['name' => $paper->category->name, 'item' => route('papers', ['category' => $paper->category->slug])]
                : null,
            ['name' => $paper->title, 'item' => route('papers.show', $paper->slug)],
            ['name' => 'Full Text', 'item' => url()->current()],
        ]));
        $_crumbs = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => array_map(
                fn ($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['item']],
                $_crumbItems,
                array_keys($_crumbItems)
            ),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    @endphp
    <script type="application/ld+json">{!! $_crumbs !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frontend/blog-view.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend/paper-read.css') }}">
@endpush

@section('content')

@php
    $_kind   = $paper->category ? \Illuminate\Support\Str::singular($paper->category->name) : 'Resource';
    $_fileExt = strtoupper($paper->file_extension ?? 'file');
@endphp

<div class="reading-progress-container">
    <div class="reading-progress-bar" id="reading-progress"></div>
</div>

<section class="article-header">
    <div class="header-content reveal">

        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('papers') }}">Resource Library</a>
            @if($paper->category)
                <span>/</span>
                <a href="{{ route('papers', ['category' => $paper->category->slug]) }}">{{ $paper->category->name }}</a>
            @endif
            <span>/</span>
            <a href="{{ route('papers.show', $paper->slug) }}">About this {{ strtolower($_kind) }}</a>
        </nav>

        <h1 class="page-title">{{ $paper->title }}</h1>

        <div class="article-meta-wrapper">
            <img src="{{ asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}" alt="Dr. Kevin Thompson, Ph.D. — Agile hardware consultant" class="meta-avatar" width="48" height="48" loading="lazy" decoding="async">
            <div class="meta-info">
                <strong>Dr. Kevin Thompson</strong>
                <div class="meta-details">
                    <span>{{ $_kind }}</span>
                    @if($paper->updated_at)
                        <span class="dot">&bull;</span>
                        <span>Updated {{ $paper->updated_at->format('F d, Y') }}</span>
                    @endif
                    <span class="dot">&bull;</span>
                    <span>{{ $paper->html_read_minutes }} min read</span>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="article-section">
    {{-- No scroll-reveal here: a full paper is many screens tall, so the 10% visibility
         threshold the reveal observer uses is never met and the text stays invisible. --}}
    <article class="article-wrap">

        @if($paper->featured_image_url)
            <img src="{{ $paper->featured_image_url }}" alt="{{ $paper->title }}" class="article-cover" width="1200" height="630" loading="eager" fetchpriority="high" decoding="async">
        @endif

        <div class="article-body">

            {{-- The original file stays one click away --}}
            @if($paper->file_url)
                <div class="pdf-attachment">
                    <div class="pdf-info">
                        <div class="pdf-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                        <div class="pdf-text">
                            <strong>Original Formatting Available</strong>
                            <span style="text-transform: uppercase;">{{ $_fileExt }}@if($paper->file_size_label) &middot; {{ $paper->file_size_label }}@endif</span>
                        </div>
                    </div>
                    <a href="{{ $paper->file_url }}" download class="btn-download">
                        Download {{ $_fileExt }}
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </a>
                </div>
            @endif

            {{-- FULL TEXT (HTML authored in the admin). Server-rendered so crawlers
                 and AI fetchers read it in the raw response. Any <h1> in the
                 authored content is demoted to <h2> by the model accessor so the
                 title above stays the page's only H1. --}}
            <div class="rich-content">
                {!! $paper->html_content_html !!}
            </div>

            <div class="article-footer">

                <div class="article-actions">
                    <div class="article-tags">
                        @if($paper->meta_keywords)
                            @foreach(explode(',', $paper->meta_keywords) as $keyword)
                                @if(trim($keyword) !== '')
                                    <span class="tag">{{ trim($keyword) }}</span>
                                @endif
                            @endforeach
                        @else
                            <span class="tag">{{ $paper->category?->name ?? 'Resource Library' }}</span>
                            @if($paper->sub_category)<span class="tag">{{ $paper->sub_category }}</span>@endif
                        @endif
                    </div>

                    <div class="share-bottom">
                        <span>Share:</span>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($paper->title) }}" target="_blank" rel="noopener" class="share-btn" aria-label="Share on LinkedIn" title="LinkedIn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($paper->title) }}" target="_blank" rel="noopener" class="share-btn" aria-label="Share on Twitter" title="Twitter">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        </a>
                        <a href="mailto:?subject={{ rawurlencode($paper->title) }}&body={{ rawurlencode('Check out this ' . strtolower($_kind) . ': ' . request()->url()) }}" class="share-btn" aria-label="Share via Email" title="Email">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </a>
                    </div>
                </div>

                <div class="read-footer-links">
                    <a href="{{ route('papers.show', $paper->slug) }}" class="read-back">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                        About this {{ strtolower($_kind) }}
                    </a>
                    @if($paper->file_url)
                        <a href="{{ $paper->file_url }}" download class="read-back">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                            Download {{ $_fileExt }}
                        </a>
                    @endif
                    <a href="{{ route('papers') }}" class="read-back">
                        Back to Resource Library
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <div class="author-bio-box">
                    <img src="{{ asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}" alt="Dr. Kevin Thompson, Ph.D. — Agile hardware consultant" width="80" height="80" loading="lazy" decoding="async">
                    <div class="author-bio-text">
                        <h4>Dr. Kevin Thompson, Ph.D.</h4>
                        <p>Principal Consultant specializing in Agile hardware development. Dr. Thompson has successfully guided over 100 enterprise transformations, bridging the gap between hardware engineering and Agile software methodologies. <a href="{{ route('about') }}">About Dr. Thompson &rarr;</a></p>
                    </div>
                </div>

            </div>

        </div>
    </article>
</section>

@if($related->isNotEmpty())
<section class="read-related">
    <div class="read-related-wrap">
        <h2>More from the <em>Resource Library</em></h2>
        <div class="read-related-grid">
            @foreach($related as $item)
                <a href="{{ route('papers.show', $item->slug) }}" class="read-related-card">
                    <div class="read-related-meta">{{ $item->category?->name ?? 'Resource' }}</div>
                    <h3>{{ $item->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($item->plain_summary, 120) }}</p>
                    <span class="read-related-cta">{{ $item->is_book ? 'About the Book' : 'View Resource' }} &rarr;</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
{{-- Page-level JSON-LD: the paper as a ScholarlyArticle. Author / publisher are
     referenced by @id from the sitewide graph; the PDF is attached as
     associatedMedia so machines can tie the web text to the original file. --}}
@php
    $_readJsonLd = json_encode(array_filter([
        '@context'            => 'https://schema.org',
        '@type'               => 'ScholarlyArticle',
        'mainEntityOfPage'    => url()->current(),
        'url'                 => url()->current(),
        'headline'            => mb_substr($paper->title, 0, 110),
        'name'                => $paper->title,
        'description'         => $paper->readSeoDescription(),
        'image'               => $paper->featured_image_url ? url($paper->featured_image_url) : null,
        'datePublished'       => optional($paper->created_at)->toAtomString(),
        'dateModified'        => optional($paper->updated_at)->toAtomString(),
        'author'              => ['@id' => url('/') . '/#person'],
        'publisher'           => ['@id' => url('/') . '/#organization'],
        'articleSection'      => $paper->category?->name,
        'keywords'            => $paper->meta_keywords ?: $paper->sub_category,
        'wordCount'           => $paper->html_word_count,
        'inLanguage'          => 'en-US',
        'isAccessibleForFree' => true,
        'isPartOf'            => ['@type' => 'CollectionPage', 'name' => 'Resource Library', 'url' => route('papers')],
        'associatedMedia'     => $paper->file_url ? array_filter([
            '@type'          => 'MediaObject',
            'contentUrl'     => url($paper->file_url),
            'encodingFormat' => $paper->file_extension === 'pdf' ? 'application/pdf' : null,
            'name'           => $paper->title,
        ]) : null,
    ], fn ($v) => $v !== null && $v !== ''), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_HEX_TAG);
    // JSON_HEX_TAG: the description is taken from the paper's own text, which may
    // legitimately mention markup such as </script>; it must not end this block.
@endphp
<script type="application/ld+json">{!! $_readJsonLd !!}</script>

<script>
// Reading progress bar (the layout already handles .reveal animations)
(function () {
    const bar = document.getElementById('reading-progress');
    if (!bar) return;
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        bar.style.width = (height > 0 ? (winScroll / height) * 100 : 0) + '%';
    }, { passive: true });
})();
</script>
@endpush
