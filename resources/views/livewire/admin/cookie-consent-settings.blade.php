<div class="cps-root">
    <style>
        .cps-root { display:flex; gap:1.5rem; align-items:flex-start; max-width:1600px; margin:0 auto; }
        .cps-panel { width:430px; min-width:380px; max-width:470px; position:sticky; top:100px; max-height:calc(100vh - 120px); display:flex; flex-direction:column; background:var(--white,#fff); border:1px solid var(--ivory3,#e8dfd2); border-radius:16px; overflow:hidden; box-shadow:0 4px 24px -8px rgba(26,35,50,.1); }
        .cps-panel-head { padding:1.1rem 1.4rem; border-bottom:1px solid var(--ivory3,#e8dfd2); flex-shrink:0; }
        .cps-panel-head h1 { font-family:'Cormorant Garamond',serif; font-size:1.7rem; font-weight:600; color:var(--slate,#1a2332); line-height:1.1; }
        .cps-panel-head p { font-size:.78rem; color:var(--muted,#8a8175); margin-top:.2rem; line-height:1.5; }
        .cps-body { flex:1; overflow-y:auto; padding:1.25rem 1.4rem; }
        .cps-body::-webkit-scrollbar { width:4px; }
        .cps-body::-webkit-scrollbar-thumb { background:var(--ivory3,#e8dfd2); border-radius:4px; }

        .cps-group-head { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:.9rem; padding-bottom:.55rem; border-bottom:1px solid var(--ivory3,#e8dfd2); }
        .cps-group-head .t { font-weight:800; font-size:.72rem; letter-spacing:.08em; text-transform:uppercase; color:var(--slate,#1a2332); }
        .cps-reset { background:none; border:1px solid var(--ivory3,#e8dfd2); color:var(--muted,#8a8175); font-weight:700; font-size:.62rem; letter-spacing:.05em; text-transform:uppercase; padding:.35rem .7rem; border-radius:6px; cursor:pointer; white-space:nowrap; }
        .cps-reset:hover { border-color:var(--copper,#b5722a); color:var(--copper,#b5722a); }

        .cps-toggle { display:flex; align-items:center; gap:.7rem; padding:.75rem .85rem; background:var(--ivory,#faf7f2); border:1px solid var(--ivory3,#e8dfd2); border-radius:8px; margin-bottom:1.1rem; cursor:pointer; }
        .cps-toggle input { width:18px; height:18px; accent-color:var(--copper,#b5722a); cursor:pointer; }
        .cps-toggle span { font-size:.82rem; font-weight:700; color:var(--slate,#1a2332); }
        .cps-toggle small { display:block; font-weight:400; color:var(--muted,#8a8175); font-size:.72rem; }

        .cps-field { margin-bottom:.9rem; }
        .cps-field:last-child { margin-bottom:0; }
        .cps-label { display:block; font-size:.68rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:var(--slate,#1a2332); margin-bottom:.4rem; }
        .cps-label small { font-weight:400; text-transform:none; letter-spacing:0; color:var(--muted,#8a8175); }
        .cps-input { width:100%; padding:.6rem .75rem; font-size:.85rem; color:var(--slate,#1a2332); background:var(--ivory,#faf7f2); border:1px solid var(--ivory3,#e8dfd2); border-radius:7px; transition:border-color .2s, box-shadow .2s; }
        .cps-input:focus { outline:none; border-color:var(--copper,#b5722a); background:#fff; box-shadow:0 0 0 2px rgba(181,114,42,.15); }
        textarea.cps-input { min-height:96px; resize:vertical; line-height:1.55; }
        .cps-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .cps-err { display:block; color:#ef4444; font-size:.7rem; margin-top:.25rem; }

        .cps-footer { flex-shrink:0; padding:1rem 1.4rem; border-top:1px solid var(--ivory3,#e8dfd2); background:var(--ivory,#faf7f2); display:flex; justify-content:flex-end; }
        .cps-save { display:inline-flex; align-items:center; gap:.5rem; background:var(--copper,#b5722a); color:#fff; font-weight:800; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; padding:.75rem 1.6rem; border:none; border-radius:8px; cursor:pointer; transition:background .25s; }
        .cps-save:hover { background:var(--slate,#1a2332); }

        .cps-preview { flex:1; position:sticky; top:100px; height:calc(100vh - 120px); display:flex; flex-direction:column; background:var(--white,#fff); border:1px solid var(--ivory3,#e8dfd2); border-radius:16px; overflow:hidden; box-shadow:0 4px 24px -8px rgba(26,35,50,.1); }
        .cps-chrome { background:#f0ece5; border-bottom:1px solid #e0d8ce; padding:.6rem 1rem; display:flex; align-items:center; gap:.85rem; flex-shrink:0; }
        .cps-dots { display:flex; gap:.4rem; }
        .cps-dots span { width:11px; height:11px; border-radius:50%; }
        .cps-statusbar { background:var(--ivory,#faf7f2); border-bottom:1px solid var(--ivory3,#e8dfd2); padding:.45rem 1.25rem; display:flex; align-items:center; gap:.45rem; flex-shrink:0; font-size:.7rem; font-weight:600; color:var(--slate,#1a2332); font-family:-apple-system,sans-serif; }
        .cps-pulse { width:7px; height:7px; background:#10b981; border-radius:50%; display:inline-block; animation:cpsPulse 2.2s ease-in-out infinite; }
        @keyframes cpsPulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.8)} }
        /* preview stage — a mock page footer area with the banner floating over it */
        .cps-stage { flex:1; overflow:hidden; background:#efe9e0; position:relative; display:flex; align-items:flex-end; padding:1.25rem; }
        .cps-stage.is-off { align-items:center; justify-content:center; }
        .cps-off-note { font-family:-apple-system,sans-serif; font-size:.85rem; color:var(--muted,#8a8175); }

        .pv-banner { width:100%; background:var(--slate,#1a2332); color:var(--ivory,#faf7f2); border:1px solid rgba(181,114,42,.35); border-radius:12px; box-shadow:0 16px 44px rgba(0,0,0,.35); padding:1.1rem 1.3rem; display:flex; align-items:center; gap:1.5rem; }
        .pv-heading { font-family:'Cormorant Garamond',serif; font-size:1.15rem; font-weight:600; color:#fff; margin-bottom:.15rem; }
        .pv-message { font-family:-apple-system,sans-serif; font-size:.82rem; line-height:1.6; color:rgba(250,247,242,.8); font-weight:300; }
        .pv-link { color:var(--copper3,#edb97a); text-decoration:underline; white-space:nowrap; }
        .pv-actions { display:flex; gap:.6rem; flex-shrink:0; }
        .pv-btn { font-family:-apple-system,sans-serif; font-size:.66rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; padding:.65rem 1.2rem; border-radius:3px; white-space:nowrap; }
        .pv-decline { background:transparent; color:rgba(250,247,242,.75); border:1px solid rgba(250,247,242,.28); }
        .pv-accept { background:var(--copper,#b5722a); color:#fff; }

        @media (max-width:1100px) {
            .cps-root { flex-direction:column; }
            .cps-panel { width:100%; max-width:none; position:static; max-height:none; }
            .cps-preview { width:100%; height:420px; position:static; }
        }
    </style>

    {{-- ══════════ LEFT: form ══════════ --}}
    <form wire:submit.prevent="save" class="cps-panel">
        <div class="cps-panel-head">
            <h1>Cookie Banner</h1>
            <p>Consent banner shown to new visitors (Google Consent Mode v2 — ads &amp; analytics stay off until Accept).</p>
        </div>

        <div class="cps-body">
            <label class="cps-toggle">
                <input type="checkbox" wire:model.live="cc_enabled">
                <span>Show the cookie banner<small>Turn off only if you handle consent elsewhere (e.g. inside GTM).</small></span>
            </label>

            <div class="cps-group-head">
                <span class="t">Banner Text</span>
                <button type="button" class="cps-reset"
                        x-on:click="Swal.fire({title:'Reset to defaults?', text:'Restores the original wording.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection()})">Reset</button>
            </div>

            <div class="cps-field">
                <label class="cps-label">Heading <small>(optional)</small></label>
                <input type="text" class="cps-input" wire:model.live.debounce.400ms="cc_heading" placeholder="We value your privacy">
                @error('cc_heading')<span class="cps-err">{{ $message }}</span>@enderror
            </div>
            <div class="cps-field">
                <label class="cps-label">Message</label>
                <textarea class="cps-input" wire:model.live.debounce.400ms="cc_message" placeholder="We use cookies to..."></textarea>
                @error('cc_message')<span class="cps-err">{{ $message }}</span>@enderror
            </div>
            <div class="cps-grid-2">
                <div class="cps-field">
                    <label class="cps-label">Accept Button</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="cc_accept_text" placeholder="Accept all">
                    @error('cc_accept_text')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Decline Button</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="cc_decline_text" placeholder="Decline">
                    @error('cc_decline_text')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="cps-grid-2">
                <div class="cps-field">
                    <label class="cps-label">Link Text</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="cc_link_text" placeholder="Privacy Policy">
                    @error('cc_link_text')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Link URL <small>blank = privacy page</small></label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="cc_link_url" placeholder="{{ $privacyUrl }}">
                    @error('cc_link_url')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="cps-footer">
            <button type="submit" class="cps-save">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Changes
            </button>
        </div>
    </form>

    {{-- ══════════ RIGHT: live preview ══════════ --}}
    <div class="cps-preview">
        <div class="cps-chrome">
            <div class="cps-dots"><span style="background:#ff5f57"></span><span style="background:#febc2e"></span><span style="background:#28c840"></span></div>
        </div>
        <div class="cps-statusbar"><span class="cps-pulse"></span> Live Preview — how the banner appears to a new visitor</div>

        <div class="cps-stage {{ $cc_enabled ? '' : 'is-off' }}">
            @if($cc_enabled)
                <div class="pv-banner">
                    <div style="flex:1;">
                        @if($cc_heading)<div class="pv-heading">{{ $cc_heading }}</div>@endif
                        <p class="pv-message">{{ $cc_message }}
                            @if($cc_link_text)<a class="pv-link">{{ $cc_link_text }}</a>@endif
                        </p>
                    </div>
                    <div class="pv-actions">
                        <span class="pv-btn pv-decline">{{ $cc_decline_text ?: 'Decline' }}</span>
                        <span class="pv-btn pv-accept">{{ $cc_accept_text ?: 'Accept all' }}</span>
                    </div>
                </div>
            @else
                <div class="cps-off-note">The cookie banner is turned off — visitors won't see it.</div>
            @endif
        </div>
    </div>
</div>
