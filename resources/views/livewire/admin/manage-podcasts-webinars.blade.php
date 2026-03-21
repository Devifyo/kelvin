<div class="lw-service-manager">
    <style>
        .lw-service-manager .list-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
        .lw-service-manager .search-box { position: relative; flex: 1; max-width: 400px; }
        .lw-service-manager .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); }
        .lw-service-manager .search-box input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid var(--ivory3); outline: none; }
        .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--slate); color: var(--white); border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .service-list-container { background: var(--white); border-radius: 12px; border: 1px solid var(--ivory3); overflow: hidden; }
        .service-row { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3); }
        .service-row:hover { background: var(--ivory); }
        .modal-overlay { position: fixed; inset: 0; background: rgba(26, 35, 50, 0.85); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal-window { background: var(--white); width: 100%; border-radius: 20px; display: flex; flex-direction: column; position: relative; }
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
            <input type="text" wire:model.live="search" placeholder="Search podcasts & webinars...">
        </div>
        <button wire:click="create" class="btn-create">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Link
        </button>
    </div>

    <div class="service-list-container">
        @forelse($mediaItems as $item)
            <div class="service-row">
                <div style="width: 50%;">
                    <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $item->title }}</div>
                    <div style="font-size: 0.75rem; color: var(--muted); text-transform: uppercase;">
                        {{ $item->type }} &bull; {{ $item->platform ?? 'External' }}
                    </div>
                </div>
                
                <div style="flex: 1; font-size: 0.85rem; color: var(--muted);">
                    {{ $item->published_date ? $item->published_date->format('M d, Y') : 'No Date' }}
                </div>

                <button wire:click="toggleStatus({{ $item->id }})" class="status-badge {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $item->is_active ? 'Active' : 'Hidden' }}
                </button>

                <div style="display: flex; gap: 0.75rem; align-items: center; margin-left: 1.5rem;">
                    <a href="{{ $item->url }}" target="_blank" class="icon-btn" title="Test Link"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg></a>
                    <button wire:click="edit({{ $item->id }})" class="icon-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                    <button x-on:click="Swal.fire({title:'Delete?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444'}).then((r)=>{if(r.isConfirmed) $wire.deleteMedia({{$item->id}})})" class="icon-btn delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 5rem; color: var(--muted); font-size: 0.9rem;">No podcasts or webinars added yet.</div>
        @endforelse
        <div style="padding: 1.25rem; background: var(--ivory2);">{{ $mediaItems->links() }}</div>
    </div>

    @if($showModal)
        <div class="modal-overlay" wire:click.self="$set('showModal', false)">
            <div class="modal-window" style="max-width: 800px;">
                <button type="button" class="close-x" wire:click="$set('showModal', false)">
                    <svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" fill="none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                
                <div class="modal-header">
                    <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; color: var(--slate); margin:0;">
                        {{ $mediaId ? 'Edit Item' : 'New Podcast/Webinar' }}
                    </h2>
                </div>
                
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" wire:model="title" class="form-control" placeholder="e.g. Agile Hardware on The Tech Podcast">
                            @error('title')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Format Type</label>
                                <select wire:model="type" class="form-control">
                                    <option value="podcast">Podcast Episode</option>
                                    <option value="webinar">Webinar / Video</option>
                                    <option value="interview">Interview</option>
                                </select>
                                @error('type')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Platform Name (Optional)</label>
                                <input type="text" wire:model="platform" class="form-control" placeholder="e.g. YouTube, Spotify, Zoom">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Direct URL / Link</label>
                            <input type="url" wire:model="url" class="form-control" placeholder="https://...">
                            @error('url')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Short Description / Summary</label>
                            <textarea wire:model="description" class="form-control" rows="3" placeholder="What was discussed? Keep it brief for the card layout."></textarea>
                            @error('description')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Thumbnail Image Upload</label>
                                <input type="file" wire:model="thumbnail_image" class="form-control">
                                <div wire:loading wire:target="thumbnail_image" style="color:var(--copper); font-size:0.75rem;">Uploading...</div>
                                @if($existing_thumbnail && !$thumbnail_image)
                                    <div class="preview-box">
                                        <img src="{{ $existing_thumbnail }}" style="max-height: 80px; border-radius: 4px; object-fit: cover;">
                                    </div>
                                @endif
                                @error('thumbnail_image')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            
                            <div class="form-group">
                                <label>Publish Date</label>
                                <input type="date" wire:model="published_date" class="form-control">
                                @error('published_date')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        </div>

                        <label style="display: flex; align-items: center; gap: 0.75rem; font-weight: 800; cursor: pointer; font-size: 0.85rem;">
                            <input type="checkbox" wire:model="is_active" style="width: 18px; height: 18px; accent-color: var(--copper);"> 
                            ACTIVE (VISIBLE)
                        </label>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" wire:click="$set('showModal', false)" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; padding: 0.9rem 1.5rem;">Cancel</button>
                    <button type="button" wire:click="save" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer;">Save Link</button>
                </div>
                
            </div>
        </div>
    @endif
</div>