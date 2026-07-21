<div>
    {{-- Tab Bar --}}
    <div class="as-tabs">
        <button wire:click="$set('tab','general')"  class="as-tab {{ $tab === 'general'  ? 'as-tab-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
            General
        </button>
        <button wire:click="$set('tab','colors')"   class="as-tab {{ $tab === 'colors'   ? 'as-tab-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="13.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="10.5" r="2.5"/><circle cx="8.5" cy="7.5" r="2.5"/><circle cx="6.5" cy="12.5" r="2.5"/><path d="M12 22a7 7 0 0 0 7-7c0-5-7-13-7-13S5 10 5 15a7 7 0 0 0 7 7z"/></svg>
            Theme Colors
        </button>
        <button wire:click="$set('tab','icons')"    class="as-tab {{ $tab === 'icons'    ? 'as-tab-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M3 9h18M9 21V9"/></svg>
            App Icons
        </button>
        <button wire:click="$set('tab','seo')"      class="as-tab {{ $tab === 'seo'      ? 'as-tab-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            SEO
        </button>
    </div>

    {{-- ── GENERAL ── --}}
    @if($tab === 'general')
    <div class="as-card">
        <div class="as-card-head">
            <h2 class="as-card-title">General Settings</h2>
            <p class="as-card-sub">Update the application name shown in the admin panel and browser title.</p>
        </div>
        <div class="as-body">
            <div class="as-field">
                <label class="as-label">Application Name</label>
                <input type="text" wire:model="appName" class="as-input" placeholder="e.g. Kevin Thompson" maxlength="80" />
                <span class="as-hint">Shown in the sidebar header and browser tab.</span>
            </div>

            <div class="as-actions">
                <button wire:click="saveGeneral" wire:loading.attr="disabled" class="as-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span wire:loading.remove wire:target="saveGeneral">Save Name</span>
                    <span wire:loading wire:target="saveGeneral">Saving…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ── COLORS ── --}}
    @if($tab === 'colors')
    <div class="as-card">
        <div class="as-card-head">
            <h2 class="as-card-title">Theme Colors</h2>
            <p class="as-card-sub">Choose the two primary colors. All shades are derived automatically.</p>
        </div>
        <div class="as-body">
            <div class="as-color-grid">

                {{-- Accent / Copper --}}
                <div class="as-color-row">
                    <div class="as-color-info">
                        <label class="as-label">Accent Color</label>
                        <span class="as-hint">Buttons, active links, highlights — the primary brand color.</span>
                    </div>
                    <div class="as-color-picker-wrap">
                        <input type="color" wire:model.live="colorCopper" class="as-color-input" />
                        <input type="text"  wire:model.live="colorCopper" class="as-hex-input" maxlength="7" placeholder="#b5722a" />
                    </div>
                    <div class="as-swatches">
                        <span class="as-swatch" style="background:{{ $colorCopper }}" title="Base"></span>
                        <span class="as-swatch" style="background:{{ $previewColors['copper2'] }}" title="+15% light"></span>
                        <span class="as-swatch" style="background:{{ $previewColors['copper3'] }}" title="+35% light"></span>
                    </div>
                </div>

                {{-- Dark / Slate --}}
                <div class="as-color-row">
                    <div class="as-color-info">
                        <label class="as-label">Dark / Background Color</label>
                        <span class="as-hint">Sidebar background and dark surface areas.</span>
                    </div>
                    <div class="as-color-picker-wrap">
                        <input type="color" wire:model.live="colorSlate" class="as-color-input" />
                        <input type="text"  wire:model.live="colorSlate" class="as-hex-input" maxlength="7" placeholder="#1a2332" />
                    </div>
                    <div class="as-swatches">
                        <span class="as-swatch" style="background:{{ $colorSlate }}" title="Base"></span>
                        <span class="as-swatch" style="background:{{ $previewColors['slateHi'] }}" title="+8% light"></span>
                    </div>
                </div>
            </div>

            {{-- Live Preview Strip --}}
            <div class="as-preview-strip" style="background:{{ $colorSlate }}; background-image: linear-gradient(90deg, {{ $colorSlate }} 0%, #111827 100%);">
                <div class="as-preview-logo" style="border-color:{{ $previewColors['copper2'] }}; color:{{ $previewColors['copper2'] }}">KT</div>
                <div style="flex:1">
                    <div style="font-size:0.95rem; font-weight:700; color:#fff; margin-bottom:2px;">{{ $appName }}</div>
                    <div style="font-size:0.65rem; text-transform:uppercase; letter-spacing:0.1em; color:{{ $previewColors['copper3'] }}">Admin Portal</div>
                </div>
                <div class="as-preview-badge" style="background:{{ $colorCopper }}">Active</div>
            </div>

            <div class="as-actions">
                <button wire:click="saveColors" wire:loading.attr="disabled" class="as-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span wire:loading.remove wire:target="saveColors">Save Colors</span>
                    <span wire:loading wire:target="saveColors">Saving…</span>
                </button>

                <button wire:loading.attr="disabled"
                        @click="Swal.fire({ title: 'Reset to Default?', text: 'This will restore the original copper and dark colors.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, reset', cancelButtonText: 'Cancel', confirmButtonColor: '#b5722a', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.resetColors())"
                        class="as-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                    <span wire:loading.remove wire:target="resetColors">Reset to Default</span>
                    <span wire:loading wire:target="resetColors">Resetting…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ── ICONS ── --}}
    @if($tab === 'icons')
    <div class="as-card">
        <div class="as-card-head">
            <h2 class="as-card-title">App Icons</h2>
            <p class="as-card-sub">Upload a square PNG for the sidebar logo mark and the browser favicon. Recommended size: 512×512px.</p>
        </div>
        <div class="as-body">
            <div class="as-icon-grid">

                {{-- App Icon --}}
                <div class="as-icon-block">
                    <p class="as-label">App Icon</p>
                    <p class="as-hint">Shown in the admin sidebar. Replaces the text initials.</p>
                    <div class="as-icon-preview">
                        @if($newAppIcon)
                            <img src="{{ $newAppIcon->temporaryUrl() }}" class="as-icon-img" alt="New icon preview" />
                        @elseif($currentIconUrl)
                            <img src="{{ $currentIconUrl }}" class="as-icon-img" alt="Current app icon" />
                        @else
                            <div class="as-icon-placeholder">KT</div>
                        @endif
                    </div>
                    <label class="as-upload-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                        Choose File
                        <input type="file" wire:model="newAppIcon" accept="image/png,image/jpeg,image/webp" class="as-hidden-input" />
                    </label>
                    @error('newAppIcon') <span class="as-error">{{ $message }}</span> @enderror
                    @if($currentIconUrl)
                        <button type="button" class="as-remove-btn"
                                @click="Swal.fire({ title: 'Remove the app icon?', text: 'The sidebar will fall back to your initials.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, remove', cancelButtonText: 'Cancel', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.removeAppIcon())">Remove current icon</button>
                    @endif
                </div>

                {{-- Favicon --}}
                <div class="as-icon-block">
                    <p class="as-label">Favicon</p>
                    <p class="as-hint">Shown in browser tabs. Upload a square PNG (min 32×32px).</p>
                    <div class="as-icon-preview as-favicon-preview">
                        @if($newFavicon)
                            <img src="{{ $newFavicon->temporaryUrl() }}" class="as-favicon-img" alt="New favicon preview" />
                        @elseif($currentFaviconUrl)
                            <img src="{{ $currentFaviconUrl }}" class="as-favicon-img" alt="Current favicon" />
                        @else
                            <div class="as-icon-placeholder" style="font-size:0.7rem">ICO</div>
                        @endif
                    </div>
                    <label class="as-upload-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                        Choose File
                        <input type="file" wire:model="newFavicon" accept="image/png,image/jpeg" class="as-hidden-input" />
                    </label>
                    @error('newFavicon') <span class="as-error">{{ $message }}</span> @enderror
                    @if($currentFaviconUrl)
                        <button type="button" class="as-remove-btn"
                                @click="Swal.fire({ title: 'Remove the favicon?', text: 'Browsers will fall back to the default site icon.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, remove', cancelButtonText: 'Cancel', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.removeFavicon())">Remove current favicon</button>
                    @endif
                </div>
            </div>

            <div class="as-actions">
                <button wire:click="saveIcons" wire:loading.attr="disabled" class="as-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span wire:loading.remove wire:target="saveIcons">Save Icons</span>
                    <span wire:loading wire:target="saveIcons">Uploading…</span>
                </button>

                <button wire:loading.attr="disabled"
                        @click="Swal.fire({ title: 'Reset to Default?', text: 'Both uploaded icons will be removed and initials will be restored.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, reset', cancelButtonText: 'Cancel', confirmButtonColor: '#b5722a', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.resetIcons())"
                        class="as-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                    <span wire:loading.remove wire:target="resetIcons">Reset to Default</span>
                    <span wire:loading wire:target="resetIcons">Resetting…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ── SEO ── --}}
    @if($tab === 'seo')
    <div class="as-card">
        <div class="as-card-head">
            <h2 class="as-card-title">SEO &amp; Crawlers</h2>
            <p class="as-card-sub">
                All three files are auto-generated from these settings — no manual file editing needed.
                <a href="/robots.txt" target="_blank" class="as-link">robots.txt</a> ·
                <a href="/sitemap.xml" target="_blank" class="as-link">sitemap.xml</a> ·
                <a href="/llms.txt"   target="_blank" class="as-link">llms.txt</a>
            </p>
        </div>
        <div class="as-body" style="display:flex;flex-direction:column;gap:2rem;">

            {{-- ── 1. Global Meta Defaults ── --}}
            <div>
                <h3 class="as-section-title">Global Meta Defaults</h3>
                <p class="as-hint" style="margin-bottom:.75rem">Fallback values used when a page has no specific meta set.</p>
                <div class="as-field" style="margin-bottom:.85rem">
                    <label class="as-label">Title suffix <span class="as-hint">appended to every page title — e.g. <code>| Kevin Thompson Ph.D.</code></span></label>
                    <input type="text" wire:model="seoTitleSuffix" class="as-input" placeholder="| Kevin Thompson Ph.D." maxlength="100" />
                    @error('seoTitleSuffix') <span class="as-error">{{ $message }}</span> @enderror
                </div>
                <div class="as-field" style="margin-bottom:.85rem">
                    <label class="as-label">Default meta description <span class="as-hint">max 160 chars for search snippets</span></label>
                    <textarea wire:model="seoDefaultDesc" rows="3" class="as-input" maxlength="320" placeholder="Expert consulting, training…"></textarea>
                    @error('seoDefaultDesc') <span class="as-error">{{ $message }}</span> @enderror
                </div>
                <div class="as-field">
                    <label class="as-label">Default Open Graph / social share image</label>
                    <span class="as-hint" style="display:block;margin:.25rem 0 .75rem">
                        Shown on Facebook, LinkedIn, X/Twitter, Slack, and other platforms when this site is shared.
                        <strong>Recommended size: 1200 × 630 px</strong> (1.91:1 ratio, under 5 MB, JPG/PNG/WebP).
                        Used as the fallback whenever a page has no featured image of its own.
                    </span>

                    {{-- Preview --}}
                    <div style="display:flex;gap:1.25rem;align-items:flex-start;flex-wrap:wrap;margin-bottom:.85rem">
                        <div style="width:240px;aspect-ratio:1200/630;border:1px solid var(--ivory3,#e6e1d8);border-radius:6px;background:#f7f5f0;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                            @if($newOgImage)
                                <img src="{{ $newOgImage->temporaryUrl() }}" alt="New OG image preview" style="width:100%;height:100%;object-fit:cover" />
                            @elseif($currentOgImageUrl)
                                <img src="{{ $currentOgImageUrl }}" alt="Current OG image" style="width:100%;height:100%;object-fit:cover" />
                            @else
                                <span class="as-hint" style="text-align:center;padding:1rem;font-size:.78rem">
                                    Falling back to<br><strong>Dr. Kevin Thompson</strong><br>headshot
                                </span>
                            @endif
                        </div>

                        <div style="flex:1;min-width:240px;display:flex;flex-direction:column;gap:.5rem">
                            <label class="as-upload-btn" style="align-self:flex-start">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
                                {{ $currentOgImageUrl || $newOgImage ? 'Replace Image' : 'Choose Image' }}
                                <input type="file" wire:model="newOgImage" accept="image/png,image/jpeg,image/webp" class="as-hidden-input" />
                            </label>

                            @error('newOgImage') <span class="as-error">{{ $message }}</span> @enderror

                            @if($newOgImage)
                                <span class="as-hint">Selected: <code>{{ $newOgImage->getClientOriginalName() }}</code> · {{ number_format($newOgImage->getSize() / 1024, 0) }} KB</span>
                                <button wire:click="uploadOgImage" wire:loading.attr="disabled" wire:target="uploadOgImage,newOgImage" class="as-btn-primary" style="align-self:flex-start;margin-top:.25rem">
                                    <span wire:loading.remove wire:target="uploadOgImage">Upload</span>
                                    <span wire:loading wire:target="uploadOgImage">Uploading…</span>
                                </button>
                            @elseif($currentOgImageUrl)
                                <span class="as-hint">
                                    Currently using your uploaded image.
                                    @if($currentOgImageDimensions)
                                        Dimensions: <strong>{{ $currentOgImageDimensions }}</strong>
                                        @php
                                            preg_match('/(\d+)\s*×\s*(\d+)/', $currentOgImageDimensions, $m);
                                            $w = (int)($m[1] ?? 0); $h = (int)($m[2] ?? 0);
                                            $isOptimal = $w === 1200 && $h === 630;
                                            $aspectOk  = $w > 0 && $h > 0 && abs(($w / $h) - (1200 / 630)) < 0.05;
                                        @endphp
                                        @if($isOptimal)
                                            <span style="color:#16a34a">✓ Optimal</span>
                                        @elseif($aspectOk)
                                            <span style="color:#ca8a04">⚠ Right ratio, non-standard size</span>
                                        @else
                                            <span style="color:#dc2626">⚠ Recommend 1200 × 630 px for best display</span>
                                        @endif
                                    @endif
                                </span>
                                <button type="button" class="as-remove-btn" style="align-self:flex-start"
                                        @click="Swal.fire({ title: 'Remove the OG image?', text: 'Social sharing previews will fall back to the default headshot.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, remove', cancelButtonText: 'Cancel', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.removeOgImage())">Remove image</button>
                            @else
                                <span class="as-hint">No image uploaded — sharing previews will fall back to the default headshot. Upload a 1200 × 630 brand card for best results.</span>
                            @endif
                        </div>
                    </div>

                    {{-- Optional: external URL fallback for power users / CDN-hosted images --}}
                    <details style="margin-top:.75rem">
                        <summary class="as-hint" style="cursor:pointer;user-select:none">
                            Advanced: use an external URL instead of an upload
                        </summary>
                        <div style="margin-top:.5rem">
                            <input type="text" wire:model="seoOgImage" class="as-input" placeholder="https://cdn.example.com/og-image.jpg" />
                            <span class="as-hint">If set to a full URL (starting with <code>http://</code> or <code>https://</code>), it overrides the uploaded image. Saved with "Save All SEO Settings" below.</span>
                            @error('seoOgImage') <span class="as-error">{{ $message }}</span> @enderror
                        </div>
                    </details>
                </div>
            </div>

            {{-- ── 2. Social & Open Graph ── --}}
            <div>
                <h3 class="as-section-title">Social &amp; Open Graph</h3>
                <p class="as-hint" style="margin-bottom:.75rem">Used in Twitter/X cards and Schema.org <code>sameAs</code> links.</p>
                <div class="as-color-grid" style="gap:.75rem">
                    <div class="as-field">
                        <label class="as-label">Twitter / X handle</label>
                        <input type="text" wire:model="seoTwitterHandle" class="as-input" placeholder="@kevinthompsonphd" maxlength="100" />
                        @error('seoTwitterHandle') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="as-field">
                        <label class="as-label">LinkedIn profile URL</label>
                        <input type="text" wire:model="seoLinkedinUrl" class="as-input" placeholder="https://linkedin.com/in/…" maxlength="500" />
                        @error('seoLinkedinUrl') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ── 3. Structured Data (JSON-LD) ── --}}
            <div>
                <h3 class="as-section-title">Structured Data — Schema.org</h3>
                <p class="as-hint" style="margin-bottom:.75rem">Auto-injected on every page as a JSON-LD <code>&lt;script&gt;</code>. Tells Google who you are and what you do.</p>
                <div class="as-color-grid" style="gap:.75rem">
                    <div class="as-field">
                        <label class="as-label">Person job title</label>
                        <input type="text" wire:model="seoSchemaJobTitle" class="as-input" placeholder="Agile Consultant &amp; Trainer, Ph.D." maxlength="200" />
                        @error('seoSchemaJobTitle') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="as-field">
                        <label class="as-label">Organization name</label>
                        <input type="text" wire:model="seoSchemaOrgName" class="as-input" placeholder="Kevin Thompson Ph.D. Consulting" maxlength="200" />
                        @error('seoSchemaOrgName') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ── 4. Analytics & Tracking ── --}}
            <div>
                <h3 class="as-section-title">Analytics &amp; Tracking</h3>
                <p class="as-hint" style="margin-bottom:.75rem">If both are set, only GTM is loaded (it handles GA4 internally).</p>
                <div class="as-color-grid" style="gap:.75rem">
                    <div class="as-field">
                        <label class="as-label">Google Analytics 4 — Measurement ID</label>
                        <input type="text" wire:model="seoGa4Id" class="as-input" placeholder="G-XXXXXXXXXX" maxlength="50" style="font-family:monospace" />
                        @error('seoGa4Id') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="as-field">
                        <label class="as-label">Google Tag Manager — Container ID</label>
                        <input type="text" wire:model="seoGtmId" class="as-input" placeholder="GTM-XXXXXXX" maxlength="50" style="font-family:monospace" />
                        @error('seoGtmId') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ── 5. Search Engine Verification ── --}}
            <div>
                <h3 class="as-section-title">Search Engine Verification</h3>
                <p class="as-hint" style="margin-bottom:.75rem">Paste only the <strong>content value</strong> from the verification meta tag — not the full tag.</p>
                <div class="as-color-grid" style="gap:.75rem">
                    <div class="as-field">
                        <label class="as-label">Google Search Console</label>
                        <input type="text" wire:model="seoGoogleVerify" class="as-input" placeholder="AbCdEfGhIjKlMnOpQrStUvWxYz" style="font-family:monospace;font-size:0.8rem" />
                        @error('seoGoogleVerify') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="as-field">
                        <label class="as-label">Bing Webmaster Tools</label>
                        <input type="text" wire:model="seoBingVerify" class="as-input" placeholder="0123456789ABCDEF…" style="font-family:monospace;font-size:0.8rem" />
                        @error('seoBingVerify') <span class="as-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- ── robots.txt ── --}}
            <div>
                <h3 class="as-section-title">robots.txt</h3>
                <p class="as-hint" style="margin-bottom:.75rem">
                    <code>/admin</code>, <code>/login</code>, <code>/livewire</code>, and <code>/_ignition</code> are always blocked.
                    Add extra <code>Disallow:</code> or <code>Allow:</code> lines below (one per line).
                </p>
                <div class="as-field">
                    <label class="as-label">Extra directives</label>
                    <textarea wire:model="robotsDisallowExtra" rows="4" class="as-input" style="font-family:monospace;font-size:0.8rem"
                        placeholder="Disallow: /private&#10;Allow: /admin/public-assets"></textarea>
                    @error('robotsDisallowExtra') <span class="as-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- ── sitemap.xml ── --}}
            <div>
                <h3 class="as-section-title">sitemap.xml — Static Pages</h3>
                <p class="as-hint" style="margin-bottom:.75rem">
                    Add, remove, or edit the static pages included in your sitemap. Toggle the checkbox to exclude a page without deleting it.
                </p>

                {{-- Column headers --}}
                <div class="as-sp-header">
                    <span style="flex:1 1 auto">URL Path</span>
                    <span class="as-sp-col-sm">Change Freq</span>
                    <span class="as-sp-col-xs">Priority</span>
                    <span class="as-sp-col-icon">On</span>
                    <span class="as-sp-col-icon"></span>
                </div>

                @foreach($sitemapStaticPages as $i => $page)
                <div class="as-sp-row">
                    <input type="text"
                           wire:model="sitemapStaticPages.{{ $i }}.url"
                           class="as-sp-input"
                           placeholder="/your-page-slug" />

                    <select wire:model="sitemapStaticPages.{{ $i }}.changefreq" class="as-sp-select">
                        @foreach(['always','hourly','daily','weekly','monthly','yearly','never'] as $cf)
                        <option value="{{ $cf }}">{{ $cf }}</option>
                        @endforeach
                    </select>

                    <select wire:model="sitemapStaticPages.{{ $i }}.priority" class="as-sp-select as-sp-select-xs">
                        @foreach(['1.0','0.9','0.8','0.7','0.6','0.5','0.4','0.3','0.2','0.1'] as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>

                    <input type="checkbox"
                           wire:model="sitemapStaticPages.{{ $i }}.enabled"
                           class="as-toggle as-sp-check" />

                    <button wire:click="removeStaticPage({{ $i }})" class="as-sp-remove" title="Remove">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                @endforeach

                <button wire:click="addStaticPage" class="as-sp-add">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add page
                </button>

                <div style="margin-top:1.25rem">
                    <h3 class="as-section-title" style="margin-bottom:.5rem">Dynamic content</h3>
                    <div style="display:flex;flex-direction:column;gap:.6rem">
                        <label class="as-toggle-row">
                            <input type="checkbox" wire:model="sitemapBlog" class="as-toggle" />
                            <span class="as-toggle-label">Include blog posts</span>
                            <span class="as-sp-count">{{ $blogPosts->count() }} posts</span>
                        </label>
                        <label class="as-toggle-row">
                            <input type="checkbox" wire:model="sitemapTraining" class="as-toggle" />
                            <span class="as-toggle-label">Include training classes</span>
                            <span class="as-sp-count">{{ $trainingClasses->count() }} classes</span>
                        </label>
                    </div>
                </div>

                {{-- Sitemap preview --}}
                <details class="as-sp-preview">
                    <summary class="as-sp-preview-summary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        Sitemap preview — <strong>{{ $totalSitemap }} total URLs</strong>
                        <span class="as-sp-count">{{ $staticCount }} static · {{ $blogPosts->count() }} blog · {{ $trainingClasses->count() }} training</span>
                    </summary>
                    <div class="as-sp-preview-body">
                        @foreach(array_filter($sitemapStaticPages, fn($p) => $p['enabled'] ?? true) as $p)
                        <div class="as-sp-preview-row as-sp-preview-static">
                            <span class="as-sp-preview-badge">static</span>
                            <code>{{ $p['url'] }}</code>
                        </div>
                        @endforeach

                        @foreach($blogPosts as $post)
                        <div class="as-sp-preview-row as-sp-preview-blog">
                            <span class="as-sp-preview-badge">blog</span>
                            <code>/agile-insights-blog/{{ $post->slug }}</code>
                        </div>
                        @endforeach

                        @foreach($trainingClasses as $class)
                        <div class="as-sp-preview-row as-sp-preview-training">
                            <span class="as-sp-preview-badge">training</span>
                            <code>/agile-training-classes/{{ $class->slug }}</code>
                        </div>
                        @endforeach
                    </div>
                </details>
            </div>

            {{-- ── llms.txt ── --}}
            <div>
                <h3 class="as-section-title">llms.txt</h3>
                <p class="as-hint" style="margin-bottom:.75rem">
                    Helps AI crawlers understand your site. Blog posts and training classes are appended automatically.
                </p>
                <div class="as-field" style="margin-bottom:1rem">
                    <label class="as-label">Site description</label>
                    <textarea wire:model="llmsDescription" rows="3" class="as-input"
                        placeholder="Kevin Thompson Ph.D. provides agile consulting and training for hardware development teams…"></textarea>
                    @error('llmsDescription') <span class="as-error">{{ $message }}</span> @enderror
                </div>
                <div class="as-field">
                    <label class="as-label">Extra content <span class="as-hint">(optional — appended at the end)</span></label>
                    <textarea wire:model="llmsExtra" rows="4" class="as-input"
                        placeholder="Any additional context, disclaimers, or notes for LLMs."></textarea>
                    @error('llmsExtra') <span class="as-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="as-actions">
                <button wire:click="saveSeo" wire:loading.attr="disabled" class="as-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span wire:loading.remove wire:target="saveSeo">Save SEO Settings</span>
                    <span wire:loading wire:target="saveSeo">Saving…</span>
                </button>

                <button wire:loading.attr="disabled"
                        @click="Swal.fire({ title: 'Reset static pages?', text: 'This restores the original default pages list.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, reset', cancelButtonText: 'Cancel', confirmButtonColor: '#b5722a', cancelButtonColor: '#6b7280' }).then(r => r.isConfirmed && $wire.resetStaticPages())"
                        class="as-btn-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                    <span wire:loading.remove wire:target="resetStaticPages">Reset Pages</span>
                    <span wire:loading wire:target="resetStaticPages">Resetting…</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
