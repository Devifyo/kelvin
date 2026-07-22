# Action Plan — kevinthompsonphd.com

Audit date: 2026-07-21 · Health score: **67/100** · Business type: B2B professional services (solo expert consultancy)

Ordered by impact ÷ effort. Every item traces to evidence in `findings/`.

---

## Phase 1 — Critical (this week)

### 1.1 Fix the Blade `@context` leak breaking FAQPage schema — 15 min

**Affected:** `/agile-consulting-services`, `/faq`

The FAQPage JSON-LD on both pages has its `@context` key replaced by compiled Blade PHP source:

```
{"<?php $__contextArgs = [];\nif (context()->has($__contextArgs[0])) : ... ?>":"https://schema.org","@type":"FAQPage",...}
```

Two distinct problems:
1. **Schema is dead.** The block is valid *JSON* but has no `@context`, so it is invalid *JSON-LD*. Google discards it. Verified: `has_@context=False` on both URLs.
2. **Server template internals are exposed** in the public HTML response.

**Root cause.** Laravel 12 has a `@context` Blade directive. The literal string `'@context'` inside a `{!! json_encode([...]) !!}` echo is compiled as that directive. The same string inside an `@php ... @endphp` block is *not* — which is why `services.blade.php:457` (Service schema, inside `@php`) renders correctly on the very same page.

**Fix** — move the array build into an `@php` block, matching the pattern already used everywhere else in this codebase:

```blade
@php
    $_faqJsonLd = json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => collect(\App\Models\FaqSection::schemaItems($page))->map(fn ($f) => [
            '@type' => 'Question', 'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">{!! $_faqJsonLd !!}</script>
```

**Files to change** (all three carry the vulnerable pattern; two are live, one is a latent landmine):

| File | Line | Status |
|---|---|---|
| `resources/views/landing-pages/partials/page-faqs.blade.php` | 23 | **live leak** (`/agile-consulting-services`) |
| `resources/views/landing-pages/partials/faq.blade.php` | 101 | **live leak** (`/faq`) |
| `resources/views/landing-pages/partials/faq-block.blade.php` | 65 | not currently rendered — fix anyway |

**Verify:** `curl -s <url> | grep -c '__contextArgs'` must return `0` for all 26 URLs.

**Add a regression test** — this class of bug is invisible in code review and silent in production:

```php
public function test_no_blade_source_leaks_into_rendered_html(): void
{
    foreach (['/agile-consulting-services', '/faq'] as $path) {
        $this->get($path)->assertOk()->assertDontSee('__contextArgs', false);
    }
}
```

---

## Phase 2 — High impact (weeks 2–3)

### 2.1 Add `sameAs` to the Person entity — 20 min
Zero `sameAs` links across all 26 pages. "Kevin Thompson" is a common name; nothing disambiguates this one from any other. This is the **single highest-leverage GEO fix** — entity resolution is how AI engines decide whether to attribute a claim to him.
Add LinkedIn, Google Scholar, ORCID, Amazon author page, Scrum Alliance profile to the sitewide `#person` node.

### 2.2 Fix `Course.offers.category` on all 7 training pages — 15 min
`"category": "Professional Training"` is not a valid enum. Google requires `Paid` / `Free` / `Subscription`. All 7 `/agile-training-classes/*` pages currently fail Course validation — these are the highest commercial-value pages on the site.

### 2.3 Add `logo` to the Organization entity — 10 min
Missing entirely. Blocks Logo rich result and weakens Knowledge Panel eligibility.

### 2.4 Fix LCP — render-blocking CSS + font chain — 2–4 h
LCP fails the "Good" threshold on **5/5** pages measured (3.38–3.93s lab vs 2.5s target). 4 of 5 LCP elements are plain *text*, stalled by the stylesheet chain, not by asset weight. Inline critical CSS, self-host Cormorant Garamond (or `preload` + `font-display: swap`), defer non-critical CSS. Estimated saving 1,160–1,770ms per page.

### 2.5 Fix the blog index image weight — 30 min
`/agile-insights-blog` is 776 KB — 4–8× every other page. One thumbnail is served at 1800×1195 into a 330×220 slot, wasting 371 KB alone. Generate correctly sized responsive variants.

---

## Phase 3 — Content & authority (month 2)

### 3.1 Thicken the training-class pages — 258–403 words each
The 7 `/agile-training-classes/*` pages are the money pages and the thinnest content on the site. `/agile-overview-for-executives-and-managers` is **258 words**. Add: full syllabus, learning outcomes, prerequisites, audience, duration, format, instructor bio, FAQs.

### 3.2 Fix heading hierarchy on 11 blog posts — 1–2 h
Every blog post skips `h2 → h4`; `/agile-insights-blog/thermo-fisher-scientific-lesson-7` skips `h1 → h4`. The homepage and `/podcasts-webinars` skip `h1 → h3`. Harms accessibility and machine parsing of document structure.

### 3.3 Surface the Thermo Fisher lesson payoff — 2 h
Each post buries its actual lesson ~350 words into first-person narrative with no isolating subheading. Add a "Key takeaway" callout near the top of each — directly improves LLM passage extraction.

### 3.4 Mark up the book as `schema.org/Book` — 30 min
A real published book (ASIN 0578420589) exists but appears nowhere in structured data and is not linked from the Person entity. Free authority signal.

### 3.5 Add `/faq` to llms.txt — 5 min
The site's richest citable asset (~40 Q&As) is omitted from `llms.txt`.

### 3.6 Add transcripts to podcasts/webinars — ongoing
No `<track>` elements or transcript text. Video content is currently invisible to search and AI engines except via thumbnail alt text.

---

## Phase 4 — Monitoring (ongoing)

### 4.1 Wire up Google Search Console + CrUX credentials
**All Core Web Vitals numbers in this audit are lab-only.** No field data was available. Configure credentials at `~/.config/claude-seo/google-api.json` to get real user metrics, indexation status, and query data.

### 4.2 Use the drift baselines
Baselines were captured this session for `/`, `/agile-consulting-services`, `/agile-insights-blog`, `/about-kevin-thompson`. Run `/seo drift compare <url>` after any change to shared markup.

### 4.3 Lower-priority technical items
- Trailing-slash URLs return 200 instead of 301 (`/about-kevin-thompson/` == `/about-kevin-thompson`). Canonical masks it; fix at nginx for cleanliness.
- No Content-Security-Policy header on any response.
- `/faq` has no meta description; its `<title>` is 93 chars (will truncate in SERPs).
- No IndexNow key file — Bing/Copilot indexing is slower than it needs to be.
- `Cache-Control: no-cache, private` + 3 `Set-Cookie` headers on every anonymous HTML response blocks all CDN edge caching.
