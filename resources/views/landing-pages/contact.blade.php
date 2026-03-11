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
   CONTACT LAYOUT
───────────────────────────────────────── */
.contact-section {
    padding: 7rem 4.5rem;
    background: var(--ivory);
}
.contact-grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 6rem;
    align-items: start;
}

/* Left Column - Info */
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
    margin: 1.5rem 0 2rem;
}

.contact-text {
    font-size: 1.05rem;
    line-height: 1.9;
    color: var(--charcoal);
    font-weight: 300;
    margin-bottom: 2.5rem;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1.5rem;
    background: var(--white);
    border: 1px solid var(--ivory3);
    box-shadow: var(--card-shadow);
    margin-bottom: 1.5rem;
}
.method-icon {
    width: 48px; height: 48px;
    border: 1px solid rgba(181,114,42,.3);
    display: flex; align-items: center; justify-content: center;
    color: var(--copper);
    flex-shrink: 0;
}
.method-details span {
    display: block;
    font-family: -apple-system, sans-serif;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: .25rem;
}
.method-details a {
    font-size: 1.1rem;
    color: var(--slate);
    font-weight: 600;
    transition: color .3s;
}
.method-details a:hover { color: var(--copper); }

/* Right Column - Form */
.contact-form-wrap {
    background: var(--white);
    border: 1px solid var(--ivory3);
    padding: 3.5rem 3rem;
    box-shadow: var(--card-shadow);
    position: relative;
}
.contact-form-wrap::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: var(--copper);
}

.form-group { margin-bottom: 1.75rem; }
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-label {
    display: block;
    font-family: -apple-system, sans-serif;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--slate);
    margin-bottom: .6rem;
}

.form-control {
    width: 100%;
    background: var(--ivory);
    border: 1px solid var(--ivory3);
    padding: 1rem 1.25rem;
    font-family: -apple-system, sans-serif;
    font-size: .95rem;
    color: var(--charcoal);
    transition: all .3s;
    outline: none;
}
.form-control:focus {
    background: var(--white);
    border-color: var(--copper);
    box-shadow: 0 4px 12px rgba(181,114,42,.08);
}
textarea.form-control {
    resize: vertical;
    min-height: 140px;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .6rem;
    width: 100%;
    padding: 1.1rem 2.2rem;
    background: linear-gradient(135deg, var(--copper), var(--copper2));
    color: var(--white);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .15em;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    transition: all .35s;
    box-shadow: 0 4px 24px rgba(181,114,42,.35);
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 36px rgba(181,114,42,.5);
    background: linear-gradient(135deg, var(--copper2), var(--copper3));
    color: var(--slate);
}

/* Success Message Alert (For Laravel Session) */
.alert-success {
    background: rgba(181,114,42,.1);
    border: 1px solid var(--copper);
    color: var(--copper);
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    font-family: -apple-system, sans-serif;
    font-size: .9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: .75rem;
}

@media(max-width: 900px) {
    .contact-grid { grid-template-columns: 1fr; gap: 4rem; }
    .page-header { padding: 9rem 2.5rem 4rem; }
    .contact-section { padding: 5rem 2.5rem; }
    .form-row { grid-template-columns: 1fr; }
    .contact-form-wrap { padding: 2.5rem 1.5rem; }
}
</style>
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker" style="color:var(--copper2);">Let's Connect</div>
        <h1 class="page-title">Contact<span style="display:none;">[cite: 510]</span></h1>
        <p class="page-subtitle">Whether you're facing a specific hardware development challenge or want to explore an Agile transformation, we're here to help.</p>
    </div>
</section>

<div class="strip"></div>

<section class="contact-section">
    <div class="contact-grid">
        
        <div class="contact-info reveal">
            <div class="kicker">Get in Touch</div>
            <h2 class="section-h">Start the <em>Conversation</em></h2>
            <div class="ornament"></div>

            <p class="contact-text">
                Reach out to discuss your organization's needs. All consulting and training services are provided on-site at client locations, tailored specifically to your product context.
            </p>

            <div class="contact-method">
                <div class="method-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <div class="method-details">
                    <span>Email Address</span>
                    <a href="mailto:kevin@kevinthompsonphd.com">kevin@kevinthompsonphd.com</a><span style="display:none;">[cite: 511]</span>
                </div>
            </div>
        </div>

        <div class="contact-form-wrap reveal rv1">
            
            @if(session('success'))
                <div class="alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('/contact') }}" method="POST">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Jane Doe" value="{{ old('name') }}">
                        @error('name') <span style="color:red; font-size:0.8rem;">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="jane@company.com" value="{{ old('email') }}">
                        @error('email') <span style="color:red; font-size:0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" required placeholder="How can we help you?" value="{{ old('subject') }}">
                    @error('subject') <span style="color:red; font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" required placeholder="Tell us about your organization's challenges...">{{ old('message') }}</textarea>
                    @error('message') <span style="color:red; font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Send Message
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>

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