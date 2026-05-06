# Manual Follow-ups (outside the codebase)

After the Phase 2 (Tier A) edits, these are the actions only you can do.

## Tier 1 — Do this week

### 1. Validate the new structured data with Google
Run all of these through https://validator.schema.org and https://search.google.com/test/rich-results:
- `https://kevinthompsonphd.com/`
- `https://kevinthompsonphd.com/agile-training-classes`
- `https://kevinthompsonphd.com/agile-training-classes/agile-hardware-development-with-scrum`
- `https://kevinthompsonphd.com/agile-insights-blog`
- `https://kevinthompsonphd.com/agile-insights-blog/embedded-software`
- `https://kevinthompsonphd.com/agile-consulting-services`
- `https://kevinthompsonphd.com/about-kevin-thompson`

Look for: "Course" detected on training pages; "Article" detected on blog posts; no "missing field" warnings.

### 2. Re-submit the sitemap in Google Search Console
- Property: `https://kevinthompsonphd.com` (and the Domain property if you have it)
- Sitemaps → Add new sitemap → `sitemap.xml`
- Then run "URL Inspection" → "Request Indexing" on the seven main pages plus `/about-kevin-thompson` so Google notices the new titles/schema faster.

### 3. Add a default Open Graph image
The layout currently emits `og:image` only when `seo_og_image` AppSetting is set. Right now it's empty, so social shares of any page without a per-page image preview will lack a thumbnail.
- Upload a 1200×630 image (logo + tagline works) via the admin → SEO settings, OR set it via tinker:
  ```
  php artisan tinker --execute="\App\Models\AppSetting::set('seo_og_image', 'https://kevinthompsonphd.com/images/og-default.jpg');"
  ```
- Same image will be used for `Organization.logo` in schema (already wired).

### 4. Set the LinkedIn URL and Twitter handle
The layout exposes `seo_linkedin_url` and `seo_twitter_handle` AppSettings. When set, they populate the `Person.sameAs` and `Organization.sameAs` arrays in JSON-LD — important for Google's knowledge-graph reconciliation.
- Admin → SEO settings, or:
  ```
  php artisan tinker --execute="\App\Models\AppSetting::set('seo_linkedin_url', 'https://www.linkedin.com/in/REPLACE'); \App\Models\AppSetting::set('seo_twitter_handle', 'REPLACE');"
  ```

### 5. Update homepage and About `seo_title` / `seo_description` (DB-backed)
These come from the `WelcomePageContent` and `AboutPageContent` models, edited in admin. Current values are decent but can be sharper — see [suggested-meta-updates.md](suggested-meta-updates.md) for proposed copy.

## Tier 2 — Do this month

### 6. Author bio on every blog post
The blog-show template hardcodes a single bio block ("Principal Consultant specializing in Agile hardware development …"). Move this into a Blade partial or — better — add an `author_bio` column to the `User` model (or a sitewide AppSetting) so it can be updated centrally without code changes.

### 7. Wikidata entry
Create a Wikidata item for "Kevin Thompson (Agile consultant)". Required to earn a Google Knowledge Panel. See Section 8 of the prior strategy plan for fields and citations.

### 8. Search Console properties
Make sure all four URL prefix properties + the Domain property are added so you catch any remaining www variants in Google's indexing report:
- `https://kevinthompsonphd.com` (URL-prefix)
- `https://www.kevinthompsonphd.com` (URL-prefix — should show "Permanent redirect" in Coverage)
- `http://kevinthompsonphd.com` (URL-prefix)
- `http://www.kevinthompsonphd.com` (URL-prefix)
- `kevinthompsonphd.com` (Domain — covers all of the above)

## Tier 3 — Nice to have

### 9. HSTS preload
After auditing every subdomain serves HTTPS (mail, dev, staging, etc.), upgrade the HSTS header in [/etc/nginx/sites-available/default](/etc/nginx/sites-available/default) from
```
add_header Strict-Transport-Security "max-age=31536000" always;
```
to
```
add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always;
```
then submit the domain at https://hstspreload.org/.

### 10. nginx — set `ServerTokens` off / hide nginx version
Currently responses include `server: nginx/1.24.0 (Ubuntu)`. Optional security hardening — add `server_tokens off;` to the `http {}` block in [/etc/nginx/nginx.conf](/etc/nginx/nginx.conf).

### 11. Run Lighthouse / PageSpeed Insights
After the schema changes propagate, run https://pagespeed.web.dev/ on `/`, `/agile-training-classes`, and one blog post. Fix the top three opportunities each. Targets: LCP < 2.5s, INP < 200ms, CLS < 0.1.

### 12. Image alt-text sweep
Decorative `<img>` should have `alt=""`; meaningful images need descriptive alts that include a target keyword once. The existing template uses generic alts (e.g. `Dr. Kevin Thompson`); admin-uploaded blog featured images often have no alt at all (depends on the upload UI).
