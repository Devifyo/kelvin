<div class="lw-service-manager rl-manager">
    <link href="{{ asset('css/admin/manage-papers.css') }}" rel="stylesheet">

    {{-- Featured hint — the public page opens on the Featured tab --}}
    <div class="rl-hint {{ $featuredCount === 0 ? 'rl-hint-warn' : '' }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        @if($featuredCount === 0)
            <span>No resources are featured yet, so the public <a href="{{ route('papers') }}" target="_blank" rel="noopener">Resource Library</a> currently opens on <strong>All Documents</strong>. Star a resource to enable the Featured view.</span>
        @else
            <span><strong>{{ $featuredCount }}</strong> featured {{ Str::plural('resource', $featuredCount) }} — visitors see these first on the <a href="{{ route('papers') }}" target="_blank" rel="noopener">Resource Library</a>. Click the star on any row to change.</span>
        @endif
    </div>

    <div class="list-controls">
        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search resources...">
            <div class="search-divider"></div>
            <div class="filter-wrap">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                <select wire:model.live="filterCategory" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div class="filter-tabs">
                <button wire:click="setFeaturedFilter('')" class="filter-pill {{ $filterFeatured === '' ? 'active' : '' }}">Everything</button>
                <button wire:click="setFeaturedFilter('1')" class="filter-pill {{ $filterFeatured === '1' ? 'active' : '' }}">&#9733; Featured</button>
            </div>
            <div class="filter-tabs">
                <button wire:click="setStatusFilter('')" class="filter-pill {{ $filterStatus === '' ? 'active' : '' }}">All</button>
                <button wire:click="setStatusFilter('1')" class="filter-pill {{ $filterStatus === '1' ? 'active' : '' }}">Active</button>
                <button wire:click="setStatusFilter('0')" class="filter-pill {{ $filterStatus === '0' ? 'active' : '' }}">Hidden</button>
            </div>
            <button wire:click="create" class="btn-create">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Resource
            </button>
        </div>
    </div>

    <div class="service-list-container">
        {{-- Sortable wrapper with Alpine --}}
        <div
            x-data
            x-init="
                Sortable.create($el, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(evt) {
                        // Collect new order of IDs from DOM
                        let ids = Array.from(evt.from.children).map(el => parseInt(el.dataset.id));
                        $wire.reorder(ids);
                    }
                })
            "
            wire:sortable="reorder"
        >
            @forelse ($papers as $paper)
                <div class="service-row" data-id="{{ $paper->id }}" wire:key="paper-{{ $paper->id }}">
                    {{-- Drag Handle --}}
                    <div class="drag-handle" title="Drag to reorder">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="6" r="1.5" fill="currentColor"/>
                            <circle cx="15" cy="6" r="1.5" fill="currentColor"/>
                            <circle cx="9" cy="12" r="1.5" fill="currentColor"/>
                            <circle cx="15" cy="12" r="1.5" fill="currentColor"/>
                            <circle cx="9" cy="18" r="1.5" fill="currentColor"/>
                            <circle cx="15" cy="18" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>

                    {{-- Featured star --}}
                    <button type="button"
                            wire:click="toggleFeatured({{ $paper->id }})"
                            class="rl-star {{ $paper->is_featured ? 'on' : '' }}"
                            title="{{ $paper->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}"
                            aria-pressed="{{ $paper->is_featured ? 'true' : 'false' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $paper->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </button>

                    <div class="rl-row-main">
                        <div class="rl-row-title">
                            {{ $paper->title }}
                            <span class="rl-type rl-type-{{ $paper->resource_type }}">{{ $paper->is_book ? 'Book' : ($paper->is_link ? 'Link' : ($paper->file_extension ? strtoupper($paper->file_extension) : 'No file')) }}</span>
                        </div>
                        <div class="rl-row-sub">
                            {{ $paper->category?->name ?? 'Uncategorized' }}
                            @if($paper->sub_category) &bull; {{ $paper->sub_category }} @endif
                            <span class="rl-slug">/{{ $paper->slug }}</span>
                            @if(! filled(strip_tags((string) $paper->long_description)))
                                <span class="rl-flag" title="The resource page is using the short card summary until you write a longer description.">Page copy pending</span>
                            @endif
                        </div>
                    </div>

                    <button wire:click="toggleStatus({{ $paper->id }})" class="status-badge {{ $paper->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $paper->is_active ? 'Active' : 'Hidden' }}
                    </button>

                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <div style="text-align: right; margin-right: 1rem; font-size: 0.85rem; font-weight: 700;">#{{ $paper->sort_order }}</div>
                        @if($paper->is_active)
                            <a href="{{ route('papers.show', $paper->slug) }}" target="_blank" rel="noopener" class="icon-btn" title="View live page">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                        @endif
                        <button wire:click="edit({{ $paper->id }})" class="icon-btn" title="Edit"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                        <button x-on:click="Swal.fire({title:'Delete this resource?', text:'Its public page will stop existing. This cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete'}).then((r)=>{if(r.isConfirmed) $wire.deletePaper({{$paper->id}})})" class="icon-btn delete" title="Delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    </div>
                    <h3>No resources found</h3>
                    <p>
                        @if($search || $filterCategory || $filterStatus !== '' || $filterFeatured !== '')
                            No resources match your current filters. Try adjusting your search or filters.
                        @else
                            No resources have been added yet.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ivory3); background: var(--ivory);">
            {{ $papers->links('vendor.pagination.admin-theme') }}
        </div>
    </div>

    @if($showModal)
        @include('livewire.admin.partials.papers.papers-modal')
    @endif
</div>
