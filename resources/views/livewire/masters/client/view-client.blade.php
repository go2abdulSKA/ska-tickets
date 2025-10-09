{{-- 
    resources/views/livewire/masters/client/view-client.blade.php
    
    View Client Offcanvas
--}}

<div class="offcanvas offcanvas-end show" style="visibility: visible; width: 400px;" tabindex="-1">
    {{-- Offcanvas Header --}}
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Client Details</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>
    
    {{-- Offcanvas Body --}}
    <div class="offcanvas-body">

        {{-- Basic Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Basic Information</h6>

            {{-- Client Name --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Client Name:</label>
                <p class="mb-0"><strong>{{ $viewClient->client_name }}</strong></p>
            </div>

            {{-- Company Name --}}
            @if($viewClient->company_name)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Company Name:</label>
                    <p class="mb-0">{{ $viewClient->company_name }}</p>
                </div>
            @endif

            {{-- Department --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Department:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-primary font-14">
                        {{ $viewClient->department->department }}
                    </span>
                </p>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Status:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-{{ $viewClient->is_active ? 'success' : 'danger' }}">
                        {{ $viewClient->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Contact Information</h6>

            {{-- Phone --}}
            @if($viewClient->phone)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Phone:</label>
                    <p class="mb-0">
                        <i class="ti ti-phone me-1"></i>
                        <a href="tel:{{ $viewClient->phone }}">{{ $viewClient->phone }}</a>
                    </p>
                </div>
            @endif

            {{-- Email --}}
            @if($viewClient->email)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Email:</label>
                    <p class="mb-0">
                        <i class="ti ti-mail me-1"></i>
                        <a href="mailto:{{ $viewClient->email }}">{{ $viewClient->email }}</a>
                    </p>
                </div>
            @endif

            {{-- Address --}}
            @if($viewClient->address)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Address:</label>
                    <p class="mb-0">
                        <i class="ti ti-map-pin me-1"></i>
                        {{ $viewClient->address }}
                    </p>
                </div>
            @endif

            @if(!$viewClient->phone && !$viewClient->email && !$viewClient->address)
                <p class="text-muted">No contact information available</p>
            @endif
        </div>

        {{-- Usage Statistics --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Usage Statistics</h6>

            {{-- Tickets Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Tickets Created:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        {{ $viewClient->tickets_count ?? 0 }} tickets
                    </span>
                </p>
            </div>
        </div>

        {{-- Audit Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Audit Information</h6>

            {{-- Created By --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Created By:</label>
                <p class="mb-0">{{ $viewClient->creator->name ?? 'N/A' }}</p>
                <small class="text-muted">{{ $viewClient->created_at->format('d M, Y h:i A') }}</small>
            </div>

            {{-- Updated By --}}
            @if ($viewClient->updated_at != $viewClient->created_at)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0">{{ $viewClient->updater->name ?? 'N/A' }}</p>
                    <small class="text-muted">{{ $viewClient->updated_at->format('d M, Y h:i A') }}</small>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="gap-2 d-grid">
            {{-- Edit Button --}}
            <button type="button" wire:click="edit({{ $viewClient->id }}); $set('showOffcanvas', false)"
                class="btn btn-primary">
                <i class="ti ti-edit me-1"></i> Edit Client
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
