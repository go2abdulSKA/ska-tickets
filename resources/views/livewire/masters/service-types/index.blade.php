{{--
    resources/views/livewire/masters/service-type/index.blade.php

    Service Type List View
    Displays service types with department filter
--}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Service Types' page='Masters' subpage='Service Types' />

    {{-- Flash Messages --}}
    <x-ui.flash-msg />

    {{-- Main Content --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- Card Header with Filters and Actions --}}
                <div class="card-header border-light justify-content-between">

                    {{-- Left Side: Search and Bulk Actions --}}
                    <div class="gap-2 d-flex">
                        {{-- Search Input --}}
                        <div class="app-search">
                            <input wire:model.live.debounce.300ms="search" type="search" class="form-control"
                                placeholder="Search Service Type...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                        {{-- Bulk Delete Button --}}
                        @if (count($selectedItems) > 0)
                            <button wire:click="deleteSelected" class="btn btn-danger">
                                Delete Selected ({{ count($selectedItems) }})
                            </button>
                        @endif
                    </div>

                    {{-- Right Side: Filters and Add Button --}}
                    <div class="gap-1 d-flex align-items-center">

                        {{-- Department Filter --}}
                        <div class="app-search">
                            <select wire:model.live="departmentFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="building-2" class="app-search-icon text-muted"></i>
                        </div>

                        {{-- Records Per Page --}}
                        <div>
                            <select wire:model.live="perPage" class="my-1 form-select form-control my-md-0">
                                <option value="5">5</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="app-search">
                            <select wire:model.live="statusFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <i data-lucide="circle" class="app-search-icon text-muted"></i>
                        </div>

                        {{-- Add Button --}}
                        <button wire:click="openModal" class="btn btn-primary ms-1">
                            <i data-lucide="plus" class="fs-sm me-2"></i> Add Service Type
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table mb-0 table-custom table-centered table-select table-hover w-100">
                        <thead class="align-middle bg-opacity-25 bg-light thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                {{-- Select All --}}
                                <th class="ps-3" style="width: 1%;">
                                    <input wire:model.live="selectAll"
                                        class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                </th>

                                {{-- Code (Sortable) --}}
                                {{-- <th wire:click="sortBy('code')" style="cursor: pointer;">
                                    Code
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th> --}}

                                {{-- Name (Sortable) --}}
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Name
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Department --}}
                                <th>Department</th>

                                {{-- Description --}}
                                <th>Description</th>

                                {{-- Usage --}}
                                <th>Usage</th>

                                {{-- Created (Sortable) --}}
                                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                    Created
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Status --}}
                                <th>Status</th>

                                {{-- Actions --}}
                                <th class="text-center" style="width: 1%;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($serviceTypes as $serviceType)
                                <tr>
                                    {{-- Checkbox --}}
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="{{ $serviceType->id }}"
                                            class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                    </td>

                                    {{-- Code --}}
                                    {{-- <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $serviceType->id }})">
                                            <span class="badge badge-soft-primary fs-xs">{{ $serviceType->service_type }}</span>
                                        </a>
                                    </td> --}}

                                    {{-- Name --}}
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $serviceType->id }})">
                                            <span class="badge badge-soft-primary fs-xs">{{ $serviceType->service_type }}</span>
                                        </a>

                                        {{-- <h5 class="mb-0 fs-base">{{ $serviceType->service_type }}</h5> --}}
                                    </td>

                                    {{-- Department --}}
                                    <td>
                                        <span class="badge badge-soft-secondary fs-xxs">
                                            {{ $serviceType->department->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Description --}}
                                    <td>
                                        <span class="text-muted">{{ Str::limit($serviceType->description ?? 'N/A', 50) }}</span>
                                    </td>

                                    {{-- Usage --}}
                                    <td>
                                        <span class="badge badge-soft-info fs-xxs">
                                            {{ $serviceType->tickets_count }} tickets
                                        </span>
                                    </td>

                                    {{-- Created Date --}}
                                    <td>
                                        {{ $serviceType->created_at->format('d M, Y') }}
                                        <small class="text-muted">{{ $serviceType->created_at->format('h:i A') }}</small>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $serviceType->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $serviceType->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            <button wire:click="view({{ $serviceType->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details">
                                                <i class="ti ti-eye fs-lg"></i>
                                            </button>
                                            <button wire:click="edit({{ $serviceType->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" title="Edit">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $serviceType->id }})"
                                                class="btn btn-danger btn-icon btn-sm rounded-circle" title="Delete">
                                                <i class="ti ti-trash fs-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="9" class="py-4 text-center">
                                        <i class="ti ti-clipboard-list" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Service Types found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="border-0 card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing
                            <span class="fw-semibold">{{ $serviceTypes->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $serviceTypes->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $serviceTypes->total() }}</span> Service Types
                        </div>
                        <div>
                            {{ $serviceTypes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @if ($showModal)
        @include('livewire.masters.service-types.add-service-type')
    @endif

    @if ($showOffcanvas && $viewServiceType)
        @include('livewire.masters.service-types.view-service-type')
    @endif

    @if ($showDeleteModal)
        @include('livewire.masters.service-types.delete-service-type')
    @endif

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            window.addEventListener('openModal', event => {
                const modalId = event.detail[0].modalId || event.detail.modalId;
                const modalEl = document.getElementById(modalId);
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            });

            window.addEventListener('closeModal', event => {
                const modalId = event.detail[0].modalId || event.detail.modalId;
                const modalEl = document.getElementById(modalId);
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });

        document.addEventListener('livewire:navigated', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        Livewire.hook('morph.updated', ({el, component}) => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
