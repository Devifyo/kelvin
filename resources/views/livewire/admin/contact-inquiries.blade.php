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
            <button wire:click="setFilter('blocked')" class="filter-pill filter-pill-blocked {{ $filterStatus === 'blocked' ? 'active' : '' }}">Blocked</button>
        </div>
    </div>

    {{-- Main List UI --}}
    <div class="inquiry-list-container">
        <div class="inquiry-list">
            @forelse($inquiries as $item)
                @php $isBlocked = $blockedEmails->has($item->email); @endphp
                <div class="inquiry-row {{ !$item->is_read ? 'is-unread' : '' }} {{ $isBlocked ? 'is-blocked' : '' }}" wire:key="inquiry-{{ $item->id }}">
                    
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
                        @if($isBlocked)
                            @php $block = $blockedEmails->get($item->email); @endphp
                            <div class="status-badge badge-blocked"
                                title="Blocked since {{ $block->created_at->format('M d, Y') }}{{ $block->blocked_until ? ' · until ' . $block->blocked_until->format('M d, Y') : ' · until unblocked' }}">
                                Blocked
                            </div>
                        @endif
                        <div class="subject-text" title="{{ $item->subject }}">
                            {{ Str::limit($item->subject, 50) }}
                        </div>
                    </div>

                    {{-- Right: Date/Time & Actions --}}
                    <div class="inquiry-meta">
                        <div class="datetime-block">
                            <div class="date-text">{{ $item->created_at->format('M d, Y') }}</div>
                            <div class="time-text">{{ $item->created_at->format('g:i A') }}</div>
                            @if($isBlocked)
                                <div class="blocked-since-text">
                                    Blocked {{ $block->created_at->format('M d, Y') }}
                                    @if($block->blocked_until)
                                        · til {{ $block->blocked_until->format('M d, Y') }}
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="inquiry-actions">
                            {{-- View Button --}}
                            <button title="View Message" wire:click="viewInquiry({{ $item->id }})" class="icon-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            
                            {{-- Block / Unblock Button --}}
                            @if($isBlocked)
                                <button title="Unblock Email"
                                    x-data
                                    x-on:click="
                                        Swal.fire({
                                            title: 'Unblock Email?',
                                            text: '{{ $item->email }} will be able to submit the contact form again.',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonColor: '#b5722a',
                                            cancelButtonColor: '#e5e7eb',
                                            confirmButtonText: 'Unblock',
                                            cancelButtonText: '<span style=\'color: #374151\'>Cancel</span>'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $wire.unblockEmail({{ $item->id }})
                                            }
                                        })
                                    "
                                    class="icon-btn unblock">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </button>
                            @else
                                <button title="Block Email" wire:click="openBlockModal({{ $item->id }})" class="icon-btn block">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </button>
                            @endif

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
        
        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ivory3); background: var(--ivory);">
            {{ $inquiries->links('vendor.pagination.admin-theme') }}
        </div>
    </div>

    {{-- View Modal Include --}}
    @include('livewire.admin.partials.contact-inquiries.view-modal')

    {{-- Block Modal Include --}}
    @include('livewire.admin.partials.contact-inquiries.block-modal')

    <link href="{{ asset('css/admin/contact-inquiries.css') }}" rel="stylesheet">
</div>