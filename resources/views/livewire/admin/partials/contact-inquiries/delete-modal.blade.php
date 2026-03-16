@if($confirmingDelete)
    <div class="modal-overlay" wire:click.self="cancelDelete">
        <div class="modal-window" style="max-width: 440px; text-align: center; padding: 3rem 2.5rem;">
            
            {{-- Danger Warning Icon --}}
            <div class="danger-icon-wrap" style="margin-bottom: 1.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>

            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--slate); margin-bottom: 0.5rem;">Confirm Deletion</h3>
            <p style="color: var(--muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 2.5rem;">
                This inquiry will be permanently scrubbed from the system. This action is irreversible.
            </p>
            
            <div style="display: flex; gap: 1rem;">
                {{-- Cancel / Keep Button --}}
                <button wire:click="cancelDelete" class="btn-modal-action btn-modal-cancel">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Keep It
                </button>
                
                {{-- Confirm Delete Button --}}
                <button wire:click="deleteInquiry" class="btn-modal-action btn-modal-delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Delete Now
                </button>
            </div>
        </div>
    </div>
@endif