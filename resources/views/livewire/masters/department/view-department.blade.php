{{-- 
    resources/views/livewire/masters/department/view-department.blade.php
    
    View Department Offcanvas
    Displays detailed information about a department
--}}

<div class="offcanvas offcanvas-end show" style="visibility: visible; width: 400px;" tabindex="-1">
    {{-- Offcanvas Header --}}
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Department Details</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>
    
    {{-- Offcanvas Body --}}
    <div class="offcanvas-body">
        
        {{-- Logo Section (Top) --}}
        <div class="mb-4 text-center">
            @if($viewDepartment->logo_path)
                <div class="p-3 mb-2 border rounded" style="background: #f8f9fa;">
                    <img src="{{ asset('storage/' . $viewDepartment->logo_path) }}" 
                         alt="{{ $viewDepartment->department }}"
                         class="img-fluid"
                         style="max-height: 120px; max-width: 100%; object-fit: contain;">
                </div>
            @else
                <div class="p-4 mb-2 border rounded d-flex flex-column align-items-center justify-content-center" 
                     style="background: #f8f9fa; min-height: 120px;">
                    <i class="mb-2 ti ti-building-factory-2 text-muted" style="font-size: 48px;"></i>
                    <p class="mb-0 text-muted small">No logo uploaded</p>
                </div>
            @endif
        </div>

        {{-- Basic Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Basic Information</h6>

            {{-- Department Name --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Department:</label>
                <p class="mb-0"><strong>{{ $viewDepartment->department }}</strong></p>
            </div>

            {{-- Short Name --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Short Name:</label>
                <p class="mb-0">{{ $viewDepartment->short_name ?? 'N/A' }}</p>
            </div>

            {{-- Prefix --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Prefix:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-primary font-14">{{ $viewDepartment->prefix }}</span>
                </p>
            </div>

            {{-- Form Name --}}
            @if($viewDepartment->form_name)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Form Name:</label>
                    <p class="mb-0">{{ $viewDepartment->form_name }}</p>
                </div>
            @endif

            {{-- Notes --}}
            @if($viewDepartment->notes)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Notes:</label>
                    <p class="mb-0">{{ $viewDepartment->notes }}</p>
                </div>
            @endif

            {{-- Status --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Status:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-{{ $viewDepartment->is_active ? 'success' : 'danger' }}">
                        {{ $viewDepartment->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Usage Statistics Section --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Usage Statistics</h6>

            {{-- Users Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Assigned Users:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        {{ $viewDepartment->users_count ?? 0 }} users
                    </span>
                </p>
            </div>

            {{-- Tickets Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Tickets Created:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-success font-14">
                        {{ $viewDepartment->tickets_count ?? 0 }} tickets
                    </span>
                </p>
            </div>

            {{-- Clients Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Associated Clients:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-warning font-14">
                        {{ $viewDepartment->clients_count ?? 0 }} clients
                    </span>
                </p>
            </div>

            {{-- Service Types Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Service Types:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-secondary font-14">
                        {{ $viewDepartment->service_types_count ?? 0 }} services
                    </span>
                </p>
            </div>
        </div>

        {{-- Audit Information Section --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Audit Information</h6>

            {{-- Created By --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Created By:</label>
                <p class="mb-0">{{ $viewDepartment->creator->name ?? 'N/A' }}</p>
                <small class="text-muted">{{ $viewDepartment->created_at->format('d M, Y h:i A') }}</small>
            </div>

            {{-- Updated By --}}
            @if ($viewDepartment->updated_at != $viewDepartment->created_at)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0">{{ $viewDepartment->updater->name ?? 'N/A' }}</p>
                    <small class="text-muted">{{ $viewDepartment->updated_at->format('d M, Y h:i A') }}</small>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="gap-2 d-grid">
            {{-- Edit Button --}}
            <button type="button" wire:click="edit({{ $viewDepartment->id }}); $set('showOffcanvas', false)"
                class="btn btn-primary">
                <i class="ti ti-edit me-1"></i> Edit Department
            </button>
            
            {{-- Close Button --}}
            <button type="button" wire:click="closeOffcanvas" class="btn btn-light">
                Close
            </button>
        </div>
    </div>
</div>

{{-- Backdrop --}}
<div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>
