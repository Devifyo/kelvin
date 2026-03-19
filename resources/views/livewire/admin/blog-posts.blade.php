<div class="lw-service-manager">
    {{-- Flatpickr CSS and JS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        /* Base Admin Styles */
        .lw-service-manager .list-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
        .lw-service-manager .search-box { position: relative; flex: 1; max-width: 400px; }
        .lw-service-manager .search-box svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); }
        .lw-service-manager .search-box input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border-radius: 10px; border: 1px solid var(--ivory3); background: var(--white); font-size: 0.95rem; color: var(--slate); outline: none; }
        
        .lw-service-manager .filter-tabs { display: flex; background: var(--white); border-radius: 10px; padding: 0.3rem; border: 1px solid var(--ivory3); }
        .lw-service-manager .filter-pill { padding: 0.5rem 1.2rem; border: none; background: transparent; border-radius: 6px; font-size: 0.85rem; font-weight: 600; color: var(--muted); cursor: pointer; transition: all 0.2s; }
        .lw-service-manager .filter-pill.active { background: var(--copper); color: var(--white); }

        .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--slate); color: var(--white); border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: transform 0.2s; }
        
        .service-list-container { background: var(--white); border-radius: 12px; border: 1px solid var(--ivory3); overflow: hidden; }
        .service-row { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3); transition: background 0.2s; }
        .service-row:hover { background: var(--ivory); }
        .service-avatar { width: 44px; height: 44px; border-radius: 10px; background: rgba(181, 114, 42, 0.1); color: var(--copper); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
        .service-avatar img { width: 100%; height: 100%; object-fit: cover; }
        
        /* Modal Structure */
        .modal-overlay { position: fixed; inset: 0; background: rgba(26, 35, 50, 0.85); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal-window { background: var(--white); width: 100%; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); display: flex; flex-direction: column; position: relative; animation: modalPop 0.3s ease-out; }
        @keyframes modalPop { from { opacity: 0; transform: scale(0.98) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        
        .close-x { position: absolute; top: 1.25rem; right: 1.25rem; width: 34px; height: 34px; border-radius: 50%; background: var(--ivory); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 11; color: var(--muted); }
        .close-x:hover { background: #ef4444; color: white; }
        
        .modal-header { padding: 1.5rem 2.5rem; border-bottom: 1px solid var(--ivory3); background: var(--ivory2); border-radius: 20px 20px 0 0; }
        .modal-body { padding: 2.5rem; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 1.25rem 2.5rem; background: var(--ivory2); border-top: 1px solid var(--ivory3); display: flex; justify-content: flex-end; gap: 1.25rem; border-radius: 0 0 20px 20px; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--slate); text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.08em; }
        .form-control { width: 100%; padding: 0.9rem 1.1rem; border-radius: 10px; border: 1.5px solid var(--ivory3); font-size: 0.95rem; color: var(--slate); background: var(--white); }
        
        .form-divider { margin: 2.5rem 0 1.5rem; font-size: 0.75rem; font-weight: 900; color: var(--copper); text-transform: uppercase; border-bottom: 2px solid var(--ivory); padding-bottom: 0.5rem; letter-spacing: 0.1em; }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

        .status-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; }
        .badge-published { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .badge-draft { background: var(--ivory3); color: var(--muted); }
        .badge-inactive { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; border: none; background: transparent; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; }
        .icon-btn:hover { background: var(--ivory3); color: var(--slate); }
        .icon-btn.delete:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block; font-weight: 600; }
        .tox-tinymce { border-radius: 10px !important; border-color: var(--ivory3) !important; }
        
        /* Preview Box Styles */
        .preview-box { margin-top: 0.75rem; padding: 0.75rem; border: 1px dashed var(--ivory3); border-radius: 8px; display: inline-block; background: var(--ivory2); }
        .preview-label { display: block; font-size: 0.65rem; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; }
        .attachment-link { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; background: var(--white); border: 1px solid var(--ivory3); border-radius: 6px; font-size: 0.8rem; font-weight: 600; color: var(--slate); text-decoration: none; transition: all 0.2s; }
        .attachment-link:hover { border-color: var(--copper); color: var(--copper); box-shadow: 0 2px 8px rgba(181, 114, 42, 0.1); }
    </style>

    {{-- List Header Controls --}}
    <div class="list-controls">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live.debounce.300ms="searchTitle" placeholder="Search blog posts...">
        </div>
        <div style="display: flex; gap: 1rem;">
            <div class="filter-tabs">
                <button wire:click="setFilter('all')" class="filter-pill {{ $filterStatus === 'all' ? 'active' : '' }}">All</button>
                <button wire:click="setFilter('published')" class="filter-pill {{ $filterStatus === 'published' ? 'active' : '' }}">Published</button>
                <button wire:click="setFilter('draft')" class="filter-pill {{ $filterStatus === 'draft' ? 'active' : '' }}">Drafts</button>
            </div>
            <button wire:click="create" class="btn-create">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Post
            </button>
        </div>
    </div>

    {{-- Data List --}}
    <div class="service-list-container">
        @forelse($posts as $item)
            <div class="service-row" wire:key="post-{{ $item->id }}">
                <div style="display: flex; align-items: center; gap: 1.25rem; width: 45%;">
                    <div class="service-avatar">
                        @if($item->featured_image_url)
                            <img src="{{ $item->featured_image_url }}" alt="">
                        @else
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $item->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--muted);">{{ $item->category?->name ?? 'Uncategorized' }}</div>
                    </div>
                </div>
                
                <div style="flex: 1; font-size: 0.85rem; color: var(--muted); display: flex; gap: 1rem; align-items: center;">
                    <span>{{ $item->published_at ? $item->published_at->format('M d, Y') : 'No Date' }}</span>
                    <button wire:click="toggleStatus({{ $item->id }})" class="status-badge badge-{{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </button>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('blog.show', $item->slug) }}" target="_blank" class="icon-btn" title="View Live">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </a>
                    <button wire:click="edit({{ $item->id }})" class="icon-btn" title="Edit Post">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button x-on:click="Swal.fire({title:'Delete Post?', text:'This action cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Yes, delete it!'}).then((r)=>{if(r.isConfirmed) $wire.deletePost({{$item->id}})})" class="icon-btn delete" title="Delete Post">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 5rem; color: var(--muted); font-size: 0.9rem;">No blog posts found.</div>
        @endforelse
        <div style="padding: 1.25rem; border-top: 1px solid var(--ivory3); background: var(--ivory2);">{{ $posts->links() }}</div>
    </div>

    {{-- CREATE/EDIT MODAL --}}
    @if($showModal)
        <div class="modal-overlay" wire:click.self="closeModal">
            <div class="modal-window" style="max-width: 1000px;">
                <button class="close-x" wire:click="closeModal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                
                <div class="modal-header">
                    <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; font-weight: 600; color: var(--slate);">{{ $postId ? 'Edit Blog Post' : 'New Blog Post' }}</h2>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Post Title <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model.live.debounce.500ms="title" class="form-control" placeholder="The title of the post...">
                                @error('title')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>URL Slug <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model="slug" class="form-control">
                                @error('slug')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Category <span style="color:#ef4444;">*</span></label>
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
                                    <input type="text" wire:model="new_category_name" class="form-control" placeholder="Enter new category name...">
                                    @error('new_category_name')<span class="error-msg">{{$message}}</span>@enderror
                                </div>
                            @endif
                        </div>

                        <div class="form-divider">Media & Excerpt</div>
                        <div class="form-grid-2">
                            {{-- Featured Image --}}
                            <div class="form-group">
                                <label>Featured Image</label>
                                <input type="file" wire:model="featured_image" class="form-control">
                                <div wire:loading wire:target="featured_image" style="font-size:0.75rem; color:var(--copper); margin-top:4px;">Uploading...</div>
                                @if ($existing_featured_image && !$featured_image)
                                    <div class="preview-box">
                                        <span class="preview-label">Current Cover Image</span>
                                        <img src="{{ $existing_featured_image }}" style="max-height: 120px; border-radius: 6px; border: 1px solid var(--ivory3); object-fit: cover;">
                                    </div>
                                @endif
                                @error('featured_image')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            
                            {{-- PDF Attachment --}}
                            <div class="form-group">
                                <label>PDF Attachment (Optional)</label>
                                <input type="file" wire:model="attachment" class="form-control">
                                <div wire:loading wire:target="attachment" style="font-size:0.75rem; color:var(--copper); margin-top:4px;">Uploading...</div>
                                @if ($existing_attachment && !$attachment)
                                    <div class="preview-box" style="display: block;">
                                        <span class="preview-label">Current File Attached</span>
                                        <a href="{{ $existing_attachment }}" target="_blank" class="attachment-link">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                            View / Download Attachment
                                        </a>
                                    </div>
                                @endif
                                @error('attachment')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Short Excerpt (Card Summary)</label>
                            <textarea wire:model="excerpt" class="form-control" rows="2"></textarea>
                        </div>
                        
                        <div class="form-divider">Blog Content</div>
                        <div class="form-group">
                            <label>Main Body (Rich Text) <span style="color:#ef4444;">*</span></label>
                            <div wire:ignore>
                                <textarea x-data x-init="
                                    let editorInstance = null;
                                    $nextTick(() => {
                                        if (typeof tinymce !== 'undefined') {
                                            tinymce.init({
                                                target: $el,
                                                menubar: true,
                                                plugins: 'lists link image media code table',
                                                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image media | code',
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
                            @error('content')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-divider">Publishing & SEO</div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select wire:model="status" class="form-control">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Publish Strategy</label>
                                <select wire:model.live="publish_strategy" class="form-control">
                                    <option value="now">Publish Immediately</option>
                                    <option value="custom">{{ $postId ? 'Keep / Set Specific Date' : 'Schedule Specific Date' }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- DYNAMIC TIMEZONE SAFE CALENDAR --}}
                        @if($publish_strategy === 'custom')
                        <div class="form-group" wire:ignore>
                            <label>Select Local Date & Time</label>
                            <input type="text" class="form-control" placeholder="Select date & time..." style="border-color: var(--copper); box-shadow: 0 0 0 2px rgba(181, 114, 42, 0.1);" x-data x-init="
                                setTimeout(() => {
                                    if (typeof flatpickr !== 'undefined') {
                                        flatpickr($el, {
                                            enableTime: true,
                                            dateFormat: 'F j, Y h:i K', // User friendly format
                                            defaultDate: $wire.get('published_at') ? new Date($wire.get('published_at')) : new Date(),
                                            onChange: function(selectedDates) {
                                                if (selectedDates.length > 0) {
                                                    // Convert local selection to strict UTC before sending to Livewire
                                                    $wire.set('published_at', selectedDates[0].toISOString());
                                                }
                                            }
                                        });
                                    } else {
                                        console.error('Flatpickr failed to load.');
                                    }
                                }, 100);
                            ">
                            <span style="font-size:0.75rem; color:var(--muted); margin-top:8px; display:block;">
                                Timezone detected automatically: <strong style="color:var(--slate);" x-data x-text="Intl.DateTimeFormat().resolvedOptions().timeZone"></strong>. (Saved as UTC backend).
                            </span>
                        </div>
                        @endif

                        <div class="form-grid-2">
                            <div class="form-group"><label>Meta Title</label><input type="text" wire:model="meta_title" class="form-control"></div>
                            <div class="form-group"><label>Meta Keywords</label><input type="text" wire:model="meta_keywords" class="form-control"></div>
                        </div>
                        <div class="form-group"><label>Meta Description</label><textarea wire:model="meta_description" class="form-control" rows="2"></textarea></div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; font-size: 0.9rem;">Cancel</button>
                    <button wire:click="save" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(181, 114, 42, 0.3); font-size: 0.95rem;">
                        {{ $postId ? 'Update Post' : 'Create Post' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>