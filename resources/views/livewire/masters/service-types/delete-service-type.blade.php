{{--
    resources/views/livewire/masters/service-type/delete-service-type.blade.php

    Delete Service Type Confirmation Modal
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
                <p class="mb-3 text-muted">You are about to delete the following Service Type:</p>

                {{-- Get Service Type Details --}}
                @php
                    $serviceTypeToDelete = \App\Models\ServiceType::with('department')->find($deleteId);
                @endphp

                {{-- Display Service Type Info --}}
                @if ($serviceTypeToDelete)
                    <div class="mb-4 alert alert-warning text-start">
                        {{-- Code --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Service Type:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-primary">{{ $serviceTypeToDelete->service_type }}</span>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Name:</div>
                            <div class="col-8">{{ $serviceTypeToDelete->service_type }}</div>
                        </div>

                        {{-- Department --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Department:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-secondary">
                                    {{ $serviceTypeToDelete->department->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if ($serviceTypeToDelete->description)
                            <div class="row">
                                <div class="col-4 fw-bold">Description:</div>
                                <div class="col-8">{{ Str::limit($serviceTypeToDelete->description, 50) }}</div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Warning --}}
                <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>

                {{-- Action Buttons --}}
                <div class="gap-2 d-grid">
                    {{-- Delete Button --}}
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
