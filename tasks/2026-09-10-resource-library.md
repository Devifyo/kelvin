# Task: Resource Library (rename + Featured view + dedicated resource pages)

**Date:** 2026-09-10 · **Branch:** pre-main · **Status:** implemented — awaiting client copy + review

## 1. What the client asked for

1. Rename **"Papers & Presentations"** → **"Resource Library"** in the Resources nav dropdown.
2. Add a **"Featured"** tab at the start of the filter bar (Featured · All Documents · Case Studies ·
   Presentations · White Papers). **Featured is the default view.** The other four tabs keep working.
3. Every resource gets its **own dedicated web page** (editable title, several summary paragraphs for
   visitors + SEO, SEO meta fields, and a link to the PDF). The Resource Library becomes the index; the
   short card descriptions stay, but the card link goes to the resource page, not the PDF.
4. Architecture must accommodate a **book** resource (*Solutions for Agile Governance in the Enterprise*)
   whose page links to **Amazon** instead of a PDF. No book content needed now.

Client confirmations (all answered "yes" by design below):
- (1) *Every* existing resource gets a page — slugs are back-filled by migration, pages render even before
  long copy is written (fall back to the short description).
- (2) Card short descriptions untouched; card title + CTA → resource page.
- (3) Long description, title, and SEO fields are edited per resource in the admin modal.
- (4) Each page has a PDF link, or an external URL (Amazon / website) depending on resource type.
- (5) "Featured" is a star toggle in the admin list + a checkbox in the modal; Featured is the default tab.
- (6) `resource_type` = `document | book | link` with `external_url`.

## 2. Current state (what exists today)

| Piece | Where |
|---|---|
| Model / table | `App\Models\Paper` → `papers` (title, category_id, sub_category, description, file_path, is_active, sort_order) |
| Categories | `categories` rows of `type = 'paper'`: Case Studies, White Papers, Presentations |
| Public listing | `GET /agile-hardware-papers-and-presentations` → `PageController@papers` → `landing-pages/papers.blade.php` (server-side `?category=` filter, cards link straight to `Storage::url(file_path)`) |
| Hero copy | `PageHeader::PAGES['papers']` (kicker "Knowledge & Research", H1 "Papers & *Presentations*") — editable in Admin → Page Headers |
| Admin | `App\Livewire\Admin\ManagePapers` + `livewire/admin/manage-papers.blade.php` + `partials/papers/papers-modal.blade.php`; CSS `public/css/admin/manage-papers.css`; sidebar label "Papers & Research" |
| Nav | `layouts/partials/frontend/header.blade.php` (dropdown + mobile drawer), `footer.blade.php` ("Papers") |
| SEO | `CollectionPage` JSON-LD on listing; `llms.txt` lists papers but links them all to the listing URL; sitemap has only the listing URL; `Paper::saved` regenerates llms only |
| Tests | `PageHeadersTest` asserts one `<h1>` on the papers route; `StructuredDataTest` guards JSON-LD |

15 live papers in the DB, all with PDFs. An unrelated, unused `App\Models\Resource` model already exists
(different `resources` table) — **do not reuse it**; keep the `Paper` model/table and label it "Resource
Library" in the UI to avoid a risky rename.

## 3. Decisions

- **Listing URL stays** `/agile-hardware-papers-and-presentations` — it is indexed and keyword-bearing.
  Renaming it would need a 301 and would throw away equity for a cosmetic gain. Only labels change.
- **Detail URL:** `/agile-hardware-papers-and-presentations/{slug}` (route `papers.show`). Mirrors
  `/agile-insights-blog/{slug}` and `/agile-training-classes/{slug}`; gives a clean breadcrumb hierarchy.
- **Featured default:** no `?category=` → Featured view. If *zero* resources are featured, the page falls
  back to "All Documents" and hides the Featured tab, so the client can never publish an empty landing view.
- **Crawlability of the filtered default:** the listing's `CollectionPage` JSON-LD carries an `ItemList`
  of *all* active resources (URL + name) regardless of the active tab, every detail URL goes in
  `sitemap.xml` and `llms.txt`, and each resource page links to related resources — so hiding non-featured
  cards behind a tab does not orphan anything.
- **Resource types:** `document` (uploaded file → "View PDF" + download), `book` (external_url → "Buy on
  Amazon"), `link` (external_url → "Visit Website"). CTA label is derived from type; an optional
  `cta_label` override is available in admin.
- **Hero H1 rename:** the `page_headers` row for `papers` is updated by migration *only if it still holds
  the old default* ("Papers &" / "Presentations") so a client edit is never clobbered. New defaults:
  kicker "Knowledge & Research", H1 "Resource *Library*".
- **Schema on detail pages:** `document` → `DigitalDocument` (+ `encoding` MediaObject of the PDF),
  `book` → `Book` (+ `offers.url` → Amazon), `link` → `WebPage`. All with `BreadcrumbList`; author /
  publisher by `@id` only.
- **Model name stays `Paper`.** UI copy says "Resource".

## 4. Data model changes (`papers` table)

| Column | Type | Notes |
|---|---|---|
| `slug` | string, unique | back-filled from title (de-duplicated) |
| `resource_type` | string, default `document` | `document` / `book` / `link` |
| `external_url` | string, nullable | Amazon / website URL for `book` / `link` |
| `cta_label` | string, nullable | optional override of the derived button label |
| `is_featured` | boolean, default false | drives the Featured tab |
| `long_description` | longText, nullable | rich text for the resource page; falls back to `description` |
| `featured_image` | string, nullable | cover / OG image (book cover later) |
| `meta_title`, `meta_description`, `meta_keywords` | string, nullable | per-page SEO |

## 5. Implementation steps

- [x] 5.1 Migration: add columns, back-fill slugs, rename hero copy if still default.
- [x] 5.2 `Paper` model: fillable/casts, `slug` route key, scopes (`featured`), accessors
      (`file_url`, `file_extension`, `file_size_label`, `cta_label`, `cta_url`, `is_document`, `long_description_html`, `seoTitle()`).
- [x] 5.3 Routes: `papers.show`; `FrontendContentService::getPapersData()` (featured default + fallback) and
      `getPaperBySlug()` / `getRelatedPapers()`; `PageController@showPaper`.
- [x] 5.4 Public listing: Featured tab first, cards link to detail page, "Featured" badge, keep short desc.
      JSON-LD `CollectionPage` + `ItemList` of all resources.
- [x] 5.5 Public detail page `landing-pages/paper-show.blade.php` + `public/css/frontend/paper-show.css`:
      breadcrumb, H1, meta strip (category · sub-category · file type/size), long description, sticky
      resource card with CTA (View PDF / Download / Buy on Amazon / Visit Website), related resources,
      author box, JSON-LD.
- [x] 5.6 Nav/footer/drawer labels → "Resource Library"; active state covers `papers*`.
      `PageHeader::PAGES['papers']` label/defaults.
- [x] 5.7 Admin `ManagePapers`: featured star toggle + Featured filter pill + type badge + "View live"
      link per row; modal reorganised into tabs (Details · Resource Page · SEO) with slug auto-fill,
      resource type switch (file upload vs external URL), long-description TinyMCE, meta fields,
      Featured/Active checkboxes. Sidebar label "Resource Library". SweetAlert confirmations only.
- [x] 5.8 SEO plumbing: sitemap includes detail URLs (toggle `seo_sitemap_papers` in App Settings, on by
      default); `Paper::saved/deleted` → sitemap + llms; `llms.txt` links each resource to its own page and
      renames the section; regenerate files.
- [x] 5.9 Tests: `tests/Feature/ResourceLibraryTest.php` (featured default + fallback, tab filters, card
      links go to the page, detail page 200 + one H1 + valid JSON-LD + PDF link, book page shows Amazon
      link and no PDF, inactive → 404, admin save generates slug, toggleFeatured). Keep the full suite green.
- [x] 5.10 Docs: `seo.md` schema/model tables, `CLAUDE.md` pointer, this file → done.

## 6. Verification checklist (run before calling it done)

```bash
curl -s -k https://kevin.devifyo.cloud/agile-hardware-papers-and-presentations | grep -c '<h1'   # 1
curl -s -k https://kevin.devifyo.cloud/agile-hardware-papers-and-presentations/<slug> | grep -c '<h1'   # 1
grep -rn "https\?://\(www\.\)\?\(kevinthompsonphd\.com\|kevin\.devifyo\.cloud\|localhost\)" --include="*.blade.php" --include="*.php" resources/views app
docker exec kevin_app php artisan seo:generate
docker exec kevin_app php -r '$d=new DOMDocument(); echo $d->load("public/sitemap.xml")?"OK\n":"BAD\n";'
docker exec kevin_app php artisan test
```

## 6b. Verification results (2026-09-10)

- Full suite: **99 passed** (13 new in `ResourceLibraryTest`).
- Listing, `?category=all`, and a detail page each return 200 with exactly one `<h1>`; unknown slug → 404.
- Hardcoded-domain grep: clean.
- `public/{robots.txt,sitemap.xml,llms.txt}` were **not** committed from this machine — the local DB
  holds test data and stale slugs, so a local regenerate would regress the production sitemap. Run
  `php artisan seo:generate` on production after deploy (the next admin save of any resource also
  regenerates them automatically).

## 7. Out of scope / follow-ups for the client

- Writing the long descriptions and SEO copy for the 15 existing resources (client will supply).
- Creating the book resource itself (category "Books", Amazon URL, cover image) — the admin supports it now.
- Optional: a "Books" category and cover-image upload can be added from the admin without code changes.
