<nav id="nav">
    @php
        $initials = strtoupper(substr($appName ?? 'KT', 0, 1))
                  . strtoupper(substr(strrchr($appName ?? 'KT', ' ') ?: 'T', 1, 1));
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

    <div class="nav-menu">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('services.training') }}" class="{{ request()->routeIs('services.training') || request()->routeIs('training') ? 'active' : '' }}">Consulting & Training</a>
        
        {{-- Resources / Proof of Expertise --}}
        <a href="{{ route('papers') }}" class="{{ request()->routeIs('papers') ? 'active' : '' }}">Papers &amp; Presentations</a>
        <a href="{{ route('podcasts-webinars') }}" class="{{ request()->routeIs('podcasts-webinars') ? 'active' : '' }}">Podcasts & Webinars</a>
        <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a>
        
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
    
    <a href="{{ route('papers') }}" class="{{ request()->routeIs('papers') ? 'active' : '' }}" onclick="closeDrawer()">Papers &amp; Presentations</a>
    <div class="drawer-line"></div>
    
    <a href="{{ route('podcasts-webinars') }}" class="{{ request()->routeIs('podcasts-webinars') ? 'active' : '' }}" onclick="closeDrawer()">Podcasts & Webinars</a>
    <div class="drawer-line"></div>
    
    <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}" onclick="closeDrawer()">Blog</a>
    <div class="drawer-line"></div>
    
    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}" onclick="closeDrawer()">Contact</a>
</div>