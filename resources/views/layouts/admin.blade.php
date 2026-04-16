@php
    use App\Models\AppSetting;
    $c       = AppSetting::resolvedColors();
    $appName = AppSetting::get('app_name', AppSetting::DEFAULTS['app_name']);
    $favicon = AppSetting::get('favicon');
    $appIcon = AppSetting::get('app_icon');
    $faviconUrl = $favicon ? Storage::disk('public')->url($favicon) : '/favicon-32x32.png';
    $appIconUrl = $appIcon ? Storage::disk('public')->url($appIcon) : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', $title ?? 'Admin Dashboard') | {{ $appName }} Ph.D.</title>

    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <meta name="theme-color" content="{{ $c['slate'] }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --slate:    {{ $c['slate'] }};
            --slate-hi: {{ $c['slateHi'] }};
            --copper:   {{ $c['copper'] }};
            --copper2:  {{ $c['copper2'] }};
            --copper3:  {{ $c['copper3'] }};
            --ivory:    #faf7f2;
            --ivory3:   #e8dfd2;
            --charcoal: #2c3a4a;
            --muted:    #7a8fa0;
            --white:    #ffffff;
            --danger:   #e11d48;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, sans-serif;
            background-color: var(--ivory);
            color: var(--charcoal);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .tox-statusbar__branding {
            display: none !important;
        }
        /* ── SIDEBAR ── */
        .admin-sidebar {
            width: 280px;
            background-color: var(--slate);
            background-image: linear-gradient(180deg, var(--slate) 0%, #111827 100%);
            color: var(--white);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 50;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-logo-mark {
            width: 40px; height: 40px;
            border: 2px solid var(--copper2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 800; color: var(--copper2);
            flex-shrink: 0;
        }

        .sidebar-title { font-size: 1rem; font-weight: 700; line-height: 1.2; letter-spacing: -0.02em; }
        .sidebar-title span { display: block; font-size: 0.65rem; color: var(--copper3); letter-spacing: 0.1em; text-transform: uppercase; }

        .sidebar-nav { padding: 1rem 1rem; flex-grow: 1; display: flex; flex-direction: column; gap: 0.25rem; overflow-y: auto; min-height: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.72rem 1rem; color: rgba(255,255,255,0.7);
            text-decoration: none; font-size: 0.875rem; font-weight: 500;
            border-radius: 8px; transition: all 0.2s ease;
        }

        .nav-item:hover { color: var(--white); background: rgba(255,255,255,0.05); }

        .nav-item.active {
            color: var(--copper3);
            background: linear-gradient(90deg, rgba(181, 114, 42, 0.15) 0%, transparent 100%);
            border-left: 3px solid var(--copper);
            border-radius: 0 8px 8px 0;
        }

        .nav-item svg { width: 18px; height: 18px; stroke-width: 2; transition: color 0.2s; }
        .nav-item.active svg { color: var(--copper); }

        /* Fade hint at bottom of nav when it overflows */
        .sidebar-nav-wrap { flex-grow: 1; display: flex; flex-direction: column; min-height: 0; position: relative; }
        .sidebar-nav-wrap::after {
            content: ''; pointer-events: none;
            position: absolute; bottom: 0; left: 0; right: 0; height: 40px;
            background: linear-gradient(to bottom, transparent, rgba(17,24,39,0.85));
            opacity: 0; transition: opacity 0.2s;
        }
        .sidebar-nav-wrap.can-scroll::after { opacity: 1; }

        .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); }

        .logout-btn {
            display: flex; align-items: center; gap: 0.75rem;
            width: 100%; padding: 0.85rem 1rem;
            background: transparent; border: 1px solid rgba(255,255,255,0.1);
            color: var(--white); border-radius: 8px; cursor: pointer;
            font-size: 0.85rem; font-weight: 600; transition: all 0.2s;
        }
        .logout-btn:hover { background: rgba(225, 29, 72, 0.1); border-color: var(--danger); color: var(--danger); }

        /* ── MAIN CONTENT AREA ── */
        .admin-main {
            flex-grow: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }

        .topbar {
            height: 80px;
            background: var(--white);
            border-bottom: 1px solid var(--ivory3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 3rem;
            position: sticky; top: 0; z-index: 40;
        }

        .topbar-title { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 600; color: var(--slate); }

        .user-profile { display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 600; color: var(--slate); }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--copper); color: var(--white);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        .content-canvas { padding: 3rem; flex-grow: 1; }

        .mobile-toggle { display: none; background: none; border: none; color: var(--slate); cursor: pointer; }

        /* Mobile Backdrop Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(26, 35, 50, 0.5);
            backdrop-filter: blur(4px);
            z-index: 45;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* ── RESPONSIVE MOBILE LOGIC ── */
        @media (max-width: 1024px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            
            .admin-main { margin-left: 0; }
            .topbar { padding: 0 1.5rem; }
            .content-canvas { padding: 1.5rem; }
            
            .mobile-toggle { display: block; }
            
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }

        /* ── PROFILE DROPDOWN ── */
        .profile-dropdown-wrapper { position: relative; }
        .user-profile { 
            display: flex; align-items: center; gap: 0.75rem; 
            font-size: 0.85rem; font-weight: 600; color: var(--slate); 
            cursor: pointer; user-select: none;
        }
        .profile-dropdown {
            position: absolute; top: calc(100% + 15px); right: 0;
            background: var(--white); border: 1px solid var(--ivory3);
            border-radius: 12px; box-shadow: 0 10px 30px rgba(26,35,50,0.1);
            width: 220px; opacity: 0; visibility: hidden;
            transform: translateY(-10px); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 100;
        }
        .profile-dropdown.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-item { 
            display: flex; align-items: center; gap: 0.75rem; 
            padding: 0.85rem 1.25rem; color: var(--charcoal); 
            text-decoration: none; font-size: 0.85rem; font-weight: 600; 
            transition: all 0.2s; 
        }
        .dropdown-item:hover { background: var(--ivory); color: var(--copper); }
        .dropdown-item svg { width: 16px; height: 16px; stroke-width: 2; }
        .dropdown-divider { height: 1px; background: var(--ivory3); margin: 0.25rem 0; }
    </style>
    <link rel="stylesheet" href="/css/admin/app-settings.css">
    @livewireStyles
    @stack('styles')
</head>
<body>

    @include('layouts.partials.admin.admin-sidebar', ['appName' => $appName, 'appIconUrl' => $appIconUrl])

    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <main class="admin-main">
        <header class="topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-toggle" onclick="toggleSidebar()" aria-label="Toggle Navigation">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <h1 class="topbar-title">@yield('title', $title ?? 'Dashboard')</h1>
            </div>

            <div class="profile-dropdown-wrapper">
                <div class="user-profile" onclick="toggleDropdown(event)">
                    <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
                    <div class="user-avatar">KT</div>
                </div>
                
                <div class="profile-dropdown" id="profileMenu">
                    <a href="{{ route('admin.profile') }}" wire:navigate class="dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Account Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Secure Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div class="content-canvas">
            {{-- Supports BOTH Traditional Blade and Livewire Full-Page Components --}}
            @if(isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </main>

    <x-alert />

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tiny.cloud/1/{{ config('app.tinymce_api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        // Show fade hint when sidebar nav can scroll
        function updateNavFade() {
            const nav  = document.getElementById('sidebar-nav');
            const wrap = document.getElementById('sidebar-nav-wrap');
            if (!nav || !wrap) return;
            const canScroll = nav.scrollHeight > nav.clientHeight;
            const atBottom  = nav.scrollTop + nav.clientHeight >= nav.scrollHeight - 4;
            wrap.classList.toggle('can-scroll', canScroll && !atBottom);
        }
        document.addEventListener('DOMContentLoaded', () => {
            updateNavFade();
            document.getElementById('sidebar-nav')?.addEventListener('scroll', updateNavFade);
        });
        document.addEventListener('livewire:navigated', updateNavFade);

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            
            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // PRO TOUCH: Auto-close sidebar on Livewire SPA navigation (wire:navigate)
        document.addEventListener('livewire:navigated', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        function toggleDropdown(e) {
            e.stopPropagation();
            document.getElementById('profileMenu').classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('profileMenu');
            if (menu && menu.classList.contains('show') && !e.target.closest('.profile-dropdown-wrapper')) {
                menu.classList.remove('show');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>