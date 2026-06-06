@if($showBlockModal)
    <div class="modal-overlay" wire:click.self="closeBlockModal">
        <div class="modal-window block-modal-window" style="max-width: 460px; padding: 2.25rem;">

            {{-- Close Button --}}
            <button wire:click="closeBlockModal" class="close-x">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" style="width: 18px; height: 18px; stroke-width: 2;">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            {{-- Header --}}
            <div class="block-modal-head">
                <div class="block-modal-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <h3 class="block-modal-title">Block Email</h3>
                <p class="block-modal-sub">
                    Prevent this address from submitting the contact form.
                </p>
                <div class="block-modal-email">{{ $blockTargetEmail }}</div>
            </div>

            {{-- Duration Options --}}
            <div class="block-duration-label">Block duration</div>
            <div class="block-duration-grid">
                @foreach($durationOptions as $value => $label)
                    <button type="button"
                        wire:click="$set('blockDuration', '{{ $value }}')"
                        class="duration-chip {{ $blockDuration === $value ? 'is-selected' : '' }} {{ $value === 'forever' ? 'duration-chip-full' : '' }}">
                        <span class="chip-radio"></span>
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Footer Actions --}}
            <div class="block-modal-actions">
                <button wire:click="closeBlockModal" class="btn-ghost">Cancel</button>
                <button wire:click="confirmBlock" class="btn-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Block Email
                </button>
            </div>
        </div>
    </div>
@endif
