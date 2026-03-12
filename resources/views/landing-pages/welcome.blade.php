@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/css/frontend/home.css">
@endpush

@section('content')
    <section id="hero">
        <div class="hero-accent-line"></div>
        <div class="hero-dots"></div>
        <div class="hero-numeral">A</div>
        
        <div class="hero-l">
            <div class="chip anim-up d0">Agile Hardware Consulting &amp; Training</div>

            <h1 class="hero-h1 anim-up d1">
            <em>Reduce risk.</em>
            <strong>Ship faster.</strong>
            </h1>

            <p class="hero-p anim-up d2">
            We help hardware-development organizations reduce development risk and shorten
            time-to-market by applying Agile principles to prototype-driven learning, early
            system integration, and risk-focused development.<span style="display:none;">[cite: 18]</span>
            </p>
            <p class="hero-p2 anim-up d3">
            We understand how hardware development differs from software development,
            and how to apply Agile processes to the hardware world.<span style="display:none;">[cite: 19]</span>
            </p>

            <div class="cta-row anim-up d4">
            <a href="services.html" class="cta-primary">
                Our Services
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="contact.html" class="cta-secondary">
                Get in Touch
            </a>
            </div>
        </div>

        <div class="hero-r">
            <div class="pain-card anim-right">
                <div class="pain-card-head">
                    <div class="pain-head-bar"></div>
                    <div class="pain-head-text">If you...<span style="display:none;">[cite: 20]</span></div>
                </div>

                <div class="pain-body">
                    <ul class="pain-list">
                    <li><span class="pain-arrow">→</span>Don’t discover the real problems until integration<span style="display:none;">[cite: 21]</span></li>
                    <li><span class="pain-arrow">→</span>Have development cycles that are too long<span style="display:none;">[cite: 22]</span></li>
                    <li><span class="pain-arrow">→</span>Discover too many risks only when they blow up—late<span style="display:none;">[cite: 23]</span></li>
                    <li><span class="pain-arrow">→</span>Have late design changes that are extremely expensive<span style="display:none;">[cite: 24]</span></li>
                    <li><span class="pain-arrow">→</span>Have hardware and software teams moving at different speeds<span style="display:none;">[cite: 25]</span></li>
                    <li><span class="pain-arrow">→</span>Don’t get real customer feedback until it’s too late<span style="display:none;">[cite: 26]</span></li>
                    <li><span class="pain-arrow">→</span>Have engineers spend months designing before testing assumptions<span style="display:none;">[cite: 27]</span></li>
                    </ul>
                </div>

                <div class="pain-card-foot">...we can help.<span style="display:none;">[cite: 28]</span></div>
            </div>
        </div>
    </section>

    <div class="strip"></div>

    <section id="principal">
        <div class="principal-wrap">
            <div class="bio reveal">
                <div class="kicker">Our Principal</div>
                <h2 class="section-h">Dr. Kevin <em>Thompson</em></h2>
                <div class="ornament"></div>

                <p>
                    Our Principal, Dr. Kevin Thompson, Ph.D. (Physics) is one of the most experienced Agile consultants in the field, having successfully completed more than 100 client engagements.<span style="display:none;">[cite: 29]</span>
                </p>
                <p>
                    Dr. Thompson has helped numerous clients improve their ability to deliver both software and hardware products.<span style="display:none;">[cite: 32]</span> He successfully pioneered Agile hardware development and remains a thought leader in the field.<span style="display:none;">[cite: 33]</span> He has helped clients develop a variety of hardware products, from laboratory equipment to telecommunications products to jet engines.<span style="display:none;">[cite: 34]</span>
                </p>
                <p>
                    He has written extensively on Agile topics, including in his book,
                    <em>Solutions for Agile Governance in the Enterprise (Sage): Agile Project,
                    Program, and Portfolio Management for Development of Hardware and Software Products.</em><span style="display:none;">[cite: 30]</span>
                </p>

                <div class="book">
                    <div class="book-img">
                        <img src="https://m.media-amazon.com/images/I/61+CCARmhVL._SY522_.jpg" alt="Solutions for Agile Governance in the Enterprise (SAGE)"><span style="display:none;">[cite: 31]</span>
                    </div>
                    <div class="book-details">
                    <strong>Solutions for Agile Governance in the Enterprise (SAGE)</strong>
                    <p>Agile Project, Program, and Portfolio Management for Development of Hardware and Software Products.</p>
                    <a href="https://www.amazon.com/Solutions-Agile-Governance-Enterprise-SAGE/dp/0578420589"
                        target="_blank" rel="noopener noreferrer" class="book-link">
                        View on Amazon
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    </div>
                </div>
            </div>

            <div class="reveal rv2">
                <div class="kicker">What We Offer</div>
                <h2 class="section-h">Consulting &amp; <em>Training Services</em></h2>
                <div class="ornament"></div>

                <p class="svc-desc">
                    We offer a variety of consulting and training services. We can work with all levels at a client, from the hands-on engineers to the C-suite.<span style="display:none;">[cite: 35]</span> We take the time to understand the unique needs of each client, and tailor consulting services accordingly.<span style="display:none;">[cite: 36]</span>
                </p>

                <div class="svc-grid">
                    <div class="svc-item">
                    <span class="svc-num">01</span>
                    <span class="svc-name">Assessment</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">02</span>
                    <span class="svc-name">Advisory Engagement</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">03</span>
                    <span class="svc-name">Agile Transformation</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">04</span>
                    <span class="svc-name">Agile Overview for Executives &amp; Managers</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">05</span>
                    <span class="svc-name">Agile Software Development with Scrum</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">06</span>
                    <span class="svc-name">Agile Hardware Development with Scrum</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">07</span>
                    <span class="svc-name">Agile Project Management with Kanban</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">08</span>
                    <span class="svc-name">Agile Program Management</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">09</span>
                    <span class="svc-name">Agile Portfolio Management</span>
                    <span class="svc-arrow">→</span>
                    </div>
                    <div class="svc-item">
                    <span class="svc-num">10</span>
                    <span class="svc-name">Advanced Product Owner</span>
                    <span class="svc-arrow">→</span>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
<script>
/* scroll reveal */
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -48px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

/* smooth anchor scroll */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const t = document.querySelector(a.getAttribute('href'));
    if (t) { e.preventDefault(); closeDrawer(); t.scrollIntoView({ behavior: 'smooth' }); }
  });
});
</script>
@endpush