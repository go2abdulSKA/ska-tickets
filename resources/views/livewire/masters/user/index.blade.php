{{--
    resources/views/livewire/masters/user/index.blade.php

    User List View with Profile Photos
--}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Users' page='Masters' subpage='Users' />

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

                    {{-- Left Side: Search --}}
                    <div class="gap-2 d-flex">
                        {{-- Search Input --}}
                        <div class="app-search">
                            <input wire:model.live.debounce.300ms="search" type="search" class="form-control"
                                placeholder="Search User...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>
                    </div>

                    {{-- Right Side: Filters and Add Button --}}
                    <div class="gap-1 d-flex align-items-center">
                        {{-- Records Per Page Selector --}}
                        <div>
                            <select wire:model.live="perPage" class="my-1 form-select form-control my-md-0">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                        {{-- Role Filter --}}
                        <div class="app-search">
                            <select wire:model.live="roleFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="shield" class="app-search-icon text-muted"></i>
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
                            <i data-lucide="user-plus" class="fs-sm me-2"></i> Add User
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table mb-0 table-custom table-centered table-hover w-100">
                        {{-- Table Head --}}
                        <thead class="align-middle bg-opacity-25 bg-light thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                {{-- Profile Photo --}}
                                <th style="width: 60px;">Photo</th>

                                {{-- User Name Column (Sortable) --}}
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Name
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Email Column --}}
                                <th wire:click="sortBy('email')" style="cursor: pointer;">
                                    Email
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                {{-- Role Column --}}
                                <th>Role</th>

                                {{-- Departments Column --}}
                                <th>Departments</th>

                                {{-- Contact Column --}}
                                <th>Phone</th>

                                {{-- Usage Column --}}
                                <th>Tickets</th>

                                {{-- Created At Column --}}
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
                            @forelse($users as $user)
                                <tr>
                                    {{-- Profile Photo --}}
                                    <td>
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                                 alt="{{ $user->name }}"
                                                 class="rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="text-white rounded-circle d-flex align-items-center justify-content-center bg-primary fw-bold" 
                                                 style="width: 40px; height: 40px; font-size: 16px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- User Name (Clickable to view) --}}
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $user->id }})">
                                            <h5 class="mb-0 fs-base">{{ $user->name }}</h5>
                                        </a>
                                    </td>

                                    {{-- Email --}}
                                    <td>
                                        <span class="text-muted small">{{ $user->email }}</span>
                                    </td>

                                    {{-- Role --}}
                                    <td>
                                        <span class="badge badge-soft-info fs-xs">
                                            {{ $user->role->display_name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Departments --}}
                                    <td>
                                        <div class="flex-wrap gap-1 d-flex">
                                            @forelse($user->departments as $dept)
                                                <span class="badge badge-soft-primary fs-xxs">
                                                    {{ $dept->short_name ?? $dept->department }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">None</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    {{-- Phone --}}
                                    <td>
                                        @if($user->phone)
                                            <span class="small">{{ $user->phone }}</span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Tickets Count --}}
                                    <td>
                                        <span class="badge badge-soft-success fs-xxs">
                                            {{ $user->tickets_count }}
                                        </span>
                                    </td>

                                    {{-- Created Date --}}
                                    <td>
                                        {{ $user->created_at->format('d M, Y') }}
                                        <small class="text-muted d-block">{{ $user->created_at->format('h:i A') }}</small>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td>
                                        <span class="badge badge-soft-{{ $user->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            {{-- View Button --}}
                                            <button wire:click="view({{ $user->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="view({{ $user->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details"
                                                type="button">
                                                <span wire:loading.remove wire:target="view({{ $user->id }})">
                                                    <i class="ti ti-eye fs-lg"></i>
                                                </span>
                                                <span wire:loading wire:target="view({{ $user->id }})">
                                                    <span class="spinner-border spinner-border-sm"></span>
                                                </span>
                                            </button>

                                            {{-- Edit Button --}}
                                            <button wire:click="edit({{ $user->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" 
                                                title="Edit"
                                                type="button">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>

                                            {{-- Delete Button (not for current user) --}}
                                            {{-- @if($user->id !== auth()->id()) --}}
                                                <button wire:click="confirmDelete({{ $user->id }})"
                                                    class="btn btn-danger btn-icon btn-sm rounded-circle {{ $user->id !== auth()->id() ? '' : 'disabled' }}" 
                                                    title="Delete"
                                                    type="button">
                                                    <i class="ti ti-trash fs-lg"></i>
                                                </button>
                                            {{-- @endif --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="10" class="py-4 text-center">
                                        <i class="ti ti-users" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Users found</p>
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
                            <span class="fw-semibold">{{ $users->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $users->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $users->total() }}</span> Users
                        </div>

                        {{-- Pagination Links --}}
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        {{-- Modals and Offcanvas --}}

        {{-- Add/Edit User Modal --}}
        @if ($showModal)
            @include('livewire.masters.user.add-user')
        @endif

        {{-- View Offcanvas --}}
        @if ($showOffcanvas && $viewUser)
            @include('livewire.masters.user.view-user')
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($showDeleteModal)
            @include('livewire.masters.user.delete-user')
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
