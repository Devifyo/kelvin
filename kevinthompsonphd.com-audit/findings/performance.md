# Performance / Core Web Vitals — kevinthompsonphd.com

**Method:** Lighthouse 13.4.1 (CLI, via `npx lighthouse`), mobile emulation, simulated throttling, performance category only. Run directly against production on 2026-07-21.

**All numbers below are LAB data (single Lighthouse run per page).** PageSpeed Insights API and CrUX field data were attempted first (`pagespeed_check.py --psi-only`) but returned `"PSI rate limit exceeded (240 QPM / 25,000 QPD)"` on repeated attempts — no PSI/CrUX data could be retrieved. No Google API credentials are configured for this environment, so **field data (CrUX, real-user 75th percentile) is not available and none is reported or implied below.** Lab data from a single run does not represent the 75th-percentile field distribution Google uses for CWV pass/fail — treat the "Good/Needs Improvement/Poor" labels below as lab-run classifications only.

All 5 target pages were measured. None were skipped.

## Per-page measured results (LAB, mobile, Lighthouse 13.4.1)

### 1. Home — `/`
- Performance score: 75
- **LCP: 3.93s** — Needs Improvement (lab)
- **CLS: 0.000** — Good (lab)
- **TBT (INP proxy): 0ms** — Good (lab)
- Total page weight: 176 KB (total-byte-weight audit); resource-summary transfer size 164,473 bytes
- Requests: 10
- Render-blocking resources (3): `fonts.googleapis.com/css2?family=Cormorant+Garamond...` (890ms wasted), `/css/frontend/main.css` (12.3KB), `/css/frontend/home.css` (14.3KB) — insight estimates **1,750ms** total savings if unblocked
- LCP breakdown: TTFB 989ms (simulated) + Element render delay 1,796ms. LCP element = nav logo text (`span.logo-name`), not an image.
- Image waste: bio-portrait `Dr. Kevin Thompson.webp` served at native ~667×837 but displayed 90×113 → 34.2 KB wasted (image-delivery-insight)

### 2. Services — `/agile-consulting-services`
- Performance score: 81
- **LCP: 3.74s** — Needs Improvement (lab)
- **CLS: 0.000** — Good (lab)
- **TBT (INP proxy): 0ms** — Good (lab)
- Total page weight: 103 KB; resource-summary transfer size 90,218 bytes
- Requests: 7
- Render-blocking resources (2): `/css/frontend/main.css`, `fonts.googleapis.com` CSS (825ms wasted) — insight estimate **1,730ms** savings
- LCP breakdown: TTFB 982ms + Element render delay 1,253ms. LCP element = body paragraph (`p.section-lead`), text, not an image.
- No image-delivery findings (page loads 0 images).

### 3. Blog — `/agile-insights-blog`
- Performance score: 79
- **LCP: 3.89s** — Needs Improvement (lab)
- **CLS: 0.043** — Good (lab)
- **TBT (INP proxy): 0ms** — Good (lab)
- **Total page weight: 776 KB** — 4–8x heavier than every other measured page
- Requests: 12
- Render-blocking resources (2): `/css/frontend/main.css` (409ms wasted), `fonts.googleapis.com` CSS (834ms wasted) — insight estimate **1,770ms** savings
- LCP breakdown: TTFB 1,046ms + Element render delay 1,238ms. LCP element = page subtitle paragraph, text, not an image.
- Image waste: blog-card featured image `q57jeUxCsgFnGStJFkSDyWS3I6VqynSgsK4eazx4.jpg` served at native 1,800×1,195 but displayed 330×220, JPEG not modern format → **371.1 KB wasted on this single image** (24.9 KB of that from format alone). Total image bytes on this page: 691 KB across 5 images.

### 4. About — `/about-kevin-thompson`
- Performance score: 85 (highest of the 5)
- **LCP: 3.40s** — Needs Improvement (lab)
- **CLS: 0.000** — Good (lab)
- **TBT (INP proxy): 56ms** — Good (lab, only non-zero-TBT page, still far under 200ms threshold)
- Total page weight: 137 KB; resource-summary transfer size 125,002 bytes
- Requests: 9
- Render-blocking resources (3): `/css/frontend/about.css`, `/css/frontend/main.css` (391ms wasted), `fonts.googleapis.com` CSS (827ms wasted) — insight estimate **1,160ms** savings (lowest of the 5, still substantial)
- LCP breakdown: TTFB 765ms + resourceLoadDelay 20ms + resourceLoadDuration 504ms + Element render delay 1,197ms. This is the **only page where the LCP element is an image** (profile portrait). It correctly uses `fetchpriority="high"`, `loading="eager"`, and explicit `width="320" height="400"` — good practice — but the source file (native ~667×1,000) is oversized for its 314×471 display box → 27.2 KB wasted.

### 5. Training — `/agile-training-classes/agile-hardware-development-with-scrum`
- Performance score: 84
- **LCP: 3.38s** — Needs Improvement (lab)
- **CLS: 0.022** — Good (lab)
- **TBT (INP proxy): 0ms** — Good (lab)
- Total page weight: 101 KB; resource-summary transfer size 87,475 bytes
- Requests: 7
- Render-blocking resources (2): `/css/frontend/main.css` (398ms wasted), `fonts.googleapis.com` CSS (823ms wasted) — insight estimate **1,710ms** savings
- LCP breakdown: TTFB 1,031ms + Element render delay 1,216ms. LCP element = `h1.page-title`, text, not an image.

## Root-cause attribution

### HIGH — Render-blocking CSS + font chain delays LCP on every page (5/5)
Evidence: Lighthouse's `render-blocking-insight` fired on all 5 pages with estimated savings of 1,160ms–1,770ms, always naming `/css/frontend/main.css`, the page-specific stylesheet (`home.css`/`about.css`), and the Google Fonts stylesheet as blocking. This directly explains why 4 of 5 pages have **text-only LCP elements** (nav logo, paragraph, h1) that still take 3.3–3.9s to paint despite page weights as low as 87–103 KB — the browser is stalled on the render-blocking request chain, not on network transfer of a heavy asset.

### HIGH — LCP fails the "Good" (≤2.5s) lab threshold on all 5 measured pages
Range: 3.38s–3.93s (lab, simulated mobile throttling). All 5 are in the "Needs Improvement" band (2.5–4.0s); none crossed into "Poor" (>4.0s), though Home at 3.93s is closest to that line. Cannot state a field p75 — no CrUX data available (see Method note above).

### MEDIUM — Google Fonts (Cormorant Garamond) loaded as a render-blocking third-party request, no preconnect evidence
Evidence: `fonts.googleapis.com/css2?family=Cormorant+Garamond...` appears in `render-blocking-insight` on all 5 pages with 823–890ms of wasted time attributed to it specifically. The CSS then references a second third-party origin, `fonts.gstatic.com`, for the actual woff2 files (2 fonts, 61,469 bytes total per page) — a second render-blocking round trip before font-styled text can paint. `font-display-insight` passed (score 1) on every page, confirming `display=swap` is in use, so this is a render-blocking/critical-path issue, not a FOIT/invisible-text issue.

### CRITICAL — Unoptimized/oversized images inflate the Blog listing page to 776 KB (page weight), no responsive sizing
Evidence: single blog-card thumbnail (`q57jeUxCsgFnGStJFkSDyWS3I6VqynSgsK4eazx4.jpg`) is 1,800×1,195px served at a 330×220 display box, wasting 371.1 KB (image-delivery-insight), pushing this one page to 4–8x the weight of the other 4 measured pages (776 KB vs. 87–176 KB). The same oversized-source pattern recurs on Home (34.2 KB wasted) and About (27.2 KB wasted) for the `Dr. Kevin Thompson.webp` portrait, which is served at native ~667px width regardless of its 90px or 314px display size on those two pages — no `srcset`/responsive variants observed.

### LOW/INFORMATIONAL — `Cache-Control: no-cache, private` on HTML document (confirmed)
Evidence (curl -I, live):
```
cache-control: no-cache, private
set-cookie: XSRF-TOKEN=...; expires=...Max-Age=7200...
set-cookie: kevin-thompson-phd-session=...; Max-Age=7200; httponly
set-cookie: vid=...; Max-Age=31536000; httponly
```
Every document response sets 3 cookies (Laravel CSRF + session + a long-lived `vid` tracking cookie), which is why the document cannot be cached by any CDN/edge layer — it must always be revalidated at origin. This is architecturally expected for a Laravel session-cookie-bearing app, but it means Lighthouse's simulated-throttle TTFB contribution to LCP (765–1,046ms) has no caching layer to fall back on. By contrast, static CSS is correctly cached (`cache-control: public, immutable, max-age=31536000`, confirmed via curl). Unauthenticated/anonymous visitors do not strictly need a session cookie set on first response; deferring session-cookie issuance until actually needed (e.g., form submission) would allow the HTML document to be edge-cached for anonymous traffic. This is an architecture-level tradeoff, not a quick fix — flagged as LOW/informational rather than actionable in isolation.

### INFORMATIONAL — cdn.plyr.io not observed in this 5-page sample
Third-party audit (`third-parties-insight`) passed (score 1, no findings) on all 5 measured pages, and no `plyr.io` requests appeared in any `network-requests` list captured. **This does not confirm Plyr has no performance impact** — it simply was not loaded on any of the 5 URLs audited. Plyr most likely loads only on individual blog posts with embedded video, which were not in the 5-page sample. Not measured; no claim made either way.

### GOOD — CLS passes comfortably on all 5 pages
Range: 0.000–0.043, all far under the 0.1 "Good" threshold. `cls-culprits-insight` passed (score 1) on every page. Images observed carry explicit `width`/`height` attributes (e.g., About page LCP image: `width="320" height="400"`). No action needed.

### GOOD — TBT (INP proxy) passes comfortably on all 5 pages
Range: 0ms (4 of 5 pages) to 56ms (About), all far under the 200ms "Good" threshold. 3 of 5 pages load zero scripts (resource-summary: `Script: 0 reqs`). This strongly suggests INP will also be good in the field, though this cannot be confirmed without CrUX field data.

## Summary table

| Page | LCP (lab) | CLS (lab) | TBT (lab, INP proxy) | Weight | Requests | Render-blocking |
|---|---|---|---|---|---|---|
| Home `/` | 3.93s NI | 0.000 Good | 0ms Good | 176 KB | 10 | 3 (~1,750ms) |
| Services | 3.74s NI | 0.000 Good | 0ms Good | 103 KB | 7 | 2 (~1,730ms) |
| Blog | 3.89s NI | 0.043 Good | 0ms Good | 776 KB | 12 | 2 (~1,770ms) |
| About | 3.40s NI | 0.000 Good | 56ms Good | 137 KB | 9 | 3 (~1,160ms) |
| Training | 3.38s NI | 0.022 Good | 0ms Good | 101 KB | 7 | 2 (~1,710ms) |

NI = Needs Improvement per lab thresholds. All figures are single-run Lighthouse lab measurements; no field/CrUX data available.

## Category score: 60 / 100

Justification: 2 of 3 Core Web Vitals (CLS, TBT/INP-proxy) pass comfortably with wide margin on every single measured page — that alone would support a high score. However, LCP fails the "Good" lab threshold on **all 5 of 5** measured pages (3.38–3.93s vs. 2.5s target), driven by a well-evidenced, consistent, fixable root cause (render-blocking CSS + third-party font chain, ~1.2–1.8s of estimated waste on every page). One page (Blog) has a critical, isolated image-weight problem (776 KB, 371 KB wasted on one image) that would independently warrant a lower score. Because the LCP failure is uniform, moderate (Needs Improvement, not Poor), and the causes are all standard, well-understood, high-confidence fixes (defer/inline critical CSS, preconnect + self-host or subset fonts, responsive image serving), the score sits in the middle band rather than lower — but cannot go higher while 100% of measured pages fail the primary metric Google uses for ranking/UX evaluation. No field data was available to corroborate or override this lab-only assessment.
