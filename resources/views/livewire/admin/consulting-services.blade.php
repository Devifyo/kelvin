<div class="lw-service-manager">
    {{-- Internal Styles for scoping and layout --}}
    <style>
        .lw-service-manager .list-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
        .lw-service-manager .search-box { position: relative; flex: 1; max-width: 400px; }
        .lw-service-manager .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); }
        .lw-service-manager .search-box input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid var(--ivory3); background: var(--white); font-size: 0.95rem; color: var(--slate); outline: none; transition: border-color 0.2s; }
        .lw-service-manager .search-box input:focus { border-color: var(--copper); }
        
        .lw-service-manager .filter-tabs { display: flex; background: var(--white); border-radius: 10px; padding: 0.3rem; border: 1px solid var(--ivory3); }
        .lw-service-manager .filter-pill { padding: 0.5rem 1.2rem; border: none; background: transparent; border-radius: 6px; font-size: 0.85rem; font-weight: 600; color: var(--muted); cursor: pointer; transition: all 0.2s; }
        .lw-service-manager .filter-pill.active { background: var(--copper); color: var(--white); box-shadow: 0 2px 8px rgba(181, 114, 42, 0.3); }

        .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--slate); color: var(--white); border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: transform 0.2s; }
        .btn-create:hover { transform: translateY(-1px); background: var(--charcoal); }
        
        .lw-service-manager .service-list-container { background: var(--white); border-radius: 12px; border: 1px solid var(--ivory3); overflow: hidden; }
        .lw-service-manager .service-row { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3); transition: background 0.2s; }
        .lw-service-manager .service-row:last-child { border-bottom: none; }
        .lw-service-manager .service-row:hover { background: var(--ivory); }

        .lw-service-manager .service-avatar { width: 44px; height: 44px; border-radius: 10px; background: rgba(181, 114, 42, 0.1); color: var(--copper); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
        .lw-service-manager .service-avatar img, .lw-service-manager .service-avatar svg { width: 24px; height: 24px; object-fit: contain; }

        /* Modal Structure */
        .modal-overlay { position: fixed; inset: 0; background: rgba(26, 35, 50, 0.85); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal-window { background: var(--white); width: 100%; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); display: flex; flex-direction: column; position: relative; animation: modalPop 0.3s ease-out; }
        @keyframes modalPop { from { opacity: 0; transform: scale(0.98) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        
        .close-x { position: absolute; top: 1.25rem; right: 1.25rem; width: 34px; height: 34px; border-radius: 50%; background: var(--ivory); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; z-index: 11; color: var(--muted); }
        .close-x:hover { background: #ef4444; color: white; transform: rotate(90deg); }
        
        .modal-header { padding: 1.5rem 2.5rem; border-bottom: 1px solid var(--ivory3); background: var(--ivory2); border-radius: 20px 20px 0 0; }
        .modal-body { padding: 2.5rem; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 1.25rem 2.5rem; background: var(--ivory2); border-top: 1px solid var(--ivory3); display: flex; justify-content: flex-end; gap: 1.25rem; border-radius: 0 0 20px 20px; }

        /* Form Controls */
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--slate); text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.08em; }
        .form-control { width: 100%; padding: 0.9rem 1.1rem; border-radius: 10px; border: 1.5px solid var(--ivory3); font-size: 0.95rem; color: var(--slate); background: var(--white); transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: var(--copper); box-shadow: 0 0 0 4px rgba(181, 114, 42, 0.1); }
        
        .form-divider { margin: 2.5rem 0 1.5rem; font-size: 0.75rem; font-weight: 900; color: var(--copper); text-transform: uppercase; border-bottom: 2px solid var(--ivory); padding-bottom: 0.5rem; letter-spacing: 0.1em; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        
        .image-preview-box { width: 85px; height: 85px; background: white; border-radius: 12px; border: 1.5px solid var(--ivory3); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); position: relative; }
        .image-preview-box img, .image-preview-box svg { width: 45px; height: 45px; object-fit: contain; }
        
        .status-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; transition: opacity 0.2s; }
        .badge-active { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .badge-draft { background: var(--ivory3); color: var(--muted); }
        
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; border: none; background: transparent; cursor: pointer; color: var(--muted); transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .icon-btn:hover { background: var(--ivory3); color: var(--slate); }
        .icon-btn.delete:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block; font-weight: 600; }
        
        /* Added for Image Upload Spinner */
        .animate-spin { animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>

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
        @forelse($services as $item)
            <div class="service-row" wire:key="consult-{{ $item->id }}">
                <div style="display: flex; align-items: center; gap: 1.25rem; width: 45%;">
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
        <div style="padding: 1.25rem; border-top: 1px solid var(--ivory3); background: var(--ivory2);">{{ $services->links() }}</div>
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