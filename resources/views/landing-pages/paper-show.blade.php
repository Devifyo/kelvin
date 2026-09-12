@extends('layouts.app')

@section('title', $paper->seoTitle())
@section('meta_description', $paper->seoDescription())
@section('meta_keywords', $paper->meta_keywords ?: 'agile hardware development, ' . strtolower($paper->category?->name ?? 'resource') . ', scrum for hardware, Kevin Thompson')
@section('og_type', $paper->is_book ? 'book' : 'article')
@if($paper->featured_image_url)
    @section('og_image', url($paper->featured_image_url))
    @section('og_image_alt', $paper->title)
@endif

{{-- Breadcrumb structured data (head) --}}
@push('schema')
    @php
        $_crumbs = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Resource Library', 'item' => route('papers')],
                $paper->category
                    ? ['@type' => 'ListItem', 'position' => 3, 'name' => $paper->category->name, 'item' => route('papers', ['category' => $paper->category->slug])]
                    : null,
                ['@type' => 'ListItem', 'position' => $paper->category ? 4 : 3, 'name' => $paper->title, 'item' => url()->current()],
            ])),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $_crumbs !!}</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/frontend/paper-show.css') }}">
@endpush

@section('content')

<section class="resource-hero">
    <div class="header-content reveal">
        <nav class="resource-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('papers') }}">Resource Library</a>
            @if($paper->category)
                <span aria-hidden="true">/</span>
                <a href="{{ route('papers', ['category' => $paper->category->slug]) }}">{{ $paper->category->name }}</a>
            @endif
        </nav>

        <div class="kicker">{{ $paper->category?->name ?? 'Resource' }}</div>
        <h1 class="page-title">{{ $paper->title }}</h1>

        <div class="resource-meta">
            @if($paper->sub_category)<span>{{ $paper->sub_category }}</span>@endif
            <span>{{ $paper->type_label }}@if($paper->file_size_label) &middot; {{ $paper->file_size_label }}@endif</span>
            @if($paper->is_featured)<span class="is-featured">&#9733; Featured</span>@endif
        </div>
    </div>
</section>

<section class="resource-section">
    <div class="resource-grid">

        {{-- MAIN: editable long description (falls back to the card summary) --}}
        <article class="resource-main reveal rv1">
            @if($paper->featured_image_url)
                <img src="{{ $paper->featured_image_url }}" alt="{{ $paper->title }}" class="resource-cover" width="960" height="540" loading="eager" fetchpriority="high" decoding="async">
            @endif

            <h2 class="section-heading">About this <em>{{ $paper->is_book ? 'Book' : ($paper->category ? \Illuminate\Support\Str::singular($paper->category->name) : 'Resource') }}</em></h2>
            <div class="ornament"></div>

            <div class="resource-body">
                {!! $paper->long_description_html !!}
            </div>

            <div class="resource-author">
                <img src="{{ asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}" alt="Dr. Kevin Thompson, Ph.D. — Agile hardware consultant" width="72" height="72" loading="lazy" decoding="async">
                <div>
                    <h4>Dr. Kevin Thompson, Ph.D.</h4>
                    <p>Principal Consultant specializing in Agile hardware development. Dr. Thompson has guided more than 100 enterprise transformations, bridging the gap between hardware engineering and Agile software methodologies. <a href="{{ route('about') }}">About Dr. Thompson &rarr;</a></p>
                </div>
            </div>
        </article>

        {{-- SIDEBAR: the resource itself --}}
        <aside class="resource-aside reveal rv2">
            <div class="resource-card">
                <div class="resource-card-label">{{ $paper->is_book ? 'The Book' : ($paper->is_link ? 'External Resource' : 'Full Document') }}</div>
                <div class="resource-card-icon" aria-hidden="true">
                    @if($paper->is_book)
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    @elseif($paper->is_link)
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    @else
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    @endif
                </div>
                <h3>{{ $paper->title }}</h3>

                <ul class="resource-card-facts">
                    @if($paper->category)<li><span>Category</span><span>{{ $paper->category->name }}</span></li>@endif
                    @if($paper->sub_category)<li><span>Topic</span><span>{{ $paper->sub_category }}</span></li>@endif
                    <li><span>Format</span><span>{{ $paper->is_book ? 'Book' : ($paper->is_link ? 'Website' : $paper->type_label) }}</span></li>
                    @if($paper->file_size_label)<li><span>Size</span><span>{{ $paper->file_size_label }}</span></li>@endif
                    <li><span>Author</span><span>Dr. Kevin Thompson</span></li>
                </ul>

                @if($paper->cta_url)
                    <a href="{{ $paper->cta_url }}" class="btn-resource" target="_blank" rel="noopener{{ $paper->is_document ? '' : ' nofollow' }}">
                        {{ $paper->cta_text }}
                        @if($paper->is_document)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        @else
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        @endif
                    </a>
                    @if($paper->is_document)
                        <a href="{{ $paper->cta_url }}" class="btn-resource-secondary" download>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                            Download {{ strtoupper($paper->file_extension ?? 'file') }}
                        </a>
                        <p class="resource-card-note">Opens in a new tab. The full document is free to read and share.</p>
                    @else
                        <p class="resource-card-note">Opens in a new tab on an external site.</p>
                    @endif
                @else
                    <span class="btn-resource is-pending">{{ $paper->is_document ? 'File Pending' : 'Link Pending' }}</span>
                @endif

                <a href="{{ route('papers') }}" class="resource-back">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                    Back to Resource Library
                </a>
            </div>
        </aside>

    </div>
</section>

@if($related->isNotEmpty())
<section class="related-section">
    <div class="related-wrap reveal rv1">
        <div class="related-head">
            <h2>More from the <em>Resource Library</em></h2>
            <a href="{{ route('papers', ['category' => 'all']) }}" class="resource-back" style="margin-top:0;">View all resources
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
        <div class="related-grid">
            @foreach($related as $item)
                <a href="{{ route('papers.show', $item->slug) }}" class="related-card">
                    <div class="paper-meta">{{ $item->category?->name ?? 'Resource' }}</div>
                    <h3>{{ $item->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($item->plain_summary, 120) }}</p>
                    <span class="related-cta">{{ $item->is_book ? 'About the Book' : 'View Resource' }} &rarr;</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
{{-- Page-level JSON-LD: the resource itself. Type depends on what it is;
     author/publisher always reference the sitewide entities by @id. --}}
@php
    $_type = $paper->is_book ? 'Book' : ($paper->is_link ? 'WebPage' : 'DigitalDocument');
    $_schema = [
        '@context'         => 'https://schema.org',
        '@type'            => $_type,
        'mainEntityOfPage' => url()->current(),
        'url'              => url()->current(),
        'name'             => $paper->title,
        'headline'         => mb_substr($paper->title, 0, 110),
        'description'      => $paper->seoDescription(),
        'image'            => $paper->featured_image_url ? url($paper->featured_image_url) : null,
        'author'           => ['@id' => url('/') . '/#person'],
        'publisher'        => ['@id' => url('/') . '/#organization'],
        'genre'            => $paper->category?->name,
        'keywords'         => $paper->meta_keywords ?: $paper->sub_category,
        'datePublished'    => optional($paper->created_at)->toAtomString(),
        'dateModified'     => optional($paper->updated_at)->toAtomString(),
        'inLanguage'       => 'en-US',
        'isAccessibleForFree' => $paper->is_document ? true : null,
        'isPartOf'         => ['@type' => 'CollectionPage', 'name' => 'Resource Library', 'url' => route('papers')],
    ];

    if ($paper->is_document && $paper->file_url) {
        $_schema['encoding'] = [
            '@type'          => 'MediaObject',
            'contentUrl'     => url($paper->file_url),
            'encodingFormat' => $paper->file_extension === 'pdf' ? 'application/pdf' : null,
            'name'           => $paper->title,
        ];
        $_schema['encoding'] = array_filter($_schema['encoding']);
    }

    if ($paper->is_book && $paper->external_url) {
        $_schema['offers'] = [
            '@type'        => 'Offer',
            'url'          => $paper->external_url,
            'availability' => 'https://schema.org/InStock',
        ];
    }

    if ($paper->is_link && $paper->external_url) {
        $_schema['sameAs'] = $paper->external_url;
    }

    $_resourceJsonLd = json_encode(array_filter($_schema, fn ($v) => $v !== null && $v !== ''), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_resourceJsonLd !!}</script>
@endpush
