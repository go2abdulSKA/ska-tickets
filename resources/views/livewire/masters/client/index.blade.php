{{--
    resources/views/livewire/masters/client/index.blade.php

    Client List View
    Displays clients in a table with search, filter, and CRUD operations
--}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Client' page='Masters' subpage='Client' />

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
                                placeholder="Search Client...">
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

                        {{-- Department Filter --}}
                        <div class="app-search">
                            <select wire:model.live="departmentFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->short_name ?? $dept->department }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="building" class="app-search-icon text-muted"></i>
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
                            <i data-lucide="plus" class="fs-sm me-2"></i> Add Client
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

                                {{-- Client Name Column (Sortable) --}}
                                <th wire:click="sortBy('client_name')" style="cursor: pointer;">
                                    Client Name
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Company Column --}}
                                <th>Company</th>

                                {{-- Department Column --}}
                                <th>Department</th>

                                {{-- Contact Column --}}
                                <th>Contact</th>

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
                            @forelse($clients as $client)
                                <tr>
                                    {{-- Checkbox --}}
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="{{ $client->id }}"
                                            class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                    </td>

                                    {{-- Client Name (Clickable to view) --}}
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $client->id }})">
                                            <h5 class="mb-0 fs-base">{{ $client->client_name }}</h5>
                                        </a>
                                    </td>

                                    {{-- Company Name --}}
                                    <td>
                                        <span class="text-muted">{{ $client->company_name ?? 'N/A' }}</span>
                                    </td>

                                    {{-- Department --}}
                                    <td>
                                        <span class="badge badge-soft-primary fs-xs">
                                            {{ $client->department->short_name ?? $client->department->department }}
                                        </span>
                                    </td>

                                    {{-- Contact --}}
                                    <td>
                                        @if($client->phone)
                                            <div class="small">
                                                <i class="ti ti-phone me-1"></i>{{ $client->phone }}
                                            </div>
                                        @endif
                                        @if($client->email)
                                            <div class="small text-muted">
                                                <i class="ti ti-mail me-1"></i>{{ $client->email }}
                                            </div>
                                        @endif
                                        @if(!$client->phone && !$client->email)
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Usage Count --}}
                                    <td>
                                        <span class="badge badge-soft-info fs-xxs">
                                            {{ $client->tickets_count }} tickets
                                        </span>
                                    </td>

                                    {{-- Created Date --}}
                                    <td>
                                        {{ $client->created_at->format('d M, Y') }}
                                        <small class="text-muted">{{ $client->created_at->format('h:i A') }}</small>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $client->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            {{-- View Button --}}
                                            <button wire:click="view({{ $client->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="view({{ $client->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details"
                                                type="button">
                                                <span wire:loading.remove wire:target="view({{ $client->id }})">
                                                    <i class="ti ti-eye fs-lg"></i>
                                                </span>
                                                <span wire:loading wire:target="view({{ $client->id }})">
                                                    <span class="spinner-border spinner-border-sm"></span>
                                                </span>
                                            </button>

                                            {{-- Edit Button --}}
                                            <button wire:click="edit({{ $client->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" 
                                                title="Edit"
                                                type="button">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button wire:click="confirmDelete({{ $client->id }})"
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
                                        <i class="ti ti-users" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Clients found</p>
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
                            <span class="fw-semibold">{{ $clients->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $clients->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $clients->total() }}</span> Clients
                        </div>

                        {{-- Pagination Links --}}
                        <div>
                            {{ $clients->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        {{-- Modals and Offcanvas --}}

        {{-- Add/Edit Client Modal --}}
        @if ($showModal)
            @include('livewire.masters.client.add-client')
        @endif

        {{-- View Offcanvas --}}
        @if ($showOffcanvas && $viewClient)
            @include('livewire.masters.client.view-client')
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($showDeleteModal)
            @include('livewire.masters.client.delete-client')
        @endif

        {{-- Bulk Delete Confirmation Modal --}}
        @if ($showBulkDeleteModal)
            @include('livewire.masters.client.bulk-delete-client')
        @endif

    </div><!-- end row -->

</div>

{{-- Scripts --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        document.addEventListener('livewire:navigated', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        document.addEventListener('livewire:update', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', ({el, component}) => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            Livewire.hook('commit', ({component, commit, respond}) => {
                respond(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            });
        }
    </script>
@endpush
