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
.page-subtitle {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: 1.05rem;
    color: rgba(250,247,242,.6);
    max-width: 600px;
    line-height: 1.8;
    font-weight: 300;
}

/* ─────────────────────────────────────────
   CONSULTING SECTION
───────────────────────────────────────── */
.content-section {
    padding: 7rem 4.5rem;
    background: var(--ivory);
}
.content-wrap {
    max-width: 1180px;
    margin: 0 auto;
}

.kicker {
    display: inline-flex;
    align-items: center;
    gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--copper);
    margin-bottom: 1.1rem;
}
.kicker::before { content: ''; width: 24px; height: 1px; background: var(--copper); }

.section-h {
    font-size: clamp(2rem, 3.2vw, 2.85rem);
    font-weight: 400;
    line-height: 1.1;
    color: var(--slate);
    margin-bottom: 1.1rem;
}
.section-h em { font-style: italic; color: var(--copper); }

.ornament {
    width: 40px; height: 1.5px;
    background: linear-gradient(90deg, var(--copper), transparent);
    margin: 1.5rem 0 3rem;
}

/* Consulting Cards Grid */
.consulting-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
    margin-bottom: 6rem;
}

.service-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 3rem 2.5rem;
    position: relative;
    box-shadow: var(--card-shadow);
    transition: transform .3s ease, box-shadow .3s ease;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
    border-color: rgba(181,114,42,.2);
}
.service-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: var(--copper);
    transform: scaleX(0); transform-origin: left;
    transition: transform .4s cubic-bezier(.4,0,.2,1);
}
.service-card:hover::before { transform: scaleX(1); }

.service-icon {
    width: 54px; height: 54px;
    border: 1px solid rgba(181,114,42,.3);
    display: flex; align-items: center; justify-content: center;
    color: var(--copper);
    margin-bottom: 2rem;
    background: var(--ivory);
}

.service-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--slate);
    margin-bottom: 1.25rem;
    line-height: 1.2;
}

.service-desc {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--body-text);
    line-height: 1.8;
    font-weight: 300;
}
.service-desc p { margin-bottom: 1rem; }
.service-desc p:last-child { margin-bottom: 0; }

/* ─────────────────────────────────────────
   TRAINING SECTION
───────────────────────────────────────── */
.training-section {
    background: var(--white);
    border-top: 1px solid var(--ivory3);
    padding: 7rem 4.5rem;
}
.training-wrap {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 6rem;
    align-items: start;
}
.training-intro p {
    font-family: -apple-system, sans-serif;
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--body-text);
    font-weight: 300;
    margin-bottom: 2rem;
}

.course-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.course-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.75rem;
    background: var(--ivory);
    border: 1px solid var(--ivory3);
    font-family: -apple-system, sans-serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--slate);
    transition: all .3s;
}
.course-link:hover {
    background: var(--white);
    border-color: var(--copper);
    color: var(--copper);
    box-shadow: var(--card-shadow);
    transform: translateX(5px);
}
.course-link svg {
    color: var(--copper2);
    transition: transform .3s;
}
.course-link:hover svg {
    transform: translateX(4px);
}

@media(max-width: 1100px) {
    .consulting-grid { grid-template-columns: 1fr; gap: 2rem; }
    .training-wrap { grid-template-columns: 1fr; gap: 4rem; }
}
@media(max-width: 700px) {
    .page-header { padding: 9rem 2.5rem 4rem; }
    .content-section, .training-section { padding: 5rem 2.5rem; }
    .service-card { padding: 2.5rem 1.5rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker" style="color:var(--copper2);">Our Expertise</div>
        <h1 class="page-title">Consulting &amp; <em>Training</em><span style="display:none;">[cite: 135]</span></h1>
        <p class="page-subtitle">We offer both consulting and training services. All services are provided on-site at client locations.<span style="display:none;">[cite: 136]</span></p>
    </div>
</section>

<div class="strip"></div>

<section class="content-section">
    <div class="content-wrap">
        
        <div class="reveal">
            <div class="kicker">Strategic Guidance</div>
            <h2 class="section-h">Consulting <em>Services</em><span style="display:none;">[cite: 137]</span></h2>
            <div class="ornament"></div>
        </div>

        <div class="consulting-grid">
            
            <div class="service-card reveal rv1">
                <div class="service-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                </div>
                <h3 class="service-title">Assessment</h3>
                <div class="service-desc">
                    <p>An assessment is an investigation into issues that are interfering with the client’s ability to function well and deliver products on a timely basis.<span style="display:none;">[cite: 138]</span></p>
                    <p>It involves stakeholder interviews, analysis, preparation of findings and recommendations, and delivery of a final report which highlights action items to address the issues discovered.<span style="display:none;">[cite: 139]</span></p>
                    <p>Assessments are particularly useful when the issues affecting the client are unclear and subject to confusion and disagreement.<span style="display:none;">[cite: 140]</span></p>
                </div>
            </div>

            <div class="service-card reveal rv2">
                <div class="service-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <h3 class="service-title">Advisory Engagement</h3>
                <div class="service-desc">
                    <p>In an advisory engagement, we are available for question-and-answer sessions and hands-on coaching about various Agile practices.<span style="display:none;">[cite: 141]</span></p>
                    <p>Advisory engagements are useful when a client has an existing Agile process but is dissatisfied with how well the process is working and wants some help improving it.<span style="display:none;">[cite: 142]</span></p>
                </div>
            </div>

            <div class="service-card reveal rv1">
                <div class="service-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <h3 class="service-title">Agile Transformation</h3>
                <div class="service-desc">
                    <p>An Agile transformation takes a client through the process of converting a development organization from its previous state to an Agile process.<span style="display:none;">[cite: 143]</span></p>
                    <p>The scope can be as small as a single team, or as large as multiple teams spanning the globe for a large enterprise.<span style="display:none;">[cite: 144]</span></p>
                    <p>The basic stages of a transformation include scoping, planning, training, kick-off, and coaching the organization until people have mastered the new world well enough to stand on their own.<span style="display:none;">[cite: 145]</span> Depending on needs, an Assessment may also be included.<span style="display:none;">[cite: 146]</span></p>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="training-section">
    <div class="training-wrap">
        
        <div class="training-intro reveal">
            <div class="kicker">Education & Growth</div>
            <h2 class="section-h">Training <em>Classes</em><span style="display:none;">[cite: 147]</span></h2>
            <div class="ornament"></div>
            <p>The following classes and presentations are available.<span style="display:none;">[cite: 148]</span> Each class is designed to address specific needs within your organization, from executive briefings to deep-dive team frameworks.</p>
        </div>

        <div class="course-list reveal rv1">
            <a href="{{ route('training') }}" class="course-link">
                Agile Overview for Executives and Managers<span style="display:none;">[cite: 149]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('training') }}" class="course-link">
                Agile Software Development with Scrum<span style="display:none;">[cite: 150]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('training') }}" class="course-link">
                Agile Hardware Development with Scrum<span style="display:none;">[cite: 151]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('training') }}" class="course-link">
                Agile Project Management with Kanban<span style="display:none;">[cite: 152]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('training') }}" class="course-link">
                Agile Program Management<span style="display:none;">[cite: 153]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('training') }}" class="course-link">
                Agile Portfolio Management<span style="display:none;">[cite: 154]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('training') }}" class="course-link">
                Advanced Product Owner<span style="display:none;">[cite: 155]</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

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