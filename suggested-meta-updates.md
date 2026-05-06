# Suggested Meta-Tag Updates (manual review)

These are recommendations only — **not auto-applied**. The Phase 2 fixes have already replaced obvious wrong values (Kelvin/Kevin Enterprise typos, missing training-listing tags, etc.). The items below are *content-quality* improvements you can approve/decline one at a time.

For DB-backed pages (`/`, `/about-kevin-thompson`) edit via admin → page settings.
For hardcoded pages, edit the `@section()` line in the named Blade file.

---

## Homepage `/`

**Source**: `WelcomePageContent.seo_title` / `seo_description` (DB)

| Field | Current | Suggested |
|---|---|---|
| Title (53) | `Kevin Thompson Ph.D. Consulting \| Agile Hardware & Software` | `Agile Hardware Development Consulting — Kevin Thompson, Ph.D.` |
| Description (151) | `Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development.` | `Dr. Kevin Thompson helps R&D and hardware teams adopt Agile and Scrum at scale. Consulting, training, and assessments backed by 20+ years of practice.` |

**Why**: Suggested title leads with the highest-value keyword phrase ("Agile Hardware Development") and ends with brand. Suggested description adds expertise signal ("20+ years"), audience ("R&D and hardware teams"), and concrete deliverables.

---

## About `/about-kevin-thompson`

**Source**: `AboutPageContent.seo_title` / `seo_description` (DB)

| Field | Current | Suggested |
|---|---|---|
| Title (52) | `About Dr. Kevin Thompson \| Principal Consultant` | `About Dr. Kevin Thompson — Agile Hardware Consultant` |
| Description (151) | `Learn about Dr. Kevin Thompson's background, from Physics at Princeton to pioneering Agile hardware scaling at Cprime.` | `Dr. Kevin Thompson, Ph.D. — Agile hardware consultant, author, and trainer. PhD Physics (Princeton), former Cprime Principal Consultant. CSP, PMP, ACP.` |

**Why**: Suggested title swaps "Principal Consultant" (generic) for "Agile Hardware Consultant" (the actual searched keyword). Suggested description front-loads credentials (PhD, CSP, PMP, ACP) which are E-E-A-T anchors.

---

## Services `/agile-consulting-services`

**Source**: [resources/views/landing-pages/services.blade.php](resources/views/landing-pages/services.blade.php) lines 4-6

| Field | Current | Suggested |
|---|---|---|
| Title (45 → 57) | `Consulting & Training Services \| Kevin Thompson Ph.D.` | `Agile Hardware Consulting & Transformation \| Kevin Thompson` |

**Why**: Current title says "Services" without keywords. Suggested title leads with "Agile Hardware Consulting" — the commercial-intent keyword these visitors actually search.

The meta description was already updated in Phase 2.

---

## Papers `/agile-hardware-papers-and-presentations`

**Source**: [resources/views/landing-pages/papers.blade.php](resources/views/landing-pages/papers.blade.php) line 3

| Field | Current | Suggested |
|---|---|---|
| Title (44) | `Papers & Presentations \| Kevin Thompson Ph.D.` | `Agile Hardware Research Papers & Talks \| Kevin Thompson` |

**Why**: Current title is generic — "Papers & Presentations" could be anything. Suggested adds the niche keyword ("Agile Hardware Research") which is what searchers type.

---

## Podcasts/Webinars `/podcasts-webinars`

**Source**: [resources/views/landing-pages/podcasts-webinars.blade.php](resources/views/landing-pages/podcasts-webinars.blade.php) line 3

| Field | Current | Suggested |
|---|---|---|
| Title (42) | `Podcasts & Webinars \| Kevin Thompson Ph.D.` | `Agile Hardware Podcasts & Webinars \| Kevin Thompson` |

**Why**: Same — qualify "Podcasts" with the niche.

---

## Contact `/contact-us`

**Source**: [resources/views/landing-pages/contact.blade.php](resources/views/landing-pages/contact.blade.php) line 3

| Field | Current | Suggested |
|---|---|---|
| Title (33) | `Contact Us \| Kevin Thompson Ph.D.` | `Contact Dr. Kevin Thompson — Agile Hardware Consulting` |

**Why**: "Contact Us" by itself is brand-less. Suggested makes the title work as a SERP click magnet for branded searches.

---

## Blog index `/agile-insights-blog`

Already updated in Phase 2 to:
- Title: `Agile Hardware Insights — Blog | Kevin Thompson, Ph.D.`
- Description: `Practical articles on Agile hardware development, Scrum, Kanban, and Agile transformation by Dr. Kevin Thompson, Ph.D. — written for R&D and engineering leaders.`

No further change recommended.

---

## Training listing `/agile-training-classes`

Already updated in Phase 2 to:
- Title: `Agile & Scrum Training for Hardware Teams | Kevin Thompson, Ph.D.`
- Description: `Live, instructor-led Scrum and Agile classes for hardware, embedded, and R&D teams. Taught by Dr. Kevin Thompson — CSP, PMI-ACP, PMP-aligned curricula.`

No further change recommended.

---

## Training detail `/agile-training-classes/{slug}`

Pulled from `Service.meta_title` / `meta_description` per record (admin). Sample audit of `agile-hardware-development-with-scrum`:

| Field | Current | Suggested |
|---|---|---|
| Title | `Agile Hardware Development with Scrum` | `Agile Hardware Development with Scrum — Training Class` |

**Why**: Adds the search modifier "training class" so the page shows up for "agile hardware training" queries.

For each training-class record, prepend a short qualifier (e.g. "Training Class", "Live Course") via the admin's meta_title field if you want to harvest more long-tail traffic. **Optional.**

---

## Blog post detail `/agile-insights-blog/{slug}`

Pulled from `Post.meta_title` / `meta_description` per record (admin). The first published posts have very short titles (e.g. `Embedded Software`, `Agile Hardware`). For SEO, add a keyword qualifier:

| Slug | Current title | Suggested title |
|---|---|---|
| embedded-software | `Embedded Software` | `Embedded Software in Agile Hardware Teams` |
| how-hardware-and-software-engineers-differ | (current) | `How Hardware and Software Engineers Differ in Agile Teams` |
| scrum-teams-swarming-and-hardware | (current) | `Scrum Team Swarming for Hardware Development` |

These are admin-editable — no code change needed. **Optional.**

---

## How to apply

### DB-backed (homepage, about)
- Admin UI: Settings → Welcome page / About page → SEO fields → save → `AppSetting::saved` hook auto-regenerates sitemap/llms.

### Hardcoded (services, papers, podcasts, contact)
- Edit the named Blade file's `@section('title', '...')` line.
- After editing, run `php artisan view:clear`. No sitemap regeneration needed (titles aren't in the sitemap).

### Per-record (training classes, blog posts)
- Admin UI: each Service/Post has `meta_title`, `meta_description`, `meta_keywords` editable fields.
- The model's `saved` event hook auto-regenerates sitemap.
