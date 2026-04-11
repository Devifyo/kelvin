<div class="wps-root">
    <link href="{{ asset('css/admin/profile-settings.css') }}" rel="stylesheet">
    <style>
        /* ================================================================
           AboutPageSettings — Professional CMS Split-Panel UI
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

        /* ── FILE UPLOAD ────────────────────────────────────────────── */
        .wps-file-input {
            width: 100%;
            padding: 0.65rem 0.875rem;
            background: var(--ivory);
            border: 1.5px dashed var(--ivory3);
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--charcoal);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            cursor: pointer;
            transition: border-color 0.18s;
        }
        .wps-file-input:hover { border-color: var(--copper); }

        .wps-img-preview {
            margin-top: 0.75rem;
            padding: 0.65rem;
            border: 1px solid var(--ivory3);
            border-radius: 8px;
            background: var(--ivory);
            display: inline-block;
        }
        .wps-img-preview span {
            display: block;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 0.35rem;
        }
        .wps-img-preview img { max-height: 100px; border-radius: 6px; object-fit: cover; display: block; }

        /* ── EDUCATION LIST ITEMS ───────────────────────────────────── */
        .wps-edu-item {
            background: var(--ivory);
            border: 1px solid var(--ivory3);
            border-radius: 10px;
            padding: 1rem 1rem 1rem;
            margin-bottom: 0.75rem;
            position: relative;
        }
        .wps-edu-remove {
            position: absolute;
            right: 0.75rem;
            top: 0.75rem;
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 6px;
            padding: 0.2rem 0.55rem;
            font-size: 0.65rem;
            font-weight: 700;
            cursor: pointer;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            transition: all 0.15s;
        }
        .wps-edu-remove:hover { background: rgba(239, 68, 68, 0.15); border-color: #ef4444; }

        .wps-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: transparent;
            color: var(--copper);
            border: 1.5px solid var(--copper);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin-top: 0.25rem;
        }
        .wps-add-btn:hover { background: var(--copper); color: var(--white); }
        .wps-add-btn svg { width: 13px; height: 13px; stroke-width: 3; }

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

        /* Browser Chrome */
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
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <div class="wps-panel-title">About Page</div>
                <div class="wps-panel-subtitle">About page content editor</div>
            </div>
        </div>

        <!-- Section Tab Navigation -->
        <div class="wps-tabs">
            <button type="button" wire:click="setTab('header')" class="wps-tab {{ $tab === 'header' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                Header
            </button>
            <button type="button" wire:click="setTab('sidebar')" class="wps-tab {{ $tab === 'sidebar' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="21" x2="9" y2="3"/></svg>
                Sidebar
            </button>
            <button type="button" wire:click="setTab('content')" class="wps-tab {{ $tab === 'content' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Content
            </button>
            <button type="button" wire:click="setTab('seo')" class="wps-tab {{ $tab === 'seo' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                SEO
            </button>
        </div>

        <!-- Form -->
        <form wire:submit="save" class="wps-form">
            <div class="wps-form-body">

                <!-- ─── HEADER TAB ───────────────────────────── -->
                @if($tab === 'header')
                <div wire:key="tab-header">
                    <div class="wps-section-label">Page Header</div>

                    <div class="wps-field">
                        <label class="wps-label">Kicker / Eyebrow</label>
                        <input type="text" wire:model.live.debounce.500ms="header_kicker" class="wps-input" placeholder="e.g. Principal Consultant">
                        <div class="wps-hint">Short label displayed above the main page title.</div>
                    </div>

                    <div class="wps-subsection">Headline</div>

                    <div class="wps-grid-2">
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">H1 Regular</label>
                            <input type="text" wire:model.live.debounce.500ms="header_h1_regular" class="wps-input" placeholder="About Dr. Kevin">
                        </div>
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">H1 Italic</label>
                            <input type="text" wire:model.live.debounce.500ms="header_h1_em" class="wps-input" placeholder="Thompson">
                        </div>
                    </div>
                </div>
                @endif

                <!-- ─── SIDEBAR TAB ──────────────────────────── -->
                @if($tab === 'sidebar')
                <div wire:key="tab-sidebar">
                    <div class="wps-section-label">Sidebar Details</div>

                    <div class="wps-field">
                        <label class="wps-label">Upload New Profile Image</label>
                        <input type="file" wire:model="new_profile_image" accept="image/png,image/jpeg,image/jpg,image/webp" class="wps-file-input">
                        <div wire:loading wire:target="new_profile_image" class="wps-hint" style="color: var(--copper);">Uploading preview...</div>
                        @error('new_profile_image') <span class="wps-hint" style="color: var(--danger);">{{ $message }}</span> @enderror

                        @if ($new_profile_image && empty($errors->get('new_profile_image')))
                            @php $tempUrl = null; try { $tempUrl = $new_profile_image->temporaryUrl(); } catch (\Exception $e) {} @endphp
                            @if($tempUrl)
                                <div class="wps-img-preview">
                                    <span>New Image Preview</span>
                                    <img src="{{ $tempUrl }}" alt="Preview">
                                </div>
                            @endif
                        @elseif ($profile_image)
                            <div class="wps-img-preview">
                                <span>Current Profile Image</span>
                                <img src="{{ $profile_image }}" alt="Current">
                            </div>
                        @endif
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Or Set Image URL Manually</label>
                        <input type="text" wire:model.live.debounce.500ms="profile_image" class="wps-input" placeholder="/img/frontend/Dr. Kevin Thompson.webp">
                        <div class="wps-hint">Overrides any uploaded file. Updates the live preview instantly.</div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Sidebar Section Title</label>
                        <input type="text" wire:model.live.debounce.500ms="sidebar_kicker" class="wps-input" placeholder="Education & Certifications">
                    </div>

                    <div class="wps-subsection">Education & Certifications</div>

                    @foreach($education_list as $index => $item)
                    <div class="wps-edu-item" wire:key="edu-{{ $index }}">
                        <button type="button" wire:click="removeEducationItem({{ $index }})" class="wps-edu-remove">Remove</button>
                        <div class="wps-field">
                            <label class="wps-label">Credential Title</label>
                            <input type="text" wire:model.live.debounce.500ms="education_list.{{ $index }}.title" class="wps-input" placeholder="e.g. Ph.D. & B.S.">
                        </div>
                        <div class="wps-field" style="margin-bottom: 0;">
                            <label class="wps-label">Description / Details</label>
                            <textarea wire:model.live.debounce.500ms="education_list.{{ $index }}.details" class="wps-input" style="min-height: 72px;" placeholder="e.g. Physics from Princeton University"></textarea>
                        </div>
                    </div>
                    @endforeach

                    <button type="button" wire:click="addEducationItem" class="wps-add-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add Credential
                    </button>
                </div>
                @endif

                <!-- ─── CONTENT TAB ──────────────────────────── -->
                @if($tab === 'content')
                <div wire:key="tab-content">
                    <div class="wps-section-label">Body Content</div>

                    <div class="wps-field">
                        <label class="wps-label">Introduction Paragraph</label>
                        <textarea wire:model.live.debounce.500ms="intro_text" class="wps-input"></textarea>
                    </div>

                    <div class="wps-subsection">Section 1 — The Transition</div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">H2 Regular</label>
                            <input type="text" wire:model.live.debounce.500ms="section_1_h2_regular" class="wps-input" placeholder="The Transition to">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">H2 Italic</label>
                            <input type="text" wire:model.live.debounce.500ms="section_1_h2_em" class="wps-input" placeholder="Software & Agile">
                        </div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Paragraph 1</label>
                        <textarea wire:model.live.debounce.500ms="section_1_p1" class="wps-input"></textarea>
                    </div>
                    <div class="wps-field">
                        <label class="wps-label">Paragraph 2</label>
                        <textarea wire:model.live.debounce.500ms="section_1_p2" class="wps-input"></textarea>
                    </div>
                    <div class="wps-field">
                        <label class="wps-label">Highlight Quote Box</label>
                        <textarea wire:model.live.debounce.500ms="highlight_quote" class="wps-input" style="min-height: 72px;"></textarea>
                        <div class="wps-hint">Displayed in the decorative pull-quote box.</div>
                    </div>
                    <div class="wps-field">
                        <label class="wps-label">Paragraph 3</label>
                        <textarea wire:model.live.debounce.500ms="section_1_p3" class="wps-input"></textarea>
                    </div>

                    <div class="wps-subsection">Section 2 — Expanding Horizons</div>

                    <div class="wps-grid-2">
                        <div class="wps-field">
                            <label class="wps-label">H2 Regular</label>
                            <input type="text" wire:model.live.debounce.500ms="section_2_h2_regular" class="wps-input" placeholder="Expanding">
                        </div>
                        <div class="wps-field">
                            <label class="wps-label">H2 Italic</label>
                            <input type="text" wire:model.live.debounce.500ms="section_2_h2_em" class="wps-input" placeholder="Agile Horizons">
                        </div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Paragraph 1</label>
                        <textarea wire:model.live.debounce.500ms="section_2_p1" class="wps-input"></textarea>
                    </div>
                    <div class="wps-field">
                        <label class="wps-label">Paragraph 2</label>
                        <textarea wire:model.live.debounce.500ms="section_2_p2" class="wps-input"></textarea>
                    </div>
                    <div class="wps-field" style="margin-bottom: 0;">
                        <label class="wps-label">Paragraph 3</label>
                        <textarea wire:model.live.debounce.500ms="section_2_p3" class="wps-input"></textarea>
                    </div>
                </div>
                @endif

                <!-- ─── SEO TAB ──────────────────────────────── -->
                @if($tab === 'seo')
                <div wire:key="tab-seo">
                    <div class="wps-section-label">SEO Settings</div>

                    <div class="wps-field">
                        <label class="wps-label">Meta Title</label>
                        <input type="text" wire:model.live.debounce.500ms="seo_title" class="wps-input" placeholder="About Dr. Kevin Thompson | Hardware Consulting">
                        <div class="wps-hint">Shown in browser tabs and Google results. Aim for 50–60 characters.</div>
                    </div>

                    <div class="wps-field">
                        <label class="wps-label">Meta Description</label>
                        <textarea wire:model.live.debounce.500ms="seo_description" class="wps-input" placeholder="A brief description of the about page..."></textarea>
                        <div class="wps-hint">Displayed in search result snippets. Aim for 120–160 characters.</div>
                    </div>

                    <div class="wps-field" style="margin-bottom: 0;">
                        <label class="wps-label">Meta Keywords</label>
                        <input type="text" wire:model.live.debounce.500ms="seo_keywords" class="wps-input" placeholder="Kevin Thompson, hardware consulting, agile">
                        <div class="wps-hint">Comma-separated. Less critical for modern SEO, but still read by some tools.</div>
                    </div>
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
                {{config('app.url')}}/about
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
                <link rel="stylesheet" href="/css/frontend/about.css">
                <style>
                    /* Disable scroll-reveal animations in preview */
                    .reveal, .rv1, .rv2 {
                        opacity: 1 !important;
                        transform: none !important;
                        transition: none !important;
                    }

                    /* Footer */
                    footer {
                        background: var(--slate);
                        padding: 2.75rem 4.5rem;
                        display: flex; align-items: center; justify-content: space-between;
                        flex-wrap: wrap; gap: 1.5rem;
                        border-top: 1px solid rgba(181, 114, 42, .12);
                    }
                    .footer-logo { display: flex; align-items: center; gap: .65rem; }
                    .footer-logo-mark {
                        width: 28px; height: 28px;
                        border: 1px solid rgba(181,114,42,.3);
                        display: flex; align-items: center; justify-content: center;
                        font-family: -apple-system, sans-serif;
                        font-size: .55rem; font-weight: 900; letter-spacing: .05em;
                        color: var(--copper2);
                    }
                    .footer-name { font-size: .92rem; font-weight: 700; color: var(--white); }
                    .footer-name span { color: var(--copper2); }
                    .footer-copy { font-family: -apple-system, sans-serif; font-size: .68rem; color: rgba(250,247,242,.5); }
                    .footer-links { display: flex; gap: 2rem; flex-wrap: wrap; list-style: none; padding: 0; margin: 0; }
                    .footer-links a { font-family: -apple-system, sans-serif; font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; color: rgba(250,247,242,.6); text-decoration: none; }
                    .footer-links a:hover { color: var(--copper3); }
                </style>

                <!-- PAGE HEADER -->
                <section class="page-header">
                    <div class="header-content reveal">
                        <div class="kicker-small" style="color: var(--copper2);">{{ $header_kicker ?? 'Principal Consultant' }}</div>
                        <h1 class="page-title">{{ $header_h1_regular ?? 'About Dr. Kevin' }} <em>{{ $header_h1_em ?? 'Thompson' }}</em></h1>
                    </div>
                </section>

                <div class="strip"></div>

                <!-- ABOUT GRID -->
                <section class="content-section">
                    <div class="about-grid">

                        <aside class="about-sidebar reveal">
                            <div class="profile-img-wrap">
                                <img src="{{ $profile_image ?? '/img/frontend/Dr. Kevin Thompson.webp' }}" alt="Dr. Kevin Thompson" width="320" height="400">
                            </div>
                            <div>
                                <div class="kicker-small">{{ $sidebar_kicker ?? 'Education & Certifications' }}</div>
                                <div class="cred-list">
                                    @if(!empty($education_list) && is_array($education_list))
                                        @foreach($education_list as $item)
                                        <div class="cred-item">
                                            <strong>{{ $item['title'] ?? '' }}</strong>
                                            {!! nl2br(e($item['details'] ?? '')) !!}
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="cred-item"><strong>Ph.D. & B.S.</strong> Physics from Princeton University<br>Physics from Santa Clara University</div>
                                        <div class="cred-item"><strong>PMP</strong> Project Management Professional</div>
                                        <div class="cred-item"><strong>CSM & CSP</strong> Certified Scrum Master and Certified Scrum Professional</div>
                                    @endif
                                </div>
                            </div>
                        </aside>

                        <article class="about-body reveal rv1">
                            <p>{{ $intro_text ?? 'Dr. Kevin Thompson obtained his B.S. in Physics from Santa Clara University, and his Ph.D. in Physics from Princeton University.' }}</p>

                            <h2>{{ $section_1_h2_regular ?? 'The Transition to' }} <em>{{ $section_1_h2_em ?? 'Software & Agile' }}</em></h2>
                            <div class="body-ornament"></div>

                            <p>{{ $section_1_p1 ?? '' }}</p>
                            <p>{{ $section_1_p2 ?? '' }}</p>

                            @if($highlight_quote)
                            <div class="highlight-box">{{ $highlight_quote }}</div>
                            @endif

                            <p>{{ $section_1_p3 ?? '' }}</p>

                            <h2>{{ $section_2_h2_regular ?? 'Expanding' }} <em>{{ $section_2_h2_em ?? 'Agile Horizons' }}</em></h2>
                            <div class="body-ornament"></div>

                            <p>{{ $section_2_p1 ?? '' }}</p>
                            <p>{{ $section_2_p2 ?? '' }}</p>
                            <p>{{ $section_2_p3 ?? '' }}</p>
                        </article>

                    </div>
                </section>

                @include('layouts.partials.frontend.footer')
            </div>{{-- /wps-preview-inner --}}
        </div>{{-- /wps-preview-scroll --}}
    </div>{{-- /wps-preview --}}
</div>{{-- /wps-root --}}
