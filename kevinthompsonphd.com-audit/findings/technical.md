# Technical SEO Audit — kevinthompsonphd.com

Audited: 2026-07-21 (production, live). Tooling: `claude-seo` (`sitemap_discovery.py`, `render_page.py`), direct `curl` header/body inspection of live responses. No Google API credentials configured — no CrUX field data available anywhere in this report; Core Web Vitals commentary below is lab/source-inspection only.

---

## 1. Crawlability

**PASS | robots.txt is valid and does not block anything important**
`https://kevinthompsonphd.com/robots.txt` → HTTP 200, `Content-Type: text/plain`:
```
User-agent: *
Disallow: /admin
Disallow: /login
Disallow: /livewire
Disallow: /_ignition
Disallow: /telescope
Disallow: /horizon
Disallow: /storage/app/
Sitemap: https://kevinthompsonphd.com/sitemap.xml
```
All disallowed paths are Laravel internals (admin panel, auth, Livewire endpoint, Ignition debug page, Telescope/Horizon dashboards, private storage). No public content, blog, service, or training-class path is blocked. Severity: **Info** (correct as-is).

**PASS | Sitemap declared in robots.txt validates**
`sitemap_discovery.py` confirms the declared sitemap (`https://kevinthompsonphd.com/sitemap.xml`) returns HTTP 200, `kind: urlset`, `valid: true`. This is a genuine passing result validated by the helper, not a stale robots.txt declaration. Common fallback paths (`sitemap_index.xml`, `sitemap-index.xml`, `wp-sitemap.xml`) all correctly 404 — no orphaned/legacy sitemap confusion. Severity: **Info**.

**PASS | Sitemap coverage matches the 26-URL site**
`sitemap.xml` contains 26 `<loc>` entries, one-to-one with every URL in the provided audit list (home, about, services, papers, blog index, podcasts, contact, FAQ, 11 blog posts, 7 training classes). `lastmod` values are present and plausible (2026-05-05 to 2026-07-17). Severity: **Info**.

**Info | robots.txt does not declare AI-crawler-specific tokens**
No explicit allow/disallow rules for GPTBot, ClaudeBot, PerplexityBot, etc. — the wildcard `User-agent: *` block applies to all crawlers, so AI crawlers are not blocked, but the site also has not made an explicit policy choice. Severity: **Low** (informational, no defect).

---

## 2. Indexability

**PASS | Self-referencing canonical tags present on all sampled page types**
Verified via full raw HTML (not truncated JSON summaries) on one page from each type:
- Home: `<link rel="canonical" href="https://kevinthompsonphd.com" />`
- Service (`/agile-consulting-services`): `<link rel="canonical" href="https://kevinthompsonphd.com/agile-consulting-services" />`
- Blog post (`/agile-insights-blog/embedded-software`): `<link rel="canonical" href="https://kevinthompsonphd.com/agile-insights-blog/embedded-software" />`
- Training class (`/agile-training-classes/advanced-product-owner`): `<link rel="canonical" href="https://kevinthompsonphd.com/agile-training-classes/advanced-product-owner" />`
- About: self-referencing to `/about-kevin-thompson`
- FAQ: self-referencing to `/faq`

All canonicals are absolute, HTTPS, non-www, and match the requested URL exactly. Severity: **Info** (pass).

**Medium | Trailing-slash duplicate is served as 200, not 301, and relies solely on canonical tag to disambiguate**
Both `https://kevinthompsonphd.com/about-kevin-thompson` and `https://kevinthompsonphd.com/about-kevin-thompson/` (and the same pattern confirmed on the homepage and a blog post) return **HTTP 200** with byte-identical content (`diff` of the two responses produced zero output). The trailing-slash variant's canonical tag correctly points to the non-slash URL (`<link rel="canonical" href="https://kevinthompsonphd.com/about-kevin-thompson" />`), so this is not an indexing risk for Google — but it is architecturally a soft-404-style duplicate: two distinct URLs serve identical content with no HTTP-level redirect, so crawlers (and crawlers that ignore/de-prioritize canonical hints, e.g. some AI/vertical crawlers) will fetch and store both. Recommendation: add a nginx/Laravel-level 301 from the trailing-slash form to the canonical form so duplication is resolved at the protocol level, not just via a hint tag. Severity: **Medium**.

**No meta robots noindex found on any sampled page** — default index,follow applies everywhere checked (home, service, blog post, training class, about, FAQ). Severity: **Info** (pass).

**Low | FAQ page missing a meta description**
`https://kevinthompsonphd.com/faq` has no `<meta name="description">` tag in its `<head>`, while every other sampled page type has one. Google will auto-generate a snippet from page content, which is a minor missed-opportunity rather than an indexability defect. Severity: **Low**.

---

## 3. Security

**PASS | HTTPS enforced with correct single-hop redirects**
- `http://kevinthompsonphd.com/` → `301 Moved Permanently` → `Location: https://kevinthompsonphd.com/` (verified via `curl -D -`).
- `https://www.kevinthompsonphd.com/` → `301` → `Location: https://kevinthompsonphd.com/` (single hop, confirmed live, matches stated known-good behavior).
No redirect chains (all tested redirects resolve in exactly one hop). Severity: **Info** (pass).

**PASS | Core security headers present**
Confirmed on robots.txt, homepage, and all sampled pages:
```
strict-transport-security: max-age=31536000
x-frame-options: SAMEORIGIN
x-content-type-options: nosniff
```
Severity: **Info** (pass, though see CSP gap below).

**Medium | No Content-Security-Policy header observed**
None of the fetched responses (home, service, blog post, training, about, FAQ, robots.txt) included a `Content-Security-Policy` header. This is a hardening gap rather than an SEO-ranking factor per se, but is worth flagging since the other three standard hardening headers (HSTS, X-Frame-Options, X-Content-Type-Options) are present and well-configured — CSP is the natural next step, especially given the JSON-LD PHP-leak defect below (#8), which shows unrendered server code can reach the response body. Severity: **Medium**.

---

## 4. URL Structure & HTTP Status (all 26 URLs)

**PASS | All 26 URLs return clean 200s, no redirect chains, no 404/5xx**
Every URL in `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/urls.txt` was checked with `curl -o /dev/null -w "%{http_code} %{redirect_url}"`. Result: **26/26 return `200` with an empty `redirect_url`** (i.e., no redirect occurred at all — these are the canonical destination URLs, not the source of a chain). No 404s, no 5xxs, no multi-hop redirects found anywhere in the provided URL set. Severity: **Info** (pass — clean status-code health).

**PASS | Clean, human-readable URL structure**
All paths use lowercase, hyphen-separated slugs with logical folder grouping (`/agile-insights-blog/<slug>`, `/agile-training-classes/<slug>`), no query-string parameters, no session IDs in URLs, no numeric IDs. Severity: **Info** (pass).

**Low | URLs are case-sensitive with a hard 404 on case variants**
`https://kevinthompsonphd.com/About-Kevin-Thompson` (capitalized) returns `HTTP 404`, confirmed live. This is standard Linux/nginx behavior and not a defect by itself, but any inbound links, backlinks, or social shares using incorrect casing will hard-fail instead of resolving. No case-insensitive rewrite or redirect-to-canonical-case exists. Severity: **Low**.

**Medium | Trailing-slash duplicate URLs return 200 instead of redirecting** — see indexability section above (same evidence, filed once to avoid duplication in this report).

---

## 5. Set-Cookie + `Cache-Control: no-cache, private` on HTML — CDN/crawler efficiency impact

**High | Every HTML response sets session cookies and forbids shared caching, eliminating CDN cacheability for a site with no per-user content**
Confirmed on every page fetched (home, service, blog post, training class, about, FAQ, robots.txt is the only exception — text file, no cookies):
```
cache-control: no-cache, private
set-cookie: XSRF-TOKEN=...; Max-Age=7200; path=/; secure; samesite=lax
set-cookie: kevin-thompson-phd-session=...; Max-Age=7200; path=/; secure; httponly; samesite=lax
set-cookie: vid=...; Max-Age=31536000; path=/; secure; httponly; samesite=lax
```
Concrete impact:
1. **`private` explicitly forbids any shared/CDN cache from storing the response**, per RFC 9111 — a CDN in front of this origin (Cloudflare, Fastly, CloudFront, etc.) cannot cache these HTML documents at all. Every visitor and every crawler hit is a full round-trip to the Laravel/nginx origin.
2. **`no-cache` (distinct from `no-store`) still permits browser-level reuse-after-revalidation, but combined with `private` it blocks the CDN edge layer entirely** — the practical effect for a marketing/consulting brochure site with zero personalization is the same as if every page were dynamic.
3. This is a solo-practice B2B content site (services, blog posts, training pages, FAQ) — none of the sampled content is per-user or session-dependent, yet every response carries a full session bootstrap (CSRF token + Laravel session + a long-lived `vid` visitor-tracking cookie good for one year). There is no technical reason visible in the HTML/response for anonymous, non-form pages to need a live session on every crawl.
4. For crawl efficiency specifically: Googlebot and Bingbot do not send cookies back, so the session-per-request behavior imposes real compute cost on the origin for every crawl pass (fresh session established, fresh CSRF token generated) with zero caching benefit — this scales poorly if crawl frequency increases and directly reduces effective crawl budget utilization since the origin cannot offload any of that work to an edge cache.

Recommendation: for anonymous GET requests to public, non-form marketing/content pages (home, service, blog posts, training classes, FAQ, about), skip Laravel session/cookie initialization (or exclude these routes from the `StartSession`/`VerifyCsrfToken` middleware group) and set a cacheable `Cache-Control: public, max-age=..., stale-while-revalidate=...` so a CDN can serve these pages from edge. Reserve session cookies for the contact/lead-gen form page(s) that actually need CSRF protection. Severity: **High** (this is a real, measurable crawl-efficiency and CDN-cost issue, not cosmetic).

---

## 6. Mobile-Friendliness

**PASS | Correct responsive viewport meta tag present on all sampled pages**
`<meta name="viewport" content="width=device-width, initial-scale=1.0"/>` confirmed identically on home, service, blog post, training class, about, and FAQ pages. No fixed-width viewport, no `user-scalable=no`, no `maximum-scale` lock found. Severity: **Info** (pass).

**PASS | Images use explicit width/height + lazy loading (mobile-relevant CLS mitigation)**
Sampled `<img>` tags on the homepage all specify explicit `width`/`height` attributes and `loading="lazy" decoding="async"` (e.g., client-logo images, book cover image), which reserves layout space on mobile viewports and reduces mobile data usage from off-screen images. Severity: **Info** (pass).

**Not tested | Touch-target sizing and tap-spacing** — this requires either a rendered screenshot/computed-style pass or a live Lighthouse mobile run, neither of which was executed in this session. Not verified; not claiming pass or fail.

---

## 7. Core Web Vitals (lab/source-inspection only — no CrUX field data available)

Explicitly noting: **no Google API credentials are configured in this environment**, so no CrUX/PageSpeed Insights field data (real-user LCP/INP/CLS) was retrieved or could be retrieved. Nothing below should be read as field data — it is inference from HTML source inspection only.

**Info | Render-blocking resources are minimal but present**
Homepage `<head>` loads 3 stylesheets synchronously before paint: 1 external Google Fonts stylesheet (`Cormorant+Garamond`) plus 2 local stylesheets (`/css/frontend/main.css`, `/css/frontend/home.css`). `rel="preconnect"` is correctly set up for `fonts.googleapis.com` and `fonts.gstatic.com`, which mitigates but does not eliminate the connection-setup cost of the external font request. No `font-display` value was visible in the stylesheet link itself (governed inside the loaded CSS, not inspected). This is a **potential LCP risk** (render-blocking CSS delays first paint) but severity cannot be quantified without a lab timing run. Severity: **Low** (source-level observation only).

**Info | No render-blocking synchronous `<script src>` tags found in homepage `<head>` or inspected markup** — no `<script>` tags with a `src` attribute were found via regex scan of the homepage HTML, consistent with the stated Blade/Livewire server-rendered architecture (Livewire's JS runtime is typically injected at the end of `<body>`, not blocking head-render). Not fully confirmed for body-end scripts in this pass. Severity: **Info**.

**Not tested | Actual LCP/INP/CLS values** — no Lighthouse/PageSpeed run was executed in this session (would require `pagespeed_check.py` with API credentials, which are not configured per the task brief). Not verified; do not infer pass/fail from this report for these three metrics.

---

## 8. Structured Data Delivery

**PASS | Multiple valid, well-typed JSON-LD blocks confirmed across page types**
- Home: `@graph` containing `Person`, `Organization`, `WebSite` (with `SearchAction`/`EntryPoint`), plus a separate `FAQPage` block — both parse cleanly as valid JSON.
- Service (`/agile-consulting-services`): `Service` schema + `FAQPage` block.
- Blog post (`/agile-insights-blog/embedded-software`): `Article` + `BreadcrumbList`.
- Training class (`/agile-training-classes/advanced-product-owner`): `Course` + `BreadcrumbList`.
- About: `AboutPage`.
- FAQ: `FAQPage`.
Severity: **Info** (pass, structure is appropriate to content type).

**Critical | Unrendered Blade/PHP template source leaks into the live JSON-LD `@context` key on at least two pages, corrupting the structured data and exposing server internals**
Reproduced independently via two separate fresh `curl` fetches of the live production responses (not a caching artifact of a single fetch):
- `https://kevinthompsonphd.com/agile-consulting-services`
- `https://kevinthompsonphd.com/faq`

On both, the `FAQPage` JSON-LD block's `@context` key is not the string `"https://schema.org"` — it is literally the raw, unexecuted Blade-compiled PHP source:
```
{"<?php $__contextArgs = [];\nif (context()->has($__contextArgs[0])) :\nif (isset($value)) { $__contextPrevious[] = $value; }\n$value = context()->get($__contextArgs[0]); ?>":"https://schema.org","@type":"FAQPage","mainEntity":[...]}
```
This was consistent across repeated fetches of the same two URLs (checked twice each, live). By contrast, the homepage's own `FAQPage` block (a separately-authored/rendered instance, not using the shared component) parses correctly with a clean `"@context": "https://schema.org"` key — indicating the defect is isolated to whatever shared Blade FAQ component is reused on the service and FAQ pages, likely a broken `@context`/`@once` conditional directive in that component that fails to compile/execute before its output is written into the `<script type="application/ld+json">` tag.

Impact from a technical-SEO/output-integrity angle (structured-data validity impact is the schema specialist's domain, flagged separately): this is a **server-side template execution failure leaking raw PHP source into a public HTTP response** — a code-execution/output-integrity defect, not merely a markup typo. It indicates the Blade compilation/caching pipeline is emitting uncompiled directive source in production on at least these two routes. Given the response is also uncached (`Cache-Control: no-cache, private`, see §5), every visitor and crawler on these two pages currently receives this leaked source. Severity: **Critical**.

---

## 9. JavaScript Rendering

**PASS | Site is genuinely server-rendered; no SPA/CSR dependency**
`render_page.py --mode auto` (which only invokes Playwright when an SPA shell is detected) returned `is_spa: False` and `mode_used: raw` for the homepage — i.e., the tool determined a full render was unnecessary because the raw HTML already contains complete content. Confirmed by direct inspection: the homepage HTML delivered by a plain `curl` (no JS execution) already contains the full title, meta description, Person/Organization/WebSite JSON-LD, and body content. This matches the stated architecture (Laravel 12 + Livewire + server-rendered Blade). No JS-rendering dependency risk for crawlers that don't execute JavaScript (Bingbot's classic crawler, many AI crawlers, social-share scrapers). Severity: **Info** (pass — this is the single strongest structural asset in this audit).

**Not tested | Livewire interactive-component behavior under JS-disabled conditions** — not exercised in this session; Livewire's own hydration/interactivity was not tested since it does not affect initial-HTML content delivery, which is what matters for crawlability.

---

## IndexNow Protocol

**Not verified / Info | No IndexNow key file found at the conventional location; no evidence of active IndexNow integration**
`https://kevinthompsonphd.com/indexnow.txt` returns `HTTP 404`. No `indexnow` string found anywhere in the homepage HTML (checked via case-insensitive grep). This means either (a) IndexNow submission is not implemented, or (b) it is implemented via a differently-named key file not checked here. I did not attempt an actual `indexnow_submit.py` submission (that would create a real, possibly unwanted, side-effecting API call against Bing/Yandex/Naver on a live production site — out of scope for a passive audit). **Stating explicitly: IndexNow key-file presence at the default path was checked and is absent; whether IndexNow is implemented via another mechanism was not exhaustively verified.** Severity: **Medium** (missed low-cost opportunity — IndexNow is free, low-effort, and would let this small site push instant index notifications to Bing/Yandex/Naver rather than waiting on polling-based discovery, which matters more given the crawl-cost issue in §5).

---

## Summary Table

| # | Severity | Finding | Evidence |
|---|----------|---------|----------|
| 1 | Info (Pass) | robots.txt correct, blocks only Laravel internals | Full robots.txt content shown above, `/admin /login /livewire /_ignition /telescope /horizon /storage/app/` |
| 2 | Info (Pass) | Sitemap declared, validates, matches 26-URL set | `sitemap_discovery.py` JSON: `valid: true`, `kind: urlset`; 26 `<loc>` entries counted |
| 3 | Info (Pass) | Self-referencing canonicals correct on all 6 sampled page types | Home/service/blog/training/about/FAQ canonical tags quoted above |
| 4 | Medium | Trailing-slash duplicate served as 200, not 301 (canonical-only fix) | `/about-kevin-thompson` and `/about-kevin-thompson/` both 200, byte-identical, `diff` empty |
| 5 | Info (Pass) | All 26 audited URLs return clean 200, zero redirects, zero errors | Full curl status sweep of `urls.txt`, all 200/no-redirect |
| 6 | Low | Case-sensitive URLs hard-404 on capitalized variant | `/About-Kevin-Thompson` → 404 |
| 7 | Info (Pass) | HTTPS + www→non-www redirects are single-hop 301s | curl -D- on `http://` and `https://www.` |
| 8 | Info (Pass) | HSTS/X-Frame-Options/X-Content-Type-Options present everywhere | Headers shown on every fetch |
| 9 | Medium | No Content-Security-Policy header | Absent from all fetched responses |
| 10 | High | Every HTML response is `Cache-Control: no-cache, private` + 3 Set-Cookie headers, blocking CDN caching | Headers quoted from home/service/blog/training/about/FAQ |
| 11 | Info (Pass) | Correct responsive viewport meta on all sampled pages | `width=device-width, initial-scale=1.0` confirmed 6/6 |
| 12 | Info (Pass) | Images have explicit width/height + lazy loading | `<img ... width height loading="lazy" decoding="async">` on homepage |
| 13 | Low | Render-blocking Google Fonts + 2 local stylesheets in `<head>` (potential LCP risk, not measured) | 3 `<link rel="stylesheet">` in homepage head, no lab timing run performed |
| 14 | Not tested | Actual LCP/INP/CLS field or lab values | No API credentials, no Lighthouse run executed |
| 15 | Info (Pass) | Valid JSON-LD present and well-typed on all sampled page types | Person/Organization/WebSite/FAQPage/Service/Article/Course/BreadcrumbList/AboutPage confirmed |
| 16 | Critical | Unrendered Blade/PHP template source leaks into live `@context` key on `/agile-consulting-services` and `/faq` | Raw `<?php $__contextArgs...?>` string reproduced on 2 independent fetches per URL |
| 17 | Info (Pass) | No JS-rendering dependency; site is genuinely server-rendered | `render_page.py`: `is_spa: False`, `mode_used: raw`; full content present in raw HTML |
| 18 | Medium | No IndexNow key file found at default location; integration unverified elsewhere | `/indexnow.txt` → 404; no "indexnow" string in homepage HTML |
| 19 | Low | FAQ page missing `<meta name="description">` | Confirmed absent in FAQ page `<head>`, present on all other sampled pages |

---

## Category Score: 68 / 100

**Justification:** Crawlability, sitemap health, URL status-code hygiene (26/26 clean 200s), canonical-tag discipline, mobile viewport, and JS-rendering architecture are all genuinely strong (this is a well-built server-rendered Laravel/Blade site with no structural crawl blockers). The score is held down primarily by one **Critical** defect (live PHP source leaking into a JSON-LD script tag on two production URLs, indicating a broken template-compilation path reaching the public response) and one **High** finding (universal session-cookie issuance + `no-cache, private` on every HTML response eliminates CDN cacheability and adds needless per-crawl origin cost for a site that has no per-user content). Three **Medium** findings (trailing-slash duplicates relying on canonical-only resolution, missing CSP, unverified/absent IndexNow) and several **Low**/**Info** items round out the picture but are not primary score drivers.
