{{--
    Reusable "Trusted By" homepage section.
    Expects:
      $featuredClients : Collection of active + featured App\Models\Client (sorted)
      $clientsCount    : int  total active clients (for the dynamic "X+ Clients Served" line)
      $welcomeContent  : App\Models\WelcomePageContent (optional) — CMS text + on/off toggle
    Renders nothing when the section is disabled in the CMS or there are no featured clients.
--}}
@php
    $featuredClients = $featuredClients ?? collect();
    $tc = $welcomeContent ?? null;

    // CMS-managed copy with sensible defaults.
    $trustedEnabled    = $tc?->trusted_enabled ?? true;
    $trustedKicker     = $tc?->trusted_kicker     ?: 'Our Clients';
    $trustedTitle      = $tc?->trusted_title      ?: 'Trusted By';
    $trustedTitleEm    = $tc?->trusted_title_em   ?: 'Industry Leaders';
    $trustedCountLabel = $tc?->trusted_count_label ?: 'Clients Served';
    $trustedCtaText    = $tc?->trusted_cta_text   ?: 'View All Clients';
@endphp

@if($trustedEnabled && $featuredClients->isNotEmpty())
<section id="trusted-by" class="trusted-by" aria-labelledby="trusted-by-title">
    <style>
        .trusted-by {
            background: var(--ivory);
            padding: 6rem 4.5rem;
            border-top: 1px solid var(--ivory3);
            border-bottom: 1px solid var(--ivory3);
        }
        .trusted-wrap { max-width: 1180px; margin: 0 auto; text-align: center; }
        .trusted-by .kicker {
            display: inline-flex; align-items: center; justify-content: center; gap: .75rem;
            font-family: -apple-system, sans-serif; font-size: .72rem; font-weight: 700;
            letter-spacing: .2em; text-transform: uppercase; color: var(--copper); margin-bottom: 1.25rem;
        }
        .trusted-by .kicker::before, .trusted-by .kicker::after {
            content: ''; width: 30px; height: 1px; background: var(--copper);
        }
        .trusted-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 600; color: var(--slate); line-height: 1.1; margin-bottom: 3.5rem;
        }
        .trusted-title em { font-style: italic; color: var(--copper); }

        .trusted-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1.5rem;
            align-items: center;
        }
        .trusted-logo {
            display: flex; align-items: center; justify-content: center;
            height: 90px; padding: 1rem 1.25rem;
            background: var(--white);
            border: 1px solid var(--ivory3);
            border-radius: 10px;
            transition: transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s, border-color .35s;
        }
        .trusted-logo img {
            max-height: 100%; max-width: 100%; width: auto; object-fit: contain;
            transition: transform .35s ease;
        }
        .trusted-logo:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(26,35,50,.08);
            border-color: rgba(181,114,42,.25);
        }
        .trusted-logo:hover img { transform: scale(1.04); }

        .trusted-footer {
            margin-top: 3.5rem;
            display: flex; flex-direction: column; align-items: center; gap: 1.75rem;
        }
        .trusted-count {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem; color: var(--slate); font-weight: 500;
        }
        .trusted-count strong { color: var(--copper); font-weight: 700; }
        .trusted-cta {
            display: inline-flex; align-items: center; gap: .6rem;
            font-family: -apple-system, sans-serif; font-size: .72rem; font-weight: 700;
            letter-spacing: .15em; text-transform: uppercase;
            color: var(--white); background: var(--copper);
            padding: 1rem 2.25rem; border-radius: 0;
            text-decoration: none; transition: background .3s, color .3s, transform .3s;
        }
        .trusted-cta:hover { background: var(--slate); color: var(--white); transform: translateY(-2px); }
        .trusted-cta svg { transition: transform .3s; }
        .trusted-cta:hover svg { transform: translateX(4px); }

        @media (max-width: 1024px) {
            .trusted-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 768px) {
            .trusted-by { padding: 4rem 1.75rem; }
            .trusted-grid { grid-template-columns: repeat(3, 1fr); gap: 1rem; }
            .trusted-logo { height: 70px; padding: .75rem; }
        }
        @media (max-width: 460px) {
            .trusted-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

    <div class="trusted-wrap">
        <div class="kicker">{{ $trustedKicker }}</div>
        <h2 id="trusted-by-title" class="trusted-title">{{ $trustedTitle }} <em>{{ $trustedTitleEm }}</em></h2>

        <div class="trusted-grid">
            @foreach($featuredClients as $client)
                @php($logoTag = $client->logo_url
                    ? '<img src="'.e($client->logo_url).'" alt="'.e($client->name).' logo" loading="lazy" width="160" height="60">'
                    : '<span style="font-weight:700;color:var(--muted);">'.e($client->name).'</span>')
                @if($client->website_url)
                    <a class="trusted-logo" href="{{ $client->website_url }}" target="_blank" rel="noopener nofollow" title="{{ $client->name }}">
                        {!! $logoTag !!}
                    </a>
                @else
                    <div class="trusted-logo" title="{{ $client->name }}">
                        {!! $logoTag !!}
                    </div>
                @endif
            @endforeach
        </div>

        <div class="trusted-footer">
            @php($clientsCount = $clientsCount ?? 0)
            @if($clientsCount > 0)
                <div class="trusted-count">
                    {{-- Round down to the nearest 10 for a "70+" style figure; show exact for small counts. --}}
                    <strong>{{ $clientsCount >= 10 ? (floor($clientsCount / 10) * 10) . '+' : $clientsCount }}</strong> {{ $trustedCountLabel }}
                </div>
            @endif
            <a href="{{ route('clients') }}" class="trusted-cta">
                {{ $trustedCtaText }}
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endif
