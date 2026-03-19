<div class="lw-service-manager">
    <style>
        /* Shared Admin Styles */
        .lw-service-manager .list-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
        .lw-service-manager .search-box { position: relative; flex: 1; max-width: 400px; }
        .lw-service-manager .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); }
        .lw-service-manager .search-box input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid var(--ivory3); background: var(--white); outline: none; }
        .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--slate); color: var(--white); border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .service-list-container { background: var(--white); border-radius: 12px; border: 1px solid var(--ivory3); overflow: hidden; }
        .service-row { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3); transition: background 0.2s; }
        .service-row:hover { background: var(--ivory); }
        
        .modal-overlay { position: fixed; inset: 0; background: rgba(26, 35, 50, 0.85); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal-window { background: var(--white); width: 100%; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); display: flex; flex-direction: column; position: relative; }
        .close-x { position: absolute; top: 1.25rem; right: 1.25rem; width: 34px; height: 34px; border-radius: 50%; background: var(--ivory); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 11; }
        .modal-header { padding: 1.5rem 2.5rem; border-bottom: 1px solid var(--ivory3); background: var(--ivory2); border-radius: 20px 20px 0 0; }
        .modal-body { padding: 2.5rem; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 1.25rem 2.5rem; background: var(--ivory2); border-top: 1px solid var(--ivory3); display: flex; justify-content: flex-end; gap: 1.25rem; border-radius: 0 0 20px 20px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--slate); text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.08em; }
        .form-control { width: 100%; padding: 0.9rem 1.1rem; border-radius: 10px; border: 1.5px solid var(--ivory3); }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; border: none; background: transparent; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; }
        .icon-btn:hover { background: var(--ivory3); color: var(--slate); }
        .icon-btn.delete:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .status-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; }
        .badge-active { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .badge-inactive { background: var(--ivory3); color: var(--muted); }
        .error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block; font-weight: 600; }
        .preview-box { margin-top: 0.75rem; padding: 0.75rem; border: 1px dashed var(--ivory3); border-radius: 8px; display: inline-block; background: var(--ivory2); }
    </style>

    <div class="list-controls">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live="search" placeholder="Search papers...">
        </div>
        <button wire:click="create" class="btn-create">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Document
        </button>
    </div>

    <div class="service-list-container">
        @foreach($papers as $paper)
            <div class="service-row">
                <div style="width: 50%;">
                    <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $paper->title }}</div>
                    <div style="font-size: 0.75rem; color: var(--muted);">{{ $paper->category?->name ?? 'Uncategorized' }} &bull; {{ $paper->sub_category }}</div>
                </div>
                
                <button wire:click="toggleStatus({{ $paper->id }})" class="status-badge {{ $paper->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $paper->is_active ? 'Active' : 'Hidden' }}
                </button>

                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <div style="text-align: right; margin-right: 1.5rem; font-size: 0.85rem; font-weight: 700;">#{{ $paper->sort_order }}</div>
                    <button wire:click="edit({{ $paper->id }})" class="icon-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                    <button x-on:click="Swal.fire({title:'Delete?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444'}).then((r)=>{if(r.isConfirmed) $wire.deletePaper({{$paper->id}})})" class="icon-btn delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                </div>
            </div>
        @endforeach
        <div style="padding: 1.25rem; background: var(--ivory2);">{{ $papers->links() }}</div>
    </div>

    @if($showModal)
        <div class="modal-overlay" wire:click.self="showModal = false">
            <div class="modal-window" style="max-width: 800px;">
                <button class="close-x" wire:click="showModal = false"><svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                <div class="modal-header"><h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; color: var(--slate);">{{ $paperId ? 'Edit Document' : 'New Document' }}</h2></div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        
                        <div class="form-group">
                            <label>Document Title</label>
                            <input type="text" wire:model="title" class="form-control">
                            @error('title')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Category</label>
                                <select wire:model.live="category_id" class="form-control">
                                    <option value="">Select a category...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                    <option value="new" style="font-weight: bold; color: var(--copper);">+ Create New Category</option>
                                </select>
                                @error('category_id')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            
                            @if($category_id === 'new')
                                <div class="form-group">
                                    <label style="color: var(--copper);">New Category Name</label>
                                    <input type="text" wire:model="new_category_name" class="form-control" placeholder="e.g. Case Studies">
                                    @error('new_category_name')<span class="error-msg">{{$message}}</span>@enderror
                                </div>
                            @else
                                <div class="form-group">
                                    <label>Sub-Category Tag (e.g. Hardware)</label>
                                    <input type="text" wire:model="sub_category" class="form-control">
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Description Summary</label>
                            <textarea wire:model="description" class="form-control" rows="4"></textarea>
                            @error('description')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>PDF / Document Upload</label>
                                <input type="file" wire:model="file" class="form-control">
                                <div wire:loading wire:target="file" style="color:var(--copper); font-size:0.75rem;">Uploading...</div>
                                @if($existing_file && !$file)
                                    <div class="preview-box">
                                        <a href="{{ $existing_file }}" target="_blank" style="color: var(--copper); font-weight:600; font-size: 0.85rem; text-decoration: none;">View Current Attachment</a>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Display Order</label>
                                <input type="number" wire:model="sort_order" class="form-control">
                            </div>
                        </div>

                        <label style="display: flex; align-items: center; gap: 0.75rem; font-weight: 800; cursor: pointer; font-size: 0.85rem;"><input type="checkbox" wire:model="is_active" style="width: 18px; height: 18px; accent-color: var(--copper);"> ACTIVE (VISIBLE)</label>
                    </form>
                </div>
                <div class="modal-footer">
                    <button wire:click="save" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer;">Save Document</button>
                </div>
            </div>
        </div>
    @endif
</div>