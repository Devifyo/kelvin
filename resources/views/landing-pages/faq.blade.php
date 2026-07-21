@extends('layouts.app')

@section('title', 'Frequently Asked Questions — Agile Hardware Consulting & Training | Kevin Thompson, Ph.D.')
@section('meta_description', 'Answers to common Agile, Scrum, hardware development, and consulting questions — from applying Scrum to hardware and regulated products to how engagements, training, and estimation work.')
@section('meta_keywords', 'agile hardware faq, scrum for hardware, agile scrum questions, hardware development scrum, agile consulting questions, Kevin Thompson')
@section('og_type', 'website')

@php
    // FAQ content is managed in the database (Admin → FAQ Manager) under page 'faq'.
    // Sections and their active questions are cached; the admin clears the cache on edit.
    // Original import source: resources/views/landing-pages/partials/faq-data.php
    // (generated from "Frequently Asked Questions - v2.docx") seeded via FaqPageSeeder.
    $faqSections = [];
    $__usedAnchors = [];
    foreach (\App\Models\FaqSection::forPage('faq') as $__sec) {
        $__questions = [];
        foreach ($__sec->activeFaqs as $__f) {
            $anchor = \Illuminate\Support\Str::slug($__f->question) ?: 'q-'.$__f->id;
            $base = $anchor; $n = 2;
            while (in_array($anchor, $__usedAnchors, true)) { $anchor = $base.'-'.$n; $n++; }
            $__usedAnchors[] = $anchor;
            $__questions[] = ['id' => $anchor, 'q' => $__f->question, 'a' => $__f->answer];
        }
        if (empty($__questions)) { continue; }
        $faqSections[] = [
            'id'        => $__sec->key,
            'title'     => trim(($__sec->title ?? '').' '.($__sec->title_em ?? '')) ?: $__sec->name,
            'questions' => $__questions,
        ];
    }
    $faqTotal = collect($faqSections)->sum(fn ($s) => count($s['questions']));
@endphp

@push('styles')
<style>
    /* ── Page header ─────────────────────────────────────── */
    .faq-page-header { background: var(--slate); padding: 11rem 4.5rem 5rem; text-align: center; position: relative; overflow: hidden; }
    .faq-page-header::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%); }
    .faq-page-header .inner { max-width: 860px; margin: 0 auto; position: relative; z-index: 1; }
    .faq-page-header .kicker { display:inline-flex; align-items:center; gap:.75rem; font-family:-apple-system,sans-serif; font-size:.75rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:var(--copper3); margin-bottom:1.25rem; }
    .faq-page-header .kicker::before, .faq-page-header .kicker::after { content:''; width:30px; height:1px; background:var(--copper); }
    .faq-page-header h1 { font-family:'Cormorant Garamond',serif; font-size:clamp(2.6rem,5vw,4rem); font-weight:400; color:#fff; line-height:1.1; margin-bottom:1.4rem; }
    .faq-page-header h1 em { font-style:italic; color:var(--copper3); }
    .faq-page-header p { font-family:-apple-system,sans-serif; font-size:1.05rem; color:rgba(250,247,242,.72); line-height:1.8; font-weight:300; max-width:660px; margin:0 auto; }

    /* ── Layout shell ────────────────────────────────────── */
    .faq-shell { background: var(--ivory); padding: 4.5rem 4.5rem 6rem; }
    .faq-wrap { max-width: 1160px; margin: 0 auto; display: grid; grid-template-columns: 300px 1fr; gap: 3.5rem; align-items: start; }

    /* ── Table of contents ───────────────────────────────── */
    .faq-toc { position: sticky; top: 6.5rem; background: var(--white); border: 1px solid var(--ivory3); border-radius: 4px; box-shadow: var(--card-shadow); overflow: hidden; }
    .faq-toc-head { display:none; width:100%; align-items:center; justify-content:space-between; gap:1rem; padding:1.1rem 1.4rem; font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--slate); background:var(--ivory2); }
    .faq-toc-head .chev { width:16px; height:16px; color:var(--copper); transition:transform .3s; }
    .faq-toc-body { padding: 1.4rem 1.4rem 1.6rem; max-height: calc(100vh - 9rem); overflow-y: auto; }
    .faq-toc-title { font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--copper); margin-bottom:1.1rem; }
    .faq-toc-group { margin-bottom: 1.5rem; }
    .faq-toc-group:last-child { margin-bottom: 0; }
    .faq-toc-group > h3 { font-family:'Cormorant Garamond',serif; font-size:1.18rem; font-weight:600; color:var(--slate); line-height:1.25; margin-bottom:.6rem; }
    .faq-toc-group ul { list-style:none; margin:0; padding:0; border-left:1px solid var(--ivory3); }
    .faq-toc-group li { margin:0; }
    .faq-toc-group li a { display:block; padding:.4rem .9rem; font-family:-apple-system,sans-serif; font-size:.85rem; line-height:1.45; color:var(--body-text); border-left:2px solid transparent; margin-left:-1px; transition:color .2s, border-color .2s, background .2s; }
    .faq-toc-group li a:hover { color:var(--copper); }
    .faq-toc-group li a.is-active { color:var(--a11y-copper); font-weight:600; border-left-color:var(--copper); background:var(--ivory2); }

    /* ── FAQ column ──────────────────────────────────────── */
    .faq-intro { display:none; }
    .faq-section { margin-bottom: 3.25rem; scroll-margin-top: 6.5rem; }
    .faq-section:last-child { margin-bottom: 0; }
    .faq-section-head { margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 2px solid var(--ivory3); }
    .faq-section-head .num { font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--copper); display:block; margin-bottom:.5rem; }
    .faq-section-head h2 { font-family:'Cormorant Garamond',serif; font-size:clamp(1.8rem,3.2vw,2.4rem); font-weight:600; color:var(--slate); line-height:1.15; }

    /* ── Accordion item ──────────────────────────────────── */
    .faq-item { border-bottom: 1px solid var(--ivory3); scroll-margin-top: 6.5rem; }
    .faq-item:first-child { border-top: 1px solid var(--ivory3); }
    .faq-q { list-style:none; width:100%; text-align:left; cursor:pointer; user-select:none; display:flex; align-items:flex-start; justify-content:space-between; gap:1.25rem; padding:1.35rem .35rem; font-family:-apple-system,sans-serif; font-size:1.02rem; font-weight:600; line-height:1.45; color:var(--slate); transition:color .25s; }
    .faq-q::-webkit-details-marker { display:none; }
    .faq-q:hover, .faq-item.is-open .faq-q { color:var(--copper); }
    .faq-q:focus-visible { outline:2px solid var(--copper); outline-offset:2px; border-radius:3px; }
    .faq-ic { flex-shrink:0; width:20px; height:20px; margin-top:.15rem; color:var(--copper); transition:transform .35s cubic-bezier(.4,0,.2,1); }
    .faq-item.is-open .faq-ic { transform:rotate(45deg); }

    /* smooth expand/collapse via max-height */
    .faq-panel { max-height:0; overflow:hidden; transition:max-height .4s cubic-bezier(.4,0,.2,1); }
    .faq-item.is-open .faq-panel { /* max-height set inline by JS */ }
    .faq-answer-scroll { max-height:600px; overflow-y:auto; padding:.25rem 1.4rem 1.8rem .35rem; }
    /* If the answer is short, the scroll box simply hugs the content (max-height is a ceiling). */
    .faq-answer-scroll.is-scrollable { border-left:2px solid var(--ivory3); padding-left:1.25rem; }
    .faq-answer-scroll::-webkit-scrollbar { width:6px; }
    .faq-answer-scroll::-webkit-scrollbar-thumb { background:var(--ivory3); border-radius:3px; }
    .faq-answer-scroll::-webkit-scrollbar-thumb:hover { background:var(--copper3); }

    /* ── Rich answer typography ──────────────────────────── */
    /* Matches the reading style of the About page (.about-body p): the site's
       Palatino/Georgia body serif at a light weight with generous line-height. */
    .faq-answer { font-family:'Palatino Linotype','Book Antiqua',Palatino,Georgia,'Times New Roman',serif; font-size:1.1rem; line-height:1.9; color:var(--charcoal); font-weight:300; }
    .faq-answer > *:first-child { margin-top:0; }
    .faq-answer p { margin:0 0 1.25rem; }
    .faq-answer strong { color:var(--slate); font-weight:700; }
    .faq-answer em { font-style:italic; }
    .faq-answer a { color:var(--a11y-copper); text-decoration:underline; text-underline-offset:2px; }
    .faq-answer a:hover { color:var(--copper); }
    .faq-answer h1, .faq-answer h2, .faq-answer h3, .faq-answer h4 { font-family:-apple-system,sans-serif; font-weight:700; color:var(--slate); line-height:1.3; margin:1.5rem 0 .75rem; }
    .faq-answer h3 { font-size:1.05rem; }
    .faq-answer h4 { font-size:.95rem; }
    .faq-answer ul, .faq-answer ol { margin:0 0 1rem; padding-left:1.5rem; }
    .faq-answer ul { list-style:disc; }
    .faq-answer ol { list-style:decimal; }
    .faq-answer li { margin:.3rem 0; padding-left:.25rem; }
    .faq-answer li > p { margin:0 0 .4rem; }
    .faq-answer li > ul, .faq-answer li > ol { margin:.4rem 0 .6rem; }
    .faq-answer blockquote { border-left:3px solid var(--copper3); padding-left:1.1rem; margin:0 0 1rem; color:var(--body-text); font-style:italic; }
    .faq-answer table { width:100%; border-collapse:collapse; margin:0 0 1rem; font-size:1rem; }
    .faq-answer th, .faq-answer td { border:1px solid var(--ivory3); padding:.6rem .8rem; text-align:left; }
    .faq-answer th { background:var(--ivory2); font-family:-apple-system,sans-serif; font-weight:700; font-size:.9rem; }

    .faq-backtop { display:inline-flex; align-items:center; gap:.45rem; margin-top:.4rem; font-family:-apple-system,sans-serif; font-size:.68rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); background:none; transition:color .2s; }
    .faq-backtop:hover { color:var(--copper); }
    .faq-backtop svg { width:13px; height:13px; }

    /* ── CTA ─────────────────────────────────────────────── */
    .faq-cta { margin-top:3.5rem; text-align:center; background:var(--white); border:1px solid var(--ivory3); border-radius:4px; padding:2.75rem 2rem; box-shadow:var(--card-shadow); }
    .faq-cta h3 { font-family:'Cormorant Garamond',serif; font-size:1.7rem; font-weight:600; color:var(--slate); margin-bottom:.6rem; }
    .faq-cta p { font-family:-apple-system,sans-serif; font-size:.95rem; color:var(--body-text); margin-bottom:1.5rem; }
    .faq-cta a { display:inline-flex; align-items:center; gap:.6rem; font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#fff; background:var(--copper); padding:1rem 2.25rem; border-radius:2px; transition:background .3s, transform .3s; }
    .faq-cta a:hover { background:var(--slate); transform:translateY(-2px); }

    /* ── Floating back-to-top button ─────────────────────── */
    .faq-float { position:fixed; right:1.75rem; bottom:1.75rem; z-index:60; width:46px; height:46px; display:flex; align-items:center; justify-content:center; background:var(--slate); color:#fff; border-radius:50%; box-shadow:var(--hover-shadow); opacity:0; visibility:hidden; transform:translateY(12px); transition:opacity .3s, transform .3s, background .3s, visibility .3s; }
    .faq-float.is-visible { opacity:1; visibility:visible; transform:translateY(0); }
    .faq-float:hover { background:var(--copper); }
    .faq-float svg { width:20px; height:20px; }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 960px) {
        .faq-wrap { grid-template-columns: 1fr; gap: 2rem; }
        .faq-toc { position: static; top: auto; }
        .faq-toc-head { display: flex; cursor: pointer; }
        .faq-toc-head.is-open .chev { transform: rotate(180deg); }
        .faq-toc-body { display: none; max-height: none; }
        .faq-toc.is-open .faq-toc-body { display: block; }
        .faq-toc-title { display: none; }
    }
    @media (max-width: 768px) {
        .faq-page-header { padding: 9rem 1.5rem 3.5rem; }
        .faq-shell { padding: 3rem 1.4rem 4.5rem; }
        .faq-q { font-size: .98rem; }
        .faq-answer-scroll { max-height: 520px; padding-right: .8rem; }
        .faq-answer { font-size: 1.04rem; }
    }
</style>
@endpush

@section('content')

<section class="faq-page-header">
    <div class="inner">
        <div class="kicker">Frequently Asked Questions</div>
        <h1>Frequently Asked <em>Questions</em></h1>
        <p>Browse common questions on Agile, Scrum, hardware &amp; software development, and consulting &amp; training. Use the table of contents to jump to any answer, or expand a question to read the full response.</p>
    </div>
</section>

<section class="faq-shell">
    <div class="faq-wrap">

        {{-- ── Table of Contents ─────────────────────────── --}}
        <aside class="faq-toc" id="faqToc" aria-label="FAQ table of contents">
            <button type="button" class="faq-toc-head" id="faqTocToggle" aria-expanded="false" aria-controls="faqTocBody">
                <span>Table of Contents</span>
                <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <nav class="faq-toc-body" id="faqTocBody">
                <div class="faq-toc-title">On this page</div>
                @foreach($faqSections as $section)
                    <div class="faq-toc-group">
                        <h3>{{ $section['title'] }}</h3>
                        <ul>
                            @foreach($section['questions'] as $q)
                                <li><a href="#{{ $q['id'] }}" class="faq-toc-link" data-target="{{ $q['id'] }}">{{ $q['q'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </aside>

        {{-- ── FAQ Sections ──────────────────────────────── --}}
        <div class="faq-main">
            @foreach($faqSections as $si => $section)
                <div class="faq-section" id="{{ $section['id'] }}">
                    <div class="faq-section-head">
                        <span class="num">Section {{ $si + 1 }}</span>
                        <h2>{{ $section['title'] }}</h2>
                    </div>

                    @foreach($section['questions'] as $q)
                        <div class="faq-item" id="{{ $q['id'] }}" data-faq-item>
                            <button type="button" class="faq-q" aria-expanded="false" aria-controls="panel-{{ $q['id'] }}">
                                <span>{{ $q['q'] }}</span>
                                <svg class="faq-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                            <div class="faq-panel" id="panel-{{ $q['id'] }}" role="region" aria-label="{{ $q['q'] }}">
                                <div class="faq-answer-scroll">
                                    <div class="faq-answer">
                                        {!! $q['a'] !!}
                                    </div>
                                    <button type="button" class="faq-backtop" data-backtop>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                        Back to top of question
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="faq-cta">
                <h3>Still have a question?</h3>
                <p>Tell me about your organization, products, and the challenges you're trying to solve. I typically respond within one working day.</p>
                <a href="{{ route('contact') }}">Get in touch
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<button type="button" class="faq-float" id="faqFloat" aria-label="Back to top">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
</button>

@endsection

@push('scripts')
<script>
(function () {
    var HEADER_OFFSET = 90; // sticky header height for anchored scrolling

    var items = Array.prototype.slice.call(document.querySelectorAll('[data-faq-item]'));

    function scrollableCheck(item) {
        var scroll = item.querySelector('.faq-answer-scroll');
        window.setTimeout(function () {
            if (scroll.scrollHeight > scroll.clientHeight + 4) scroll.classList.add('is-scrollable');
            else scroll.classList.remove('is-scrollable');
        }, 30);
    }

    // Collapse an item. animate=false collapses instantly — used when we are
    // about to re-anchor the scroll, so the page height must settle synchronously.
    function collapse(item, animate) {
        if (!item.classList.contains('is-open')) return;
        var panel = item.querySelector('.faq-panel');
        if (animate) {
            panel.style.maxHeight = panel.scrollHeight + 'px';
            requestAnimationFrame(function () { panel.style.maxHeight = '0px'; });
        } else {
            panel.style.transition = 'none';
            panel.style.maxHeight = '0px';
            void panel.offsetHeight;                 // force the 0-height reflow now
            requestAnimationFrame(function () { panel.style.transition = ''; });
        }
        item.classList.remove('is-open');
        item.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
    }

    function expand(item) {
        var panel = item.querySelector('.faq-panel');
        item.classList.add('is-open');
        item.querySelector('.faq-q').setAttribute('aria-expanded', 'true');
        panel.style.maxHeight = panel.scrollHeight + 'px';
        scrollableCheck(item);
    }

    // Close every OTHER item without animation so the layout settles immediately.
    function closeSiblingsInstantly(keep) {
        items.forEach(function (other) { if (other !== keep) collapse(other, false); });
    }

    // Direct click on a question: pin it to its current on-screen position so it
    // can never jump above the viewport when a taller open sibling above collapses.
    function openKeepingPosition(item) {
        var before = item.getBoundingClientRect().top;
        closeSiblingsInstantly(item);
        var after = item.getBoundingClientRect().top;   // forces a synchronous reflow
        if (after !== before) window.scrollBy(0, after - before);
        expand(item);
    }

    // Navigation (TOC / hash): the caller handles the scroll afterwards.
    function openForNav(item) {
        closeSiblingsInstantly(item);
        expand(item);
    }

    items.forEach(function (item) {
        var btn = item.querySelector('.faq-q');
        btn.addEventListener('click', function () {
            if (item.classList.contains('is-open')) {
                collapse(item, true);
                setActive(null);
            } else {
                openKeepingPosition(item);
                setActive(item.id);
            }
        });
    });

    // Recalculate open panel height when the window resizes (content reflows)
    var resizeTimer;
    window.addEventListener('resize', function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(function () {
            var open = document.querySelector('.faq-item.is-open .faq-panel');
            if (open) open.style.maxHeight = open.scrollHeight + 'px';
        }, 120);
    });

    function scrollToItem(id) {
        var el = document.getElementById(id);
        if (!el) return;
        var y = el.getBoundingClientRect().top + window.pageYOffset - HEADER_OFFSET;
        window.scrollTo({ top: y, behavior: 'smooth' });
    }

    // Active-link highlighting based on the currently open question
    var tocLinks = Array.prototype.slice.call(document.querySelectorAll('.faq-toc-link'));
    function setActive(id) {
        tocLinks.forEach(function (l) {
            l.classList.toggle('is-active', l.getAttribute('data-target') === id);
        });
    }

    // TOC links: open the target (siblings close instantly), then smooth-scroll to it
    document.querySelectorAll('.faq-toc-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var id = link.getAttribute('data-target');
            var item = document.getElementById(id);
            if (item) openForNav(item);
            setActive(id);
            scrollToItem(id);
            history.replaceState(null, '', '#' + id);
            // collapse mobile TOC after choosing
            if (window.innerWidth <= 960) closeMobileToc();
        });
    });

    // Per-answer "Back to top": return to the TOP OF THIS QUESTION, not the page.
    document.querySelectorAll('[data-backtop]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = btn.closest('.faq-item');
            var scroll = btn.closest('.faq-answer-scroll');
            if (scroll) scroll.scrollTop = 0;         // reset the answer's inner scroll
            if (item) scrollToItem(item.id);          // bring the question header to the top
        });
    });

    // Floating back-to-top
    var floatBtn = document.getElementById('faqFloat');
    floatBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    window.addEventListener('scroll', function () {
        if (window.pageYOffset > 600) floatBtn.classList.add('is-visible');
        else floatBtn.classList.remove('is-visible');
    }, { passive: true });

    // Mobile TOC accordion
    var toc = document.getElementById('faqToc');
    var tocToggle = document.getElementById('faqTocToggle');
    function closeMobileToc() { toc.classList.remove('is-open'); tocToggle.classList.remove('is-open'); tocToggle.setAttribute('aria-expanded', 'false'); }
    tocToggle.addEventListener('click', function () {
        var open = toc.classList.toggle('is-open');
        tocToggle.classList.toggle('is-open', open);
        tocToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    // Deep-link support: open the question named in the URL hash on load
    if (window.location.hash) {
        var id = window.location.hash.slice(1);
        var target = document.getElementById(id);
        if (target && target.hasAttribute('data-faq-item')) {
            openForNav(target);
            setActive(id);
            window.setTimeout(function () { scrollToItem(id); }, 60);
        }
    }
})();
</script>

{{-- Build the array inside a PHP block, never inline in an unescaped echo.
     Blade treats the literal schema context key (at-sign + "context") as its own
     directive, which corrupts the JSON-LD and leaks compiled PHP into the page.
     Do not mention that key outside a PHP block — not even in a comment. --}}
@php
    $_faqPageJsonLd = json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => collect($faqSections)
            ->flatMap(fn ($s) => $s['questions'])
            ->map(fn ($q) => [
                '@type'          => 'Question',
                'name'           => $q['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => trim(preg_replace('/\s+/', ' ', strip_tags($q['a']))),
                ],
            ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">{!! $_faqPageJsonLd !!}</script>
@endpush
