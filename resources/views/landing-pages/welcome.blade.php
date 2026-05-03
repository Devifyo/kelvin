@extends('layouts.app')

@if(!empty($welcomeContent->seo_title))
    @section('title', $welcomeContent->seo_title)
    @section('meta_title', $welcomeContent->seo_title)
@endif

@if(!empty($welcomeContent->seo_description))
    @section('meta_description', $welcomeContent->seo_description)
@endif

@if(!empty($welcomeContent->seo_keywords))
    @section('meta_keywords', $welcomeContent->seo_keywords)
@endif

@push('styles')
    <link rel="stylesheet" href="/css/frontend/home.css">
    
    <style>
        /* ─────────────────────────────────────────
           1. HERO: ELITE EDITORIAL MINIMALISM
           (Overrides the background noise for a high-end look)
        ───────────────────────────────────────── */
        
        #hero { background: var(--slate) !important; }
        .hero-accent-line, .hero-dots, .hero-numeral, .hero-glow { display: none !important; }
        #hero::before { display: none !important; } 

        .elite-kicker {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--copper);
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .elite-kicker::before {
            content: '';
            width: 50px;
            height: 1px;
            background: var(--copper);
        }

        .hero-h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3.5rem, 6vw, 6.5rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
            margin-bottom: 2rem;
        }
        .hero-h1 em {
            color: var(--copper2);
            font-style: italic;
            font-weight: 400;
            display: block;
        }
        .hero-h1 strong {
            color: var(--white);
            font-weight: 600;
            background: none !important;
            -webkit-text-fill-color: initial !important;
            display: block;
        }

        .hero-p, .hero-p2 {
            color: rgba(250, 247, 242, 0.75) !important;
            font-weight: 400 !important;
            font-size: 1.05rem;
            line-height: 1.8;
            max-width: 480px;
        }

        /* Brutalist Hero Buttons */
        .cta-primary {
            border-radius: 0 !important;
            background: var(--copper) !important;
            box-shadow: none !important; 
            letter-spacing: 0.15em;
        }
        .cta-primary:hover {
            background: var(--white) !important;
            color: var(--slate) !important;
            transform: none !important;
        }
        .cta-secondary {
            border-radius: 0 !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .cta-secondary:hover {
            border-color: var(--copper) !important;
            color: var(--white) !important;
            background: rgba(181, 114, 42, 0.05) !important;
        }

        /* Editorial Pain Column */
        .elite-card {
            background: transparent;
            border-left: 1px solid rgba(250, 247, 242, 0.15);
            padding: 0 0 0 3.5rem;
            width: 100%;
            max-width: 460px;
        }
        .elite-card-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            color: var(--white);
            margin-bottom: 2.5rem;
            line-height: 1.2;
            font-weight: 400;
        }
        .elite-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .elite-list li {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            padding: 1.25rem 0;
            border-bottom: 1px solid rgba(250, 247, 242, 0.08);
            color: rgba(250, 247, 242, 0.7);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 0.9rem;
            line-height: 1.6;
            transition: color 0.3s ease;
        }
        .elite-list li:first-child { border-top: 1px solid rgba(250, 247, 242, 0.08); }
        .elite-list li:hover { color: var(--white); }
        
        .elite-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.25rem;
            color: var(--copper);
            font-style: italic;
            line-height: 1;
            padding-top: 0.1rem;
        }
        .elite-foot {
            margin-top: 2.5rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            color: var(--copper2);
            font-style: italic;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .elite-foot::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--copper), transparent);
        }

        @media(max-width: 1100px) { 
            .elite-card { border-left: none; border-top: 1px solid rgba(250, 247, 242, 0.15); padding: 3.5rem 0 0 0; }
        }

        /* ─────────────────────────────────────────
           2. NEW SERVICE HEADINGS (Ties into the Hero)
        ───────────────────────────────────────── */
        .svc-group-title {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.25em !important;
            text-transform: uppercase !important;
            color: var(--copper) !important;
            margin: 0 0 1.5rem 0 !important;
            display: flex !important;
            align-items: center !important;
            gap: 1.25rem !important;
        }
        .svc-group-title::after {
            content: '' !important;
            flex-grow: 1 !important;
            height: 1px !important;
            background: var(--copper) !important;
            opacity: 0.3 !important; /* Clean, subtle solid line instead of fading gradient */
        }

        /* ─────────────────────────────────────────
           3. STICKY BIO COLUMN
        ───────────────────────────────────────── */
        .principal-wrap { align-items: stretch !important; }
        .bio { position: sticky; top: 140px; align-self: start; }
        @media(max-width: 1100px) { .bio { position: relative; top: 0; } }

    </style>
@endpush

@section('content')

    <section id="hero">
        
        <div class="hero-l">
            <div class="elite-kicker anim-up d0">
                {{ $welcomeContent->hero_kicker ?? 'Agile Hardware Consulting' }}
            </div>

            <h1 class="hero-h1 anim-up d1">
                <em>{{ $welcomeContent->hero_h1_em ?? 'Reduce risk.' }}</em>
                <strong>{{ $welcomeContent->hero_h1_strong ?? 'Ship faster.' }}</strong>
            </h1>

            <p class="hero-p anim-up d2">
                {{ $welcomeContent->hero_p1 ?? 'We help hardware-development organizations reduce development risk and shorten time-to-market by applying Agile principles to prototype-driven learning, early system integration, and risk-focused development.' }}
            </p>
            <p class="hero-p2 anim-up d3">
                {{ $welcomeContent->hero_p2 ?? 'We understand how hardware development differs from software development, and how to apply Agile processes to the hardware world.' }}
            </p>

            <div class="cta-row anim-up d4">
                <a href="{{ $welcomeContent->hero_cta_primary_link ?? route('services.training') }}" class="cta-primary">
                    {{ $welcomeContent->hero_cta_primary_text ?? 'Our Services' }}
                </a>
                <a href="{{ $welcomeContent->hero_cta_secondary_link ?? route('contact') }}" class="cta-secondary">
                    {{ $welcomeContent->hero_cta_secondary_text ?? 'Get in Touch' }}
                </a>
            </div>
        </div>

        <div class="hero-r">
            <div class="elite-card anim-right">
                <h3 class="elite-card-title">{{ $welcomeContent->pain_title ?? 'If your organization is experiencing...' }}</h3>

                <ul class="elite-list">
                    @php
                        $painPoints = $welcomeContent && !empty($welcomeContent->pain_list) 
                            ? $welcomeContent->pain_list 
                            : [
                                'Discovery of critical problems too late during system integration.',
                                'Development cycles that are extending significantly beyond projections.',
                                'Late-stage design changes resulting in severe cost overruns.',
                                'Hardware and software engineering teams operating at misaligned velocities.',
                                'Extended design phases occurring prior to the validation of core assumptions.'
                            ];
                    @endphp
                    @foreach($painPoints as $index => $point)
                    <li>
                        <span class="elite-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>

                <div class="elite-foot">{{ $welcomeContent->pain_footer ?? '...we can help.' }}</div>
            </div>
        </div>
    </section>
    
    <div class="strip"></div>

    <section id="principal">
        <div class="principal-wrap">
            
            <div class="bio reveal">
                <div class="kicker">{{ $welcomeContent->principal_kicker ?? 'Our Principal' }}</div>
                <h2 class="section-h">{{ $welcomeContent->principal_h2_name ?? 'Dr. Kevin' }} <em>{{ $welcomeContent->principal_h2_em ?? 'Thompson' }}</em></h2>
                <div class="ornament"></div>

                <p>
                    {{ $welcomeContent->principal_p1 ?? 'Our Principal, Dr. Kevin Thompson, Ph.D. (Physics) is one of the most experienced Agile consultants in the field, having successfully completed more than 100 client engagements.' }}
                </p>
                <p>
                    {{ $welcomeContent->principal_p2 ?? 'Dr. Thompson has helped numerous clients improve their ability to deliver both software and hardware products. He successfully pioneered Agile hardware development and remains a thought leader in the field. He has helped clients develop a variety of hardware products, from laboratory equipment to telecommunications products to jet engines.' }}
                </p>
                <p>
                    {!! $welcomeContent->principal_p3 ?? 'He has written extensively on Agile topics, including in his book, <em>Solutions for Agile Governance in the Enterprise (Sage): Agile Project, Program, and Portfolio Management for Development of Hardware and Software Products.</em>' !!}
                </p>

                <div class="book">
                    <div class="book-img">
                        <img src="{{ $welcomeContent->principal_book_image ?? 'https://m.media-amazon.com/images/I/61+CCARmhVL._SY522_.jpg' }}" alt="{{ $welcomeContent->principal_book_title ?? 'Book Cover' }}">
                    </div>
                    <div class="book-details">
                        <strong>{{ $welcomeContent->principal_book_title ?? 'Solutions for Agile Governance in the Enterprise (Sage)' }}</strong>
                        <p>{{ $welcomeContent->principal_book_desc ?? 'Agile Project, Program, and Portfolio Management for Development of Hardware and Software Products.' }}</p>
                        <a href="{{ $welcomeContent->principal_book_url ?? 'https://www.amazon.com/Solutions-Agile-Governance-Enterprise-SAGE/dp/0578420589' }}"
                            target="_blank" rel="noopener noreferrer" class="book-link">
                            View on Amazon
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="reveal rv2">
                <div class="kicker">What We Offer</div>
                <h2 class="section-h">Consulting &amp; <em>Training Services</em></h2>
                <div class="ornament"></div>
                <p class="svc-desc">
                    We offer a variety of consulting and training services. We can work with all levels at a client, from the hands-on engineers to the C-suite. We take the time to understand the unique needs of each client, and tailor consulting services accordingly.
                </p>

                <div class="svc-group">
                    <h3 class="svc-group-title first">Consulting</h3>
                    <div class="svc-grid">
                        @forelse ($consultingServices ?? [] as $service)
                        <a href="{{ route('services.training',['slug' => $service->slug]) }}" class="svc-item-link" style="text-decoration: none;">
                            <div class="svc-item">
                                <span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="svc-name">{{ $service->title }}</span>
                                <span class="svc-arrow">→</span>
                            </div> 
                        </a>  
                        @empty
                            <p>No consulting services available at this time.</p>
                        @endforelse
                    </div>
                </div>

                <div class="svc-group" style="margin-top: 2.5rem;">
                    <h3 class="svc-group-title">Training</h3>
                    <div class="svc-grid">
                        @forelse ($trainingClasses ?? [] as $training)
                        <a href="{{ route('training',['slug' => $training->slug]) }}" class="svc-item-link" style="text-decoration: none;">
                            <div class="svc-item">
                                <span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="svc-name">{{ $training->title }}</span>
                                <span class="svc-arrow">→</span>
                            </div>
                        </a>
                        @empty
                            <p>No training classes available at this time.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection

@push('scripts')
{{-- Page-level JSON-LD: Homepage structured data --}}
@php
    $_homeJsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'           => 'WebSite',
                '@id'             => url('/') . '#website',
                'url'             => url('/'),
                'name'            => 'Kelvin Enterprise',
                'description'     => $welcomeContent->seo_description ?? 'Kelvin Enterprise delivers expert Agile consulting and on-site training services, specializing in Agile hardware development, embedded systems, and Agile transformation.',
                'publisher'       => ['@id' => url('/') . '#organization'],
                'inLanguage'      => 'en-US',
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => url('/blog') . '?search={search_term_string}'],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type'       => 'Organization',
                '@id'         => url('/') . '#organization',
                'name'        => 'Kelvin Enterprise',
                'url'         => url('/'),
                'founder'     => ['@id' => url('/') . '#person'],
                'areaServed'  => 'US',
                'knowsAbout'  => ['Agile Consulting', 'Agile Training', 'Agile Transformation', 'Hardware Agile', 'Embedded Systems', 'Scrum'],
            ],
            [
                '@type'      => 'Person',
                '@id'        => url('/') . '#person',
                'name'       => 'Kevin Thompson PhD',
                'jobTitle'   => 'Agile Consultant & Founder',
                'worksFor'   => ['@id' => url('/') . '#organization'],
                'knowsAbout' => ['Agile Development', 'Hardware Agile', 'Embedded Systems', 'Scrum', 'Agile Transformation', 'Agile Coaching'],
                'url'        => url('/about'),
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_homeJsonLd !!}</script>
<script>
    document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const t = document.querySelector(a.getAttribute('href'));
        if (t) { e.preventDefault(); closeDrawer(); t.scrollIntoView({ behavior: 'smooth' }); }
    });
    });
</script>
@endpush