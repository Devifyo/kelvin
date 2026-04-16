<footer>
    @php
        $ftInitials = strtoupper(substr($appName ?? 'KT', 0, 1))
                    . strtoupper(substr(strrchr($appName ?? 'KT', ' ') ?: 'T', 1, 1));
    @endphp
    <div class="footer-logo">
        <div class="footer-logo-mark">{{ $ftInitials }}</div>
        <div class="footer-name">{{ $appName ?? 'Kevin Thompson' }} <span>Ph.D.</span> Consulting</div>
    </div>
    <div class="footer-copy">&copy; {{ date('Y') }} {{ $appName ?? 'Kevin Thompson' }} Ph.D. Consulting. All rights reserved.</div>
    
    <nav class="footer-links">
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('services.training') }}" class="{{ request()->routeIs('services.training') ? 'active' : '' }}">Consulting & Training</a>
        
        {{-- Resources / Proof of Expertise --}}
        <a href="{{ route('papers') }}" class="{{ request()->routeIs('papers') ? 'active' : '' }}">Papers</a>
        <a href="{{ route('podcasts-webinars') }}" class="{{ request()->routeIs('podcasts-webinars') ? 'active' : '' }}">Podcasts & Webinars</a>
        <a href="{{ route('blog') }}" class="{{ request()->routeIs('blog') ? 'active' : '' }}">Blog</a>
        
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </nav>
</footer>