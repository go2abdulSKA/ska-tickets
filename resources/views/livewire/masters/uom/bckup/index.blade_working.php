{{-- resources/views/livewire/masters/uom/index.blade.php --}}
<div>

    <x-admin.page-header title='Unit of Measurement (UOM)' page='Masters' subpage='UOM' />

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
                <div class="card-header border-light justify-content-between">

                    <div class="d-flex gap-2">
                        <div class="app-search">
                            <input wire:model.live.debounce.300ms="search" type="search" class="form-control"
                                placeholder="Search UOM...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                        @if (count($selectedItems) > 0)
                            <button wire:click="deleteSelected" class="btn btn-danger">
                                Delete Selected ({{ count($selectedItems) }})
                            </button>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-1">
                        <!-- Records Per Page -->
                        <div>
                            <select wire:model.live="perPage" class="form-select form-control my-1 my-md-0">
                                <option value="5">5</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="app-search">
                            <select wire:model.live="statusFilter" class="form-select form-control my-1 my-md-0">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <i data-lucide="circle" class="app-search-icon text-muted"></i>
                        </div>

                        <button wire:click="openModal" class="btn btn-primary ms-1">
                            <i data-lucide="plus" class="fs-sm me-2"></i> Add UOM
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-custom table-centered table-select table-hover w-100 mb-0">
                        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th class="ps-3" style="width: 1%;">
                                    <input wire:model.live="selectAll"
                                        class="form-check-input form-check-input-light fs-14 mt-0" type="checkbox">
                                </th>
                                <th wire:click="sortBy('code')" style="cursor: pointer;">
                                    Code
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Name
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>
                                <th>Description</th>
                                <th>Usage</th>
                                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                    Created
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>
                                <th>Status</th>
                                <th class="text-center" style="width: 1%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uoms as $uom)
                                <tr>
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="{{ $uom->id }}"
                                            class="form-check-input form-check-input-light fs-14 mt-0" type="checkbox">
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-primary fs-xs">
                                            <a wire:click="view({{ $uom->id }})" style="cursor: pointer;">
                                                {{ $uom->code }}
                                            </a>
                                        </span>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 fs-base">{{ $uom->name }}</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($uom->description ?? 'N/A', 50) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-info fs-xxs">
                                            {{ $uom->ticket_transactions_count }} transactions
                                        </span>
                                    </td>
                                    <td>
                                        {{ $uom->created_at->format('d M, Y') }}
                                        <small class="text-muted">{{ $uom->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-soft-{{ $uom->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $uom->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button wire:click="view({{ $uom->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details">
                                                <i class="ti ti-eye fs-lg"></i>
                                            </button>
                                            <button wire:click="edit({{ $uom->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" title="Edit">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $uom->id }})"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" title="Delete">
                                                <i class="ti ti-trash fs-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="ti ti-package" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="text-muted mt-2">No UOM found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing
                            <span class="fw-semibold">{{ $uoms->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $uoms->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $uoms->total() }}</span> UOMs
                        </div>
                        <div>
                            {{ $uoms->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        <!-- Add/Edit UOM Modal -->
        @if ($showModal)
            {{-- <div x-ignore> --}}
                <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">{{ $editMode ? 'Edit' : 'Add New' }} UOM</h5>
                                <button type="button" class="btn-close" wire:click="closeModal"></button>
                            </div>

                            <form wire:submit.prevent="save">
                                <div class="modal-body">
                                    <div class="row g-3">

                                        <!-- Code -->
                                        <div class="col-md-6">
                                            <label for="code" class="form-label">Code <span
                                                    class="text-danger">*</span></label>
                                            <input wire:model="code" type="text"
                                                class="form-control @error('code') is-invalid @enderror"
                                                id="code" placeholder="e.g. PCS, KG">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Common: PCS, KG, LTR, HR, DAY
                                            </small>
                                        </div>

                                        <!-- Name -->
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name <span
                                                    class="text-danger">*</span></label>
                                            <input wire:model="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name" placeholder="e.g. Pieces">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="col-md-12">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                                rows="3" placeholder="Optional description"></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input wire:model="is_active" type="checkbox"
                                                    class="form-check-input" id="is_active">
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light"
                                        wire:click="closeModal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save me-1"></i> {{ $editMode ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        @endif
        <!-- View Offcanvas -->
        @if ($showOffcanvas && $viewUom)
            <div class="offcanvas offcanvas-end show" style="visibility: visible;" tabindex="-1">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title">UOM Details</h5>
                    <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="mb-4">
                        <h6 class="text-uppercase bg-light p-2 mt-0 mb-3">Basic Information</h6>

                        <div class="mb-3">
                            <label class="text-muted fw-bold">Code:</label>
                            <p class="mb-0">
                                <span class="badge badge-soft-primary font-14">{{ $viewUom->code }}</span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted fw-bold">Name:</label>
                            <p class="mb-0">{{ $viewUom->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted fw-bold">Description:</label>
                            <p class="mb-0">{{ $viewUom->description ?? 'N/A' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted fw-bold">Status:</label>
                            <p class="mb-0">
                                <span class="badge badge-soft-{{ $viewUom->is_active ? 'success' : 'danger' }}">
                                    {{ $viewUom->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-uppercase bg-light p-2 mt-0 mb-3">Usage Statistics</h6>

                        <div class="mb-3">
                            <label class="text-muted fw-bold">Used in Transactions:</label>
                            <p class="mb-0">
                                <span class="badge badge-soft-info font-14">
                                    {{ $viewUom->ticket_transactions_count }} transactions
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-uppercase bg-light p-2 mt-0 mb-3">Audit Information</h6>

                        <div class="mb-3">
                            <label class="text-muted fw-bold">Created By:</label>
                            <p class="mb-0">{{ $viewUom->creator->name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $viewUom->created_at->format('d M, Y h:i A') }}</small>
                        </div>

                        @if ($viewUom->updated_at != $viewUom->created_at)
                            <div class="mb-3">
                                <label class="text-muted fw-bold">Last Updated By:</label>
                                <p class="mb-0">{{ $viewUom->updater->name ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $viewUom->updated_at->format('d M, Y h:i A') }}</small>
                            </div>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" wire:click="edit({{ $viewUom->id }}); $set('showOffcanvas', false)"
                            class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i> Edit UOM
                        </button> <button type="button" wire:click="closeOffcanvas" class="btn btn-light">
                            Close
                        </button>
                    </div>
                </div>
            </div>
            <div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>
        @endif

        <!-- Delete Confirmation Modal -->

        @if ($showDeleteModal)
            <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <i class="ti ti-alert-triangle" style="font-size: 5rem; color: #f1556c;"></i>
                            </div>
                            <h4 class="mb-2">Are you sure?</h4>
                            <p class="text-muted mb-3">You are about to delete the following UOM:</p>

                            @php
                                $uomToDelete = \App\Models\UOM::find($deleteId);
                            @endphp

                            @if ($uomToDelete)
                                <div class="alert alert-warning mb-4 text-start">
                                    <div class="row mb-2">
                                        <div class="col-4 fw-bold">Code:</div>
                                        <div class="col-8">
                                            <span class="badge badge-soft-primary">{{ $uomToDelete->code }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 fw-bold">Name:</div>
                                        <div class="col-8">{{ $uomToDelete->name }}</div>
                                    </div>
                                    @if ($uomToDelete->description)
                                        <div class="row">
                                            <div class="col-4 fw-bold">Description:</div>
                                            <div class="col-8">{{ Str::limit($uomToDelete->description, 50) }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <p class="text-danger mb-4"><strong>This action cannot be undone!</strong></p>

                            <div class="d-grid gap-2">
                                <button wire:click="delete" class="btn btn-danger btn-lg">
                                    <i class="ti ti-trash me-1"></i> Yes, Delete It!
                                </button>
                                <button wire:click="cancelDelete" class="btn btn-light">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        {{-- <div wire:loading wire:target="save, deleteUom, toggleStatus"
            class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background: rgba(0,0,0,0.3); z-index: 9999;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div> --}}

    </div><!-- end row -->

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Handle modal open/close
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

// document.addEventListener('hidden.bs.modal', function () {
//     document.body.style.overflow = 'auto';
// });

        // Reinitialize Lucide icons after Livewire updates
        document.addEventListener('livewire:navigated', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        Livewire.hook('morph.updated', ({
            el,
            component
        }) => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
