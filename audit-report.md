# SEO Audit Report — kevinthompsonphd.com

Date: 2026-05-06 · Scope: Phase 1 read-only audit only · No code changes were made.

---

## 1. SEO Architecture (how the system currently works)

**Custom, no SEO package installed.** No `artesaos/seotools`, `ralphjsmit/laravel-seo`, or `spatie/laravel-seo` in `composer.json`.

### Master layout
[resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) — single layout used by all frontend pages. It already contains:
- `<title>` from `@yield('title', $_appName)` + suffix from `AppSetting`
- `<meta name="description">` from `@yield('meta_description', $_defaultDesc)`
- `<meta name="keywords">` from `@yield('meta_keywords', …)`  *(deprecated by Google but harmless)*
- `<link rel="canonical">` from `url()->current()` *(strips query strings — verified on `?slug=…`)*
- Open Graph tags (`og:type`, `og:url`, `og:site_name`, `og:title`, `og:description`, conditional `og:image`)
- Twitter Card tags (`summary_large_image`, conditional `twitter:image`)
- Favicon, apple-touch-icon, manifest, theme-color, viewport, charset
- Sitewide JSON-LD `@graph`: `Person` + `Organization` + `WebSite` (no SearchAction in the layout block)
- Optional GTM, optional GA4, optional Google/Bing site verification — all driven by `AppSetting`

### Per-page extension pattern
Every page lives under [resources/views/landing-pages/](resources/views/landing-pages/) and `@extends('layouts.app')`. Pages override SEO via `@section()`:
```blade
@section('title', 'Page Title')
@section('meta_description', '...')
@section('meta_keywords', '...')
@section('og_type', 'article')
@section('og_image', asset('storage/...'))
```

### DB-backed dynamic SEO fields
- [app/Models/Post.php](app/Models/Post.php) — `meta_title`, `meta_description`, `meta_keywords` columns (used by [blog-show.blade.php](resources/views/landing-pages/blog-show.blade.php))
- [app/Models/Service.php](app/Models/Service.php) — `meta_title`, `meta_description`, `meta_keywords` (used by [training-show.blade.php](resources/views/landing-pages/training-show.blade.php))
- [app/Models/WelcomePageContent.php](app/Models/WelcomePageContent.php) — `seo_title`, `seo_description`, `seo_keywords` (used by [welcome.blade.php](resources/views/landing-pages/welcome.blade.php))
- [app/Models/AboutPageContent.php](app/Models/AboutPageContent.php) — `seo_title`, `seo_description`, `seo_keywords` (used by [about.blade.php](resources/views/landing-pages/about.blade.php))
- `AppSetting` provides global defaults: `seo_title_suffix`, `seo_default_desc`, `seo_og_image`, `seo_twitter_handle`, `seo_linkedin_url`, `seo_google_verify`, `seo_bing_verify`, `seo_ga4_id`, `seo_gtm_id`, `seo_schema_job_title`, `seo_schema_org_name`

### Per-page JSON-LD
Most pages also emit a *second* JSON-LD block via `@push('scripts')` (renders at the end of `<body>` because `@stack('scripts')` is in the body footer of the layout). Per page:

| Page | Layout JSON-LD | Page JSON-LD | Type emitted |
|---|---|---|---|
| `/` (welcome) | ✓ | ✓ | WebSite + Organization + Person (with SearchAction) |
| `/about-kevin-thompson` | ✓ | ✓ | AboutPage + Person |
| `/agile-consulting-services` | ✓ | ✓ | Service |
| `/agile-training-classes` *(listing)* | ✓ | ✗ | — |
| `/agile-training-classes/{slug}` *(course detail)* | ✓ | ✗ | — |
| `/agile-hardware-papers-and-presentations` | ✓ | ✓ | CollectionPage |
| `/agile-insights-blog` *(listing)* | ✓ | ✓ | Blog |
| `/agile-insights-blog/{slug}` *(post detail)* | ✓ | ✗ | — |
| `/podcasts-webinars` | ✓ | ✓ | CollectionPage |
| `/contact-us` | ✓ | ✓ | ContactPage |

### Sitemap & robots
- [public/sitemap.xml](public/sitemap.xml) — static file, 18 URLs, all non-www HTTPS, includes 7 training-class detail pages and 3 blog post pages. Needs to be regenerated when content changes (no scheduled command found).
- [public/robots.txt](public/robots.txt) — clean. Disallows `/admin /login /livewire /_ignition /telescope /horizon /storage/app/`. Sitemap directive points to non-www HTTPS.
- Both served via [app/Http/Controllers/SeoController.php](app/Http/Controllers/SeoController.php) with `withoutMiddleware([…])` so crawlers get clean responses.
- Bonus: `llms.txt` route exists for AI crawler optimization — modern touch.

### www → non-www redirect
**Already handled at server level** — fixed in our previous session via [/etc/nginx/sites-available/default](/etc/nginx/sites-available/default). All `www.*` and `http://*` URLs return 301 → `https://kevinthompsonphd.com/<path>` in a single hop. HSTS header is now present (`max-age=31536000`). **No middleware needed; do not add one.**

---

## 2. Per-page SEO inventory (live HTML)

Legend: ✓ present and correct · ⚠ present but problematic · ✗ missing

| Page | title | meta desc | canonical | robots | OG | Twitter | JSON-LD | Favicon | Viewport |
|---|---|---|---|---|---|---|---|---|---|
| `/` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ⚠ (conflict — see issue 1) | ✓ | ✓ |
| `/agile-training-classes` | ⚠ (`Kevin Thompson` — fallthrough) | ⚠ (default) | ✓ | ✗ | ⚠ | ⚠ | ⚠ (only sitewide; no Course list) | ✓ | ✓ |
| `/agile-training-classes/{slug}` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ✗ (no `Course` schema) | ✓ | ✓ |
| `/agile-consulting-services` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ⚠ (conflict — issue 1) | ✓ | ✓ |
| `/agile-hardware-papers-and-presentations` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ⚠ (conflict — issue 1) | ✓ | ✓ |
| `/agile-insights-blog` | ⚠ (`Kelvin Enterprise` typo) | ⚠ (typo) | ✓ | ✗ | ⚠ (typo) | ⚠ (typo) | ⚠ (typo + conflict) | ✓ | ✓ |
| `/agile-insights-blog/{slug}` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ✗ (no `Article` schema) | ✓ | ✓ |
| `/podcasts-webinars` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ⚠ (conflict — issue 1) | ✓ | ✓ |
| `/contact-us` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ⚠ (conflict — issue 1) | ✓ | ✓ |
| `/about-kevin-thompson` | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ⚠ (conflict — issue 1) | ✓ | ✓ |

**Hreflang:** Not applicable — site is en-US only. Don't add.

---

## 3. What's already good (DO NOT TOUCH)

- ✅ Layout architecture with `@yield`/`@section` is clean. Reuse it — don't replace.
- ✅ Canonical implementation correctly **strips query strings** (`url()->current()` proven with `?slug=…`). Tab views like `/agile-consulting-services?slug=…` correctly canonicalize to the path-only URL.
- ✅ Open Graph and Twitter tags exist and reuse `@yield('title')` / `@yield('meta_description')` — exactly the right pattern.
- ✅ DB-backed SEO fields on `Post` and `Service` models — admin can edit per-record.
- ✅ AppSetting-driven defaults — sane fallbacks.
- ✅ `og:image:width=1200`/`height=630` set when image present.
- ✅ Server-level www→non-www 301 redirect (single-hop) and HSTS already active.
- ✅ `robots.txt` clean and disallows sensitive paths.
- ✅ `sitemap.xml` URLs all non-www HTTPS; covers all main routes + content detail pages.
- ✅ All canonicals point to non-www HTTPS.
- ✅ `<h1>` present on every page (though see issue 6 for blog-show duplication).
- ✅ Title and description **not duplicated** across the seven main pages (home/services/papers/blog/contact/about/podcasts each have unique titles).
- ✅ `llms.txt` already served — AI-search forward-thinking.
- ✅ Schema validates as JSON (no malformed blocks observed).

---

## 4. Critical gaps (must fix)

### Issue 1 — Conflicting Organization/Person identities across pages 🔴
The most damaging single issue. The layout JSON-LD says `Organization.name = "Kevin Thompson Ph.D. Consulting"` (from `seo_schema_org_name` AppSetting). Page-level JSON-LD blocks use **three different brand names**:

| File | Line | Says |
|---|---|---|
| [welcome.blade.php:355](resources/views/landing-pages/welcome.blade.php#L355) | 355, 368, 377 | `Kelvin Enterprise` (typo) |
| [blog.blade.php:402](resources/views/landing-pages/blog.blade.php#L402) | 402, 414 | `Kelvin Enterprise` (typo) |
| [about.blade.php:121](resources/views/landing-pages/about.blade.php#L121) | 121, 128, 132 | `Kelvin Enterprise` (typo) |
| [services.blade.php:432](resources/views/landing-pages/services.blade.php#L432) | 437, 442 | `Kevin Enterprise` |
| [papers.blade.php:90](resources/views/landing-pages/papers.blade.php#L90) | 92, 100 | `Kevin Enterprise` |
| [contact.blade.php:337](resources/views/landing-pages/contact.blade.php#L337) | 340, 347 | `Kevin Enterprise` |
| [podcasts-webinars.blade.php:245](resources/views/landing-pages/podcasts-webinars.blade.php#L245) | 247, 256 | `Kevin Enterprise` |
| [layouts/app.blade.php:31](resources/views/layouts/app.blade.php#L31) | 31, 106 | `Kevin Thompson Ph.D. Consulting` *(canonical)* |

In addition, the `@id` IRIs differ between layout (`https://kevinthompsonphd.com/#person`, with leading slash) and welcome (`https://kevinthompsonphd.com#person`, no slash) — Google will treat these as different entities. Effect: **no entity consolidation, no knowledge panel possible**, conflicting brand signals.

### Issue 2 — `/agile-training-classes` has no SEO directives 🔴
[training.blade.php](resources/views/landing-pages/training.blade.php) has only `@extends` and content — no `@section('title')`, no `@section('meta_description')`, no JSON-LD. Result: live `<title>` is literally `Kevin Thompson` (the AppSetting `app_name` fallback in [app.blade.php:47](resources/views/layouts/app.blade.php#L47)). This page is a top-of-funnel commercial keyword target ("agile training classes for hardware") and is the worst-optimized page on the site.

Evidence: `curl -s https://kevinthompsonphd.com/agile-training-classes | grep '<title>'` →
```
<title>Kevin Thompson</title>
```

### Issue 3 — Blog post detail pages have no `Article` schema 🔴
[blog-show.blade.php](resources/views/landing-pages/blog-show.blade.php) sets title and description but emits **no JSON-LD** (only the layout sitewide block). No `Article`, no `headline`, no `datePublished`, no `dateModified`, no `author` linkage. This is the #1 blocker for blog rich results.

### Issue 4 — Course detail pages have no `Course` schema 🔴
[training-show.blade.php](resources/views/landing-pages/training-show.blade.php) sets title/description/og but emits **no JSON-LD**. Course rich results (cards in the SERP carousel) require `Course` + `CourseInstance`. Seven training pages currently miss this.

### Issue 5 — Sitelinks search box `urlTemplate` points at a 404 🔴
[welcome.blade.php:361](resources/views/landing-pages/welcome.blade.php#L361):
```php
'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/blog') . '?search={search_term_string}']
```
But `/blog` does not exist — the blog route is `/agile-insights-blog`. The sitelinks search box (when granted by Google) would 404. Plus `Person.url` on the same page (line 381) points to `/about` — also a 404; the route is `/about-kevin-thompson`.

### Issue 6 — Brand typo "Kelvin" in three places 🔴
- [blog.blade.php:3](resources/views/landing-pages/blog.blade.php#L3) — `<title>Blog | Kelvin Enterprise</title>`
- [blog.blade.php:4](resources/views/landing-pages/blog.blade.php#L4) — meta description starts "Read the Kelvin Enterprise blog…"
- [welcome.blade.php:355](resources/views/landing-pages/welcome.blade.php#L355), [about.blade.php:121](resources/views/landing-pages/about.blade.php#L121), [blog.blade.php:402](resources/views/landing-pages/blog.blade.php#L402) — JSON-LD uses "Kelvin Enterprise"

User confirms the brand is **"Kevin Enterprise"** / **"Kevin Thompson Ph.D. Consulting"**. Every "Kelvin" is a typo and is currently being indexed by Google.

### Issue 7 — Duplicate `<h1>` on blog post pages 🔴
[blog-show.blade.php](resources/views/landing-pages/blog-show.blade.php) renders two `<h1>` elements — one with class `page-title` and a separate `<h1>` inside the article. Only one `<h1>` per page is best practice; multiple H1s dilute the keyword signal Google extracts.

Evidence:
```
<h1 class="page-title">Embedded Software</h1>
<h1>Embedded Software</h1>
```

---

## 5. Minor improvements (nice to have)

### M1 — No `<meta name="robots">` directive
Layout has none. Recommend explicit `<meta name="robots" content="index, follow, max-image-preview:large">` for clarity (the `max-image-preview:large` token is what enables larger thumbnails in Discover and SERPs).

### M2 — Sitemap is static and manually maintained
[public/sitemap.xml](public/sitemap.xml) was last regenerated 2026-05-06. New blog posts and training classes won't appear until someone re-runs whatever generates it. Recommend either a scheduled artisan command or `spatie/laravel-sitemap` driven from the same models.

### M3 — `meta_keywords` is being set everywhere
Google has ignored the keywords meta tag since 2009. Harmless but ~150 chars per page of bytes for nothing. Optional: drop it from the layout and views.

### M4 — `og:locale` missing
Add `<meta property="og:locale" content="en_US">` once in the layout.

### M5 — JSON-LD lives in `<body>` (via `@push('scripts')`), not `<head>`
Google accepts both, but `<head>` is conventional. Add a dedicated `@stack('schema')` in `<head>` and have pages push schema there instead of into `scripts`.

### M6 — `og:image` default is conditional on `seo_og_image` AppSetting being non-empty
If the admin hasn't uploaded a default OG image, social shares of pages without page-specific `og_image` will lack a preview thumbnail. Verify `AppSetting('seo_og_image')` is set in production.

### M7 — `BreadcrumbList` schema is missing on all interior pages
Earns breadcrumb display in Google SERPs (in place of plain URLs). Currently zero pages emit it.

### M8 — `FAQPage` schema is missing
Service and pillar pages are excellent candidates. Five-to-eight Q&As per page can earn AI Overview / People Also Ask placements.

### M9 — `VideoObject` schema is missing on `/podcasts-webinars`
Page has `CollectionPage` schema but individual videos are not marked up — they cannot appear in Google Videos search.

### M10 — `Person` block in layout is minimal
[layouts/app.blade.php:89-95](resources/views/layouts/app.blade.php#L89-L95) only sets `name`, `jobTitle`, `url`. Missing: `honorificSuffix`, `alumniOf`, `hasCredential` (PhD, CSP, ACP, PMP), `description`, `givenName`/`familyName`. These are E-E-A-T signals.

### M11 — `Organization` block in layout is minimal
[layouts/app.blade.php:103-109](resources/views/layouts/app.blade.php#L103-L109) only sets `name`, `url`, `founder`. Missing: `logo`, `contactPoint`, `sameAs`, `knowsAbout`, `areaServed`. These help knowledge-graph reconciliation.

### M12 — `@yield` defaults render even when fallback is empty
Line 47 — `<title>@yield('title', $_appName){{ $_suffix }}</title>` works, but if `$_appName` ever returns empty the user sees the suffix only. Defensive — consider hardening the fallback string.

### M13 — Robots.txt does not block query-string variants
Although canonicals handle the `?slug=` and `?category=` cases, you could add `Disallow: /*?slug=` and `Disallow: /*?category=` for belt-and-braces (optional).

---

## 6. Prioritized fix list

### Tier A — fixes for next push (highest ROI, lowest risk)

1. **Unify brand naming** site-wide. Decision needed from you: is it `Kevin Enterprise` or `Kevin Thompson Ph.D. Consulting`? Once chosen, replace every `Kelvin Enterprise` (typo, 3 files) and align all JSON-LD `Organization.name` fields with the AppSetting `seo_schema_org_name`. *(Issues 1 + 6)*
2. **Fix `/agile-training-classes` SEO directives** — add `@section('title')`, `@section('meta_description')`, etc. to [training.blade.php](resources/views/landing-pages/training.blade.php). *(Issue 2)*
3. **Fix the broken `urlTemplate` and `Person.url`** in [welcome.blade.php:361,381](resources/views/landing-pages/welcome.blade.php#L361). *(Issue 5)*
4. **Add `Article` schema** to [blog-show.blade.php](resources/views/landing-pages/blog-show.blade.php) using existing `Post` model fields (`title`, `excerpt`, `created_at`, `updated_at`, `featured_image`, etc.). *(Issue 3)*
5. **Add `Course` schema** to [training-show.blade.php](resources/views/landing-pages/training-show.blade.php) using existing `Service` model fields. *(Issue 4)*
6. **Remove duplicate `<h1>`** on blog post pages — keep only the article `<h1>`, demote the page-header version to a styled `<div class="page-title">` or `<p>`. *(Issue 7)*

### Tier B — schema enrichment (next 2 weeks)

7. Consolidate the layout-level `@graph` and remove conflicting page-level Person/Organization re-declarations. Each page should only emit schema *new to that page* (Service, Course, Article, FAQPage, BreadcrumbList) and reference the canonical Person/Organization via `@id` instead of re-defining them.
8. Add `BreadcrumbList` schema to all non-homepage views.
9. Add `FAQPage` schema to homepage, services page, and (later) pillar blog posts.
10. Add `VideoObject` schema to each podcast/webinar item on `/podcasts-webinars`.
11. Enrich layout `Person` and `Organization` with credentials, sameAs, logo, contactPoint.
12. Add `<meta name="robots" content="index, follow, max-image-preview:large">` to the layout.

### Tier C — content / DB review (handled in `suggested-meta-updates.md` — Phase 2)

13. Review and replace generic titles/descriptions on hardcoded pages (services, papers, blog, contact, podcasts) with keyword-targeted versions — **as suggestions only, for your manual approval**.
14. Convert `?slug=…` query-string sub-services on `/agile-consulting-services` into proper path segments and 301 the old URLs.

### Tier D — infra (separate ticket)

15. Replace static [public/sitemap.xml](public/sitemap.xml) with an auto-generated version (artisan scheduled command or `spatie/laravel-sitemap`).
16. Add a default `seo_og_image` in AppSettings if not set.
17. Migrate JSON-LD blocks from `@push('scripts')` (renders in `<body>`) to a new `@push('schema')` + `@stack('schema')` in `<head>`.

---

## 7. Decisions needed from you before Phase 2

1. **Brand canonical name.** Three options on the site today: `Kevin Enterprise`, `Kelvin Enterprise` (typo, definitely retire), `Kevin Thompson Ph.D. Consulting`. **Recommendation: `Kevin Thompson Ph.D. Consulting`** as the legal/SEO name (matches the layout `seo_schema_org_name` AppSetting and the domain). `Kevin Enterprise` could remain as `alternateName`. Confirm before I propagate.
2. **Should I touch existing titles/descriptions** that are *not* typos but are keyword-weak (e.g. services, papers)? Per Phase 2 rule G, my plan is to write `suggested-meta-updates.md` for manual review — confirm that's what you want.
3. **OK to remove `meta_keywords`** site-wide (M3)? It's deprecated noise.
4. **OK to remove `<h1 class="page-title">` styling** and replace with a non-`<h1>` element on `blog-show.blade.php` (Issue 7)? This changes the visual class on the page header — no styling change otherwise.

---

**Phase 1 complete. Awaiting approval before starting Phase 2 fixes.**
