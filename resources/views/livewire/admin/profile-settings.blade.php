<div class="settings-wrapper">
    <style>
        /* ─────────────────────────────────────────
           PRO UI: UNIFIED SETTINGS WINDOW
        ───────────────────────────────────────── */
        
        .settings-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 1rem;
        }

        .settings-window {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px; 
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--ivory3);
            box-shadow: 0 10px 40px -10px rgba(26, 35, 50, 0.08);
            overflow: hidden; 
        }

        /* ── Inner Sidebar (Left) ── */
        .settings-sidebar {
            width: 280px;
            background: var(--ivory);
            border-right: 1px solid var(--ivory3);
            padding: 2.5rem 1.5rem;
            flex-shrink: 0;
        }
        
        /* Renamed to prevent conflict with main admin layout */
        .settings-sidebar-title {
            font-family: -apple-system, sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            margin-bottom: 1.5rem;
            padding-left: 0.5rem;
        }

        .nav-pill {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            width: 100%;
            padding: 0.9rem 1rem;
            background: transparent;
            border: none;
            border-radius: 8px;
            color: var(--charcoal);
            font-size: 0.9rem;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s;
            font-family: -apple-system, sans-serif;
            margin-bottom: 0.4rem;
        }
        .nav-pill:hover {
            background: rgba(26, 35, 50, 0.04);
        }
        .nav-pill.active {
            background: var(--white);
            color: var(--copper);
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            border: 1px solid var(--ivory3);
        }
        .nav-pill svg { width: 18px; height: 18px; stroke-width: 2; }

        /* ── Content Canvas (Right) ── */
        .settings-canvas {
            flex: 1;
            padding: 3.5rem 4rem;
            background: var(--white);
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--slate);
            line-height: 1.1;
            margin-bottom: 0.5rem;
        }
        .section-desc {
            font-family: -apple-system, sans-serif;
            font-size: 0.95rem;
            color: var(--muted);
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--ivory3);
        }

        /* ── Form Elements ── */
        .form-group { margin-bottom: 1.75rem; }
        .form-group:last-child { margin-bottom: 0; }
        .form-label {
            display: block; font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--slate); margin-bottom: 0.6rem;
            font-family: -apple-system, sans-serif;
        }
        .form-input {
            width: 100%; max-width: 480px; padding: 0.95rem 1rem;
            background: var(--ivory); border: 1px solid var(--ivory3);
            border-radius: 8px; font-size: 0.95rem; color: var(--charcoal);
            font-family: -apple-system, sans-serif; transition: all 0.2s;
        }
        .form-input:focus {
            outline: none; border-color: var(--copper);
            background: var(--white); box-shadow: 0 0 0 3px rgba(181,114,42,0.1);
        }

        /* ── Button ── */
        .btn-save {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.9rem 2rem; background: var(--slate); color: var(--white);
            font-size: 0.85rem; font-weight: 700; border: none; border-radius: 8px;
            cursor: pointer; transition: all 0.2s; font-family: -apple-system, sans-serif;
            margin-top: 1rem; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .btn-save:hover { background: var(--copper); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(181,114,42,0.2); }
        .btn-save:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }
        .error-msg { color: var(--danger); font-size: 0.8rem; margin-top: 0.5rem; display: block; font-family: -apple-system, sans-serif; }

        @media (max-width: 850px) {
            .settings-window { flex-direction: column; min-height: auto; }
            .settings-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--ivory3); padding: 1.5rem; }
            .settings-canvas { padding: 2rem 1.5rem; }
            .form-input { max-width: 100%; }
        }
    </style>

    <div class="settings-window">
        
        <aside class="settings-sidebar">
            <div class="settings-sidebar-title">Account Settings</div>
            
            <button wire:click="setTab('profile')" class="nav-pill {{ $tab === 'profile' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Public Profile
            </button>
            
            <button wire:click="setTab('security')" class="nav-pill {{ $tab === 'security' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Security & Password
            </button>
        </aside>

        <div class="settings-canvas">
            
            @if($tab === 'profile')
            <div wire:key="tab-profile">
                <h2 class="section-title">Public Profile</h2>
                <p class="section-desc">Manage your display name and the primary email address associated with your administrator account.</p>

                <form wire:submit="updateProfile">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" wire:model="name" class="form-input" required>
                        @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" wire:model="email" class="form-input" required>
                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-save" wire:loading.attr="disabled" wire:target="updateProfile">
                        <span wire:loading.remove wire:target="updateProfile">Save Profile</span>
                        <span wire:loading wire:target="updateProfile">Saving...</span>
                    </button>
                </form>
            </div>
            @endif

            @if($tab === 'security')
            <div wire:key="tab-security">
                <h2 class="section-title">Security & Password</h2>
                <p class="section-desc">Ensure your account is using a long, randomized password to stay secure against unauthorized access.</p>

                <form wire:submit="updatePassword">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" wire:model="current_password" class="form-input" required>
                        @error('current_password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group" style="margin-top: 2.5rem;">
                        <label class="form-label">New Password</label>
                        <input type="password" wire:model="new_password" class="form-input" required>
                        @error('new_password') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" wire:model="new_password_confirmation" class="form-input" required>
                    </div>

                    <button type="submit" class="btn-save" wire:loading.attr="disabled" wire:target="updatePassword">
                        <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                        <span wire:loading wire:target="updatePassword">Updating...</span>
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
</div>