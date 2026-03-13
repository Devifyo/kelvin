<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Kevin Thompson Ph.D. Consulting</title>
    <meta name="title" content="Kevin Thompson Ph.D. Consulting | Agile Hardware & Software">
    <meta name="description" content="Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development.">
    <meta name="keywords" content="Agile Hardware, Scrum, Embedded Systems, Agile Consulting, Software Engineering">
    <meta name="author" content="Dr. Kevin Thompson">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Kevin Thompson Ph.D. Consulting">
    <meta property="og:description" content="Expert consulting and training for Agile hardware and software development.">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Kevin Thompson Ph.D. Consulting">
    <meta property="twitter:description" content="Expert consulting and training for Agile hardware and software development.">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico">

    <meta name="theme-color" content="#1a2332">

    <link rel="stylesheet" href="/css/frontend/main.css">
    
    @stack('styles')
</head>
<body>

    @include('layouts.partials.frontend.header')

    <main>
      {{-- @if(request()->Is('/') || request()->Is('about-kevin-thompson') || request()->Is('agile-training-classes') || request()->Is('agile-consulting-services') ) --}}
        @yield('content')
      {{-- @else --}}
       {{-- <h1 class="page-title">{{ $title ?? 'Page Title' }}</h1>
      @endif
    </main> --}}

    @include('layouts.partials.frontend.footer')

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

</body>
</html>