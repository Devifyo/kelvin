<nav id="nav">
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-mark">KT</div>
        <div class="logo-text">
            <span class="logo-name">Kevin Thompson</span>
            <span class="logo-sub">Ph.D. Consulting</span>
        </div>
    </a>

    <div class="nav-menu">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('papers') }}" class="{{ request()->routeIs('papers') ? 'active' : '' }}">Papers &amp; Presentations</a>
        <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a>
        <a href="{{ route('training') }}" class="{{ request()->routeIs('training*') ? 'active' : '' }}">Training</a>
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
    <a href="{{ route('papers') }}" class="{{ request()->routeIs('papers') ? 'active' : '' }}" onclick="closeDrawer()">Papers &amp; Presentations</a>
    <div class="drawer-line"></div>
    <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}" onclick="closeDrawer()">Services</a>
    <div class="drawer-line"></div>
    <a href="{{ route('training') }}" class="{{ request()->routeIs('training*') ? 'active' : '' }}" onclick="closeDrawer()">Training</a>
    <div class="drawer-line"></div>
    <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}" onclick="closeDrawer()">Blog</a>
    <div class="drawer-line"></div>
    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}" onclick="closeDrawer()">Contact</a>
</div>