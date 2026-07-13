<nav id="nav">
    @php
        $initials = \App\Models\AppSetting::initials($appName ?? null);
    @endphp
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-mark">
            @if($appIconUrl ?? null)
                <img src="{{ $appIconUrl }}" alt="{{ $appName ?? 'Logo' }}" style="width:100%;height:100%;object-fit:cover;border-radius:4px;">
            @else
                {{ $initials }}
            @endif
        </div>
        <div class="logo-text">
            <span class="logo-name">{{ $appName ?? 'Kevin Thompson' }}</span>
            <span class="logo-sub">Ph.D. Consulting</span>
        </div>
    </a>

    @php
        // "Resources" groups the proof-of-expertise pages — including the FAQ hub —
        // so the primary nav stays short and the FAQ is reachable from the top bar.
        $__resActive = request()->routeIs('papers')
            || request()->routeIs('podcasts-webinars')
            || request()->routeIs('blog*')
            || request()->routeIs('faq');
    @endphp
    <div class="nav-menu">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('services.training') }}" class="{{ request()->routeIs('services.training') || request()->routeIs('training') ? 'active' : '' }}">Consulting & Training</a>

        {{-- Resources / Proof of Expertise --}}
        <div class="nav-dropdown" data-nav-dropdown>
            <button type="button" class="nav-dd-toggle {{ $__resActive ? 'active' : '' }}" aria-expanded="false" aria-haspopup="true">
                Resources
                <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-dd-menu" role="menu">
                <a href="{{ route('papers') }}" role="menuitem" class="{{ request()->routeIs('papers') ? 'active' : '' }}">Papers &amp; Presentations</a>
                <a href="{{ route('podcasts-webinars') }}" role="menuitem" class="{{ request()->routeIs('podcasts-webinars') ? 'active' : '' }}">Podcasts &amp; Webinars</a>
                <a href="{{ route('blog') }}" role="menuitem" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a>
                <a href="{{ route('faq') }}" role="menuitem" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
            </div>
        </div>

        <a href="{{ route('contact') }}" class="nav-contact {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </div>

    <button class="burger" id="burger" aria-label="Toggle menu" onclick="toggleDrawer()">
        <span></span><span></span><span></span>
    </button>
</nav>

<div class="drawer" id="drawer">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}" onclick="closeDrawer()">Home</a>
    <div class="drawer-line"></div>
    
    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}" onclick="closeDrawer()">About</a>
    <div class="drawer-line"></div>
    
    <a href="{{ route('services.training') }}" class="{{ request()->routeIs('services.training') || request()->routeIs('training') ? 'active' : '' }}" onclick="closeDrawer()">Consulting & Training</a>
    <div class="drawer-line"></div>

    {{-- Resources group --}}
    <div class="drawer-group-label">Resources</div>
    <a href="{{ route('papers') }}" class="drawer-sub {{ request()->routeIs('papers') ? 'active' : '' }}" onclick="closeDrawer()">Papers &amp; Presentations</a>
    <a href="{{ route('podcasts-webinars') }}" class="drawer-sub {{ request()->routeIs('podcasts-webinars') ? 'active' : '' }}" onclick="closeDrawer()">Podcasts &amp; Webinars</a>
    <a href="{{ route('blog') }}" class="drawer-sub {{ request()->routeIs('blog*') ? 'active' : '' }}" onclick="closeDrawer()">Blog</a>
    <a href="{{ route('faq') }}" class="drawer-sub {{ request()->routeIs('faq') ? 'active' : '' }}" onclick="closeDrawer()">FAQ</a>
    <div class="drawer-line"></div>

    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}" onclick="closeDrawer()">Contact</a>
</div>