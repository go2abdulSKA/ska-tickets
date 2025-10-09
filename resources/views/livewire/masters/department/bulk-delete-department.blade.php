{{-- 
    resources/views/livewire/masters/department/bulk-delete-department.blade.php
    
    Bulk Delete Departments Confirmation Modal
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
                    You are about to delete <strong>{{ count($selectedItems) }} department(s)</strong>
                </p>

                {{-- Get Selected Departments --}}
                @php
                    $selectedDepartments = \App\Models\Department::whereIn('id', $selectedItems)->get();
                    
                    // Check usage
                    $departmentsInUse = [];
                    $canDelete = [];
                    
                    foreach ($selectedDepartments as $dept) {
                        $userCount = $dept->users()->count();
                        $ticketCount = $dept->tickets()->count();
                        $clientCount = $dept->clients()->count();
                        $serviceTypeCount = $dept->serviceTypes()->count();
                        $totalUsage = $userCount + $ticketCount + $clientCount + $serviceTypeCount;
                        
                        if ($totalUsage > 0) {
                            $departmentsInUse[] = [
                                'dept' => $dept,
                                'usage' => $totalUsage,
                                'details' => [
                                    'users' => $userCount,
                                    'tickets' => $ticketCount,
                                    'clients' => $clientCount,
                                    'services' => $serviceTypeCount,
                                ]
                            ];
                        } else {
                            $canDelete[] = $dept;
                        }
                    }
                @endphp

                {{-- Display Selected Departments --}}
                <div class="mb-4 alert alert-warning text-start">
                    <p class="mb-2 fw-bold">Selected Departments:</p>
                    <ul class="mb-0 ps-3 small">
                        @foreach($selectedDepartments as $dept)
                            <li>
                                {{ $dept->department }}
                                <span class="badge badge-soft-primary">{{ $dept->prefix }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- If some departments are in use --}}
                @if(count($departmentsInUse) > 0)
                    <div class="p-3 mb-3 alert alert-danger text-start">
                        <h6 class="mb-2">
                            <i class="ti ti-alert-circle me-1"></i>
                            {{ count($departmentsInUse) }} Department(s) Cannot be Deleted
                        </h6>
                        <p class="mb-2 small">
                            The following departments are currently being used:
                        </p>
                        <ul class="mb-0 ps-3 small">
                            @foreach($departmentsInUse as $item)
                                <li>
                                    <strong>{{ $item['dept']->department }}</strong>
                                    <ul class="mt-1">
                                        @if($item['details']['users'] > 0)
                                            <li>{{ $item['details']['users'] }} user(s)</li>
                                        @endif
                                        @if($item['details']['tickets'] > 0)
                                            <li>{{ $item['details']['tickets'] }} ticket(s)</li>
                                        @endif
                                        @if($item['details']['clients'] > 0)
                                            <li>{{ $item['details']['clients'] }} client(s)</li>
                                        @endif
                                        @if($item['details']['services'] > 0)
                                            <li>{{ $item['details']['services'] }} service type(s)</li>
                                        @endif
                                    </ul>
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
                            {{ count($canDelete) }} Department(s) Can be Deleted
                        </h6>
                        <ul class="mb-0 ps-3 small">
                            @foreach($canDelete as $dept)
                                <li>{{ $dept->department }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>
                @else
                    <p class="mb-4 text-muted">
                        None of the selected departments can be deleted as they are all in use.
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
                                Yes, Delete {{ count($canDelete) }} Department(s)!
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
