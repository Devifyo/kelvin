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
    </style>

    <div style="margin-bottom: 1.5rem;">
        <p style="color: var(--muted); font-size: 0.9rem;">Manage the FAQ section shown on each public page. Toggle a whole section on/off, edit its heading, and add, reorder, activate, or remove questions. <strong>The homepage FAQ is managed under Welcome Page → FAQ.</strong></p>
    </div>

    @forelse($sections as $section)
        <div class="faqmgr-section" wire:key="sec-{{ $section->id }}">
            <div class="faqmgr-head">
                <div class="meta">
                    <span class="faqmgr-name">{{ $section->name }}</span>
                    @php($__url = $section->pageUrl())
                    <a class="faqmgr-page" @if($__url) href="{{ $__url }}" target="_blank" rel="noopener" @endif
                       title="These questions appear on the {{ $section->pageLabel() }}{{ $__url ? ' — opens in a new tab' : '' }}">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Shows on: {{ $section->pageLabel() }}
                        @if($__url)<span class="faqmgr-path">{{ parse_url($__url, PHP_URL_PATH) }}</span>@endif
                    </a>
                </div>
                <div class="faqmgr-actions">
                    <button wire:click="editSection({{ $section->id }})" class="icon-btn" title="Edit heading">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button wire:click="toggleSection({{ $section->id }})" class="seg-toggle {{ $section->is_active ? 'badge-active' : 'badge-draft' }}"
                            style="{{ $section->is_active ? 'background:#dcfce7;color:#16a34a;' : 'background:#f1f5f9;color:#64748b;' }}">
                        {{ $section->is_active ? 'SECTION ON' : 'SECTION OFF' }}
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
        <div style="text-align:center; padding:4rem; color:var(--muted);">No FAQ sections found. Run <code>php artisan db:seed --class=FaqSeeder</code>.</div>
    @endforelse

    {{-- Item modal --}}
    @if($showModal)
        <div class="modal-overlay" wire:click.self="$set('showModal', false)">
            <div class="modal-window" style="max-width: 640px;">
                <button class="close-x" wire:click="$set('showModal', false)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                <div class="modal-header"><h2 style="font-family:'Cormorant Garamond'; font-size:1.8rem; font-weight:600; color:var(--slate);">{{ $faqId ? 'Edit Question' : 'New Question' }}</h2></div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveItem">
                        <div class="form-group"><label>Question <span style="color:#ef4444;">*</span></label><input type="text" wire:model="question" class="form-control" placeholder="e.g. Does Agile work for hardware?">@error('question')<span class="error-msg">{{ $message }}</span>@enderror</div>
                        <div class="form-group"><label>Answer <span style="color:#ef4444;">*</span></label><textarea wire:model="answer" class="form-control" rows="5" placeholder="Answer first, then context — 2–4 sentences."></textarea>@error('answer')<span class="error-msg">{{ $message }}</span>@enderror</div>
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

    {{-- Section heading modal --}}
    @if($showSectionModal)
        <div class="modal-overlay" wire:click.self="$set('showSectionModal', false)">
            <div class="modal-window" style="max-width: 560px;">
                <button class="close-x" wire:click="$set('showSectionModal', false)"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                <div class="modal-header"><h2 style="font-family:'Cormorant Garamond'; font-size:1.8rem; font-weight:600; color:var(--slate);">Section Heading</h2></div>
                <div class="modal-body">
                    <div class="form-group"><label>Kicker</label><input type="text" wire:model="s_kicker" class="form-control" placeholder="Common Questions"></div>
                    <div class="form-grid-2">
                        <div class="form-group"><label>Title (bold)</label><input type="text" wire:model="s_title" class="form-control" placeholder="Frequently Asked"></div>
                        <div class="form-group"><label>Title (italic)</label><input type="text" wire:model="s_title_em" class="form-control" placeholder="Questions"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('showSectionModal', false)" type="button" style="background:transparent; border:none; font-weight:700; color:var(--muted); cursor:pointer;">Cancel</button>
                    <button wire:click="saveSection" type="button" style="background:var(--copper); color:#fff; border:none; padding:0.85rem 2.25rem; border-radius:10px; font-weight:700; cursor:pointer;">Save Heading</button>
                </div>
            </div>
        </div>
    @endif
</div>
