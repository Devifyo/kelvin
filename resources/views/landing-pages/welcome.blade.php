@extends('layouts.app')

@push('styles')
<style>
/* ─────────────────────────────────────────
   HERO
───────────────────────────────────────── */
#hero{
  min-height:100vh;
  background:var(--slate);
  display:grid;grid-template-columns:52% 48%;
  position:relative;overflow:hidden;
}
.hero-l{
  padding:9rem 3.5rem 6rem 4.5rem;
  display:flex;flex-direction:column;justify-content:center;
  position:relative;z-index:1;
}
.hero-r{
  display:flex;align-items:center;justify-content:center;
  padding:9rem 4rem 6rem 3rem;
  position:relative;z-index:1;
}
#hero::before{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 100% 80% at 0% 100%, rgba(47,66,89,.6) 0%,transparent 60%),
    radial-gradient(ellipse 70% 70% at 100% 0%, rgba(181,114,42,.05) 0%,transparent 55%);
  z-index:0;
}
.hero-accent-line{
  position:absolute;
  top:0;right:35%;bottom:0;width:1px;
  background:linear-gradient(to bottom,transparent,rgba(181,114,42,.18) 20%,rgba(181,114,42,.18) 80%,transparent);
  z-index:0;
}
.hero-dots{
  position:absolute;right:0;top:0;width:50%;height:100%;
  background-image:radial-gradient(circle,rgba(181,114,42,.12) 1px,transparent 1px);
  background-size:32px 32px;
  mask-image:radial-gradient(ellipse 80% 80% at 70% 50%,black 20%,transparent 80%);
  z-index:0;
}
.hero-numeral{
  position:absolute;bottom:-4rem;left:-.5rem;
  font-size:clamp(22rem,35vw,42rem);
  font-weight:900;
  color:rgba(181,114,42,.035);
  line-height:1;letter-spacing:-.05em;
  user-select:none;z-index:0;pointer-events:none;
}
.chip{
  display:inline-flex;align-items:center;gap:.75rem;
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.65rem;font-weight:700;letter-spacing:.32em;text-transform:uppercase;
  color:var(--copper2);margin-bottom:2rem;
}
.chip::before{content:'';width:28px;height:1px;background:var(--copper2)}
.hero-h1{
  font-size:clamp(3rem,5.8vw,5.6rem);
  font-weight:400;line-height:1.04;color:var(--white);
  margin-bottom:2rem;letter-spacing:-.01em;
}
.hero-h1 em{
  font-style:italic;
  color:var(--copper3);
  display:block;
}
.hero-h1 strong{
  font-weight:800;display:block;
  background:linear-gradient(135deg,var(--white) 40%,var(--copper4));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-clip:text;
}
.hero-p{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:1.02rem;color:rgba(250,247,242,.55);
  line-height:1.88;font-weight:300;max-width:460px;margin-bottom:.9rem;
}
.hero-p2{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.92rem;color:rgba(250,247,242,.4);
  line-height:1.8;font-weight:300;max-width:460px;margin-bottom:2.75rem;
}
.cta-row{display:flex;gap:1rem;flex-wrap:wrap;align-items:center}
.cta-primary{
  display:inline-flex;align-items:center;gap:.6rem;
  padding:.95rem 2.2rem;
  background:linear-gradient(135deg,var(--copper),var(--copper2));
  color:var(--white);
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;
  border:none;cursor:pointer;transition:all .35s;
  box-shadow:0 4px 24px rgba(181,114,42,.35);
}
.cta-primary:hover{
  transform:translateY(-2px);
  box-shadow:0 8px 36px rgba(181,114,42,.5);
  background:linear-gradient(135deg,var(--copper2),var(--copper3));
  color:var(--slate);
}
.cta-secondary{
  display:inline-flex;align-items:center;gap:.5rem;
  padding:.95rem 2.2rem;
  background:transparent;
  color:rgba(250,247,242,.65);
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.75rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;
  border:1.5px solid rgba(250,247,242,.18);cursor:pointer;transition:all .35s;
}
.cta-secondary:hover{border-color:var(--copper2);color:var(--copper3)}

/* ── pain card ── */
.pain-card{
  background:rgba(36,51,69,.6);
  border:1px solid rgba(181,114,42,.18);
  padding:0;
  backdrop-filter:blur(16px);
  position:relative;
  width:100%;max-width:420px;
}
.pain-card-head{
  padding:1.6rem 2rem 1.4rem;
  border-bottom:1px solid rgba(181,114,42,.12);
  display:flex;align-items:center;gap:.85rem;
}
.pain-head-bar{width:3px;height:20px;background:var(--copper);flex-shrink:0}
.pain-head-text{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.68rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;
  color:var(--copper2);
}
.pain-body{padding:1.25rem 2rem 1.75rem}
.pain-list li{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.86rem;color:rgba(250,247,242,.72);
  padding:.55rem 0;border-bottom:1px solid rgba(181,114,42,.06);
  display:flex;gap:.8rem;align-items:flex-start;line-height:1.5;
}
.pain-list li:last-child{border-bottom:none}
.pain-arrow{
  color:var(--copper2);flex-shrink:0;
  font-size:.75rem;margin-top:.18rem;
  font-style:normal;
}
.pain-card-foot{
  margin:0;padding:1.25rem 2rem 1.5rem;
  border-top:1px solid rgba(181,114,42,.12);
  font-style:italic;font-size:1.08rem;
  color:var(--copper3);
  display:flex;align-items:center;gap:.65rem;
}
.pain-card-foot::before{content:'';width:20px;height:1px;background:var(--copper2)}

/* ─────────────────────────────────────────
   DIVIDER STRIP
───────────────────────────────────────── */
.strip{
  background:linear-gradient(90deg,var(--copper),var(--copper2) 50%,var(--copper));
  height:2px;
}

/* ─────────────────────────────────────────
   PRINCIPAL SECTION
───────────────────────────────────────── */
#principal{
  background:var(--white);
  padding:8rem 4.5rem;
}
.principal-wrap{
  max-width:1180px;margin:0 auto;
  display:grid;grid-template-columns:1fr 1.1fr;
  gap:7rem;align-items:start;
}

/* labels */
.kicker{
  display:inline-flex;align-items:center;gap:.7rem;
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.65rem;font-weight:700;letter-spacing:.3em;text-transform:uppercase;
  color:var(--copper);margin-bottom:1.1rem;
}
.kicker::before{content:'';width:24px;height:1px;background:var(--copper)}

.section-h{
  font-size:clamp(2rem,3.2vw,2.85rem);
  font-weight:400;line-height:1.1;color:var(--slate);margin-bottom:1.1rem;
}
.section-h em{font-style:italic;color:var(--copper)}

.ornament{
  width:40px;height:1.5px;
  background:linear-gradient(90deg,var(--copper),transparent);
  margin:1.5rem 0;
}

/* bio text */
.bio p{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.96rem;color:var(--body-text);line-height:1.92;
  font-weight:300;margin-bottom:1.15rem;
}

/* book card */
.book{
  display:flex;gap:1.5rem;align-items:flex-start;
  background:linear-gradient(135deg,rgba(181,114,42,.08),rgba(181,114,42,.02));
  border:1px solid rgba(181,114,42,.18);
  padding:1.65rem;margin-top:2.25rem;
  position:relative;
  transition:box-shadow .3s;
}
.book:hover{box-shadow:var(--hover-shadow)}
.book::before{
  content:'';position:absolute;top:0;left:0;
  width:100%;height:2px;
  background:linear-gradient(90deg,var(--copper),transparent);
}

/* New Book Image styles */
.book-img {
  width: 75px;
  flex-shrink: 0;
  border: 1px solid rgba(181,114,42,.3);
  box-shadow: 0 4px 12px rgba(26,35,50,.15);
  background: var(--slate2);
  overflow: hidden;
  display: flex;
}
.book-img img {
  width: 100%; 
  height: auto; 
  display: block;
  transition: transform .4s ease;
}
.book:hover .book-img img {
  transform: scale(1.05);
}

.book-details strong{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.88rem;color:var(--slate);display:block;
  margin-bottom:.35rem;line-height:1.45;font-weight:700;
}
.book-details p{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.78rem;color:var(--muted);line-height:1.6;margin-bottom:.75rem;
}
.book-link{
  display:inline-flex;align-items:center;gap:.4rem;
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.68rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
  color:var(--copper);transition:color .3s;
}
.book-link:hover{color:var(--slate)}
.book-link svg{transition:transform .3s}
.book-link:hover svg{transform:translateX(3px)}

/* ── services column ── */
.svc-desc{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.96rem;color:var(--body-text);line-height:1.88;
  font-weight:300;margin-bottom:2.25rem;
}

.svc-grid{display:flex;flex-direction:column;gap:.5rem}
.svc-item{
  display:flex;align-items:center;gap:1rem;
  padding:.8rem 1.1rem;
  border:1px solid var(--ivory3);
  background:var(--ivory);
  transition:all .32s cubic-bezier(.4,0,.2,1);
  cursor:default;position:relative;overflow:hidden;
}
.svc-item::before{
  content:'';position:absolute;left:0;top:0;bottom:0;
  width:2px;background:var(--copper);
  transform:scaleY(0);transform-origin:bottom;
  transition:transform .32s cubic-bezier(.4,0,.2,1);
}
.svc-item:hover{
  background:var(--white);
  border-color:rgba(181,114,42,.25);
  transform:translateX(5px);
  box-shadow:var(--card-shadow);
}
.svc-item:hover::before{transform:scaleY(1)}
.svc-num{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.62rem;color:rgba(181,114,42,.4);
  font-weight:700;width:18px;flex-shrink:0;text-align:right;
}
.svc-name{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.88rem;color:var(--charcoal);font-weight:500;flex:1;
}
.svc-arrow{
  font-size:.75rem;color:var(--copper2);
  opacity:0;transform:translateX(-4px);
  transition:opacity .3s,transform .3s;
}
.svc-item:hover .svc-arrow{opacity:1;transform:none}

/* ─────────────────────────────────────────
   ANIMATIONS
───────────────────────────────────────── */
/* hero staggered load */
@keyframes fadeUp{
  from{opacity:0;transform:translateY(36px)}
  to{opacity:1;transform:none}
}
@keyframes fadeRight{
  from{opacity:0;transform:translateX(48px)}
  to{opacity:1;transform:none}
}
@keyframes fadeIn{
  from{opacity:0}to{opacity:1}
}

.anim-up{
  opacity:0;
  animation:fadeUp .85s cubic-bezier(.25,.46,.45,.94) both;
}
.anim-right{
  opacity:0;
  animation:fadeRight 1s cubic-bezier(.25,.46,.45,.94) .55s both;
}
.d0{animation-delay:.1s}
.d1{animation-delay:.25s}
.d2{animation-delay:.42s}
.d3{animation-delay:.58s}
.d4{animation-delay:.74s}
.d5{animation-delay:.9s}

/* scroll reveal */
.reveal{
  opacity:0;transform:translateY(28px);
  transition:opacity .8s cubic-bezier(.25,.46,.45,.94),
             transform .8s cubic-bezier(.25,.46,.45,.94);
}
.reveal.in{opacity:1;transform:none}
.rv1{transition-delay:.12s}
.rv2{transition-delay:.24s}

/* ─────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────── */
@media(max-width:1100px){
  #hero{grid-template-columns:1fr;min-height:auto}
  .hero-l{padding:9rem 2.5rem 3rem}
  .hero-r{padding:0 2.5rem 6rem;justify-content:flex-start}
  .hero-p,.hero-p2{max-width:100%}
  .pain-card{max-width:100%}
  #principal{padding:6rem 2.5rem}
  .principal-wrap{grid-template-columns:1fr;gap:4rem}
}
@media(max-width:700px){
  #hero{grid-template-columns:1fr}
  .hero-l{padding:8rem 1.5rem 3rem}
  .hero-r{padding:0 1.5rem 5rem}
  #principal{padding:5rem 1.5rem}
  .cta-row{flex-direction:column;align-items:flex-start}
  .cta-primary,.cta-secondary{width:100%;justify-content:center}
  .hero-numeral{display:none}
}
@media(max-width:480px){
  .hero-h1{font-size:clamp(2.4rem,10vw,3.5rem)}
}
</style>
@endpush

@section('content')

@if(request()->has('visible'))
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

@else
    <h1> homepage </h1>
@endif

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