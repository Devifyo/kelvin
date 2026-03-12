<nav id="nav">
    <a href="{{ url('/') }}" class="logo">
        <div class="logo-mark">KT</div>
        <div class="logo-text">
            <span class="logo-name">Kevin Thompson</span>
            <span class="logo-sub">Ph.D. Consulting</span>
        </div>
    </a>

    <div class="nav-menu">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('papers') }}">Papers &amp; Presentations</a>
        <a href="{{ route('services') }}">Services</a>
        <a href="{{ route('training') }}">Training</a>
        <a href="{{ route('blog') }}">Blog</a>
        <a href="{{ route('contact') }}" class="nav-contact">Contact</a>
    </div>

    <button class="burger" id="burger" aria-label="Toggle menu" onclick="toggleDrawer()">
        <span></span><span></span><span></span>
    </button>
</nav>

<div class="drawer" id="drawer">
    <a href="{{ route('home') }}" onclick="closeDrawer()">Home</a>
    <div class="drawer-line"></div>
    <a href="{{ route('about') }}" onclick="closeDrawer()">About</a>
    <div class="drawer-line"></div>
    <a href="{{ route('papers') }}" onclick="closeDrawer()">Papers &amp; Presentations</a>
    <div class="drawer-line"></div>
    <a href="{{ route('services') }}" onclick="closeDrawer()">Services</a>
    <div class="drawer-line"></div>
    <a href="{{ route('training') }}" onclick="closeDrawer()">Training</a>
    <div class="drawer-line"></div>
    <a href="{{ route('blog') }}" onclick="closeDrawer()">Blog</a>
    <div class="drawer-line"></div>
    <a href="{{ route('contact') }}" onclick="closeDrawer()">Contact</a>
</div>