<div class="lw-service-manager">
    <link href="{{ asset('css/admin/training-classes.css') }}" rel="stylesheet" />

    {{-- List Header Controls --}}
    <div class="list-controls">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live.debounce.300ms="searchTitle" placeholder="Search training classes...">
        </div>
        <div style="display: flex; gap: 1rem;">
            <div class="filter-tabs">
                <button wire:click="setFilter('all')" class="filter-pill {{ $filterStatus === 'all' ? 'active' : '' }}">All</button>
                <button wire:click="setFilter('active')" class="filter-pill {{ $filterStatus === 'active' ? 'active' : '' }}">Active</button>
                <button wire:click="setFilter('draft')" class="filter-pill {{ $filterStatus === 'draft' ? 'active' : '' }}">Drafts</button>
            </div>
            <button wire:click="create" class="btn-create">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Class
            </button>
        </div>
    </div>

    {{-- Data List --}}
    <div class="service-list-container">
        
        <div id="sortable-list"
             x-data
             x-init="if (typeof Sortable !== 'undefined') {
                 Sortable.create($el, {
                     animation: 150,
                     handle: '.drag-handle',
                     onEnd: function () {
                         let orderedIds = Array.from($el.querySelectorAll('.service-row')).map(row => row.dataset.id);
                         $wire.updateSortOrder(orderedIds);
                     }
                 });
             }"
        >
            @forelse($services as $item)
                <div class="service-row" wire:key="train-{{ $item->id }}" data-id="{{ $item->id }}">
                    
                    <div style="display: flex; align-items: center; gap: 1.25rem; width: 45%;">
                        <div class="drag-handle" style="cursor: grab; color: var(--muted); display: flex; align-items: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="8" cy="4" r="1.5"/><circle cx="16" cy="4" r="1.5"/>
                                <circle cx="8" cy="12" r="1.5"/><circle cx="16" cy="12" r="1.5"/>
                                <circle cx="8" cy="20" r="1.5"/><circle cx="16" cy="20" r="1.5"/>
                            </svg>
                        </div>

                        <div class="service-avatar">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $item->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted); font-family: monospace;">/{{ $item->slug }}</div>
                        </div>
                    </div>
                    
                    <div style="flex: 1; font-size: 0.85rem; color: var(--muted); display: flex; gap: 1rem; align-items: center;">
                        <span style="background: var(--ivory); padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: 600;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            {{ $item->length ?: 'N/A' }}
                        </span>
                        <button wire:click="toggleStatus({{ $item->id }})" class="status-badge {{ $item->is_active ? 'badge-active' : 'badge-draft' }}">
                            {{ $item->is_active ? 'Active' : 'Draft' }}
                        </button>
                    </div>

                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <div style="text-align: right; margin-right: 1.5rem;">
                            <div style="font-size: 0.6rem; font-weight: 800; color: var(--muted);">SORT</div>
                            <div style="font-size: 0.85rem; font-weight: 700;">#{{ $item->sort_order }}</div>
                        </div>
                        <button wire:click="edit({{ $item->id }})" class="icon-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                        <button x-on:click="Swal.fire({title:'Delete Class?', text:'This action cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete'}).then((r)=>{if(r.isConfirmed) $wire.deleteService({{$item->id}})})" class="icon-btn delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 5rem; color: var(--muted); font-size: 0.9rem;">No training classes found.</div>
            @endforelse
        </div>
        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ivory3); background: var(--ivory);">
            {{ $services->links('vendor.pagination.admin-theme') }}
        </div>
    </div>

    {{-- CREATE/EDIT MODAL --}}
    @if($showModal)
        <div class="modal-overlay" wire:click.self="closeModal">
            <div class="modal-window" style="max-width: 1000px;">
                <button class="close-x" wire:click="closeModal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                
                <div class="modal-header">
                    <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; font-weight: 600; color: var(--slate);">{{ $serviceId ? 'Edit Training Class' : 'New Training Class' }}</h2>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Class Title <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="title" class="form-control" placeholder="e.g. Agile Software Development">
                                @error('title')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>URL Slug <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model="slug" class="form-control">
                                @error('slug')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-divider">Class Logistics</div>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label>Course Length</label>
                                <input type="text" wire:model="length" class="form-control" placeholder="e.g. One day, Two days">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Prerequisites</label>
                                <input type="text" wire:model="prerequisites" class="form-control" placeholder="e.g. No prerequisites, Basic Agile knowledge...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Target Audience</label>
                            <input type="text" wire:model="audience" class="form-control" placeholder="Who should attend this class?">
                        </div>

                        <div class="form-divider">Description & Objectives</div>
                        <div class="form-group">
                            <label>Short Summary (Card View) <span style="color:#ef4444;">*</span></label>
                            <textarea wire:model="short_description" class="form-control" rows="2" placeholder="Briefly describe the training class..."></textarea>
                            @error('short_description')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                        
                        <div class="form-grid-2">
                            {{-- TINYMCE FOR MAIN CONTENT --}}
                            <div class="form-group">
                                <label>Main Content Overview (Rich Text) <span style="color:#ef4444;">*</span></label>
                                <div wire:ignore>
                                    <textarea x-data x-init="
                                        let editorInstance = null;
                                        $nextTick(() => {
                                            if (typeof tinymce !== 'undefined') {
                                                tinymce.init({
                                                    target: $el,
                                                    menubar: false,
                                                    plugins: 'lists link code',
                                                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter | bullist numlist | link code',
                                                    setup: function (editor) {
                                                        editorInstance = editor;
                                                        editor.on('blur change', function () {
                                                            $wire.set('content', editor.getContent());
                                                        });
                                                        editor.on('init', function () {
                                                            editor.setContent($wire.get('content') || '');
                                                        });
                                                    }
                                                });
                                            } else {
                                                console.error('TinyMCE failed to load from CDN.');
                                            }
                                        });
                                        return () => { if (editorInstance) { editorInstance.remove(); } }
                                    "></textarea>
                                </div>
                                @error('content')<span class="error-msg">{{$message}}</span>@enderror
                            </div>

                            {{-- TINYMCE FOR LEARNING OBJECTIVES --}}
                            <div class="form-group">
                                <label>Learning Objectives (Rich Text)</label>
                                <div wire:ignore>
                                    <textarea x-data x-init="
                                        let editorInstanceObj = null;
                                        $nextTick(() => {
                                            if (typeof tinymce !== 'undefined') {
                                                tinymce.init({
                                                    target: $el,
                                                    menubar: false,
                                                    plugins: 'lists link code',
                                                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter | bullist numlist | link code',
                                                    setup: function (editor) {
                                                        editorInstanceObj = editor;
                                                        editor.on('blur change', function () {
                                                            $wire.set('learning_objectives', editor.getContent());
                                                        });
                                                        editor.on('init', function () {
                                                            editor.setContent($wire.get('learning_objectives') || '');
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                        return () => { if (editorInstanceObj) { editorInstanceObj.remove(); } }
                                    "></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- DYNAMIC TOPICS BUILDER --}}
                        <div class="form-divider">Curriculum & Topics Builder</div>
                        <div style="background: var(--ivory2); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--ivory3);">
                            
                            @foreach($topicGroups as $groupIndex => $group)
                                <div class="topic-group-card">
                                    <button type="button" wire:click="removeTopicGroup({{ $groupIndex }})" class="btn-remove-group">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Remove Group
                                    </button>
                                    
                                    <div class="form-group">
                                        <label style="color: var(--copper);">Topic Group Header (e.g. Introduction to Scrum)</label>
                                        <input type="text" wire:model="topicGroups.{{ $groupIndex }}.name" class="form-control" placeholder="Group Name">
                                    </div>

                                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--slate); text-transform: uppercase; margin-bottom: 0.6rem;">Sub-Topics / Bullet Points</label>
                                    @foreach($group['items'] as $itemIndex => $item)
                                        <div class="topic-item-row">
                                            <input type="text" wire:model="topicGroups.{{ $groupIndex }}.items.{{ $itemIndex }}" class="form-control" placeholder="Topic concept...">
                                            <button type="button" wire:click="removeTopicItem({{ $groupIndex }}, {{ $itemIndex }})" style="background:none; border:none; color:var(--muted); cursor:pointer; padding:0.5rem;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                        </div>
                                    @endforeach
                                    
                                    <button type="button" wire:click="addTopicItem({{ $groupIndex }})" class="btn-add-topic">+ Add Sub-Topic</button>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addTopicGroup" class="btn-add-group">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Add New Topic Group
                            </button>
                        </div>

                        <div class="form-divider">SEO Settings</div>
                        <div class="form-group"><label>Meta Title <span style="font-weight:400;color:var(--muted);">(max 255 chars)</span></label><input type="text" wire:model="meta_title" class="form-control" maxlength="255">@error('meta_title')<span class="error-msg">{{ $message }}</span>@enderror</div>
                        <div class="form-grid-2">
                            <div class="form-group"><label>Meta Keywords <span style="font-weight:400;color:var(--muted);">(max 255 chars)</span></label><textarea wire:model="meta_keywords" class="form-control" rows="3" maxlength="255"></textarea>@error('meta_keywords')<span class="error-msg">{{ $message }}</span>@enderror</div>
                            <div class="form-group"><label>Meta Description <span style="font-weight:400;color:var(--muted);">(max 160 chars — Google truncates beyond this)</span></label><textarea wire:model="meta_description" class="form-control" rows="3" maxlength="160"></textarea>@error('meta_description')<span class="error-msg">{{ $message }}</span>@enderror</div>
                        </div>

                        <div class="form-divider">Publishing Controls</div>
                        <div style="display: flex; align-items: center; gap: 4rem;">
                            <div class="form-group" style="margin-bottom:0;"><label>Display Order</label><input type="number" wire:model="sort_order" class="form-control" style="width: 130px;"></div>
                            <label style="display: flex; align-items: center; gap: 0.75rem; font-weight: 800; cursor: pointer; font-size: 0.85rem; color: var(--slate); padding-top: 1.25rem;"><input type="checkbox" wire:model="is_active" style="width: 18px; height: 18px; accent-color: var(--copper);"> ACTIVE & PUBLISHED</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; font-size: 0.9rem;">Cancel</button>
                    <button wire:click="save" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(181, 114, 42, 0.3); font-size: 0.95rem;">
                        {{ $serviceId ? 'Update Training Class' : 'Create Training Class' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>