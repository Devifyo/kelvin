{{--
    Homepage FAQ section — reusable, CMS-driven.
    Expects $welcomeContent (App\Models\WelcomePageContent), optional.
    Renders nothing when disabled. Emits FAQPage JSON-LD for SEO/AEO.
--}}
@php
    $tc = $welcomeContent ?? null;
    $faqEnabled = $tc?->faq_enabled ?? true;

    $faqKicker  = $tc?->faq_kicker   ?: 'Common Questions';
    $faqTitle   = $tc?->faq_title    ?: 'Frequently Asked';
    $faqTitleEm = $tc?->faq_title_em ?: 'Questions';

    // Homepage FAQ content is seeded into (and edited via) WelcomePageContent.faq_items
    // in /admin/welcome-page. Empty items simply hide the section.
    $faqs = collect($tc?->faq_items ?? [])
        ->map(fn ($i) => ['q' => trim($i['q'] ?? ''), 'a' => trim($i['a'] ?? '')])
        ->filter(fn ($i) => $i['q'] !== '' && $i['a'] !== '')
        ->values()
        ->all();
@endphp

@if($faqEnabled && count($faqs))
<section id="faq" class="faq-section" aria-labelledby="faq-title">
    <style>
        .faq-section { background: var(--ivory); padding: 6rem 4.5rem; border-top: 1px solid var(--ivory3); }
        .faq-wrap { max-width: 820px; margin: 0 auto; }
        .faq-head { text-align: center; margin-bottom: 3.25rem; }
        .faq-section .kicker {
            display: inline-flex; align-items: center; justify-content: center; gap: .75rem;
            font-family: -apple-system, sans-serif; font-size: .72rem; font-weight: 700;
            letter-spacing: .2em; text-transform: uppercase; color: var(--copper); margin-bottom: 1.1rem;
        }
        .faq-section .kicker::before, .faq-section .kicker::after { content: ''; width: 30px; height: 1px; background: var(--copper); }
        .faq-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2.1rem, 4vw, 3rem); font-weight: 600; color: var(--slate); line-height: 1.1; }
        .faq-title em { font-style: italic; color: var(--copper); }

        .faq-item { border-top: 1px solid var(--ivory3); }
        .faq-item:last-of-type { border-bottom: 1px solid var(--ivory3); }
        .faq-item > summary {
            list-style: none; cursor: pointer; user-select: none;
            display: flex; align-items: center; justify-content: space-between; gap: 1.25rem;
            padding: 1.5rem 0.25rem; position: relative;
            font-family: -apple-system, sans-serif; font-size: 1.02rem; font-weight: 600; color: var(--slate);
            transition: color .25s ease;
        }
        .faq-item > summary::-webkit-details-marker { display: none; }
        .faq-item > summary:hover { color: var(--copper); }
        .faq-item > summary:focus-visible { outline: 2px solid var(--copper); outline-offset: 3px; border-radius: 3px; }
        .faq-icon { flex-shrink: 0; width: 22px; height: 22px; color: var(--copper); transition: transform .3s ease; }
        .faq-item[open] > summary .faq-icon { transform: rotate(45deg); }
        .faq-answer {
            padding: 0 0.25rem 1.75rem; max-width: 70ch;
            font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; line-height: 1.75; color: var(--charcoal);
        }
        .faq-item[open] > summary { color: var(--copper); }

        .faq-cta { text-align: center; margin-top: 3rem; }
        .faq-cta p { font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; color: var(--slate); margin-bottom: 1.25rem; }
        .faq-cta a {
            display: inline-flex; align-items: center; gap: .6rem;
            font-family: -apple-system, sans-serif; font-size: .72rem; font-weight: 700; letter-spacing: .15em; text-transform: uppercase;
            color: var(--white); background: var(--copper); padding: 1rem 2.25rem; text-decoration: none;
            transition: background .3s, transform .3s;
        }
        .faq-cta a:hover { background: var(--slate); transform: translateY(-2px); }

        @media (max-width: 768px) {
            .faq-section { padding: 4rem 1.75rem; }
            .faq-item > summary { font-size: .98rem; padding: 1.25rem 0.1rem; }
            .faq-answer { font-size: 1.08rem; }
        }
    </style>

    <div class="faq-wrap">
        <div class="faq-head">
            <div class="kicker">{{ $faqKicker }}</div>
            <h2 id="faq-title" class="faq-title">{{ $faqTitle }} <em>{{ $faqTitleEm }}</em></h2>
        </div>

        @foreach($faqs as $faq)
            <details class="faq-item">
                <summary>
                    <span>{{ $faq['q'] }}</span>
                    <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </summary>
                <div class="faq-answer">{!! nl2br(e($faq['a'])) !!}</div>
            </details>
        @endforeach

        <div class="faq-cta">
            <p>Still have a question?</p>
            <a href="{{ route('contact') }}">Get in touch <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
    </div>
</section>

@push('scripts')
@php
    $_faqJsonLd = json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => collect($faqs)->map(fn ($f) => [
            '@type'          => 'Question',
            'name'           => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_faqJsonLd !!}</script>
@endpush
@endif
