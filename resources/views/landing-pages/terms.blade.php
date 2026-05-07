@extends('layouts.app')

@if(!empty($termsContent->seo_title))
    @section('title', $termsContent->seo_title)
    @section('meta_title', $termsContent->seo_title)
@endif

@if(!empty($termsContent->seo_description))
    @section('meta_description', $termsContent->seo_description)
@endif

@if(!empty($termsContent->seo_keywords))
    @section('meta_keywords', $termsContent->seo_keywords)
@endif

@push('styles')
    <link rel="stylesheet" href="/css/frontend/about.css">
    <link rel="stylesheet" href="/css/frontend/legal.css">
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker-small" style="color:var(--copper2);">{{ $termsContent->header_kicker ?? 'Legal' }}</div>
        <h1 class="page-title">{{ $termsContent->header_h1_regular ?? 'Terms &' }} <em>{{ $termsContent->header_h1_em ?? 'Conditions' }}</em></h1>
    </div>
</section>

<div class="strip"></div>

<section class="legal-section">
    <div class="legal-wrap reveal">
        @if(!empty($termsContent->last_updated))
            <span class="legal-meta">{{ $termsContent->last_updated }}</span>
        @endif

        <div class="legal-body">
            @if(!empty($termsContent->content))
                {!! $termsContent->content !!}
            @else
                <div class="legal-empty">
                    The Terms &amp; Conditions have not been published yet. An administrator can add the content from the admin panel.
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

@push('scripts')
@php
    $_termsJsonLd = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'WebPage',
        'name'        => $termsContent->seo_title ?? 'Terms & Conditions',
        'description' => $termsContent->seo_description ?? 'Terms and conditions governing use of this website.',
        'url'         => url()->current(),
        'publisher'   => ['@id' => url('/') . '/#organization'],
        'inLanguage'  => 'en-US',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_termsJsonLd !!}</script>
@endpush
