<style>
    /* ─────────────────────────────────────────
       PRO ALERT UI
    ───────────────────────────────────────── */
    .pro-alert-toast {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem 1.25rem;
        background: var(--white, #ffffff);
        border-radius: 12px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.15), 0 4px 10px -5px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--slate, #1a2332);
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
        min-width: 300px;
        max-width: 400px;
    }

    .pro-alert-toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .pro-alert-toast.success { border-left-color: #10b981; } /* Emerald */
    .pro-alert-toast.error { border-left-color: #e11d48; }   /* Rose */
    .pro-alert-toast.info { border-left-color: var(--copper, #b5722a); }

    .pro-alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pro-alert-message {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--charcoal, #2c3a4a);
        line-height: 1.4;
    }
</style>

<div id="pro-global-alert" class="pro-alert-toast" role="alert">
    <div id="pro-alert-icon" class="pro-alert-icon"></div>
    <div id="pro-alert-message" class="pro-alert-message"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const alertEl = document.getElementById('pro-global-alert');
    const msgEl = document.getElementById('pro-alert-message');
    const iconEl = document.getElementById('pro-alert-icon');
    let timeout;

    // Premium SVGs for different alert states
    const icons = {
        success: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`,
        error: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`,
        info: `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--copper, #b5722a)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`
    };

    // The core trigger function
    window.showProAlert = function(message, type = 'info') {
        clearTimeout(timeout);
        
        // Reset classes to prevent CSS conflicts
        alertEl.className = 'pro-alert-toast';
        
        // Inject data
        msgEl.innerText = message;
        iconEl.innerHTML = icons[type] || icons.info;
        
        // Add specific color class and trigger animation
        alertEl.classList.add(type);
        
        // Small delay to allow DOM to register changes before animating in
        requestAnimationFrame(() => {
            alertEl.classList.add('show');
        });

        // Auto-dismiss after 4 seconds
        timeout = setTimeout(() => {
            alertEl.classList.remove('show');
        }, 4000);
    };

    /* |--------------------------------------------------------------------------
    | 1. Handle Standard Laravel Controller Sessions
    |--------------------------------------------------------------------------
    */
    @if(session()->has('success'))
        showProAlert("{{ session('success') }}", 'success');
    @elseif(session()->has('error'))
        showProAlert("{{ session('error') }}", 'error');
    @elseif(session()->has('info'))
        showProAlert("{{ session('info') }}", 'info');
    @endif

    /* |--------------------------------------------------------------------------
    | 2. Handle Livewire Dispatched Events
    |--------------------------------------------------------------------------
    */
    window.addEventListener('notify', event => {
        // Robust variable assignment to support both Livewire 2 and Livewire 3 event detail structures
        const message = event.detail.message || (event.detail[0] && event.detail[0].message) || 'Action completed.';
        const type = event.detail.type || (event.detail[0] && event.detail[0].type) || 'success';
        
        showProAlert(message, type);
    });
});
</script>