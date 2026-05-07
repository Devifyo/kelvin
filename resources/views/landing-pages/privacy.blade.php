@extends('layouts.app')

@if(!empty($privacyContent->seo_title))
    @section('title', $privacyContent->seo_title)
    @section('meta_title', $privacyContent->seo_title)
@endif

@if(!empty($privacyContent->seo_description))
    @section('meta_description', $privacyContent->seo_description)
@endif

@if(!empty($privacyContent->seo_keywords))
    @section('meta_keywords', $privacyContent->seo_keywords)
@endif

@push('styles')
    <link rel="stylesheet" href="/css/frontend/about.css">
    <link rel="stylesheet" href="/css/frontend/legal.css">
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker-small" style="color:var(--copper2);">{{ $privacyContent->header_kicker ?? 'Legal' }}</div>
        <h1 class="page-title">{{ $privacyContent->header_h1_regular ?? 'Privacy' }} <em>{{ $privacyContent->header_h1_em ?? 'Policy' }}</em></h1>
    </div>
</section>

<div class="strip"></div>

<section class="legal-section">
    <div class="legal-wrap reveal">
        @if(!empty($privacyContent->last_updated))
            <span class="legal-meta">{{ $privacyContent->last_updated }}</span>
        @endif

        <div class="legal-body">
            @if(!empty($privacyContent->content))
                {!! $privacyContent->content !!}
            @else
                <div class="legal-empty">
                    The Privacy Policy has not been published yet. An administrator can add the content from the admin panel.
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

@push('scripts')
@php
    $_privacyJsonLd = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'WebPage',
        'name'        => $privacyContent->seo_title ?? 'Privacy Policy',
        'description' => $privacyContent->seo_description ?? 'Privacy policy describing how visitor data is collected and used.',
        'url'         => url()->current(),
        'publisher'   => ['@id' => url('/') . '/#organization'],
        'inLanguage'  => 'en-US',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_privacyJsonLd !!}</script>
@endpush
