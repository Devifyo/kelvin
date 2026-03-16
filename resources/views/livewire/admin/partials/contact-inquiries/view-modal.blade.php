@if($showModal && $selectedInquiry)
    <div class="modal-overlay" wire:click.self="closeModal">
        <div class="modal-window" style="padding: 2.5rem;">
            
            {{-- Close Button --}}
            <button wire:click="closeModal" class="close-x">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 18px; height: 18px; stroke-width: 2;">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--slate); margin-bottom: 1.5rem; border-bottom: 1px solid var(--ivory3); padding-bottom: 1rem;">
                Inquiry Details
            </h3>

            {{-- Info Header --}}
            <div style="background: var(--ivory); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--ivory3);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <div style="color: var(--slate);">
                        <span style="color: var(--muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.2rem;">From</span>
                        <strong>{{ $selectedInquiry->name }}</strong> 
                        <span style="color: var(--muted);">{{ $selectedInquiry->email }}</span>
                    </div>
                    <div style="text-align: right; color: var(--muted); font-size: 0.9rem;">
                        {{ $selectedInquiry->created_at->format('M d, Y') }}<br>
                        {{ $selectedInquiry->created_at->format('g:i A') }}
                    </div>
                </div>
                
                <div style="color: var(--slate); padding-top: 0.75rem; border-top: 1px solid var(--ivory3);">
                    <span style="color: var(--muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.2rem;">Subject</span>
                    <strong style="font-size: 1.05rem;">{{ $selectedInquiry->subject }}</strong>
                </div>
            </div>

            {{-- Message Body --}}
            <div style="color: var(--charcoal); font-size: 1rem; line-height: 1.7;">
                <span style="color: var(--muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.5rem;">Message</span>
                <div style="padding: 1.25rem; background: var(--white); border: 1px solid var(--ivory3); border-radius: 12px; white-space: pre-wrap; color: var(--slate);">{{ $selectedInquiry->message }}</div>
            </div>
            
            {{-- Footer Action --}}
            <div style="margin-top: 2rem; text-align: right;">
                <button wire:click="closeModal" style="padding: 0.75rem 1.5rem; background: var(--slate); border: none; border-radius: 8px; cursor: pointer; color: var(--white); font-weight: 600; box-shadow: 0 4px 12px rgba(181, 114, 42, 0.25); transition: transform 0.2s;">
                    Close Window
                </button>
            </div>
        </div>
    </div>
@endif