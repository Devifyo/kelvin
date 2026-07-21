{{--
    Reusable FAQ block (accordion + FAQPage schema) for any landing page.
    Params:
      $items   : array of ['q' => ..., 'a' => ...]   (required)
      $kicker  : string (default 'Common Questions')
      $title   : string (default 'Frequently Asked')
      $titleEm : string (default 'Questions')
      $ctaText : string|null  (optional CTA button label)
      $ctaUrl  : string|null
--}}
@php
    $items = collect($items ?? [])
        ->map(fn ($i) => ['q' => trim($i['q'] ?? ''), 'a' => trim($i['a'] ?? '')])
        ->filter(fn ($i) => $i['q'] !== '' && $i['a'] !== '')
        ->values()->all();
    $kicker  = $kicker  ?? 'Common Questions';
    $title   = $title   ?? 'Frequently Asked';
    $titleEm = $titleEm ?? 'Questions';
@endphp

@if(count($items))
<section class="faqx" aria-labelledby="faqx-title-{{ $loop->index ?? '' }}">
    <style>
        .faqx { background: var(--ivory, #faf7f2); padding: 5.5rem 4.5rem; border-top: 1px solid var(--ivory3, #e8dfd2); }
        .faqx-wrap { max-width: 820px; margin: 0 auto; }
        .faqx-head { text-align: center; margin-bottom: 3rem; }
        .faqx-kicker { display:inline-flex; align-items:center; gap:.75rem; font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:var(--copper,#b5722a); margin-bottom:1rem; }
        .faqx-kicker::before, .faqx-kicker::after { content:''; width:30px; height:1px; background:var(--copper,#b5722a); }
        .faqx-title { font-family:'Cormorant Garamond',serif; font-size:clamp(2rem,4vw,2.8rem); font-weight:600; color:var(--slate,#1a2332); line-height:1.1; }
        .faqx-title em { font-style:italic; color:var(--copper,#b5722a); }
        .faqx-item { border-top:1px solid var(--ivory3,#e8dfd2); }
        .faqx-item:last-of-type { border-bottom:1px solid var(--ivory3,#e8dfd2); }
        .faqx-item > summary { list-style:none; cursor:pointer; user-select:none; display:flex; align-items:center; justify-content:space-between; gap:1.25rem; padding:1.4rem .25rem; font-family:-apple-system,sans-serif; font-size:1rem; font-weight:600; color:var(--slate,#1a2332); transition:color .25s; }
        .faqx-item > summary::-webkit-details-marker { display:none; }
        .faqx-item > summary:hover, .faqx-item[open] > summary { color:var(--copper,#b5722a); }
        .faqx-item > summary:focus-visible { outline:2px solid var(--copper,#b5722a); outline-offset:3px; border-radius:3px; }
        .faqx-ic { flex-shrink:0; width:20px; height:20px; color:var(--copper,#b5722a); transition:transform .3s; }
        .faqx-item[open] .faqx-ic { transform:rotate(45deg); }
        .faqx-ans { padding:0 .25rem 1.6rem; max-width:70ch; font-family:'Cormorant Garamond',serif; font-size:1.12rem; line-height:1.75; color:var(--charcoal,#2c3a4a); }
        .faqx-cta { text-align:center; margin-top:2.5rem; }
        .faqx-cta a { display:inline-flex; align-items:center; gap:.6rem; font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#fff; background:var(--copper,#b5722a); padding:1rem 2.25rem; text-decoration:none; transition:background .3s, transform .3s; }
        .faqx-cta a:hover { background:var(--slate,#1a2332); transform:translateY(-2px); }
        @media (max-width:768px){ .faqx{ padding:4rem 1.75rem;} }
    </style>
    <div class="faqx-wrap">
        <div class="faqx-head">
            <div class="faqx-kicker">{{ $kicker }}</div>
            <h2 class="faqx-title">{{ $title }} <em>{{ $titleEm }}</em></h2>
        </div>
        @foreach($items as $f)
            <details class="faqx-item">
                <summary><span>{{ $f['q'] }}</span><svg class="faqx-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></summary>
                <div class="faqx-ans">{!! nl2br(e($f['a'])) !!}</div>
            </details>
        @endforeach
        @if(!empty($ctaText) && !empty($ctaUrl))
            <div class="faqx-cta"><a href="{{ $ctaUrl }}">{{ $ctaText }} <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a></div>
        @endif
    </div>
</section>

@if($schema ?? true)
@push('scripts')
{{-- Build the array inside a PHP block, never inline in an unescaped echo.
     Blade treats the literal schema context key (at-sign + "context") as its own
     directive, which corrupts the JSON-LD and leaks compiled PHP into the page.
     Do not mention that key outside a PHP block — not even in a comment. --}}
@php
    $_faqBlockJsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        'mainEntity' => collect($items)->map(fn ($f) => [
            '@type' => 'Question', 'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">{!! $_faqBlockJsonLd !!}</script>
@endpush
@endif
@endif
