

<div>
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Unit of Measurement (UOM)','page' => 'Masters','subpage' => 'UOM']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Unit of Measurement (UOM)','page' => 'Masters','subpage' => 'UOM']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginalf2e394a2ecd19198970344c76e4108ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2e394a2ecd19198970344c76e4108ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.flash-msg','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.flash-msg'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2e394a2ecd19198970344c76e4108ce)): ?>
<?php $attributes = $__attributesOriginalf2e394a2ecd19198970344c76e4108ce; ?>
<?php unset($__attributesOriginalf2e394a2ecd19198970344c76e4108ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2e394a2ecd19198970344c76e4108ce)): ?>
<?php $component = $__componentOriginalf2e394a2ecd19198970344c76e4108ce; ?>
<?php unset($__componentOriginalf2e394a2ecd19198970344c76e4108ce); ?>
<?php endif; ?>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-light justify-content-between">

                    <div class="gap-2 d-flex">
                        <div class="app-search">
                            <input wire:model.live.debounce.300ms="search" type="search" class="form-control"
                                placeholder="Search UOM...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                        <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                            <button wire:click="deleteSelected" class="btn btn-danger">
                                Delete Selected (<?php echo e(count($selectedItems)); ?>)
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="gap-1 d-flex align-items-center">

                        <!-- Records Per Page -->
                        <div>
                            <select wire:model.live="perPage" class="my-1 form-select form-control my-md-0">
                                <option value="5">5</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="app-search">
                            <select wire:model.live="statusFilter" class="my-1 form-select form-control my-md-0">
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

                <div class="table-responsive" wire:loading.class="opacity-50"
                    wire:target="search, perPage, statusFilter, sortBy, sortDirection, selectAll, selectedItems">
                    
                    <table class="table mb-0 table-custom table-centered table-select table-hover w-100">
                        <thead class="align-middle bg-opacity-25 bg-light thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th class="ps-3" style="width: 1%;">
                                    <input wire:model.live="selectAll"
                                        class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
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
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="<?php echo e($uom->id); ?>"
                                            class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view(<?php echo e($uom->id); ?>)">
                                            <span class="badge badge-soft-primary fs-xs"><?php echo e($uom->code); ?></span>
                                        </a>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 fs-base"><?php echo e($uom->name); ?></h5>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?php echo e(Str::limit($uom->description ?? 'N/A', 50)); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-info fs-xxs">
                                            <?php echo e($uom->ticket_transactions_count); ?> transactions
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo e($uom->created_at->format('d M, Y')); ?>

                                        <small class="text-muted"><?php echo e($uom->created_at->format('h:i A')); ?></small>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-soft-<?php echo e($uom->is_active ? 'success' : 'danger'); ?> fs-xxs">
                                            <?php echo e($uom->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            <button wire:click="view(<?php echo e($uom->id); ?>)"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details">
                                                <i class="ti ti-eye fs-lg"></i>
                                            </button>
                                            <button wire:click="edit(<?php echo e($uom->id); ?>)"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" title="Edit">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>
                                            <button wire:click="confirmDelete(<?php echo e($uom->id); ?>)"
                                                class="btn btn-danger btn-icon btn-sm rounded-circle" title="Delete">
                                                <i class="ti ti-trash fs-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="py-4 text-center">
                                        <i class="ti ti-package" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No UOM found</p>
                                    </td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                <div class="border-0 card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing
                            <span class="fw-semibold"><?php echo e($uoms->firstItem() ?? 0); ?></span> to
                            <span class="fw-semibold"><?php echo e($uoms->lastItem() ?? 0); ?></span> of
                            <span class="fw-semibold"><?php echo e($uoms->total()); ?></span> UOMs
                        </div>
                        <div>
                            <?php echo e($uoms->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        <!-- Add/Edit UOM Modal -->
        <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
            <?php echo $__env->make('livewire.masters.uom.add-uom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- View Offcanvas -->
        <!--[if BLOCK]><![endif]--><?php if($showOffcanvas && $viewUom): ?>
            <?php echo $__env->make('livewire.masters.uom.view-uom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Delete Confirmation Modal -->

        <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
            <?php echo $__env->make('livewire.masters.uom.delete-uom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>;
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Loading Overlay -->
        

    </div><!-- end row -->

</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/uom/index.blade.php ENDPATH**/ ?>