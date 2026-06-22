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

    // Editable defaults — answer-first, grounded in Dr. Thompson's actual positioning.
    $defaultFaqs = [
        ['q' => 'Does Agile really work for hardware, not just software?',
         'a' => 'Yes — but it must be adapted, not copied from software. Hardware has longer lead times, physical iteration costs, and integration risk, so sprints deliver progress (CAD, board routing, design reviews, parts on order) rather than shippable features. Dr. Thompson pioneered Agile for hardware and authored the foundational paper on it; this is the core of every engagement.'],
        ['q' => 'Will I work with Dr. Thompson directly, or a junior consultant?',
         'a' => 'You work directly with Dr. Kevin Thompson, Ph.D. This is a senior, hands-on practice — not a staffing firm that sells you an expert and delivers a junior.'],
        ['q' => 'Have you worked in regulated environments like medical devices or FDA design controls?',
         'a' => 'Yes. Agile and regulatory compliance are not mutually exclusive — the FDA and IEC 62304 are methodology-neutral and care that design controls are documented, not that you use waterfall. Dr. Thompson has written specifically on Agile development for FDA-regulated medical products and how iterative work actually improves traceability and risk discovery.'],
        ['q' => 'How long does an engagement take, and how is it priced?',
         'a' => 'It depends on your goal. Engagements range from short assessments and targeted training through multi-month transformation and coaching programs, scoped to your organization’s size and complexity. Reach out with your situation and you’ll get a tailored proposal.'],
        ['q' => 'Do you work on-site, remote, or hybrid?',
         'a' => 'Both. Engagements are delivered on-site at your facilities and can include remote coaching for distributed hardware and software teams across locations and time zones.'],
        ['q' => 'What results do clients typically see?',
         'a' => 'Common outcomes are earlier discovery of design and integration problems, shorter and more predictable development cycles, and tighter alignment between hardware and software teams — fewer expensive late-stage surprises. Our Thermo Fisher Scientific case study walks through a real Agile hardware transformation.'],
        ['q' => 'How is Scrum different for hardware versus software?',
         'a' => 'The framework is the same; the mechanics differ. Hardware sprints are often roughly twice the length of software sprints and must be sequenced around procurement lead time, the Product Owner is frequently a team member, and a sprint’s output is demonstrable progress rather than a releasable feature. We tailor each of these to your product.'],
        ['q' => 'Does the change stick after you leave?',
         'a' => 'That’s the point. Engagements pair assessment and training with hands-on coaching and executive alignment so your teams own the process — the goal is durable capability, not dependence on a consultant.'],
    ];

    $faqs = collect($tc?->faq_items ?? [])
        ->map(fn ($i) => ['q' => trim($i['q'] ?? ''), 'a' => trim($i['a'] ?? '')])
        ->filter(fn ($i) => $i['q'] !== '' && $i['a'] !== '')
        ->values()
        ->all();
    if (empty($faqs)) {
        $faqs = $defaultFaqs;
    }
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
