# Full SEO Audit — kevinthompsonphd.com

**Date:** 2026-07-21 · **Pages audited:** 26 (complete sitemap) · **Business type:** B2B professional services — solo expert consultancy (Agile hardware development consulting & training, Dr. Kevin Thompson, Ph.D.)

## SEO Health Score: 67 / 100

| Category | Weight | Score | Confidence |
|---|---|---|---|
| Technical SEO | 22% | 68 | High — specialist completed |
| Content Quality | 23% | 62 | **Low** — see coverage gaps |
| On-Page SEO | 20% | 74 | High — all 26 URLs measured |
| Schema / Structured Data | 10% | 68 | High — specialist completed |
| Performance (CWV) | 10% | 60 | Medium — **lab only, no field data** |
| AI Search Readiness | 10% | 62 | High — specialist completed |
| Images | 5% | 72 | Medium |

### Coverage gaps — read before trusting this score

This audit is **not complete**. Four specialist tracks failed to produce output (the agents exhausted their turn budget before writing). Affected areas, and what was substituted:

| Track | Status | Substitute evidence used |
|---|---|---|
| Content / E-E-A-T | **not completed** | Word counts + heading structure measured directly across all 26 URLs. No qualitative E-E-A-T assessment was performed — the 62 score is inferred from measurable signals only. |
| Visual / mobile | **partially completed** | Mobile captures of the homepage and services page were reviewed (`findings/visual.md`). Desktop layouts, the blog and about pages, and measured tap-target sizes remain unverified. |
| SXO / SERP intent | **not completed** | None. No competitive SERP analysis was performed. |
| Backlinks | **not completed** | None. Off-site authority is entirely unassessed. |

Additionally, **no Google API credentials** were configured, so there is **no CrUX field data, no Search Console indexation data, and no GA4 traffic data**. Every performance number here is lab-simulated.

---

## Top 5 Critical / High Issues

1. **CRITICAL — Compiled Blade PHP leaks into FAQPage JSON-LD** on `/agile-consulting-services` and `/faq`. The `@context` key is replaced by raw template source. The blocks are valid JSON but invalid JSON-LD, so Google discards them entirely — and server internals are exposed publicly. Root cause and 15-minute fix in `ACTION-PLAN.md` §1.1.
2. **HIGH — Person entity has zero `sameAs` links.** Across all 26 pages there is no LinkedIn, ORCID, Scholar, or publisher profile. "Kevin Thompson" is a common name with nothing to disambiguate this one — the biggest single blocker to AI-engine attribution.
3. **HIGH — LCP fails on 5/5 pages measured** (3.38–3.93s lab vs 2.5s target), caused by a render-blocking CSS + Google Fonts chain. 4 of 5 LCP elements are plain text, so this is a delivery problem, not a content-weight problem.
4. **HIGH — `Course.offers.category` is invalid on all 7 training pages.** `"Professional Training"` is not in the allowed enum (`Paid`/`Free`/`Subscription`), so Course markup fails validation on the highest commercial-value pages.
5. **HIGH — Every HTML response is uncacheable.** `Cache-Control: no-cache, private` plus 3 `Set-Cookie` headers on anonymous marketing pages blocks all CDN edge caching.

## Top 5 Quick Wins

| Fix | Effort | Impact |
|---|---|---|
| Fix the `@context` Blade leak | 15 min | Restores schema on 2 pages, stops source disclosure |
| Add `sameAs` to Person | 20 min | Largest available GEO/entity gain |
| Fix `Course.offers.category` (7 pages) | 15 min | Restores Course rich-result eligibility |
| Add `logo` to Organization | 10 min | Unblocks Logo rich result / Knowledge Panel |
| Add `/faq` to `llms.txt` | 5 min | Exposes ~40 Q&As to AI crawlers |

---

## What is genuinely strong

Worth stating plainly, because these are the things most sites of this size get wrong:

- **No duplicate titles or meta descriptions anywhere.** All 26 pages are unique — including the 7 templated training pages and the 8-part blog series, which is exactly where collisions normally appear.
- **Exactly one `<h1>` on every one of the 26 pages.** Zero exceptions.
- **All 26 URLs return clean 200s.** No redirect chains, no 404s, no 5xx.
- **Self-referencing canonicals correct** on every page type sampled.
- **Genuinely server-rendered** (`is_spa: false`) — all content present without JS, which is what makes it legible to AI crawlers.
- **The `@id`-referenced `@graph` architecture is correctly implemented.** Zero duplicate or forked Person/Organization entities across 10 sampled URLs. This is a real architectural strength.
- **CLS passes on 5/5** (0.000–0.043) and **TBT/INP-proxy passes on 5/5** (0–56ms).
- **94% image alt coverage** (65 of 69 images).
- **`llms.txt` is present, curated, and accurate** — not boilerplate.
- **robots.txt blocks only Laravel internals**, no content paths; all AI crawlers allowed.

---

## Findings by category

Full detail per specialist is in `findings/`:

| File | Score | Contents |
|---|---|---|
| `findings/technical.md` | 68 | Crawlability, indexability, security headers, cookies/caching, URL structure, JS rendering, IndexNow |
| `findings/schema.md` | 68 | Full schema detection tables, validation per page type, ready-to-paste corrected JSON-LD |
| `findings/performance.md` | 60 | Lighthouse lab measurements for 5 pages with per-page LCP/CLS/TBT and attributed causes |
| `findings/geo.md` | 62 | AI crawler access, llms.txt, passage citability with quoted passages, entity disambiguation |
| `findings/visual.md` | — | Mobile above-the-fold review of the homepage and services page |

### On-Page SEO (measured directly across all 26 URLs)

Raw data: `pages.json`. Leak scan: `leak-scan.txt`.

**Issues found:**

- **Heading hierarchy skips on 13 of 26 pages.** All 11 blog posts skip `h2 → h4`; `thermo-fisher-scientific-lesson-7` skips `h1 → h4`; the homepage and `/podcasts-webinars` skip `h1 → h3`.
- **`/faq` title is 93 characters** — will truncate in SERPs.
- ~~`/faq` has no meta description.~~ **RETRACTED** — the technical specialist reported this; re-verified against the live page and it is false. `/faq` has a 205-char meta description (which is over the ~155 display limit, so it will truncate — that is the real, smaller issue).
- **4 titles under 30 chars** (`/agile-training-classes/agile-portfolio-management` 26, `agile-program-management` 24, `advanced-product-owner` 22, `/agile-insights-blog/embedded-software` 17) — under-using available SERP real estate on commercial pages.
- **4 images missing alt text**, all on 2 blog posts (`scrum-teams-swarming-and-hardware` 1/4, `how-hardware-and-software-engineers-differ` 3/6).

### Content depth (measured, not qualitatively assessed)

Thinnest pages by word count:

| Page | Words |
|---|---|
| `/contact-us` | 182 |
| `/podcasts-webinars` | 205 |
| `/agile-training-classes/agile-overview-for-executives-and-managers` | 258 |
| `/agile-training-classes/agile-software-development-with-scrum-training` | 309 |
| `/agile-training-classes/agile-hardware-development-with-scrum` | 309 |
| `/agile-training-classes/agile-work-management-with-kanban` | 312 |
| `/agile-training-classes/agile-program-management` | 318 |
| `/agile-training-classes/agile-portfolio-management` | 377 |
| `/agile-training-classes/advanced-product-owner` | 403 |

**Correction to the original framing.** I initially read this as "the commercial pages are missing content." Re-checking the live pages, that is wrong: each training page already carries a full syllabus — course overview, learning objectives, a 39-item curriculum, length, target audience and prerequisites. The word counts are low because the copy is terse and the curriculum renders as bare keyword lists, not because content is absent.

The real, narrower issue was that **the syllabus was invisible to search engines** — it rendered on the page but was absent from the Course structured data. That has since been fixed (see "Fixed since this audit" below).

### Sitemap

`sitemap.xml` validates (`kind: urlset`), contains 26 `<loc>` entries matching the audited set exactly, and is generated by `App\Services\SeoGenerator` — so any fix belongs in the generator, not the file. Deeper architecture analysis (click depth, orphan detection, series hub) was not completed.

---

## Fixed since this audit

Applied and verified against staging; ships to production on next deploy:

| Item | Status |
|---|---|
| CRITICAL — Blade `@context` leak on 2 pages | **Fixed** in 3 partials, + regression test proven to fail on the old code |
| HIGH — `Course.offers.category` invalid on 7 pages | **Fixed** → `"Paid"` |
| `Course.courseMode` claimed `online` with no online instance | **Fixed** → `["onsite"]` |
| `Course.image: null` validation error | **Fixed** — key omitted when absent |
| Course syllabus invisible to search engines | **Fixed** — now exposes `teaches` (39 items), `coursePrerequisites`, `audience`, `abstract` |
| 4 training titles under 30 chars | **Fixed in code** via `Service::seoTitle()` — all 7 now 48–59 chars, branded, no DB edit needed |
| `/faq` missing from `llms.txt` | **Fixed** in the generator template |
| HIGH — Person/Organization `sameAs` | **Unblocked** — plumbing widened + admin field added; still needs the real profile URLs |
| HIGH — Organization `logo` | **Unblocked** — code was already correct; needs an OG image uploaded |

Also fixed, found incidentally: **running `php artisan test` was rewriting `public/sitemap.xml` and `llms.txt`** with test-fixture slugs and the local APP_URL, which would have been committed and deployed. Now gated behind `config('seo.autogenerate')`, disabled in `phpunit.xml`.

---

## Method & reproducibility

- Target: production `https://kevinthompsonphd.com` (live), not the dev mirror.
- URL set: complete sitemap (26 URLs), no sampling.
- Tooling: `claude-seo` v2.2.4 skill pack (isolated venv + Playwright Chromium), Lighthouse 13.4.1 CLI, plus direct extraction (`pages.json`).
- The Critical schema bug was found by a specialist and then **independently reproduced and root-caused** before being reported.
- Drift baselines captured for 4 key pages — use `/seo drift compare <url>` to detect regressions from any of the fixes above.

## Artifacts

```
kevinthompsonphd.com-audit/
├── FULL-AUDIT-REPORT.md      this file
├── ACTION-PLAN.md            prioritized fixes with code
├── audit-data.json           structured envelope for PDF generation
├── pages.json                raw per-URL extraction (26 pages)
├── leak-scan.txt             Blade-leak scan across all 26 URLs
├── urls.txt                  audited URL set
└── findings/                 5 specialist reports
```
