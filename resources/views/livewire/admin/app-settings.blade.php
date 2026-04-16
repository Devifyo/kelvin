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
                        <button wire:click="removeAppIcon" wire:confirm="Remove the current app icon?" class="as-remove-btn">Remove current icon</button>
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
                        <button wire:click="removeFavicon" wire:confirm="Remove the current favicon?" class="as-remove-btn">Remove current favicon</button>
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
</div>
