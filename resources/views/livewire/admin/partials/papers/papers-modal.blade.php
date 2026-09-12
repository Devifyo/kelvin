<div class="modal-overlay" wire:click.self="closeModal">
    <div class="modal-window rl-modal" style="max-width: 980px;" x-data="{ tab: @entangle('modalTab') }">

        {{-- Fixed Close Button --}}
        <button type="button" class="close-x" wire:click="closeModal">
            <svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" fill="none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="modal-header">
            <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; color: var(--slate); margin:0;">
                {{ $paperId ? 'Edit Resource' : 'New Resource' }}
            </h2>
            @if($paperId && $slug)
                <a href="{{ route('papers.show', $slug) }}" target="_blank" rel="noopener" class="rl-modal-live">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    View live page
                </a>
            @endif

            {{-- Tabs --}}
            <div class="rl-tabs" role="tablist">
                <button type="button" role="tab" class="rl-tab" :class="{ active: tab === 'details' }" x-on:click="tab = 'details'">
                    Details
                    @if($errors->hasAny(['title','slug','category_id','new_category_name','sub_category','resource_type','description','file','external_url','cta_label','sort_order']))
                        <span class="rl-tab-err" title="This tab has errors"></span>
                    @endif
                </button>
                <button type="button" role="tab" class="rl-tab" :class="{ active: tab === 'page' }" x-on:click="tab = 'page'">
                    Resource Page
                    @if($errors->hasAny(['long_description','featured_image']))<span class="rl-tab-err"></span>@endif
                </button>
                <button type="button" role="tab" class="rl-tab" :class="{ active: tab === 'seo' }" x-on:click="tab = 'seo'">
                    SEO
                    @if($errors->hasAny(['meta_title','meta_description','meta_keywords']))<span class="rl-tab-err"></span>@endif
                </button>
            </div>
        </div>

        <div class="modal-body">
            <form wire:submit.prevent="save">

                {{-- ══════════════ TAB: DETAILS ══════════════ --}}
                <div x-show="tab === 'details'" x-cloak>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Title <span class="rl-req">*</span></label>
                            <input type="text" wire:model.live.debounce.400ms="title" class="form-control" placeholder="e.g. The Agile PMO">
                            @error('title')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>URL Slug <span class="rl-req">*</span></label>
                            <div class="rl-slug-wrap">
                                <span class="rl-slug-prefix">/agile-hardware-papers-and-presentations/</span>
                                <input type="text" wire:model.blur="slug" class="form-control" placeholder="the-agile-pmo">
                            </div>
                            @error('slug')<span class="error-msg">{{$message}}</span>@enderror
                            @if($paperId)
                                <span class="rl-help">Changing the slug changes the public URL. Old links to this page will stop working.</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Category <span class="rl-req">*</span></label>
                            <select wire:model.live="category_id" class="form-control">
                                <option value="">Select a category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                                <option value="new" style="font-weight: bold; color: var(--copper);">+ Create New Category</option>
                            </select>
                            @error('category_id')<span class="error-msg">{{$message}}</span>@enderror
                            @if($category_id === 'new')
                                <input type="text" wire:model="new_category_name" class="form-control" placeholder="New category name, e.g. Books" style="margin-top:.6rem;">
                                @error('new_category_name')<span class="error-msg">{{$message}}</span>@enderror
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Sub-Category Tag</label>
                            <input type="text" wire:model="sub_category" class="form-control" placeholder="e.g. Hardware-Oriented Engagements">
                            @error('sub_category')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                    </div>

                    {{-- Resource type --}}
                    <div class="form-group">
                        <label>Resource Type <span class="rl-req">*</span></label>
                        <div class="rl-type-grid">
                            @foreach(\App\Models\Paper::TYPES as $key => $label)
                                <label class="rl-type-card {{ $resource_type === $key ? 'active' : '' }}">
                                    <input type="radio" wire:model.live="resource_type" value="{{ $key }}">
                                    <span class="rl-type-icon">
                                        @if($key === 'book')
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                        @elseif($key === 'link')
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                        @else
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        @endif
                                    </span>
                                    <span class="rl-type-text">
                                        <strong>{{ Str::before($label, ' (') }}</strong>
                                        <small>{{ Str::between($label, '(', ')') }}</small>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('resource_type')<span class="error-msg">{{$message}}</span>@enderror
                    </div>

                    <div class="form-grid-2">
                        @if($resource_type === 'document')
                            <div class="form-group">
                                <label>PDF / Document Upload</label>
                                <input type="file" wire:model="file" class="form-control" accept=".pdf,.ppt,.pptx,.doc,.docx">
                                <div wire:loading wire:target="file" style="color:var(--copper); font-size:0.75rem; margin-top:.4rem;">Uploading...</div>
                                @error('file')<span class="error-msg">{{$message}}</span>@enderror
                                @if($existing_file && !$file)
                                    <div class="preview-box">
                                        <a href="{{ $existing_file }}" target="_blank" rel="noopener" style="color: var(--copper); font-weight:600; font-size: 0.85rem; text-decoration: none;">Current file: {{ $existing_file_name }}</a>
                                        <span class="rl-help" style="display:block;margin-top:.25rem;">Upload a new file to replace it.</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label>{{ $resource_type === 'book' ? 'Purchase URL (Amazon)' : 'Destination URL' }} <span class="rl-req">*</span></label>
                                <input type="url" wire:model="external_url" class="form-control" placeholder="https://www.amazon.com/dp/...">
                                @error('external_url')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Button Label <span class="rl-opt">(optional)</span></label>
                            <input type="text" wire:model="cta_label" class="form-control" maxlength="60"
                                   placeholder="{{ $resource_type === 'book' ? 'Buy on Amazon' : ($resource_type === 'link' ? 'Visit Website' : 'View PDF') }}">
                            <span class="rl-help">Leave blank to use the default shown as placeholder.</span>
                            @error('cta_label')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Card Summary <span class="rl-req">*</span> <span class="rl-opt">— the short description shown on the Resource Library cards</span></label>
                        <div wire:ignore>
                            <textarea x-data x-init="
                                let editorInstance = null;
                                $nextTick(() => {
                                    if (typeof tinymce !== 'undefined') {
                                        tinymce.init({
                                            target: $el,
                                            menubar: false,
                                            height: 220,
                                            plugins: 'lists link code',
                                            toolbar: 'undo redo | bold italic | bullist numlist | link | code',
                                            setup: function (editor) {
                                                editorInstance = editor;
                                                editor.on('blur change', function () {
                                                    $wire.set('description', editor.getContent(), false);
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

                    <div class="rl-settings-row">
                        <label class="rl-switch">
                            <input type="checkbox" wire:model="is_featured">
                            <span class="rl-switch-track"></span>
                            <span class="rl-switch-text"><strong>&#9733; Featured</strong><small>Shown in the default Featured view</small></span>
                        </label>
                        <label class="rl-switch">
                            <input type="checkbox" wire:model="is_active">
                            <span class="rl-switch-track"></span>
                            <span class="rl-switch-text"><strong>Active</strong><small>Visible on the public site</small></span>
                        </label>
                        <div class="form-group" style="margin:0; max-width: 140px;">
                            <label>Display Order</label>
                            <input type="number" wire:model="sort_order" class="form-control">
                            @error('sort_order')<span class="error-msg">{{$message}}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- ══════════════ TAB: RESOURCE PAGE ══════════════ --}}
                <div x-show="tab === 'page'" x-cloak>
                    <p class="rl-intro">This copy is the body of the resource's own web page. Write several self-contained paragraphs that summarise the document for visitors and for search / AI answer engines. Until it is filled in, the page shows the card summary.</p>

                    <div class="form-group">
                        <label>Page Description (Rich Text)</label>
                        <div wire:ignore>
                            <textarea x-data="{ inited: false }" x-effect="
                                if (tab === 'page' && !inited) {
                                    inited = true;
                                    $nextTick(() => {
                                        if (typeof tinymce === 'undefined') { console.error('TinyMCE failed to load.'); return; }
                                        tinymce.init({
                                            target: $el,
                                            menubar: false,
                                            height: 420,
                                            plugins: 'lists link table code',
                                            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3',
                                            toolbar: 'undo redo | blocks | bold italic | bullist numlist | link table | code',
                                            setup: function (editor) {
                                                editor.on('blur change', function () {
                                                    $wire.set('long_description', editor.getContent(), false);
                                                });
                                                editor.on('init', function () {
                                                    editor.setContent($wire.get('long_description') || '');
                                                });
                                            }
                                        });
                                    });
                                }
                            "></textarea>
                        </div>
                        @error('long_description')<span class="error-msg">{{$message}}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Cover Image <span class="rl-opt">(optional — book cover or a preview image; also used for social sharing)</span></label>
                        <input type="file" wire:model="featured_image" class="form-control" accept="image/*">
                        <div wire:loading wire:target="featured_image" style="color:var(--copper); font-size:0.75rem; margin-top:.4rem;">Uploading...</div>
                        @error('featured_image')<span class="error-msg">{{$message}}</span>@enderror

                        @if($featured_image)
                            <div class="preview-box"><img src="{{ $featured_image->temporaryUrl() }}" alt="" style="max-height:120px; display:block;"></div>
                        @elseif($existing_featured_image && !$remove_featured_image)
                            <div class="preview-box" style="display:flex; align-items:center; gap:1rem;">
                                <img src="{{ $existing_featured_image }}" alt="" style="max-height:120px; display:block;">
                                <button type="button" wire:click="$set('remove_featured_image', true)" style="background:none; border:none; color:#ef4444; font-weight:700; font-size:.8rem; cursor:pointer;">Remove image</button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ══════════════ TAB: SEO ══════════════ --}}
                <div x-show="tab === 'seo'" x-cloak>
                    <p class="rl-intro">Leave a field blank to use the automatic value shown as its placeholder.</p>

                    <div class="form-group">
                        <label>Meta Title <span class="rl-opt">(recommended ≤ 60 characters)</span></label>
                        <input type="text" wire:model.live.debounce.300ms="meta_title" class="form-control" maxlength="255" placeholder="{{ $title ? $title . ' | Kevin Thompson, Ph.D.' : 'Auto-generated from the title' }}">
                        <span class="rl-help rl-count">{{ mb_strlen((string) $meta_title) }} / 60</span>
                        @error('meta_title')<span class="error-msg">{{$message}}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Meta Description <span class="rl-opt">(max 160 characters — Google truncates beyond this)</span></label>
                        <textarea wire:model.live.debounce.300ms="meta_description" class="form-control" rows="3" maxlength="160" placeholder="{{ Str::limit(trim(strip_tags((string) $description)), 155, '') ?: 'Auto-generated from the card summary' }}"></textarea>
                        <span class="rl-help rl-count">{{ mb_strlen((string) $meta_description) }} / 160</span>
                        @error('meta_description')<span class="error-msg">{{$message}}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Meta Keywords <span class="rl-opt">(comma separated)</span></label>
                        <input type="text" wire:model="meta_keywords" class="form-control" maxlength="255" placeholder="agile hardware, scrum, case study">
                        @error('meta_keywords')<span class="error-msg">{{$message}}</span>@enderror
                    </div>

                    {{-- Search preview --}}
                    <div class="rl-serp">
                        <div class="rl-serp-label">Search result preview</div>
                        <div class="rl-serp-url">{{ url('/agile-hardware-papers-and-presentations') }}/{{ $slug ?: 'your-slug' }}</div>
                        <div class="rl-serp-title">{{ Str::limit($meta_title ?: (($title ?: 'Resource title') . ' | Kevin Thompson, Ph.D.'), 60) }}</div>
                        <div class="rl-serp-desc">{{ Str::limit($meta_description ?: trim(strip_tags((string) $description)), 160) ?: 'The meta description will appear here.' }}</div>
                    </div>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button type="button" wire:click="closeModal" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; padding: 0.9rem 1.5rem;">Cancel</button>
            <button type="button" wire:click="save" wire:loading.attr="disabled" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer;">
                <span wire:loading.remove wire:target="save">{{ $paperId ? 'Save Changes' : 'Create Resource' }}</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>

    </div>
</div>
