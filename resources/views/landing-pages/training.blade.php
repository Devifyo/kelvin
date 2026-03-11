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
    font-size: clamp(3rem, 4.5vw, 4rem);
    font-weight: 300;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 1.5rem;
    max-width: 900px;
}
.page-title em { font-style: italic; color: var(--copper3); }

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
    align-items: start;
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
    font-family: -apple-system, sans-serif;
    font-size: 1.05rem;
    line-height: 1.85;
    color: var(--charcoal);
    font-weight: 300;
    margin-bottom: 1.75rem;
}

/* Highlight Box for Objectives */
.objectives-box {
    background: var(--white);
    border: 1px solid var(--ivory3);
    border-left: 3px solid var(--copper);
    padding: 2.5rem;
    margin: 3rem 0;
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

/* Topics Grid */
.topics-container {
    margin-top: 4rem;
}
.topics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem 2rem;
}
.topic-item {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--body-text);
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--ivory3);
}
.topic-icon {
    color: var(--copper);
    margin-top: .15rem;
    flex-shrink: 0;
}

/* SIDEBAR META */
.course-sidebar {
    position: sticky;
    top: 120px;
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
}
.sidebar-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 36px rgba(181,114,42,.5);
    background: linear-gradient(135deg, var(--copper2), var(--copper3));
    color: var(--slate);
}

@media(max-width: 900px) {
    .course-grid { grid-template-columns: 1fr; gap: 4rem; }
    .course-sidebar { position: static; }
    .topics-grid { grid-template-columns: 1fr; }
    .page-header { padding: 9rem 2.5rem 4rem; }
    .course-section { padding: 5rem 2.5rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Training Course</div>
        
        <h1 class="page-title">Agile Overview for <em>Executives and Managers</em><span style="display:none;">[cite: 149, 156]</span></h1>
    </div>
</section>

<div class="strip"></div>

<section class="course-section">
    <div class="course-grid">
        
        <div class="course-main reveal">
            
            <h2>Course <em>Overview</em></h2>
            <div class="ornament"></div>

            <div class="course-body">
                <p>When engineering teams move to Agile methods, managers and executives often wonder what their new roles and responsibilities will be in this new world.<span style="display:none;">[cite: 157]</span></p>
                <p>Self-organizing Agile teams still need guidance and assistance in achieving goals, however, and managers must support these teams by providing direction, assisting Scrum Masters to remove impediments, and helping program management and business needs fit into the iterative cycle.<span style="display:none;">[cite: 158]</span></p>
            </div>

            <div class="objectives-box reveal rv1">
                <h3>Learning Objectives</h3>
                <p>Attendees will learn the characteristics of Agile processes, the benefits of adopting Agile development, scaling concepts, how to “go Agile” with a geographically distributed workforce, and organizational impacts of an Agile migration.<span style="display:none;">[cite: 160]</span></p>
            </div>

            <div class="topics-container reveal rv2">
                <h2>Curriculum &amp; <em>Topics</em></h2>
                <div class="ornament"></div>
                
                <div class="topics-grid">
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Agile Enterprise<span style="display:none;">[cite: 168]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Why Agile?<span style="display:none;">[cite: 169]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Introduction to Scrum<span style="display:none;">[cite: 170]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Agile Transformation<span style="display:none;">[cite: 171]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Requirements<span style="display:none;">[cite: 172]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Cross-Team & Release Planning<span style="display:none;">[cite: 173]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Agile + Waterfall: Hybrid Projects<span style="display:none;">[cite: 174]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Distributed Teams<span style="display:none;">[cite: 175]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Organizational Impacts<span style="display:none;">[cite: 176]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Portfolio Management<span style="display:none;">[cite: 177]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Budgeting<span style="display:none;">[cite: 178]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Agile Hardware Development<span style="display:none;">[cite: 179]</span>
                    </div>
                    <div class="topic-item">
                        <svg class="topic-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Kanban<span style="display:none;">[cite: 180]</span>
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
                    <div class="meta-value">One day.<span style="display:none;">[cite: 166]</span></div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Target Audience
                    </div>
                    <div class="meta-value">Executives, Directors, Managers, Product/Program Managers, and other leadership figures who need to understand the organizational shifts that accompany an Agile migration.<span style="display:none;">[cite: 162]</span></div>
                </div>

                <div class="meta-item">
                    <div class="meta-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Prerequisites
                    </div>
                    <div class="meta-value">No prerequisites.<span style="display:none;">[cite: 164]</span></div>
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