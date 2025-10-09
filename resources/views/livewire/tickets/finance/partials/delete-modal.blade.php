{{-- resources/views/livewire/tickets/finance/partials/delete-modal.blade.php --}}

<div class="modal fade show" 
     style="display: block; background: rgba(0,0,0,0.5);" 
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            {{-- Modal Header --}}
            <div class="pb-0 border-0 modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" 
                        class="btn-close" 
                        wire:click="cancelDelete"></button>
            </div>

            {{-- Modal Body --}}
            <div class="p-4 text-center modal-body">
                
                {{-- Warning Icon --}}
                <div class="mb-3">
                    <i class="mdi mdi-alert-triangle text-danger" style="font-size: 5rem;"></i>
                </div>

                {{-- Heading --}}
                <h4 class="mb-2">Are you sure?</h4>
                <p class="mb-3 text-muted">
                    You are about to delete ticket: 
                    <strong class="text-danger">{{ $deleteTicketNo }}</strong>
                </p>

                {{-- Warning Message --}}
                <div class="alert alert-warning text-start">
                    <small>
                        <strong>Note:</strong> This ticket will be moved to trash. 
                        Only draft tickets can be deleted. This action can be undone by administrators.
                    </small>
                </div>

                {{-- Action Buttons --}}
                <div class="gap-2 d-grid">
                    <button type="button" 
                            wire:click="delete" 
                            class="btn btn-danger"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="delete">
                            <i class="mdi mdi-delete me-1"></i> Yes, Delete It!
                        </span>
                        <span wire:loading wire:target="delete">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Deleting...
                        </span>
                    </button>
                    <button type="button" 
                            wire:click="cancelDelete" 
                            class="btn btn-light">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
