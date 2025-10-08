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

                        @if(count($selectedItems) > 0)
                            <button wire:click="deleteSelected" wire:confirm="Are you sure you want to delete selected UOMs?" class="btn btn-danger">
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
                                    <input wire:model.live="selectAll" class="form-check-input form-check-input-light fs-14 mt-0" type="checkbox">
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
                                        <span class="badge badge-soft-primary fs-xs">{{ $uom->code }}</span>
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
                                        <span class="badge badge-soft-{{ $uom->is_active ? 'success' : 'danger' }} fs-xxs">
                                            {{ $uom->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button wire:click="edit({{ $uom->id }})" class="btn btn-default btn-icon btn-sm rounded-circle">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>
                                            <button wire:click="delete({{ $uom->id }})"
                                                    wire:confirm="Are you sure you want to delete this UOM?"
                                                    class="btn btn-default btn-icon btn-sm rounded-circle">
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
        <div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="addUomModalLabel">{{ $editMode ? 'Edit' : 'Add New' }} UOM</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row g-3">

                                <!-- Code -->
                                <div class="col-md-6">
                                    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                    <input wire:model="code" type="text" class="form-control @error('code') is-invalid @enderror"
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
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" placeholder="e.g. Pieces">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror"
                                              id="description" rows="3" placeholder="Optional description"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input wire:model="is_active" type="checkbox" class="form-check-input" id="is_active">
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i> {{ $editMode ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div><!-- end row -->

    <!-- Loading Overlay -->
    {{-- <div wire:loading wire:target="save, delete, deleteSelected" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.3); z-index: 9999;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> --}}

</div>

@script
<script>
    // Initialize Lucide icons when component loads
    document.addEventListener('livewire:initialized', () => {
        lucide.createIcons();
    });

    // Reinitialize icons after Livewire updates
    Livewire.hook('morph.updated', ({ el, component }) => {
        lucide.createIcons();
    });

    // Handle modal open/close
    Livewire.on('openModal', (data) => {
        const modal = new bootstrap.Modal(document.getElementById(data.modalId));
        modal.show();
    });

    Livewire.on('closeModal', (data) => {
        const modalEl = document.getElementById(data.modalId);
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    });
</script>
@endscript
