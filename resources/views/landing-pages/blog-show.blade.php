@extends('layouts.app')

{{-- Dynamically inject SEO Meta Tags if you have them in your layout --}}
@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('og_type', 'article')
@if($post->featured_image_url)
    @section('og_image', url($post->featured_image_url))
    @section('og_image_alt', $post->title)
@endif

{{-- Article Open Graph tags + breadcrumb structured data (head) --}}
@push('schema')
    @if($post->published_at)<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">@endif
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name ?? 'Dr. Kevin Thompson' }}">
    @if($post->category)<meta property="article:section" content="{{ $post->category->name }}">@endif
    @php
        $_crumbs = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Agile Insights Blog', 'item' => route('blog')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => url()->current()],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $_crumbs !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="/css/frontend/blog-view.css">
@endpush

@section('content')

<div class="reading-progress-container">
    <div class="reading-progress-bar" id="reading-progress"></div>
</div>

<section class="article-header">
    <div class="header-content reveal">
        
        <div class="breadcrumb">
            <a href="{{ route('blog') }}">Insights</a> 
            <span>/</span> 
            {{ $post->category ? $post->category->name : 'Uncategorized' }}
        </div>
        
        <h1 class="page-title">{{ $post->title }}</h1>
        
        <div class="article-meta-wrapper">
            <img src="{{ asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}" alt="{{ $post->author->name ?? 'Dr. Kevin Thompson, Ph.D. — Agile hardware consultant' }}" class="meta-avatar" width="48" height="48" loading="lazy" decoding="async">
            <div class="meta-info">
                <strong>{{ $post->author->name ?? 'Dr. Kevin Thompson' }}</strong>
                <div class="meta-details">
                    <span>{{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
                    <span class="dot">&bull;</span>
                    {{-- Dynamic read time calculation based on word count --}}
                    <span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} min read</span>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="article-section">
    <article class="article-wrap reveal rv1">
        
        {{-- DYNAMIC COVER IMAGE --}}
        @if($post->featured_image_url)
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="article-cover" width="1200" height="630" loading="eager" fetchpriority="high" decoding="async">
        @endif

        <div class="article-body">
            
            {{-- DYNAMIC PDF ATTACHMENT --}}
            @if($post->has_attachment)
                <div class="pdf-attachment">
                    <div class="pdf-info">
                        <div class="pdf-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <div class="pdf-text">
                            <strong>Original Formatting Available</strong>
                            <span style="text-transform: uppercase;">{{ str_replace('application/', '', $post->attachment_mime_type) }} Download</span>
                        </div>
                    </div>
                    <a href="{{ $post->attachment_url }}" target="_blank" download class="btn-download">
                        Download File
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </a>
                </div>
            @endif

            {{-- DYNAMIC CONTENT (Unescaped HTML from TinyMCE).
                 Demote any <h1> inside authored content to <h2> so there is only
                 one H1 on the page (the article title rendered above). --}}
            <div class="rich-content">
                {!! preg_replace('#<(/?)h1(\s[^>]*)?>#i', '<$1h2$2>', $post->content) !!}
            </div>

            <div class="article-footer">
                
                <div class="article-actions">
                    {{-- DYNAMIC KEYWORDS / TAGS --}}
                    <div class="article-tags">
                        @if($post->meta_keywords)
                            @foreach(explode(',', $post->meta_keywords) as $keyword)
                                @if(trim($keyword) !== '')
                                    <span class="tag">{{ trim($keyword) }}</span>
                                @endif
                            @endforeach
                        @else
                            <span class="tag">{{ $post->category ? $post->category->name : 'Insights' }}</span>
                        @endif
                    </div>

                    {{-- DYNAMIC SHARE LINKS --}}
                    <div class="share-bottom">
                        <span>Share:</span>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="share-btn" aria-label="Share on LinkedIn" title="LinkedIn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn" aria-label="Share on Twitter" title="Twitter">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        </a>
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body=Check out this article: {{ urlencode(request()->url()) }}" class="share-btn" aria-label="Share via Email" title="Email">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </a>
                    </div>
                </div>

                <div class="author-bio-box">
                    <img src="{{ asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}" alt="Dr. Kevin Thompson, Ph.D. — Agile hardware consultant" width="80" height="80" loading="lazy" decoding="async">
                    <div class="author-bio-text">
                        <h4>Dr. Kevin Thompson, Ph.D.</h4>
                        <p>Principal Consultant specializing in Agile hardware development. Dr. Thompson has successfully guided over 100 enterprise transformations, bridging the gap between hardware engineering and Agile software methodologies.</p>
                    </div>
                </div>

            </div>
            
        </div>
    </article>
</section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: Article — author/publisher referenced by @id from layout --}}
@php
    $_articleDate = $post->published_at ?: $post->created_at;
    $_articleJsonLd = json_encode(array_filter([
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'mainEntityOfPage' => url()->current(),
        'headline'         => mb_substr($post->title, 0, 110),
        'description'      => $post->meta_description ?: $post->excerpt,
        'image'            => $post->featured_image_url ? url($post->featured_image_url) : null,
        'datePublished'    => optional($_articleDate)->toAtomString(),
        'dateModified'     => optional($post->updated_at)->toAtomString(),
        'author'           => ['@id' => url('/') . '/#person'],
        'publisher'        => ['@id' => url('/') . '/#organization'],
        'articleSection'   => optional($post->category)->name,
        'wordCount'        => str_word_count(strip_tags($post->content)),
        'inLanguage'       => 'en-US',
        'url'              => url()->current(),
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_articleJsonLd !!}</script>

<script>
// Prevent multiple observer declarations via IIFE
(function() {
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('in');
          revealObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
})();

// Reading Progress Bar Logic
window.addEventListener('scroll', () => {
    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
    document.getElementById('reading-progress').style.width = scrolled + "%";
});
</script>
@endpush