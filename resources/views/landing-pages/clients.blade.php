@extends('layouts.app')

@section('title', 'Our Clients — Trusted By Industry Leaders | Kevin Thompson, Ph.D.')
@section('meta_description', 'Dr. Kevin Thompson, Ph.D. has partnered with leading hardware, technology, and enterprise organizations worldwide. Browse the companies we have served.')
@section('meta_keywords', 'kevin thompson clients, agile consulting clients, trusted by, hardware companies, agile transformation clients')
@section('og_type', 'website')

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 12rem 4.5rem 8rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%);
    z-index: 0;
}
.header-content { max-width: 900px; margin: 0 auto; position: relative; z-index: 1; }
.kicker {
    display: inline-flex; align-items: center; justify-content: center; gap: .75rem;
    font-family: -apple-system, sans-serif; font-size: .75rem; font-weight: 700;
    letter-spacing: .2em; text-transform: uppercase; color: var(--copper); margin-bottom: 1.5rem;
}
.kicker::before, .kicker::after { content: ''; width: 30px; height: 1px; background: var(--copper); }
.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3rem, 6vw, 4.8rem);
    font-weight: 400; color: var(--white); line-height: 1.1; margin-bottom: 1.5rem;
}
.page-title em { font-style: italic; color: var(--copper3); }
.page-subtitle {
    font-family: -apple-system, sans-serif; font-size: 1.1rem;
    color: rgba(250,247,242,.7); max-width: 650px; line-height: 1.8;
    font-weight: 300; margin: 0 auto;
}

/* ─────────────────────────────────────────
   CONTROLS & SEARCH
───────────────────────────────────────── */
.clients-section { padding: 4rem 4.5rem 8rem; background: var(--ivory); min-height: 60vh; }
.clients-container { max-width: 1180px; margin: 0 auto; }
.clients-controls {
    display: flex; align-items: flex-end; justify-content: space-between;
    margin-bottom: 3.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--ivory3);
}
.controls-title {
    font-family: 'Cormorant Garamond', serif; font-size: 2.2rem;
    font-weight: 600; color: var(--slate); line-height: 1; margin: 0;
}
.controls-title small {
    display: block; font-family: -apple-system, sans-serif; font-size: .8rem;
    font-weight: 600; letter-spacing: .05em; color: var(--muted); margin-top: .5rem;
}
.search-wrapper { position: relative; }
.search-input {
    width: 280px; padding: .9rem 1.2rem .9rem 2.8rem;
    font-family: -apple-system, sans-serif; font-size: .95rem; color: var(--slate);
    background: var(--white); border: 1px solid var(--ivory3); border-radius: 50px;
    outline: none; box-shadow: 0 4px 12px rgba(26,35,50,.03);
    transition: all .4s cubic-bezier(.4,0,.2,1);
}
.search-input::placeholder { color: var(--muted); font-weight: 400; }
.search-input:focus { width: 360px; border-color: var(--copper); box-shadow: 0 8px 24px rgba(181,114,42,.12); }
.search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
.search-btn { position: absolute; left: 0; top: 0; bottom: 0; width: 40px; background: transparent; border: none; cursor: pointer; z-index: 2; }

/* ─────────────────────────────────────────
   CLIENT GRID
───────────────────────────────────────── */
.clients-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.75rem;
}
.client-card {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    background: var(--white); border: 1px solid var(--ivory3); border-radius: 12px;
    padding: 1.75rem 1.25rem; min-height: 160px; text-align: center;
    transition: transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s, border-color .35s;
}
.client-card:hover {
    transform: translateY(-5px); box-shadow: 0 18px 36px rgba(26,35,50,.08); border-color: rgba(181,114,42,.25);
}
.client-logo-wrap {
    height: 70px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;
}
.client-logo-wrap img {
    max-height: 70px; max-width: 100%; width: auto; object-fit: contain;
    transition: transform .35s ease;
}
.client-card:hover .client-logo-wrap img { transform: scale(1.04); }
.client-name {
    font-family: -apple-system, sans-serif; font-size: .82rem; font-weight: 600;
    color: var(--slate); line-height: 1.3;
}
.client-name a { color: var(--slate); text-decoration: none; transition: color .25s; }
.client-card:hover .client-name a { color: var(--copper); }
.client-visit {
    margin-top: .35rem; font-size: .65rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted);
}

.no-results {
    grid-column: 1 / -1; text-align: center; padding: 6rem 2rem;
    background: var(--white); border: 1px dashed var(--ivory3);
}
.no-results h3 { font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; color: var(--slate); margin-bottom: .5rem; }
.no-results p { font-family: -apple-system, sans-serif; color: var(--muted); }
.clear-search-btn {
    display: inline-block; margin-top: 1.5rem; color: var(--copper); font-weight: 600;
    text-decoration: none; border: 1px solid var(--copper); padding: .5rem 1.5rem; border-radius: 4px; transition: all .3s;
}
.clear-search-btn { background: transparent; cursor: pointer; font-family: inherit; font-size: 0.95rem; }
.clear-search-btn:hover { background: var(--copper); color: var(--white); }

/* Hide Alpine-only elements until Alpine initialises (prevents flash) */
[x-cloak] { display: none !important; }

/* Inline clear (×) button inside the search field */
.search-wrapper .search-input { padding-right: 2.6rem; }
.search-clear {
    position: absolute; right: 0.55rem; top: 50%; transform: translateY(-50%);
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px; padding: 0;
    border: none; border-radius: 50%;
    background: var(--ivory3); color: var(--slate);
    cursor: pointer; z-index: 3;
    transition: background .2s ease, color .2s ease;
}
.search-clear:hover { background: var(--copper); color: var(--white); }

@media(max-width: 1024px) { .clients-grid { grid-template-columns: repeat(4, 1fr); } }
@media(max-width: 768px) {
    .page-header { padding: 10rem 2.5rem 5rem; }
    .clients-section { padding: 3rem 2.5rem 5rem; }
    .clients-controls { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
    .search-wrapper { width: 100%; }
    .search-input, .search-input:focus { width: 100%; }
    .clients-grid { grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
}
@media(max-width: 480px) { .clients-grid { grid-template-columns: repeat(2, 1fr); } }
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Our Clients</div>
        <h1 class="page-title">Trusted By <em>Industry Leaders</em></h1>
        <p class="page-subtitle">Over the course of more than 100 engagements, we have partnered with leading hardware, technology, and enterprise organizations to deliver lasting Agile transformation.</p>
    </div>
</section>

<section class="clients-section">
    <div class="clients-container"
         x-data="{
            q: @js($search),
            names: {{ \Illuminate\Support\Js::from($clients->map(fn ($c) => \Illuminate\Support\Str::lower($c->name))->values()) }},
            get needle() { return this.q.trim().toLowerCase() },
            show(name) { return this.needle === '' || name.includes(this.needle) },
            get shown() { return this.needle === '' ? this.names.length : this.names.filter(n => n.includes(this.needle)).length }
         }">

        <div class="clients-controls reveal rv1">
            <h2 class="controls-title">
                Companies We've Served
                <small><span x-text="shown">{{ $clients->count() }}</span> of {{ $clients->count() }} {{ \Illuminate\Support\Str::plural('company', $clients->count()) }}</small>
            </h2>

            {{-- Instant client-side search. Falls back to a server GET for no-JS. --}}
            <form action="{{ route('clients') }}" method="GET" class="search-wrapper" @submit.prevent>
                <input type="text" name="search" class="search-input" placeholder="Search by company name..."
                       x-model="q" autocomplete="off" aria-label="Search companies by name">
                <span class="search-btn" aria-hidden="true">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <button type="button" class="search-clear" x-show="q.length" x-cloak @click="q = ''" aria-label="Clear search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </form>
        </div>

        <div class="clients-grid">
            @foreach($clients as $client)
                <div class="client-card" data-name="{{ \Illuminate\Support\Str::lower($client->name) }}" x-show="show($el.dataset.name)">
                    <div class="client-logo-wrap">
                        @if($client->logo_url)
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }} logo" loading="lazy" width="180" height="70">
                        @else
                            <span style="font-weight:700;color:var(--muted);">{{ $client->name }}</span>
                        @endif
                    </div>
                    <div class="client-name">
                        @if($client->website_url)
                            <a href="{{ $client->website_url }}" target="_blank" rel="noopener nofollow">{{ $client->name }}</a>
                        @else
                            {{ $client->name }}
                        @endif
                    </div>
                    @if($client->website_url)
                        <div class="client-visit">Visit Website &rarr;</div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Empty state: live query matches nothing (only when there ARE clients to filter) --}}
        <div class="no-results" x-show="shown === 0 && names.length > 0" x-cloak>
            <h3>No companies found.</h3>
            <p>We couldn't find any companies matching “<span x-text="q"></span>”.</p>
            <button type="button" class="clear-search-btn" @click="q = ''">Clear search</button>
        </div>

        @if($clients->isEmpty())
            <div class="no-results">
                <h3>No clients yet.</h3>
                <p>Check back soon.</p>
            </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
{{-- Alpine powers the instant client-side search on this page (not loaded globally) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
{{-- Page-level JSON-LD: ItemList of client organizations on the current page --}}
@php
    $_clientItems = [];
    foreach ($clients as $i => $client) {
        $org = ['@type' => 'Organization', 'name' => $client->name];
        if ($client->website_url) { $org['url'] = $client->website_url; }
        if ($client->logo_url)    { $org['logo'] = url($client->logo_url); }
        $_clientItems[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'item'     => $org,
        ];
    }
    $_clientsJsonLd = json_encode([
        '@context'        => 'https://schema.org',
        '@type'           => 'CollectionPage',
        'name'            => 'Our Clients — Trusted By Industry Leaders',
        'description'     => 'Companies served by Dr. Kevin Thompson, Ph.D. across Agile hardware and software transformation engagements.',
        'url'             => url()->current(),
        'isPartOf'        => ['@id' => url('/') . '/#website'],
        'mainEntity'      => [
            '@type'           => 'ItemList',
            'numberOfItems'   => $clients->count(),
            'itemListElement' => $_clientItems,
        ],
        'inLanguage'      => 'en-US',
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_clientsJsonLd !!}</script>
<script>
(function() {
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); revealObs.unobserve(e.target); }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });
    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
})();
</script>
@endpush
