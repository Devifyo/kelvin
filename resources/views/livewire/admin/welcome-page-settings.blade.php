<div class="wps-root">
    <link href="{{ asset('css/admin/profile-settings.css') }}" rel="stylesheet">
    <style>
        /* ================================================================
           WelcomePageSettings — Professional CMS Split-Panel UI
        ================================================================ */

        .wps-root {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* ── LEFT CONTROL PANEL ─────────────────────────────────────── */
        .wps-panel {
            width: 400px;
            min-width: 360px;
            max-width: 440px;
            position: sticky;
            top: 100px;
            max-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid var(--ivory3);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px -8px rgba(26, 35, 50, 0.1);
        }

        .wps-panel-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--ivory3);
            background: var(--ivory);
            flex-shrink: 0;
        }

        .wps-panel-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--copper) 0%, var(--copper2) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .wps-panel-icon svg { width: 18px; height: 18px; stroke: #fff; }

        .wps-panel-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--slate);
            line-height: 1.2;
        }
        .wps-panel-subtitle {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 0.15rem;
        }

        /* ── TAB NAVIGATION ─────────────────────────────────────────── */
        .wps-tabs {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-bottom: 1px solid var(--ivory3);
            background: var(--white);
            flex-shrink: 0;
        }

        .wps-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            padding: 0.75rem 0.25rem 0.65rem;
            background: transparent;
            border: none;
            border-bottom: 2.5px solid transparent;
            color: var(--muted);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            cursor: pointer;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .wps-tab svg { width: 15px; height: 15px; stroke-width: 2; }
        .wps-tab:hover { color: var(--slate); background: var(--ivory); }
        .wps-tab.active {
            color: var(--copper);
            border-bottom-color: var(--copper);
            background: rgba(181, 114, 42, 0.04);
        }

        /* ── FORM BODY (SCROLLABLE) ─────────────────────────────────── */
        .wps-form { display: flex; flex-direction: column; flex: 1; min-height: 0; }

        .wps-form-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.4rem 1.4rem 1rem;
        }
        .wps-form-body::-webkit-scrollbar { width: 4px; }
        .wps-form-body::-webkit-scrollbar-track { background: transparent; }
        .wps-form-body::-webkit-scrollbar-thumb { background: var(--ivory3); border-radius: 4px; }

        /* Section label dividers */
        .wps-section-label {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--copper);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .wps-section-label::after { content: ''; flex: 1; height: 1px; background: var(--ivory3); }

        .wps-subsection {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--muted);
            margin: 1.4rem 0 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .wps-subsection::after { content: ''; flex: 1; height: 1px; background: var(--ivory3); }

        /* ── FORM FIELDS ────────────────────────────────────────────── */
        .wps-field { margin-bottom: 1.1rem; }
        .wps-field:last-child { margin-bottom: 0; }

        .wps-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--charcoal);
            margin-bottom: 0.35rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .wps-input {
            width: 100%;
            padding: 0.7rem 0.875rem;
            background: var(--ivory);
            border: 1.5px solid var(--ivory3);
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--charcoal);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
            line-height: 1.5;
        }
        .wps-input:focus {
            outline: none;
            border-color: var(--copper);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(181, 114, 42, 0.1);
        }
        .wps-input::placeholder { color: var(--muted); opacity: 0.55; }
        textarea.wps-input { min-height: 88px; resize: vertical; }

        .wps-hint {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 0.3rem;
            line-height: 1.4;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .wps-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }

        /* ── SAVE BAR ───────────────────────────────────────────────── */
        .wps-save-bar {
            padding: 0.9rem 1.4rem;
            border-top: 1px solid var(--ivory3);
            background: var(--white);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-shrink: 0;
        }

        .wps-save-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.25rem;
            background: var(--slate);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .wps-save-btn:hover {
            background: var(--copper);
            box-shadow: 0 4px 16px rgba(181, 114, 42, 0.3);
            transform: translateY(-1px);
        }
        .wps-save-btn:active { transform: translateY(0); }
        .wps-save-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .wps-save-btn svg { width: 15px; height: 15px; stroke-width: 2; }

        .wps-saved-badge {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: #059669;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 6px;
            padding: 0.35rem 0.65rem;
            white-space: nowrap;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .wps-saved-badge svg { width: 12px; height: 12px; stroke-width: 3; }

        /* ── RIGHT PREVIEW PANE ─────────────────────────────────────── */
        .wps-preview {
            flex: 1;
            position: sticky;
            top: 100px;
            height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid var(--ivory3);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px -8px rgba(26, 35, 50, 0.1);
        }

        /* Browser Chrome Bar */
        .wps-chrome {
            background: #f0ece5;
            border-bottom: 1px solid #e0d8ce;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-shrink: 0;
        }

        .wps-chrome-dots { display: flex; gap: 0.4rem; align-items: center; flex-shrink: 0; }
        .wps-chrome-dots span { width: 11px; height: 11px; border-radius: 50%; display: block; }
        .wps-dot-r { background: #ff5f57; }
        .wps-dot-y { background: #febc2e; }
        .wps-dot-g { background: #28c840; }

        .wps-chrome-url {
            flex: 1;
            min-width: 0;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(0,0,0,0.09);
            border-radius: 7px;
            padding: 0.32rem 0.75rem;
            font-size: 0.72rem;
            color: var(--muted);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .wps-chrome-url svg { width: 11px; height: 11px; flex-shrink: 0; color: #10b981; }

        /* Preview Status Bar */
        .wps-preview-statusbar {
            background: var(--ivory);
            border-bottom: 1px solid var(--ivory3);
            padding: 0.45rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .wps-live-badge {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .wps-pulse {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: wpsPulse 2.2s ease-in-out infinite;
        }
        @keyframes wpsPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(0.8); }
        }

        .wps-zoom-label {
            font-size: 0.68rem;
            color: var(--muted);
            font-weight: 600;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .wps-zoom-label svg { width: 12px; height: 12px; }

        /* Preview Scroll Container */
        .wps-preview-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            background: #ddd8d0;
            position: relative;
        }
        .wps-preview-scroll::-webkit-scrollbar { width: 6px; }
        .wps-preview-scroll::-webkit-scrollbar-track { background: transparent; }
        .wps-preview-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.18); border-radius: 3px; }

        .wps-preview-inner {
            zoom: 0.82;
            width: 100%;
            background: var(--white);
            transform-origin: top left;
        }

        /* ── RESPONSIVE ─────────────────────────────────────────────── */
        @media (max-width: 1100px) {
            .wps-root { flex-direction: column; }
            .wps-panel { width: 100%; max-width: none; position: static; max-height: none; }
            .wps-preview { width: 100%; height: 600px; position: static; }
        }
    </style>

    <!-- ════════════════════════════════════════════════════════════════
         LEFT: CONTROL PANEL
    ════════════════════════════════════════════════════════════════ -->
    <div class="wps-panel">

        <!-- Panel Header -->
        <div class="wps-panel-header">
            <div class="wps-panel-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                </svg>
            </div>
            <div>
                <div class="wps-panel-title">Welcome Page</div>
                <div class="wps-panel-subtitle">Landing page content editor</div>
            </div>
        </div>

        <!-- Section Tab Navigation -->
        <div class="wps-tabs">
            <button type="button" wire:click="setTab('hero')" class="wps-tab {{ $tab === 'hero' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                Hero
            </button>
            <button type="button" wire:click="setTab('pain')" class="wps-tab {{ $tab === 'pain' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Pain
            </button>
            <button type="button" wire:click="setTab('bio')" class="wps-tab {{ $tab === 'bio' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Bio
            </button>
            <button type="button" wire:click="setTab('seo')" class="wps-tab {{ $tab === 'seo' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                SEO
            </button>
            <button type="button" wire:click="setTab('clients')" class="wps-tab {{ $tab === 'clients' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Clients
            </button>
            <button type="button" wire:click="setTab('faq')" class="wps-tab {{ $tab === 'faq' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                FAQ
            </button>
        </div>

        <!-- Form -->
        <form wire:submit="save" class="wps-form">
            <div class="wps-form-body">

                <!-- ─── HERO TAB ─────────────────────────────── -->
                @if($tab === 'hero')
                <div wire:key="tab-hero">
                    <div class="wps-section-label">Hero Section</div>

                    <div class="wps-field">
                        <label class="wps-label">Kicker / Eyebrow</label>
                        <input type="text" wire:model.live.debounce.500ms="hero_kicker" class="wps-input" placeholder="e.g. Agile Hardware Consulting">
                        <div class="wps-hint">Short tagline displayed above the main headline.</div>
                    </div>

                    <div class="wps-subsection">Headline</div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">Italic Part</label>
                            <input type="text" wire:model.live.debounce.500ms="hero_h1_em" class="wps-input" placeholder="Reduce risk.">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">Bold Part</label>
                            <input type="text" wire:model.live.debounce.500ms="hero_h1_strong" class="wps-input" placeholder="Ship faster.">
                        </div>
                    </div>

                    <div class="wps-subsection">Body Text</div>

                    <div class="wps-field">
                        <label class="wps-label">First Paragraph</label>
                        <textarea wire:model.live.debounce.500ms="hero_p1" class="wps-input" placeholder="Opening statement..."></textarea>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Second Paragraph</label>
                        <textarea wire:model.live.debounce.500ms="hero_p2" class="wps-input" placeholder="Supporting context..."></textarea>
                    </div>

                    <div class="wps-subsection">Call to Action</div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">Primary Text</label>
                            <input type="text" wire:model.live.debounce.500ms="hero_cta_primary_text" class="wps-input" placeholder="Our Services">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">Primary Link</label>
                            <input type="text" wire:model.live.debounce.500ms="hero_cta_primary_link" class="wps-input" placeholder="/services">
                        </div>
                    </div>

                    <div class="wps-grid-2">
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">Secondary Text</label>
                            <input type="text" wire:model.live.debounce.500ms="hero_cta_secondary_text" class="wps-input" placeholder="Get in Touch">
                        </div>
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">Secondary Link</label>
                            <input type="text" wire:model.live.debounce.500ms="hero_cta_secondary_link" class="wps-input" placeholder="/contact">
                        </div>
                    </div>
                </div>
                @endif

                <!-- ─── PAIN TAB ─────────────────────────────── -->
                @if($tab === 'pain')
                <div wire:key="tab-pain">
                    <div class="wps-section-label">Pain Points Column</div>

                    <div class="wps-field">
                        <label class="wps-label">Section Title</label>
                        <input type="text" wire:model.live.debounce.500ms="pain_title" class="wps-input" placeholder="If your organization is experiencing...">
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Pain Points List</label>
                        <textarea wire:model.live.debounce.500ms="pain_list_string" class="wps-input" style="min-height: 180px;" placeholder="One pain point per line&#10;Each line becomes a numbered item&#10;Add as many as needed"></textarea>
                        <div class="wps-hint">Each line is automatically rendered as a numbered list item.</div>
                    </div>

                    <div class="wps-field" style="margin-bottom: 0;">
                        <label class="wps-label">Footer Phrase</label>
                        <input type="text" wire:model.live.debounce.500ms="pain_footer" class="wps-input" placeholder="...we can help.">
                        <div class="wps-hint">Closing statement shown below the list with a decorative line.</div>
                    </div>
                </div>
                @endif

                <!-- ─── BIO TAB ──────────────────────────────── -->
                @if($tab === 'bio')
                <div wire:key="tab-bio">
                    <div class="wps-section-label">Principal Portrait</div>

                    <div class="wps-field">
                        <label class="wps-label" style="display:flex; align-items:center; gap:0.6rem; cursor:pointer;">
                            <input type="checkbox" wire:model.live="principal_portrait_enabled" style="width:18px; height:18px; accent-color:var(--copper);">
                            Show the portrait photo on the homepage
                        </label>
                        <div class="wps-hint">Turn off to hide the photo and show just the name &amp; bio.</div>
                    </div>

                    <div class="wps-field" style="opacity: {{ $principal_portrait_enabled ? '1' : '0.5' }};">
                        <label class="wps-label">Portrait Photo</label>
                        <div style="display:flex; gap:1.25rem; align-items:flex-start; flex-wrap:wrap;">
                            {{-- Live thumbnail: uploaded preview → saved upload → default headshot --}}
                            <div style="width:104px; height:130px; border:1px solid var(--ivory3); border-radius:8px; overflow:hidden; background:var(--ivory); flex-shrink:0; position:relative;">
                                <div wire:loading.flex wire:target="principal_portrait_upload" style="position:absolute; inset:0; align-items:center; justify-content:center; background:rgba(255,255,255,.7); font-size:.7rem; color:var(--muted);">Uploading…</div>
                                @if($principal_portrait_upload)
                                    <img src="{{ $principal_portrait_upload->temporaryUrl() }}" style="width:100%; height:100%; object-fit:cover;">
                                @elseif($principal_portrait_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($principal_portrait_image) }}" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <img src="{{ asset('img/frontend/Dr.%20Kevin%20Thompson.webp') }}" style="width:100%; height:100%; object-fit:cover;">
                                @endif
                            </div>
                            <div style="flex:1; min-width:220px;">
                                <input type="file" wire:model="principal_portrait_upload" accept="image/*" class="wps-input" style="padding:0.5rem;">
                                @error('principal_portrait_upload')<div class="wps-hint" style="color:#dc2626;">{{ $message }}</div>@enderror
                                <div class="wps-hint">JPG/PNG/WebP, portrait orientation works best. Max 2MB. Auto-optimised on upload.</div>
                                @if($principal_portrait_image)
                                    <button type="button"
                                            @click="Swal.fire({ title: 'Reset to the default photo?', text: 'The uploaded portrait will be removed.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, reset', cancelButtonText: 'Cancel', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.removePortrait())"
                                            style="margin-top:.5rem; background:none; border:none; color:#dc2626; font-size:.78rem; font-weight:600; cursor:pointer; padding:0;">
                                        Reset to default photo
                                    </button>
                                @else
                                    <div class="wps-hint" style="margin-top:.4rem; font-style:italic;">Using the default headshot.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="wps-section-label">Principal Bio</div>

                    <div class="wps-field">
                        <label class="wps-label">Kicker Title</label>
                        <input type="text" wire:model.live.debounce.500ms="principal_kicker" class="wps-input" placeholder="Our Principal">
                    </div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">Name (Regular)</label>
                            <input type="text" wire:model.live.debounce.500ms="principal_h2_name" class="wps-input" placeholder="Dr. Kevin">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">Name (Italic)</label>
                            <input type="text" wire:model.live.debounce.500ms="principal_h2_em" class="wps-input" placeholder="Thompson">
                        </div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Bio Paragraph 1</label>
                        <textarea wire:model.live.debounce.500ms="principal_p1" class="wps-input"></textarea>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Bio Paragraph 2</label>
                        <textarea wire:model.live.debounce.500ms="principal_p2" class="wps-input"></textarea>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">
                            Bio Paragraph 3
                            <span style="font-weight:400; color:var(--muted); text-transform:none; letter-spacing:0;">&nbsp;(HTML allowed)</span>
                        </label>
                        <textarea wire:model.live.debounce.500ms="principal_p3" class="wps-input"></textarea>
                    </div>

                    <div class="wps-subsection">Associated Book</div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">Cover Image URL</label>
                            <input type="text" wire:model.live.debounce.500ms="principal_book_image" class="wps-input" placeholder="https://...">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">Amazon URL</label>
                            <input type="text" wire:model.live.debounce.500ms="principal_book_url" class="wps-input" placeholder="https://amazon.com/...">
                        </div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Book Title</label>
                        <input type="text" wire:model.live.debounce.500ms="principal_book_title" class="wps-input">
                    </div>

                    <div class="wps-field" style="margin-bottom: 0;">
                        <label class="wps-label">Book Description / Tagline</label>
                        <textarea wire:model.live.debounce.500ms="principal_book_desc" class="wps-input" style="min-height: 68px;"></textarea>
                    </div>
                </div>
                @endif

                <!-- ─── SEO TAB ──────────────────────────────── -->
                @if($tab === 'seo')
                <div wire:key="tab-seo">
                    <div class="wps-section-label">SEO Settings</div>

                    <div class="wps-field">
                        <label class="wps-label">Meta Title</label>
                        <input type="text" wire:model.live.debounce.500ms="seo_title" class="wps-input" placeholder="Kevin Thompson Ph.D. | Hardware Consulting">
                        <div class="wps-hint">Shown in browser tabs and Google results. Aim for 50–60 characters.</div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Meta Description</label>
                        <textarea wire:model.live.debounce.500ms="seo_description" class="wps-input" placeholder="A brief, compelling description of the page..."></textarea>
                        <div class="wps-hint">Displayed in search result snippets. Aim for 120–160 characters.</div>
                    </div>

                    <div class="wps-field" style="margin-bottom: 0;">
                        <label class="wps-label">Meta Keywords</label>
                        <input type="text" wire:model.live.debounce.500ms="seo_keywords" class="wps-input" placeholder="hardware consulting, agile, product development">
                        <div class="wps-hint">Comma-separated. Less critical for modern SEO, but still read by some tools.</div>
                    </div>
                </div>
                @endif

                <!-- ─── CLIENTS / TRUSTED BY TAB ─────────────── -->
                @if($tab === 'clients')
                <div wire:key="tab-clients">
                    <div class="wps-section-label">Trusted By Section</div>

                    <div class="wps-field">
                        <label class="wps-label" style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                            <input type="checkbox" wire:model.live="trusted_enabled" style="width: 18px; height: 18px; accent-color: var(--copper);">
                            Show the "Trusted By" section on the homepage
                        </label>
                        <div class="wps-hint">When enabled, featured clients appear in a logo strip near the bottom of the homepage. Manage which clients are featured under <strong>Client Showcase</strong>.</div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Kicker / Eyebrow</label>
                        <input type="text" wire:model.live.debounce.500ms="trusted_kicker" class="wps-input" placeholder="Our Clients">
                        <div class="wps-hint">Short label shown above the heading.</div>
                    </div>

                    <div class="wps-subsection">Heading</div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">Bold Part</label>
                            <input type="text" wire:model.live.debounce.500ms="trusted_title" class="wps-input" placeholder="Trusted By">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">Italic Part</label>
                            <input type="text" wire:model.live.debounce.500ms="trusted_title_em" class="wps-input" placeholder="Industry Leaders">
                        </div>
                    </div>

                    <div class="wps-subsection">Footer</div>

                    <div class="wps-grid-2">
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">Count Label</label>
                            <input type="text" wire:model.live.debounce.500ms="trusted_count_label" class="wps-input" placeholder="Clients Served">
                            <div class="wps-hint">The number (e.g. "70+") is counted automatically from active clients.</div>
                        </div>
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">Button Text</label>
                            <input type="text" wire:model.live.debounce.500ms="trusted_cta_text" class="wps-input" placeholder="View All Clients">
                            <div class="wps-hint">Links to the public <strong>/clients</strong> page.</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ─── FAQ TAB ──────────────────────────────── -->
                @if($tab === 'faq')
                <div wire:key="tab-faq">
                    <div class="wps-section-label">FAQ Section</div>

                    <div class="wps-field">
                        <label class="wps-label" style="display:flex; align-items:center; gap:0.6rem; cursor:pointer;">
                            <input type="checkbox" wire:model.live="faq_enabled" style="width:18px; height:18px; accent-color:var(--copper);">
                            Show the FAQ section on the homepage
                        </label>
                        <div class="wps-hint">Appears near the bottom of the homepage and powers FAQ structured data (helps AI search &amp; Google understand your answers).</div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Kicker</label>
                        <input type="text" wire:model.live.debounce.500ms="faq_kicker" class="wps-input" placeholder="Common Questions">
                    </div>
                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">Heading (bold)</label>
                            <input type="text" wire:model.live.debounce.500ms="faq_title" class="wps-input" placeholder="Frequently Asked">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">Heading (italic)</label>
                            <input type="text" wire:model.live.debounce.500ms="faq_title_em" class="wps-input" placeholder="Questions">
                        </div>
                    </div>

                    <div class="wps-subsection">Questions &amp; Answers</div>
                    <div class="wps-hint" style="margin-bottom:1rem;">
                        @if(empty($faq_items))
                            No questions yet — add some below. With none, the FAQ section is hidden on the homepage.
                        @else
                            Drag the <span style="vertical-align:middle;">⠿</span> handle to reorder questions, then Save.
                        @endif
                    </div>
                    <style>
                        .faq-drag-handle:active { cursor:grabbing; }
                        .faq-drag-ghost { opacity:.4; background:var(--ivory) !important; }
                    </style>

                    <div id="sortable-faqs"
                         x-data
                         x-init="if (typeof Sortable !== 'undefined') {
                             Sortable.create($el, {
                                 animation: 150,
                                 handle: '.faq-drag-handle',
                                 ghostClass: 'faq-drag-ghost',
                                 onEnd() {
                                     const ids = Array.from($el.children).map(el => el.getAttribute('data-id'));
                                     $wire.reorderFaqItems(ids);
                                 }
                             });
                         }">
                        @foreach($faq_items as $i => $item)
                            <div wire:key="faq-{{ $item['_id'] ?? $i }}" data-id="{{ $item['_id'] ?? $i }}" style="border:1px solid var(--ivory3); border-radius:10px; padding:1rem 1.1rem; margin-bottom:0.9rem; background:#fff;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                                    <div style="display:flex; align-items:center; gap:.55rem;">
                                        <span class="faq-drag-handle" title="Drag to reorder" style="cursor:grab; color:var(--muted); display:flex; align-items:center; touch-action:none;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                                        </span>
                                        <strong style="font-size:.78rem; color:var(--muted); letter-spacing:.05em;">Q{{ $i + 1 }}</strong>
                                    </div>
                                    <button type="button" wire:click="removeFaqItem({{ $i }})" style="background:none; border:none; color:#dc2626; font-size:.75rem; font-weight:700; cursor:pointer;">Remove</button>
                                </div>
                                <div class="wps-field" style="margin-bottom:.6rem;">
                                    <input type="text" wire:model.live.debounce.500ms="faq_items.{{ $i }}.q" class="wps-input" placeholder="Question…">
                                </div>
                                <div class="wps-field" style="margin-bottom:0;">
                                    <textarea wire:model.live.debounce.500ms="faq_items.{{ $i }}.a" class="wps-input" style="min-height:80px;" placeholder="Answer (2–4 sentences, answer first)…"></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" wire:click="addFaqItem" class="wps-input" style="cursor:pointer; text-align:center; font-weight:700; color:var(--copper); background:var(--ivory); border-style:dashed;">
                        + Add question
                    </button>
                </div>
                @endif

            </div>{{-- /wps-form-body --}}

            <!-- Sticky Save Footer -->
            <div class="wps-save-bar">
                @if(session()->has('success'))
                <div class="wps-saved-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>
                    Saved
                </div>
                @endif
                <button type="submit" class="wps-save-btn" wire:loading.attr="disabled" wire:target="save">
                    <svg wire:loading.remove wire:target="save" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ════════════════════════════════════════════════════════════════
         RIGHT: LIVE PREVIEW PANE
    ════════════════════════════════════════════════════════════════ -->
    <div class="wps-preview">

        <!-- Browser Chrome -->
        <div class="wps-chrome">
            <div class="wps-chrome-dots">
                <span class="wps-dot-r"></span>
                <span class="wps-dot-y"></span>
                <span class="wps-dot-g"></span>
            </div>
            <div class="wps-chrome-url">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                {{config('app.url')}}
            </div>
        </div>

        <!-- Preview Status Bar -->
        <div class="wps-preview-statusbar">
            <div class="wps-live-badge">
                <span class="wps-pulse"></span>
                Live Preview — updates as you type
            </div>
        </div>

        <!-- Scrollable Preview -->
        <div class="wps-preview-scroll">
            <div class="wps-preview-inner">
                <link rel="stylesheet" href="/css/frontend/home.css">
                <style>
                    /* ── Disable hover transforms in preview ── */
                    .cta-primary:hover, .cta-secondary:hover, .svc-item:hover { transform: none !important; }

                    /* ── 1. HERO ── */
                    #hero { background: var(--slate) !important; }
                    .hero-accent-line, .hero-dots, .hero-numeral, .hero-glow { display: none !important; }
                    #hero::before { display: none !important; }

                    .elite-kicker {
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, sans-serif;
                        font-size: 0.7rem; font-weight: 600; letter-spacing: 0.25em; text-transform: uppercase;
                        color: var(--copper); margin-bottom: 2.5rem;
                        display: flex; align-items: center; gap: 1.5rem;
                    }
                    .elite-kicker::before { content: ''; width: 50px; height: 1px; background: var(--copper); }

                    .hero-h1 {
                        font-family: 'Cormorant Garamond', serif;
                        font-size: clamp(3.5rem, 6vw, 6.5rem); line-height: 1.05;
                        letter-spacing: -0.02em; margin-bottom: 2rem;
                    }
                    .hero-h1 em { color: var(--copper2); font-style: italic; font-weight: 400; display: block; }
                    .hero-h1 strong { color: var(--white); font-weight: 600; background: none !important; -webkit-text-fill-color: initial !important; display: block; }

                    .hero-p, .hero-p2 { color: rgba(250, 247, 242, 0.75) !important; font-weight: 400 !important; font-size: 1.05rem; line-height: 1.8; max-width: 480px; }

                    .cta-primary { border-radius: 0 !important; background: var(--copper) !important; box-shadow: none !important; letter-spacing: 0.15em; }
                    .cta-primary:hover { background: var(--white) !important; color: var(--slate) !important; }
                    .cta-secondary { border-radius: 0 !important; border: 1px solid rgba(255,255,255,0.2) !important; color: rgba(255,255,255,0.8) !important; }
                    .cta-secondary:hover { border-color: var(--copper) !important; color: var(--white) !important; background: rgba(181,114,42,0.05) !important; }

                    .elite-card { background: transparent; border-left: 1px solid rgba(250,247,242,0.15); padding: 0 0 0 3.5rem; width: 100%; max-width: 460px; }
                    .elite-card-title { font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; color: var(--white); margin-bottom: 2.5rem; line-height: 1.2; font-weight: 400; }
                    .elite-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; }
                    .elite-list li { display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.25rem 0; border-bottom: 1px solid rgba(250,247,242,0.08); color: rgba(250,247,242,0.7); font-family: -apple-system, sans-serif; font-size: 0.9rem; line-height: 1.6; transition: color 0.3s; }
                    .elite-list li:first-child { border-top: 1px solid rgba(250,247,242,0.08); }
                    .elite-list li:hover { color: var(--white); }
                    .elite-num { font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; color: var(--copper); font-style: italic; line-height: 1; padding-top: 0.1rem; }
                    .elite-foot { margin-top: 2.5rem; font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--copper2); font-style: italic; display: flex; align-items: center; gap: 1rem; }
                    .elite-foot::after { content: ''; flex-grow: 1; height: 1px; background: linear-gradient(90deg, var(--copper), transparent); }
                    @media(max-width: 1100px) { .elite-card { border-left: none; border-top: 1px solid rgba(250,247,242,0.15); padding: 3.5rem 0 0 0; } }

                    /* ── 2. SERVICE HEADINGS ── */
                    .svc-group-title {
                        font-family: -apple-system, sans-serif !important; font-size: 0.8rem !important; font-weight: 700 !important;
                        letter-spacing: 0.25em !important; text-transform: uppercase !important; color: var(--copper) !important;
                        margin: 0 0 1.5rem 0 !important; display: flex !important; align-items: center !important; gap: 1.25rem !important;
                    }
                    .svc-group-title::after { content: '' !important; flex-grow: 1 !important; height: 1px !important; background: var(--copper) !important; opacity: 0.3 !important; }

                    /* ── 3. BIO COLUMN ── */
                    .principal-wrap { align-items: stretch !important; }
                    .bio { position: relative; top: 0; align-self: start; }

                    /* ── 4. FOOTER ── */
                    footer { background: var(--slate); padding: 2.75rem 4.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; border-top: 1px solid rgba(181,114,42,.12); }
                    .footer-logo { display: flex; align-items: center; gap: .65rem; }
                    .footer-logo-mark { width: 28px; height: 28px; border: 1px solid rgba(181,114,42,.3); display: flex; align-items: center; justify-content: center; font-family: -apple-system,sans-serif; font-size: .55rem; font-weight: 900; letter-spacing: .05em; color: var(--copper2); }
                    .footer-name { font-size: .92rem; font-weight: 700; color: var(--white); }
                    .footer-name span { color: var(--copper2); }
                    .footer-copy { font-family: -apple-system,sans-serif; font-size: .68rem; color: rgba(250,247,242,.5); }
                    .footer-links { display: flex; gap: 2rem; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; }
                    .footer-links a { font-family: -apple-system,sans-serif; font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; color: rgba(250,247,242,.6); text-decoration: none; }
                    .footer-links a:hover { color: var(--copper3); }
                </style>

                @php
                    $painPoints = array_filter(array_map('trim', explode("\n", $pain_list_string)));
                    if (empty($painPoints)) { $painPoints = ['Example problem 1.', 'Example problem 2.']; }
                @endphp

                <!-- HERO BLOCK -->
                <section id="hero">
                    <div class="hero-l">
                        <div class="elite-kicker anim-up d0">{{ $hero_kicker ?: 'Agile Hardware Consulting' }}</div>
                        <h1 class="hero-h1 anim-up d1">
                            <em>{{ $hero_h1_em ?: 'Reduce risk.' }}</em>
                            <strong>{{ $hero_h1_strong ?: 'Ship faster.' }}</strong>
                        </h1>
                        <p class="hero-p anim-up d2">{{ $hero_p1 ?: 'We help hardware-development organizations...' }}</p>
                        <p class="hero-p2 anim-up d3">{{ $hero_p2 ?: 'We understand how hardware development differs...' }}</p>
                        <div class="cta-row anim-up d4">
                            <a href="#" class="cta-primary">{{ $hero_cta_primary_text ?: 'Our Services' }}</a>
                            <a href="#" class="cta-secondary">{{ $hero_cta_secondary_text ?: 'Get in Touch' }}</a>
                        </div>
                    </div>
                    <div class="hero-r">
                        <div class="elite-card anim-right" style="opacity:1; transform:none; animation:none;">
                            <h3 class="elite-card-title">{{ $pain_title ?: 'If your organization is experiencing...' }}</h3>
                            <ul class="elite-list">
                                @foreach($painPoints as $index => $point)
                                <li>
                                    <span class="elite-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span>{{ $point }}</span>
                                </li>
                                @endforeach
                            </ul>
                            <div class="elite-foot">{{ $pain_footer ?: '...we can help.' }}</div>
                        </div>
                    </div>
                </section>
                <div class="strip"></div>

                <!-- PRINCIPAL BIO BLOCK -->
                <section id="principal" style="background: var(--white); padding: 8rem 4.5rem;">
                    <div class="principal-wrap">
                        <div class="bio reveal in" style="opacity:1; transform:none;">
                            @if($principal_portrait_enabled)
                                @php
                                    $previewPortrait = $principal_portrait_upload
                                        ? $principal_portrait_upload->temporaryUrl()
                                        : ($principal_portrait_image
                                            ? \Illuminate\Support\Facades\Storage::url($principal_portrait_image)
                                            : asset('img/frontend/Dr.%20Kevin%20Thompson.webp'));
                                @endphp
                                <div class="bio-head">
                                    <figure class="bio-portrait"><img src="{{ $previewPortrait }}" alt="Dr. Kevin Thompson" width="320" height="400"></figure>
                                    <div class="bio-head-text">
                                        <div class="kicker">{{ $principal_kicker ?: 'Our Principal' }}</div>
                                        <h2 class="section-h">{{ $principal_h2_name ?: 'Dr. Kevin' }} <em>{{ $principal_h2_em ?: 'Thompson' }}</em></h2>
                                    </div>
                                </div>
                            @else
                            <div class="kicker">{{ $principal_kicker ?: 'Our Principal' }}</div>
                            <h2 class="section-h">{{ $principal_h2_name ?: 'Dr. Kevin' }} <em>{{ $principal_h2_em ?: 'Thompson' }}</em></h2>
                            @endif
                            <div class="ornament"></div>
                            <p>{!! nl2br(e($principal_p1)) !!}</p>
                            <p>{!! nl2br(e($principal_p2)) !!}</p>
                            <p>{!! $principal_p3 !!}</p>
                            <div class="book">
                                <div class="book-img"><img src="{{ $principal_book_image }}" alt="Book"></div>
                                <div class="book-details">
                                    <strong>{{ $principal_book_title }}</strong>
                                    <p>{{ $principal_book_desc }}</p>
                                    <a href="#" class="book-link">View on Amazon <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
                                </div>
                            </div>
                        </div>
                        <div class="reveal in" style="opacity:1; transform:none;">
                            <div class="kicker">What We Offer</div>
                            <h2 class="section-h">Consulting &amp; <em>Training Services</em></h2>
                            <div class="ornament"></div>
                            <p class="svc-desc">We offer a variety of consulting and training services. We can work with all levels at a client, from the hands-on engineers to the C-suite.</p>
                            <div class="svc-group">
                                <h3 class="svc-group-title">Consulting</h3>
                                <div class="svc-grid">
                                    @forelse ($consultingServices ?? [] as $service)
                                    <div class="svc-item"><span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="svc-name">{{ $service->title }}</span></div>
                                    @empty
                                    <p style="font-size:0.8rem; color:var(--muted)">Consulting services list.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="svc-group" style="margin-top: 2.5rem;">
                                <h3 class="svc-group-title">Training</h3>
                                <div class="svc-grid">
                                    @forelse ($trainingClasses ?? [] as $training)
                                    <div class="svc-item"><span class="svc-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="svc-name">{{ $training->title }}</span></div>
                                    @empty
                                    <p style="font-size:0.8rem; color:var(--muted)">Training classes list.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- TRUSTED BY / CLIENTS BLOCK -->
                @if($trusted_enabled && ($featuredClients ?? collect())->isNotEmpty())
                <section style="background: var(--ivory); padding: 5rem 4.5rem; border-top: 1px solid var(--ivory3); text-align: center;">
                    <div style="max-width: 1180px; margin: 0 auto;">
                        <div class="kicker" style="justify-content: center;">{{ $trusted_kicker ?: 'Our Clients' }}</div>
                        <h2 class="section-h" style="margin-bottom: 2.5rem;">{{ $trusted_title ?: 'Trusted By' }} <em>{{ $trusted_title_em ?: 'Industry Leaders' }}</em></h2>
                        <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; align-items: center;">
                            @foreach($featuredClients as $client)
                            <div style="display: flex; align-items: center; justify-content: center; height: 70px; padding: 0.75rem; background: var(--white); border: 1px solid var(--ivory3); border-radius: 8px;">
                                @if($client->logo_url)
                                    <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                @else
                                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--muted);">{{ $client->name }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div style="margin-top: 2.5rem; font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; color: var(--slate);">
                            <strong style="color: var(--copper);">{{ ($clientsCount ?? 0) >= 10 ? (floor(($clientsCount ?? 0) / 10) * 10) . '+' : ($clientsCount ?? 0) }}</strong>
                            {{ $trusted_count_label ?: 'Clients Served' }}
                        </div>
                        <a href="#" class="cta-primary" style="display: inline-block; margin-top: 1.5rem;">{{ $trusted_cta_text ?: 'View All Clients' }}</a>
                    </div>
                </section>
                @endif

                <!-- FAQ BLOCK -->
                @if($faq_enabled)
                @php
                    $previewFaqs = collect($faq_items)->filter(fn ($i) => trim($i['q'] ?? '') !== '')->values()->all();
                @endphp
                <section style="background: var(--ivory); padding: 4rem 4.5rem; border-top: 1px solid var(--ivory3);">
                    <div style="max-width: 760px; margin: 0 auto; text-align:center;">
                        <div class="kicker" style="justify-content:center;">{{ $faq_kicker ?: 'Common Questions' }}</div>
                        <h2 class="section-h" style="margin-bottom: 2rem;">{{ $faq_title ?: 'Frequently Asked' }} <em>{{ $faq_title_em ?: 'Questions' }}</em></h2>
                        <div style="text-align:left;">
                            @forelse($previewFaqs as $f)
                                <div style="border-top:1px solid var(--ivory3); padding:1.1rem 0; font-weight:600; color:var(--slate); display:flex; justify-content:space-between; gap:1rem;">
                                    <span>{{ $f['q'] }}</span><span style="color:var(--copper);">+</span>
                                </div>
                            @empty
                                <p style="font-size:0.85rem; color:var(--muted); text-align:center;">Using the default question set. Add questions in the form to customise.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
                @endif

                @include('layouts.partials.frontend.footer')
            </div>{{-- /wps-preview-inner --}}
        </div>{{-- /wps-preview-scroll --}}
    </div>{{-- /wps-preview --}}
</div>{{-- /wps-root --}}
