{{--
    resources/views/livewire/masters/service-type/view-service-type.blade.php

    View Service Type Offcanvas
    Displays service type details including department
--}}

<div class="offcanvas offcanvas-end show" style="visibility: visible;" tabindex="-1">
    {{-- Offcanvas Header --}}
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Service Type Details</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>

    {{-- Offcanvas Body --}}
    <div class="offcanvas-body">

        {{-- Basic Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Basic Information</h6>

            {{-- Code --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Service Type:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-primary font-14">{{ $viewServiceType->service_type }}</span>
                </p>
            </div>

            {{-- Name (same as service_type) --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Name:</label>
                <p class="mb-0">{{ $viewServiceType->service_type }}</p>
            </div>

            {{-- Department --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Department:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-secondary font-14">
                        {{ $viewServiceType->department->name ?? 'N/A' }}
                    </span>
                </p>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Description:</label>
                <p class="mb-0">{{ $viewServiceType->description ?? 'N/A' }}</p>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Status:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-{{ $viewServiceType->is_active ? 'success' : 'danger' }}">
                        {{ $viewServiceType->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Usage Statistics --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Usage Statistics</h6>

            {{-- Transactions Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Used in Tickets:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        {{ $viewServiceType->tickets_count }} tickets
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
                <p class="mb-0">{{ $viewServiceType->creator->name ?? 'N/A' }}</p>
                <small class="text-muted">{{ $viewServiceType->created_at->format('d M, Y h:i A') }}</small>
            </div>

            {{-- Updated By --}}
            @if ($viewServiceType->updated_at != $viewServiceType->created_at)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0">{{ $viewServiceType->updater->name ?? 'N/A' }}</p>
                    <small class="text-muted">{{ $viewServiceType->updated_at->format('d M, Y h:i A') }}</small>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="gap-2 d-grid">
            {{-- Edit Button --}}
            <button type="button" wire:click="edit({{ $viewServiceType->id }}); $set('showOffcanvas', false)"
                class="btn btn-primary">
                <i class="ti ti-edit me-1"></i> Edit Service Type
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
