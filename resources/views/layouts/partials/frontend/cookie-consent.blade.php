@php
    use App\Models\AppSetting;
    $cc_enabled     = AppSetting::get('cc_enabled', '1') === '1';
    $cc_heading     = AppSetting::get('cc_heading', 'We value your privacy');
    $cc_message     = AppSetting::get('cc_message', 'We use cookies to run this site, analyse traffic, and measure our advertising. You can accept all cookies or decline non-essential ones. See our policy for details.');
    $cc_accept_text = AppSetting::get('cc_accept_text', 'Accept all');
    $cc_decline_text= AppSetting::get('cc_decline_text', 'Decline');
    $cc_link_text   = AppSetting::get('cc_link_text', 'Privacy Policy');
    $cc_link_url    = AppSetting::get('cc_link_url') ?: route('privacy');
@endphp

@if($cc_enabled)
<div id="cookieConsent" class="cc-banner" role="dialog" aria-live="polite" aria-label="Cookie consent" hidden>
    <div class="cc-inner">
        <div class="cc-copy">
            @if($cc_heading)<div class="cc-heading">{{ $cc_heading }}</div>@endif
            <p class="cc-message">{{ $cc_message }}
                @if($cc_link_text)<a href="{{ $cc_link_url }}" class="cc-link">{{ $cc_link_text }}</a>@endif
            </p>
        </div>
        <div class="cc-actions">
            <button type="button" class="cc-btn cc-decline" data-cc="declined">{{ $cc_decline_text }}</button>
            <button type="button" class="cc-btn cc-accept" data-cc="accepted">{{ $cc_accept_text }}</button>
        </div>
    </div>
</div>

<style>
    .cc-banner { position:fixed; left:1.25rem; right:1.25rem; bottom:1.25rem; z-index:9000; background:var(--slate,#1a2332); color:var(--ivory,#faf7f2); border:1px solid rgba(181,114,42,.35); border-radius:12px; box-shadow:0 16px 44px rgba(0,0,0,.4); padding:1.15rem 1.35rem; opacity:0; transform:translateY(14px); transition:opacity .35s ease, transform .35s ease; }
    .cc-banner.is-visible { opacity:1; transform:translateY(0); }
    .cc-inner { max-width:1160px; margin:0 auto; display:flex; align-items:center; gap:1.75rem; }
    .cc-heading { font-family:'Cormorant Garamond',serif; font-size:1.15rem; font-weight:600; color:#fff; margin-bottom:.15rem; }
    .cc-message { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; font-size:.85rem; line-height:1.6; color:rgba(250,247,242,.8); font-weight:300; }
    .cc-link { color:var(--copper3,#edb97a); text-decoration:underline; text-underline-offset:2px; white-space:nowrap; }
    .cc-link:hover { color:#fff; }
    .cc-actions { display:flex; align-items:center; gap:.75rem; flex-shrink:0; }
    .cc-btn { font-family:-apple-system,sans-serif; font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; padding:.75rem 1.5rem; border-radius:3px; cursor:pointer; transition:background .25s, color .25s, border-color .25s; white-space:nowrap; }
    .cc-decline { background:transparent; color:rgba(250,247,242,.75); border:1px solid rgba(250,247,242,.28); }
    .cc-decline:hover { color:#fff; border-color:rgba(250,247,242,.6); }
    .cc-accept { background:var(--copper,#b5722a); color:#fff; border:1px solid var(--copper,#b5722a); }
    .cc-accept:hover { background:var(--copper2,#d4924e); border-color:var(--copper2,#d4924e); }
    @media (max-width:768px){ .cc-inner { flex-direction:column; align-items:stretch; gap:1rem; } .cc-actions { justify-content:stretch; } .cc-btn { flex:1; text-align:center; } }
</style>

<script>
(function () {
    var banner = document.getElementById('cookieConsent');
    if (!banner) return;

    function readConsent() {
        var m = document.cookie.match(/(?:^|; )cookie_consent=([^;]+)/);
        return m ? decodeURIComponent(m[1]) : null;
    }
    function setConsent(value) {
        var maxAge = 60 * 60 * 24 * 180; // 180 days
        var secure = location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = 'cookie_consent=' + value + '; Max-Age=' + maxAge + '; Path=/; SameSite=Lax' + secure;
    }
    function hide() {
        banner.classList.remove('is-visible');
        window.setTimeout(function () { banner.hidden = true; }, 350);
    }

    // Only show if the visitor has not chosen yet.
    if (!readConsent()) {
        banner.hidden = false;
        requestAnimationFrame(function () { banner.classList.add('is-visible'); });
    }

    banner.querySelectorAll('[data-cc]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var choice = btn.getAttribute('data-cc');
            setConsent(choice);
            if (choice === 'accepted' && typeof gtag === 'function') {
                gtag('consent', 'update', {
                    ad_storage: 'granted',
                    ad_user_data: 'granted',
                    ad_personalization: 'granted',
                    analytics_storage: 'granted'
                });
            }
            hide();
        });
    });
})();
</script>
@endif
