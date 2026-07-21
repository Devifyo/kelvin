# GEO / AI Search Readiness Audit — kevinthompsonphd.com

Audited live, production. Fetched via direct `curl` and `render_page.py --mode never` (raw HTML, no JS execution) on 2026-07-21. Site confirmed fully server-rendered (`is_spa: false`, `mode_used: raw`) — content is identical for a browser and for a non-JS AI crawler.

---

## 1. AI Crawler Accessibility — robots.txt

Full, verbatim contents of `https://kevinthompsonphd.com/robots.txt`:

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

**Finding — INFO/LOW:** There is exactly one user-agent block, `User-agent: *`, and it disallows only framework/admin internals (Laravel admin panel, login, Livewire, Ignition debug pages, Telescope, Horizon, private storage). None of the disallowed paths overlap with any of the 26 audited content URLs.

**Verified:** No dedicated stanzas exist for GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, Google-Extended, CCBot, Bingbot, anthropic-ai, or cohere-ai. All of these therefore fall under the wildcard `*` rule and are **not blocked** from any content page — they can crawl the homepage, about, services, training classes, papers, blog, podcasts, FAQ, and contact pages.

- GPTBot: allowed (no specific rule; falls under `*`, content paths not disallowed)
- OAI-SearchBot: allowed (same)
- ClaudeBot: allowed (same)
- PerplexityBot: allowed (same)
- Google-Extended: allowed (same)
- Bingbot: allowed (same)
- CCBot: allowed (same) — this is worth flagging since CCBot (Common Crawl) is training-only and typically the one site owners choose to block if they want citation but not verbatim reuse in training sets; this site does not distinguish.
- anthropic-ai / cohere-ai (training crawlers, distinct from ClaudeBot): allowed (same)

**Severity: LOW.** Nothing is blocked, so crawler access is not a gap. But there is also no evidence of *deliberate* AI-crawler policy — the file reads as a stock Laravel robots.txt with no AI-specific consideration. If the business goal is explicit "be cited, but don't be used for training," that intent is not expressed anywhere (no CCBot/anthropic-ai/cohere-ai disallow, despite these being flagged as "optional block, training only" in the brief).

---

## 2. /llms.txt

`https://kevinthompsonphd.com/llms.txt` returns HTTP 200, `content-type: text/plain`, 13,044 bytes, `last-modified: Fri, 17 Jul 2026`.

**Finding — well-formed, GOOD:** Correct llms.txt structure — H1 title (`# Kevin Thompson`), blockquote summary, then `##` sections (`Pages`, `Training Classes`, `Papers & Presentations`, `Blog Posts`) each with markdown link + one-line description, e.g.:

```
## Pages
- [Home](https://kevinthompsonphd.com): Agile hardware development consulting and training by Dr. Kevin Thompson
- [About Kevin Thompson](https://kevinthompsonphd.com/about-kevin-thompson): Biography, credentials, and expertise of Dr. Kevin Thompson Ph.D.
...
## Papers & Presentations
- [Agile Processes for Hardware Development](...): This is the foundational publication on how to develop hardware products using an Agile process. It reflects 18 months of my original research...
## Blog Posts
- [Thermo Fisher Scientific Lesson 1](...): Lesson #1: In hardware development, the Product Owner is often a team member.
```

It correctly summarizes all 14 papers/presentations and all 9 blog posts with distinct, non-boilerplate descriptions (not truncated title repeats) — genuinely useful for an LLM deciding what to fetch.

**Finding — MEDIUM:** `/faq` is not listed anywhere in llms.txt, despite the FAQ page being the single richest, most citation-ready page on the site (see §4) — 40+ marked-up Q&A pairs in three categories. `/agile-consulting-services` FAQ block (5 Q&As) and the homepage FAQ (8 Q&As) are also not called out. An LLM agent relying on llms.txt to decide what to fetch would miss the highest-value definitional content.

**Finding — LOW/INFO:** No `llms-full.txt` (checked, returns HTTP 404) — the optional full-text companion file some crawlers prefer is absent. Not a defect (llms.txt spec doesn't require it) but a missed opportunity given the site already has clean per-page extracted text.

**RSL 1.0 licensing:** **Not present.** No `<link rel="license">`, no `/rsl.xml`, no RSL reference in robots.txt or homepage HTML. Checked homepage source directly — no `licen[sc]e`/`rsl` string found anywhere. **Severity: LOW** (RSL adoption is still nascent industry-wide; absence is normal for most sites today, but noted since the brief calls it out explicitly and it's a near-zero-effort addition given AI-citation is an explicit goal).

---

## 3. Passage-Level Citability (real passages, scored)

Site skews toward **short, direct, self-contained** answers rather than the 134–167 word "optimal" band — a defensible tradeoff for FAQ content, but it means many passages under-shoot the ideal length.

**Passage A — homepage FAQ, `Answer` schema, self-contained (58 words):**
> "Yes — but it must be adapted, not copied from software. Hardware has longer lead times, physical iteration costs, and integration risk, so sprints deliver progress (CAD, board routing, design reviews, parts on order) rather than shippable features. Dr. Thompson pioneered Agile for hardware and authored the foundational paper on it; this is the core of every engagement."

Scoring: **High citability.** Direct yes/no framing, self-contained (doesn't require the question text to make sense — restates the topic), and bakes in attribution ("Dr. Thompson pioneered Agile for hardware") so a model quoting it is likely to also surface the source person. Below the 134–167 word band, but that's a minor efficiency loss, not a correctness problem.

**Passage B — `/faq`, "What is Agile?" (79 words, two `<p>` blocks under an `<h3>`):**
> "'Agile' is an umbrella term for a collection of processes and practices that optimize for rapid delivery of results in situations where high uncertainty renders more traditional planning and project-management practices ineffective. Agile processes include Scrum and Kanban. Agile practices include breaking work into small and testable deliverables, fine-grained planning for the near term and coarse-grained planning for the long term, iterative development that incorporates frequent feedback from stakeholders, and the use of cross-functional teams to do the work."

Scoring: **High citability, no attribution.** Fully self-contained generic definition — an LLM could quote this standalone and it would read correctly, but nothing in the passage itself signals it came from Kevin Thompson (no first-person voice, no brand token). Attribution depends entirely on the surrounding page/byline, which most citation pipelines do capture (URL + FAQPage schema), but the passage text alone is generic and interchangeable with any Scrum-101 explainer — this is a differentiation, not correctness, problem.

**Passage C — `/faq`, "What is Scrum?" — over-long for single-passage extraction (~250+ words spanning one `<h3>`, five `<p>`, a `<ul>` with 5 items, then more prose).** Well-structured with bolded key terms ("Deliver value incrementally.", "Work in fixed-length Sprints.") that make it easy for a model to lift individual bullets, but the full answer is too long to be lifted as one atomic quotable unit — a model will fragment it, and which fragment gets attributed/cited becomes unpredictable. **Severity: LOW** — structure mitigates the length problem but doesn't eliminate it.

**Passage D — blog post `/agile-insights-blog/thermo-fisher-scientific-lesson-1` — the core "lesson," found as a bolded standalone paragraph (53 words for lede + 2 supporting sentences):**
> `<p class="MsoNormal"><strong>Lesson #1: In hardware development, the Product Owner is often a team member.</strong></p>`
> followed by: "The reason is that ownership of a hardware product typically requires deep technical knowledge of the product itself. The person making decisions about priorities and tradeoffs frequently possesses the same specialized expertise required to contribute directly to the development effort."

Scoring: **Good, with a structural caveat.** The lesson statement itself is bolded and set apart (`<strong>` in its own `<p>`), and it is also mirrored verbatim in `<meta name="description">`, `og:description`, `twitter:description`, and the JSON-LD `Article.description` — meaning four independent extraction paths (meta description scraping, Open Graph, Twitter Card, structured data) all converge on the identical 15-word citable claim. That is a strong, low-effort citability pattern.

The caveat: to reach that sentence, the passage is preceded by ~350 words of first-person narrative scene-setting ("'Do you have any experience in Agile hardware development?' I was asked at the go/no-go decision meeting...") before the lesson and its explanation appear. There is no H2/H3 subheading, summary box, or "Key takeaway" callout inside the article body separating narrative from insight — a model would need to read/parse the whole article to locate the citable claim rather than jump to it. **Severity: MEDIUM** — this narrative-first pattern repeats across all 9 blog posts (confirmed via llms.txt descriptions, which show the same "Lesson #N: ..." one-liner is used as *the* summary for lessons 1–7, i.e. the site itself treats that one sentence as the payload, but the HTML doesn't structurally isolate it beyond bold+own-paragraph).

**Passage E — `/agile-consulting-services`, "What kinds of organizations do you work with?" (34 words):**
> "I work with organizations that develop complex products, with a particular focus on hardware and integrated hardware-software engineering. My clients have included organizations in biotechnology, robotics, aerospace, telecommunications, and other engineering-intensive industries."

Scoring: **High citability**, first-person and specific (named industries), but short — a model citing "who does Kevin Thompson work with" gets a complete, accurate, standalone answer.

**Overall citability pattern:** FAQ-format content (homepage, /faq, /agile-consulting-services) is short, direct, and well below the 134-167 target; it trades some "optimal length" for near-perfect self-containedness. Long-form blog content has the opposite problem — the citable insight exists but is buried in narrative and not visually/structurally isolated. Neither failure mode is severe, but both are addressable with low effort (see recommendations).

---

## 4. Definitional / Question-Shaped Content

**Finding — MAJOR STRENGTH.** This is the site's strongest GEO asset.

- `/faq` alone contains **~40 question-shaped headings** across three `<h2>` sections ("General Agile Questions," "Agile Hardware and Software Development Questions," "Consulting and Training Questions"), each question in its own `<h3 class="faq-q-heading">`, e.g. verbatim: *"What is Agile?", "What is Scrum?", "Does Scrum really work outside software development?", "Is Scrum the same thing as Agile?", "How does Scrum differ from Kanban?", "Can Scrum really work for hardware development?", "Why do Agile methods developed for software often fail in hardware organizations?", "What changes when Scrum is applied to hardware development?"* — all directly answering the kind of query pattern ("what is X," "does X work for Y," "how does X differ from Y") that AI answer engines favor.
- All ~40 Q&As are also marked up as `FAQPage`/`Question`/`Answer` JSON-LD (confirmed in the page's structured-data blocks).
- The homepage carries its own 8-question `FAQPage` block, and `/agile-consulting-services` carries a 5-question `FAQPage` block — three separate FAQPage instances sitewide, all schema-valid.
- Content is confirmed present in raw (pre-JS) HTML — the accordion is a plain `<button>`/`aria-expanded` pattern, not a JS-rendered-on-click component, so a non-executing crawler still receives full answer text.

**Severity: this is a POSITIVE finding, not a gap** — flagging only that llms.txt fails to surface it (see §2, MEDIUM).

---

## 5. Entity / Brand Disambiguation — "Kevin Thompson" is a common name

**Finding — HIGH severity gap.** The sitewide JSON-LD `Person` entity (`@id: https://kevinthompsonphd.com/#person`) contains **no `sameAs` property at all**. Full verbatim entity:

```json
{
  "@type": "Person",
  "@id": "https://kevinthompsonphd.com/#person",
  "name": "Kevin Thompson",
  "givenName": "Kevin",
  "familyName": "Thompson",
  "honorificSuffix": "Ph.D.",
  "jobTitle": "Agile Consultant & Trainer, Ph.D.",
  "description": "Agile hardware development consultant, trainer, and author. Helps R&D and hardware teams adopt Scrum and scale Agile.",
  "url": "https://kevinthompsonphd.com/about-kevin-thompson",
  "image": {...},
  "worksFor": {"@id": "https://kevinthompsonphd.com/#organization"},
  "knowsAbout": ["Agile hardware development", "Scrum", "Kanban", "Agile transformation", "Agile portfolio management", "Embedded systems development"]
}
```

No `sameAs` array pointing to LinkedIn, ORCID, Google Scholar, Wikidata, or a Scrum Alliance member profile. This matters specifically because "Kevin Thompson" is a very common name (country musician, meteorologists, athletes, academics of the same name exist) — `sameAs` is the standard schema.org mechanism for telling a knowledge graph / entity-resolution system which real-world identity a Person node maps to, and it's absent entirely.

**Checked directly — confirmed no outbound authority links anywhere on the site**, run against homepage, about page, services, papers, podcasts, and contact page `href`s (excluding internal links and font CDNs):
```
https://birdrf.com, https://plantronicsstore.com, https://www.agilent.com, https://www.airbus.com,
https://www.amazon.com/.../SAGE-.../dp/0578420589, https://www.appliedmaterials.com, https://www.bio-rad.com,
https://www.cisco.com, https://www.ericsson.com, https://www.gopro.com, https://www.insitu.com,
https://www.l3harris.com, https://www.lutron.com, https://www.northropgrumman.com, https://www.picarro.com,
https://www.roche.com, https://www.rtx.com/prattwhitney, https://www.thermofisher.com
(from homepage)
https://resources.scrumalliance.org/Webinar/agile-hardware-development-with-scrum, https://youtu.be/s1sQX0qzQ_U
(from /podcasts-webinars)
```
These are all client-logo / media links, not identity-verification links. **Zero LinkedIn URL, zero ORCID, zero Google Scholar profile, zero Wikipedia/Wikidata, zero personal Twitter/X, zero Scrum Alliance *personal* trainer-directory profile** appear anywhere on the 26 audited pages or in the JSON-LD.

**Textual disambiguation, by contrast, is strong** — the `/about-kevin-thompson` page (confirmed via raw HTML) contains specific, verifiable biographical facts that a co-occurrence-trained model can use to disambiguate even without structured `sameAs`:
> "I received my B.S. in Physics from Santa Clara University and my Ph.D. in Physics from Princeton University. During and after my years at Princeton, I conducted research at both Lawrence Livermore National Laboratory and NASA Ames Research Center's Space Sciences Division..."
> "...earned certifications as a Project Management Professional (PMP) from the Project Management Institute, as well as Certified Scrum Master (CSM) and Certified Scrum Professional (CSP) certifications from the Scrum Alliance."
> "I later became Chief Scientist at Cprime, an Agile consulting and training company..."

Also, a real, externally-verifiable published book — *Solutions for Agile Governance in the Enterprise (Sage): Agile Project, Program, and Portfolio Management for Development of Hardware and Software Products*, linked to a live Amazon listing (ASIN 0578420589) — is a genuine, checkable authority artifact, but it is **not marked up as `schema.org/Book`** and is not referenced via `sameAs` or `author` linkage from the Person entity; it exists only as prose + an `<a href>` link.

**Severity: HIGH.** For a solo named expert whose entire business model depends on being correctly attributed by AI answer engines (not confused with any other "Kevin Thompson"), shipping zero `sameAs` links and zero `Book`/`ScholarlyArticle` markup for the one piece of externally-verifiable published work is the single biggest structured-data gap on the site. The prose-level biographical specificity is good and should not be discounted, but it is not a substitute for machine-readable entity linking.

---

## 6. Author/Article-level Authority Signals (blog posts)

**Finding — GOOD.** Both sampled blog posts (`thermo-fisher-scientific-lesson-1`, `how-hardware-and-software-engineers-differ`) carry:
- A byline with name and date: "Kevin Thompson · June 02, 2026 · 3 min read"
- `Article` JSON-LD block + `BreadcrumbList`
- A per-article author bio block, verbatim: "Dr. Kevin Thompson, Ph.D. Principal Consultant specializing in Agile hardware development. Dr. Thompson has successfully guided over 100 enterprise transformations, bridging the gap between hardware engineering and Agile software methodologies."
- A downloadable PDF original-source link ("Original Formatting Available — pdf Download") pointing to `https://kevinthompsonphd.com/storage/papers/LessonsLearned-AgileHardware-v6.pdf`, i.e. the case study exists as a citable primary document, not just a blog rehash.

**Discrepancy noted, LOW severity:** on-page byline date reads "June 02, 2026" while `htmldate`-derived `publication_date` in the render output reads `2026-07-17` for the same URL (and `2026-01-01` for static pages like home/about/services/faq, which is very likely a site-wide fallback/last-deploy date rather than true content dates). This is a minor freshness-signal inconsistency worth checking against the actual sitemap `<lastmod>` values.

---

## 7. Multi-Modal Content

**Finding — MEDIUM gap.** `/podcasts-webinars` embeds a real YouTube video (`https://youtu.be/s1sQX0qzQ_U`) and links to a Scrum Alliance-hosted webinar (`https://resources.scrumalliance.org/Webinar/agile-hardware-development-with-scrum`) — both good brand-mention correlates per the brief (YouTube ~0.737 correlation with AI citation). However:
- Checked directly for captions/transcripts: no `<track>` element, no visible transcript text on the page. Confirmed via source inspection — the word "caption" appears only as a CSS/markup token, not an actual caption track.
- This means the audio/video content itself is **not text-extractable** by any crawler (AI or otherwise) — only the thumbnail `alt` text (e.g. "Dave Borzillo interviews me on Agile Hardware...", "Agile Hardware Webinar for the Scrum Alliance...") is machine-readable.
- Images sitewide do have descriptive `alt` text (confirmed on author photo, book cover, podcast thumbnails) — that part is solid.

**Severity: MEDIUM.** Only one video property is exposed with zero transcript; for a site whose expert is presumably speaking on many podcasts/webinars (papers page references multiple conference talks — Gartner briefing, Scrum4HW keynote, Agile Alliance conference), the lack of any transcript text is a lost citability opportunity for the audio-based authority evidence that most differentiates a genuine subject-matter expert from a content-mill writer.

---

## 8. Technical Accessibility

**Finding — GOOD.** Confirmed via direct fetch and `render_page.py`:
- `mode_used: "raw"`, `is_spa: false` on every sampled page — content is present in the initial HTML response, no client-side rendering dependency for AI crawlers that don't execute JS.
- HTTP 200 across all sampled URLs, `Strict-Transport-Security`, `X-Frame-Options`, `X-Content-Type-Options` headers present.
- Valid `sitemap.xml` referenced from robots.txt.

**Finding — LOW/INFO, worth monitoring:** Response headers show `Cache-Control: no-cache, private` and a fresh `Set-Cookie` (XSRF-TOKEN + session cookie) on every request, including the homepage — typical of a stateful Laravel app serving every visitor (including bots) through the full framework stack rather than a cached/static edge response. Not a blocker (crawlers still get 200 + full content), but it means every AI-crawler hit incurs a full app-server render with session/cookie overhead, which is worth confirming doesn't trigger rate-limiting or bot-mitigation false positives at scale.

---

## 9. Competitive Context (qualified — not independently verifiable)

The brief notes that only ~11% of domains are cited by both ChatGPT and Google AI Overviews, and asks for comparison to competitors in Agile/Scrum training citation patterns. **I did not query any live AI answer engine or third-party citation-tracking tool (no DataForSEO MCP tools were available in this session), so I cannot report actual competitor citation rates or this site's live LLM visibility — that would be invented behavior.** What can be said structurally: large Agile/Scrum training publishers (Scrum.org, Scrum Alliance, Atlassian) typically win generic "what is Scrum" citations through domain authority and volume of indexed Q&A content, not niche expertise — kevinthompsonphd.com's differentiated angle (named PhD expert, hardware-specific Agile, case studies with named clients) is a defensible niche for the *specific* query space ("Agile hardware," "Scrum for hardware development," "hardware Product Owner") where generic Scrum publishers have thin or no content. The `/faq` page's ~40 Q&As are the asset most likely to compete for that niche if crawled and indexed by AI engines, provided the entity-disambiguation gap in §5 is closed so citations attach the answer to the right "Kevin Thompson."

---

## Compact Summary

| Severity | Finding | Evidence |
|---|---|---|
| LOW | robots.txt has no AI-specific directives; wildcard `*` allows all AI crawlers by default (no explicit blocks found) | `User-agent: *` with only `/admin /login /livewire /_ignition /telescope /horizon /storage/app/` disallowed |
| LOW | No CCBot/anthropic-ai/cohere-ai training-only block despite these being flagged as optional-block candidates | Same robots.txt — no such stanzas exist at all |
| POSITIVE | /llms.txt present, well-formed, curated, accurately describes papers/blog/training pages | 13KB file with per-page markdown descriptions, verified via `curl` |
| MEDIUM | /llms.txt omits `/faq` and the three on-page FAQPage blocks, the site's richest citable content | Confirmed `/faq` string absent from llms.txt body |
| LOW | No `llms-full.txt`; no RSL 1.0 licensing anywhere (robots.txt, HTML head, no `/rsl.xml`) | 404 on llms-full.txt; no `licen[sc]e`/`rsl` string found in homepage source |
| POSITIVE | ~40 question-shaped H2/H3 headings + 3 separate schema-valid FAQPage blocks (home, /faq, /agile-consulting-services), fully present in raw SSR HTML | Verbatim headings quoted in §4 |
| MEDIUM | Blog posts bury the citable "lesson" ~350 words into first-person narrative with no subheading/callout isolating it (mitigated by bold text + matching meta description) | `thermo-fisher-scientific-lesson-1` passage quoted in §3, Passage D |
| LOW | FAQ answers mostly run well under the 134–167-word optimal citation length (58–79 words typical) | Passages A, B, E quoted in §3 |
| **HIGH** | Person JSON-LD has **zero `sameAs`** — no LinkedIn, ORCID, Google Scholar, Wikidata, or Scrum Alliance profile link anywhere on 26 pages, for a common name that needs disambiguation | Full Person entity + full outbound-link scan quoted in §5 |
| MEDIUM | Real published book (Amazon, ASIN 0578420589) not marked up as `schema.org/Book`, not linked via `sameAs`/`author` from the Person entity | Book link + surrounding prose quoted in §5 |
| POSITIVE | Blog posts carry byline, date, Article/Breadcrumb schema, and a genuine per-post author-bio block | Bio text quoted in §6 |
| LOW | Byline date vs. `htmldate`-derived publication_date mismatch on sampled blog post | "June 02, 2026" vs. `2026-07-17` |
| MEDIUM | Video/podcast content has no transcript or caption track; only thumbnail alt-text is machine-readable | Verified no `<track>`, no transcript text on `/podcasts-webinars` |
| POSITIVE | Fully SSR, no JS dependency, valid sitemap, no crawler-blocking headers | `is_spa: false`, `mode_used: raw` confirmed on all sampled URLs |
| N/A | Competitor AI-citation comparison not independently verifiable this session (no live AI-engine query tool available) | Flagged rather than invented |

## GEO / AI Search Readiness Score: 62 / 100

**Dimension breakdown (per the weighted rubric):**

| Dimension | Weight | Score | Justification |
|---|---|---|---|
| Citability | 25% | 65/100 | Strong self-containedness on FAQ content but under the optimal length band; blog "lessons" are extractable but narrative-buried |
| Structural Readability | 20% | 72/100 | Excellent question-shaped headings and FAQ schema; blog posts lack internal subheadings/callouts |
| Multi-Modal Content | 15% | 45/100 | Alt text is solid; one YouTube embed with zero transcript; thin multi-modal footprint overall for a "papers and presentations" practice |
| Authority & Brand Signals | 20% | 45/100 | Strong prose-level credentials (Princeton PhD, Cprime, PMP/CSM/CSP, named book) but **no `sameAs`, no Book schema, no external profile links** — the critical disambiguation gap for a common name |
| Technical Accessibility | 20% | 88/100 | Fully SSR, robots.txt open to all AI crawlers, valid sitemap, no blocking headers |

Weighted: (65×.25)+(72×.20)+(45×.15)+(45×.20)+(88×.20) = 16.25+14.4+6.75+9+17.6 = **~64**, rounded with qualitative weight given to the severity of the entity-disambiguation gap → **62/100**.

**Read of the score:** the site has done real, non-trivial GEO work already (llms.txt, sitewide JSON-LD graph, FAQ-schema-heavy content, SSR) that most consulting-practice sites never attempt — this is well above a typical unoptimized small-business site. The score is held down almost entirely by one structural gap (`sameAs`/entity-linking absent for a common name) and one content gap (video with no transcript, llms.txt missing the FAQ page) rather than by broad neglect. Both are fixable without a redesign.

## Top-Priority Fixes (highest impact first)

1. **Add `sameAs` to the Person JSON-LD** — link LinkedIn profile, Scrum Alliance trainer/member profile, Google Scholar (if applicable), and the Amazon author page. *Effort: low (structured-data edit only).* Addresses the HIGH-severity finding directly.
2. **Mark up the SAGE book as `schema.org/Book`** with `author: {"@id": ".../#person"}`, and add `sameAs` from Person to the Amazon listing. *Effort: low.*
3. **Add `/faq` (and the two other FAQPage blocks) to `/llms.txt`.** *Effort: trivial* — one more line in an already-generated file.
4. **Add an explicit "Key takeaway" / summary callout at the top of each blog post**, mirroring the meta-description sentence, so the citable claim isn't buried ~350 words into narrative. *Effort: low-medium (template change to the blog layout).*
5. **Produce transcripts for the YouTube interview and the Scrum Alliance webinar**, even a plain-text summary page, so that spoken-word authority content becomes crawlable text. *Effort: medium.*

---

**Files referenced in this audit (absolute paths):**
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/urls.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/homepage-render.json`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/home_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/about_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/services_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/faq_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/blog1_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/blog2_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/podcasts_full.txt`
- `/root/projects/laravel/disputer/kevin/kevinthompsonphd.com-audit/findings/geo.md` (this file)
