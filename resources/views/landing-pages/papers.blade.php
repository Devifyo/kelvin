@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 11rem 4.5rem 6rem;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.6) 0%, transparent 70%);
    z-index: 0;
}
.header-content {
    max-width: 1180px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    text-align: center; /* Centered for the new layout */
}
.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3rem, 5vw, 4.5rem);
    font-weight: 300;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 1.5rem;
}
.page-title em { font-style: italic; color: var(--copper3); }
.page-subtitle {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: 1.05rem;
    color: rgba(250,247,242,.6);
    max-width: 700px;
    line-height: 1.8;
    font-weight: 300;
    margin: 0 auto;
}
.kicker {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--copper2);
    margin-bottom: 1.1rem;
}
.kicker::before, .kicker::after { 
    content: ''; width: 24px; height: 1px; background: var(--copper2); 
}

/* ─────────────────────────────────────────
   FILTER BAR
───────────────────────────────────────── */
.filter-container {
    background: var(--ivory2);
    padding: 2rem 4.5rem;
    border-bottom: 1px solid var(--ivory3);
    display: flex;
    justify-content: center;
    position: sticky;
    top: 85px; /* Adjust based on your nav height */
    z-index: 150;
    backdrop-filter: blur(10px);
    background: rgba(242, 236, 227, 0.9);
}
.filter-menu {
    display: flex;
    gap: .5rem;
    background: var(--white);
    padding: .5rem;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(26,35,50,.05);
    border: 1px solid var(--ivory3);
    flex-wrap: wrap;
    justify-content: center;
}
.filter-btn {
    font-family: -apple-system, sans-serif;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--slate);
    padding: .8rem 1.75rem;
    border-radius: 50px;
    transition: all .3s ease;
}
.filter-btn:hover {
    color: var(--copper);
}
.filter-btn.active {
    background: var(--slate);
    color: var(--white);
    box-shadow: 0 4px 12px rgba(26,35,50,.15);
}

/* ─────────────────────────────────────────
   PAPERS GRID
───────────────────────────────────────── */
.content-section {
    padding: 6rem 4.5rem;
    background: var(--ivory);
    min-height: 60vh;
}
.papers-grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2.5rem;
}

/* Paper Cards */
.paper-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 3rem 2.5rem;
    position: relative;
    box-shadow: var(--card-shadow);
    transition: all .4s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    /* Used for the JS filter animation */
    animation: fadeIn .6s ease forwards; 
}
.paper-card:hover {
    box-shadow: var(--hover-shadow);
    border-color: rgba(181,114,42,.2);
    transform: translateY(-4px);
}
.paper-card::before {
    content: ''; position: absolute; left: 0; right: 0; top: 0;
    height: 3px; background: linear-gradient(90deg, var(--copper), var(--copper2));
    transform: scaleX(0); transform-origin: left;
    transition: transform .4s cubic-bezier(.4,0,.2,1);
}
.paper-card:hover::before { transform: scaleX(1); }

.paper-meta {
    font-family: -apple-system, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
}
.paper-category-tag {
    background: var(--ivory2);
    padding: .3rem .8rem;
    border-radius: 4px;
    color: var(--copper);
}

.paper-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: 1rem;
    line-height: 1.2;
}
.paper-desc {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--body-text);
    line-height: 1.7;
    font-weight: 300;
    margin-bottom: 2rem;
    flex-grow: 1; /* Pushes the button to the bottom */
}

.download-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-family: -apple-system, sans-serif;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--copper);
    transition: color .3s;
    align-self: flex-start;
    padding-top: 1rem;
    border-top: 1px solid var(--ivory3);
    width: 100%;
}
.download-btn:hover { color: var(--slate); }
.download-btn svg { transition: transform .3s; }
.download-btn:hover svg { transform: translateY(3px); }

/* No Results Message */
.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem;
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    color: var(--muted);
    display: none;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

@media(max-width: 900px) {
    .papers-grid { grid-template-columns: 1fr; }
    .page-header { padding: 9rem 2.5rem 4rem; }
    .content-section { padding: 4rem 2.5rem; }
    .filter-container { padding: 1.5rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Knowledge &amp; Research</div>
        <h1 class="page-title">Papers &amp; <em>Presentations</em><span style="display:none;">[cite: 55]</span></h1>
        <p class="page-subtitle">A comprehensive collection of insights, methodologies, and findings from our extensive engagements in Agile hardware and software development.</p>
    </div>
</section>

<div class="filter-container reveal rv1">
    <div class="filter-menu">
        <button class="filter-btn active" data-filter="all">All Documents</button>
        <button class="filter-btn" data-filter="case-studies">Case Studies</button>
        <button class="filter-btn" data-filter="white-papers">White Papers</button>
        <button class="filter-btn" data-filter="presentations">Presentations</button>
    </div>
</div>

<section class="content-section">
    <div class="papers-grid" id="papers-container">

        <div class="paper-card" data-category="case-studies">
            <div class="paper-meta">
                <span class="paper-category-tag">Case Study</span>
                Hardware Engagement<span style="display:none;">[cite: 58]</span>
            </div>
            <h3 class="paper-title">Eleven Lessons Learned about Agile Hardware Development<span style="display:none;">[cite: 59]</span></h3>
            <div class="paper-desc">
                Thermo Fisher Scientific makes biotechnology equipment and supplies. This case study of an Agile transformation for the company shows lessons learned from Dr. Thompson’s first Agile hardware engagement.<span style="display:none;">[cite: 60]</span> The lessons aligned surprisingly well with the predictions of the white paper, Agile Processes for Hardware Development.<span style="display:none;">[cite: 61]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="case-studies">
            <div class="paper-meta">
                <span class="paper-category-tag">Case Study</span>
                Hardware Engagement
            </div>
            <h3 class="paper-title">AgileVox Scrum for Hardware<span style="display:none;">[cite: 63]</span></h3>
            <div class="paper-desc">
                This case study of the Thermo Fisher transformation was written up by and appeared in AgileVox magazine, a Scrum Alliance publication.<span style="display:none;">[cite: 64]</span> It overlaps with the above case study but provides more perspectives from the participants in the transformation.<span style="display:none;">[cite: 65]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="case-studies">
            <div class="paper-meta">
                <span class="paper-category-tag">Case Study</span>
                Hardware Engagement
            </div>
            <h3 class="paper-title">Plantronics Case Study<span style="display:none;">[cite: 67]</span></h3>
            <div class="paper-desc">
                Plantronics has a lengthy pedigree in making audio headsets of all kinds.<span style="display:none;">[cite: 68]</span> The Plantronics case study highlights the issues and successes of an Agile hardware process applied to a Research and Development organization.<span style="display:none;">[cite: 69]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="case-studies">
            <div class="paper-meta">
                <span class="paper-category-tag">Case Study</span>
                Hardware Engagement
            </div>
            <h3 class="paper-title">Bird Technologies Case Study<span style="display:none;">[cite: 71]</span></h3>
            <div class="paper-desc">
                Bird Technologies makes radio-frequency communications products and test equipment. Dr. Thompson developed this case study because the Agile Alliance wanted him to deliver a presentation about the transformation work he did for Bird at an Agile Alliance conference.<span style="display:none;">[cite: 72]</span> It highlights the challenges and successes of Bird’s Agile hardware transformation.<span style="display:none;">[cite: 73]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="case-studies">
            <div class="paper-meta">
                <span class="paper-category-tag">Case Study</span>
                Software Engagement<span style="display:none;">[cite: 74]</span>
            </div>
            <h3 class="paper-title">A Real Release-Planning Experience<span style="display:none;">[cite: 75]</span></h3>
            <div class="paper-desc">
                This case study of Accela shows the fine details of their first Release Planning experience.<span style="display:none;">[cite: 76]</span> It makes a good reference for any team or company that is about to have their first such experience.<span style="display:none;">[cite: 77]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="case-studies">
            <div class="paper-meta">
                <span class="paper-category-tag">Case Study</span>
                Software Engagement
            </div>
            <h3 class="paper-title">Agilent Case Study<span style="display:none;">[cite: 79]</span></h3>
            <div class="paper-desc">
                This was a large Agile transformation, involving 14 teams spread across three continents.<span style="display:none;">[cite: 80]</span> The case study documents the before and after states and describes basic elements of the transformation.<span style="display:none;">[cite: 81]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="white-papers">
            <div class="paper-meta">
                <span class="paper-category-tag">White Paper</span>
                Methodology
            </div>
            <h3 class="paper-title">Agile Processes for Hardware Development<span style="display:none;">[cite: 83]</span></h3>
            <div class="paper-desc">
                This is the foundational publication on how to develop hardware products using an Agile process.<span style="display:none;">[cite: 84]</span> It reflects 18 months of Dr. Thompson’s original research into the relevant issues and has been the basis for our subsequent work in the field.<span style="display:none;">[cite: 85]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="white-papers">
            <div class="paper-meta">
                <span class="paper-category-tag">White Paper</span>
                Methodology
            </div>
            <h3 class="paper-title">Agile Development for Medical Products<span style="display:none;">[cite: 87]</span></h3>
            <div class="paper-desc">
                The development of medical products is regulated by the US Food and Drug Administration, whose regulations permeate all aspects of development.<span style="display:none;">[cite: 88]</span> In spite of some beliefs to the contrary, FDA rules and Agile development can coexist without any difficulty.<span style="display:none;">[cite: 89]</span> This paper addresses common issues with FDA regulation, especially around requirements management and traceability of test cases.<span style="display:none;">[cite: 90]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="white-papers">
            <div class="paper-meta">
                <span class="paper-category-tag">White Paper</span>
                Analysis
            </div>
            <h3 class="paper-title">The Price of Uncertainty<span style="display:none;">[cite: 92]</span></h3>
            <div class="paper-desc">
                This paper conducts an analysis of two projects, both intended to produce the same Business Intelligence and Reporting system.<span style="display:none;">[cite: 93]</span> One uses the classic Waterfall method, while the other uses an Agile process.<span style="display:none;">[cite: 94]</span> The two projects are stress-tested with unforeseen challenges and delays, and the results are analyzed to show the differences in outcomes for the two projects.<span style="display:none;">[cite: 95]</span> The Agile project emerges as the clear winner when uncertainty is high.<span style="display:none;">[cite: 96]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="white-papers">
            <div class="paper-meta">
                <span class="paper-category-tag">White Paper</span>
                Methodology
            </div>
            <h3 class="paper-title">The Agile PMO<span style="display:none;">[cite: 97]</span></h3>
            <div class="paper-desc">
                Project, Program, and Project-Portfolio Management Organizations need to evolve from their classic roots in order to accommodate Agile processes.<span style="display:none;">[cite: 98]</span> This paper analyzes the impacts of Agile processes on PMOs, PgMOs, and PPMOs.<span style="display:none;">[cite: 99]</span> It reveals a surprising discovery, namely that the impact decreases as one moves from the PMO, through the PgMO, to the PPMO, because the PPMO is found to have a substantial “Agile flavor” from the beginning.<span style="display:none;">[cite: 100]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="white-papers">
            <div class="paper-meta">
                <span class="paper-category-tag">White Paper</span>
                Methodology
            </div>
            <h3 class="paper-title">Recipes for Agile Governance in the Enterprise (RAGE)<span style="display:none;">[cite: 102]</span></h3>
            <div class="paper-desc">
                The purpose of this lengthy white paper was to develop scaling concepts for the Agile world.<span style="display:none;">[cite: 103]</span> It pioneered Agile versions of Program and Portfolio management. The basis for the entire paper is a definition for governance that Dr. Thompson devised for it, namely, “Governance is the formalization and exercise of repeatable decision-making practices.”<span style="display:none;">[cite: 104]</span> Much of the content for this paper was folded into the Sage book.<span style="display:none;">[cite: 105]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="white-papers">
            <div class="paper-meta">
                <span class="paper-category-tag">White Paper</span>
                Methodology
            </div>
            <h3 class="paper-title">Agile, Scrum, and “Hitting the Date”<span style="display:none;">[cite: 106]</span></h3>
            <div class="paper-desc">
                This paper examines some misconceptions around the inability to hit dates in product development with Scrum and clarifies that Scrum is actually a very date-oriented process.<span style="display:none;">[cite: 107]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="presentations">
            <div class="paper-meta">
                <span class="paper-category-tag">Presentation</span>
                Hardware<span style="display:none;">[cite: 110]</span>
            </div>
            <h3 class="paper-title">The Agile Hardware Research Project<span style="display:none;">[cite: 111]</span></h3>
            <div class="paper-desc">
                This presentation lays out the findings from Dr. Thompson’s original research into the nature of Agile hardware development.<span style="display:none;">[cite: 112]</span> It provided the foundation for all subsequent work he has done in this area.<span style="display:none;">[cite: 113]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="presentations">
            <div class="paper-meta">
                <span class="paper-category-tag">Presentation</span>
                Hardware
            </div>
            <h3 class="paper-title">Agile for Hardware: A Briefing to Gartner, Inc.<span style="display:none;">[cite: 114]</span></h3>
            <div class="paper-desc">
                Gartner is a highly respected research and advisory company focusing on business and technology topics.<span style="display:none;">[cite: 115]</span> This briefing that Dr. Thompson delivered to Gartner expresses the basic drivers and challenges of implementing hardware products with Agile processes.<span style="display:none;">[cite: 116]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="paper-card" data-category="presentations">
            <div class="paper-meta">
                <span class="paper-category-tag">Presentation</span>
                Hardware
            </div>
            <h3 class="paper-title">Scrum for Hardware Conference Keynote<span style="display:none;">[cite: 118]</span></h3>
            <div class="paper-desc">
                The Scrum4HW conference was held under the auspices of the Scrum Alliance, to explore the possibility of using Scrum for hardware development.<span style="display:none;">[cite: 119]</span> Dr. Thompson was contacted and asked to deliver a keynote presentation due to the article in AgileVox magazine about his introduction of Scrum for hardware development at Thermo Fisher Scientific.<span style="display:none;">[cite: 120]</span> This presentation is the one that he delivered to the conference.<span style="display:none;">[cite: 121]</span>
            </div>
            <a href="#" class="download-btn">
                Download PDF
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="no-results" id="no-results-msg">
            No documents found in this category.
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
// Intersection Observer for scroll animations
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

// Vanilla JS Filtering Logic
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.paper-card');
    const noResultsMsg = document.getElementById('no-results-msg');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');
            let visibleCount = 0;

            // Filter cards
            cards.forEach(card => {
                // Reset animation
                card.style.animation = 'none';
                card.offsetHeight; /* trigger reflow */
                card.style.animation = null; 

                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show 'no results' message if empty
            noResultsMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    });
});
</script>
@endpush