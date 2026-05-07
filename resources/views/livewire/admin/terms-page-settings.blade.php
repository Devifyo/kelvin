<div class="lps-root">
    <link href="{{ asset('css/admin/profile-settings.css') }}" rel="stylesheet">
    <style>
        /* ────────────────────────────────────────────────────────────
           Legal Page Settings — single-column rich-text editor UI
        ──────────────────────────────────────────────────────────── */
        .lps-root {
            max-width: 1100px;
            margin: 0 auto;
        }

        .lps-panel {
            background: var(--white);
            border: 1px solid var(--ivory3);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px -8px rgba(26, 35, 50, 0.1);
        }

        .lps-panel-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--ivory3);
            background: var(--ivory);
        }
        .lps-panel-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--copper) 0%, var(--copper2) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .lps-panel-icon svg { width: 18px; height: 18px; stroke: #fff; }
        .lps-panel-title { font-size: 0.95rem; font-weight: 700; color: var(--slate); line-height: 1.2; }
        .lps-panel-subtitle { font-size: 0.72rem; color: var(--muted); margin-top: 0.15rem; }

        .lps-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid var(--ivory3);
            background: var(--white);
        }
        .lps-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            padding: 0.85rem 0.25rem 0.7rem;
            background: transparent;
            border: none;
            border-bottom: 2.5px solid transparent;
            color: var(--muted);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            cursor: pointer;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
        }
        .lps-tab svg { width: 16px; height: 16px; stroke-width: 2; }
        .lps-tab:hover { color: var(--slate); background: var(--ivory); }
        .lps-tab.active {
            color: var(--copper);
            border-bottom-color: var(--copper);
            background: rgba(181, 114, 42, 0.04);
        }

        .lps-form-body { padding: 1.75rem 1.75rem 1.25rem; }

        .lps-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--copper);
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .lps-section-label::after { content: ''; flex: 1; height: 1px; background: var(--ivory3); }

        .lps-field { margin-bottom: 1.2rem; }
        .lps-field:last-child { margin-bottom: 0; }
        .lps-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--charcoal);
            margin-bottom: 0.4rem;
        }
        .lps-input {
            width: 100%;
            padding: 0.75rem 0.9rem;
            background: var(--ivory);
            border: 1.5px solid var(--ivory3);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--charcoal);
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
            line-height: 1.5;
        }
        .lps-input:focus {
            outline: none;
            border-color: var(--copper);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(181, 114, 42, 0.1);
        }
        .lps-hint { font-size: 0.72rem; color: var(--muted); margin-top: 0.35rem; line-height: 1.4; }
        .lps-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
        textarea.lps-input { min-height: 96px; resize: vertical; }

        .lps-save-bar {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--ivory3);
            background: var(--white);
            display: flex; align-items: center; gap: 0.85rem;
        }
        .lps-save-btn {
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
        .lps-save-btn:hover { background: var(--copper); box-shadow: 0 4px 16px rgba(181, 114, 42, 0.3); transform: translateY(-1px); }
        .lps-save-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }
        .lps-save-btn svg { width: 16px; height: 16px; stroke-width: 2; }

        .lps-saved-badge {
            display: flex; align-items: center; gap: 0.35rem;
            font-size: 0.75rem; font-weight: 600; color: #059669;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 6px;
            padding: 0.4rem 0.7rem;
            white-space: nowrap;
        }
        .lps-saved-badge svg { width: 13px; height: 13px; stroke-width: 3; }

        .lps-editor-wrap .tox-tinymce { border-radius: 10px; border-color: var(--ivory3); }
    </style>

    <div class="lps-panel">

        <div class="lps-panel-header">
            <div class="lps-panel-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <div class="lps-panel-title">Terms &amp; Conditions Page</div>
                <div class="lps-panel-subtitle">Header, rich-text body &amp; SEO for /terms-conditions</div>
            </div>
        </div>

        <div class="lps-tabs">
            <button type="button" wire:click="setTab('header')" class="lps-tab {{ $tab === 'header' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                Header
            </button>
            <button type="button" wire:click="setTab('content')" class="lps-tab {{ $tab === 'content' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Content
            </button>
            <button type="button" wire:click="setTab('seo')" class="lps-tab {{ $tab === 'seo' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                SEO
            </button>
        </div>

        <form wire:submit="save">
            <div class="lps-form-body">

                @if($tab === 'header')
                <div wire:key="lps-tab-header">
                    <div class="lps-section-label">Page Header</div>

                    <div class="lps-field">
                        <label class="lps-label">Kicker / Eyebrow</label>
                        <input type="text" wire:model="header_kicker" class="lps-input" placeholder="e.g. Legal">
                        <div class="lps-hint">Small label rendered above the main page title.</div>
                    </div>

                    <div class="lps-grid-2">
                        <div class="lps-field">
                            <label class="lps-label">H1 Regular</label>
                            <input type="text" wire:model="header_h1_regular" class="lps-input" placeholder="Terms &amp;">
                        </div>
                        <div class="lps-field">
                            <label class="lps-label">H1 Italic</label>
                            <input type="text" wire:model="header_h1_em" class="lps-input" placeholder="Conditions">
                        </div>
                    </div>

                    <div class="lps-field">
                        <label class="lps-label">Last Updated Label</label>
                        <input type="text" wire:model="last_updated" class="lps-input" placeholder="Last updated: April 2026">
                        <div class="lps-hint">Optional. Shown directly under the headline.</div>
                    </div>
                </div>
                @endif

                @if($tab === 'content')
                <div wire:key="lps-tab-content">
                    <div class="lps-section-label">Page Body</div>

                    <div class="lps-field lps-editor-wrap">
                        <label class="lps-label">Terms &amp; Conditions Content (Rich Text)</label>
                        <div wire:ignore>
                            <textarea x-data x-init="
                                let editorInstance = null;
                                $nextTick(() => {
                                    if (typeof tinymce !== 'undefined') {
                                        tinymce.init({
                                            target: $el,
                                            menubar: true,
                                            height: 600,
                                            plugins: 'lists link image media code table anchor searchreplace wordcount',
                                            toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | code | removeformat',
                                            image_title: true,
                                            automatic_uploads: true,
                                            images_upload_handler: function (blobInfo, progress) {
                                                return new Promise((resolve, reject) => {
                                                    const xhr = new XMLHttpRequest();
                                                    xhr.open('POST', '{{ route('admin.tinymce.upload') }}');
                                                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                                                    xhr.upload.onprogress = (e) => progress(e.loaded / e.total * 100);
                                                    xhr.onload = () => {
                                                        if (xhr.status < 200 || xhr.status >= 300) { reject('HTTP Error: ' + xhr.status); return; }
                                                        const json = JSON.parse(xhr.responseText);
                                                        if (!json || typeof json.location != 'string') { reject('Invalid JSON: ' + xhr.responseText); return; }
                                                        resolve(json.location);
                                                    };
                                                    xhr.onerror = () => reject('Image upload failed.');
                                                    const formData = new FormData();
                                                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                                                    xhr.send(formData);
                                                });
                                            },
                                            setup: function (editor) {
                                                editorInstance = editor;
                                                editor.on('blur change', function () { $wire.set('content', editor.getContent()); });
                                                editor.on('init', function () { editor.setContent($wire.get('content') || ''); });
                                            }
                                        });
                                    }
                                });
                                return () => { if (editorInstance) { editorInstance.remove(); } }
                            "></textarea>
                        </div>
                        <div class="lps-hint">Use headings (H2 / H3), lists, links and tables to structure the terms. Images are uploaded automatically.</div>
                    </div>
                </div>
                @endif

                @if($tab === 'seo')
                <div wire:key="lps-tab-seo">
                    <div class="lps-section-label">SEO Settings</div>

                    <div class="lps-field">
                        <label class="lps-label">Meta Title</label>
                        <input type="text" wire:model="seo_title" class="lps-input" placeholder="Terms &amp; Conditions | Kevin Thompson Ph.D.">
                        <div class="lps-hint">50–60 characters recommended.</div>
                    </div>

                    <div class="lps-field">
                        <label class="lps-label">Meta Description</label>
                        <textarea wire:model="seo_description" class="lps-input" placeholder="A brief summary of the terms governing use of this site..."></textarea>
                        <div class="lps-hint">120–160 characters recommended.</div>
                    </div>

                    <div class="lps-field">
                        <label class="lps-label">Meta Keywords</label>
                        <input type="text" wire:model="seo_keywords" class="lps-input" placeholder="terms of service, conditions, legal">
                    </div>
                </div>
                @endif

            </div>

            <div class="lps-save-bar">
                @if(session()->has('success'))
                <div class="lps-saved-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>
                    Saved
                </div>
                @endif
                <button type="submit" class="lps-save-btn" wire:loading.attr="disabled" wire:target="save">
                    <svg wire:loading.remove wire:target="save" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
