# Visual / Mobile Findings — kevinthompsonphd.com

**Method:** Playwright captures at 390×844 (3× DPR, so each mobile PNG is exactly one viewport) and 1440×900 desktop.

**Scope — read this first.** I reviewed the **mobile** captures of the **homepage** and **/agile-consulting-services** in detail. The desktop captures and the `/agile-insights-blog` and `/about-kevin-thompson` captures were **not** individually reviewed. Findings below are limited to what I actually looked at. Tap-target sizing was assessed visually, not by measuring computed styles.

The source PNGs were deleted after analysis (8.2 MB, unreferenced). Re-capture with:
```bash
"$HOME/.claude/skills/seo/bin/claude-seo" run capture_screenshot.py <url> --mobile
```

---

## POSITIVE | Mobile above-the-fold on the homepage is genuinely strong

Within a single 390×844 viewport, without scrolling, a visitor sees:

- Logo + brand lockup ("Kevin Thompson / PH.D. CONSULTING")
- Kicker: **"AGILE HARDWARE CONSULTING"** — on-target for the primary keyword set in the client brief
- H1: *"Reduce risk. Ship faster."*
- Both supporting paragraphs, in full
- **Both CTAs — "SERVICES" (filled copper) and "GET IN TOUCH" (outlined)**

Getting the full value proposition *and* two CTAs above the fold on mobile is uncommon. No change needed.

## MEDIUM | /agile-consulting-services has no CTA above the fold on mobile

The first viewport contains kicker ("OUR EXPERTISE"), H1 ("Consulting & *Training*"), and a two-line subtitle — then the section ends and the next section ("Consulting Services") begins. **There is no button or contact link anywhere in the first viewport.**

This is a money page. A visitor arriving from search must scroll through the hero and at least one full content section before any conversion affordance appears.

**Recommendation:** add a primary CTA into the services hero, mirroring the homepage pattern. The hero has ample vertical space — roughly a third of the first viewport is empty below the subtitle.

## POSITIVE | No layout breakage at 390px

No horizontal overflow, clipped text, or overlapping elements on either page. Body copy is comfortably legible at default size; line length and leading are appropriate for mobile reading.

## POSITIVE | Heading hierarchy renders as intended

The DB-driven page header (kicker / regular heading / italic copper accent) renders correctly on `/agile-consulting-services` — "Consulting &" in white with "*Training*" in italic copper, exactly as configured.

## INFO | The serif hero heading is the LCP element

The large Cormorant Garamond heading is the largest above-fold paint on both pages. This corroborates `performance.md`: 4 of 5 measured LCP elements are plain **text**, stalled by the render-blocking CSS/font chain — not by image weight. It reinforces that the LCP fix is font/CSS delivery (self-host the font, inline critical CSS), not image optimisation.

## INFO | Navigation is a single hamburger control

Adequately sized and clear of the logo. Menu contents were not opened, so the expanded nav state is unverified.

---

## Not verified

- Desktop (1440×900) layouts
- `/agile-insights-blog` and `/about-kevin-thompson` at any viewport
- Computed tap-target dimensions against the 44×44px guideline
- Expanded mobile navigation state
- Any interaction, scroll, or animation behaviour
