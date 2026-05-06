<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    @php
        use App\Models\AppSetting;
        use Illuminate\Support\Facades\Storage;

        // Brand
        $_c          = AppSetting::resolvedColors();
        $_favicon    = AppSetting::get('favicon');
        $_faviconUrl = $_favicon ? Storage::disk('public')->url($_favicon) : null;
        $_appIcon    = AppSetting::get('app_icon');
        $_appIconUrl = $_appIcon ? Storage::disk('public')->url($_appIcon) : null;
        $_appName    = AppSetting::get('app_name');

        // SEO settings (all cached)
        $_titleSuffix    = AppSetting::get('seo_title_suffix', '');
        $_defaultDesc    = AppSetting::get('seo_default_desc')
            ?: 'Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development.';
        $_ogImage        = AppSetting::get('seo_og_image', '');
        $_twitterHandle  = ltrim(AppSetting::get('seo_twitter_handle', ''), '@');
        $_linkedinUrl    = AppSetting::get('seo_linkedin_url', '');
        $_googleVerify   = AppSetting::get('seo_google_verify', '');
        $_bingVerify     = AppSetting::get('seo_bing_verify', '');
        $_ga4Id          = AppSetting::get('seo_ga4_id', '');
        $_gtmId          = AppSetting::get('seo_gtm_id', '');
        $_schemaJobTitle = AppSetting::get('seo_schema_job_title') ?: 'Agile Consultant & Trainer, Ph.D.';
        $_schemaOrgName  = AppSetting::get('seo_schema_org_name')  ?: 'Kevin Thompson Ph.D. Consulting';
        $_sameAs = array_values(array_filter([
            $_linkedinUrl,
            $_twitterHandle ? 'https://x.com/' . $_twitterHandle : '',
        ]));

        $_suffix  = $_titleSuffix ? ' ' . ltrim($_titleSuffix) : '';
        $_pageUrl = url()->current();
    @endphp

    {{-- Google Tag Manager (head) --}}
    @if($_gtmId)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $_gtmId }}');</script>
    @endif

    {{-- Core meta --}}
    <title>@yield('title', $_appName){{ $_suffix }}</title>
    <meta name="description" content="@yield('meta_description', $_defaultDesc)">
    <meta name="keywords"    content="@yield('meta_keywords', 'Agile Hardware, Scrum, Embedded Systems, Agile Consulting, Software Engineering')">
    <meta name="author"      content="Dr. Kevin Thompson">
    <link rel="canonical"    href="{{ $_pageUrl }}" />

    {{-- Search engine verification --}}
    @if($_googleVerify)
    <meta name="google-site-verification" content="{{ $_googleVerify }}" />
    @endif
    @if($_bingVerify)
    <meta name="msvalidate.01" content="{{ $_bingVerify }}" />
    @endif

    {{-- Open Graph --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:url"         content="{{ $_pageUrl }}">
    <meta property="og:site_name"   content="{{ $_appName }}">
    <meta property="og:title"       content="@yield('title', $_appName){{ $_suffix }}">
    <meta property="og:description" content="@yield('meta_description', $_defaultDesc)">
    @if($_ogImage || View::hasSection('og_image'))
    <meta property="og:image"       content="@yield('og_image', $_ogImage)">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    @endif

    {{-- Twitter / X Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:url"         content="{{ $_pageUrl }}">
    <meta name="twitter:title"       content="@yield('title', $_appName){{ $_suffix }}">
    <meta name="twitter:description" content="@yield('meta_description', $_defaultDesc)">
    @if($_twitterHandle)
    <meta name="twitter:site"    content="{{ '@' . $_twitterHandle }}">
    <meta name="twitter:creator" content="{{ '@' . $_twitterHandle }}">
    @endif
    @if($_ogImage || View::hasSection('og_image'))
    <meta name="twitter:image"       content="@yield('og_image', $_ogImage)">
    @endif

    {{-- JSON-LD Structured Data (sitewide canonical entities — pages reference via @id) --}}
    @php
        $_base = url('/');
        $_person = [
            '@type'            => 'Person',
            '@id'              => $_base . '/#person',
            'name'             => 'Kevin Thompson',
            'givenName'        => 'Kevin',
            'familyName'       => 'Thompson',
            'honorificSuffix'  => 'Ph.D.',
            'jobTitle'         => $_schemaJobTitle,
            'description'      => 'Agile hardware development consultant, trainer, and author. Helps R&D and hardware teams adopt Scrum and scale Agile.',
            'url'              => url('/about-kevin-thompson'),
            'worksFor'         => ['@id' => $_base . '/#organization'],
            'knowsAbout'       => [
                'Agile hardware development',
                'Scrum',
                'Kanban',
                'Agile transformation',
                'Agile portfolio management',
                'Embedded systems development',
            ],
        ];
        if (count($_sameAs)) {
            $_person['sameAs'] = $_sameAs;
        }

        $_organization = [
            '@type'         => 'Organization',
            '@id'           => $_base . '/#organization',
            'name'          => $_schemaOrgName,
            'alternateName' => 'Kevin Enterprise',
            'url'           => $_base,
            'founder'       => ['@id' => $_base . '/#person'],
            'knowsAbout'    => [
                'Agile hardware development',
                'Scrum',
                'Kanban',
                'Agile transformation',
                'Embedded systems development',
            ],
            'areaServed'    => 'Worldwide',
        ];
        if ($_ogImage) {
            $_organization['logo'] = [
                '@type' => 'ImageObject',
                'url'   => $_ogImage,
            ];
        }
        if (count($_sameAs)) {
            $_organization['sameAs'] = $_sameAs;
        }

        $_website = [
            '@type'           => 'WebSite',
            '@id'             => $_base . '/#website',
            'url'             => $_base,
            'name'            => $_appName,
            'inLanguage'      => 'en-US',
            'publisher'       => ['@id' => $_base . '/#organization'],
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => url('/agile-insights-blog') . '?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        $_jsonLd = [
            '@context' => 'https://schema.org',
            '@graph'   => [$_person, $_organization, $_website],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($_jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

    @stack('schema')

    {{-- Google Analytics 4 (only if no GTM — GTM handles GA4 via tag) --}}
    @if($_ga4Id && !$_gtmId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $_ga4Id }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $_ga4Id }}');</script>
    @endif
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $_faviconUrl ?? '/apple-touch-icon.png' }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $_faviconUrl ?? '/favicon-32x32.png' }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $_faviconUrl ?? '/favicon-16x16.png' }}">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="{{ $_faviconUrl ?? '/favicon.ico' }}">
    <meta name="theme-color" content="{{ $_c['slate'] }}">

    <link rel="stylesheet" href="/css/frontend/main.css">

    {{-- Dynamic color override — keeps main.css static, just replaces the tokens --}}
    <style>
        :root {
            --slate:       {{ $_c['slate'] }};
            --slate2:      {{ $_c['slate2'] }};
            --slate3:      {{ $_c['slate3'] }};
            --copper:      {{ $_c['copper'] }};
            --copper2:     {{ $_c['copper2'] }};
            --copper3:     {{ $_c['copper3'] }};
            --copper4:     {{ $_c['copper4'] }};
            --copper-dark: {{ $_c['copperDark'] }};
        }
        /* main.css hardcodes the pinned nav color as rgba() — override it here */
        #nav.pinned { background: {{ $_c['slate'] }}f7 !important; }
    </style>

    @stack('styles')
</head>
<body>

    @include('layouts.partials.frontend.header', ['appName' => $_appName, 'appIconUrl' => $_appIconUrl])

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.frontend.footer', ['appName' => $_appName])

    <script>
        /* sticky nav  */
        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('pinned', window.scrollY > 55);
        }, { passive: true });

        /* mobile drawer */
        function toggleDrawer() {
            const b = document.getElementById('burger');
            const d = document.getElementById('drawer');
            b.classList.toggle('x');
            d.classList.toggle('show');
            document.body.style.overflow = d.classList.contains('show') ? 'hidden' : '';
        }
        function closeDrawer() {
            document.getElementById('burger').classList.remove('x');
            document.getElementById('drawer').classList.remove('show');
            document.body.style.overflow = '';
        }

        /* ─────────────────────────────────────────
           GLOBAL SCROLL REVEAL ANIMATION
           Works on any page with class="reveal"
        ───────────────────────────────────────── */
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
    
    @stack('scripts')
<x-alert />
</body>
</html>