{{-- 
    resources/views/livewire/masters/client/bulk-delete-client.blade.php
    
    Bulk Delete Clients Confirmation Modal
--}}

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            {{-- Modal Header --}}
            <div class="pb-0 border-0 modal-header">
                <h5 class="modal-title">Confirm Bulk Deletion</h5>
                <button type="button" class="btn-close" wire:click="cancelBulkDelete"></button>
            </div>

            {{-- Modal Body --}}
            <div class="p-4 text-center modal-body">

                {{-- Warning Icon --}}
                <div class="mb-3">
                    <i class="ti ti-alert-triangle" style="font-size: 5rem; color: #f1556c;"></i>
                </div>

                {{-- Heading --}}
                <h4 class="mb-2">Are you sure?</h4>
                <p class="mb-3 text-muted">
                    You are about to delete <strong>{{ count($selectedItems) }} client(s)</strong>
                </p>

                {{-- Get Selected Clients --}}
                @php
                    $selectedClients = \App\Models\Client::with('department')->whereIn('id', $selectedItems)->get();
                    
                    // Check usage
                    $clientsInUse = [];
                    $canDelete = [];
                    
                    foreach ($selectedClients as $client) {
                        $ticketCount = $client->tickets()->count();
                        
                        if ($ticketCount > 0) {
                            $clientsInUse[] = [
                                'client' => $client,
                                'tickets' => $ticketCount,
                            ];
                        } else {
                            $canDelete[] = $client;
                        }
                    }
                @endphp

                {{-- Display Selected Clients --}}
                <div class="mb-4 alert alert-warning text-start">
                    <p class="mb-2 fw-bold">Selected Clients:</p>
                    <ul class="mb-0 ps-3 small">
                        @foreach($selectedClients as $client)
                            <li>
                                {{ $client->client_name }}
                                @if($client->company_name)
                                    ({{ $client->company_name }})
                                @endif
                                <span class="badge badge-soft-primary">
                                    {{ $client->department->short_name ?? $client->department->department }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- If some clients are in use --}}
                @if(count($clientsInUse) > 0)
                    <div class="p-3 mb-3 alert alert-danger text-start">
                        <h6 class="mb-2">
                            <i class="ti ti-alert-circle me-1"></i>
                            {{ count($clientsInUse) }} Client(s) Cannot be Deleted
                        </h6>
                        <p class="mb-2 small">
                            The following clients are currently being used in tickets:
                        </p>
                        <ul class="mb-0 ps-3 small">
                            @foreach($clientsInUse as $item)
                                <li>
                                    <strong>{{ $item['client']->client_name }}</strong>
                                    - {{ $item['tickets'] }} ticket(s)
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- If some can be deleted --}}
                @if(count($canDelete) > 0)
                    <div class="p-3 mb-3 alert alert-info text-start">
                        <h6 class="mb-2">
                            <i class="ti ti-check-circle me-1"></i>
                            {{ count($canDelete) }} Client(s) Can be Deleted
                        </h6>
                        <ul class="mb-0 ps-3 small">
                            @foreach($canDelete as $client)
                                <li>{{ $client->client_name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>
                @else
                    <p class="mb-4 text-muted">
                        None of the selected clients can be deleted as they are all in use.
                    </p>
                @endif

                {{-- Action Buttons --}}
                <div class="gap-2 d-grid">
                    @if(count($canDelete) > 0)
                        {{-- Confirm Delete Button --}}
                        <button wire:click="deleteSelected" 
                                class="btn btn-danger btn-lg"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="deleteSelected">
                                <i class="ti ti-trash me-1"></i> 
                                Yes, Delete {{ count($canDelete) }} Client(s)!
                            </span>
                            <span wire:loading wire:target="deleteSelected">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Deleting...
                            </span>
                        </button>
                    @else
                        {{-- Cannot Delete Button --}}
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="ti ti-ban me-1"></i> Cannot Delete (All In Use)
                        </button>
                    @endif

                    {{-- Cancel Button --}}
                    <button wire:click="cancelBulkDelete" class="btn btn-light">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
