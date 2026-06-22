<footer>
    @php
        $ftInitials = \App\Models\AppSetting::initials($appName ?? null);
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
        <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>

        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>

        <a href="{{ route('privacy') }}" class="{{ request()->routeIs('privacy') ? 'active' : '' }}">Privacy Policy</a>
        <a href="{{ route('terms') }}" class="{{ request()->routeIs('terms') ? 'active' : '' }}">Terms &amp; Conditions</a>
    </nav>
</footer>