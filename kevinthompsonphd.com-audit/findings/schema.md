# Schema.org / Structured Data Audit — kevinthompsonphd.com

Audited: 2026-07-21 (production, live). Fetch method: `render_page.py --mode auto` (raw HTML; site confirmed **not** SPA, `is_spa: false`, `mode_used: raw` on every URL checked — schema is genuinely server-rendered in Blade, no client-side injection).

Pages sampled (10 of 26 URLs, covering every page type):

| Type | URL |
|---|---|
| Home | https://kevinthompsonphd.com/ |
| About | https://kevinthompsonphd.com/about-kevin-thompson |
| Services | https://kevinthompsonphd.com/agile-consulting-services |
| FAQ | https://kevinthompsonphd.com/faq |
| Contact | https://kevinthompsonphd.com/contact-us |
| Training class | https://kevinthompsonphd.com/agile-training-classes/agile-hardware-development-with-scrum (+ 6 sibling class pages spot-checked) |
| Blog post | https://kevinthompsonphd.com/agile-insights-blog/embedded-software |
| Blog index | https://kevinthompsonphd.com/agile-insights-blog |
| Papers | https://kevinthompsonphd.com/agile-hardware-papers-and-presentations |
| Podcasts | https://kevinthompsonphd.com/podcasts-webinars |

---

## 1. CRITICAL — Broken `@context` on FAQPage blocks (compiled Blade leaked into JSON-LD)

**Affected URLs:**
- `https://kevinthompsonphd.com/agile-consulting-services` (script tag ~line 860)
- `https://kevinthompsonphd.com/faq` (script tag ~line 1905)

**Evidence** (verbatim from production HTML, `view-source`):

```
<script type="application/ld+json">{"<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>":"https://schema.org","@type":"FAQPage","mainEntity":[...]}</script>
```

The property that should be the literal string key `"@context"` has been replaced by raw, un-executed compiled PHP source. This is a Laravel Blade compilation collision: Laravel ships a Blade **`@context` / `@endcontext`** directive pair (context-aware string/translation helper). Because the literal text `"@context"` appears unescaped inside a Blade template (rather than as `"@@context"` or `{!! '"@context"' !!}`), the Blade compiler parsed it as the `@context(...)` directive and inlined the compiled PHP output instead of the literal key.

**Impact:**
- The block is syntactically valid JSON but has no `@context` property at all (the schema.org context is set on the wrong/garbage key), so it is **not recognized as JSON-LD schema.org markup** by Google's Structured Data parser. Google will report "Invalid property" / effectively ignore the block.
- Both instances carry a genuine content FAQ set (6 questions on Services, 46 questions on `/faq`) that is currently delivering **zero** structured-data value.
- Note: per current Google policy, FAQPage rich results were retired for all sites (May 7, 2026), so even a fixed block earns **no SERP rich-result** benefit — see §6. This bug is flagged Critical purely as a **markup-validity break**, not for lost rich-result eligibility. Any AI/GEO crawler value from a well-formed FAQPage block is also lost as long as the key is broken.
- Root cause is template-level (shared FAQ partial/blade component), not content-level — confirmed clean on the **home page**, which renders its own FAQPage block correctly (`"@context": "https://schema.org"` intact, see `https://kevinthompsonphd.com/`). So home page uses a different code path than `/faq` and `/agile-consulting-services`, which share the broken one.

**Fix (Blade-level, not a JSON-LD content problem):** escape the literal directive with `@@context` (or emit the JSON via `json_encode()`/`Str::` helper rather than a raw Blade-templated heredoc/string). Example corrected Blade snippet:

```blade
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqItems->map(fn ($item) => [
        '@type' => 'Question',
        'name' => $item->question,
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $item->answer,
        ],
    ]),
]) !!}
</script>
```

Using `json_encode()` on a PHP array (rather than typing `"@context"` as a literal string inside a Blade view) sidesteps the directive collision entirely and is the correct long-term fix — apply it to whichever shared partial renders FAQ schema on `/faq` and `/agile-consulting-services`.

---

## 2. Sitewide `@graph` / `@id` architecture — VALIDATED, working as designed

Every one of the 10 sampled pages emits an identical first `<script type="application/ld+json">` block containing:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    { "@type": "Person", "@id": "https://kevinthompsonphd.com/#person", ... },
    { "@type": "Organization", "@id": "https://kevinthompsonphd.com/#organization", ... },
    { "@type": "WebSite", "@id": "https://kevinthompsonphd.com/#website", ... }
  ]
}
```

Page-specific blocks correctly reference these by `@id` and were checked for resolution:

| URL | Page type block | References found | Resolves to graph entity? |
|---|---|---|---|
| `/about-kevin-thompson` | AboutPage | `mainEntity` → `#person`, `publisher` → `#organization` | Yes — PASS |
| `/agile-consulting-services` | Service | `provider` → `#organization` | Yes — PASS |
| `/contact-us` | ContactPage | `about` → `#person`, `publisher` → `#organization` | Yes — PASS |
| `.../agile-hardware-development-with-scrum` | Course | `provider` → `#organization`, `instructor` → `#person` (x2, incl. inside `hasCourseInstance`) | Yes — PASS |
| `/agile-insights-blog/embedded-software` | Article | `author` → `#person`, `publisher` → `#organization` | Yes — PASS |
| `/agile-hardware-papers-and-presentations` | CollectionPage | `author` → `#person`, `publisher` → `#organization` | Yes — PASS |
| `/podcasts-webinars` | CollectionPage | `author` → `#person`, `publisher` → `#organization` | Yes — PASS |
| `/agile-insights-blog` | Blog | `author` → `#person`, `publisher` → `#organization` | Yes — PASS |

**No page inlines a duplicate/forked Person or Organization object.** Every sampled page's Person and Organization nodes are byte-identical to the canonical `#person` / `#organization` declarations — no entity forking detected anywhere in the 10-page sample. This is a genuine structural strength; the `@id`-reference pattern is implemented consistently. (Multiple separate `<script>` tags per page merging into one graph via shared `@id`s is valid per Google's structured-data parser, which processes all JSON-LD on a page as one dataset.)

**Severity: PASS / no finding.** Continue this pattern for any new page templates.

---

## 3. HIGH — Organization node missing `logo` (blocks Logo rich result + weakens Knowledge Panel)

**URL:** every page (canonical entity declared once, referenced everywhere; sampled instance: `https://kevinthompsonphd.com/`)
**Node:** `@graph[1]` (`@id: https://kevinthompsonphd.com/#organization`)

Current Organization node:
```json
{
  "@type": "Organization",
  "@id": "https://kevinthompsonphd.com/#organization",
  "name": "Kevin Thompson Ph.D.",
  "url": "https://kevinthompsonphd.com",
  "founder": { "@id": "https://kevinthompsonphd.com/#person" },
  "knowsAbout": [ ... ],
  "areaServed": "Worldwide"
}
```

Missing:
- `logo` — **required** for Google's Logo rich result and used by Knowledge Panel; absent entirely.
- `sameAs` — no social/profile URLs (LinkedIn, X, etc.) linking the Organization to authoritative external profiles; recommended for entity disambiguation.
- `contactPoint` — no telephone/email contact point (recommended for LocalBusiness/Organization eligibility if applicable).

**Ready-to-paste fix** (add to the single canonical Organization node in the shared master-layout `@graph`; do not duplicate on individual pages):

```json
{
  "@type": "Organization",
  "@id": "https://kevinthompsonphd.com/#organization",
  "name": "Kevin Thompson Ph.D.",
  "url": "https://kevinthompsonphd.com",
  "logo": {
    "@type": "ImageObject",
    "url": "https://kevinthompsonphd.com/img/frontend/REPLACE-WITH-ACTUAL-LOGO-FILENAME.png",
    "width": 600,
    "height": 60
  },
  "founder": { "@id": "https://kevinthompsonphd.com/#person" },
  "knowsAbout": [
    "Agile hardware development",
    "Scrum",
    "Kanban",
    "Agile transformation",
    "Embedded systems development"
  ],
  "areaServed": "Worldwide",
  "sameAs": [
    "https://www.linkedin.com/in/REPLACE-WITH-ACTUAL-HANDLE",
    "https://REPLACE-OTHER-VERIFIED-PROFILE"
  ]
}
```
Do not paste this with the placeholder URLs live — replace with the real logo path and real profile URLs before deploying (rule: no placeholder text in production JSON-LD).

---

## 4. MEDIUM — Person node missing `sameAs` (author E-E-A-T / disambiguation signal)

**URL:** every page (canonical entity; sampled: `https://kevinthompsonphd.com/`)
**Node:** `@graph[0]` (`@id: https://kevinthompsonphd.com/#person`)

Person node has good depth (`jobTitle`, `description`, `image` w/ dimensions+caption, `worksFor`, `knowsAbout`) but has **no `sameAs`** — no link to LinkedIn, Google Scholar, ORCID, published-paper profiles, etc. Given the business model leans on Dr. Thompson's personal authority (used as `author` on every Article and `instructor` on every Course), a `sameAs` array materially strengthens author E-E-A-T signals and entity disambiguation for a Knowledge Panel.

**Ready-to-paste fix** (add to canonical Person node):
```json
"sameAs": [
  "https://www.linkedin.com/in/REPLACE-WITH-ACTUAL-HANDLE",
  "https://scholar.google.com/citations?user=REPLACE-IF-APPLICABLE"
]
```

---

## 5. HIGH — Course `Offer.category` uses an invalid enum value (systemic, all 7 training-class pages)

**Affected URLs (confirmed on all 7):**
`/agile-training-classes/agile-hardware-development-with-scrum`, `/agile-software-development-with-scrum-training`, `/agile-overview-for-executives-and-managers`, `/agile-work-management-with-kanban`, `/agile-program-management`, `/agile-portfolio-management`, `/advanced-product-owner`

**Offending property:** `offers.category`

Current value on every training page:
```json
"offers": {
  "@type": "Offer",
  "category": "Professional Training",
  "url": "https://kevinthompsonphd.com/contact-us",
  "availability": "https://schema.org/InStock"
}
```

Google's Course structured-data guidelines require `Offer.category` to be one of the enumerated values `Paid`, `Free`, or `Subscription`. `"Professional Training"` is not a valid value for this property in the Course-offer context — this fails Google's Course rich-result validation and blocks Course pricing/eligibility signal.

Also missing (recommended, not strictly required, but flagged because `offers` is already present and incomplete): `price` and `priceCurrency`. Without them, Google cannot surface any price signal even though an `Offer` object exists.

**Ready-to-paste fix** (apply to all 7 course templates — likely one shared Blade partial):
```json
"offers": {
  "@type": "Offer",
  "category": "Paid",
  "price": "REPLACE-WITH-ACTUAL-PRICE-OR-OMIT-IF-QUOTE-ONLY",
  "priceCurrency": "USD",
  "url": "https://kevinthompsonphd.com/contact-us",
  "availability": "https://schema.org/InStock"
}
```
If pricing is genuinely quote-only/not published, omit `price`/`priceCurrency` entirely rather than fabricating a value (never insert placeholder pricing) — but `category` must still be corrected to `Paid` (or `Free`/`Subscription` as applicable), not left as free text.

---

## 6. MEDIUM — Course `image: null` and single-mode `hasCourseInstance` mismatch (systemic, all 7 training-class pages)

**Affected URLs:** same 7 training-class pages as §5.

- **`"image": null`** — the Course node explicitly emits an `image` key with a literal `null` value rather than omitting the property or supplying a real course image. This is a placeholder-equivalent value and should either be removed (if no image exists) or populated with a real `ImageObject`. Course images are a recommended property for the Course rich result carousel treatment.
- **`courseMode` vs `hasCourseInstance` mismatch:** top-level `courseMode` on the Course entity lists `["onsite", "online"]`, but `hasCourseInstance` contains only **one** instance, `courseMode: "onsite"`. There is no corresponding `online` `CourseInstance`. Google's Course guidelines expect `hasCourseInstance` to enumerate each actual delivery mode offered; as written, the markup asserts an online delivery option exists at the Course level but provides no instance data for it, which is an internal inconsistency a validator will flag.

**Ready-to-paste fix** (example, adjust `courseWorkload`/dates as accurate per class):
```json
"hasCourseInstance": [
  {
    "@type": "CourseInstance",
    "courseMode": "onsite",
    "instructor": { "@id": "https://kevinthompsonphd.com/#person" },
    "courseWorkload": "Two days."
  },
  {
    "@type": "CourseInstance",
    "courseMode": "online",
    "instructor": { "@id": "https://kevinthompsonphd.com/#person" },
    "courseWorkload": "Two days, delivered live remotely."
  }
],
"image": {
  "@type": "ImageObject",
  "url": "https://kevinthompsonphd.com/REPLACE-WITH-ACTUAL-COURSE-IMAGE.jpg",
  "width": 1200,
  "height": 675
}
```
If online delivery is not actually offered for a given class, instead remove `"online"` from the top-level `courseMode` array rather than leaving an unbacked claim.

---

## 7. INFO — FAQPage: no Google SERP benefit sitewide (policy note, not a bug)

**Affected URLs:** `https://kevinthompsonphd.com/` (8 Q&As), `https://kevinthompsonphd.com/agile-consulting-services` (6 Q&As, also broken — see §1), `https://kevinthompsonphd.com/faq` (46 Q&As, also broken — see §1)

Google retired FAQ rich results for **all** sites as of May 7, 2026 (superseding the Aug 2023 gov/health-only restriction). None of these three FAQPage blocks — even once §1 is fixed — will produce a Google SERP feature. This is flagged **Info**, not Critical/High, per current policy:
- The `/faq` and `/agile-consulting-services` blocks must still be fixed (§1) because they are technically broken JSON, independent of rich-result eligibility.
- Whether to keep FAQPage markup at all is a business call: any AI/GEO-assistant citation benefit from well-formed `FAQPage` markup is **unconfirmed** — do not represent it as a ranking or SERP lever when scoping the fix.
- Genuine, standalone user Q&A content (not this site's case) should use `QAPage`, not `FAQPage` — not applicable here since these are curated FAQs, not community Q&A.

---

## 8. LOW/INFO — BreadcrumbList coverage is inconsistent but not damaging

**Present:** individual training-class pages (e.g. `.../agile-hardware-development-with-scrum`) and individual blog posts (e.g. `/agile-insights-blog/embedded-software`) — both correctly 3-level (`Home → Section → Page`), both resolve to real, live URLs.

**Absent:** `/about-kevin-thompson`, `/agile-consulting-services`, `/contact-us`, `/faq`, `/agile-hardware-papers-and-presentations`, `/podcasts-webinars`, `/agile-insights-blog` (index).

Severity is Low/Info because all of the pages missing `BreadcrumbList` are one level deep off the homepage (single-tier nav), where breadcrumb rich results add the least value. The two-tier content types (training classes, blog posts) — where breadcrumbs matter most for disambiguating URL hierarchy in SERPs — already have it correctly implemented. Recommend adding `BreadcrumbList` to the remaining top-level pages only for consistency/graph completeness, not as a priority fix.

Example for `/faq` (single-level page):
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://kevinthompsonphd.com" },
    { "@type": "ListItem", "position": 2, "name": "FAQ", "item": "https://kevinthompsonphd.com/faq" }
  ]
}
```

---

## 9. INFO — Page-level entities not linked back to WebSite via `isPartOf`

**Affected:** `AboutPage`, `ContactPage`, `CollectionPage` (papers, podcasts), `Blog` (blog index), `Service`, `Course` (all instances) — none declare `"isPartOf": { "@id": "https://kevinthompsonphd.com/#website" }`.

Not required for any rich result, but recommended for full graph connectivity given the site already invests in the `@id`-reference architecture. Low-cost addition, e.g. on ContactPage:
```json
"isPartOf": { "@id": "https://kevinthompsonphd.com/#website" }
```

---

## Schema type coverage by page type (present vs missing)

| Page type | Present | Missing / opportunity |
|---|---|---|
| Home | Person, Organization, WebSite+SearchAction, FAQPage | Organization `logo`/`sameAs` (§3), Person `sameAs` (§4) |
| About | AboutPage (+ shared graph) | BreadcrumbList (low), `isPartOf` (info) |
| Services | Service, FAQPage (**broken**, §1) | BreadcrumbList (low), `isPartOf` (info) |
| FAQ | FAQPage (**broken**, §1) | BreadcrumbList (low) |
| Contact | ContactPage (+ shared graph) | BreadcrumbList (low), `isPartOf` (info); no `ContactPoint`/phone in Organization (§3) |
| Training class (x7) | Course, CourseInstance, Offer, BreadcrumbList | `Offer.category` invalid (§5, HIGH), `image:null` + mode mismatch (§6, MEDIUM) |
| Blog post | Article, BreadcrumbList (+ shared graph) | Recommend `image` as full `ImageObject` w/ explicit width/height for large-image eligibility (currently a bare string URL — acceptable but suboptimal) |
| Blog index | Blog (+ shared graph) | BreadcrumbList (low) |
| Papers | CollectionPage (+ shared graph) | BreadcrumbList (low); consider `ScholarlyArticle`/`CreativeWork` items per paper for richer indexing if individual paper pages exist |
| Podcasts | CollectionPage (+ shared graph) | BreadcrumbList (low); consider `PodcastEpisode`/`VideoObject` per item if individual episode pages exist |

---

## Compact severity summary

| Severity | Finding | Evidence (URL / property) |
|---|---|---|
| CRITICAL | FAQPage `@context` replaced by unescaped compiled Blade `@context` directive output — block unparseable as JSON-LD | `/agile-consulting-services` (script ~L860), `/faq` (script ~L1905); key literal `<?php $__contextArgs...?>` instead of `"@context"` |
| HIGH | Organization node missing `logo` — blocks Logo rich result / weakens Knowledge Panel | sitewide `#organization` node, e.g. on `/` |
| HIGH | Course `offers.category` = `"Professional Training"`, not a valid enum (`Paid`/`Free`/`Subscription`) | all 7 `/agile-training-classes/*` pages, `offers.category` |
| MEDIUM | Person node missing `sameAs` | sitewide `#person` node |
| MEDIUM | Course `image: null` placeholder + `courseMode` (`onsite`,`online`) vs single onsite-only `hasCourseInstance` mismatch | all 7 `/agile-training-classes/*` pages |
| INFO | FAQPage retired from Google rich results sitewide (policy, not a bug) — applies once §1 fixed too | `/`, `/agile-consulting-services`, `/faq` |
| LOW/INFO | Inconsistent `BreadcrumbList` coverage on single-tier pages | `/about-kevin-thompson`, `/agile-consulting-services`, `/contact-us`, `/faq`, `/agile-hardware-papers-and-presentations`, `/podcasts-webinars`, `/agile-insights-blog` |
| INFO | No `isPartOf` link from page entities back to `#website` | AboutPage, ContactPage, CollectionPage x2, Blog, Service, Course x7 |
| PASS | `@id`-reference `@graph` architecture — no forked/duplicate Person or Organization found on any of the 10 sampled pages | all sampled URLs |

---

## Category score: 68 / 100

**Justification:**
- **Architecture (strong, +):** The sitewide `@graph` with canonical `@id` anchors is correctly and consistently implemented across every sampled page type (home, about, services, FAQ, contact, training, blog post, blog index, papers, podcasts) with zero entity forking detected. This is a genuinely above-average implementation most sites don't attempt.
- **Critical deduction:** Two live pages (`/faq`, `/agile-consulting-services`) ship structurally broken FAQPage JSON-LD due to an unescaped Blade directive collision — this is a real production bug shipping invalid markup, not a design gap.
- **High deductions:** Organization missing `logo` (blocks a real, currently-live rich-result type unrelated to the FAQ deprecation) and an invalid `Offer.category` enum value repeated across all 7 revenue-relevant Course pages, which is the site's actual commercial content type most likely to benefit from Course rich results.
- **Medium deductions:** Missing Person `sameAs`, Course `image:null` + mode mismatch, both systemic across page sets.
- Score reflects: solid foundational architecture undermined by one critical template bug and two recurring, fixable property-level errors on the highest commercial-value page type (Course/training pages). Fixing §1, §3, and §5 alone would likely move this into the low-80s.
