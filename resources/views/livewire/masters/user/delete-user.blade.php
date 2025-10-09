{{-- 
    resources/views/livewire/masters/user/delete-user.blade.php
    
    Delete User Confirmation Modal
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
                <p class="mb-3 text-muted">You are about to delete the following User:</p>

                {{-- Get User Details --}}
                @php
                    $userToDelete = \App\Models\User::with(['role', 'departments'])->find($deleteId);
                @endphp

                {{-- Display User Information --}}
                @if ($userToDelete)
                    <div class="mb-4 alert alert-warning text-start">
                        
                        {{-- Profile Photo & Name --}}
                        <div class="gap-3 mb-3 d-flex align-items-center">
                            @if($userToDelete->profile_photo_path)
                                <img src="{{ $userToDelete->profile_photo_url }}" 
                                     alt="{{ $userToDelete->name }}"
                                     class="rounded-circle"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center bg-primary fw-bold" 
                                     style="width: 60px; height: 60px; font-size: 24px;">
                                    {{ strtoupper(substr($userToDelete->name, 0, 1)) }}
                                </div>
                            @endif
                            
                            <div>
                                <h6 class="mb-1">{{ $userToDelete->name }}</h6>
                                <p class="mb-0 text-muted small">{{ $userToDelete->email }}</p>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Role:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-info">
                                    {{ $userToDelete->role->display_name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- Departments --}}
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Departments:</div>
                            <div class="col-8">
                                @forelse($userToDelete->departments as $dept)
                                    <span class="badge badge-soft-primary me-1">
                                        {{ $dept->short_name ?? $dept->department }}
                                    </span>
                                @empty
                                    <span class="text-muted small">None</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Phone --}}
                        @if($userToDelete->phone)
                            <div class="row">
                                <div class="col-4 fw-bold">Phone:</div>
                                <div class="col-8">{{ $userToDelete->phone }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Usage Check --}}
                    @php
                        $ticketCount = $userToDelete->tickets()->count();
                    @endphp

                    {{-- If user is current user --}}
                    @if($userToDelete->id === auth()->id())
                        <div class="p-3 alert alert-danger">
                            <h6 class="mb-2"><i class="ti ti-alert-circle me-1"></i> Cannot Delete</h6>
                            <p class="mb-0 small">
                                You cannot delete your own account while logged in.
                            </p>
                        </div>
                        <p class="mb-4 text-muted small">
                            Please contact another administrator to delete this account.
                        </p>
                    {{-- If user has tickets --}}
                    @elseif($ticketCount > 0)
                        <div class="p-3 alert alert-danger">
                            <h6 class="mb-2"><i class="ti ti-alert-circle me-1"></i> Cannot Delete</h6>
                            <p class="mb-0 small">
                                This user has created <strong>{{ $ticketCount }} ticket(s)</strong> and cannot be deleted.
                            </p>
                        </div>
                        <p class="mb-4 text-muted small">
                            <strong>Suggestion:</strong> You can deactivate this user instead to prevent login while preserving data.
                        </p>
                    @else
                        <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>
                    @endif
                @endif

                {{-- Action Buttons --}}
                <div class="gap-2 d-grid">
                    @if($userToDelete && $userToDelete->id !== auth()->id() && $ticketCount == 0)
                        {{-- Confirm Delete Button --}}
                        <button wire:click="delete" class="btn btn-danger btn-lg">
                            <i class="ti ti-trash me-1"></i> Yes, Delete User!
                        </button>
                    @else
                        {{-- Cannot Delete Button --}}
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="ti ti-ban me-1"></i> Cannot Delete
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
