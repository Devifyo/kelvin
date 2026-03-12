@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   PAGE HEADER
───────────────────────────────────────── */
.page-header {
    background: var(--slate);
    padding: 12rem 4.5rem 8rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%);
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
    justify-content: center;
    gap: .75rem;
    font-family: -apple-system, sans-serif;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--copper);
    margin-bottom: 1.5rem;
}
.kicker::before, .kicker::after { 
    content: ''; width: 30px; height: 1px; background: var(--copper); 
}
.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3rem, 6vw, 4.8rem);
    font-weight: 400;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 1.5rem;
}
.page-title em { font-style: italic; color: var(--copper3); }
.page-subtitle {
    font-family: -apple-system, sans-serif;
    font-size: 1.1rem;
    color: rgba(250,247,242,.7);
    max-width: 650px;
    line-height: 1.8;
    font-weight: 300;
    margin: 0 auto;
}

/* ─────────────────────────────────────────
   BLOG CONTROLS & SEARCH (UX IMPROVED)
───────────────────────────────────────── */
.blog-section {
    padding: 4rem 4.5rem 8rem;
    background: var(--ivory);
    min-height: 60vh;
}
.blog-container {
    max-width: 1180px;
    margin: 0 auto;
}

/* The Utility Bar */
.blog-controls {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 3.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--ivory3);
}
.controls-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 600;
    color: var(--slate);
    line-height: 1;
    margin: 0;
}

/* Expanding Search Bar */
.search-wrapper {
    position: relative;
}
.search-input {
    width: 280px; /* Compact initially */
    padding: .9rem 1.2rem .9rem 2.8rem;
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--slate);
    background: var(--white);
    border: 1px solid var(--ivory3);
    border-radius: 50px;
    outline: none;
    box-shadow: 0 4px 12px rgba(26,35,50,.03);
    transition: all .4s cubic-bezier(.4,0,.2,1);
}
.search-input::placeholder {
    color: var(--muted);
    font-weight: 400;
}
/* The Premium Micro-interaction: Expand on focus */
.search-input:focus {
    width: 380px; 
    border-color: var(--copper);
    box-shadow: 0 8px 24px rgba(181,114,42,.12);
}
.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    transition: color .3s ease;
    pointer-events: none;
}
.search-input:focus + .search-icon {
    color: var(--copper);
}

/* ─────────────────────────────────────────
   BLOG GRID
───────────────────────────────────────── */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
}

/* Article Cards */
.blog-card {
    display: flex;
    flex-direction: column;
    background: var(--white);
    border: 1px solid var(--ivory3);
    box-shadow: 0 10px 25px rgba(26,35,50,.03);
    transition: transform .4s cubic-bezier(.4,0,.2,1), box-shadow .4s;
    position: relative;
}
.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(26,35,50,.08);
    border-color: rgba(181,114,42,.2);
}

/* Image Card Variant */
.blog-card.has-image .img-wrap {
    width: 100%;
    height: 220px;
    overflow: hidden;
    border-bottom: 1px solid var(--ivory3);
}
.blog-card.has-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.blog-card:hover .img-wrap img {
    transform: scale(1.05);
}
.blog-card.has-image .blog-content {
    padding: 2rem;
}

/* No-Image Card Variant */
.blog-card.no-image {
    border-top: 3px solid var(--copper);
}
.blog-card.no-image .blog-content {
    padding: 3rem 2rem;
}

/* Typography Inside Cards */
.blog-content {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.article-meta {
    font-family: -apple-system, sans-serif;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .75rem;
}
.article-category {
    color: var(--copper);
}
.article-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--slate);
    line-height: 1.2;
    margin-bottom: 1rem;
    transition: color .3s;
}
.blog-card:hover .article-title { color: var(--copper); }

.article-excerpt {
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--body-text);
    line-height: 1.7;
    font-weight: 300;
    flex-grow: 1;
    margin-bottom: 2rem;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-family: -apple-system, sans-serif;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--slate);
    transition: color .3s;
    margin-top: auto;
    padding-top: 1.5rem;
    border-top: 1px solid var(--ivory3);
}
.read-more:hover { color: var(--copper); }
.read-more svg { transition: transform .3s; }
.read-more:hover svg { transform: translateX(4px); }

/* No Results Message */
.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 6rem 2rem;
    display: none;
    background: var(--white);
    border: 1px dashed var(--ivory3);
}
.no-results h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    color: var(--slate);
    margin-bottom: .5rem;
}
.no-results p {
    font-family: -apple-system, sans-serif;
    color: var(--muted);
}

@media(max-width: 1024px) {
    .blog-grid { grid-template-columns: repeat(2, 1fr); gap: 2rem; }
}
@media(max-width: 768px) {
    .page-header { padding: 10rem 2.5rem 5rem; }
    .blog-section { padding: 3rem 2.5rem 5rem; }
    
    /* Stack controls on mobile */
    .blog-controls { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
    .search-wrapper { width: 100%; }
    .search-input { width: 100%; }
    .search-input:focus { width: 100%; } /* Disable expansion on mobile */
    
    .blog-grid { grid-template-columns: 1fr; gap: 3rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker">Insights &amp; Articles</div>
        <h1 class="page-title">Agile <em>Insights</em><span style="display:none;"></span></h1>
        <p class="page-subtitle">Expert perspectives on the unique challenges, methodologies, and intersection of Agile software and hardware development.</p>
    </div>
</section>

<section class="blog-section">
    <div class="blog-container">

        <div class="blog-controls reveal rv1">
            <h2 class="controls-title">Explore Publications</h2>
            
            <div class="search-wrapper">
                <input type="text" id="blogSearch" class="search-input" placeholder="Search by topic or keyword...">
                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
        </div>

        <div class="blog-grid" id="blogGrid">

            <article class="blog-card has-image reveal rv2">
                <a href="{{ route('blog.show', 'how-hardware-and-software-engineers-differ') }}" class="img-wrap">
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=800&auto=format&fit=crop" alt="Hardware Engineering">
                </a>
                <div class="blog-content">
                    <div class="article-meta">
                        <span class="article-category">Engineering</span>
                        <span>&bull;</span>
                        March 18, 2026
                    </div>
                    <h3 class="article-title">How Hardware and Software Engineers Differ<span style="display:none;"></span></h3>
                    <div class="article-excerpt">
                        They have a number of common characteristics, but also a number of differences.<span style="display:none;"></span> Discover how their distinct workflows impact Agile adoption.
                    </div>
                    <a href="{{ route('blog.show', 'how-hardware-and-software-engineers-differ') }}" class="read-more">
                        Read Article
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7-7"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-card has-image reveal rv2">
                <a href="{{ route('blog.show', 'embedded-software') }}" class="img-wrap">
                    <img src="https://images.unsplash.com/photo-1555664424-778a1e5e1b48?q=80&w=800&auto=format&fit=crop" alt="Embedded Software">
                </a>
                <div class="blog-content">
                    <div class="article-meta">
                        <span class="article-category">Development</span>
                        <span>&bull;</span>
                        March 12, 2026
                    </div>
                    <h3 class="article-title">Embedded Software<span style="display:none;"></span></h3>
                    <div class="article-excerpt">
                        Embedded software is loaded onto circuit boards, for purposes as varied as controlling other low-level devices to providing programmatic interfaces.<span style="display:none;"></span>
                    </div>
                    <a href="{{ route('blog.show', 'embedded-software') }}" class="read-more">
                        Read Article
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7-7"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-card no-image reveal rv2">
                <div class="blog-content">
                    <div class="article-meta">
                        <span class="article-category">Integration</span>
                        <span>&bull;</span>
                        February 28, 2026
                    </div>
                    <h3 class="article-title">Integrated Product Development<span style="display:none;"></span></h3>
                    <div class="article-excerpt">
                        Integration of software and hardware elements in the same product can be done well or poorly.<span style="display:none;"></span> This post develops optimum strategies for integration and testing.<span style="display:none;"></span>
                    </div>
                    <a href="{{ route('blog.show', 'integrated-product-development') }}" class="read-more">
                        Read Article
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7-7"/></svg>
                    </a>
                </div>
            </article>

            <article class="blog-card no-image reveal rv2">
                <div class="blog-content">
                    <div class="article-meta">
                        <span class="article-category">Scrum</span>
                        <span>&bull;</span>
                        February 15, 2026
                    </div>
                    <h3 class="article-title">Swarming and Hardware<span style="display:none;"></span></h3>
                    <div class="article-excerpt">
                        Swarming is a value-optimization and risk-minimization strategy common for Scrum teams in software development.<span style="display:none;"></span> Although swarming has desirable characteristics, it is generally not feasible for teams that develop hardware products.<span style="display:none;"></span>
                    </div>
                    <a href="{{ route('blog.show', 'swarming-and-hardware') }}" class="read-more">
                        Read Article
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7-7"/></svg>
                    </a>
                </div>
            </article>

            <div id="noResults" class="no-results">
                <h3>No articles found.</h3>
                <p>Try adjusting your search terms.</p>
            </div>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
// 1. Scroll Animations
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

// 2. Client-Side Search Logic
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('blogSearch');
    const blogCards = document.querySelectorAll('.blog-card');
    const noResults = document.getElementById('noResults');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        let visibleCount = 0;

        blogCards.forEach(card => {
            // Grab the text from the title and excerpt
            const title = card.querySelector('.article-title').textContent.toLowerCase();
            const excerpt = card.querySelector('.article-excerpt').textContent.toLowerCase();
            const category = card.querySelector('.article-category').textContent.toLowerCase();
            
            // If search matches title, excerpt, or category
            if (title.includes(searchTerm) || excerpt.includes(searchTerm) || category.includes(searchTerm)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Toggle No Results Message
        if (visibleCount === 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    });
});
</script>
@endpush