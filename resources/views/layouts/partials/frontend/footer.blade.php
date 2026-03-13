<footer>
    <div class="footer-logo">
        <div class="footer-logo-mark">KT</div>
        <div class="footer-name">Kevin Thompson <span>Ph.D.</span> Consulting</div>
    </div>
    <div class="footer-copy">&copy; {{ date('Y') }} Kevin Thompson Ph.D. Consulting. All rights reserved.</div>
    <nav class="footer-links">
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('papers') }}" class="{{ request()->routeIs('papers') ? 'active' : '' }}">Papers</a>
        <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a>
        <a href="{{ route('training') }}" class="{{ request()->routeIs('training') ? 'active' : '' }}">Training</a>
        <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog') ? 'active' : '' }}">Blog</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </nav>
</footer>