<div class="lw-service-manager">
    <link href="{{ asset('css/admin/consulting-services.css') }}" rel="stylesheet">

    <style>
        .faqmgr-section { background: var(--white); border: 1px solid var(--ivory3); border-radius: 12px; margin-bottom: 1.75rem; overflow: hidden; }
        .faqmgr-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.5rem; background: var(--ivory); border-bottom: 1px solid var(--ivory3); flex-wrap: wrap; }
        .faqmgr-head .meta { display: flex; align-items: center; gap: 0.85rem; }
        .faqmgr-name { font-weight: 800; color: var(--slate); font-size: 0.98rem; }
        .faqmgr-page { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.66rem; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase; color: var(--copper); background: var(--copper3); padding: 0.28rem 0.65rem; border-radius: 999px; text-decoration: none; transition: background 0.15s, color 0.15s; }
        a.faqmgr-page:hover { background: var(--copper); color: #fff; }
        .faqmgr-page svg { flex-shrink: 0; }
        .faqmgr-path { font-family: 'SF Mono','Consolas',monospace; text-transform: none; letter-spacing: 0; font-weight: 700; opacity: 0.85; padding-left: 0.4rem; border-left: 1px solid currentColor; }
        .faqmgr-actions { display: flex; align-items: center; gap: 0.6rem; }
        .faq-row { display: flex; align-items: center; gap: 1rem; padding: 0.95rem 1.5rem; border-bottom: 1px solid var(--ivory3); }
        .faq-row:last-child { border-bottom: none; }
        .faq-q { flex: 1; min-width: 0; }
        .faq-q .q { font-weight: 700; color: var(--slate); font-size: 0.9rem; }
        .faq-q .a { font-size: 0.78rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .faq-empty { padding: 1.5rem; text-align: center; color: var(--muted); font-size: 0.85rem; }
        .faq-add { width: 100%; padding: 0.9rem; border: 1px dashed var(--ivory3); background: var(--ivory); color: var(--copper); font-weight: 700; cursor: pointer; font-size: 0.85rem; }
        .mini-btn { background: none; border: none; cursor: pointer; padding: 0.3rem; display: inline-flex; color: var(--muted); }
        .mini-btn:hover { color: var(--copper); }
        .mini-btn.del:hover { color: var(--danger); }
        .seg-toggle { font-size: 0.7rem; font-weight: 800; letter-spacing: 0.05em; padding: 0.35rem 0.8rem; border-radius: 999px; border: none; cursor: pointer; }

        /* ── Page listing ── */
        .faqpg-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
        .faqpg-card { text-align: left; background: var(--white); border: 1px solid var(--ivory3); border-radius: 14px; padding: 1.4rem 1.5rem; cursor: pointer; transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s; display: flex; flex-direction: column; gap: 1rem; }
        .faqpg-card:hover { border-color: var(--copper); box-shadow: 0 8px 30px rgba(26,35,50,0.08); transform: translateY(-2px); }
        .faqpg-card .top { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
        .faqpg-title { font-family: 'Cormorant Garamond', serif; font-size: 1.45rem; font-weight: 600; color: var(--slate); line-height: 1.15; }
        .faqpg-path { font-family: 'SF Mono','Consolas',monospace; font-size: 0.72rem; color: var(--muted); margin-top: 0.15rem; }
        .faqpg-stats { display: flex; gap: 1.5rem; }
        .faqpg-stat { display: flex; flex-direction: column; }
        .faqpg-stat b { font-size: 1.3rem; font-weight: 800; color: var(--copper); line-height: 1; }
        .faqpg-stat span { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: var(--muted); margin-top: 0.3rem; }
        .faqpg-manage { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: var(--copper); }
        .faqpg-badge { flex-shrink: 0; font-size: 0.6rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; padding: 0.25rem 0.55rem; border-radius: 999px; }
        .faq-back { display: inline-flex; align-items: center; gap: 0.4rem; background: none; border: none; cursor: pointer; color: var(--muted); font-weight: 800; font-size: 0.8rem; padding: 0; margin-bottom: 1rem; }
        .faq-back:hover { color: var(--copper); }
        .faq-detail-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .faq-detail-head h2 { font-family: 'Cormorant Garamond', serif; font-size: 1.9rem; font-weight: 600; color: var(--slate); }
        .faq-detail-head .sub { font-size: 0.82rem; color: var(--muted); margin-top: 0.15rem; }
        .btn-addsec { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--copper); color: #fff; border: none; padding: 0.7rem 1.3rem; border-radius: 10px; font-weight: 800; font-size: 0.82rem; cursor: pointer; }
        .btn-addsec:hover { background: var(--slate); }
    </style>

    @if($pages !== null)
        {{-- ─────────── PAGE LISTING ─────────── --}}
        <div style="margin-bottom: 1.5rem;">
            <p style="color: var(--muted); font-size: 0.9rem;">Choose a page to manage its FAQ sections and questions. <strong>The homepage FAQ is managed separately under Welcome Page → FAQ.</strong></p>
        </div>

        @if($pages->isEmpty())
            <div style="text-align:center; padding:4rem; color:var(--muted);">No FAQ sections found. Run <code>php artisan db:seed --class=FaqSeeder</code>.</div>
        @else
            <div class="faqpg-grid">
                @foreach($pages as $pageKey => $group)
                    @php($first = $group->first())
                    @php($url = $first->pageUrl())
                    @php($qCount = $group->sum('faqs_count'))
                    @php($activeSecs = $group->where('is_active', true)->count())
                    <button type="button" class="faqpg-card" wire:key="page-{{ $pageKey }}" wire:click="selectPage('{{ $pageKey }}')">
                        <div class="top">
                            <div>
                                <div class="faqpg-title">{{ $first->pageLabel() }}</div>
                                @if($url)<div class="faqpg-path">{{ parse_url($url, PHP_URL_PATH) }}</div>@endif
                            </div>
                            <span class="faqpg-badge" style="{{ $activeSecs ? 'background:#dcfce7;color:#16a34a;' : 'background:#f1f5f9;color:#64748b;' }}">
                                {{ $activeSecs ? 'Live' : 'Hidden' }}
                            </span>
                        </div>
                        <div class="faqpg-stats">
                            <div class="faqpg-stat"><b>{{ $group->count() }}</b><span>Section{{ $group->count() === 1 ? '' : 's' }}</span></div>
                            <div class="faqpg-stat"><b>{{ $qCount }}</b><span>Question{{ $qCount === 1 ? '' : 's' }}</span></div>
                        </div>
                        <span class="faqpg-manage">
                            Manage
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </span>
                    </button>
                @endforeach
            </div>
        @endif
    @else
        {{-- ─────────── PAGE DETAIL ─────────── --}}
        @php($__first = $sections->first())
        @php($__label = $__first?->pageLabel() ?? \Illuminate\Support\Str::title($selectedPage).' Page')
        @php($__url = $__first?->pageUrl())

        <button type="button" class="faq-back" wire:click="backToPages">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            All FAQ pages
        </button>

        <div class="faq-detail-head">
            <div>
                <h2>{{ $__label }}</h2>
                <div class="sub">
                    Manage the sections and questions shown on this page.
                    @if($__url)<a href="{{ $__url }}" target="_blank" rel="noopener" style="color:var(--copper); font-weight:700;">View page ↗</a>@endif
                </div>
            </div>
            <button type="button" class="btn-addsec" wire:click="createSection">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Section
            </button>
        </div>

        @forelse($sections as $section)
            <div class="faqmgr-section" wire:key="sec-{{ $section->id }}">
                <div class="faqmgr-head">
                    <div class="meta">
                        <span class="faqmgr-name">{{ $section->name }}</span>
                    </div>
                    <div class="faqmgr-actions">
                        <button wire:click="editSection({{ $section->id }})" class="icon-btn" title="Edit section heading & name">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button wire:click="toggleSection({{ $section->id }})" class="seg-toggle {{ $section->is_active ? 'badge-active' : 'badge-draft' }}"
                                style="{{ $section->is_active ? 'background:#dcfce7;color:#16a34a;' : 'background:#f1f5f9;color:#64748b;' }}">
                            {{ $section->is_active ? 'SECTION ON' : 'SECTION OFF' }}
                        </button>
                        <button x-on:click="Swal.fire({title:'Delete this section?',text:'All questions in “{{ addslashes($section->name) }}” will be removed too.',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Delete section'}).then(r=>{if(r.isConfirmed)$wire.deleteSection({{ $section->id }})})"
                                class="mini-btn del" title="Delete section">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>

                <div wire:key="sortable-{{ $section->id }}"
                     x-data
                     x-init="if (typeof Sortable !== 'undefined') { Sortable.create($el, { animation: 150, handle: '.drag-handle', onEnd() { $wire.updateItemOrder(Array.from($el.querySelectorAll('.faq-row')).map(r => r.dataset.id)); } }); }">
                    @forelse($section->faqs as $faq)
                        <div class="faq-row" data-id="{{ $faq->id }}" wire:key="faq-{{ $faq->id }}" style="{{ $faq->is_active ? '' : 'opacity:0.55;' }}">
                            <span class="drag-handle" style="cursor:grab; color:var(--muted); display:flex;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="5" r="1.5"/><circle cx="16" cy="5" r="1.5"/><circle cx="8" cy="12" r="1.5"/><circle cx="16" cy="12" r="1.5"/><circle cx="8" cy="19" r="1.5"/><circle cx="16" cy="19" r="1.5"/></svg>
                            </span>
                            <div class="faq-q">
                                <div class="q">{{ $faq->question }}</div>
                                <div class="a">{{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 90) }}</div>
                            </div>
                            <button wire:click="toggleItem({{ $faq->id }})" class="status-badge {{ $faq->is_active ? 'badge-active' : 'badge-draft' }}" title="{{ $faq->is_active ? 'Active — click to hide' : 'Hidden — click to show' }}">
                                {{ $faq->is_active ? 'Active' : 'Hidden' }}
                            </button>
                            <button wire:click="editItem({{ $faq->id }})" class="mini-btn" title="Edit">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button x-on:click="Swal.fire({title:'Delete question?',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Delete'}).then(r=>{if(r.isConfirmed)$wire.deleteItem({{ $faq->id }})})" class="mini-btn del" title="Delete">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    @empty
                        <div class="faq-empty">No questions yet.</div>
                    @endforelse
                </div>

                <button wire:click="createItem({{ $section->id }})" class="faq-add">+ Add question to “{{ $section->name }}”</button>
            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--muted); border:1px dashed var(--ivory3); border-radius:12px;">
                This page has no sections yet. Click <strong>Add Section</strong> to create one.
            </div>
        @endforelse
    @endif

    {{-- Item modal --}}
    @if($showModal)
        <div class="modal-overlay" wire:click.self="$set('showModal', false)">
            <div class="modal-window" style="max-width: 640px;">
                <button class="close-x" wire:click="$set('showModal', false)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                <div class="modal-header"><h2 style="font-family:'Cormorant Garamond'; font-size:1.8rem; font-weight:600; color:var(--slate);">{{ $faqId ? 'Edit Question' : 'New Question' }}</h2></div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveItem">
                        <div class="form-group"><label>Question <span style="color:#ef4444;">*</span></label><input type="text" wire:model="question" class="form-control" placeholder="e.g. Does Agile work for hardware?">@error('question')<span class="error-msg">{{ $message }}</span>@enderror</div>
                        <div class="form-group">
                            <label>Answer <span style="color:#ef4444;">*</span></label>
                            {{-- Rich-text (TinyMCE). wire:ignore keeps Livewire from wiping the editor on re-render. --}}
                            <div wire:ignore>
                                <textarea x-data x-init="
                                    let editorInstance = null;
                                    $nextTick(() => {
                                        if (typeof tinymce !== 'undefined') {
                                            tinymce.init({
                                                target: $el,
                                                menubar: false,
                                                height: 460,
                                                plugins: 'lists link code',
                                                toolbar: 'undo redo | blocks | bold italic | bullist numlist | link blockquote | removeformat | code',
                                                block_formats: 'Paragraph=p; Heading 3=h3; Heading 4=h4',
                                                branding: false,
                                                setup: function (editor) {
                                                    editorInstance = editor;
                                                    editor.on('blur change undo redo', function () {
                                                        $wire.set('answer', editor.getContent());
                                                    });
                                                    editor.on('init', function () {
                                                        editor.setContent($wire.get('answer') || '');
                                                    });
                                                }
                                            });
                                        } else {
                                            console.error('TinyMCE failed to load.');
                                        }
                                    });
                                    return () => { if (editorInstance) { editorInstance.remove(); } }
                                "></textarea>
                            </div>
                            <span style="display:block; margin-top:.35rem; font-size:.75rem; color:var(--muted);">Use the toolbar to format the answer (headings, bold, lists, links). The <strong>&lt;/&gt;</strong> button opens the raw HTML source. Long answers scroll inside their own box on the public page.</span>
                            @error('answer')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <label style="display:flex; align-items:center; gap:0.6rem; font-weight:800; font-size:0.85rem; color:var(--slate); cursor:pointer;"><input type="checkbox" wire:model="item_is_active" style="width:18px; height:18px; accent-color:var(--copper);"> ACTIVE (visible on the page)</label>
                    </form>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('showModal', false)" type="button" style="background:transparent; border:none; font-weight:700; color:var(--muted); cursor:pointer;">Cancel</button>
                    <button wire:click="saveItem" type="button" style="background:var(--copper); color:#fff; border:none; padding:0.85rem 2.25rem; border-radius:10px; font-weight:700; cursor:pointer;">{{ $faqId ? 'Update' : 'Add Question' }}</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Section modal (create + edit) --}}
    @if($showSectionModal)
        <div class="modal-overlay" wire:click.self="$set('showSectionModal', false)">
            <div class="modal-window" style="max-width: 560px;">
                <button class="close-x" wire:click="$set('showSectionModal', false)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                <div class="modal-header"><h2 style="font-family:'Cormorant Garamond'; font-size:1.8rem; font-weight:600; color:var(--slate);">{{ $sectionId ? 'Edit Section' : 'New Section' }}</h2></div>
                <div class="modal-body">
                    <div class="form-group"><label>Section name <span style="color:#ef4444;">*</span></label><input type="text" wire:model="s_name" class="form-control" placeholder="e.g. Tools &amp; Resources">@error('s_name')<span class="error-msg">{{ $message }}</span>@enderror<span style="display:block; margin-top:.3rem; font-size:.72rem; color:var(--muted);">Used as the section heading on the page (unless a Title below is set).</span></div>
                    <div class="form-group"><label>Kicker</label><input type="text" wire:model="s_kicker" class="form-control" placeholder="Common Questions">@error('s_kicker')<span class="error-msg">{{ $message }}</span>@enderror</div>
                    <div class="form-grid-2">
                        <div class="form-group"><label>Title (bold)</label><input type="text" wire:model="s_title" class="form-control" placeholder="Frequently Asked">@error('s_title')<span class="error-msg">{{ $message }}</span>@enderror</div>
                        <div class="form-group"><label>Title (italic)</label><input type="text" wire:model="s_title_em" class="form-control" placeholder="Questions">@error('s_title_em')<span class="error-msg">{{ $message }}</span>@enderror</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('showSectionModal', false)" type="button" style="background:transparent; border:none; font-weight:700; color:var(--muted); cursor:pointer;">Cancel</button>
                    <button wire:click="saveSection" type="button" style="background:var(--copper); color:#fff; border:none; padding:0.85rem 2.25rem; border-radius:10px; font-weight:700; cursor:pointer;">{{ $sectionId ? 'Save Changes' : 'Add Section' }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
