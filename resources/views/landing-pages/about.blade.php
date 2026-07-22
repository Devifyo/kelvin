@extends('layouts.app')

@if(!empty($aboutContent->seo_title))
    @section('title', $aboutContent->seo_title)
    @section('meta_title', $aboutContent->seo_title)
@endif

@if(!empty($aboutContent->seo_description))
    @section('meta_description', $aboutContent->seo_description)
@endif

@if(!empty($aboutContent->seo_keywords))
    @section('meta_keywords', $aboutContent->seo_keywords)
@endif

@push('styles')
    <link rel="stylesheet" href="/css/frontend/about.css">
@endpush

@section('content')

<x-page-header page="about" />

<div class="strip"></div>

<section class="content-section">
    <div class="about-grid">
        
        <aside class="about-sidebar reveal">
            <figure class="profile-img-wrap" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                <link itemprop="url" href="{{ $aboutContent->profile_image ?? asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}">
                <meta itemprop="width" content="320">
                <meta itemprop="height" content="400">
                <img
                    src="{{ $aboutContent->profile_image ?? asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}"
                    alt="Portrait of Dr. Kevin Thompson, Ph.D. — Agile hardware development consultant, trainer, and author"
                    title="Dr. Kevin Thompson, Ph.D. — Agile Hardware Consultant"
                    width="320" height="400"
                    loading="eager" fetchpriority="high" decoding="async"
                    itemprop="contentUrl">
                <figcaption itemprop="caption" style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">Dr. Kevin Thompson, Ph.D. — Principal Agile consultant specializing in hardware development, Scrum at scale, and Agile transformation.</figcaption>
            </figure>
            
            <div>
                <div class="kicker-small">{{ $aboutContent->sidebar_kicker ?? 'Education & Certifications' }}</div>
                <div class="cred-list">
                    @if(!empty($aboutContent->education_list) && is_array($aboutContent->education_list))
                        @foreach($aboutContent->education_list as $item)
                        <div class="cred-item">
                            <strong>{{ $item['title'] ?? '' }}</strong>
                            {!! nl2br(e($item['details'] ?? '')) !!}<span style="display:none;"></span>
                        </div>
                        @endforeach
                    @else
                        <div class="cred-item">
                            <strong>Ph.D. & B.S.</strong>
                            Physics from Princeton University<br>
                            Physics from Santa Clara University<span style="display:none;"></span>
                        </div>
                        <div class="cred-item">
                            <strong>PMP</strong>
                            Project Management Professional from the Project Management Institute<span style="display:none;"></span>
                        </div>
                        <div class="cred-item">
                            <strong>CSM & CSP</strong>
                            Certified Scrum Master and Certified Scrum Professional from the Scrum Alliance<span style="display:none;"></span>
                        </div>
                    @endif
                </div>
            </div>
        </aside>

        <article class="about-body reveal rv1">
            
            <p>
                {{ $aboutContent->intro_text ?? 'Dr. Kevin Thompson obtained his B.S. in Physics from Santa Clara University, and his Ph.D. in Physics from Princeton University. During and after his years at Princeton, Dr. Thompson conducted research at both the Lawrence Livermore National Laboratory and NASA Ames Research Center’s Space Sciences Division, focusing primarily on astrophysics and computational fluid dynamics.' }}<span style="display:none;"></span>
            </p>

            <h2>{{ $aboutContent->section_1_h2_regular ?? 'The Transition to' }} <em>{{ $aboutContent->section_1_h2_em ?? 'Software & Agile' }}</em></h2>
            <div class="body-ornament"></div>

            <p>
                {{ $aboutContent->section_1_p1 ?? 'He followed his career in science with a second career in software engineering, where he worked for a variety of companies. Dr. Thompson exited software engineering for software project management, as the PMO manager for StarCite. There he learned that classic project planning, applied to software development, produced schedules that were more myth than reality.' }}<span style="display:none;"></span>
            </p>

            <p>
                {{ $aboutContent->section_1_p2 ?? 'When the company’s COO announced that the company needed to be more Agile in our software development, Dr. Thompson pioneered the Scrum process and filled the Scrum Master role for three concurrent engineering teams. The results were striking. Visibility into status of work improved tremendously. Slippages were caught much earlier, when there was still time to develop plans for dealing with them.' }}<span style="display:none;"></span> 
            </p>

            <div class="highlight-box">
                {{ $aboutContent->highlight_quote ?? '"The simple-seeming ability to ship a new product, which had eluded the company for years, suddenly became a reality."' }}
            </div>

            <p>
                {{ $aboutContent->section_1_p3 ?? 'After layoffs struck the company in 2008, Dr. Thompson pursued and obtained three certifications: Project Management Professional (PMP) from the Project Management Institute; and Certified Scrum Master (CSM) and Certified Scrum Professional (CSP) from the Scrum Alliance.' }}<span style="display:none;"></span>
            </p>

            <h2>{{ $aboutContent->section_2_h2_regular ?? 'Expanding' }} <em>{{ $aboutContent->section_2_h2_em ?? 'Agile Horizons' }}</em></h2>
            <div class="body-ornament"></div>

            <p>
                {{ $aboutContent->section_2_p1 ?? 'Dr. Thompson was most recently Chief Scientist at Cprime, an Agile consulting and training company. He joined Cprime as the first in-house Agile expert, where his role was to provide the expertise and content to make possible the company’s expansion into that market.' }}<span style="display:none;"></span> 
            </p>

            <p>
                {{ $aboutContent->section_2_p2 ?? 'Over the years, Dr. Thompson developed several key classes. These included a “practical Scrum” class (one each for software and hardware development), Kanban, Agile Program Management, Agile Portfolio Management, Advanced Product Owner, and a PMI Agile Certified Practitioner exam prep class. In addition to developing classes, Dr. Thompson also wrote a number of case studies, white papers, and blog posts for the company’s website, and delivered training and consulting engagements to numerous clients.' }}<span style="display:none;"></span>
            </p>

            <p>
                {{ $aboutContent->section_2_p3 ?? 'In 2019, Dr. Thompson resigned his position at Cprime to pursue a career as an independent consultant.' }}<span style="display:none;"></span>
            </p>

        </article>

    </div>
</section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: About page references the canonical Person/Organization
     via @id (defined sitewide in layouts/app.blade.php). --}}
@php
    $_aboutJsonLd = json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'AboutPage',
        'name'       => $aboutContent->seo_title ?? 'About Kevin Thompson, Ph.D.',
        'description'=> $aboutContent->seo_description ?? 'Learn about Dr. Kevin Thompson — Agile hardware development consultant, trainer, and author specializing in Scrum, embedded systems, and Agile transformation.',
        'url'        => url()->current(),
        'publisher'  => ['@id' => url('/') . '/#organization'],
        'mainEntity' => ['@id' => url('/') . '/#person'],
        'inLanguage' => 'en-US',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_aboutJsonLd !!}</script>

@endpush
