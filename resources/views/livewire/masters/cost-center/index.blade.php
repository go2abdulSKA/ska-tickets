{{-- resources/views/livewire/masters/cost-center/index.blade.php --}}
<div>
    {{-- Page Header --}}
    <div class="mb-3 row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="m-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Masters</a></li>
                        <li class="breadcrumb-item active">Cost Centers</li>
                    </ol>
                </div>
                <h4 class="page-title">Cost Centers Management</h4>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- Card Header with Gradient --}}
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-white">
                                <i class="mdi mdi-office-building-marker me-2"></i>Cost Centers
                            </h4>
                            <p class="mb-0 text-white-50 small">Manage your cost center master data</p>
                        </div>
                        <div class="text-end">
                            @if(count($selectedItems) > 0)
                                <button class="btn btn-light me-2" wire:click="deleteSelected">
                                    <i class="mdi mdi-delete-outline me-1"></i>
                                    Delete Selected ({{ count($selectedItems) }})
                                </button>
                            @endif
                            <button class="btn btn-light" wire:click="create">
                                <i class="mdi mdi-plus-circle me-1"></i> Add Cost Center
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Filters Row --}}
                    <div class="mb-3 row">
                        {{-- Search --}}
                        <div class="col-md-4">
                            <div class="position-relative">
                                <input type="text"
                                       class="form-control ps-5"
                                       placeholder="Search cost centers..."
                                       wire:model.live.debounce.300ms="search">
                                <i class="mdi mdi-magnify position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #98a6ad;"></i>
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active Only</option>
                                <option value="inactive">Inactive Only</option>
                            </select>
                        </div>

                        {{-- Per Page --}}
                        <div class="col-md-2">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="5">5 per page</option>
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                            </select>
                        </div>

                        {{-- Results Count --}}
                        <div class="col-md-3 text-end">
                            <p class="mt-2 mb-0 text-muted">
                                Showing {{ $costCenters->firstItem() ?? 0 }} to {{ $costCenters->lastItem() ?? 0 }} of {{ $costCenters->total() }} results
                            </p>
                        </div>
                    </div>

                    {{-- Cost Centers Table --}}
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="selectAll"
                                                   wire:model.live="selectAll">
                                            <label class="form-check-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th style="width: 50px;">#</th>
                                    <th wire:click="sortBy('code')" style="cursor: pointer;">
                                        Code
                                        @if($sortField === 'code')
                                            <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Name
                                        @if($sortField === 'name')
                                            <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th>Description</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 120px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costCenters as $costCenter)
                                    <tr wire:key="costcenter-{{ $costCenter->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       value="{{ $costCenter->id }}"
                                                       wire:model.live="selectedItems"
                                                       id="check-{{ $costCenter->id }}">
                                                <label class="form-check-label" for="check-{{ $costCenter->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $costCenters->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="badge badge-soft-primary font-13">
                                                {{ $costCenter->code }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);"
                                               wire:click="view({{ $costCenter->id }})"
                                               class="text-body fw-semibold">
                                                {{ $costCenter->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $costCenter->description ? Str::limit($costCenter->description, 50) : '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($costCenter->is_active)
                                                <span class="badge badge-soft-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="p-0 btn btn-sm btn-link text-primary me-2"
                                                    wire:click="view({{ $costCenter->id }})"
                                                    title="View">
                                                <i class="mdi mdi-eye font-18"></i>
                                            </button>
                                            <button class="p-0 btn btn-sm btn-link text-info me-2"
                                                    wire:click="edit({{ $costCenter->id }})"
                                                    title="Edit">
                                                <i class="mdi mdi-square-edit-outline font-18"></i>
                                            </button>
                                            <button class="p-0 btn btn-sm btn-link text-danger"
                                                    wire:click="confirmDelete({{ $costCenter->id }})"
                                                    title="Delete">
                                                <i class="mdi mdi-delete-outline font-18"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 text-center">
                                            <div class="text-muted">
                                                <i class="mb-2 mdi mdi-information-outline font-24 d-block"></i>
                                                <p class="mb-0">No cost centers found.</p>
                                                @if($search || $statusFilter)
                                                    <button class="btn btn-sm btn-link" wire:click="$set('search', ''); $set('statusFilter', '')">
                                                        Clear Filters
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($costCenters->hasPages())
                        <div class="mt-3">
                            {{ $costCenters->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div class="modal fade" id="costCenterModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="text-white modal-title">
                        <i class="mdi mdi-{{ $modalMode === 'create' ? 'plus-circle' : 'square-edit-outline' }} me-1"></i>
                        {{ $modalMode === 'create' ? 'Add New Cost Center' : 'Edit Cost Center' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        {{-- Code --}}
                        <div class="mb-3">
                            <label for="code" class="form-label">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('code') is-invalid @enderror"
                                   id="code"
                                   wire:model="code"
                                   placeholder="Enter cost center code (e.g., CC001)"
                                   maxlength="50"
                                   style="text-transform: uppercase;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Will be converted to uppercase</small>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   wire:model="name"
                                   placeholder="Enter cost center name"
                                   maxlength="255">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      wire:model="description"
                                      rows="3"
                                      placeholder="Enter description (optional)"
                                      maxlength="500"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                {{ strlen($description ?? '') }}/500 characters
                            </small>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       wire:model="is_active">
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Inactive cost centers cannot be used in new tickets
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="closeModal">
                        <i class="mdi mdi-close me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="mdi mdi-content-save me-1"></i>
                        {{ $modalMode === 'create' ? 'Create' : 'Update' }} Cost Center
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    @if($showViewModal && $costCenterToView)
    <div class="modal fade show" id="viewModal" tabindex="-1" style="display: block;" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="text-white modal-title">
                        <i class="mdi mdi-eye me-1"></i> Cost Center Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeView"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="mb-1 text-muted small">Code</label>
                            <div>
                                <span class="badge badge-soft-primary font-14">
                                    {{ $costCenterToView->code }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="mb-1 text-muted small">Status</label>
                            <div>
                                @if($costCenterToView->is_active)
                                    <span class="badge badge-soft-success">Active</span>
                                @else
                                    <span class="badge badge-soft-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-12">
                            <label class="mb-1 text-muted small">Name</label>
                            <div class="fw-semibold">{{ $costCenterToView->name }}</div>
                        </div>
                    </div>

                    @if($costCenterToView->description)
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label class="mb-1 text-muted small">Description</label>
                            <div>{{ $costCenterToView->description }}</div>
                        </div>
                    </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="mb-1 text-muted small">Created By</label>
                            <div>{{ $costCenterToView->createdBy->name ?? '—' }}</div>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="mb-1 text-muted small">Created At</label>
                            <div>{{ $costCenterToView->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>

                    @if($costCenterToView->updated_at != $costCenterToView->created_at)
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="mb-1 text-muted small">Updated By</label>
                            <div>{{ $costCenterToView->updatedBy->name ?? '—' }}</div>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="mb-1 text-muted small">Updated At</label>
                            <div>{{ $costCenterToView->updated_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="closeView">
                        <i class="mdi mdi-close me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="editFromView">
                        <i class="mdi mdi-square-edit-outline me-1"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
    <div class="modal fade show" id="deleteModal" tabindex="-1" style="display: block;" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="text-white modal-title">
                        <i class="mdi mdi-alert-circle-outline me-1"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="cancelDelete"></button>
                </div>
                <div class="p-4 text-center modal-body">
                    <div class="mb-3">
                        <i class="mdi mdi-alert-triangle" style="font-size: 5rem; color: #f1556c;"></i>
                    </div>
                    <h4 class="mb-2">Are you sure?</h4>
                    <p class="mb-3 text-muted">You are about to delete the following cost center:</p>

                    @php
                        $costCenterToDeleteData = \App\Models\CostCenter::find($costCenterToDelete);
                    @endphp

                    @if($costCenterToDeleteData)
                    <div class="mb-4 alert alert-warning text-start">
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Code:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-primary">{{ $costCenterToDeleteData->code }}</span>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Name:</div>
                            <div class="col-8">{{ $costCenterToDeleteData->name }}</div>
                        </div>
                        @if($costCenterToDeleteData->description)
                        <div class="row">
                            <div class="col-4 fw-bold">Description:</div>
                            <div class="col-8">{{ Str::limit($costCenterToDeleteData->description, 50) }}</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>

                    <div class="gap-2 d-grid">
                        <button wire:click="delete" class="btn btn-danger btn-lg">
                            <i class="mdi mdi-delete me-1"></i> Yes, Delete It!
                        </button>
                        <button wire:click="cancelDelete" class="btn btn-light">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    {{-- Toast Notification Container --}}
    <div class="top-0 p-3 position-fixed end-0" style="z-index: 11000;">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="mdi mdi-check-circle me-2 text-success" id="toastIcon"></i>
                <strong class="me-auto" id="toastTitle">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Cost center saved successfully.
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toast notifications
    window.addEventListener('showToast', event => {
        const toastEl = document.getElementById('liveToast');
        const toastIcon = document.getElementById('toastIcon');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');

        const toast = new bootstrap.Toast(toastEl);

        // Set icon and title based on type
        if (event.detail.type === 'success') {
            toastIcon.className = 'mdi mdi-check-circle me-2 text-success';
            toastTitle.textContent = 'Success';
        } else if (event.detail.type === 'error') {
            toastIcon.className = 'mdi mdi-alert-circle me-2 text-danger';
            toastTitle.textContent = 'Error';
        } else if (event.detail.type === 'warning') {
            toastIcon.className = 'mdi mdi-alert me-2 text-warning';
            toastTitle.textContent = 'Warning';
        } else {
            toastIcon.className = 'mdi mdi-information me-2 text-info';
            toastTitle.textContent = 'Info';
        }

        toastMessage.textContent = event.detail.message;
        toast.show();
    });

    // Modal controls
    window.addEventListener('openModal', event => {
        const modalId = event.detail.modalId;
        const modalEl = document.getElementById(modalId);
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });

    window.addEventListener('closeModal', event => {
        const modalId = event.detail.modalId;
        const modalEl = document.getElementById(modalId);
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        }
    });

    // Auto-convert code to uppercase
    document.addEventListener('livewire:initialized', () => {
        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });
</script>
@endpush
