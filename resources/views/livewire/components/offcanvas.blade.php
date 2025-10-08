{{-- resources/views/livewire/components/offcanvas.blade.php --}}
{{-- Generic Reusable Offcanvas Component --}}

<div wire:ignore.self
     class="offcanvas offcanvas-{{ $placement }}"
     tabindex="-1"
     id="{{ $offcanvasId }}"
     aria-labelledby="{{ $offcanvasId }}Label">

    {{-- Offcanvas Header --}}
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="{{ $offcanvasId }}Label">
            {{ $offcanvasTitle }}
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" wire:click="close"></button>
    </div>

    {{-- Offcanvas Body --}}
    <div class="offcanvas-body">

        {{-- Loading State --}}
        <div wire:loading class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2">Loading details...</p>
        </div>

        {{-- Content - Slot from parent component --}}
        <div wire:loading.remove>
            {{ $slot }}
        </div>

    </div>

    {{-- Offcanvas Footer (Optional) --}}
    @if(isset($footer))
    <div class="offcanvas-footer border-top p-3">
        {{ $footer }}
    </div>
    @endif

</div>

{{-- JavaScript to handle offcanvas events --}}
@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Listen for show-offcanvas event
        Livewire.on('show-offcanvas', (data) => {
            const offcanvasId = data.offcanvasId || 'genericOffcanvas';
            const offcanvasElement = document.getElementById(offcanvasId);
            if (offcanvasElement) {
                const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                offcanvas.show();
            }
        });

        // Listen for hide-offcanvas event
        Livewire.on('hide-offcanvas', (data) => {
            const offcanvasId = data.offcanvasId || 'genericOffcanvas';
            const offcanvasElement = document.getElementById(offcanvasId);
            if (offcanvasElement) {
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (offcanvas) {
                    offcanvas.hide();
                }
            }
        });
    });
</script>
@endpush
