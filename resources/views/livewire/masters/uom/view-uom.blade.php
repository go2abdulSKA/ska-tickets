
<div class="offcanvas offcanvas-end show" style="visibility: visible;" tabindex="-1">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">UOM Details</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Basic Information</h6>

            <div class="mb-3">
                <label class="text-muted fw-bold">Code:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-primary font-14">{{ $viewUom->code }}</span>
                </p>
            </div>

            <div class="mb-3">
                <label class="text-muted fw-bold">Name:</label>
                <p class="mb-0">{{ $viewUom->name }}</p>
            </div>

            <div class="mb-3">
                <label class="text-muted fw-bold">Description:</label>
                <p class="mb-0">{{ $viewUom->description ?? 'N/A' }}</p>
            </div>

            <div class="mb-3">
                <label class="text-muted fw-bold">Status:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-{{ $viewUom->is_active ? 'success' : 'danger' }}">
                        {{ $viewUom->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Usage Statistics</h6>

            <div class="mb-3">
                <label class="text-muted fw-bold">Used in Transactions:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        {{ $viewUom->ticket_transactions_count }} transactions
                    </span>
                </p>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Audit Information</h6>

            <div class="mb-3">
                <label class="text-muted fw-bold">Created By:</label>
                <p class="mb-0">{{ $viewUom->creator->name ?? 'N/A' }}</p>
                <small class="text-muted">{{ $viewUom->created_at->format('d M, Y h:i A') }}</small>
            </div>

            @if ($viewUom->updated_at != $viewUom->created_at)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0">{{ $viewUom->updater->name ?? 'N/A' }}</p>
                    <small class="text-muted">{{ $viewUom->updated_at->format('d M, Y h:i A') }}</small>
                </div>
            @endif
        </div>

        <div class="gap-2 d-grid">
            <button type="button" wire:click="edit({{ $viewUom->id }}); $set('showOffcanvas', false)"
                class="btn btn-primary">
                <i class="ti ti-edit me-1"></i> Edit UOM
            </button> <button type="button" wire:click="closeOffcanvas" class="btn btn-light">
                Close
            </button>
        </div>
    </div>
</div>
<div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>
