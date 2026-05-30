{{--
    ╔══════════════════════════════════════════════════════════════════╗
    ║  <x-captcha />  ·  Reusable human-verification component           ║
    ╠══════════════════════════════════════════════════════════════════╣
    ║  Drop inside any <form> that POSTs to a route validating the       ║
    ║  `captcha` field with \App\Rules\Captcha:                          ║
    ║                                                                    ║
    ║      <x-captcha />                                                 ║
    ║      <x-captcha label="Are you human?" />                          ║
    ║                                                                    ║
    ║  A fresh, single-use challenge is minted on every render. The      ║
    ║  plaintext answer never reaches the browser.                       ║
    ╚══════════════════════════════════════════════════════════════════╝
--}}
@props([
    'label' => 'Human Verification',
])

@php
    $captcha = \App\Services\Captcha::issue();
    $length  = \App\Services\Captcha::length();
    // Unique id so several captchas can coexist on one page.
    $cid     = 'captcha_' . \Illuminate\Support\Str::random(8);
    $hasError = $errors->has('captcha');
@endphp

<div class="xcaptcha {{ $hasError ? 'is-error' : '' }}"
     id="{{ $cid }}"
     data-refresh-url="{{ route('captcha.refresh') }}"
     data-length="{{ $length }}">

    {{-- Honeypot: invisible to humans, irresistible to naive bots. --}}
    <div class="xc-hp" aria-hidden="true">
        <label for="{{ $cid }}_hp">Leave this field empty</label>
        <input type="text" id="{{ $cid }}_hp" name="_captcha_hp" tabindex="-1" autocomplete="off" value="">
    </div>

    {{-- Signed, stateless token (carries only a hash of the answer). --}}
    <input type="hidden" name="_captcha_token" value="{{ $captcha['token'] }}" data-captcha-token>

    {{-- Header --}}
    <div class="xc-head">
        <span class="xc-badge">
            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <path d="M9 12l2 2 4-4"></path>
            </svg>
            {{ $label }}
        </span>
        <span class="xc-hint">Not case-sensitive</span>
    </div>

    {{-- Challenge stage --}}
    <div class="xc-stage">
        <div class="xc-image" data-captcha-image>
            {!! $captcha['svg'] !!}
            <span class="xc-sheen" aria-hidden="true"></span>
        </div>

        <button type="button" class="xc-refresh" data-captcha-refresh
                title="Get a new image" aria-label="Get a new verification image">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
        </button>
    </div>

    {{-- Answer field --}}
    <div class="xc-field">
        <input type="text" id="{{ $cid }}_input" name="captcha"
               class="xc-input" required autocomplete="off" autocapitalize="characters"
               autocorrect="off" spellcheck="false" inputmode="text"
               maxlength="{{ $length }}" placeholder="Type the {{ $length }} characters above"
               aria-describedby="{{ $cid }}_status" data-captcha-input>

        <span class="xc-meter" id="{{ $cid }}_status" data-captcha-meter aria-live="polite">
            <span class="xc-count" data-captcha-count>0</span><span class="xc-sep">/</span>{{ $length }}
            <svg class="xc-check" viewBox="0 0 24 24" width="15" height="15" fill="none"
                 stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </span>
    </div>

    @error('captcha')
        <span class="xc-error" role="alert">
            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor"
                 stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            {{ $message }}
        </span>
    @enderror
</div>

@once
@push('styles')
<style>
    /* ─────────────────────────────────────────────────────────
       <x-captcha />  ·  premium verification UI
    ───────────────────────────────────────────────────────── */
    .xcaptcha {
        --xc-copper: var(--copper, #b5722a);
        --xc-copper2: var(--copper2, #d4924e);
        --xc-slate: var(--slate, #1a2332);
        --xc-ivory: var(--ivory, #faf7f2);
        --xc-ivory3: var(--ivory3, #e8dfd2);
        --xc-radius: 12px;
        position: relative;
        margin-bottom: 1.75rem;
        padding: .8rem .85rem .9rem;
        background: linear-gradient(180deg, #fff, var(--xc-ivory));
        border: 1px solid var(--xc-ivory3);
        border-radius: var(--xc-radius);
        box-shadow: 0 1px 2px rgba(26,35,50,.04), 0 10px 30px -18px rgba(26,35,50,.25);
    }
    .xcaptcha::before {
        content: '';
        position: absolute; top: 0; left: .85rem; right: .85rem; height: 2px;
        background: linear-gradient(90deg, transparent, var(--xc-copper), transparent);
        border-radius: 2px;
    }

    .xc-hp { position: absolute; left: -9999px; top: -9999px; width: 1px; height: 1px; overflow: hidden; }

    /* Header */
    .xc-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: .6rem;
    }
    .xc-badge {
        display: inline-flex; align-items: center; gap: .45rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
        font-size: .66rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
        color: var(--xc-slate);
    }
    .xc-badge svg { color: var(--xc-copper); }
    .xc-hint {
        font-family: -apple-system, sans-serif;
        font-size: .68rem; font-weight: 500; letter-spacing: .01em;
        color: var(--muted, #7a8fa0);
    }

    /* Challenge stage */
    .xc-stage { display: flex; align-items: stretch; gap: .55rem; }
    .xc-image {
        position: relative; flex: 1 1 auto; max-width: 200px; line-height: 0;
        border: 1px solid var(--xc-ivory3); border-radius: 8px;
        background: #fff; overflow: hidden;
        box-shadow: inset 0 1px 4px rgba(26,35,50,.08);
        transition: opacity .28s ease, filter .28s ease;
    }
    .xc-image svg { display: block; width: 100%; height: auto; }
    .xc-image.is-loading { opacity: .35; filter: blur(1.5px); }
    /* Light sweep across the artwork */
    .xc-sheen {
        position: absolute; inset: 0; pointer-events: none;
        background: linear-gradient(115deg, transparent 30%, rgba(255,255,255,.55) 50%, transparent 70%);
        transform: translateX(-120%);
    }
    .xc-image:hover .xc-sheen { animation: xc-sheen 1.1s ease; }
    @keyframes xc-sheen { to { transform: translateX(120%); } }

    .xc-refresh {
        flex: 0 0 auto; width: 42px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #fff; border: 1px solid var(--xc-ivory3); border-radius: 8px;
        color: var(--xc-copper); cursor: pointer;
        transition: background .25s, border-color .25s, color .25s, box-shadow .25s, transform .1s;
    }
    .xc-refresh:hover { background: var(--xc-ivory); border-color: var(--xc-copper); box-shadow: 0 4px 14px -6px rgba(181,114,42,.5); }
    .xc-refresh:active { transform: scale(.94); }
    .xc-refresh svg { transition: transform .6s cubic-bezier(.34,1.56,.64,1); }
    .xc-refresh.is-spinning svg { transform: rotate(-360deg); }

    /* Answer field */
    .xc-field { position: relative; margin-top: .7rem; }
    .xc-input {
        width: 100%; box-sizing: border-box;
        background: var(--xc-ivory); border: 1px solid var(--xc-ivory3); border-radius: 8px;
        padding: .7rem 3.8rem .7rem .95rem;
        font-family: 'SFMono-Regular', ui-monospace, 'Menlo', monospace;
        font-size: .95rem; font-weight: 600; letter-spacing: .3em; text-transform: uppercase;
        color: var(--charcoal, #2c3a4a); outline: none;
        transition: background .25s, border-color .25s, box-shadow .25s;
    }
    .xc-input::placeholder { letter-spacing: .02em; text-transform: none; font-family: -apple-system, sans-serif; font-weight: 400; color: var(--muted, #7a8fa0); font-size: .9rem; }
    .xc-input:focus { background: #fff; border-color: var(--xc-copper); box-shadow: 0 0 0 3px rgba(181,114,42,.12); }

    /* Live counter / completion tick */
    .xc-meter {
        position: absolute; top: 50%; right: 1rem; transform: translateY(-50%);
        display: inline-flex; align-items: center; gap: 1px;
        font-family: -apple-system, sans-serif; font-size: .78rem; font-weight: 700;
        color: var(--muted, #7a8fa0); letter-spacing: .04em; pointer-events: none;
        transition: color .25s;
    }
    .xc-meter .xc-sep { opacity: .5; margin: 0 1px; }
    .xc-check {
        width: 0; opacity: 0; color: var(--xc-copper); margin-left: .15rem;
        transition: opacity .3s, width .3s, transform .3s;
        transform: scale(.4);
    }
    .xcaptcha.is-complete .xc-input { border-color: var(--xc-copper); background: #fff; }
    .xcaptcha.is-complete .xc-meter { color: var(--xc-copper); }
    .xcaptcha.is-complete .xc-count,
    .xcaptcha.is-complete .xc-sep { display: none; }
    .xcaptcha.is-complete .xc-meter::after { content: 'Ready'; font-size: .68rem; letter-spacing: .1em; text-transform: uppercase; }
    .xcaptcha.is-complete .xc-check { width: 15px; opacity: 1; transform: scale(1); }

    /* Error state */
    .xc-error {
        display: flex; align-items: center; gap: .4rem; margin-top: .6rem;
        font-family: -apple-system, sans-serif; font-size: .8rem; font-weight: 600;
        color: #d6453f;
    }
    .xc-error svg { flex: 0 0 auto; }
    .xcaptcha.is-error { border-color: rgba(214,69,63,.5); animation: xc-shake .42s cubic-bezier(.36,.07,.19,.97); }
    .xcaptcha.is-error::before { background: linear-gradient(90deg, transparent, #d6453f, transparent); }
    .xcaptcha.is-error .xc-input { border-color: rgba(214,69,63,.6); }
    @keyframes xc-shake {
        10%, 90% { transform: translateX(-1px); }
        20%, 80% { transform: translateX(2px); }
        30%, 50%, 70% { transform: translateX(-4px); }
        40%, 60% { transform: translateX(4px); }
    }

    @media (prefers-reduced-motion: reduce) {
        .xc-image:hover .xc-sheen, .xcaptcha.is-error { animation: none; }
        .xc-refresh svg, .xc-image, .xc-check { transition: none; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var ALLOWED = /[^A-HJKMNP-Z2-9]/g; // mirror the server set exactly (no I, L, O, 0, 1)

    function wire(root) {
        var url    = root.getAttribute('data-refresh-url');
        var len    = parseInt(root.getAttribute('data-length'), 10) || 5;
        var btn    = root.querySelector('[data-captcha-refresh]');
        var imgBox = root.querySelector('[data-captcha-image]');
        var token  = root.querySelector('[data-captcha-token]');
        var input  = root.querySelector('[data-captcha-input]');
        var count  = root.querySelector('[data-captcha-count]');

        // Live formatting + completion feedback.
        if (input) {
            input.addEventListener('input', function () {
                var cleaned = input.value.toUpperCase().replace(ALLOWED, '').slice(0, len);
                if (cleaned !== input.value) input.value = cleaned;
                if (count) count.textContent = String(cleaned.length);
                root.classList.toggle('is-complete', cleaned.length === len);
                root.classList.remove('is-error');
            });
        }

        // Refresh — crossfade to a freshly minted challenge.
        if (btn) {
            btn.addEventListener('click', function () {
                if (btn.classList.contains('is-spinning')) return;
                btn.classList.add('is-spinning');
                if (imgBox) imgBox.classList.add('is-loading');

                fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data && data.svg && data.token) {
                            // Keep the sheen element after swapping the SVG.
                            var sheen = imgBox.querySelector('.xc-sheen');
                            imgBox.innerHTML = data.svg + (sheen ? sheen.outerHTML : '');
                            token.value = data.token;
                            if (input) { input.value = ''; if (count) count.textContent = '0'; }
                            root.classList.remove('is-complete', 'is-error');
                            if (input) input.focus();
                        }
                    })
                    .catch(function () { /* keep the current challenge on failure */ })
                    .finally(function () {
                        setTimeout(function () {
                            btn.classList.remove('is-spinning');
                            if (imgBox) imgBox.classList.remove('is-loading');
                        }, 320);
                    });
            });
        }

        // After a failed submit the page reloads with the error — bring the
        // captcha into view and focus the input so the user knows where to look.
        if (root.classList.contains('is-error')) {
            setTimeout(function () {
                root.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (input) input.focus({ preventScroll: true });
            }, 120);
        }
    }

    document.querySelectorAll('.xcaptcha').forEach(wire);
})();
</script>
@endpush
@endonce
