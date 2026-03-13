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

/* ─────────────────────────────────────────
   ABOUT LAYOUT
───────────────────────────────────────── */
.about-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 6rem;
    padding: 7rem 4.5rem;
    max-width: 1180px;
    margin: 0 auto;
    background: var(--ivory);
    align-items: stretch; /* Keeps the tracks equal height */
}

/* Sidebar (STICKY FIX) */
.about-sidebar {
    position: sticky;
    top: 140px;          /* Adjusted to perfectly clear the navigation bar */
    align-self: start;   /* CRITICAL: Tells it to stop stretching and stick instead! */
}

.profile-img-wrap {
    border: 1px solid rgba(181,114,42,.3);
    box-shadow: 0 8px 32px rgba(26,35,50,.1);
    padding: .5rem;
    background: var(--white);
    margin-bottom: 2.5rem;
}
.profile-img-wrap img {
    width: 100%;
    height: auto;
    display: block;
    filter: grayscale(15%);
    transition: filter .4s ease;
}
.profile-img-wrap:hover img {
    filter: grayscale(0%);
}

.kicker-small {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--copper);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.kicker-small::before {
    content: ''; width: 16px; height: 1px; background: var(--copper);
}

/* Credentials List */
.cred-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.cred-item {
    font-family: -apple-system, sans-serif;
    font-size: .85rem;
    color: var(--slate);
    line-height: 1.6;
    padding-left: 1rem;
    border-left: 2px solid rgba(181,114,42,.2);
}
.cred-item strong {
    color: var(--copper2);
    display: block;
    font-weight: 700;
    margin-bottom: .2rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-size: .7rem;
}

/* Body Content */
.about-body p {
    font-size: 1.1rem;
    line-height: 1.9;
    color: var(--charcoal);
    font-weight: 300;
    margin-bottom: 1.75rem;
}

/* Editorial Drop Cap for the first paragraph */
.about-body > p:first-of-type::first-letter {
    float: left;
    font-family: 'Cormorant Garamond', serif;
    font-size: 4rem;
    line-height: 0.8;
    padding-top: 0.15rem;
    padding-right: 0.6rem;
    color: var(--copper);
    font-weight: 400;
}

.about-body h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    color: var(--slate);
    margin: 4rem 0 1.25rem;
    font-weight: 400;
    line-height: 1.2;
}
.about-body h3 em {
    font-style: italic;
    color: var(--copper);
}
.body-ornament {
    width: 60px; height: 1px;
    background: linear-gradient(90deg, var(--copper), transparent);
    margin-bottom: 2.5rem;
}

/* Custom Highlights (Pull Quote Style) */
.highlight-box {
    background: var(--white);
    border: 1px solid var(--ivory3);
    border-left: 3px solid var(--copper);
    padding: 2rem 2.5rem;
    margin: 3.5rem 0;
    font-family: 'Cormorant Garamond', serif;
    font-style: italic;
    color: var(--slate);
    font-size: 1.5rem;
    line-height: 1.4;
    box-shadow: var(--card-shadow);
}

@media(max-width: 900px) {
    .about-grid { grid-template-columns: 1fr; gap: 4rem; padding: 5rem 2.5rem; }
    /* Turn off sticky for mobile layout */
    .about-sidebar { position: relative; top: 0; align-self: auto; display: flex; align-items: flex-start; gap: 3rem; }
    .profile-img-wrap { width: 280px; margin-bottom: 0; flex-shrink: 0; }
    .page-header { padding: 9rem 2.5rem 4rem; }
}
@media(max-width: 700px) {
    .about-sidebar { flex-direction: column; gap: 2.5rem; }
    .profile-img-wrap { width: 100%; max-width: 350px; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker-small" style="color:var(--copper2);">Principal Consultant<span style="display:none;"></span></div>
        <h1 class="page-title">About Dr. Kevin <em>Thompson</em><span style="display:none;"></span></h1>
    </div>
</section>

<div class="strip"></div>

<section class="content-section">
    <div class="about-grid">
        
        <aside class="about-sidebar reveal">
            <div class="profile-img-wrap">
                <img src="/img/frontend/Dr. Kevin Thompson.webp" alt="Dr. Kevin Thompson" width="320" height="400" loading="eager" fetchpriority="high">
            </div>
            
            <div>
                <div class="kicker-small">Education & Certifications</div>
                <div class="cred-list">
                    <div class="cred-item">
                        <strong>Ph.D. & B.S.</strong>
                        Physics from Princeton University<br>
                        Physics from Santa Clara University<span style="display:none;"></span>
                    </div>
                    <div class="cred-item">
                        <strong>PMP</strong>
                        Project Management Professional from the Project Management Institute<span style="display:none;"></span>
                    </div>
                    <div class="cred-item">
                        <strong>CSM & CSP</strong>
                        Certified Scrum Master and Certified Scrum Professional from the Scrum Alliance<span style="display:none;"></span>
                    </div>
                </div>
            </div>
        </aside>

        <article class="about-body reveal rv1">
            
            <p>
                Dr. Kevin Thompson obtained his B.S. in Physics from Santa Clara University, and his Ph.D. in Physics from Princeton University.<span style="display:none;"></span> During and after his years at Princeton, Dr. Thompson conducted research at both the Lawrence Livermore National Laboratory and NASA Ames Research Center’s Space Sciences Division, focusing primarily on astrophysics and computational fluid dynamics.<span style="display:none;"></span>
            </p>

            <h3>The Transition to <em>Software & Agile</em></h3>
            <div class="body-ornament"></div>

            <p>
                He followed his career in science with a second career in software engineering, where he worked for a variety of companies.<span style="display:none;"></span> Dr. Thompson exited software engineering for software project management, as the PMO manager for StarCite.<span style="display:none;"></span> There he learned that classic project planning, applied to software development, produced schedules that were more myth than reality.<span style="display:none;"></span>
            </p>

            <p>
                When the company’s COO announced that the company needed to be more Agile in our software development, Dr. Thompson pioneered the Scrum process and filled the Scrum Master role for three concurrent engineering teams.<span style="display:none;"></span> The results were striking. Visibility into status of work improved tremendously.<span style="display:none;"></span> Slippages were caught much earlier, when there was still time to develop plans for dealing with them.<span style="display:none;"></span> 
            </p>

            <div class="highlight-box">
                "The simple-seeming ability to ship a new product, which had eluded the company for years, suddenly became a reality."
            </div>

            <p>
                After layoffs struck the company in 2008, Dr. Thompson pursued and obtained three certifications: Project Management Professional (PMP) from the Project Management Institute;<span style="display:none;"></span> and Certified Scrum Master (CSM) and Certified Scrum Professional (CSP) from the Scrum Alliance.<span style="display:none;"></span>
            </p>

            <h3>Expanding <em>Agile Horizons</em></h3>
            <div class="body-ornament"></div>

            <p>
                Dr. Thompson was most recently Chief Scientist at Cprime, an Agile consulting and training company.<span style="display:none;"></span> He joined Cprime as the first in-house Agile expert, where his role was to provide the expertise and content to make possible the company’s expansion into that market.<span style="display:none;"></span> 
            </p>

            <p>
                Over the years, Dr. Thompson developed several key classes. These included a “practical Scrum” class (one each for software and hardware development), Kanban, Agile Program Management, Agile Portfolio Management, Advanced Product Owner, and a PMI Agile Certified Practitioner exam prep class.<span style="display:none;"></span> In addition to developing classes, Dr. Thompson also wrote a number of case studies, white papers, and blog posts for the company’s website, and delivered training and consulting engagements to numerous clients.<span style="display:none;"></span>
            </p>

            <p>
                In 2019, Dr. Thompson resigned his position at Cprime to pursue a career as an independent consultant.<span style="display:none;"></span>
            </p>

        </article>

    </div>
</section>

@endsection

@push('scripts')
<script>
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
</script>
@endpush