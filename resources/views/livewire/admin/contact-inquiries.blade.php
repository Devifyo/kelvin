<div class="lw-contact-manager">
    
    {{-- Top Controls: Search & Filters --}}
    <div class="list-controls">
        <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input type="text" wire:model.live.debounce.300ms="searchEmail" placeholder="Search by email address...">
        </div>

        <div class="filter-tabs">
            <button wire:click="setFilter('all')" class="filter-pill {{ $filterStatus === 'all' ? 'active' : '' }}">All</button>
            <button wire:click="setFilter('unread')" class="filter-pill {{ $filterStatus === 'unread' ? 'active' : '' }}">Unread</button>
            <button wire:click="setFilter('read')" class="filter-pill {{ $filterStatus === 'read' ? 'active' : '' }}">Read</button>
        </div>
    </div>

    {{-- Main List UI --}}
    <div class="inquiry-list-container">
        <div class="inquiry-list">
            @forelse($inquiries as $item)
                <div class="inquiry-row {{ !$item->is_read ? 'is-unread' : '' }}" wire:key="inquiry-{{ $item->id }}">
                    
                    {{-- Left: Avatar & Info --}}
                    <div class="inquiry-sender">
                        <div class="sender-avatar">
                            {{ strtoupper(substr($item->name, 0, 1)) }}
                        </div>
                        <div class="sender-text">
                            <div class="sender-name">{{ $item->name }}</div>
                            <div class="sender-email">{{ $item->email }}</div>
                        </div>
                    </div>

                    {{-- Middle: Status Badge & Subject --}}
                    <div class="inquiry-subject-col">
                        <div class="status-badge {{ $item->is_read ? 'badge-read' : 'badge-unread' }}">
                            {{ $item->is_read ? 'Read' : 'New' }}
                        </div>
                        <div class="subject-text" title="{{ $item->subject }}">
                            {{ Str::limit($item->subject, 50) }}
                        </div>
                    </div>

                    {{-- Right: Date/Time & Actions --}}
                    <div class="inquiry-meta">
                        <div class="datetime-block">
                            <div class="date-text">{{ $item->created_at->format('M d, Y') }}</div>
                            <div class="time-text">{{ $item->created_at->format('g:i A') }}</div>
                        </div>
                        
                        <div class="inquiry-actions">
                            {{-- View Button --}}
                            <button title="View Message" wire:click="viewInquiry({{ $item->id }})" class="icon-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            
                            {{-- Delete Button --}}
                            <button title="Delete" 
                                x-data 
                                x-on:click="
                                    Swal.fire({
                                        title: 'Delete Message?',
                                        text: 'This action cannot be undone.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#ef4444',
                                        cancelButtonColor: '#e5e7eb',
                                        confirmButtonText: 'Delete',
                                        cancelButtonText: '<span style=\'color: #374151\'>Cancel</span>'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $wire.deleteInquiry({{ $item->id }})
                                        }
                                    })
                                " 
                                class="icon-btn delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 4rem; color: #64748b;">
                    No inquiries match your filters.
                </div>
            @endforelse
        </div>
        
        <div style="padding: 1.2rem 1.5rem; border-top: 1px solid #e2e8f0;">
            {{ $inquiries->links() }}
        </div>
    </div>

    {{-- View Modal Include --}}
    @include('livewire.admin.partials.contact-inquiries.view-modal')

</div>

@push('styles')
<style>
    /* Top Controls (Search & Filters) */
    .lw-contact-manager .list-controls {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;
    }
    
    .lw-contact-manager .search-box {
        position: relative; flex: 1; max-width: 400px;
    }
    .lw-contact-manager .search-box svg {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
        width: 18px; height: 18px; color: var(--muted);
    }
    .lw-contact-manager .search-box input {
        width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem;
        border-radius: 10px; border: 1px solid var(--ivory3);
        background: var(--white); font-size: 0.95rem; color: var(--slate);
        transition: all 0.2s; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .lw-contact-manager .search-box input:focus {
        outline: none; border-color: var(--copper); box-shadow: 0 0 0 3px rgba(181, 114, 42, 0.15);
    }

    .lw-contact-manager .filter-tabs {
        display: flex; background: var(--white); border-radius: 10px;
        padding: 0.3rem; border: 1px solid var(--ivory3); box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .lw-contact-manager .filter-pill {
        padding: 0.5rem 1.2rem; border: none; background: transparent;
        border-radius: 6px; font-size: 0.85rem; font-weight: 600;
        color: var(--muted); cursor: pointer; transition: all 0.2s;
    }
    .lw-contact-manager .filter-pill:hover { color: var(--slate); }
    .lw-contact-manager .filter-pill.active {
        background: var(--copper);
        color: var(--white); box-shadow: 0 2px 8px rgba(181, 114, 42, 0.3);
    }

    /* List Container */
    .lw-contact-manager .inquiry-list-container {
        background: var(--white); border-radius: 12px;
        border: 1px solid var(--ivory3); box-shadow: 0 4px 20px rgba(26, 35, 50, 0.03);
    }

    /* Row Layout */
    .lw-contact-manager .inquiry-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3);
        transition: background 0.2s ease; gap: 1rem;
    }
    .lw-contact-manager .inquiry-row:last-child { border-bottom: none; }
    .lw-contact-manager .inquiry-row:hover { background: var(--ivory); }

    /* Unread State Highlighting */
    .lw-contact-manager .is-unread { background: rgba(181, 114, 42, 0.03); } /* Very light copper tint */
    .lw-contact-manager .is-unread .sender-name,
    .lw-contact-manager .is-unread .subject-text { font-weight: 700; color: var(--slate); }

    /* Column Sizing */
    .lw-contact-manager .inquiry-sender {
        display: flex; align-items: center; gap: 1rem; width: 25%;
    }
    .lw-contact-manager .inquiry-subject-col {
        display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 0;
    }
    .lw-contact-manager .inquiry-meta {
        display: flex; align-items: center; gap: 2rem; width: 25%; justify-content: flex-end;
    }

    /* Typography & Elements */
    .lw-contact-manager .sender-avatar {
        width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
        background: var(--copper); color: var(--white);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem; box-shadow: 0 2px 8px rgba(181, 114, 42, 0.2);
    }
    .lw-contact-manager .sender-name { font-weight: 600; color: var(--slate); font-size: 0.95rem; }
    .lw-contact-manager .sender-email { font-size: 0.8rem; color: var(--muted); margin-top: 0.1rem; }
    
    .lw-contact-manager .subject-text {
        font-size: 0.95rem; color: var(--charcoal); white-space: nowrap;
        overflow: hidden; text-overflow: ellipsis; flex: 1;
    }

    /* Status Badges */
    .lw-contact-manager .status-badge {
        padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.7rem;
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0;
    }
    .lw-contact-manager .badge-unread {
        background: rgba(181, 114, 42, 0.1); color: var(--copper); border: 1px solid var(--copper3);
    }
    .lw-contact-manager .badge-read {
        background: var(--ivory); color: var(--muted); border: 1px solid var(--ivory3);
    }

    /* Date and Time Block */
    .lw-contact-manager .datetime-block { text-align: right; }
    .lw-contact-manager .date-text { font-size: 0.85rem; font-weight: 600; color: var(--slate); }
    .lw-contact-manager .time-text { font-size: 0.75rem; color: var(--muted); margin-top: 0.1rem; }

    /* Action Buttons */
    .lw-contact-manager .inquiry-actions { display: flex; gap: 0.25rem; }
    .lw-contact-manager .icon-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px; 
        background: transparent; color: var(--muted); 
        border: none; transition: all 0.2s ease; cursor: pointer;
    }
    .lw-contact-manager .icon-btn:hover { background: var(--ivory3); color: var(--slate); }
    .lw-contact-manager .icon-btn.delete:hover { background: rgba(225, 29, 72, 0.1); color: var(--danger); }
    .lw-contact-manager .icon-btn svg { width: 18px; height: 18px; stroke-width: 1.8; }

    /* --- Modal Styles --- */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(26, 35, 50, 0.7); /* Based on --slate */
        backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 1000;
        padding: 2rem;
    }
    .modal-window {
        background: var(--white); width: 100%; max-width: 600px; border-radius: 16px;
        overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative; animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes modalPop { 
        from { opacity: 0; transform: scale(0.95) translateY(10px); } 
        to { opacity: 1; transform: scale(1) translateY(0); } 
    }

    .close-x {
        position: absolute; top: 1.25rem; right: 1.25rem;
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: var(--ivory); color: var(--muted); border: none;
        cursor: pointer; transition: all 0.2s; z-index: 10;
    }
    .close-x:hover { background: var(--danger); color: var(--white); transform: rotate(90deg); }
</style>
@endpush