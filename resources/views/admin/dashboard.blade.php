@extends('layouts.admin')

@section('title', 'Overview Dashboard')

@push('styles')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--white);
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid var(--ivory3);
        box-shadow: 0 4px 12px rgba(26,35,50,0.02);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(26,35,50,0.06);
    }

    .stat-info h3 {
        font-family: -apple-system, sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted);
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--slate);
        line-height: 1;
    }

    .stat-icon {
        width: 48px; height: 48px;
        background: var(--ivory);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--copper);
    }
    .stat-icon svg { width: 24px; height: 24px; stroke-width: 2; }

    /* Recent Section */
    .section-title {
        font-family: -apple-system, sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--ivory3);
    }

    .empty-state {
        background: var(--white);
        border: 1px dashed var(--ivory3);
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
    }
    .empty-state svg {
        width: 40px; height: 40px;
        color: var(--muted);
        margin-bottom: 1rem;
    }
    .empty-state p {
        font-size: 0.95rem; color: var(--muted);
    }
</style>
@endpush

@section('content')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Active Classes</h3>
                <div class="stat-number">07</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Published Papers</h3>
                <div class="stat-number">15</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>New Inquiries</h3>
                <div class="stat-number">03</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
        </div>
    </div>

    <div>
        <h2 class="section-title">Recent Activity</h2>
        
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <p>Your dashboard is ready. Recent system events and database updates will appear here.</p>
        </div>
    </div>

@endsection