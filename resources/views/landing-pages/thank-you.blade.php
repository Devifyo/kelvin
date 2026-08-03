@extends('layouts.app')

@section('title', \App\Models\AppSetting::get('ty_heading', 'Thank you for your inquiry.'))
@section('meta_description', 'Your inquiry has been received. We will respond promptly.')
@section('og_type', 'website')
{{-- Confirmation page: keep it out of the index but let link equity flow. --}}
@section('meta_robots', 'noindex, follow')

@php
    $ty_kicker      = \App\Models\AppSetting::get('ty_kicker', 'Message Received');
    $ty_heading     = \App\Models\AppSetting::get('ty_heading', 'Thank you for your inquiry.');
    $ty_body        = \App\Models\AppSetting::get('ty_body', "We've received your message and will respond within one working day.");
    $ty_button_text = \App\Models\AppSetting::get('ty_button_text', 'Back to Home');
    $ty_button_link = \App\Models\AppSetting::get('ty_button_link') ?: route('home');
@endphp

@push('styles')
<style>
    /* Fill the viewport so the ivory <main> background never shows below the hero
       (main has min-height:100vh, so a short section would leave a light gap). */
    .ty-section { min-height: 100vh; display:flex; align-items:center; justify-content:center; background:var(--slate); padding:9rem 1.5rem 6rem; text-align:center; position:relative; overflow:hidden; }
    .ty-section::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 70% 70% at 50% 0%, rgba(47,66,89,.75) 0%, transparent 75%); }
    .ty-inner { position:relative; z-index:1; max-width:640px; }
    .ty-check { width:74px; height:74px; margin:0 auto 2rem; border-radius:50%; border:1.5px solid var(--copper2); display:flex; align-items:center; justify-content:center; color:var(--copper3); }
    .ty-check svg { width:34px; height:34px; }
    .ty-kicker { display:inline-flex; align-items:center; gap:.75rem; font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.22em; text-transform:uppercase; color:var(--copper3); margin-bottom:1.25rem; }
    .ty-kicker::before, .ty-kicker::after { content:''; width:28px; height:1px; background:var(--copper2); }
    .ty-section h1 { font-family:'Cormorant Garamond',serif; font-size:clamp(2.4rem,5vw,3.6rem); font-weight:400; color:#fff; line-height:1.1; margin-bottom:1.3rem; }
    .ty-section p { font-family:-apple-system,sans-serif; font-size:1.05rem; color:rgba(250,247,242,.75); line-height:1.8; font-weight:300; max-width:520px; margin:0 auto 2.4rem; }
    .ty-btn { display:inline-flex; align-items:center; gap:.6rem; font-family:-apple-system,sans-serif; font-size:.72rem; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#fff; background:var(--copper); padding:1rem 2.25rem; border-radius:2px; transition:background .3s, transform .3s; }
    .ty-btn:hover { background:var(--copper2); transform:translateY(-2px); }
    .ty-btn svg { width:14px; height:14px; }

    /* ── Entrance animation (plays once on load) ─────────────── */
    @keyframes tyFadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    @keyframes tyPop { 0% { opacity:0; transform:scale(.4); } 60% { opacity:1; transform:scale(1.1); } 100% { opacity:1; transform:scale(1); } }
    @keyframes tyRing { from { opacity:0; transform:scale(.4) rotate(-90deg); } to { opacity:1; transform:scale(1) rotate(0); } }
    @keyframes tyDraw { to { stroke-dashoffset:0; } }

    .ty-check { animation: tyRing .7s cubic-bezier(.34,1.56,.64,1) both; }
    /* draw the check mark itself after the ring settles */
    .ty-check svg polyline { stroke-dasharray:22; stroke-dashoffset:22; animation: tyDraw .45s ease .55s forwards; }
    .ty-kicker         { animation: tyFadeUp .6s ease .35s both; }
    .ty-section h1     { animation: tyFadeUp .7s ease .5s both; }
    .ty-section p      { animation: tyFadeUp .7s ease .68s both; }
    /* 'backwards' (not 'both') so the animation doesn't lock the transform and
       break the hover lift once it finishes. */
    .ty-btn            { animation: tyPop .6s cubic-bezier(.34,1.56,.64,1) .86s backwards; }

    @media (prefers-reduced-motion: reduce) {
        .ty-check, .ty-kicker, .ty-section h1, .ty-section p, .ty-btn { animation: none !important; }
        .ty-check svg polyline { stroke-dashoffset:0 !important; }
    }
</style>
@endpush

@php
    // Google Ads conversion (Enhanced Conversions for Leads).
    // Only when: this really followed a submission, and both the Ads ID and the
    // conversion label are configured. The email arrives already hashed.
    $_adsId       = \App\Models\AppSetting::get('seo_google_ads_id', '');
    $_convLabel   = \App\Models\AppSetting::get('seo_google_ads_conversion_label', '');
    $_ecEmailHash = session('ec_email_hash');
    $_fireConv    = session('contact_submitted') && $_adsId && $_convLabel;
@endphp

@if($_fireConv)
@push('scripts')
<script>
(function () {
    if (typeof gtag !== 'function') return;
    @if($_ecEmailHash)
    // Enhanced Conversions: attach the hashed email only if the visitor consented.
    if (/(?:^|; )cookie_consent=accepted/.test(document.cookie)) {
        gtag('set', 'user_data', { sha256_email_address: '{{ $_ecEmailHash }}' });
    }
    @endif
    gtag('event', 'conversion', { send_to: '{{ $_adsId }}/{{ $_convLabel }}' });
})();
</script>
@endpush
@endif

@section('content')
<section class="ty-section">
    <div class="ty-inner">
        <div class="ty-check">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>

        @if($ty_kicker)<div class="ty-kicker">{{ $ty_kicker }}</div>@endif
        <h1>{{ $ty_heading }}</h1>
        @if($ty_body)<p>{{ $ty_body }}</p>@endif

        @if($ty_button_text)
            <a href="{{ $ty_button_link }}" class="ty-btn">{{ $ty_button_text }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        @endif
    </div>
</section>
@endsection
