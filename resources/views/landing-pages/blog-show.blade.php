@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   UX ENHANCEMENTS
───────────────────────────────────────── */
::selection {
    background: var(--copper2);
    color: var(--white);
}

.reading-progress-container {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: transparent;
    z-index: 1000;
}
.reading-progress-bar {
    height: 100%;
    background: var(--copper);
    width: 0%;
    transition: width 0.1s ease-out;
}

/* ─────────────────────────────────────────
   ARTICLE HEADER
───────────────────────────────────────── */
.article-header {
    background: var(--slate);
    padding: 12rem 2rem 8rem;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.article-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%);
    z-index: 0;
}
.header-content {
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.breadcrumb {
    font-family: -apple-system, sans-serif;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--copper);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: .75rem;
}
.breadcrumb a { color: rgba(250,247,242,.6); transition: color .3s; }
.breadcrumb a:hover { color: var(--white); }
.breadcrumb span { color: rgba(250,247,242,.2); }

.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.8rem, 5vw, 4.5rem);
    font-weight: 300;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 2rem;
    letter-spacing: -0.01em;
}

/* Enhanced Author & Meta Block */
.article-meta-wrapper {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    background: rgba(250,247,242,.03);
    border: 1px solid rgba(250,247,242,.1);
    padding: 1rem 2rem;
    border-radius: 50px;
    backdrop-filter: blur(10px);
}
.meta-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    border: 2px solid var(--copper);
    object-fit: cover;
}
.meta-info {
    text-align: left;
    font-family: -apple-system, sans-serif;
    font-size: .85rem;
    color: rgba(250,247,242,.6);
    line-height: 1.4;
}
.meta-info strong {
    display: block;
    color: var(--white);
    font-size: .95rem;
    font-weight: 600;
}
.meta-details {
    display: flex;
    align-items: center;
    gap: .5rem;
    margin-top: .15rem;
}
.meta-details .dot { color: var(--copper); }

/* ─────────────────────────────────────────
   ARTICLE BODY & LAYOUT
───────────────────────────────────────── */
.article-section {
    padding: 0 2rem 7rem;
    background: var(--ivory);
    position: relative;
}

/* Main Content Card */
.article-wrap {
    max-width: 860px;
    margin: 0 auto;
    background: var(--white);
    border: 1px solid var(--ivory3);
    box-shadow: 0 20px 40px rgba(26,35,50,.04);
    position: relative;
    top: -4rem; 
    overflow: hidden; /* Keeps edge-to-edge image contained */
}
.article-wrap::before {
    content: ''; position: absolute; left: 0; right: 0; top: 0;
    height: 4px; background: linear-gradient(90deg, var(--copper), var(--copper2));
    z-index: 10;
}

/* Edge-to-Edge Cover Image */
.article-cover {
    width: 100%;
    height: 400px; /* Fixed height for consistent layout */
    object-fit: cover;
    display: block;
    border-bottom: 1px solid var(--ivory3);
}

/* Inner Text Container */
.article-body {
    padding: 4rem 5rem;
}

/* Refined PDF Card */
.pdf-attachment {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--ivory);
    border: 1px solid var(--ivory3);
    padding: 1.5rem 2rem;
    margin-bottom: 4rem;
    border-radius: 4px;
}
.pdf-info {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.pdf-icon {
    width: 48px; height: 48px;
    background: var(--white);
    border: 1px solid var(--ivory3);
    color: var(--copper);
    display: flex; align-items: center; justify-content: center;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(26,35,50,.05);
}
.pdf-text strong {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--slate);
    display: block;
    margin-bottom: .2rem;
}
.pdf-text span {
    font-family: -apple-system, sans-serif;
    font-size: .8rem;
    color: var(--muted);
}
.btn-download {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-family: -apple-system, sans-serif;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--slate);
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: .8rem 1.5rem;
    transition: all .3s;
    box-shadow: 0 2px 8px rgba(26,35,50,.03);
}
.btn-download:hover { 
    background: var(--copper); 
    color: var(--white);
    border-color: var(--copper);
}

/* Typography Polish */
.rich-content {
    font-family: 'Palatino Linotype', 'Book Antiqua', Palatino, Georgia, serif;
    font-size: 1.2rem;
    color: var(--charcoal);
    line-height: 1.9;
}
.rich-content > p:first-of-type {
    font-size: 1.35rem;
    line-height: 1.8;
    color: var(--slate);
    margin-bottom: 3rem;
}
.rich-content p { margin-bottom: 2rem; }
.rich-content h2 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--slate);
    margin: 4rem 0 1.5rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--ivory3);
}
.rich-content h3 {
    font-family: -apple-system, sans-serif;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--slate);
    margin: 2.5rem 0 1rem;
}
.rich-content ul {
    margin-bottom: 2.5rem;
    padding-left: 1.5rem;
}
.rich-content ul li {
    margin-bottom: 1rem;
    padding-left: .5rem;
}
.rich-content ul li strong { color: var(--slate); font-family: -apple-system, sans-serif; }
.rich-content ul li::marker { color: var(--copper); }
.rich-content blockquote {
    margin: 4rem -2rem;
    padding: 2.5rem 3rem;
    font-size: 1.6rem;
    font-style: italic;
    line-height: 1.4;
    color: var(--copper);
    text-align: center;
    border-top: 1px solid rgba(181,114,42,.2);
    border-bottom: 1px solid rgba(181,114,42,.2);
    background: var(--ivory);
}

/* ─────────────────────────────────────────
   ARTICLE FOOTER & BOTTOM ACTIONS
───────────────────────────────────────── */
.article-footer {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1px solid var(--ivory3);
}

/* Tags & Sharing row */
.article-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 3rem;
}
.article-tags {
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
}
.tag {
    font-family: -apple-system, sans-serif;
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--muted);
    background: var(--ivory2);
    padding: .4rem 1rem;
    border-radius: 4px;
    transition: background .3s;
}
.tag:hover { background: var(--ivory3); color: var(--slate); }

.share-bottom {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.share-bottom span {
    font-family: -apple-system, sans-serif;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--muted);
}
.share-btn {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--white);
    border: 1px solid var(--ivory3);
    color: var(--slate);
    display: flex; align-items: center; justify-content: center;
    transition: all .3s;
}
.share-btn:hover {
    background: var(--copper);
    color: var(--white);
    border-color: var(--copper);
}

/* Author Box */
.author-bio-box {
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    background: var(--ivory);
    padding: 2rem;
    border-radius: 4px;
    border: 1px solid var(--ivory3);
}
.author-bio-box img {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 2px solid var(--copper);
}
.author-bio-text h4 {
    font-family: -apple-system, sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--slate);
    margin-bottom: .5rem;
}
.author-bio-text p {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--body-text);
    line-height: 1.6;
    margin: 0;
}

@media(max-width: 900px) {
    .article-wrap { top: -2rem; }
    .page-header { padding: 9rem 1.5rem 5rem; }
    .article-cover { height: 300px; }
    .article-body { padding: 3rem 2rem; }
    .pdf-attachment { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
    .rich-content blockquote { margin: 3rem -2rem; padding: 2rem; font-size: 1.3rem; }
    .article-actions { flex-direction: column; align-items: flex-start; gap: 2rem; }
    .author-bio-box { flex-direction: column; }
}
</style>
@endpush

@section('content')

@php
    $hasCoverImage = true; 
    $coverImageUrl = "https://images.unsplash.com/photo-1555664424-778a1e5e1b48?q=80&w=2000&auto=format&fit=crop";
@endphp

<div class="reading-progress-container">
    <div class="reading-progress-bar" id="reading-progress"></div>
</div>

<section class="article-header">
    <div class="header-content reveal">
        
        <div class="breadcrumb">
            <a href="{{ route('blog') }}">Insights</a> <span>/</span> Best Practices
        </div>
        
        <h1 class="page-title">Embedded Software<span style="display:none;"></span></h1>
        
        <div class="article-meta-wrapper">
            <img src="https://via.placeholder.com/100x100/243345/faf7f2?text=KT" alt="Dr. Kevin Thompson" class="meta-avatar">
            <div class="meta-info">
                <strong>Dr. Kevin Thompson</strong>
                <div class="meta-details">
                    <span>March 12, 2026</span>
                    <span class="dot">&bull;</span>
                    <span>5 min read</span>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="article-section">
    <article class="article-wrap reveal rv1">
        
        @if($hasCoverImage)
            <img src="{{ $coverImageUrl }}" alt="Embedded Software Cover" class="article-cover">
        @endif

        <div class="article-body">
            
            <div class="pdf-attachment">
                <div class="pdf-info">
                    <div class="pdf-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <div class="pdf-text">
                        <strong>Original Formatting Available</strong>
                        <span>EmbeddedSoftware.pdf &bull; 1.2 MB</span>
                    </div>
                </div>
                <a href="#" class="btn-download">
                    Download File
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                </a>
            </div>

            <div class="rich-content">
                <p>
                    Embedded software is loaded onto circuit boards, for purposes as varied as controlling other low-level devices to providing programmatic interfaces for application developers.<span style="display:none;"></span> This post discusses some issues relating to developing embedded software.<span style="display:none;"></span>
                </p>

                <p>
                    The intersection of Agile methodologies and embedded systems represents one of the most complex frontiers in product development. Unlike traditional application software that runs on standard operating systems with abstracted hardware resources, embedded software must operate within strict, fixed constraints.
                </p>

                <h2>The Hardware-Software Interface</h2>
                <p>
                    The development lifecycle is intimately tied to the physical board it runs on, creating a unique set of challenges that Agile frameworks must adapt to handle effectively. Teams cannot simply push code to a cloud server and observe the results; they must flash firmware, interact with physical pins, and account for memory limitations that web developers rarely consider.
                </p>

                <blockquote>
                    "In embedded systems, the code is only as good as the physical hardware it controls. The two must be developed and tested in perfect synchronization."
                </blockquote>

                <h3>Common Challenges in Embedded Agile</h3>
                <p>When attempting to run Scrum on embedded software projects, organizations frequently encounter significant friction points:</p>
                <ul>
                    <li><strong>Wait States & Dependencies:</strong> Software engineers often find themselves unable to test their logic until the prototype board arrives from the manufacturer, leading to artificial bottlenecks in the Sprint.</li>
                    <li><strong>Firmware Deployment:</strong> Flashing new logic onto physical devices takes significantly more time than a standard software deployment, impacting the team's velocity and testing cadence.</li>
                    <li><strong>Irreversible Errors:</strong> While a cloud application can be patched in minutes, a critical bug in embedded software might require a physical product recall if shipped to the end consumer.</li>
                </ul>

                <h2>Adapting the Framework</h2>
                <p>
                    To succeed, embedded teams must utilize simulation environments, mock hardware interfaces, and early prototype boards to ensure continuous integration. The Definition of Done must be explicitly expanded to include physical testing environments, not just successful code compilation.
                </p>
            </div>

            <div class="article-footer">
                
                <div class="article-actions">
                    <div class="article-tags">
                        <a href="#" class="tag">Embedded Systems</a>
                        <a href="#" class="tag">Scrum</a>
                        <a href="#" class="tag">Hardware Development</a>
                    </div>

                    <div class="share-bottom">
                        <span>Share:</span>
                        <a href="#" class="share-btn" aria-label="Share on LinkedIn" title="LinkedIn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        <a href="#" class="share-btn" aria-label="Share on Twitter" title="Twitter">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        </a>
                        <a href="mailto:?subject=Embedded Software&body=Check this out." class="share-btn" aria-label="Share via Email" title="Email">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </a>
                    </div>
                </div>

                <div class="author-bio-box">
                    <img src="https://via.placeholder.com/150x150/243345/faf7f2?text=KT" alt="Dr. Kevin Thompson">
                    <div class="author-bio-text">
                        <h4>Dr. Kevin Thompson, Ph.D.</h4>
                        <p>Principal Consultant specializing in Agile hardware development. Dr. Thompson has successfully guided over 100 enterprise transformations, bridging the gap between hardware engineering and Agile software methodologies.</p>
                    </div>
                </div>

            </div>
            
        </div>
    </article>
</section>

@endsection

@push('scripts')
<script>
// Scroll Animation Observer
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });
document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

// Reading Progress Bar Logic
window.addEventListener('scroll', () => {
    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    document.getElementById('reading-progress').style.width = scrolled + "%";
});
</script>
@endpush