# SEO Architecture Guide

> Read this first when adding a new page, debugging missing schema, or asking
> "where does X come from in the HTML head."
>
> This file documents the SEO system that lives in this codebase. It is the
> single source of truth — sub-systems referenced here (`SeoGenerator`,
> `AppServiceProvider`, layout, models) must remain consistent with this guide,
> or this guide must be updated alongside the change.

---

## TL;DR — the four moving parts

1. **Master layout** — [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) renders every meta tag, OG tag, Twitter card, favicon, viewport, and the **sitewide JSON-LD `@graph`** (Person + Organization + WebSite + SearchAction).
2. **Per-page Blade views** override SEO via `@section('title')`, `@section('meta_description')`, `@section('og_image')`, etc., and push **page-specific schema** (Article / Course / Service / FAQPage / etc.) via `@push('scripts')`.
3. **Models with SEO fields** (`Post`, `Service`, `WelcomePageContent`, `AboutPageContent`) and **`AppSetting`** provide the dynamic copy that populates the layout and per-page views.
4. **`SeoGenerator`** writes static `public/robots.txt`, `public/sitemap.xml`, and `public/llms.txt` files. It runs **automatically** when content changes (Eloquent saved/deleted hooks) and can also be run manually with `php artisan seo:generate`.

No SEO package is installed (`spatie/laravel-sitemap`, `artesaos/seotools`, etc. are NOT present). The system is custom — keep it that way unless you have a specific reason to migrate.

---

## File map

| File | Purpose |
|---|---|
| [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) | Master layout. Owns `<title>`, `<meta>`, OG/Twitter tags, canonical, favicons, sitewide JSON-LD `@graph`, GTM/GA4 injection. |
| [resources/views/landing-pages/*.blade.php](resources/views/landing-pages/) | Per-page views. `@extends('layouts.app')` + `@section()` overrides + `@push('scripts')` for page-specific JSON-LD. |
| [resources/views/errors/layout.blade.php](resources/views/errors/layout.blade.php) | Branded error-page shell extending `layouts.app`. Pushes `<meta name="robots" content="noindex, follow">`. |
| [resources/views/errors/{404,403,500,503}.blade.php](resources/views/errors/) | Per-status messaging. Each `@extends('errors.layout')`. |
| [resources/views/seo/llms.blade.php](resources/views/seo/llms.blade.php) | The `llms.txt` template rendered by `SeoGenerator::generateLlms()`. |
| [app/Services/SeoGenerator.php](app/Services/SeoGenerator.php) | Writes `robots.txt`, `sitemap.xml` (with `<image:image>` entries), and `llms.txt` to `public/`. |
| [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php) | Registers Eloquent saved/deleted hooks that auto-call `SeoGenerator`. Forces HTTPS via `URL::forceScheme('https')`. |
| [app/Console/Commands/GenerateSeoFiles.php](app/Console/Commands/GenerateSeoFiles.php) | The `seo:generate` Artisan command. Supports `--robots`, `--sitemap`, `--llms` flags. |
| [app/Http/Controllers/SeoController.php](app/Http/Controllers/SeoController.php) | Serves the static files at `/robots.txt`, `/sitemap.xml`, `/llms.txt` with correct Content-Type and cache headers, **outside** of session/CSRF middleware so crawlers get clean responses. |
| [routes/web.php](routes/web.php) | Has a `withoutMiddleware([...])` group that mounts the three SEO file routes. **Don't move them** — they need clean responses without cookies. |
| [public/robots.txt](public/robots.txt) | **Generated.** Do not edit by hand — the next `seo:generate` run will overwrite it. |
| [public/sitemap.xml](public/sitemap.xml) | **Generated.** Do not edit by hand. |
| [public/llms.txt](public/llms.txt) | **Generated.** Do not edit by hand. |
| [/etc/nginx/sites-available/default](/etc/nginx/sites-available/default) | Server-level redirects: HTTP→HTTPS canonical, www→non-www (single hop), HSTS header. **Not in repo.** |

---

## How meta tags work (Blade layout pattern)

The master layout uses Blade's `@yield` directive with sensible defaults. Per-page views provide values via `@section`. Defaults come from the `AppSetting` model.

### Layout — what's already wired (do not duplicate)

```blade
<title>@yield('title', $_appName){{ $_suffix }}</title>
<meta name="description" content="@yield('meta_description', $_defaultDesc)">
<meta name="keywords"    content="@yield('meta_keywords', '...')">
<link rel="canonical"    href="{{ url()->current() }}">

<meta property="og:type"        content="@yield('og_type', 'website')">
<meta property="og:url"         content="{{ url()->current() }}">
<meta property="og:site_name"   content="{{ $_appName }}">
<meta property="og:title"       content="@yield('title', $_appName){{ $_suffix }}">
<meta property="og:description" content="@yield('meta_description', $_defaultDesc)">
<meta property="og:image"       content="@yield('og_image', $_ogImage)">

<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="@yield('title', $_appName){{ $_suffix }}">
<meta name="twitter:description" content="@yield('meta_description', $_defaultDesc)">
<meta name="twitter:image"       content="@yield('og_image', $_ogImage)">
```

### Per-page — the four things you actually set

```blade
@extends('layouts.app')

@section('title',            'Agile Hardware Consulting | Kevin Thompson, Ph.D.')
@section('meta_description', 'Short, action-oriented description under 155 chars.')
@section('og_type',          'article')           {{-- only for blog posts; default is 'website' --}}
@section('og_image',         asset('images/og/page-specific.jpg')) {{-- if you have a per-page image --}}
```

**Important:** the layout strips `?slug=…` and other query strings from `url()->current()` (Laravel default), so canonical URLs are clean. `?slug=foo` and `?slug=bar` both canonicalize to the path-only URL — this is intentional and **must not change**.

### What NOT to add to a per-page view
- Do **not** redeclare `<title>`, `<meta>`, `<link rel="canonical">` directly. The layout owns them.
- Do **not** declare a second `Organization` or `Person` JSON-LD block — the layout already emits them as canonical entities. Reference them via `@id` (see "JSON-LD" below).
- Do **not** set OG image to a `kevin.devifyo.cloud` or other dev domain. Use `asset()` so the URL always comes from `APP_URL`.

---

## DB-backed SEO fields

Some pages pull title/description from the database so admins can edit without touching code.

| Model | Fillable SEO fields | Used by |
|---|---|---|
| `Post` | `meta_title`, `meta_description`, `meta_keywords`, `featured_image`, `excerpt`, `content`, `published_at` | [blog-show.blade.php](resources/views/landing-pages/blog-show.blade.php) (Article schema reads `published_at`, `updated_at`, `author->name`, `category->name`). |
| `Service` | `meta_title`, `meta_description`, `meta_keywords`, `short_description`, `featured_image`, `length`, `audience` | [training-show.blade.php](resources/views/landing-pages/training-show.blade.php) (Course schema reads `length`, `audience`, etc.). |
| `WelcomePageContent` | `seo_title`, `seo_description`, `seo_keywords` | [welcome.blade.php](resources/views/landing-pages/welcome.blade.php). |
| `AboutPageContent` | `seo_title`, `seo_description`, `seo_keywords`, `profile_image` | [about.blade.php](resources/views/landing-pages/about.blade.php). |

When `meta_title` / `seo_title` is empty, the view falls back to `$model->title` or a hardcoded string. Keep that fallback so the page never renders an empty `<title>`.

---

## AppSetting keys (sitewide SEO defaults)

Edit via the admin (Livewire `AppSettings` component) or `php artisan tinker`. Any key whose name starts with `seo_` (or equals `app_name`) **automatically** triggers `SeoGenerator::generateAll()` on save (see `AppServiceProvider::registerSeoHooks`).

| Key | What it does | Read in |
|---|---|---|
| `app_name` | Brand name. Used as `og:site_name` and `<title>` fallback. | layout, `SeoGenerator::generateLlms()` |
| `seo_title_suffix` | String appended to every page title (e.g. " — Brand"). Optional. | layout |
| `seo_default_desc` | Fallback meta description when a page doesn't set one. | layout |
| `seo_og_image` | Default OG image URL (1200×630). | layout |
| `seo_twitter_handle` | Twitter / X handle without `@`. Populates `twitter:site` / `twitter:creator` and Person/Organization `sameAs`. | layout |
| `seo_linkedin_url` | LinkedIn profile URL. Populates Person/Organization `sameAs`. | layout |
| `seo_google_verify` | Search Console site-verification token. | layout |
| `seo_bing_verify` | Bing Webmaster verification token. | layout |
| `seo_ga4_id` | GA4 Measurement ID. Auto-suppressed when `seo_gtm_id` is set. | layout |
| `seo_gtm_id` | Google Tag Manager container ID. | layout |
| `seo_schema_org_name` | Canonical Organization name in JSON-LD (`Kevin Thompson Ph.D.`). | layout |
| `seo_schema_job_title` | Person `jobTitle`. | layout |
| `seo_robots_disallow_extra` | Extra `Disallow:` lines added to `robots.txt`. Newline-separated. | `SeoGenerator::generateRobots()` |
| `seo_sitemap_blog` | `'1'` to include published blog posts in sitemap, `'0'` to skip. | `SeoGenerator::generateSitemap()` |
| `seo_sitemap_training` | `'1'` to include active training classes, `'0'` to skip. | `SeoGenerator::generateSitemap()` |
| `seo_sitemap_static_pages` | JSON array of static page entries — see `Livewire\Admin\AppSettings::DEFAULT_STATIC_PAGES`. | `SeoGenerator::staticPages()` |
| `seo_llms_description` | First-line description in `llms.txt`. | `SeoGenerator::generateLlms()` |
| `seo_llms_extra` | Extra free-form text appended to `llms.txt`. | `SeoGenerator::generateLlms()` |

**The brand identity is `Kevin Thompson Ph.D.`** with `alternateName: "Kevin Enterprise"`. Do not introduce "Kelvin Enterprise" (typo, retired) or rename to anything else without updating the AppSetting `seo_schema_org_name` AND every Blade reference simultaneously.

---

## JSON-LD structured data

### Sitewide canonical entities (rendered once, in the layout)

The layout emits a single `@graph` containing **Person**, **Organization**, and **WebSite** (with `SearchAction`). Each has a stable `@id`:

```
https://kevinthompsonphd.com/#person
https://kevinthompsonphd.com/#organization
https://kevinthompsonphd.com/#website
```

These IDs are the canonical references for those entities **everywhere on the site**. Per-page schema must reference them via `@id`, never redefine them.

### Per-page schema (push via `@push('scripts')`)

Each page-type emits its own schema block in `@push('scripts')`. Pattern:

```blade
@push('scripts')
@php
    $_jsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'Article',           // or Course, Service, FAQPage, ItemList, etc.
        'name'     => 'Page name',
        'url'      => url()->current(),
        'author'   => ['@id' => url('/') . '/#person'],         // ← @id reference
        'publisher'=> ['@id' => url('/') . '/#organization'],   // ← @id reference
        // …page-specific fields…
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $_jsonLd !!}</script>
@endpush
```

The layout has a `@stack('schema')` directive in `<head>` available for future per-page schema — currently unused; per-page schema lives in `@push('scripts')` which renders in `<body>`. Both placements are valid per Google's docs.

### Schema types currently emitted

| Page | Schema |
|---|---|
| `/` | Sitewide `@graph` only |
| `/about-kevin-thompson` | `AboutPage` (with `mainEntity` and `publisher` via `@id`) |
| `/agile-consulting-services` | `Service` (provider via `@id`) |
| `/agile-training-classes` (listing) | `ItemList` of `Course` items |
| `/agile-training-classes/{slug}` | `Course` with `hasCourseInstance`, `offers`, provider/instructor via `@id` |
| `/agile-insights-blog` | `Blog` (author/publisher via `@id`) |
| `/agile-insights-blog/{slug}` | `Article` (datePublished/dateModified/wordCount/articleSection/image — all from the `Post` model) |
| `/agile-hardware-papers-and-presentations` | `CollectionPage` |
| `/podcasts-webinars` | `CollectionPage` |
| `/contact-us` | `ContactPage` |

### Schema types still missing (Tier B follow-ups)

- `BreadcrumbList` on every interior page
- `FAQPage` on homepage and service pages (5–8 Q&As each)
- `VideoObject` per podcast/webinar item
- `Review` schema on testimonials when added

### Validating schema

Before pushing a schema change, validate with:
- https://validator.schema.org (universal)
- https://search.google.com/test/rich-results (Google-specific eligibility)

To validate every JSON-LD block on the site at once locally:

```bash
for path in "/" "/agile-training-classes" "/agile-insights-blog/embedded-software"; do
  curl -s "https://kevinthompsonphd.com${path}" | python3 -c "
import sys, re, json
html = sys.stdin.read()
for b in re.findall(r'<script type=\"application/ld\\+json\">(.*?)</script>', html, re.DOTALL):
    try: json.loads(b)
    except Exception as e: print(f'INVALID at ${path}: {e}')
print('${path}: OK')"
done
```

---

## Auto-generation: how `sitemap.xml` and `llms.txt` stay fresh

**You should never manually edit `public/{robots.txt,sitemap.xml,llms.txt}`.** They are regenerated automatically.

### The hook chain

[app/Providers/AppServiceProvider.php — `registerSeoHooks()`](app/Providers/AppServiceProvider.php) registers Eloquent model events:

```
Post saved/deleted     → SeoGenerator::generateSitemap() + generateLlms()
Service saved/deleted  → SeoGenerator::generateSitemap() + generateLlms()
Paper saved/deleted    → SeoGenerator::generateLlms()
AppSetting saved
  if key starts with "seo_" or == "app_name"
                       → SeoGenerator::generateAll()  (all three files)
```

Practical implications:
- Publishing a new blog post: `Post::save()` → sitemap.xml automatically gets a new `<url>` entry with the post's `featured_image` as an `<image:image>` child.
- Editing a training class: `Service::save()` → sitemap entry's `<lastmod>` updates and the JSON-LD `ItemList` on `/agile-training-classes` reflects new content on the next request.
- Changing the brand name in admin: `AppSetting::save()` for key `seo_schema_org_name` → all three files regenerate.

**There is no scheduled job or cron** because the hook-driven approach catches every meaningful change. If you ever add a new content model (e.g. `CaseStudy`), wire its `saved`/`deleted` hooks here too.

### Manual regeneration (`seo:generate`)

Run on demand:

```bash
php artisan seo:generate              # all three files
php artisan seo:generate --robots     # robots.txt only
php artisan seo:generate --sitemap    # sitemap.xml only
php artisan seo:generate --llms       # llms.txt only
```

When to run manually:
- After a content import/migration that bypassed Eloquent events.
- After deploying a code change that affects sitemap shape (e.g. adding image entries — like we did 2026-05-06).
- During first-time setup of a fresh environment.

### What goes into each file

| File | Source | Frequency |
|---|---|---|
| `robots.txt` | `User-agent: *`, `Disallow: /admin /login /livewire /_ignition` (hardcoded), plus optional `seo_robots_disallow_extra`, plus the `Sitemap:` directive pointing at the absolute non-www URL. | Regenerated on AppSetting change. |
| `sitemap.xml` | Static pages from `seo_sitemap_static_pages` AppSetting (or default list), plus published `Post` URLs (with their `featured_image` as `<image:image>`), plus active training `Service` URLs (with their `featured_image`), plus the headshot for `/` and `/about-kevin-thompson`. Uses the `xmlns:image` namespace. | Regenerated on Post/Service save and AppSetting save. |
| `llms.txt` | The Blade view at [resources/views/seo/llms.blade.php](resources/views/seo/llms.blade.php) renders site description + a structured list of recent posts, training classes, and active papers. | Regenerated on Post/Service/Paper save and AppSetting save. |

### Routes that serve the files

Mounted in [routes/web.php](routes/web.php) inside a `Route::withoutMiddleware([...])` group so crawlers receive responses without session cookies, CSRF tokens, or visitor-tracking writes:

```
GET /robots.txt   → SeoController::robots()
GET /sitemap.xml  → SeoController::sitemap()
GET /llms.txt     → SeoController::llms()
```

The controller just `file_get_contents()` from `public/` and returns with the right Content-Type and `Cache-Control: max-age=…`.

---

## Image SEO

Two concerns: the `<img>` element on the page, and the image's discoverability via the sitemap.

### `<img>` markup pattern

```blade
<figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <link itemprop="url"    href="{{ asset('img/.../headshot.webp') }}">
    <meta itemprop="width"  content="320">
    <meta itemprop="height" content="400">
    <img
        src="{{ asset('img/.../headshot.webp') }}"
        alt="Portrait of Dr. Kevin Thompson, Ph.D. — Agile hardware development consultant"
        title="Dr. Kevin Thompson, Ph.D. — Agile Hardware Consultant"
        width="320" height="400"
        loading="eager"          {{-- "lazy" for below-the-fold --}}
        fetchpriority="high"     {{-- only the LCP image --}}
        decoding="async"
        itemprop="contentUrl">
    <figcaption itemprop="caption" style="position:absolute;width:1px;height:1px;...">
        Descriptive caption with keyword.
    </figcaption>
</figure>
```

Rules:
- **Always `asset()`** for site-hosted images. Never hardcode `https://kevin.devifyo.cloud/...` or any other domain.
- **Alt text** must describe the image AND include the target keyword once, naturally. Never dump keywords. For decorative images, `alt=""`.
- **Above-the-fold images**: `loading="eager" fetchpriority="high"`. **Below-the-fold**: `loading="lazy"`.
- **`width` and `height`** attributes are required to prevent CLS.
- **`decoding="async"`** is a safe default for everything.
- The `figcaption` can be screen-reader-only via the inline visually-hidden style shown above. Don't omit it for the headshot; Google reads it.

### Image sitemap entries

`SeoGenerator::generateSitemap()` emits `<image:image>` children for:
- The headshot at `/` and `/about-kevin-thompson` (always — set in code).
- Each published `Post`'s `featured_image` (when present).
- Each active training `Service`'s `featured_image` (when present).

To add image entries for a new model, edit `SeoGenerator::generateSitemap()` and follow the existing pattern:

```php
$entry['images'][] = [
    'loc'     => url(\Illuminate\Support\Facades\Storage::url($model->featured_image)),
    'title'   => $model->title,
    'caption' => $model->excerpt ?: $model->title,
];
```

The `<image:loc>` MUST be absolute. `Storage::url()` returns a relative `/storage/...` path; wrap it in `url()` to absolutize.

### Person/Organization image (knowledge-graph)

The layout's Person schema has an `image` field with `ImageObject`:

```json
"image": {
  "@type":  "ImageObject",
  "url":    "https://kevinthompsonphd.com/img/frontend/Dr.%20Kevin%20Thompson.webp",
  "width":  320,
  "height": 400,
  "caption":"Dr. Kevin Thompson, Ph.D. — Agile hardware consultant"
}
```

This is the image Google uses for the knowledge panel thumbnail when one is granted. Keep the URL absolute and reachable; do not move the file without updating both the layout and `SeoGenerator`.

---

## Server-level configuration (nginx)

Not in the repo but critical to SEO. See [/etc/nginx/sites-available/default](/etc/nginx/sites-available/default).

| Concern | Status |
|---|---|
| HTTP → HTTPS redirect | ✅ 301 to canonical non-www HTTPS |
| `www.*` → non-www | ✅ Single-hop 301 to `https://kevinthompsonphd.com/...` |
| HSTS | ✅ `max-age=31536000` (no `includeSubDomains` yet — see manual-followups.md) |
| `X-Frame-Options`, `X-Content-Type-Options` | ✅ Set globally |
| Static asset caching (`*.css`, `*.webp`, etc.) | ✅ `expires 1y; Cache-Control public, immutable` |

Backup of the previous nginx config (before the canonical fix) is at `/root/nginx-default.bak.*`. Do not delete.

If you change the nginx config, run `nginx -t && systemctl reload nginx` and verify the redirect chain with:

```bash
for u in "http://www.kevinthompsonphd.com/" "https://www.kevinthompsonphd.com/foo" "http://kevinthompsonphd.com/"; do
    curl -sI -o /dev/null -w "${u} → %{http_code} → %{redirect_url}\n" "$u"
done
```

Each should return `301` with a `Location:` straight to `https://kevinthompsonphd.com/...` — single hop, no chain.

---

## Error pages

Branded 404/403/500/503 pages live in [resources/views/errors/](resources/views/errors/). Each `@extends('errors.layout')` and provides four `@section`s: `err_code`, `err_kicker`, `err_heading`, `err_message`.

The shared layout pushes `<meta name="robots" content="noindex, follow">` so error pages do not get indexed but their helpful links still get crawled. Real HTTP status codes are preserved — these are NOT soft-200 pages.

To add a new status (e.g. 410 Gone for retired URLs), copy `errors/404.blade.php` → `errors/410.blade.php` and adjust copy. Laravel auto-loads `resources/views/errors/{statusCode}.blade.php`.

---

## Recipes

### Add a new content page (admin-driven)

1. Add the route in [routes/web.php](routes/web.php) and a controller method.
2. Create `resources/views/landing-pages/your-page.blade.php` that `@extends('layouts.app')`.
3. At the top of the view set:
   ```blade
   @section('title',            'Keyword-first title | Kevin Thompson, Ph.D.')
   @section('meta_description', '<155 char description with primary keyword and CTA.')
   ```
4. Add appropriate JSON-LD in `@push('scripts')` referencing canonical Person/Organization via `@id`.
5. If the page should appear in `sitemap.xml`, edit the `seo_sitemap_static_pages` AppSetting JSON to include it (or add it to `Livewire\Admin\AppSettings::DEFAULT_STATIC_PAGES`).
6. Run `php artisan seo:generate` to refresh.

### Add a new content model (e.g. `CaseStudy`)

1. Create the migration with at least: `slug`, `title`, `meta_title`, `meta_description`, `featured_image`, `published_at`, `is_active`, `updated_at`.
2. In [app/Providers/AppServiceProvider.php — `registerSeoHooks()`](app/Providers/AppServiceProvider.php) wire the model:
   ```php
   \App\Models\CaseStudy::saved($sitemapAndLlms);
   \App\Models\CaseStudy::deleted($sitemapAndLlms);
   ```
3. In [app/Services/SeoGenerator.php — `generateSitemap()`](app/Services/SeoGenerator.php) loop the model and emit `<url>` + `<image:image>` entries.
4. Optionally update `generateLlms()` and the [seo/llms.blade.php](resources/views/seo/llms.blade.php) template.
5. Add `Article` (or appropriate type) JSON-LD in the model's detail Blade view.

### Add a new schema type (e.g. FAQPage)

1. Pick the page (homepage, services, a specific post).
2. Add a `@push('scripts')` block at the bottom of that view with the JSON-LD. Use **real, searcher-style** Q&As (look at "People also ask" for the page's target keyword in Google). Always reference Person/Organization via `@id`.
3. Validate at https://validator.schema.org and https://search.google.com/test/rich-results.

### Debug "my page has the wrong title in Google"

Run through this list in order:
1. `curl -s https://kevinthompsonphd.com/<path> | grep '<title>'` — does the live HTML have the title you expect?
2. If yes but Google shows old title: it hasn't recrawled yet. Open Search Console → URL Inspection → Request Indexing.
3. If no: check the relevant Blade for `@section('title', …)`. If it sources from a model, run `php artisan tinker` and inspect the field.
4. Clear caches: `php artisan view:clear && php artisan config:clear`.

### Debug "schema not appearing in Rich Results Test"

1. View page source, find `<script type="application/ld+json">` blocks. Copy each into https://validator.schema.org.
2. Common issues:
   - `image` is a relative URL (`/storage/...`) instead of absolute. Wrap with `url(...)`.
   - `datePublished`/`dateModified` are missing. Use `optional($model->published_at)->toAtomString()`.
   - `Article.headline` is over 110 chars. Use `mb_substr(..., 0, 110)`.
   - Required fields missing for Course rich results: `provider`, `instructor`, `hasCourseInstance` (with `courseMode`), and `offers`.
3. After fixing, request reindexing in Search Console.

---

## Common pitfalls

- **Hardcoding domain URLs.** Anywhere you write `https://kevinthompsonphd.com/...` or `https://kevin.devifyo.cloud/...` is a future bug. Use `asset()` for static files in `public/`, `url()` for absolute URLs, `route('name')` for named routes. `URL::forceScheme('https')` (in `AppServiceProvider`) ensures these always come back as HTTPS.
- **Editing `public/sitemap.xml` directly.** It will be overwritten the next time anyone saves a Post, Service, Paper, or AppSetting. Edit `SeoGenerator` instead.
- **Adding a `<meta name="robots">` to a page-level Blade.** The site has no general robots meta yet (intentional — defaults to indexable). For a single page that should NOT be indexed, push to the `@stack('schema')` slot in the layout's `<head>`:
  ```blade
  @push('schema')<meta name="robots" content="noindex, follow">@endpush
  ```
- **Forgetting Person/Organization `@id` references.** Every per-page schema's `author`/`publisher`/`provider`/`instructor` field must be `['@id' => url('/') . '/#person']` or `'#organization'`, never an inline `Person`/`Organization` block — that creates entity-graph conflicts.
- **Setting `seo_default_desc` to a generic phrase.** It's the fallback shown in Google for any page that doesn't override it. Keep it tight, keyword-bearing, under 155 chars.
- **Removing `withoutMiddleware([…])` from the SEO routes.** That group strips session, CSRF, cookie encryption, and visitor tracking so crawlers get a clean response. If you change route groupings, preserve those exclusions.
- **Renaming a published URL without a 301.** Every previously-indexed URL must redirect (server-level or `Route::redirect()`) to its new home. Never let an indexed URL 404.

---

## Quick verification commands

```bash
# Are there hardcoded dev domains anywhere?
grep -rn "devifyo\.cloud\|localhost:[0-9]*\|127\.0\.0\.1" resources/views app

# Brand sweep — should only find the intentional alternateName in the layout
grep -rn "Kelvin Enterprise\|Kevin Enterprise" resources/views app

# Validate sitemap
php -r '$d=new DOMDocument(); echo $d->load("public/sitemap.xml") ? "OK\n" : "BAD\n";'

# Count URLs and images in sitemap
php -r '$d=new DOMDocument(); $d->load("public/sitemap.xml");
echo "URLs: ".$d->getElementsByTagName("url")->length."\n";
echo "Images: ".$d->getElementsByTagNameNS("http://www.google.com/schemas/sitemap-image/1.1","image")->length."\n";'

# Confirm www → non-www single-hop redirects
for u in "https://www.kevinthompsonphd.com/" "http://www.kevinthompsonphd.com/agile-insights-blog"; do
    curl -sI -o /dev/null -w "$u  →  %{http_code}  →  %{redirect_url}\n" "$u"
done

# Regenerate everything (sitemap, robots, llms)
php artisan seo:generate
```

---

## Companion documents

- [audit-report.md](audit-report.md) — original SEO audit (2026-05-06).
- [seo-implementation-log.md](seo-implementation-log.md) — every file changed in the implementation pass and why.
- [suggested-meta-updates.md](suggested-meta-updates.md) — title/description rewrites awaiting manual approval.
- [manual-followups.md](manual-followups.md) — Search Console, Wikidata, OG image upload, HSTS preload, etc.

When this guide and one of those documents disagree, **this guide is authoritative**. Update the others to match, or update this one to reflect new reality.
