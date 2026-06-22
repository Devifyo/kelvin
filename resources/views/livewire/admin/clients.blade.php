<div class="lw-service-manager">
    {{-- Reuse the shared admin CRUD styling (list rows, modal, form controls) --}}
    <link href="{{ asset('css/admin/consulting-services.css') }}" rel="stylesheet">

    <style>
        /* Client-specific tweaks layered on top of the shared service styles */
        .client-logo-cell {
            width: 64px; height: 48px;
            border: 1px solid var(--ivory3);
            border-radius: 8px;
            background: var(--white);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; flex-shrink: 0;
            cursor: pointer; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .client-logo-cell:hover { border-color: var(--copper); box-shadow: 0 4px 12px rgba(181,114,42,0.18); }
        .client-logo-cell:focus-visible { outline: 2px solid var(--copper); outline-offset: 2px; }
        .client-logo-cell img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
        .star-btn {
            background: none; border: none; cursor: pointer; padding: 0.3rem;
            display: flex; align-items: center; color: #cbd5e1; transition: color 0.2s, transform 0.15s;
        }
        .star-btn:hover { transform: scale(1.15); }
        .star-btn.is-featured { color: #f59e0b; }
        .logo-preview-box {
            width: 130px; height: 100px;
            border: 1px dashed var(--ivory3); border-radius: 12px;
            background: var(--white);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; position: relative; flex-shrink: 0;
        }
        .logo-preview-box img { width: 100%; height: 100%; object-fit: contain; padding: 8px; }

        /* ── Responsive: tablets ── */
        @media (max-width: 900px) {
            .lw-service-manager .list-controls { flex-direction: column; align-items: stretch; }
            .lw-service-manager .search-box { max-width: none; }
            .lw-service-manager .list-controls > div:last-child { width: 100%; }
            .lw-service-manager .list-controls .btn-create { flex: 1; justify-content: center; }
        }

        /* ── Responsive: phones ── */
        @media (max-width: 768px) {
            .lw-service-manager .service-row { flex-wrap: wrap; gap: 0.85rem 1rem; padding: 1.1rem 1rem; }
            .lw-service-manager .client-row-main { width: 100% !important; }
            .lw-service-manager .filter-tabs { flex-wrap: wrap; justify-content: center; }
            /* Modal goes (near) full-screen and single-column */
            .lw-service-manager .modal-overlay { padding: 0.75rem !important; }
            .lw-service-manager .modal-window { max-width: 100% !important; }
            .lw-service-manager .modal-header { padding: 1.25rem 1.5rem !important; }
            .lw-service-manager .modal-body { padding: 1.5rem !important; max-height: 78vh !important; }
            .lw-service-manager .modal-footer { padding: 1rem 1.5rem !important; }
            .lw-service-manager .form-grid-2 { grid-template-columns: 1fr !important; }
            .lw-service-manager .logo-row { flex-direction: column !important; align-items: stretch !important; gap: 1.25rem !important; }
            .lw-service-manager .logo-preview-box { width: 100%; }
        }
    </style>

    {{-- List Header Controls --}}
    <div class="list-controls">
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" wire:model.live.debounce.300ms="searchName" placeholder="Search clients by name...">
        </div>
        <div style="display: flex; gap: 1rem;">
            <div class="filter-tabs">
                <button wire:click="setFilter('all')" class="filter-pill {{ $filterStatus === 'all' ? 'active' : '' }}">All</button>
                <button wire:click="setFilter('active')" class="filter-pill {{ $filterStatus === 'active' ? 'active' : '' }}">Active</button>
                <button wire:click="setFilter('inactive')" class="filter-pill {{ $filterStatus === 'inactive' ? 'active' : '' }}">Inactive</button>
                <button wire:click="setFilter('featured')" class="filter-pill {{ $filterStatus === 'featured' ? 'active' : '' }}">Featured</button>
            </div>
            <button wire:click="create" class="btn-create">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Client
            </button>
        </div>
    </div>

    {{-- Data List --}}
    <div class="service-list-container">

        <div id="sortable-clients"
             x-data
             x-init="if (typeof Sortable !== 'undefined') {
                 Sortable.create($el, {
                     animation: 150,
                     handle: '.drag-handle',
                     onEnd: function () {
                         let orderedIds = Array.from($el.querySelectorAll('.service-row')).map(row => row.dataset.id);
                         $wire.updateSortOrder(orderedIds);
                     }
                 });
             }"
        >
            @forelse($clients as $item)
                <div class="service-row" wire:key="client-{{ $item->id }}" data-id="{{ $item->id }}">
                    <div class="client-row-main" style="display: flex; align-items: center; gap: 1.25rem; width: 42%;">

                        <div class="drag-handle" style="cursor: grab; color: var(--muted); display: flex; align-items: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="8" cy="4" r="1.5"/><circle cx="16" cy="4" r="1.5"/>
                                <circle cx="8" cy="12" r="1.5"/><circle cx="16" cy="12" r="1.5"/>
                                <circle cx="8" cy="20" r="1.5"/><circle cx="16" cy="20" r="1.5"/>
                            </svg>
                        </div>

                        <div class="client-logo-cell" wire:click="edit({{ $item->id }})" title="Edit {{ $item->name }}" role="button" tabindex="0">
                            @if($item->logo_url)
                                <img src="{{ $item->logo_url }}" alt="{{ $item->name }}">
                            @else
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--slate); font-size: 0.95rem;">{{ $item->name }}</div>
                            @if($item->website_url)
                                <div style="font-size: 0.75rem; color: var(--muted); font-family: monospace;">{{ \Illuminate\Support\Str::limit(preg_replace('#^https?://#', '', $item->website_url), 40) }}</div>
                            @else
                                <div style="font-size: 0.75rem; color: var(--muted);">No website</div>
                            @endif
                        </div>
                    </div>

                    {{-- Featured toggle --}}
                    <button wire:click="toggleFeatured({{ $item->id }})" class="star-btn {{ $item->is_featured ? 'is-featured' : '' }}"
                            aria-label="{{ $item->is_featured ? 'Featured on homepage. Click to remove from homepage' : 'Not featured. Click to feature on homepage' }}"
                            title="{{ $item->is_featured ? 'Featured on homepage - click to unfeature' : 'Not featured — click to feature on homepage' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $item->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </button>

                    {{-- Status toggle --}}
                    <button wire:click="toggleStatus({{ $item->id }})" class="status-badge {{ $item->status === 'active' ? 'badge-active' : 'badge-draft' }}"
                            aria-label="{{ $item->status === 'active' ? 'Status: active. Click to deactivate' : 'Status: inactive. Click to activate' }}"
                            title="{{ $item->status === 'active' ? 'Active - click to deactivate' : 'Inactive — click to activate' }}">
                        {{ ucfirst($item->status) }}
                    </button>

                    <div style="display: flex; gap: 0.75rem; align-items: center;">
                        <div style="text-align: right; margin-right: 1.5rem;">
                            <div style="font-size: 0.6rem; font-weight: 800; color: var(--muted);">SORT</div>
                            <div style="font-size: 0.85rem; font-weight: 700;">#{{ $item->sort_order }}</div>
                        </div>
                        <button wire:click="edit({{ $item->id }})" class="icon-btn" title="Edit client" aria-label="Edit {{ $item->name }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                        <button x-on:click="Swal.fire({title:'Delete Client?', text:'This action cannot be undone.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete'}).then((r)=>{if(r.isConfirmed) $wire.deleteClient({{$item->id}})})" class="icon-btn delete" title="Delete client" aria-label="Delete {{ $item->name }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 5rem; color: var(--muted); font-size: 0.9rem;">No clients found matching your criteria.</div>
            @endforelse
        </div>

        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--ivory3); background: var(--ivory);">
            {{ $clients->links('vendor.pagination.admin-theme') }}
        </div>
    </div>

    {{-- CREATE/EDIT MODAL --}}
    @if($showModal)
        <div class="modal-overlay" wire:click.self="closeModal">
            <div class="modal-window" style="max-width: 760px;">
                <button class="close-x" wire:click="closeModal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>

                <div class="modal-header">
                    <h2 style="font-family: 'Cormorant Garamond'; font-size: 2rem; font-weight: 600; color: var(--slate);">{{ $clientId ? 'Edit Client' : 'New Client' }}</h2>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="save">

                        <div class="form-divider">Logo</div>
                        <div class="logo-row" style="display: flex; gap: 2rem; background: var(--ivory); padding: 1.75rem; border-radius: 15px; border: 1px solid var(--ivory3); align-items: center;">
                            <div class="logo-preview-box">
                                <div wire:loading.flex wire:target="logo_file" style="position: absolute; inset: 0; background: rgba(255,255,255,0.8); align-items: center; justify-content: center; z-index: 10;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--copper)" stroke-width="2" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                                </div>

                                @if($logo_file && !$errors->has('logo_file'))
                                    <img src="{{ $logo_file->temporaryUrl() }}">
                                @elseif($existing_logo_path)
                                    <img src="{{ asset('storage/'.$existing_logo_path) }}">
                                @else
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                @endif
                            </div>
                            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                <label>Upload Logo <span style="color:#ef4444;">*</span></label>
                                <input type="file" wire:model="logo_file" accept="image/*" style="font-size: 0.8rem; margin-top: 0.25rem;">
                                @error('logo_file')<span class="error-msg">{{$message}}</span>@enderror
                                <div style="font-size: 0.65rem; color: var(--muted); margin-top: 0.5rem; line-height: 1.4;">PNG or JPG, transparent background preferred. Max 2MB. Logos are automatically resized &amp; compressed on upload.</div>
                            </div>
                        </div>

                        <div class="form-divider">Client Details</div>
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label>Client Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" wire:model="name" class="form-control" placeholder="e.g. Acme Corporation">
                                @error('name')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Website URL <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
                                <input type="url" wire:model="website_url" class="form-control" placeholder="https://example.com">
                                @error('website_url')<span class="error-msg">{{$message}}</span>@enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Short Description <span style="font-weight:400;color:var(--muted);">(optional)</span></label>
                            <textarea wire:model="description" class="form-control" rows="2" placeholder="A brief note about the engagement or relationship..."></textarea>
                            @error('description')<span class="error-msg">{{$message}}</span>@enderror
                        </div>

                        <div class="form-divider">Display Controls</div>
                        <div style="display: flex; align-items: center; gap: 3rem; flex-wrap: wrap;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label>Display Order</label>
                                <input type="number" wire:model="sort_order" class="form-control" style="width: 120px;">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label>Status</label>
                                <select wire:model="status" class="form-control" style="width: 160px;">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <label style="display: flex; align-items: center; gap: 0.75rem; font-weight: 800; cursor: pointer; font-size: 0.85rem; color: var(--slate); padding-top: 1.25rem;">
                                <input type="checkbox" wire:model="is_featured" style="width: 18px; height: 18px; accent-color: var(--copper);"> FEATURED ON HOMEPAGE
                            </label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" type="button" style="background: transparent; border: none; font-weight: 700; color: var(--muted); cursor: pointer; font-size: 0.9rem;">Cancel</button>
                    <button wire:click="save" type="button" style="background: var(--copper); color: white; border: none; padding: 0.9rem 2.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(181, 114, 42, 0.3); font-size: 0.95rem;">
                        {{ $clientId ? 'Update Client' : 'Create Client' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
