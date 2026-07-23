<div class="cps-root">
    <style>
        /* ================================================================
           ContactPageSettings — split-panel CMS with live preview
        ================================================================ */
        .cps-root { display:flex; gap:1.5rem; align-items:flex-start; max-width:1600px; margin:0 auto; }

        /* ── LEFT: control panel ─────────────────────────────────────── */
        .cps-panel { width:430px; min-width:380px; max-width:470px; position:sticky; top:100px; max-height:calc(100vh - 120px); display:flex; flex-direction:column; background:var(--white,#fff); border:1px solid var(--ivory3,#e8dfd2); border-radius:16px; overflow:hidden; box-shadow:0 4px 24px -8px rgba(26,35,50,.1); }
        .cps-panel-head { padding:1.1rem 1.4rem; border-bottom:1px solid var(--ivory3,#e8dfd2); flex-shrink:0; }
        .cps-panel-head h1 { font-family:'Cormorant Garamond',serif; font-size:1.7rem; font-weight:600; color:var(--slate,#1a2332); line-height:1.1; }
        .cps-panel-head p { font-size:.78rem; color:var(--muted,#8a8175); margin-top:.2rem; line-height:1.5; }
        .cps-body { flex:1; overflow-y:auto; padding:1.25rem 1.4rem; }
        .cps-body::-webkit-scrollbar { width:4px; }
        .cps-body::-webkit-scrollbar-thumb { background:var(--ivory3,#e8dfd2); border-radius:4px; }

        .cps-group { margin-bottom:1.6rem; }
        .cps-group:last-child { margin-bottom:0; }
        .cps-group-head { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:.9rem; padding-bottom:.55rem; border-bottom:1px solid var(--ivory3,#e8dfd2); }
        .cps-group-head .t { font-weight:800; font-size:.72rem; letter-spacing:.08em; text-transform:uppercase; color:var(--slate,#1a2332); }
        .cps-group-head .t small { display:block; font-weight:600; font-size:.66rem; letter-spacing:0; text-transform:none; color:var(--muted,#8a8175); margin-top:.15rem; }
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
        .cps-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .cps-err { display:block; color:#ef4444; font-size:.7rem; margin-top:.25rem; }

        .cps-footer { flex-shrink:0; padding:1rem 1.4rem; border-top:1px solid var(--ivory3,#e8dfd2); background:var(--ivory,#faf7f2); display:flex; justify-content:flex-end; }
        .cps-save { display:inline-flex; align-items:center; gap:.5rem; background:var(--copper,#b5722a); color:#fff; font-weight:800; font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; padding:.75rem 1.6rem; border:none; border-radius:8px; cursor:pointer; transition:background .25s; }
        .cps-save:hover { background:var(--slate,#1a2332); }

        /* ── RIGHT: live preview ─────────────────────────────────────── */
        .cps-preview { flex:1; position:sticky; top:100px; height:calc(100vh - 120px); display:flex; flex-direction:column; background:var(--white,#fff); border:1px solid var(--ivory3,#e8dfd2); border-radius:16px; overflow:hidden; box-shadow:0 4px 24px -8px rgba(26,35,50,.1); }
        .cps-chrome { background:#f0ece5; border-bottom:1px solid #e0d8ce; padding:.6rem 1rem; display:flex; align-items:center; gap:.85rem; flex-shrink:0; }
        .cps-dots { display:flex; gap:.4rem; }
        .cps-dots span { width:11px; height:11px; border-radius:50%; }
        .cps-url { flex:1; min-width:0; background:rgba(255,255,255,.85); border:1px solid rgba(0,0,0,.09); border-radius:7px; padding:.32rem .75rem; font-size:.72rem; color:var(--muted,#8a8175); font-family:-apple-system,sans-serif; display:flex; align-items:center; gap:.45rem; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
        .cps-url svg { width:11px; height:11px; flex-shrink:0; color:#10b981; }
        .cps-statusbar { background:var(--ivory,#faf7f2); border-bottom:1px solid var(--ivory3,#e8dfd2); padding:.45rem 1.25rem; display:flex; align-items:center; gap:.45rem; flex-shrink:0; font-size:.7rem; font-weight:600; color:var(--slate,#1a2332); font-family:-apple-system,sans-serif; }
        .cps-pulse { width:7px; height:7px; background:#10b981; border-radius:50%; display:inline-block; animation:cpsPulse 2.2s ease-in-out infinite; }
        @keyframes cpsPulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.8)} }
        .cps-scroll { flex:1; overflow-y:auto; overflow-x:hidden; background:#faf7f2; }
        .cps-scroll::-webkit-scrollbar { width:6px; }
        .cps-scroll::-webkit-scrollbar-thumb { background:rgba(0,0,0,.18); border-radius:3px; }
        /* min-height (adjusted for the 0.85 zoom) makes the mock fill the panel so
           short content never leaves an empty band below it. */
        .cps-inner { zoom:.85; width:100%; min-height:calc((100vh - 175px) / 0.85); background:#faf7f2; transform-origin:top left; }

        /* preview: mock of the public contact page */
        .cpv-hero { background:#1a2332; padding:3.2rem 2.5rem 2.6rem; text-align:center; }
        .cpv-hero .k { display:inline-flex; align-items:center; gap:.6rem; font-family:-apple-system,sans-serif; font-size:.6rem; font-weight:800; letter-spacing:.22em; text-transform:uppercase; color:var(--copper3,#edb97a); margin-bottom:.8rem; }
        .cpv-hero .k::before,.cpv-hero .k::after { content:''; width:24px; height:1px; background:var(--copper2,#d4924e); }
        .cpv-hero h1 { font-family:'Cormorant Garamond',serif; font-size:2.9rem; font-weight:500; color:#fff; line-height:1.05; }
        .cpv-hero h1 em { font-style:italic; color:var(--copper3,#edb97a); }
        .cpv-hero p { font-family:-apple-system,sans-serif; font-size:.9rem; color:rgba(250,247,242,.72); line-height:1.7; max-width:34rem; margin:.9rem auto 0; font-weight:300; }
        .cpv-strip { height:6px; background:linear-gradient(90deg,var(--copper,#b5722a),var(--copper3,#edb97a)); }

        .cpv-section { padding:3rem 2.5rem; display:grid; grid-template-columns:1fr 1fr; gap:2.5rem; align-items:start; }
        .cpv-info .k { display:inline-flex; align-items:center; gap:.6rem; font-family:-apple-system,sans-serif; font-size:.62rem; font-weight:800; letter-spacing:.2em; text-transform:uppercase; color:var(--copper,#b5722a); margin-bottom:.6rem; }
        .cpv-info .k::before { content:''; width:22px; height:1px; background:var(--copper,#b5722a); }
        .cpv-info h2 { font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:600; color:var(--slate,#1a2332); line-height:1.1; }
        .cpv-info h2 em { font-style:italic; color:#7a4b1f; }
        .cpv-ornament { width:44px; height:2px; background:linear-gradient(90deg,var(--copper,#b5722a),transparent); margin:.9rem 0 1.2rem; }
        .cpv-info p { font-family:'Palatino Linotype',Georgia,serif; font-size:.95rem; color:#2c3a4a; line-height:1.8; font-weight:300; margin-bottom:1rem; }
        .cpv-info .note { font-size:.85rem; padding-left:1rem; border-left:3px solid rgba(181,114,42,.3); }
        .cpv-form { background:#fff; border:1px solid var(--ivory3,#e8dfd2); border-radius:8px; padding:1.5rem; box-shadow:0 10px 30px -12px rgba(26,35,50,.15); }
        .cpv-row { display:grid; grid-template-columns:1fr 1fr; gap:.85rem; }
        .cpv-fg { margin-bottom:.9rem; }
        .cpv-fl { display:block; font-family:-apple-system,sans-serif; font-size:.62rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:var(--slate,#1a2332); margin-bottom:.3rem; }
        .cpv-fc { min-height:34px; background:var(--ivory,#faf7f2); border:1px solid var(--ivory3,#e8dfd2); border-radius:5px; padding:.5rem .6rem; font-family:-apple-system,sans-serif; font-size:.72rem; color:var(--muted,#8a8175); line-height:1.4; }
        .cpv-fc.ta { min-height:60px; }
        .cpv-btn { margin-top:.4rem; width:100%; text-align:center; background:var(--copper,#b5722a); color:#fff; font-family:-apple-system,sans-serif; font-size:.7rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; padding:.75rem; border-radius:4px; }
        @media (max-width:640px){ .cpv-section{ grid-template-columns:1fr; } }

        @media (max-width:1100px) {
            .cps-root { flex-direction:column; }
            .cps-panel { width:100%; max-width:none; position:static; max-height:none; }
            .cps-preview { width:100%; height:640px; position:static; }
        }
    </style>

    {{-- ══════════ LEFT: form ══════════ --}}
    <form wire:submit.prevent="save" class="cps-panel">
        <div class="cps-panel-head">
            <h1>Contact Page</h1>
            <p>Header &amp; section copy for the public Contact page.</p>
        </div>

        <div class="cps-body">
            {{-- Header --}}
            <div class="cps-group">
                <div class="cps-group-head">
                    <span class="t">Page Header <small>The hero at the top (H1)</small></span>
                    <button type="button" class="cps-reset"
                            x-on:click="Swal.fire({title:'Reset header?', text:'Restores the original wording.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection('header')})">Reset</button>
                </div>

                <div class="cps-field">
                    <label class="cps-label">Kicker</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="hero_kicker" placeholder="Let's Connect">
                    @error('hero_kicker')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-grid-2">
                    <div class="cps-field">
                        <label class="cps-label">Heading / H1 <span class="req">*</span></label>
                        <input type="text" class="cps-input" wire:model.live.debounce.400ms="hero_title" placeholder="Contact">
                        @error('hero_title')<span class="cps-err">{{ $message }}</span>@enderror
                    </div>
                    <div class="cps-field">
                        <label class="cps-label">Heading <small>(italic)</small></label>
                        <input type="text" class="cps-input" wire:model.live.debounce.400ms="hero_title_em" placeholder="(optional)">
                        @error('hero_title_em')<span class="cps-err">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="cps-field">
                    <label class="cps-label">Subtitle</label>
                    <textarea class="cps-input" wire:model.live.debounce.400ms="hero_subtitle" placeholder="Whether you're facing a specific hardware development challenge..."></textarea>
                    @error('hero_subtitle')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Get in Touch --}}
            <div class="cps-group">
                <div class="cps-group-head">
                    <span class="t">"Get in Touch" Section <small>Beside the form</small></span>
                    <button type="button" class="cps-reset"
                            x-on:click="Swal.fire({title:'Reset this copy?', text:'Restores the original wording.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection('copy')})">Reset</button>
                </div>

                <div class="cps-field">
                    <label class="cps-label">Kicker</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="contact_kicker" placeholder="Get in Touch">
                    @error('contact_kicker')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-grid-2">
                    <div class="cps-field">
                        <label class="cps-label">Heading <small>(regular)</small></label>
                        <input type="text" class="cps-input" wire:model.live.debounce.400ms="contact_title" placeholder="Start the">
                        @error('contact_title')<span class="cps-err">{{ $message }}</span>@enderror
                    </div>
                    <div class="cps-field">
                        <label class="cps-label">Heading <small>(italic)</small></label>
                        <input type="text" class="cps-input" wire:model.live.debounce.400ms="contact_title_em" placeholder="Conversation">
                        @error('contact_title_em')<span class="cps-err">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="cps-field">
                    <label class="cps-label">Intro Paragraph</label>
                    <textarea class="cps-input" wire:model.live.debounce.400ms="contact_body" placeholder="Reach out to discuss your organization's needs..."></textarea>
                    @error('contact_body')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Highlighted Note <small>copper-bordered callout</small></label>
                    <textarea class="cps-input" wire:model.live.debounce.400ms="contact_note" placeholder="Please fill out the form with your details..."></textarea>
                    @error('contact_note')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Submit Button Label</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="contact_submit_text" placeholder="Send Message">
                    @error('contact_submit_text')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Form placeholders --}}
            <div class="cps-group">
                <div class="cps-group-head">
                    <span class="t">Form Placeholders <small>Grey hint text inside each field</small></span>
                    <button type="button" class="cps-reset"
                            x-on:click="Swal.fire({title:'Reset placeholders?', text:'Restores the original hint text.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection('placeholders')})">Reset</button>
                </div>

                <div class="cps-field">
                    <label class="cps-label">Full Name</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="form_name_ph" placeholder="Jane Doe">
                    @error('form_name_ph')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Email Address</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="form_email_ph" placeholder="jane@company.com">
                    @error('form_email_ph')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Subject</label>
                    <input type="text" class="cps-input" wire:model.live.debounce.400ms="form_subject_ph" placeholder="What engineering challenge are you facing?">
                    @error('form_subject_ph')<span class="cps-err">{{ $message }}</span>@enderror
                </div>
                <div class="cps-field">
                    <label class="cps-label">Message</label>
                    <textarea class="cps-input" wire:model.live.debounce.400ms="form_message_ph" placeholder="Briefly describe your organization..."></textarea>
                    @error('form_message_ph')<span class="cps-err">{{ $message }}</span>@enderror
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
            <div class="cps-url">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                {{ str_replace(['https://','http://'], '', route('contact')) }}
            </div>
        </div>
        <div class="cps-statusbar"><span class="cps-pulse"></span> Live Preview — updates as you type</div>

        <div class="cps-scroll">
            <div class="cps-inner">
                {{-- Hero --}}
                <div class="cpv-hero">
                    <div class="k">{{ $hero_kicker ?: "Let's Connect" }}</div>
                    <h1>{{ $hero_title ?: 'Contact' }}@if($hero_title_em) <em>{{ $hero_title_em }}</em>@endif</h1>
                    @if($hero_subtitle)<p>{{ $hero_subtitle }}</p>@endif
                </div>
                <div class="cpv-strip"></div>

                {{-- Get in Touch + mock form --}}
                <div class="cpv-section">
                    <div class="cpv-info">
                        <div class="k">{{ $contact_kicker ?: 'Get in Touch' }}</div>
                        <h2>{{ $contact_title ?: 'Start the' }} <em>{{ $contact_title_em ?: 'Conversation' }}</em></h2>
                        <div class="cpv-ornament"></div>
                        @if($contact_body)<p>{{ $contact_body }}</p>@endif
                        @if($contact_note)<p class="note">{{ $contact_note }}</p>@endif
                    </div>
                    <div class="cpv-form">
                        <div class="cpv-row">
                            <div class="cpv-fg"><span class="cpv-fl">Full Name</span><div class="cpv-fc">{{ $form_name_ph }}</div></div>
                            <div class="cpv-fg"><span class="cpv-fl">Email Address</span><div class="cpv-fc">{{ $form_email_ph }}</div></div>
                        </div>
                        <div class="cpv-fg"><span class="cpv-fl">Subject</span><div class="cpv-fc">{{ $form_subject_ph }}</div></div>
                        <div class="cpv-fg"><span class="cpv-fl">Message</span><div class="cpv-fc ta">{{ $form_message_ph }}</div></div>
                        <div class="cpv-btn">{{ $contact_submit_text ?: 'Send Message' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
