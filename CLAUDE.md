# CLAUDE.md

Laravel 12 + Livewire 4 marketing site for Dr. Kevin Thompson, Ph.D. (Agile hardware
consulting). Public landing pages + a custom admin panel. **SEO is the product** — this
site exists to rank and to be cited by AI answer engines. Treat SEO regressions as bugs,
not cosmetic issues.

## Environment

PHP is **not** on the host PATH. Everything runs in the `kevin_app` container:

```bash
docker exec kevin_app php artisan <cmd>     # artisan
docker exec kevin_app php artisan test      # full suite (must stay green)
docker exec kevin_app php artisan seo:generate   # rewrite robots.txt / sitemap.xml / llms.txt
npm run build                                # vite assets
```

Local URL: `https://kevin.devifyo.cloud` (prod: `kevinthompsonphd.com`).

---

## SEO — non-negotiable rules

**[seo.md](seo.md) is the authoritative architecture guide.** Read it before touching the
layout, schema, sitemap, or adding a page. The rules below are the ones that get broken
most often — they apply to *every* change, including ones that look purely cosmetic.

### Never break these

1. **Exactly one `<h1>` per page.** Never remove, duplicate, or empty it. Admin-editable
   headings must validate as `required` so a client cannot publish a page with no H1.
2. **Server-side render all indexable content.** Headings, body copy, and links must be in
   the initial HTML. Never move indexable text behind JS, Livewire lazy-loading, or a
   client-side fetch — crawlers and LLM fetchers read the raw response.
3. **The layout owns the head.** Per-page views set only `@section('title')`,
   `@section('meta_description')`, `@section('og_type')`, `@section('og_image')`.
   Never redeclare `<title>`, `<meta>`, or `<link rel="canonical">` in a page view.
4. **No hardcoded domains.** Use `route()`, `url()`, `asset()`. A literal
   `kevinthompsonphd.com` or `devifyo.cloud` in a view is a bug.
5. **Never let an indexed URL 404.** Renaming a published route requires a 301 redirect.
6. **Don't hand-edit `public/{robots.txt,sitemap.xml,llms.txt}`.** They are generated —
   edit [SeoGenerator](app/Services/SeoGenerator.php) and re-run `seo:generate`.
7. **Schema references entities by `@id`.** `author`/`publisher`/`provider` must be
   `['@id' => url('/') . '/#person']` or `'#organization'` — never an inline Person or
   Organization block (it forks the entity graph).
8. **Keep the `withoutMiddleware([...])` group** on the robots/sitemap/llms routes so
   crawlers get clean, cookie-free responses.

### GEO / AI search

This site is optimised to be cited by AI answer engines, not just to rank.

- Keep answers **self-contained per passage** — an LLM quotes a paragraph without its
  surrounding context, so each one should stand alone.
- Preserve `llms.txt` generation and the sitewide JSON-LD `@graph`. They are how machines
  resolve who Kevin is.
- Schema `name`/`headline` values are deliberately longer and more keyword-bearing than
  the visible H1. That mismatch is intentional — don't "fix" it.

### SEO tooling available

The `claude-seo` skill pack (v2.2.4) is installed at user level, so its skills are
available in every session — `/seo audit`, `/seo page`, `/seo geo`, `/seo schema`,
`/seo technical`, `/seo drift`, and ~25 more. Runtime health: `~/.claude/skills/seo/bin/claude-seo doctor`.

Most useful here: **`/seo drift baseline` before a risky change and `/seo drift compare`
after** — it snapshots SEO-critical elements and tells you exactly what a refactor
changed. Use it whenever you touch shared markup like `layouts/app.blade.php` or
`components/page-header.blade.php`.

Note: the schema-validation hook that runs on every Edit/Write is **not** active — that
requires `/plugin install`, which only the user can run. Until then, validate schema
explicitly with `/seo schema <url>`.

### Before you call SEO-touching work done

```bash
# 1. Exactly one H1, page still 200s
curl -s -k https://kevin.devifyo.cloud/<path> | grep -c '<h1'

# 2. No hardcoded domains in rendered output (.backup files are dead, ignore them;
#    bare 127.0.0.1 in TrackVisitor/VisaController are IP comparisons, not URLs)
grep -rn "https\?://\(www\.\)\?\(kevinthompsonphd\.com\|kevin\.devifyo\.cloud\|localhost\)" \
  --include="*.blade.php" --include="*.php" resources/views app

# 3. Sitemap still valid
docker exec kevin_app php -r '$d=new DOMDocument(); echo $d->load("public/sitemap.xml")?"OK\n":"BAD\n";'

# 4. Tests
docker exec kevin_app php artisan test
```

If you changed rendered markup, **diff the before/after HTML** and confirm the meta,
heading structure, and JSON-LD are unchanged unless the change was the point.

---

## Page headers (admin-editable hero copy)

The kicker / H1 / subtitle of the five main listing pages are DB-driven via
[PageHeader](app/Models/PageHeader.php) and edited in **Admin → Page Headers**.

To make a new page's header editable: add an entry to `PageHeader::PAGES`, then drop
`<x-page-header page="your-key" />` in its Blade. The admin module, live preview, seeding
and reset all pick it up — no other changes needed.

Do **not** reintroduce per-page hardcoded `<section class="page-header">` markup, and do
not add a second editing surface for the same copy (About's header used to live in two
places — that was consolidated deliberately).

## Admin panel conventions

- **Never use native `confirm()`, `alert()`, or `wire:confirm`.** All confirmations use
  SweetAlert2 (`Swal.fire`, loaded globally in `layouts/admin.blade.php`) with
  `#ef4444` for destructive actions and `#b5722a` (copper) for non-destructive ones.
  Note that `onclick="return confirm(...)"` does *not* cancel a `wire:click` — that
  combination is always a bug.
- Livewire components live in `app/Livewire/Admin/`, views in
  `resources/views/livewire/admin/`. Match the existing `wps-`/`lps-`/`phm-` scoped CSS
  prefix pattern per module.
- Brand tokens: `--slate #1a2332`, `--copper #b5722a`, `--copper2 #d4924e`,
  `--copper3 #edb97a`, `--ivory #faf7f2`. Display serif is Cormorant Garamond.

## Testing

PHPUnit feature tests in `tests/Feature/`. Livewire components are tested with
`Livewire::test(...)`. When adding admin-editable content that renders publicly, add a
test asserting the public page still renders correctly — that is what catches SEO
regressions before deploy.

## Companion docs

| File | Purpose |
|---|---|
| [seo.md](seo.md) | Authoritative SEO architecture guide — read before SEO work |
| [audit-report.md](audit-report.md) | Original SEO audit |
| [seo-implementation-log.md](seo-implementation-log.md) | What changed in the SEO pass and why |
| [manual-followups.md](manual-followups.md) | Search Console, Wikidata, HSTS preload, etc. |
