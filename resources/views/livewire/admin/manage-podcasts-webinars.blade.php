<div class="lw-service-manager">
    <link href="{{ asset('css/admin/manage-podcasts-webinars.css') }}" rel="stylesheet">
    @push('styles')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
    <style>
        .plyr { border-radius: 8px; --plyr-color-main: var(--copper, #b87333); }
        .video-preview-overlay { position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:9999;display:flex;align-items:center;justify-content:center; }
        .video-preview-inner { position:relative;width:min(860px,96vw); }
        .video-preview-close { position:absolute;top:-38px;right:0;background:none;border:none;color:#fff;font-size:1.6rem;cursor:pointer;line-height:1; }
    </style>
    @endpush
    @push('scripts')
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    @endpush

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
                        {{ $item->type }} &bull; {{ $item->video_path ? 'Uploaded Video' : ($item->platform ?? 'External') }}
                    </div>
                </div>
                
                <div style="flex: 1; font-size: 0.85rem; color: var(--muted);">
                    {{ $item->published_date ? $item->published_date->format('M d, Y') : 'No Date' }}
                </div>

                <button wire:click="toggleStatus({{ $item->id }})" class="status-badge {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $item->is_active ? 'Active' : 'Hidden' }}
                </button>

                <div style="display: flex; gap: 0.75rem; align-items: center; margin-left: 1.5rem;">
                    @if($item->video_path)
                    <button type="button" class="icon-btn" title="Preview Video"
                            x-on:click="$dispatch('open-video-preview', { url: '{{ $item->video_url }}' })">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                    </button>
                    @elseif($item->url)
                    <a href="{{ $item->url }}" target="_blank" class="icon-btn" title="Open Link"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg></a>
                    @endif
                    <button wire:click="edit({{ $item->id }})" class="icon-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                    <button x-on:click="Swal.fire({title:'Delete?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444'}).then((r)=>{if(r.isConfirmed) $wire.deleteMedia({{$item->id}})})" class="icon-btn delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 5rem; color: var(--muted); font-size: 0.9rem;">No podcasts or webinars added yet.</div>
        @endforelse
        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ivory3); background: var(--ivory);">
            {{ $mediaItems->links('vendor.pagination.admin-theme') }}
        </div>
    </div>

    {{-- Video preview overlay (list view) --}}
    <div x-data="{ open: false, url: '', plyr: null }"
         x-on:open-video-preview.window="open = true; url = $event.detail.url; $nextTick(() => { plyr = new Plyr($refs.previewVideo, { autoplay: true, controls: ['play','progress','current-time','duration','mute','volume','fullscreen'] }); })"
         x-on:keydown.escape.window="open && closePreview()"
         x-on:close-preview.window="closePreview()"
         x-init="closePreview = () => { open = false; if (plyr) { plyr.pause(); plyr.destroy(); plyr = null; } url = ''; }">
        <div class="video-preview-overlay" x-show="open" x-cloak x-on:click.self="closePreview()">
            <div class="video-preview-inner">
                <button class="video-preview-close" x-on:click="closePreview()" aria-label="Close">&times;</button>
                <video x-ref="previewVideo" playsinline style="width:100%;border-radius:8px;">
                    <source :src="url">
                </video>
            </div>
        </div>
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
                            <label>Media Source</label>
                            <div class="source-toggle">
                                <button type="button" wire:click="$set('videoSource', 'url')"
                                        class="toggle-btn {{ $videoSource === 'url' ? 'active' : '' }}">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                    Direct URL / Link
                                </button>
                                <button type="button" wire:click="$set('videoSource', 'upload')"
                                        class="toggle-btn {{ $videoSource === 'upload' ? 'active' : '' }}">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                                    Upload Video
                                </button>
                            </div>
                        </div>

                        @if($videoSource === 'url')
                        <div class="form-group">
                            <label>Direct URL / Link</label>
                            <input type="url" wire:model="url" class="form-control" placeholder="https://...">
                            @error('url')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                        @else
                        <div class="form-group"
                             x-data="{ progress: 0, uploading: false }"
                             x-on:livewire-upload-start="uploading = true; progress = 0"
                             x-on:livewire-upload-finish="uploading = false; progress = 0"
                             x-on:livewire-upload-error="uploading = false; progress = 0"
                             x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <label>Video File <span style="color:var(--muted);font-weight:400;text-transform:none;">(MP4, MOV, WebM, MKV — max 500 MB)</span></label>
                            <input type="file" wire:model="videoFile" class="form-control" accept="video/mp4,video/quicktime,video/webm,video/x-matroska">
                            <div x-show="uploading" style="margin-top:0.5rem;">
                                <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.75rem;color:var(--copper);margin-bottom:0.3rem;">
                                    <span>Uploading video...</span>
                                    <span x-text="progress + '%'"></span>
                                </div>
                                <div style="background:#e5e7eb;border-radius:9999px;height:6px;overflow:hidden;">
                                    <div :style="{ width: progress + '%', background: 'var(--copper)', height: '100%', borderRadius: '9999px', transition: 'width 0.2s ease' }"></div>
                                </div>
                            </div>
                            @if($existing_video && !$videoFile)
                                <div class="preview-box" style="margin-top:0.75rem;display:block;"
                                     x-data x-init="$nextTick(() => new Plyr($el.querySelector('video'), { controls: ['play','progress','current-time','duration','mute','volume','fullscreen'] }))">
                                    <video playsinline>
                                        <source src="{{ $existing_video }}">
                                    </video>
                                    <div style="font-size:0.75rem;color:var(--muted);margin-top:0.5rem;">Current video — upload a new file to replace it.</div>
                                </div>
                            @endif
                            @error('videoFile')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                        @endif

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