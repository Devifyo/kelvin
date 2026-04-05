<div class="lw-service-manager">
    <style>
        /* Shared Admin Styles */
        .lw-service-manager .list-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }
        .lw-service-manager .search-box { position: relative; flex: 1; max-width: 680px; display: flex; align-items: center; border-radius: 10px; border: 1px solid var(--ivory3); background: var(--white); overflow: hidden; transition: border-color 0.15s; }
        .lw-service-manager .search-box:focus-within { border-color: var(--slate); }
        .lw-service-manager .search-box .search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--muted); pointer-events: none; flex-shrink: 0; }
        .lw-service-manager .search-box input { flex: 1; padding: 0.75rem 1rem 0.75rem 2.75rem; border: none; background: transparent; outline: none; min-width: 0; }
        .search-divider { width: 1px; height: 22px; background: var(--ivory3); flex-shrink: 0; }
        .filter-wrap { position: relative; display: flex; align-items: center; flex-shrink: 0; }
        .filter-wrap .filter-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--muted); pointer-events: none; }
        .filter-select { padding: 0.75rem 2rem 0.75rem 2.25rem; border: none; background: transparent; color: var(--slate); font-size: 0.875rem; outline: none; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.6rem center; }
        .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--slate); color: var(--white); border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .lw-service-manager .filter-tabs { display: flex; background: var(--white); border-radius: 10px; padding: 0.3rem; border: 1px solid var(--ivory3); }
        .lw-service-manager .filter-pill { padding: 0.5rem 1.2rem; border: none; background: transparent; border-radius: 6px; font-size: 0.85rem; font-weight: 600; color: var(--muted); cursor: pointer; transition: all 0.2s; }
        .lw-service-manager .filter-pill.active { background: var(--copper); color: var(--white); }
        .service-list-container { background: var(--white); border-radius: 12px; border: 1px solid var(--ivory3); overflow: hidden; }
        .service-row { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--ivory3); transition: background 0.2s; }
        .service-row:hover { background: var(--ivory); }

        /* Drag-and-drop styles */
        .drag-handle { cursor: grab; color: var(--muted); padding: 0.25rem; display: flex; align-items: center; }
        .drag-handle:active { cursor: grabbing; }
        .service-row.sortable-ghost { opacity: 0.4; background: var(--ivory2); }
        .service-row.sortable-chosen { box-shadow: 0 4px 16px rgba(0,0,0,0.12); background: var(--white); }

        .modal-overlay { position: fixed; inset: 0; background: rgba(26, 35, 50, 0.85); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 2rem; }
        .modal-window { background: var(--white); width: 100%; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); display: flex; flex-direction: column; position: relative; }
        .close-x { position: absolute; top: 1.25rem; right: 1.25rem; width: 34px; height: 34px; border-radius: 50%; background: var(--ivory); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 11; }
        .modal-header { padding: 1.5rem 2.5rem; border-bottom: 1px solid var(--ivory3); background: var(--ivory2); border-radius: 20px 20px 0 0; }
        .modal-body { padding: 2.5rem; max-height: 70vh; overflow-y: auto; }
        .modal-footer { padding: 1.25rem 2.5rem; background: var(--ivory2); border-top: 1px solid var(--ivory3); display: flex; justify-content: flex-end; gap: 1.25rem; border-radius: 0 0 20px 20px; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.7rem; font-weight: 800; color: var(--slate); text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.08em; }
        .form-control { width: 100%; padding: 0.9rem 1.1rem; border-radius: 10px; border: 1.5px solid var(--ivory3); }
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; border: none; background: transparent; cursor: pointer; color: var(--muted); display: inline-flex; align-items: center; justify-content: center; }
        .icon-btn:hover { background: var(--ivory3); color: var(--slate); }
        .icon-btn.delete:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .status-badge { padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; border: none; cursor: pointer; }
        .badge-active { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .badge-inactive { background: var(--ivory3); color: var(--muted); }
        .error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.4rem; display: block; font-weight: 600; }
        .preview-box { margin-top: 0.75rem; padding: 0.75rem; border: 1px dashed var(--ivory3); border-radius: 8px; display: inline-block; background: var(--ivory2); }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; gap: 1rem; }
        .empty-state-icon { width: 56px; height: 56px; border-radius: 16px; background: var(--ivory2); display: flex; align-items: center; justify-content: center; color: var(--muted); }
        .empty-state h3 { font-size: 1rem; font-weight: 700; color: var(--slate); margin: 0; }
        .empty-state p { font-size: 0.85rem; color: var(--muted); margin: 0; }
    </style>

    <div class="list-controls">
        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live="search" placeholder="Search papers...">
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

        <div style="display: flex; gap: 1rem; align-items: center;">
            <div class="filter-tabs">
                <button wire:click="setStatusFilter('')" class="filter-pill {{ $filterStatus === '' ? 'active' : '' }}">All</button>
                <button wire:click="setStatusFilter('1')" class="filter-pill {{ $filterStatus === '1' ? 'active' : '' }}">Active</button>
                <button wire:click="setStatusFilter('0')" class="filter-pill {{ $filterStatus === '0' ? 'active' : '' }}">Hidden</button>
            </div>
            <button wire:click="create" class="btn-create">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Document
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
                <div class="service-row" data-id="{{ $paper->id }}">
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

                    <div style="width: 50%;">
                        <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $paper->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--muted);">{{ $paper->category?->name ?? 'Uncategorized' }} &bull; {{ $paper->sub_category }}</div>
                    </div>

                    <button wire:click="toggleStatus({{ $paper->id }})" class="status-badge {{ $paper->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $paper->is_active ? 'Active' : 'Hidden' }}
                    </button>

                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <div style="text-align: right; margin-right: 1.5rem; font-size: 0.85rem; font-weight: 700;">#{{ $paper->sort_order }}</div>
                        <button wire:click="edit({{ $paper->id }})" class="icon-btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                        <button x-on:click="Swal.fire({title:'Delete?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444'}).then((r)=>{if(r.isConfirmed) $wire.deletePaper({{$paper->id}})})" class="icon-btn delete"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    </div>
                    <h3>No documents found</h3>
                    <p>
                        @if($search || $filterCategory || $filterStatus !== '')
                            No documents match your current filters. Try adjusting your search or filters.
                        @else
                            No documents have been added yet.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($papers->hasPages())
            <div style="padding: 1.25rem; background: var(--ivory2);">{{ $papers->links() }}</div>
        @endif
    </div>

    @if($showModal)
        @include('admin.partials.papers.papers-modal')
    @endif
</div>