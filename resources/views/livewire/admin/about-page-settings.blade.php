<div class="settings-wrapper">
    <link href="{{ asset('css/admin/profile-settings.css') }}" rel="stylesheet">
    <style>
        .settings-canvas textarea.form-input { min-height: 100px; resize: vertical; }
    </style>

    <div class="settings-window">
        
        <aside class="settings-sidebar">
            <div class="settings-sidebar-title">About Page</div>
            
            <button wire:click="setTab('header')" class="nav-pill {{ $tab === 'header' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                Page Header
            </button>
            
            <button wire:click="setTab('sidebar')" class="nav-pill {{ $tab === 'sidebar' ? 'active' : '' }}">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                Sidebar Details
            </button>

            <button wire:click="setTab('content')" class="nav-pill {{ $tab === 'content' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Body Content
            </button>

            <button wire:click="setTab('seo')" class="nav-pill {{ $tab === 'seo' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                SEO Settings
            </button>
        </aside>

        <div class="settings-canvas">
            
            @if (session()->has('success'))
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #047857; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 600; font-size: 0.9rem;">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit="save">

                @if($tab === 'header')
                <div wire:key="tab-header">
                    <h2 class="section-title">Page Header</h2>
                    <p class="section-desc">Manage the massive headline title text.</p>

                    <div class="form-group">
                        <label class="form-label">Kicker / Eyebrow Text</label>
                        <input type="text" wire:model="header_kicker" class="form-input">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">H1 Headline (Regular)</label>
                            <input type="text" wire:model="header_h1_regular" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">H1 Headline (Italicized)</label>
                            <input type="text" wire:model="header_h1_em" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
                @endif

                @if($tab === 'sidebar')
                <div wire:key="tab-sidebar">
                    <h2 class="section-title">Sidebar Details</h2>
                    <p class="section-desc">Edit the profile picture and education/certification list.</p>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label class="form-label">Profile Image Option (Upload New File)</label>
                        <input type="file" wire:model="new_profile_image" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-input" style="max-width: 100%; border: 1px dashed var(--slate); padding: 0.5rem; background: var(--white);">
                        <div wire:loading wire:target="new_profile_image" style="font-size:0.75rem; color:var(--copper); margin-top:4px;">Uploading Preview...</div>
                        @error('new_profile_image') <span class="error-msg" style="color: red; font-size: 0.8rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
                        
                        @if ($new_profile_image && empty($errors->get('new_profile_image')))
                            @php
                                $tempUrl = null;
                                try {
                                    $tempUrl = $new_profile_image->temporaryUrl();
                                } catch (\Exception $e) {}
                            @endphp
                            @if($tempUrl)
                                <div style="margin-top: 0.75rem; padding: 0.75rem; border: 1px solid var(--ivory3); border-radius: 8px; display: inline-block; background: var(--ivory2);">
                                    <span style="display: block; font-size: 0.65rem; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; font-weight: 700;">New Image Preview</span>
                                    <img src="{{ $tempUrl }}" style="max-height: 120px; border-radius: 6px; object-fit: cover;">
                                </div>
                            @endif
                        @elseif ($profile_image)
                            <div style="margin-top: 0.75rem; padding: 0.75rem; border: 1px solid var(--ivory3); border-radius: 8px; display: inline-block; background: var(--ivory2);">
                                <span style="display: block; font-size: 0.65rem; color: var(--muted); margin-bottom: 0.4rem; text-transform: uppercase; font-weight: 700;">Current Profile Image</span>
                                <img src="{{ $profile_image }}" style="max-height: 120px; border-radius: 6px; object-fit: cover;">
                            </div>
                        @else 
                             <span style="font-size: 0.8rem; color: var(--muted); display: block; margin-top: 0.5rem;">Upload an image or manually type a URL below.</span>
                        @endif
                    </div>
                    
                    <div class="form-group" style="margin-top: -1.25rem;">
                        <label class="form-label">Profile Image Option 2 (Manually Set URL)</label>
                        <input type="text" wire:model="profile_image" class="form-input" style="max-width: 100%;" placeholder="e.g. /img/frontend/Dr. Kevin Thompson.webp">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Sidebar Section Title</label>
                        <input type="text" wire:model="sidebar_kicker" class="form-input" style="max-width: 100%;">
                    </div>
                    
                    <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--copper); margin: 2rem 0 1rem; border-bottom: 1px solid var(--ivory3); padding-bottom: 0.5rem;">Education & Certifications</h3>
                    <p style="font-size: 0.8rem; color: var(--muted); margin-bottom: 1rem;">Manage the credentials that appear underneath the profile picture.</p>
                    
                    @foreach($education_list as $index => $item)
                        <div style="background: var(--ivory2); border: 1px solid var(--ivory3); border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; position: relative;">
                            <button type="button" wire:click="removeEducationItem({{ $index }})" style="position: absolute; right: 0.75rem; top: 0.75rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: none; border-radius: 4px; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 700; cursor: pointer;">Remove</button>
                            
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label class="form-label">Credential Title (Bold text)</label>
                                <input type="text" wire:model="education_list.{{ $index }}.title" class="form-input" style="max-width: 100%;" placeholder="e.g. Ph.D. & B.S.">
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Description / Details</label>
                                <textarea wire:model="education_list.{{ $index }}.details" class="form-input" style="max-width: 100%; min-height: 80px;" placeholder="e.g. Physics from Princeton University"></textarea>
                            </div>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addEducationItem" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--copper); color: white; border: none; border-radius: 6px; padding: 0.6rem 1rem; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add New Credential
                    </button>
                    
                </div>
                @endif

                @if($tab === 'content')
                <div wire:key="tab-content">
                    <h2 class="section-title">Body Content</h2>
                    <p class="section-desc">Manage the narrative text blocks. The specific aesthetic layout is preserved seamlessly.</p>

                    <div class="form-group">
                        <label class="form-label">Introduction Paragraph</label>
                        <textarea wire:model="intro_text" class="form-input" style="max-width: 100%;"></textarea>
                    </div>

                    <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--copper); margin: 2rem 0 1rem; border-bottom: 1px solid var(--ivory3); padding-bottom: 0.5rem;">Section 1: The Transition</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Section H2 Title (Regular)</label>
                            <input type="text" wire:model="section_1_h2_regular" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Section H2 Title (Italicized)</label>
                            <input type="text" wire:model="section_1_h2_em" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Paragraph 1</label>
                        <textarea wire:model="section_1_p1" class="form-input" style="max-width: 100%;"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Paragraph 2</label>
                        <textarea wire:model="section_1_p2" class="form-input" style="max-width: 100%;"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Highlight Quote Box</label>
                        <textarea wire:model="highlight_quote" class="form-input" style="max-width: 100%;"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Paragraph 3</label>
                        <textarea wire:model="section_1_p3" class="form-input" style="max-width: 100%;"></textarea>
                    </div>

                    <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--copper); margin: 2rem 0 1rem; border-bottom: 1px solid var(--ivory3); padding-bottom: 0.5rem;">Section 2: Expanding Horizons</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Section H2 Title (Regular)</label>
                            <input type="text" wire:model="section_2_h2_regular" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Section H2 Title (Italicized)</label>
                            <input type="text" wire:model="section_2_h2_em" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Paragraph 1</label>
                        <textarea wire:model="section_2_p1" class="form-input" style="max-width: 100%;"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Paragraph 2</label>
                        <textarea wire:model="section_2_p2" class="form-input" style="max-width: 100%;"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Paragraph 3</label>
                        <textarea wire:model="section_2_p3" class="form-input" style="max-width: 100%;"></textarea>
                    </div>

                </div>
                @endif

                @if($tab === 'seo')
                <div wire:key="tab-seo">
                    <h2 class="section-title">SEO Settings</h2>
                    <p class="section-desc">Manage how the About page appears in browser tabs and search engine results.</p>

                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" wire:model="seo_title" class="form-input" style="max-width: 100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea wire:model="seo_description" class="form-input" style="max-width: 100%;"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" wire:model="seo_keywords" class="form-input" style="max-width: 100%;">
                    </div>
                </div>
                @endif

                <div style="margin-top: 2rem; border-top: 1px solid var(--ivory3); padding-top: 2rem;">
                    <button type="submit" class="btn-save" wire:loading.attr="disabled" wire:target="save">
                        <svg wire:loading.remove wire:target="save" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span wire:loading.remove wire:target="save">Save Changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
