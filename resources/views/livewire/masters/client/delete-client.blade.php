{{-- 
    resources/views/livewire/masters/client/delete-client.blade.php
    
    Delete Client Confirmation Modal
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
                <p class="mb-3 text-muted">You are about to delete the following Client:</p>

                {{-- Get Client Details --}}
                @php
                    $clientToDelete = \App\Models\Client::with('department')->find($deleteId);
                @endphp

                {{-- Display Client Information --}}
                @if ($clientToDelete)
                    <div class="mb-4 alert alert-warning text-start">
                        
                        {{-- Client Name --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Client:</div>
                            <div class="col-8">{{ $clientToDelete->client_name }}</div>
                        </div>

                        {{-- Company --}}
                        @if($clientToDelete->company_name)
                            <div class="mb-2 row">
                                <div class="col-4 fw-bold">Company:</div>
                                <div class="col-8">{{ $clientToDelete->company_name }}</div>
                            </div>
                        @endif

                        {{-- Department --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Department:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-primary">
                                    {{ $clientToDelete->department->short_name ?? $clientToDelete->department->department }}
                                </span>
                            </div>
                        </div>

                        {{-- Contact --}}
                        @if($clientToDelete->phone || $clientToDelete->email)
                            <div class="row">
                                <div class="col-4 fw-bold">Contact:</div>
                                <div class="col-8">
                                    @if($clientToDelete->phone)
                                        <div>{{ $clientToDelete->phone }}</div>
                                    @endif
                                    @if($clientToDelete->email)
                                        <div class="small text-muted">{{ $clientToDelete->email }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Usage Check --}}
                    @php
                        $ticketCount = $clientToDelete->tickets()->count();
                    @endphp

                    {{-- If client is in use --}}
                    @if($ticketCount > 0)
                        <div class="p-3 alert alert-danger">
                            <h6 class="mb-2"><i class="ti ti-alert-circle me-1"></i> Cannot Delete</h6>
                            <p class="mb-0 small">
                                This client is currently being used in <strong>{{ $ticketCount }} ticket(s)</strong> and cannot be deleted.
                            </p>
                        </div>
                        <p class="mb-4 text-muted small">
                            <strong>Suggestion:</strong> You can deactivate this client instead to prevent future use while preserving existing data.
                        </p>
                    @else
                        <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>
                    @endif
                @endif

                {{-- Action Buttons --}}
                <div class="gap-2 d-grid">
                    @if($clientToDelete && $ticketCount == 0)
                        {{-- Confirm Delete Button --}}
                        <button wire:click="delete" class="btn btn-danger btn-lg">
                            <i class="ti ti-trash me-1"></i> Yes, Delete It!
                        </button>
                    @else
                        {{-- Cannot Delete Button --}}
                        <button wire:click="$set('showDeleteModal', false)" class="btn btn-secondary btn-lg" disabled>
                            <i class="ti ti-ban me-1"></i> Cannot Delete (In Use)
                        </button>
                    @endif

                    {{-- Cancel Button --}}
                    <button wire:click="cancelDelete" class="btn btn-light">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
