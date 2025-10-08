{{--
    resources/views/livewire/masters/cost-center/index.blade.php

    Cost Center List View
    Displays cost centers in a table with search, filter, and CRUD operations
--}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Cost Center' page='Masters' subpage='Cost Center' />

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                placeholder="Search Cost Center...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                        {{-- Bulk Delete Button (shows when items selected) --}}
                        @if (count($selectedItems) > 0)
                            <button wire:click="deleteSelected" class="btn btn-danger">
                                Delete Selected ({{ count($selectedItems) }})
                            </button>
                        @endif
                    </div>

                    {{-- Right Side: Filters and Add Button --}}
                    <div class="gap-1 d-flex align-items-center">
                        {{-- Records Per Page Selector --}}
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

                        {{-- Add New Button --}}
                        <button wire:click="openModal" class="btn btn-primary ms-1">
                            <i data-lucide="plus" class="fs-sm me-2"></i> Add Cost Center
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table mb-0 table-custom table-centered table-select table-hover w-100">
                        {{-- Table Head --}}
                        <thead class="align-middle bg-opacity-25 bg-light thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                {{-- Select All Checkbox --}}
                                <th class="ps-3" style="width: 1%;">
                                    <input wire:model.live="selectAll"
                                        class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                </th>

                                {{-- Code Column (Sortable) --}}
                                <th wire:click="sortBy('code')" style="cursor: pointer;">
                                    Code
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Name Column (Sortable) --}}
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Name
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Description Column --}}
                                <th>Description</th>

                                {{-- Usage Column --}}
                                <th>Usage</th>

                                {{-- Created At Column (Sortable) --}}
                                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                    Created
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Status Column --}}
                                <th>Status</th>

                                {{-- Actions Column --}}
                                <th class="text-center" style="width: 1%;">Actions</th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody>
                            @forelse($costCenters as $costCenter)
                                <tr>
                                    {{-- Checkbox --}}
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="{{ $costCenter->id }}"
                                            class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                    </td>

                                    {{-- Code (Clickable to view) --}}
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $costCenter->id }})">
                                            <span class="badge badge-soft-primary fs-xs">{{ $costCenter->code }}</span>
                                        </a>
                                    </td>

                                    {{-- Name --}}
                                    <td>
                                        <h5 class="mb-0 fs-base">{{ $costCenter->name }}</h5>
                                    </td>

                                    {{-- Description --}}
                                    <td>
                                        <span class="text-muted">{{ Str::limit($costCenter->description ?? 'N/A', 50) }}</span>
                                    </td>

                                    {{-- Usage Count --}}
                                    <td>
                                        <span class="badge badge-soft-info fs-xxs">
                                            {{ $costCenter->tickets_count }} tickets
                                        </span>
                                    </td>

                                    {{-- Created Date --}}
                                    <td>
                                        {{ $costCenter->created_at->format('d M, Y') }}
                                        <small class="text-muted">{{ $costCenter->created_at->format('h:i A') }}</small>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $costCenter->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $costCenter->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            {{-- View Button --}}
                                            <button wire:click="view({{ $costCenter->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details">
                                                <i class="ti ti-eye fs-lg"></i>
                                            </button>

                                            {{-- Edit Button --}}
                                            <button wire:click="edit({{ $costCenter->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" title="Edit">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button wire:click="confirmDelete({{ $costCenter->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" title="Delete">
                                                <i class="ti ti-trash fs-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="8" class="py-4 text-center">
                                        <i class="ti ti-building-factory-2" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Cost Centers found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Card Footer with Pagination --}}
                <div class="border-0 card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Pagination Info --}}
                        <div class="text-muted">
                            Showing
                            <span class="fw-semibold">{{ $costCenters->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $costCenters->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $costCenters->total() }}</span> Cost Centers
                        </div>

                        {{-- Pagination Links --}}
                        <div>
                            {{ $costCenters->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        {{-- Modals and Offcanvas --}}

        {{-- Add/Edit Cost Center Modal --}}
        @if ($showModal)
            @include('livewire.masters.cost-center.add-cost-center')
        @endif

        {{-- View Offcanvas --}}
        @if ($showOffcanvas && $viewCostCenter)
            @include('livewire.masters.cost-center.view-cost-center')
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($showDeleteModal)
            @include('livewire.masters.cost-center.delete-cost-center')
        @endif

    </div><!-- end row -->

</div>

{{-- Scripts --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons on page load
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Handle modal open event
            window.addEventListener('openModal', event => {
                const modalId = event.detail[0].modalId || event.detail.modalId;
                const modalEl = document.getElementById(modalId);
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            });

            // Handle modal close event
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

        // Reinitialize Lucide icons after Livewire navigation
        document.addEventListener('livewire:navigated', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Reinitialize Lucide icons after Livewire morph updates
        Livewire.hook('morph.updated', ({el, component}) => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
