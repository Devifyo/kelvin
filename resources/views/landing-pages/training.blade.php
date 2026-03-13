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
    text-align: center;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.6) 0%, transparent 70%);
    z-index: 0;
}
.header-content {
    max-width: 900px;
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
.kicker::before, .kicker::after { 
    content: ''; width: 24px; height: 1px; background: var(--copper2); 
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
.short-desc {
    font-family: -apple-system, sans-serif;
    font-size: 1.1rem;
    color: rgba(250,247,242,.7);
    line-height: 1.8;
    font-weight: 300;
    max-width: 700px;
    margin: 0 auto;
}

/* ─────────────────────────────────────────
   TRAINING INDEX LAYOUT
───────────────────────────────────────── */
.training-wrapper {
    background: var(--ivory);
    padding: 7rem 4.5rem;
}
.content-container {
    max-width: 1000px; /* Slightly narrower for reading focus */
    margin: 0 auto;
}

/* Course Cards */
.course-card {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 3.5rem;
    margin-bottom: 3rem;
    box-shadow: 0 10px 30px rgba(26,35,50,.03);
    transition: transform .4s ease, box-shadow .4s ease, border-color .4s ease;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    position: relative;
}
.course-card::before {
    content: ''; position: absolute; top: 0; left: 0; width: 4px; bottom: 0;
    background: var(--copper);
    transform: scaleY(0); transform-origin: top;
    transition: transform .4s ease;
}
.course-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
    border-color: rgba(181,114,42,.2);
}
.course-card:hover::before {
    transform: scaleY(1);
}

.course-card h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 600;
    color: var(--slate);
    line-height: 1.2;
    margin: 0;
}
.course-card p {
    font-family: 'Palatino Linotype', serif;
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--body-text);
    margin: 0;
}

/* Card Meta (Bottom Row) */
.card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 2rem;
    border-top: 1px solid var(--ivory3);
}
.meta-info {
    display: flex;
    gap: 2rem;
}
.meta-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-family: -apple-system, sans-serif;
    font-size: .8rem;
    font-weight: 600;
    color: var(--muted);
    letter-spacing: .05em;
    text-transform: uppercase;
}
.meta-item svg { color: var(--copper); }

/* Buttons */
.btn-outline {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .8rem 1.8rem;
    background: transparent;
    color: var(--slate);
    font-family: -apple-system, sans-serif;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    border: 1.5px solid var(--ivory3);
    transition: all .35s;
    text-decoration: none;
}
.btn-outline:hover {
    border-color: var(--copper);
    color: var(--copper);
    background: var(--ivory);
}

@media(max-width: 768px) {
    .page-header { padding: 9rem 2.5rem 4rem; }
    .training-wrapper { padding: 5rem 2.5rem; }
    .course-card { padding: 2.5rem 2rem; }
    .card-footer { flex-direction: column; align-items: flex-start; gap: 2rem; }
    .meta-info { flex-direction: column; gap: 1rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Curriculum</div>
        <h1 class="page-title">Training <em>Classes</em></h1>
        <p class="short-desc">We provide comprehensive on-site training to empower your teams, from hands-on engineers to the C-suite, with the Agile skills needed to ship faster and reduce risk.</p>
    </div>
</section>

<div class="strip"></div>

<section class="training-wrapper">
    <div class="content-container">
        
        <div class="course-card reveal rv1">
            <h2>Agile Overview for Executives and Managers</h2>
            <p>Understand the organizational shifts that accompany an Agile migration. Learn the characteristics of Agile processes, scaling concepts, and how to support self-organizing teams while aligning business needs with iterative cycles.</p>
            
            <div class="card-footer">
                <div class="meta-info">
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        1 Day
                    </div>
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Leadership & Management
                    </div>
                </div>
                <a href="{{ route('training', ['slug' => 'hello']) }}" class="btn-outline">View Details →</a>
            </div>
        </div>

        <div class="course-card reveal rv1">
            <h2>Agile Software Development with Scrum</h2>
            <p>Master the practical details of the Scrum process framework for software products. Experience hands-on skills required for a Scrum Team to plan and implement work, from progressive elaboration to tracking Sprint progress.</p>
            
            <div class="card-footer">
                <div class="meta-info">
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        2 Days
                    </div>
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Software Teams
                    </div>
                </div>
                <a href="/training/software-scrum" class="btn-outline">View Details →</a>
            </div>
        </div>

        <div class="course-card reveal rv1">
            <h2>Agile Hardware Development with Scrum</h2>
            <p>Learn how to apply Agile processes to the physical world. This class trains attendees in the practical details of Scrum specifically tailored for the complexities of hardware product development.</p>
            
            <div class="card-footer">
                <div class="meta-info">
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        2 Days
                    </div>
                    <div class="meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Hardware Engineers & Teams
                    </div>
                </div>
                <a href="/training/hardware-scrum" class="btn-outline">View Details →</a>
            </div>
        </div>

        <div class="course-card reveal rv1">
            <h2>Agile Project Management with Kanban</h2>
            <p>Learn the principles of flow, managing work-in-progress limits, and optimizing delivery schedules using the Kanban methodology.</p>
            <div class="card-footer">
                <a href="#" class="btn-outline">View Details →</a>
            </div>
        </div>

        <div class="course-card reveal rv1">
            <h2>Advanced Product Owner</h2>
            <p>Deep-dive into backlog grooming, stakeholder management, and value maximization for experienced Product Owners looking to take their skills to the next level.</p>
            <div class="card-footer">
                <a href="#" class="btn-outline">View Details →</a>
            </div>
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