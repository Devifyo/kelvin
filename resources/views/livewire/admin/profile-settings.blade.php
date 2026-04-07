<div class="settings-wrapper">
    <link href="{{ asset('css/admin/profile-settings.css') }}" rel="stylesheet">

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