{{--
    resources/views/livewire/masters/cost-center/delete-cost-center.blade.php

    Delete Cost Center Confirmation Modal
    Shows confirmation dialog before deleting a cost center
--}}

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Modal Header --}}
            <div class="pb-0 border-0 modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" wire:click="cancelDelete"></button>
            </div>

            {{-- Modal Body --}}
            <div class="p-4 text-center modal-body">

                {{-- Warning Icon --}}
                <div class="mb-3">
                    <i class="ti ti-alert-triangle" style="font-size: 5rem; color: #f1556c;"></i>
                </div>

                {{-- Heading --}}
                <h4 class="mb-2">Are you sure?</h4>
                <p class="mb-3 text-muted">You are about to delete the following Cost Center:</p>

                {{-- Get Cost Center Details --}}
                @php
                    $costCenterToDelete = \App\Models\CostCenter::find($deleteId);
                @endphp

                {{-- Display Cost Center Information --}}
                @if ($costCenterToDelete)
                    <div class="mb-4 alert alert-warning text-start">
                        {{-- Code --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Code:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-primary">{{ $costCenterToDelete->code }}</span>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Name:</div>
                            <div class="col-8">{{ $costCenterToDelete->name }}</div>
                        </div>

                        {{-- Description (if exists) --}}
                        @if ($costCenterToDelete->description)
                            <div class="row">
                                <div class="col-4 fw-bold">Description:</div>
                                <div class="col-8">{{ Str::limit($costCenterToDelete->description, 50) }}</div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Warning Message --}}
                <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>

                {{-- Action Buttons --}}
                <div class="gap-2 d-grid">
                    {{-- Confirm Delete Button --}}
                    <button wire:click="delete" class="btn btn-danger btn-lg">
                        <i class="ti ti-trash me-1"></i> Yes, Delete It!
                    </button>

                    {{-- Cancel Button --}}
                    <button wire:click="cancelDelete" class="btn btn-light">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
