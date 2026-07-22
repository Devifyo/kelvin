{{--
    Public page hero header — copy is managed from Admin → Page Headers.

    Usage:  <x-page-header page="blog" />

    The markup is intentionally identical to what each page's own stylesheet
    already targets (.page-header / .header-content / .kicker / .page-title /
    .page-subtitle), so no page CSS had to change. Per-page class differences
    live in PageHeader::PAGES.
--}}
@props(['page'])

@php
    $header = \App\Models\PageHeader::for($page);
    $meta   = \App\Models\PageHeader::meta($page);
@endphp

<section class="page-header">
    <div class="header-content reveal">
        @if(filled($header->kicker))
            <div class="{{ $meta['kicker_class'] ?? 'kicker' }}"{!! !empty($meta['kicker_style']) ? ' style="'.e($meta['kicker_style']).'"' : '' !!}>{{ $header->kicker }}</div>
        @endif

        <h1 class="page-title">{{ $header->title_regular }}@if(filled($header->title_em)) <em>{{ $header->title_em }}</em>@endif</h1>

        @if(filled($header->subtitle))
            <p class="page-subtitle">{{ $header->subtitle }}</p>
        @endif
    </div>
</section>
