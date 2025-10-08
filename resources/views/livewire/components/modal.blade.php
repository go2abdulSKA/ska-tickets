<div wire:ignore.self class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $modalTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="close"></button>
            </div>

            <div class="modal-body">
                <div wire:loading class="py-3 text-center">
                    <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
                </div>

                <div wire:loading.remove>
                    {{-- safe: show slot only if available --}}
                    {{ $slot ?? '' }}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="close">Close</button>
                {{ $footer ?? '' }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:load', () => {
    Livewire.on('show-modal', (data) => {
        const modalId = data?.modalId || 'genericModal';
        const el = document.getElementById(modalId);
        if (!el) return;
        const modal = new bootstrap.Modal(el);
        modal.show();
    });

    Livewire.on('hide-modal', (data) => {
        const modalId = data?.modalId || 'genericModal';
        const el = document.getElementById(modalId);
        if (!el) return;
        const modal = bootstrap.Modal.getInstance(el);
        if (modal) modal.hide();
    });
});
</script>
@endpush
