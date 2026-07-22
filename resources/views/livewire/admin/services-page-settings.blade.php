<div class="sps-wrap">
    <style>
        .sps-wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 2rem 5rem; }
        .sps-head { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; margin-bottom:2rem; }
        .sps-head h1 { font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:600; color:var(--slate,#1a2332); line-height:1.1; }
        .sps-head p { font-size:.9rem; color:var(--muted,#8a8175); margin-top:.35rem; max-width:640px; line-height:1.6; }
        .sps-head .view-live { font-size:.75rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:var(--copper,#b5722a); text-decoration:none; white-space:nowrap; }
        .sps-head .view-live:hover { text-decoration:underline; }

        .sps-card { background:#fff; border:1px solid var(--ivory3,#e8dfd2); border-left:3px solid var(--copper,#b5722a); border-radius:10px; padding:1.5rem 1.75rem; margin-bottom:1.75rem; }
        .sps-card-title { display:flex; align-items:center; justify-content:space-between; gap:1rem; font-weight:800; font-size:.8rem; letter-spacing:.05em; text-transform:uppercase; color:var(--slate,#1a2332); margin-bottom:1.25rem; padding-bottom:.9rem; border-bottom:1px solid var(--ivory3,#e8dfd2); }
        .sps-reset { background:none; border:1px solid var(--ivory3,#e8dfd2); color:var(--muted,#8a8175); font-weight:700; font-size:.66rem; letter-spacing:.05em; text-transform:uppercase; padding:.4rem .8rem; border-radius:6px; cursor:pointer; }
        .sps-reset:hover { border-color:var(--copper,#b5722a); color:var(--copper,#b5722a); }

        .sps-field { margin-bottom:1.1rem; }
        .sps-field:last-child { margin-bottom:0; }
        .sps-label { display:block; font-size:.72rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:var(--slate,#1a2332); margin-bottom:.45rem; }
        .sps-input { width:100%; padding:.7rem .85rem; font-size:.9rem; color:var(--slate,#1a2332); background:var(--ivory,#faf7f2); border:1px solid var(--ivory3,#e8dfd2); border-radius:7px; transition:border-color .2s, box-shadow .2s; }
        .sps-input:focus { outline:none; border-color:var(--copper,#b5722a); background:#fff; box-shadow:0 0 0 2px rgba(181,114,42,.15); }
        textarea.sps-input { min-height:96px; resize:vertical; line-height:1.6; }
        .sps-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .sps-err { display:block; color:#ef4444; font-size:.72rem; margin-top:.3rem; }

        .sps-preview { margin-top:1.25rem; padding:1.4rem 1.5rem; background:var(--ivory,#faf7f2); border:1px dashed var(--ivory3,#e8dfd2); border-radius:8px; }
        .sps-preview-tag { font-size:.6rem; font-weight:800; letter-spacing:.15em; text-transform:uppercase; color:var(--muted,#8a8175); margin-bottom:.9rem; }
        .sps-pv-kicker { display:inline-flex; align-items:center; gap:.6rem; font-size:.62rem; font-weight:800; letter-spacing:.2em; text-transform:uppercase; color:var(--copper,#b5722a); margin-bottom:.6rem; }
        .sps-pv-kicker::before { content:''; width:22px; height:1px; background:var(--copper,#b5722a); }
        .sps-pv-title { font-family:'Cormorant Garamond',serif; font-size:1.9rem; font-weight:600; color:var(--slate,#1a2332); line-height:1.1; }
        .sps-pv-title em { font-style:italic; color:#7a4b1f; }
        .sps-pv-body { font-size:.9rem; color:var(--charcoal,#2c3a4a); line-height:1.7; margin-top:.7rem; max-width:60ch; }

        .sps-savebar { position:sticky; bottom:0; display:flex; justify-content:flex-end; gap:1rem; padding:1rem 0 0; margin-top:.5rem; }
        .sps-save { display:inline-flex; align-items:center; gap:.55rem; background:var(--copper,#b5722a); color:#fff; font-weight:800; font-size:.75rem; letter-spacing:.06em; text-transform:uppercase; padding:.85rem 1.9rem; border:none; border-radius:7px; cursor:pointer; transition:background .25s; }
        .sps-save:hover { background:var(--slate,#1a2332); }
        @media (max-width:760px){ .sps-grid-2 { grid-template-columns:1fr; } }
    </style>

    <div class="sps-head">
        <div>
            <h1>Services Page</h1>
            <p>Edit the section headings shown on the public <strong>Consulting &amp; Training</strong> page. The page's main hero title is managed under <em>Page Headers</em>.</p>
        </div>
        <a href="{{ route('services.training') }}" target="_blank" rel="noopener" class="view-live">View live page ↗</a>
    </div>

    <form wire:submit.prevent="save">
        {{-- ── Consulting section ─────────────────────────────── --}}
        <div class="sps-card">
            <div class="sps-card-title">
                <span>Consulting Section</span>
                <button type="button" class="sps-reset"
                        x-on:click="Swal.fire({title:'Reset Consulting header?', text:'Restores the original wording.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection('consulting')})">
                    Reset
                </button>
            </div>

            <div class="sps-field">
                <label class="sps-label">Kicker</label>
                <input type="text" class="sps-input" wire:model.live.debounce.400ms="consulting_kicker" placeholder="Strategic Guidance">
                @error('consulting_kicker')<span class="sps-err">{{ $message }}</span>@enderror
            </div>
            <div class="sps-grid-2">
                <div class="sps-field">
                    <label class="sps-label">Heading (Regular)</label>
                    <input type="text" class="sps-input" wire:model.live.debounce.400ms="consulting_title" placeholder="Consulting">
                    @error('consulting_title')<span class="sps-err">{{ $message }}</span>@enderror
                </div>
                <div class="sps-field">
                    <label class="sps-label">Heading (Italic)</label>
                    <input type="text" class="sps-input" wire:model.live.debounce.400ms="consulting_title_em" placeholder="Services">
                    @error('consulting_title_em')<span class="sps-err">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="sps-field">
                <label class="sps-label">Description</label>
                <textarea class="sps-input" wire:model.live.debounce.400ms="consulting_body" placeholder="We work directly with your organization to..."></textarea>
                @error('consulting_body')<span class="sps-err">{{ $message }}</span>@enderror
            </div>

            <div class="sps-preview">
                <div class="sps-preview-tag">Live preview</div>
                <div class="sps-pv-kicker">{{ $consulting_kicker ?: 'Strategic Guidance' }}</div>
                <div class="sps-pv-title">{{ $consulting_title ?: 'Consulting' }} <em>{{ $consulting_title_em ?: 'Services' }}</em></div>
                <div class="sps-pv-body">{{ $consulting_body }}</div>
            </div>
        </div>

        {{-- ── Training Classes section ───────────────────────── --}}
        <div class="sps-card">
            <div class="sps-card-title">
                <span>Training Classes Section</span>
                <button type="button" class="sps-reset"
                        x-on:click="Swal.fire({title:'Reset Training header?', text:'Restores the original wording.', icon:'question', showCancelButton:true, confirmButtonColor:'#b5722a', confirmButtonText:'Yes, reset'}).then((r)=>{if(r.isConfirmed) $wire.resetSection('training')})">
                    Reset
                </button>
            </div>

            <div class="sps-field">
                <label class="sps-label">Kicker</label>
                <input type="text" class="sps-input" wire:model.live.debounce.400ms="training_kicker" placeholder="Education & Growth">
                @error('training_kicker')<span class="sps-err">{{ $message }}</span>@enderror
            </div>
            <div class="sps-grid-2">
                <div class="sps-field">
                    <label class="sps-label">Heading (Regular)</label>
                    <input type="text" class="sps-input" wire:model.live.debounce.400ms="training_title" placeholder="Training">
                    @error('training_title')<span class="sps-err">{{ $message }}</span>@enderror
                </div>
                <div class="sps-field">
                    <label class="sps-label">Heading (Italic)</label>
                    <input type="text" class="sps-input" wire:model.live.debounce.400ms="training_title_em" placeholder="Classes">
                    @error('training_title_em')<span class="sps-err">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="sps-field">
                <label class="sps-label">Description</label>
                <textarea class="sps-input" wire:model.live.debounce.400ms="training_body" placeholder="The following classes and presentations are available..."></textarea>
                @error('training_body')<span class="sps-err">{{ $message }}</span>@enderror
            </div>

            <div class="sps-preview">
                <div class="sps-preview-tag">Live preview</div>
                <div class="sps-pv-kicker">{{ $training_kicker ?: 'Education & Growth' }}</div>
                <div class="sps-pv-title">{{ $training_title ?: 'Training' }} <em>{{ $training_title_em ?: 'Classes' }}</em></div>
                <div class="sps-pv-body">{{ $training_body }}</div>
            </div>
        </div>

        <div class="sps-savebar">
            <button type="submit" class="sps-save">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
