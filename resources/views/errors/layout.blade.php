{{-- Shared error-page layout. Children @extends('errors.layout') and @section()-override
     err_code, err_kicker, err_heading, err_message. --}}
@extends('layouts.app')

@section('title', trim(View::yieldContent('err_heading', 'Page Not Found')) . ' | ' . config('app.name'))
@section('meta_description', trim(View::yieldContent('err_message', 'The page you requested could not be found.')))

{{-- 404/500/503 must NOT be indexed --}}
@push('schema')
    <meta name="robots" content="noindex, follow">
@endpush

@push('styles')
<style>
.err-hero {
    background: var(--slate);
    padding: 11rem 4.5rem 6rem;
    position: relative;
    overflow: hidden;
}
.err-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.6) 0%, transparent 70%);
    z-index: 0;
}
.err-content {
    max-width: 1180px; margin: 0 auto; position: relative; z-index: 1; text-align: center;
}
.err-code {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(7rem, 18vw, 14rem);
    font-weight: 300; line-height: 1; color: var(--copper3); letter-spacing: -.03em;
    margin-bottom: 1rem;
}
.err-kicker {
    display: inline-flex; align-items: center; gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem; font-weight: 700; letter-spacing: .3em; text-transform: uppercase;
    color: var(--copper2); margin-bottom: 1.1rem;
}
.err-kicker::before, .err-kicker::after {
    content: ''; width: 24px; height: 1px; background: var(--copper2);
}
.err-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.2rem, 4vw, 3.4rem);
    font-weight: 300; color: var(--white); line-height: 1.15; margin: 0 0 1.5rem;
}
.err-message {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: 1.05rem; color: rgba(250,247,242,.7); max-width: 640px;
    margin: 0 auto 2.5rem; line-height: 1.8; font-weight: 300;
}
.err-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.err-btn {
    display: inline-flex; align-items: center; gap: .55rem;
    padding: .9rem 1.6rem; border-radius: 999px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .82rem; font-weight: 600; letter-spacing: .02em;
    text-decoration: none; transition: transform .15s ease, background .15s ease, color .15s ease;
}
.err-btn-primary { background: var(--copper); color: var(--slate); }
.err-btn-primary:hover { background: var(--copper2); transform: translateY(-1px); }
.err-btn-ghost { background: transparent; color: rgba(250,247,242,.85); border: 1px solid rgba(250,247,242,.25); }
.err-btn-ghost:hover { background: rgba(250,247,242,.08); color: #fff; transform: translateY(-1px); }

.err-helpful {
    background: var(--ivory, #faf7f2);
    padding: 7rem 4.5rem 8rem;
}
.err-helpful-inner { max-width: 1180px; margin: 0 auto; }
.err-helpful-head { text-align: center; margin-bottom: 4rem; }
.err-helpful-kicker {
    display: inline-flex; align-items: center; gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem; font-weight: 700; letter-spacing: .3em;
    text-transform: uppercase; color: var(--copper); margin-bottom: 1.1rem;
}
.err-helpful-kicker::before, .err-helpful-kicker::after {
    content: ''; width: 24px; height: 1px; background: var(--copper);
}
.err-helpful h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2rem, 3.2vw, 2.85rem);
    font-weight: 300; color: var(--slate); margin: 0 0 .9rem; line-height: 1.15;
}
.err-helpful h2 em { font-style: italic; color: var(--copper); }
.err-helpful-sub {
    color: var(--slate2, #4a5568);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: 1.02rem; max-width: 560px; margin: 0 auto; line-height: 1.7;
}

.err-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.75rem;
}
.err-card {
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 2.75rem 2.4rem;
    background: #fff;
    border: 1px solid rgba(26,35,50,.06);
    box-shadow: 0 10px 30px rgba(26,35,50,.03);
    text-decoration: none; color: inherit;
    position: relative; overflow: hidden;
    transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
}
.err-card::before {
    content: ''; position: absolute; top: 0; left: 0;
    width: 100%; height: 3px;
    background: var(--copper);
    transform: scaleX(0); transform-origin: left;
    transition: transform .4s ease;
}
.err-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 42px rgba(26,35,50,.08);
    border-color: rgba(200,157,99,.35);
}
.err-card:hover::before { transform: scaleX(1); }
.err-card-tag {
    display: inline-block;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .62rem; font-weight: 700; letter-spacing: .3em;
    text-transform: uppercase; color: var(--copper);
    margin-bottom: 1.1rem;
}
.err-card-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.65rem; font-weight: 400; color: var(--slate);
    margin: 0 0 .85rem; line-height: 1.2;
}
.err-card-desc {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .94rem; color: var(--slate2, #4a5568);
    line-height: 1.7; margin: 0 0 1.6rem;
}
.err-card-cta {
    display: inline-flex; align-items: center; gap: .45rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .78rem; font-weight: 600; letter-spacing: .12em;
    text-transform: uppercase; color: var(--slate);
    transition: color .2s ease, gap .2s ease;
    margin-top: auto;
}
.err-card:hover .err-card-cta { color: var(--copper); gap: .8rem; }
.err-card-cta svg { transition: transform .2s ease; }
.err-card:hover .err-card-cta svg { transform: translateX(3px); }

.err-search {
    margin: 4rem auto 0; max-width: 560px;
    display: flex; gap: .6rem;
    background: #fff;
    padding: .5rem .5rem .5rem 1.4rem;
    border: 1px solid rgba(26,35,50,.08);
    border-radius: 999px;
    box-shadow: 0 10px 30px rgba(26,35,50,.04);
    transition: border-color .2s ease, box-shadow .2s ease;
}
.err-search:focus-within {
    border-color: var(--copper);
    box-shadow: 0 14px 36px rgba(26,35,50,.08);
}
.err-search input {
    flex: 1; padding: .85rem 0;
    border: none; outline: none; background: transparent;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .95rem; color: var(--slate);
}
.err-search input::placeholder { color: rgba(74,85,104,.55); }
.err-search button {
    padding: .85rem 1.7rem; border: none; border-radius: 999px;
    background: var(--slate); color: #fff; cursor: pointer;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .78rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase;
    transition: background .15s ease;
}
.err-search button:hover { background: var(--copper); }

@media (max-width: 1024px) {
    .err-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .err-hero { padding: 8rem 1.5rem 4rem; }
    .err-helpful { padding: 4rem 1.5rem 5rem; }
    .err-grid { grid-template-columns: 1fr; gap: 1.25rem; }
    .err-card { padding: 2.25rem 1.75rem; }
    .err-search {
        flex-direction: column; border-radius: 16px; padding: .85rem;
    }
    .err-search input { padding: .65rem .9rem; }
    .err-search button { width: 100%; }
}
</style>
@endpush

@section('content')
<section class="err-hero" role="main">
    <div class="err-content">
        <div class="err-code" aria-hidden="true">@yield('err_code', '404')</div>
        <div class="err-kicker">@yield('err_kicker', 'Page Not Found')</div>
        <h1 class="err-heading">@yield('err_heading', 'This page is missing')</h1>
        <p class="err-message">@yield('err_message', 'The page you requested could not be found.')</p>
        <div class="err-actions">
            <a href="{{ url('/') }}" class="err-btn err-btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                Back to Home
            </a>
            <a href="{{ url('/contact-us') }}" class="err-btn err-btn-ghost">Report this Issue</a>
        </div>
    </div>
</section>

<section class="err-helpful">
    <div class="err-helpful-inner">
        <div class="err-helpful-head">
            <div class="err-helpful-kicker">Where to next</div>
            <h2>Try one of these <em>instead</em></h2>
            <p class="err-helpful-sub">A few popular destinations on the site — or search the blog for the topic you were looking for.</p>
        </div>

        <div class="err-grid">
            <a class="err-card" href="{{ url('/agile-consulting-services') }}">
                <div>
                    <span class="err-card-tag">Consulting</span>
                    <h3 class="err-card-title">Agile Hardware Consulting</h3>
                    <p class="err-card-desc">Assessments, advisory coaching, and transformation programs for R&amp;D and hardware engineering organizations.</p>
                </div>
                <span class="err-card-cta">
                    Explore Services
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </span>
            </a>
            <a class="err-card" href="{{ url('/agile-training-classes') }}">
                <div>
                    <span class="err-card-tag">Training</span>
                    <h3 class="err-card-title">Scrum &amp; Agile Classes</h3>
                    <p class="err-card-desc">Live, instructor-led courses including Agile Hardware Development with Scrum, Kanban, and Advanced Product Owner.</p>
                </div>
                <span class="err-card-cta">
                    Browse Catalog
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </span>
            </a>
            <a class="err-card" href="{{ url('/agile-insights-blog') }}">
                <div>
                    <span class="err-card-tag">Insights</span>
                    <h3 class="err-card-title">Agile Hardware Blog</h3>
                    <p class="err-card-desc">Practical articles on Agile hardware development, Scrum, and transformation — written for engineering leaders.</p>
                </div>
                <span class="err-card-cta">
                    Read Articles
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </span>
            </a>
        </div>

        <form class="err-search" action="{{ url('/agile-insights-blog') }}" method="GET" role="search" aria-label="Search the blog">
            <label for="err-search-input" style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">Search articles</label>
            <input id="err-search-input" type="search" name="search" placeholder="Search the blog — e.g. ‘scrum for hardware’" autocomplete="off" />
            <button type="submit">Search</button>
        </form>
    </div>
</section>
@endsection
