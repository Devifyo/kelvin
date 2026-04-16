<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>@yield('title', 'Kevin Thompson Ph.D. Consulting')</title>
    <meta name="title" content="@yield('meta_title', 'Kevin Thompson Ph.D. Consulting | Agile Hardware & Software')">
    <meta name="description" content="@yield('meta_description', 'Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Agile Hardware, Scrum, Embedded Systems, Agile Consulting, Software Engineering')">
    <meta name="author" content="Dr. Kevin Thompson">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="@yield('title', 'Kevin Thompson Ph.D. Consulting')">
    <meta property="og:description" content="@yield('meta_description', 'Expert consulting and training for Agile hardware and software development.')">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="@yield('title', 'Kevin Thompson Ph.D. Consulting')">
    <meta property="twitter:description" content="@yield('meta_description', 'Expert consulting and training for Agile hardware and software development.')">

    @php
        use App\Models\AppSetting;
        use Illuminate\Support\Facades\Storage;
        $_c          = AppSetting::resolvedColors();
        $_favicon    = AppSetting::get('favicon');
        $_faviconUrl = $_favicon ? Storage::disk('public')->url($_favicon) : null;
        $_appIcon    = AppSetting::get('app_icon');
        $_appIconUrl = $_appIcon ? Storage::disk('public')->url($_appIcon) : null;
        $_appName    = AppSetting::get('app_name', AppSetting::DEFAULTS['app_name']);
    @endphp
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