# SEO Implementation Log — Phase 2 (Tier A)

Date: 2026-05-06

## Files modified

| File | What changed |
|---|---|
| [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) | Enriched sitewide JSON-LD `@graph`: added `givenName`/`familyName`/`honorificSuffix`/`description`/`worksFor`/`knowsAbout` to Person; added `alternateName: "Kevin Enterprise"`/`knowsAbout`/`areaServed`/conditional `logo`/`sameAs` to Organization; added WebSite with `inLanguage` + `SearchAction` (urlTemplate now points at `/agile-insights-blog`, was missing). Added `@stack('schema')` in `<head>` for future per-page schema pushes. |
| [resources/views/landing-pages/welcome.blade.php](resources/views/landing-pages/welcome.blade.php) | Removed page-level WebSite + Organization + Person redefinition (those were "Kelvin Enterprise" duplicates that contradicted the layout). Layout now owns those entities; pages reference them via `@id`. |
| [resources/views/landing-pages/about.blade.php](resources/views/landing-pages/about.blade.php) | Replaced 3× "Kelvin Enterprise" inline duplicates with `@id` references. AboutPage `mainEntity`/`publisher` now point to canonical Person/Organization. |
| [resources/views/landing-pages/blog.blade.php](resources/views/landing-pages/blog.blade.php) | Replaced 3× "Kelvin Enterprise" with the brand "Kevin Thompson, Ph.D." in `<title>`, meta description, and the Blog JSON-LD. Inline `Person`/`Organization` swapped for `@id` references. |
| [resources/views/landing-pages/services.blade.php](resources/views/landing-pages/services.blade.php) | Replaced "Kevin Enterprise" copy in meta description and Service schema. `provider` switched to `@id` reference. Added `audience` (BusinessAudience). |
| [resources/views/landing-pages/papers.blade.php](resources/views/landing-pages/papers.blade.php) | Replaced "Kevin Enterprise" in meta description and CollectionPage schema. `author`/`publisher` switched to `@id` references. |
| [resources/views/landing-pages/contact.blade.php](resources/views/landing-pages/contact.blade.php) | Replaced "Kevin Enterprise" in meta description and ContactPage schema. `publisher` switched to `@id`; `about` now references the Person `@id`. |
| [resources/views/landing-pages/podcasts-webinars.blade.php](resources/views/landing-pages/podcasts-webinars.blade.php) | Replaced "Kevin Enterprise" in meta description and CollectionPage schema. `author`/`publisher` switched to `@id` references. |
| [resources/views/landing-pages/training.blade.php](resources/views/landing-pages/training.blade.php) | **Critical fix.** Added `@section('title')`, `@section('meta_description')`, `@section('meta_keywords')` (page had none — `<title>` was just "Kevin Thompson"). Added Course `ItemList` JSON-LD generated from `$trainingClasses`. |
| [resources/views/landing-pages/training-show.blade.php](resources/views/landing-pages/training-show.blade.php) | Removed "Kelvin Enterprise" from default meta_keywords. Added full Course JSON-LD (with `provider`/`instructor` via `@id`, `hasCourseInstance`, `offers`) generated from the `Service` model. |
| [resources/views/landing-pages/blog-show.blade.php](resources/views/landing-pages/blog-show.blade.php) | Added `og_type = article` and `og_image` from `featured_image_url`. Added Article JSON-LD with `headline`, absolute `image`, `datePublished`, `dateModified`, `author`/`publisher` via `@id`, `articleSection`, `wordCount`. Demoted any `<h1>` inside the TinyMCE-rendered post body to `<h2>` so there is exactly one H1 per page. |

## Database changes

Two `AppSetting` values updated (via `php artisan tinker`):

| Key | Before | After |
|---|---|---|
| `app_name` | `Kevin Thompson` | `Kevin Thompson Ph.D.` |
| `seo_schema_org_name` | `Kevin Thompson Ph.D. Consulting` | `Kevin Thompson Ph.D.` |

The `AppSetting::saved()` hook in [app/Providers/AppServiceProvider.php:53-57](app/Providers/AppServiceProvider.php#L53-L57) automatically re-fired `SeoGenerator::generateAll()`, refreshing `public/robots.txt`, `public/sitemap.xml`, and `public/llms.txt`.

## Sitemap auto-regeneration — confirmed already wired

No changes needed. [app/Providers/AppServiceProvider.php:33-58](app/Providers/AppServiceProvider.php#L33-L58) already registers Eloquent saved/deleted hooks that call `SeoGenerator::generateSitemap()` / `generateLlms()` whenever:
- a `Post` is saved or deleted
- a `Service` is saved or deleted
- a `Paper` is saved or deleted
- any `AppSetting` whose key starts with `seo_` (or equals `app_name`) is saved

The `php artisan seo:generate` command remains available for manual regeneration. To regenerate just one file: `php artisan seo:generate --sitemap` (or `--robots` / `--llms`).

## Verification

| Check | Result |
|---|---|
| Brand sweep — `Kelvin Enterprise` remaining | 0 |
| Brand sweep — `Kevin Enterprise` remaining | 1 (intentional `alternateName` in layout) ✓ |
| All 18 JSON-LD blocks parse as valid JSON | ✓ |
| `<title>` on every key page non-empty + unique | ✓ |
| Canonical points to non-www HTTPS on every page | ✓ |
| `https://kevinthompsonphd.com/agile-training-classes` title was `Kevin Thompson`, now `Agile & Scrum Training for Hardware Teams \| Kevin Thompson, Ph.D.` | ✓ |
| `SearchAction.urlTemplate` now `/agile-insights-blog?search={…}` (was 404 `/blog`) | ✓ |
| `Person.url` references existing `/about-kevin-thompson` route | ✓ (was already correct in layout) |
| Article schema present on `/agile-insights-blog/{slug}` with absolute image URL | ✓ |
| Course schema present on `/agile-training-classes/{slug}` | ✓ |
| `<h1>` count on blog post page | 1 (was 2) ✓ |
| `Organization.alternateName = "Kevin Enterprise"` rendered on every page | ✓ |
| `public/sitemap.xml` regenerated successfully via `seo:generate` | ✓ |

## Caches cleared

- `php artisan view:clear`
- `php artisan config:clear`
- `php artisan seo:generate`

## Files NOT touched (per Phase 2 rules)

- Controllers — none required changes; existing meta-injection pattern already passes the right data to views.
- Models — no new fields added; existing `meta_title`/`meta_description`/`meta_keywords` cover Posts and Services.
- Routes — no changes.
- Migrations — none.
- nginx config — already correct from prior session.
- `robots.txt` and `sitemap.xml` source — generated by existing `SeoGenerator`, no manual edit.
