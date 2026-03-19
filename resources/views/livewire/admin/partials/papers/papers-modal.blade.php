<div class="modal-overlay" wire:click.self="$set('showModal', false)">
    <div class="modal-window" style="max-width: 800px;">
        
        {{-- Fixed Close Button --}}
        <button type="button" class="close-x" wire:click="$set('showModal', false)">
            <svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" fill="none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        
        <div class="modal-header">
            <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; color: var(--slate); margin:0;">
                {{ $paperId ? 'Edit Document' : 'New Document' }}
            </h2>
        </div>
        
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
                    <label>Description Summary (Rich Text)</label>
                    <div wire:ignore>
                        <textarea x-data x-init="
                            let editorInstance = null;
                            $nextTick(() => {
                                if (typeof tinymce !== 'undefined') {
                                    tinymce.init({
                                        target: $el,
                                        menubar: false,
                                        plugins: 'lists link code',
                                        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link | code',
                                        setup: function (editor) {
                                            editorInstance = editor;
                                            editor.on('blur change', function () {
                                                $wire.set('description', editor.getContent());
                                            });
                                            editor.on('init', function () {
                                                editor.setContent($wire.get('description') || '');
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

                <label style="display: flex; align-items: center; gap: 0.75rem; font-weight: 800; cursor: pointer; font-size: 0.85rem;">
                    <input type="checkbox" wire:model="is_active" style="width: 18px; height: 18px; accent-color: var(--copper);"> 
                    ACTIVE (VISIBLE)
                </label>
            </form>
        </div>
        
        <div class="modal-footer">
            <button type="button" wire:click="$set('showModal', false)" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; padding: 0.9rem 1.5rem;">Cancel</button>
            <button type="button" wire:click="save" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer;">Save Document</button>
        </div>
        
    </div>
</div>