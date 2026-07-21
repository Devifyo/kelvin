{{--
    Confirmations use SweetAlert2 (loaded in layouts.admin), matching the rest of
    the admin panel — never the browser's native confirm() / wire:confirm dialog.

      phmAsk({ title, text, confirmText, danger })  →  Promise<bool>
--}}
<div class="phm-root"
     x-data="{
        phmAsk({ title, text, confirmText = 'Continue', danger = false }) {
            return Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel',
                confirmButtonColor: danger ? '#ef4444' : '#b5722a',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                focusCancel: true,
                buttonsStyling: true,
                customClass: { popup: 'phm-swal' },
            }).then(r => r.isConfirmed);
        }
     }"
     x-on:confirm-page-switch.window="
        phmAsk({
            title: 'Discard unsaved changes?',
            text: `Your edits to this header haven't been saved. Switching to “${$event.detail.label}” will discard them.`,
            confirmText: 'Discard & switch',
            danger: true,
        }).then(ok => ok && $wire.selectPage($event.detail.pageKey, true))
     ">
    <style>
        /* Branded SweetAlert dialog (scoped via customClass) */
        .phm-swal { border-radius: 18px !important; }
        .phm-swal .swal2-title {
            font-family: 'Cormorant Garamond', serif !important;
            font-weight: 600 !important;
            color: var(--slate) !important;
        }
        .phm-swal .swal2-html-container {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            font-size: 0.92rem !important;
            color: var(--muted) !important;
        }
        .phm-swal .swal2-styled {
            border-radius: 10px !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.7rem 1.3rem !important;
        }

        /* ────────────────────────────────────────────────────────────
           Page Headers — page picker + editor + live preview
        ──────────────────────────────────────────────────────────── */
        .phm-root { max-width: 1500px; margin: 0 auto; }

        /* ── PAGE PICKER ────────────────────────────────────────────── */
        /* Equal-width columns that reflow onto a new row when space runs out.
           Grid rather than flex-wrap so a lone card on the last row keeps its
           column width instead of stretching across the whole row. */
        .phm-picker {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .phm-page-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            /* min-width:0 lets the text column shrink so long slugs truncate;
               without it the content would push past the card border. */
            min-width: 0;
            max-width: 100%;
            overflow: hidden;
            padding: 0.7rem 1.05rem;
            background: var(--white);
            border: 1.5px solid var(--ivory3);
            border-radius: 12px;
            cursor: pointer;
            text-align: left;
            transition: border-color 0.18s, box-shadow 0.18s, transform 0.15s;
        }
        .phm-page-btn:hover { border-color: var(--copper2); transform: translateY(-1px); }
        .phm-page-btn.active {
            border-color: var(--copper);
            box-shadow: 0 0 0 3px rgba(181, 114, 42, 0.12);
            background: rgba(181, 114, 42, 0.04);
        }
        .phm-page-btn-mark {
            width: 30px; height: 30px;
            border-radius: 8px;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            background: var(--ivory);
            color: var(--muted);
            font-size: 0.72rem; font-weight: 800;
            font-family: -apple-system, sans-serif;
        }
        .phm-page-btn.active .phm-page-btn-mark {
            background: linear-gradient(135deg, var(--copper) 0%, var(--copper2) 100%);
            color: #fff;
        }
        .phm-page-btn-text { display: flex; flex-direction: column; gap: 0.1rem; min-width: 0; flex: 1; }
        /* Truncate rather than overflow — long slugs like
           /agile-hardware-papers-and-presentations must never escape the card. */
        .phm-page-btn-label,
        .phm-page-btn-url {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .phm-page-btn-label { font-size: 0.82rem; font-weight: 700; color: var(--slate); line-height: 1.2; }
        .phm-page-btn-url { font-size: 0.66rem; color: var(--muted); font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }

        /* ── SPLIT LAYOUT ───────────────────────────────────────────── */
        .phm-split { display: flex; gap: 1.5rem; align-items: flex-start; }

        .phm-panel {
            width: 460px;
            flex-shrink: 0;
            background: var(--white);
            border: 1px solid var(--ivory3);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px -8px rgba(26, 35, 50, 0.1);
        }

        .phm-panel-header {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--ivory3);
            background: var(--ivory);
        }
        .phm-panel-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--copper) 0%, var(--copper2) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .phm-panel-icon svg { width: 18px; height: 18px; stroke: #fff; }
        .phm-panel-title { font-size: 0.95rem; font-weight: 700; color: var(--slate); line-height: 1.2; }
        .phm-panel-subtitle { font-size: 0.72rem; color: var(--muted); margin-top: 0.15rem; }

        .phm-dirty-dot {
            margin-left: auto;
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.65rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: #b45309;
            background: rgba(245, 158, 11, 0.12);
            border-radius: 20px;
            padding: 0.28rem 0.6rem;
            white-space: nowrap;
        }

        .phm-form-body { padding: 1.6rem 1.6rem 1.25rem; }

        .phm-field { margin-bottom: 1.15rem; }
        .phm-field:last-child { margin-bottom: 0; }
        .phm-label {
            display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
            color: var(--charcoal);
            margin-bottom: 0.4rem;
        }
        .phm-count { font-size: 0.65rem; font-weight: 600; color: var(--muted); text-transform: none; letter-spacing: 0; }
        .phm-input {
            width: 100%;
            padding: 0.72rem 0.9rem;
            background: var(--ivory);
            border: 1.5px solid var(--ivory3);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--charcoal);
            line-height: 1.5;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }
        .phm-input:focus {
            outline: none;
            border-color: var(--copper);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(181, 114, 42, 0.1);
        }
        .phm-input.is-invalid { border-color: #dc2626; background: rgba(220, 38, 38, 0.04); }
        textarea.phm-input { min-height: 92px; resize: vertical; }
        .phm-hint { font-size: 0.72rem; color: var(--muted); margin-top: 0.35rem; line-height: 1.45; }
        .phm-error { font-size: 0.72rem; color: #dc2626; margin-top: 0.35rem; font-weight: 600; }
        .phm-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }

        .phm-em-swatch {
            display: inline-block; width: 9px; height: 9px; border-radius: 2px;
            background: var(--copper2); margin-right: 0.3rem; vertical-align: middle;
        }

        /* ── SAVE BAR ───────────────────────────────────────────────── */
        .phm-save-bar {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--ivory3);
            background: var(--white);
            display: flex; align-items: center; gap: 0.6rem;
        }
        .phm-save-btn {
            flex: 1;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.85rem 1.25rem;
            background: var(--slate);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 0.85rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
        }
        .phm-save-btn:hover { background: var(--copper); box-shadow: 0 4px 16px rgba(181, 114, 42, 0.3); transform: translateY(-1px); }
        .phm-save-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .phm-save-btn svg { width: 16px; height: 16px; stroke-width: 2; }

        .phm-ghost-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
            padding: 0.85rem 0.9rem;
            background: transparent;
            border: 1.5px solid var(--ivory3);
            border-radius: 10px;
            color: var(--muted);
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            cursor: pointer;
            transition: border-color 0.18s, color 0.18s, background 0.18s;
        }
        .phm-ghost-btn:hover { border-color: var(--copper); color: var(--copper); background: rgba(181, 114, 42, 0.05); }
        .phm-ghost-btn svg { width: 14px; height: 14px; stroke-width: 2; }

        .phm-flash {
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.25rem;
            padding: 0.8rem 1.1rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 10px;
            color: #047857;
            font-size: 0.82rem; font-weight: 600;
        }
        .phm-flash svg { width: 16px; height: 16px; stroke-width: 2.5; flex-shrink: 0; }

        /* ── PREVIEW PANE ───────────────────────────────────────────── */
        .phm-preview {
            flex: 1;
            min-width: 0;
            position: sticky;
            top: 100px;
            background: var(--white);
            border: 1px solid var(--ivory3);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px -8px rgba(26, 35, 50, 0.1);
        }

        .phm-chrome {
            background: #f0ece5;
            border-bottom: 1px solid #e0d8ce;
            padding: 0.6rem 1rem;
            display: flex; align-items: center; gap: 0.85rem;
        }
        .phm-chrome-dots { display: flex; gap: 0.4rem; flex-shrink: 0; }
        .phm-chrome-dots span { width: 11px; height: 11px; border-radius: 50%; display: block; }
        .phm-dot-r { background: #ff5f57; }
        .phm-dot-y { background: #febc2e; }
        .phm-dot-g { background: #28c840; }
        .phm-chrome-url {
            flex: 1; min-width: 0;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(0,0,0,0.09);
            border-radius: 7px;
            padding: 0.32rem 0.75rem;
            font-size: 0.72rem;
            color: var(--muted);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex; align-items: center; gap: 0.45rem;
            overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
        }
        .phm-chrome-url svg { width: 11px; height: 11px; flex-shrink: 0; color: #10b981; }
        .phm-chrome-open {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--muted); text-decoration: none;
            flex-shrink: 0;
        }
        .phm-chrome-open:hover { color: var(--copper); }
        .phm-chrome-open svg { width: 12px; height: 12px; stroke-width: 2; }

        .phm-statusbar {
            background: var(--ivory);
            border-bottom: 1px solid var(--ivory3);
            padding: 0.45rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .phm-live-badge {
            display: flex; align-items: center; gap: 0.45rem;
            font-size: 0.7rem; font-weight: 600; color: var(--slate);
        }
        .phm-pulse {
            width: 7px; height: 7px; background: #10b981; border-radius: 50%;
            display: inline-block; animation: phmPulse 2.2s ease-in-out infinite;
        }
        @keyframes phmPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(0.8); }
        }
        .phm-status-note { font-size: 0.68rem; color: var(--muted); }

        /* ── PREVIEW CANVAS ─────────────────────────────────────────────
           Mirrors the public .page-header design (see css/frontend/*.css).
           Scoped under .phm-canvas so nothing leaks into the admin UI.
        ─────────────────────────────────────────────────────────────── */
        .phm-canvas {
            --pv-slate: #1a2332;
            --pv-copper: #b5722a;
            --pv-copper2: #d4924e;
            --pv-copper3: #edb97a;
            background: var(--pv-slate);
            padding: 5.5rem 3rem 4rem;
            position: relative;
            overflow: hidden;
        }
        .phm-canvas.is-centered { text-align: center; }
        .phm-canvas::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%);
            z-index: 0;
        }
        .phm-canvas-inner { max-width: 760px; position: relative; z-index: 1; }
        .phm-canvas.is-centered .phm-canvas-inner { margin: 0 auto; }

        .phm-pv-kicker {
            display: inline-flex; align-items: center; gap: .75rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: .75rem; font-weight: 700;
            letter-spacing: .2em; text-transform: uppercase;
            color: var(--pv-copper2);
            margin-bottom: 1.5rem;
            min-height: 1rem;
        }
        .phm-pv-kicker.has-rules::before,
        .phm-pv-kicker.has-rules::after { content: ''; width: 30px; height: 1px; background: var(--pv-copper); }
        .phm-pv-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.4rem, 4vw, 3.6rem);
            font-weight: 400;
            color: #fff;
            line-height: 1.1;
            margin: 0 0 1.25rem;
        }
        .phm-pv-title em { font-style: italic; color: var(--pv-copper3); }
        .phm-pv-subtitle {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 1rem;
            color: rgba(250,247,242,.72);
            max-width: 620px;
            line-height: 1.8;
            font-weight: 300;
            margin: 0;
        }
        .phm-canvas.is-centered .phm-pv-subtitle { margin: 0 auto; }
        .phm-pv-empty { color: rgba(250,247,242,.28); font-style: italic; }

        .phm-strip { height: 4px; background: linear-gradient(90deg, var(--copper) 0%, var(--copper3) 100%); }

        .phm-preview-foot {
            padding: 0.9rem 1.25rem;
            background: var(--ivory);
            border-top: 1px solid var(--ivory3);
            display: flex; gap: 1.5rem; flex-wrap: wrap;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .phm-foot-item { font-size: 0.68rem; color: var(--muted); }
        .phm-foot-item strong { display: block; font-size: 0.8rem; color: var(--slate); font-weight: 700; margin-top: 0.1rem; }

        /* ── RESPONSIVE ─────────────────────────────────────────────── */
        @media (max-width: 1200px) {
            .phm-split { flex-direction: column; }
            .phm-panel { width: 100%; }
            .phm-preview { width: 100%; position: static; }
        }
    </style>

    @if(session('success'))
        <div class="phm-flash">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── PAGE PICKER ───────────────────────────────────────────── --}}
    <div class="phm-picker">
        @foreach($pages as $key => $config)
            @php $path = parse_url(route($config['route']), PHP_URL_PATH); @endphp
            <button type="button"
                    wire:key="picker-{{ $key }}"
                    wire:click="selectPage('{{ $key }}')"
                    title="{{ $config['label'] }} — {{ $path }}"
                    class="phm-page-btn {{ $pageKey === $key ? 'active' : '' }}">
                <span class="phm-page-btn-mark">{{ $loop->iteration }}</span>
                <span class="phm-page-btn-text">
                    <span class="phm-page-btn-label">{{ $config['label'] }}</span>
                    <span class="phm-page-btn-url">{{ $path }}</span>
                </span>
            </button>
        @endforeach
    </div>

    <div class="phm-split">

        {{-- ── EDITOR ────────────────────────────────────────────── --}}
        <div class="phm-panel">
            <div class="phm-panel-header">
                <div class="phm-panel-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>
                </div>
                <div>
                    <div class="phm-panel-title">{{ $this->currentMeta()['label'] }}</div>
                    <div class="phm-panel-subtitle">Header copy for this page</div>
                </div>
                @if($this->isDirty())
                    <span class="phm-dirty-dot">Unsaved</span>
                @endif
            </div>

            <form wire:submit="save">
                <div class="phm-form-body">

                    {{-- Kicker --}}
                    <div class="phm-field">
                        <label class="phm-label" for="phm-kicker">
                            Eyebrow / Kicker
                            <span class="phm-count">{{ mb_strlen($kicker ?? '') }}/120</span>
                        </label>
                        <input id="phm-kicker" type="text"
                               wire:model.live.debounce.300ms="kicker"
                               class="phm-input @error('kicker') is-invalid @enderror"
                               placeholder="e.g. {{ $pages[$pageKey]['defaults']['kicker'] }}">
                        <div class="phm-hint">Small uppercase label above the heading. Leave blank to hide it.</div>
                        @error('kicker') <div class="phm-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Heading --}}
                    <div class="phm-field">
                        <div class="phm-grid-2">
                            <div>
                                <label class="phm-label" for="phm-title-regular">
                                    Heading
                                    <span class="phm-count">{{ mb_strlen($title_regular ?? '') }}/120</span>
                                </label>
                                <input id="phm-title-regular" type="text"
                                       wire:model.live.debounce.300ms="title_regular"
                                       class="phm-input @error('title_regular') is-invalid @enderror"
                                       placeholder="{{ $pages[$pageKey]['defaults']['title_regular'] }}">
                            </div>
                            <div>
                                <label class="phm-label" for="phm-title-em">
                                    <span><span class="phm-em-swatch"></span>Accent Word</span>
                                    <span class="phm-count">{{ mb_strlen($title_em ?? '') }}/120</span>
                                </label>
                                <input id="phm-title-em" type="text"
                                       wire:model.live.debounce.300ms="title_em"
                                       class="phm-input @error('title_em') is-invalid @enderror"
                                       placeholder="{{ $pages[$pageKey]['defaults']['title_em'] }}">
                            </div>
                        </div>
                        <div class="phm-hint">
                            These two join into one H1. The accent word renders in italic copper —
                            keep the punctuation (e.g. <code>&amp;</code>) on the heading side.
                        </div>
                        @error('title_regular') <div class="phm-error">{{ $message }}</div> @enderror
                        @error('title_em') <div class="phm-error">{{ $message }}</div> @enderror
                    </div>

                    {{-- Subtitle --}}
                    @if($this->currentMeta()['has_subtitle'] ?? true)
                        <div class="phm-field">
                            <label class="phm-label" for="phm-subtitle">
                                Subtitle
                                <span class="phm-count">{{ mb_strlen($subtitle ?? '') }}/500</span>
                            </label>
                            <textarea id="phm-subtitle" rows="4"
                                      wire:model.live.debounce.300ms="subtitle"
                                      class="phm-input @error('subtitle') is-invalid @enderror"
                                      placeholder="{{ $pages[$pageKey]['defaults']['subtitle'] }}"></textarea>
                            <div class="phm-hint">One or two sentences under the heading. Leave blank to hide it.</div>
                            @error('subtitle') <div class="phm-error">{{ $message }}</div> @enderror
                        </div>
                    @else
                        <div class="phm-hint" style="padding: 0.7rem 0.9rem; background: var(--ivory); border-radius: 8px;">
                            This page's design has no subtitle under the heading, so that field is hidden.
                        </div>
                    @endif
                </div>

                <div class="phm-save-bar">
                    <button type="submit" class="phm-save-btn" wire:loading.attr="disabled" wire:target="save">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <span wire:loading.remove wire:target="save">Save Changes</span>
                        <span wire:loading wire:target="save">Saving…</span>
                    </button>

                    <button type="button" class="phm-ghost-btn"
                            x-on:click="phmAsk({
                                title: 'Discard your edits?',
                                text: 'The saved copy will be reloaded and your unsaved changes will be lost.',
                                confirmText: 'Discard edits',
                                danger: true,
                            }).then(ok => ok && $wire.discardChanges())"
                            @disabled(! $this->isDirty())>
                        Discard
                    </button>

                    <button type="button" class="phm-ghost-btn"
                            x-on:click="phmAsk({
                                title: 'Reset to original copy?',
                                text: 'The fields will be refilled with this page\'s original text. Nothing goes live until you click Save.',
                                confirmText: 'Reset fields',
                            }).then(ok => ok && $wire.restoreDefaults())">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                        Reset
                    </button>
                </div>
            </form>
        </div>

        {{-- ── LIVE PREVIEW ──────────────────────────────────────── --}}
        <div class="phm-preview">
            <div class="phm-chrome">
                <div class="phm-chrome-dots">
                    <span class="phm-dot-r"></span><span class="phm-dot-y"></span><span class="phm-dot-g"></span>
                </div>
                <div class="phm-chrome-url">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    {{ $this->currentUrl() }}
                </div>
                <a href="{{ $this->currentUrl() }}" target="_blank" rel="noopener" class="phm-chrome-open">
                    Open
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
            </div>

            <div class="phm-statusbar">
                <span class="phm-live-badge"><span class="phm-pulse"></span> Live Preview</span>
                <span class="phm-status-note">Updates as you type — click Save to publish</span>
            </div>

            @php
                $isCentered = ! in_array($pageKey, ['services', 'about'], true);
                $hasRules   = ($this->currentMeta()['kicker_class'] ?? 'kicker') === 'kicker' && $isCentered;
            @endphp

            <div class="phm-canvas {{ $isCentered ? 'is-centered' : '' }}">
                <div class="phm-canvas-inner">
                    @if(filled($kicker))
                        <div class="phm-pv-kicker {{ $hasRules ? 'has-rules' : '' }}">{{ $kicker }}</div>
                    @endif

                    <h1 class="phm-pv-title">
                        @if(filled($title_regular) || filled($title_em))
                            {{ $title_regular }}@if(filled($title_em)) <em>{{ $title_em }}</em>@endif
                        @else
                            <span class="phm-pv-empty">Your heading appears here…</span>
                        @endif
                    </h1>

                    @if(filled($subtitle) && ($this->currentMeta()['has_subtitle'] ?? true))
                        <p class="phm-pv-subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <div class="phm-strip"></div>

            <div class="phm-preview-foot">
                <div class="phm-foot-item">Page<strong>{{ $this->currentMeta()['label'] }}</strong></div>
                <div class="phm-foot-item">H1 length<strong>{{ mb_strlen(trim(($title_regular ?? '') . ' ' . ($title_em ?? ''))) }} characters</strong></div>
                <div class="phm-foot-item">Status<strong>{{ $this->isDirty() ? 'Unsaved changes' : 'Published' }}</strong></div>
            </div>
        </div>
    </div>
</div>
