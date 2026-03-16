@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 12rem 4.5rem 6rem;
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
.kicker {
    display: inline-flex;
    align-items: center;
    gap: .7rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .3em;
    text-transform: uppercase;
    color: var(--copper2);
    margin-bottom: 1.1rem;
}
.kicker::before { content: ''; width: 24px; height: 1px; background: var(--copper2); }

.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3rem, 4.5vw, 4rem);
    font-weight: 300;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 1.5rem;
    max-width: 900px;
}
.page-title em { font-style: italic; color: var(--copper3); }

.short-desc {
    font-family: -apple-system, sans-serif;
    font-size: 1.1rem;
    color: rgba(250,247,242,.7);
    max-width: 700px;
    line-height: 1.8;
    font-weight: 300;
}

/* ─────────────────────────────────────────
   COURSE LAYOUT
───────────────────────────────────────── */
.course-section {
    padding: 7rem 4.5rem;
    background: var(--ivory);
}
.course-grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 5rem;
    align-items: stretch;
}

/* MAIN CONTENT */
.course-main h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    color: var(--slate);
    margin: 0 0 1.25rem;
    font-weight: 400;
    line-height: 1.2;
}
.course-main h2 em { font-style: italic; color: var(--copper); }

.ornament {
    width: 60px; height: 1.5px;
    background: linear-gradient(90deg, var(--copper), transparent);
    margin-bottom: 2rem;
}

.course-body p {
    font-family: 'Palatino Linotype', 'Book Antiqua', Palatino, Georgia, serif;
    font-size: 1.15rem;
    line-height: 1.9;
    color: var(--charcoal);
    margin-bottom: 1.75rem;
}

/* Highlight Box for Objectives */
.objectives-box {
    background: var(--white);
    border: 1px solid var(--ivory3);
    border-left: 3px solid var(--copper);
    padding: 2.5rem;
    margin: 3.5rem 0;
    box-shadow: var(--card-shadow);
}
.objectives-box h3 {
    font-family: -apple-system, sans-serif;
    font-size: .85rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--slate);
    margin-bottom: 1rem;
}
.objectives-box p {
    font-family: 'Palatino Linotype', serif;
    font-size: 1.2rem;
    font-style: italic;
    line-height: 1.7;
    color: var(--charcoal);
    margin: 0;
}

/* Curriculum Topics Layout */
.topics-container {
    margin-top: 4rem;
}
.topics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem 2rem;
}
.topic-group h4 {
    font-family: -apple-system, sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--slate);
    margin-bottom: 1rem;
    padding-bottom: .5rem;
    border-bottom: 1px solid var(--copper);
    display: inline-block;
}
.topic-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.topic-item {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    font-family: -apple-system, sans-serif;
    font-size: .9rem;
    color: var(--body-text);
    line-height: 1.4;
}
.topic-icon {
    color: var(--copper2);
    margin-top: .15rem;
    flex-shrink: 0;
}

/* SIDEBAR META */
.course-sidebar {
    position: sticky;
    top: 140px;
    align-self: start;
}
.meta-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    box-shadow: var(--card-shadow);
    padding: 2.5rem;
}
.meta-item {
    margin-bottom: 2rem;
}
.meta-item:last-child {
    margin-bottom: 0;
}
.meta-label {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-family: -apple-system, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--copper);
    margin-bottom: .75rem;
}
.meta-value {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    line-height: 1.6;
    color: var(--slate);
    font-weight: 500;
}

.sidebar-cta {
    margin-top: 2rem;
    display: block;
    text-align: center;
    width: 100%;
    padding: 1.1rem 2rem;
    background: linear-gradient(135deg, var(--copper), var(--copper2));
    color: var(--white);
    font-family: -apple-system, sans-serif;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    transition: all .35s;
    box-shadow: 0 4px 24px rgba(181,114,42,.35);
    text-decoration: none;
}
.sidebar-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 36px rgba(181,114,42,.5);
    background: linear-gradient(135deg, var(--copper2), var(--copper3));
    color: var(--slate);
}

@media(max-width: 900px) {
    .course-grid { grid-template-columns: 1fr; gap: 4rem; }
    .course-sidebar { position: static; align-self: auto; }
    .topics-grid { grid-template-columns: 1fr; gap: 2rem; }
    .page-header { padding: 10rem 2.5rem 4rem; }
    .course-section { padding: 5rem 2.5rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <a href="{{ route('training') }}" class="kicker" style="text-decoration: none;">← Back to Curriculum</a>
        
        <h1 class="page-title">Agile Software Development <em>with Scrum</em></h1>
        
        <p class="short-desc">The Scrum process framework is well-suited for teams that engage in product development. This class trains attendees in the practical details of the Scrum process framework, as applied to the development of software products.</p>
    </div>
</section>

<div class="strip"></div>

<section class="course-section">
    <div class="course-grid">
        
        <div class="course-main reveal">
            
            <h2>Course <em>Overview</em></h2>
            <div class="ornament"></div>

            <div class="course-body">
                <p>The Scrum process framework has revolutionized software engineering by allowing teams to pivot quickly, manage complexity, and deliver continuous value. Unlike traditional waterfall methods, Scrum is specifically tailored to handle the unpredictable nature of product development.</p>
                <p>This immersive training goes beyond the theory, putting attendees into the practical, day-to-day realities of running a successful software Scrum team. You will walk away with the exact frameworks needed to build the right things, and build them right.</p>
            </div>

            <div class="objectives-box reveal rv1">
                <h3>Learning Objectives</h3>
                <p>Attendees learn and experience all of the practical, hands-on skills required for a Scrum Team to plan and implement work in a Sprint. Attendees also receive an understanding of the drivers and benefits of Scrum, and its place in the context of the larger world of project management.</p>
            </div>

            <div class="topics-container reveal rv2">
                <h2>Curriculum &amp; <em>Topics</em></h2>
                <div class="ornament"></div>
                
                <div class="topics-grid">
                    
                    <div class="topic-group">
                        <h4>Introduction to Scrum</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Classical and Agile project management</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Building the Right Things vs. Building Things Right</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Scrum Overview</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Requirements</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Progressive Elaboration</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Stories & Epics</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Definition of Done</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Estimation</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Planning Poker</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Affinity Estimation</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Units for Estimation</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Task Decomposition</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Task Breakdowns</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Task Estimation</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Planning Sprints</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Scheduling</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Velocity Forecasting</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Scope Definition</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Tracking Sprint Progress</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Tracking Data</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Burndown Charts</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Releases</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> The Release Planning Horizon</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Potentially Shippable Increments</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Hardening Sprints</div>
                        </div>
                    </div>

                    <div class="topic-group">
                        <h4>Distributed Organizations</h4>
                        <div class="topic-list">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Co-Location versus Distribution</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Best Practices for Distributed Orgs</div>
                        </div>
                    </div>

                    <div class="topic-group" style="grid-column: 1 / -1;">
                        <h4>Time Boxes &amp; Meetings</h4>
                        <div class="topic-list" style="display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;">
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Sprint</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Backlog Grooming</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Sprint Planning</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Daily Stand-Up</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Review &amp; Retrospective</div>
                            <div class="topic-item"><svg class="topic-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg> Five-hour Sample Scrum Project</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <aside class="course-sidebar reveal">
            <div class="meta-card">
                
                <div class="meta-item">
                    <div class="meta-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Course Length
                    </div>
                    <div class="meta-value">Two days.</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Target Audience
                    </div>
                    <div class="meta-value">Software Developers, QA personnel, Scrum Masters, Product Owners, Project Managers, Product Managers, and managers.</div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Prerequisites
                    </div>
                    <div class="meta-value">No prerequisites.</div>
                </div>

                <a href="{{ route('contact') }}" class="sidebar-cta">Request Booking</a>

            </div>
        </aside>

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