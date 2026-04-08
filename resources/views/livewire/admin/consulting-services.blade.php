<div class="lw-service-manager">
    {{-- Internal Styles for scoping and layout --}}
    <link href="{{ asset('css/admin/consulting-services.css') }}" rel="stylesheet">

    {{-- List Header Controls --}}
    <div class="list-controls">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live.debounce.300ms="searchTitle" placeholder="Search consulting services...">
        </div>
        <div style="display: flex; gap: 1rem;">
            <div class="filter-tabs">
                <button wire:click="setFilter('all')" class="filter-pill {{ $filterStatus === 'all' ? 'active' : '' }}">All</button>
                <button wire:click="setFilter('active')" class="filter-pill {{ $filterStatus === 'active' ? 'active' : '' }}">Active</button>
                <button wire:click="setFilter('draft')" class="filter-pill {{ $filterStatus === 'draft' ? 'active' : '' }}">Drafts</button>
            </div>
            <button wire:click="create" class="btn-create">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Service
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
                <div class="service-row" wire:key="consult-{{ $item->id }}" data-id="{{ $item->id }}">
                    <div style="display: flex; align-items: center; gap: 1.25rem; width: 45%;">
                        
                        <div class="drag-handle" style="cursor: grab; color: var(--muted); display: flex; align-items: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="8" cy="4" r="1.5"/><circle cx="16" cy="4" r="1.5"/>
                                <circle cx="8" cy="12" r="1.5"/><circle cx="16" cy="12" r="1.5"/>
                                <circle cx="8" cy="20" r="1.5"/><circle cx="16" cy="20" r="1.5"/>
                            </svg>
                        </div>

                        <div class="service-avatar">
                            @if($item->featured_image) 
                                <img src="{{ asset('storage/'.$item->featured_image) }}">
                            @elseif($item->icon) 
                                <div style="width: 24px; height: 24px;">{!! $item->icon !!}</div>
                            @else 
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg> 
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $item->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted); font-family: monospace;">/{{ $item->slug }}</div>
                        </div>
                    </div>
                    
                    <button wire:click="toggleStatus({{ $item->id }})" class="status-badge {{ $item->is_active ? 'badge-active' : 'badge-draft' }}">
                        {{ $item->is_active ? 'Active' : 'Draft' }}
                    </button>

                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <div style="text-align: right; margin-right: 1.5rem;">
                            <div style="font-size: 0.6rem; font-weight: 800; color: var(--muted);">SORT</div>
                            <div style="font-size: 0.85rem; font-weight: 700;">#{{ $item->sort_order }}</div>
                        </div>
                        <button wire:click="edit({{ $item->id }})" class="icon-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                        <button x-on:click="Swal.fire({title:'Delete Service?', text:'This action cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete'}).then((r)=>{if(r.isConfirmed) $wire.deleteService({{$item->id}})})" class="icon-btn delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 5rem; color: var(--muted); font-size: 0.9rem;">No consulting services found matching your criteria.</div>
            @endforelse
        </div>

        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ivory3); background: var(--ivory);">
            {{ $services->links('vendor.pagination.admin-theme') }}
        </div>
    </div>

    {{-- CREATE/EDIT MODAL --}}
    @if($showModal)
        <div class="modal-overlay" wire:click.self="closeModal">
            <div class="modal-window" style="max-width: 900px;">
                <button class="close-x" wire:click="closeModal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                
                <div class="modal-header">
                    <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; font-weight: 600; color: var(--slate);">{{ $serviceId ? 'Edit Consulting Service' : 'New Consulting Service' }}</h2>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Service Title <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="title" class="form-control" placeholder="e.g. Agile Assessment">
                                @error('title')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>URL Slug <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model="slug" class="form-control" placeholder="agile-assessment">
                                @error('slug')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-divider">Visual Identity</div>
                        <div style="display: flex; gap: 2rem; background: var(--ivory); padding: 1.75rem; border-radius: 15px; border: 1px solid var(--ivory3); align-items: center;">
                            
                            <div class="image-preview-box">
                                {{-- Loading Spinner --}}
                                <div wire:loading.flex wire:target="icon_file" style="position: absolute; inset: 0; background: rgba(255,255,255,0.8); align-items: center; justify-content: center; z-index: 10;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--copper)" stroke-width="2" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                </div>

                                {{-- Image Preview Logic --}}
                                @if($icon_file && !$errors->has('icon_file')) 
                                    <img src="{{ $icon_file->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: contain;">
                                @elseif($existing_icon_path) 
                                    <img src="{{ asset('storage/'.$existing_icon_path) }}" style="width: 100%; height: 100%; object-fit: contain;">
                                @elseif($icon) 
                                    <div style="width: 40px; height: 40px; color: var(--copper);">{!! $icon !!}</div> 
                                @else 
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #cbd5e1;"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg> 
                                @endif
                            </div>

                            <div style="flex: 1; display: grid; grid-template-columns: 1.2fr 1.8fr; gap: 1.5rem; align-items: start;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Option A: Upload File</label>
                                    <input type="file" wire:model="icon_file" accept="image/*" style="font-size: 0.8rem; margin-top: 0.25rem;">
                                    @error('icon_file')<span class="error-msg">{{$message}}</span>@enderror
                                    <div style="font-size: 0.65rem; color: var(--muted); margin-top: 0.5rem; line-height: 1.4;">Ideal for PNG/JPG icons or specific SVG files. Max 2MB.</div>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Option B: Raw SVG Code</label>
                                    {{-- Removed .live to prevent crashes with huge strings --}}
                                    <textarea wire:model="icon" class="form-control" rows="2" placeholder='<path d="..." />' style="font-family: monospace; font-size: 0.85rem;"></textarea>
                                    @error('icon')<span class="error-msg">{{$message}}</span>@enderror
                                    <div style="font-size: 0.65rem; color: var(--muted); margin-top: 0.5rem;">Paste the SVG path or code here to allow theme color syncing.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-divider">Service Details</div>
                        <div class="form-group"><label>Short Summary (Card Description)</label><textarea wire:model="short_description" class="form-control" rows="2" placeholder="Briefly describe the service for the list view..."></textarea>@error('short_description')<span class="error-msg">{{$message}}</span>@enderror</div>
                        <div class="form-group"><label>Main Page Content (Markdown)</label><textarea wire:model="content" class="form-control" rows="6" placeholder="Enter the full service description here..."></textarea>@error('content')<span class="error-msg">{{$message}}</span>@enderror</div>

                        <div class="form-divider">SEO Settings</div>
                        <div class="form-group"><label>Page Meta Title</label><input type="text" wire:model="meta_title" class="form-control" placeholder="Search engine title tag"></div>
                        <div class="form-grid-2">
                            <div class="form-group"><label>Meta Keywords</label><textarea wire:model="meta_keywords" class="form-control" rows="3" placeholder="Keyword 1, Keyword 2, etc."></textarea></div>
                            <div class="form-group"><label>Meta Description</label><textarea wire:model="meta_description" class="form-control" rows="3" placeholder="Brief search result snippet (approx 155 chars)"></textarea></div>
                        </div>

                        <div class="form-divider">Publishing Controls</div>
                        <div style="display: flex; align-items: center; gap: 4rem;">
                            <div class="form-group" style="margin-bottom:0;"><label>Display Order</label><input type="number" wire:model="sort_order" class="form-control" style="width: 130px;"></div>
                            <label style="display: flex; align-items: center; gap: 0.75rem; font-weight: 800; cursor: pointer; font-size: 0.85rem; color: var(--slate); padding-top: 1.25rem;"><input type="checkbox" wire:model="is_active" style="width: 18px; height: 18px; accent-color: var(--copper);"> ACTIVE & PUBLISHED</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" type="button" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; font-size: 0.9rem;">Cancel</button>
                    <button wire:click="save" type="button" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(181, 114, 42, 0.3); font-size: 0.95rem;">
                        {{ $serviceId ? 'Update Consulting Service' : 'Create Consulting Service' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>