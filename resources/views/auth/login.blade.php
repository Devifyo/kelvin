@extends('layouts.auth')

@section('content')
<div class="login-card">
    <div class="auth-header">
        <div class="logo">
            <div class="logo-mark">KT</div>
            <div class="logo-text-wrap">
                <span class="logo-name">Kevin Thompson</span>
                <span class="logo-sub">Ph.D. Consulting</span>
            </div>
        </div>
        
        <h1 class="auth-title">Admin Portal</h1>
        <p class="auth-subtitle">Restricted access for authorized personnel</p>
    </div>

    <form method="POST" action="{{ route('auth.login') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                class="form-input" 
                value="{{ old('email') }}" 
                placeholder="admin@kevinthompson.com"
                required autofocus 
                autocomplete="email"
            >
            @error('email')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                class="form-input" 
                placeholder="••••••••••••"
                required 
                autocomplete="current-password"
            >
            <button type="button" class="pw-toggle" onclick="togglePw(this)" aria-label="Toggle password visibility">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <input type="hidden" name="remember" value="1">

        <button type="submit" class="btn-submit">
            <span>Authenticate</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>
</div>

<script>
function togglePw(btn) {
    const input = document.getElementById('password');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    
    btn.innerHTML = isHidden
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
}
</script>
@endsection