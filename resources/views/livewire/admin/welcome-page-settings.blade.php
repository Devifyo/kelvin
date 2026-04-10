<div class="settings-wrapper">
    <link href="{{ asset('css/admin/profile-settings.css') }}" rel="stylesheet">
    <style>
        .settings-canvas textarea.form-input { min-height: 100px; resize: vertical; }
    </style>

    <div class="settings-window">
        
        <aside class="settings-sidebar">
            <div class="settings-sidebar-title">Welcome Page</div>
            
            <button wire:click="setTab('hero')" class="nav-pill {{ $tab === 'hero' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                Hero Section
            </button>
            
            <button wire:click="setTab('pain')" class="nav-pill {{ $tab === 'pain' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                Pain Points Column
            </button>

            <button wire:click="setTab('bio')" class="nav-pill {{ $tab === 'bio' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Principal Bio & Book
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

                @if($tab === 'hero')
                <div wire:key="tab-hero">
                    <h2 class="section-title">Hero Section</h2>
                    <p class="section-desc">Manage the main headline, introductory paragraphs, and primary call-to-actions.</p>

                    <div class="form-group">
                        <label class="form-label">Kicker / Eyebrow</label>
                        <input type="text" wire:model="hero_kicker" class="form-input">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">H1 Headline (Italicized)</label>
                            <input type="text" wire:model="hero_h1_em" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">H1 Headline (Bold)</label>
                            <input type="text" wire:model="hero_h1_strong" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">First Paragraph</label>
                        <textarea wire:model="hero_p1" class="form-input"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Second Paragraph</label>
                        <textarea wire:model="hero_p2" class="form-input"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Primary CTA Text</label>
                            <input type="text" wire:model="hero_cta_primary_text" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Primary CTA Link</label>
                            <input type="text" wire:model="hero_cta_primary_link" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Secondary CTA Text</label>
                            <input type="text" wire:model="hero_cta_secondary_text" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Secondary CTA Link</label>
                            <input type="text" wire:model="hero_cta_secondary_link" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
                @endif

                @if($tab === 'pain')
                <div wire:key="tab-pain">
                    <h2 class="section-title">Pain Points Column</h2>
                    <p class="section-desc">Edit the list of organizational pain points that appears alongside the main hero text.</p>

                    <div class="form-group">
                        <label class="form-label">Section Title</label>
                        <input type="text" wire:model="pain_title" class="form-input" style="max-width: 100%;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pain Points List (One per line)</label>
                        <textarea wire:model="pain_list_string" class="form-input" style="max-width: 100%; min-height: 180px;"></textarea>
                        <span style="font-size: 0.8rem; color: var(--muted); display: block; margin-top: 0.5rem;">Each line is automatically rendered sequentially as an item on the page.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Footer Phrase</label>
                        <input type="text" wire:model="pain_footer" class="form-input" style="max-width: 100%;">
                    </div>
                </div>
                @endif

                @if($tab === 'bio')
                <div wire:key="tab-bio">
                    <h2 class="section-title">Principal & Book</h2>
                    <p class="section-desc">Manage the bio section introducing the Principal consultant and their associated book.</p>

                    <div class="form-group">
                        <label class="form-label">Kicker Title</label>
                        <input type="text" wire:model="principal_kicker" class="form-input" style="max-width: 100%;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">H2 Name (Regular Text)</label>
                            <input type="text" wire:model="principal_h2_name" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">H2 Name (Italicized Text)</label>
                            <input type="text" wire:model="principal_h2_em" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio Paragraph 1</label>
                        <textarea wire:model="principal_p1" class="form-input" style="max-width: 100%;"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio Paragraph 2</label>
                        <textarea wire:model="principal_p2" class="form-input" style="max-width: 100%;"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio Paragraph 3 (HTML Allowed)</label>
                        <textarea wire:model="principal_p3" class="form-input" style="max-width: 100%;"></textarea>
                    </div>

                    <h3 style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--copper); margin: 2rem 0 1rem; border-bottom: 1px solid var(--ivory3); padding-bottom: 0.5rem;">Associated Book Link</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.75rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Book Image URL</label>
                            <input type="text" wire:model="principal_book_image" class="form-input" style="max-width: 100%;">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Book Amazon URL</label>
                            <input type="text" wire:model="principal_book_url" class="form-input" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Book Title</label>
                        <input type="text" wire:model="principal_book_title" class="form-input" style="max-width: 100%;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Book Description / Tagline</label>
                        <textarea wire:model="principal_book_desc" class="form-input" style="max-width: 100%; min-height: 80px;"></textarea>
                    </div>
                </div>
                @endif

                @if($tab === 'seo')
                <div wire:key="tab-seo">
                    <h2 class="section-title">SEO Settings</h2>
                    <p class="section-desc">Manage how the homepage appears in browser tabs and search engine results.</p>

                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" wire:model="seo_title" class="form-input" style="max-width: 100%;" placeholder="e.g. Kevin Thompson Ph.D. Consulting | Agile Hardware & Software">
                        <span style="font-size: 0.8rem; color: var(--muted); display: block; margin-top: 0.5rem;">The title shown in the browser tab and search results. If left blank, it will fall back to a default value.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea wire:model="seo_description" class="form-input" style="max-width: 100%;" placeholder="Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development."></textarea>
                        <span style="font-size: 0.8rem; color: var(--muted); display: block; margin-top: 0.5rem;">A brief summary of the page for search engine results. Keep it between 150-160 characters.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" wire:model="seo_keywords" class="form-input" style="max-width: 100%;" placeholder="Agile Hardware, Scrum, Embedded Systems...">
                        <span style="font-size: 0.8rem; color: var(--muted); display: block; margin-top: 0.5rem;">Comma-separated keywords to describe the page content.</span>
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
