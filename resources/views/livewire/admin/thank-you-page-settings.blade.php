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

        .cps-field { margin-bottom:.9rem; }
        .cps-field:last-child { margin-bottom:0; }
        .cps-label { display:block; font-size:.68rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:var(--slate,#1a2332); margin-bottom:.4rem; }
        .cps-label .req { color:#ef4444; }
        .cps-label small { font-weight:400; text-transform:none; letter-spacing:0; color:var(--muted,#8a8175); }
        .cps-input { width:100%; padding:.6rem .75rem; font-size:.85rem; color:var(--slate,#1a2332); background:var(--ivory,#faf7f2); border:1px solid var(--ivory3,#e8dfd2); border-radius:7px; transition:border-color .2s, box-shadow .2s; }
        .cps-input:focus { outline:none; border-color:var(--copper,#b5722a); background:#fff; box-shadow:0 0 0 2px rgba(181,114,42,.15); }
        textarea.cps-input { min-height:80px; resize:vertical; line-height:1.55; }
        .cps-err { display:block; color:#ef4444; font-size:.7rem; margin-top:.25rem; }

        .cps-footer { flex-shrink:0; padding:1rem 1.4rem; border-top:1px solid var(--ivory3,#e8dfd2); background:var(--ivory,#faf7f2); display:flex; justify-content:flex-end; }
        .cps-save { display:inline-flex; align-items:center; gap:.5rem; background:var(--copper,#b5722a); color:#fff; font-weight:800; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; padding:.75rem 1.6rem; border:none; border-radius:8px; cursor:pointer; transition:background .25s; }
        .cps-save:hover { background:var(--slate,#1a2332); }

        .cps-preview { flex:1; position:sticky; top:100px; height:calc(100vh - 120px); display:flex; flex-direction:column; background:var(--white,#fff); border:1px solid var(--ivory3,#e8dfd2); border-radius:16px; overflow:hidden; box-shadow:0 4px 24px -8px rgba(26,35,50,.1); }
        .cps-chrome { background:#f0ece5; border-bottom:1px solid #e0d8ce; padding:.6rem 1rem; display:flex; align-items:center; gap:.85rem; flex-shrink:0; }
        .cps-dots { display:flex; gap:.4rem; }
        .cps-dots span { width:11px; height:11px; border-radius:50%; }
        .cps-url { flex:1; min-width:0; background:rgba(255,255,255,.85); border:1px solid rgba(0,0,0,.09); border-radius:7px; padding:.32rem .75rem; font-size:.72rem; color:var(--muted,#8a8175); font-family:-apple-system,sans-serif; display:flex; align-items:center; gap:.45rem; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
        .cps-url svg { width:11px; height:11px; flex-shrink:0; color:#10b981; }
        .cps-statusbar { background:var(--ivory,#faf7f2); border-bottom:1px solid var(--ivory3,#e8dfd2); padding:.45rem 1.25rem; display:flex; align-items:center; gap:.45rem; flex-shrink:0; font-size:.7rem; font-weight:600; color:var(--slate,#1a2332); font-family:-apple-system,sans-serif; }
        .cps-pulse { width:7px; height:7px; background:#10b981; border-radius:50%; display:inline-block; animation:cpsPulse 2.2s ease-in-out infinite; }
        @keyframes cpsPulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.8)} }
        .cps-scroll { flex:1; overflow-y:auto; overflow-x:hidden; background:#1a2332; display:flex; }

        /* preview: mock of the confirmation page */
        .typv { flex:1; background:#1a2332; padding:3.5rem 2.5rem; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .typv-check { width:64px; height:64px; margin-bottom:1.6rem; border-radius:50%; border:1.5px solid var(--copper2,#d4924e); display:flex; align-items:center; justify-content:center; color:var(--copper3,#edb97a); }
        .typv-check svg { width:30px; height:30px; }
        .typv-k { display:inline-flex; align-items:center; gap:.6rem; font-family:-apple-system,sans-serif; font-size:.6rem; font-weight:800; letter-spacing:.22em; text-transform:uppercase; color:var(--copper3,#edb97a); margin-bottom:1rem; }
        .typv-k::before,.typv-k::after { content:''; width:24px; height:1px; background:var(--copper2,#d4924e); }
        .typv h1 { font-family:'Cormorant Garamond',serif; font-size:2.6rem; font-weight:400; color:#fff; line-height:1.1; margin-bottom:1.1rem; }
        .typv p { font-family:-apple-system,sans-serif; font-size:.95rem; color:rgba(250,247,242,.75); line-height:1.75; font-weight:300; max-width:26rem; margin:0 auto 1.8rem; }
        .typv-btn { display:inline-block; font-family:-apple-system,sans-serif; font-size:.68rem; font-weight:800; letter-spacing:.15em; text-transform:uppercase; color:#fff; background:var(--copper,#b5722a); padding:.8rem 1.8rem; border-radius:2px; }

        @media (max-width:1100px) {
            .cps-root { flex-direction:column; }
            .cps-panel { width:100%; max-width:none; position:static; max-height:none; }
            .cps-preview { width:100%; height:560px; position:static; }
        }
    </style>

    {{-- ══════════ LEFT: form ══════════ --}}
    <form wire:submit.prevent="save" class="cps-panel">
        <div class="cps-panel-head">
            <h1>Thank You Page</h1>
            <p>Shown after a visitor submits the contact form (its own URL for conversion tracking).</p>
        </div>

        <div class="cps-body">
            <div class="cps-group-head">
                <span class="t">Confirmation Content</span>
                <button type="button" class="cps-reset"
                        x-on:click="Swal.fire({title:'Reset to defaults?', text:'Restores the original wording.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection()})">Reset</button>
            </div>

            <div class="cps-field">
                <label class="cps-label">Kicker <small>(optional)</small></label>
                <input type="text" class="cps-input" wire:model.live.debounce.400ms="ty_kicker" placeholder="Message Received">
                @error('ty_kicker')<span class="cps-err">{{ $message }}</span>@enderror
            </div>
            <div class="cps-field">
                <label class="cps-label">Heading / H1 <span class="req">*</span></label>
                <input type="text" class="cps-input" wire:model.live.debounce.400ms="ty_heading" placeholder="Thank you for your inquiry.">
                @error('ty_heading')<span class="cps-err">{{ $message }}</span>@enderror
            </div>
            <div class="cps-field">
                <label class="cps-label">Body <small>(optional)</small></label>
                <textarea class="cps-input" wire:model.live.debounce.400ms="ty_body" placeholder="We've received your message and will respond within one working day."></textarea>
                @error('ty_body')<span class="cps-err">{{ $message }}</span>@enderror
            </div>
            <div class="cps-field">
                <label class="cps-label">Button Label <small>(blank hides it)</small></label>
                <input type="text" class="cps-input" wire:model.live.debounce.400ms="ty_button_text" placeholder="Back to Home">
                @error('ty_button_text')<span class="cps-err">{{ $message }}</span>@enderror
            </div>
            <div class="cps-field">
                <label class="cps-label">Button Link <small>path or full URL</small></label>
                <input type="text" class="cps-input" wire:model.live.debounce.400ms="ty_button_link" placeholder="/">
                @error('ty_button_link')<span class="cps-err">{{ $message }}</span>@enderror
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
            <div class="cps-url">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                {{ str_replace(['https://','http://'], '', route('contact.thankyou')) }}
            </div>
        </div>
        <div class="cps-statusbar"><span class="cps-pulse"></span> Live Preview — updates as you type</div>

        <div class="cps-scroll">
            <div class="typv">
                <div class="typv-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                @if($ty_kicker)<div class="typv-k">{{ $ty_kicker }}</div>@endif
                <h1>{{ $ty_heading ?: 'Thank you for your inquiry.' }}</h1>
                @if($ty_body)<p>{{ $ty_body }}</p>@endif
                @if($ty_button_text)<div class="typv-btn">{{ $ty_button_text }}</div>@endif
            </div>
        </div>
    </div>
</div>
