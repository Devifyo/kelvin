<div class="lw-service-manager">
    <style>
        /* Base Styles */
        .lw-service-manager .list-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
        .lw-service-manager .search-box { position: relative; flex: 1; max-width: 400px; }
        .lw-service-manager .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); }
        .lw-service-manager .search-box input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid var(--ivory3); background: var(--white); font-size: 0.95rem; color: var(--slate); outline: none; }
        .lw-service-manager .search-box input:focus { border-color: var(--copper); }
        
        .lw-service-manager .filter-tabs { display: flex; background: var(--white); border-radius: 10px; padding: 0.3rem; border: 1px solid var(--ivory3); }
        .lw-service-manager .filter-pill { padding: 0.5rem 1.2rem; border: none; background: transparent; border-radius: 6px; font-size: 0.85rem; font-weight: 600; color: var(--muted); cursor: pointer; transition: all 0.2s; }
        .lw-service-manager .filter-pill.active { background: var(--copper); color: var(--white); }

        .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--slate); color: var(--white); border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: transform 0.2s; }
        .btn-create:hover { transform: translateY(-1px); background: var(--charcoal); }
        
        .lw-service-manager .service-list-container { background: var(--white); border-radius: 12px; border: 1px solid var(--ivory3); overflow: hidden; }
        .lw-service-manager .service-row { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3); transition: background 0.2s; }
        .lw-service-manager .service-row:hover { background: var(--ivory); }
        .lw-service-manager .service-avatar { width: 44px; height: 44px; border-radius: 10px; background: rgba(181, 114, 42, 0.1); color: var(--copper); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        
        /* Modal Structure */
        .modal-overlay { position: fixed; inset: 0; background: rgba(26, 35, 50, 0.85); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal-window { background: var(--white); width: 100%; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); display: flex; flex-direction: column; position: relative; animation: modalPop 0.3s ease-out; }
        @keyframes modalPop { from { opacity: 0; transform: scale(0.98) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        
        .close-x { position: absolute; top: 1.25rem; right: 1.25rem; width: 34px; height: 34px; border-radius: 50%; background: var(--ivory); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; z-index: 11; color: var(--muted); }
        .close-x:hover { background: #ef4444; color: white; transform: rotate(90deg); }
        
        .modal-header { padding: 1.5rem 2.5rem; border-bottom: 1px solid var(--ivory3); background: var(--ivory2); border-radius: 20px 20px 0 0; }
        .modal-body { padding: 2.5rem; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 1.25rem 2.5rem; background: var(--ivory2); border-top: 1px solid var(--ivory3); display: flex; justify-content: flex-end; gap: 1.25rem; border-radius: 0 0 20px 20px; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--slate); text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.08em; }
        .form-control { width: 100%; padding: 0.9rem 1.1rem; border-radius: 10px; border: 1.5px solid var(--ivory3); font-size: 0.95rem; color: var(--slate); background: var(--white); transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: var(--copper); box-shadow: 0 0 0 4px rgba(181, 114, 42, 0.1); }
        
        .form-divider { margin: 2.5rem 0 1.5rem; font-size: 0.75rem; font-weight: 900; color: var(--copper); text-transform: uppercase; border-bottom: 2px solid var(--ivory); padding-bottom: 0.5rem; letter-spacing: 0.1em; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
        
        /* Topics Builder Styles */
        .topic-group-card { background: var(--ivory); border: 1px solid var(--ivory3); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; position: relative; }
        .btn-remove-group { position: absolute; top: 1rem; right: 1rem; background: transparent; border: none; color: #ef4444; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; }
        .topic-item-row { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; }
        .btn-add-topic { background: var(--white); border: 1px dashed var(--ivory3); color: var(--copper); font-weight: 700; font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; margin-top: 0.5rem; width: 100%; transition: all 0.2s; }
        .btn-add-topic:hover { border-color: var(--copper); background: rgba(181, 114, 42, 0.05); }
        .btn-add-group { background: var(--slate); color: var(--white); border: none; font-weight: 700; font-size: 0.85rem; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; }

        .status-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; }
        .badge-active { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .badge-draft { background: var(--ivory3); color: var(--muted); }
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; border: none; background: transparent; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; }
        .icon-btn:hover { background: var(--ivory3); color: var(--slate); }
        .icon-btn.delete:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block; font-weight: 600; }
        
        /* TinyMCE Form Adjustment */
        .tox-tinymce { border-radius: 10px !important; border-color: var(--ivory3) !important; }
    </style>

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
        @forelse($services as $item)
            <div class="service-row" wire:key="train-{{ $item->id }}">
                <div style="display: flex; align-items: center; gap: 1.25rem; width: 45%;">
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
        <div style="padding: 1.25rem; border-top: 1px solid var(--ivory3); background: var(--ivory2);">{{ $services->links() }}</div>
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
                        <div class="form-group"><label>Page Meta Title</label><input type="text" wire:model="meta_title" class="form-control"></div>
                        <div class="form-grid-2">
                            <div class="form-group"><label>Meta Keywords</label><textarea wire:model="meta_keywords" class="form-control" rows="3"></textarea></div>
                            <div class="form-group"><label>Meta Description</label><textarea wire:model="meta_description" class="form-control" rows="3"></textarea></div>
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