{{--
    resources/views/livewire/masters/department/index.blade.php

    Department List View
    Displays departments in a table with search, filter, and CRUD operations
--}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Department' page='Masters' subpage='Department' />

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
                                placeholder="Search Department...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                        {{-- Bulk Delete Button (shows when items selected) --}}
                        @if (count($selectedItems) > 0)
                            <button wire:click="confirmBulkDelete" class="btn btn-danger">
                                <i class="ti ti-trash me-1"></i> Delete Selected ({{ count($selectedItems) }})
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
                            <i data-lucide="plus" class="fs-sm me-2"></i> Add Department
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

                                {{-- Logo Column --}}
                                <th style="width: 80px;">Logo</th>

                                {{-- Department Column (Sortable) --}}
                                <th wire:click="sortBy('department')" style="cursor: pointer;">
                                    Department
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Short Name Column --}}
                                <th>Short Name</th>

                                {{-- Prefix Column --}}
                                <th>Prefix</th>

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
                            @forelse($departments as $department)
                                <tr>
                                    {{-- Checkbox --}}
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="{{ $department->id }}"
                                            class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                    </td>

                                    {{-- Logo --}}
                                    <td>
                                        @if($department->logo_path)
                                            <img src="{{ asset('storage/' . $department->logo_path) }}" 
                                                 alt="{{ $department->department }}"
                                                 class="rounded"
                                                 style="height: 40px; width: 60px; object-fit: contain;">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center bg-light" 
                                                 style="height: 40px; width: 60px;">
                                                <i class="ti ti-building-factory-2 text-muted"></i>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Department Name (Clickable to view) --}}
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $department->id }})">
                                            <h5 class="mb-0 fs-base">{{ $department->department }}</h5>
                                        </a>
                                    </td>

                                    {{-- Short Name --}}
                                    <td>
                                        <span class="text-muted">{{ $department->short_name ?? 'N/A' }}</span>
                                    </td>

                                    {{-- Prefix --}}
                                    <td>
                                        <span class="badge badge-soft-primary fs-xs">{{ $department->prefix }}</span>
                                    </td>

                                    {{-- Usage Count --}}
                                    <td>
                                        <div class="gap-1 d-flex flex-column">
                                            @if($department->users_count > 0)
                                                <span class="badge badge-soft-info fs-xxs">
                                                    {{ $department->users_count }} users
                                                </span>
                                            @endif
                                            @if($department->tickets_count > 0)
                                                <span class="badge badge-soft-success fs-xxs">
                                                    {{ $department->tickets_count }} tickets
                                                </span>
                                            @endif
                                            @if($department->clients_count > 0)
                                                <span class="badge badge-soft-warning fs-xxs">
                                                    {{ $department->clients_count }} clients
                                                </span>
                                            @endif
                                            @if($department->service_types_count > 0)
                                                <span class="badge badge-soft-secondary fs-xxs">
                                                    {{ $department->service_types_count }} services
                                                </span>
                                            @endif
                                            @if($department->users_count == 0 && $department->tickets_count == 0 && $department->clients_count == 0 && $department->service_types_count == 0)
                                                <span class="text-muted small">Not in use</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Created Date --}}
                                    <td>
                                        {{ $department->created_at->format('d M, Y') }}
                                        <small class="text-muted">{{ $department->created_at->format('h:i A') }}</small>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $department->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $department->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            {{-- View Button --}}
                                            <button wire:click="view({{ $department->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="view({{ $department->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details"
                                                type="button">
                                                <span wire:loading.remove wire:target="view({{ $department->id }})">
                                                    <i class="ti ti-eye fs-lg"></i>
                                                </span>
                                                <span wire:loading wire:target="view({{ $department->id }})">
                                                    <span class="spinner-border spinner-border-sm"></span>
                                                </span>
                                            </button>

                                            {{-- Edit Button --}}
                                            <button wire:click="edit({{ $department->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" 
                                                title="Edit"
                                                type="button">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button wire:click="confirmDelete({{ $department->id }})"
                                                class="btn btn-danger btn-icon btn-sm rounded-circle" 
                                                title="Delete"
                                                type="button">
                                                <i class="ti ti-trash fs-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="9" class="py-4 text-center">
                                        <i class="ti ti-building-factory-2" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Departments found</p>
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
                            <span class="fw-semibold">{{ $departments->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $departments->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $departments->total() }}</span> Departments
                        </div>

                        {{-- Pagination Links --}}
                        <div>
                            {{ $departments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        {{-- Modals and Offcanvas --}}

        {{-- Add/Edit Department Modal --}}
        @if ($showModal)
            @include('livewire.masters.department.add-department')
        @endif

        {{-- View Offcanvas --}}
        @if ($showOffcanvas && $viewDepartment)
            @include('livewire.masters.department.view-department')
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($showDeleteModal)
            @include('livewire.masters.department.delete-department')
        @endif

        {{-- Bulk Delete Confirmation Modal --}}
        @if ($showBulkDeleteModal)
            @include('livewire.masters.department.bulk-delete-department')
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

            // Debug: Log when view button is clicked
            console.log('Department list page loaded');
        });

        // Reinitialize Lucide icons after Livewire navigation
        document.addEventListener('livewire:navigated', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // CRITICAL: Reinitialize icons after ANY Livewire update
        document.addEventListener('livewire:update', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Also handle after morph updates
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', ({el, component}) => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            // Handle after component updates
            Livewire.hook('commit', ({component, commit, respond}) => {
                respond(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            });
        }

        // Listen for Livewire events
        if (typeof Livewire !== 'undefined') {
            Livewire.on('departmentViewed', (data) => {
                console.log('Department view triggered:', data);
            });
        }
    </script>
@endpush
