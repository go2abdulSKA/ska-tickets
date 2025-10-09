

<div>
    
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Department','page' => 'Masters','subpage' => 'Department']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Department','page' => 'Masters','subpage' => 'Department']); ?>
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
                                placeholder="Search Department...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                        
                        <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                            <button wire:click="confirmBulkDelete" class="btn btn-danger">
                                <i class="ti ti-trash me-1"></i> Delete Selected (<?php echo e(count($selectedItems)); ?>)
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <div class="gap-1 d-flex align-items-center">
                        
                        <div>
                            <select wire:model.live="perPage" class="my-1 form-select form-control my-md-0">
                                <option value="5">5</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>

                        
                        <div class="app-search">
                            <select wire:model.live="statusFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <i data-lucide="circle" class="app-search-icon text-muted"></i>
                        </div>

                        
                        <button wire:click="openModal" class="btn btn-primary ms-1">
                            <i data-lucide="plus" class="fs-sm me-2"></i> Add Department
                        </button>
                    </div>
                </div>

                
                <div class="table-responsive">
                    <table class="table mb-0 table-custom table-centered table-select table-hover w-100">
                        
                        <thead class="align-middle bg-opacity-25 bg-light thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                
                                <th class="ps-3" style="width: 1%;">
                                    <input wire:model.live="selectAll"
                                        class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                </th>

                                
                                <th style="width: 80px;">Logo</th>

                                
                                <th wire:click="sortBy('department')" style="cursor: pointer;">
                                    Department
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                
                                <th>Short Name</th>

                                
                                <th>Prefix</th>

                                
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
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" value="<?php echo e($department->id); ?>"
                                            class="mt-0 form-check-input form-check-input-light fs-14" type="checkbox">
                                    </td>

                                    
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($department->logo_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $department->logo_path)); ?>" 
                                                 alt="<?php echo e($department->department); ?>"
                                                 class="rounded"
                                                 style="height: 40px; width: 60px; object-fit: contain;">
                                        <?php else: ?>
                                            <div class="rounded d-flex align-items-center justify-content-center bg-light" 
                                                 style="height: 40px; width: 60px;">
                                                <i class="ti ti-building-factory-2 text-muted"></i>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view(<?php echo e($department->id); ?>)">
                                            <h5 class="mb-0 fs-base"><?php echo e($department->department); ?></h5>
                                        </a>
                                    </td>

                                    
                                    <td>
                                        <span class="text-muted"><?php echo e($department->short_name ?? 'N/A'); ?></span>
                                    </td>

                                    
                                    <td>
                                        <span class="badge badge-soft-primary fs-xs"><?php echo e($department->prefix); ?></span>
                                    </td>

                                    
                                    <td>
                                        <div class="gap-1 d-flex flex-column">
                                            <!--[if BLOCK]><![endif]--><?php if($department->users_count > 0): ?>
                                                <span class="badge badge-soft-info fs-xxs">
                                                    <?php echo e($department->users_count); ?> users
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if($department->tickets_count > 0): ?>
                                                <span class="badge badge-soft-success fs-xxs">
                                                    <?php echo e($department->tickets_count); ?> tickets
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if($department->clients_count > 0): ?>
                                                <span class="badge badge-soft-warning fs-xxs">
                                                    <?php echo e($department->clients_count); ?> clients
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if($department->service_types_count > 0): ?>
                                                <span class="badge badge-soft-secondary fs-xxs">
                                                    <?php echo e($department->service_types_count); ?> services
                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if($department->users_count == 0 && $department->tickets_count == 0 && $department->clients_count == 0 && $department->service_types_count == 0): ?>
                                                <span class="text-muted small">Not in use</span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </td>

                                    
                                    <td>
                                        <?php echo e($department->created_at->format('d M, Y')); ?>

                                        <small class="text-muted"><?php echo e($department->created_at->format('h:i A')); ?></small>
                                    </td>

                                    
                                    <td>
                                        <span
                                            class="badge badge-soft-<?php echo e($department->is_active ? 'success' : 'danger'); ?> fs-xxs">
                                            <?php echo e($department->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>

                                    
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            
                                            <button wire:click="view(<?php echo e($department->id); ?>)"
                                                wire:loading.attr="disabled"
                                                wire:target="view(<?php echo e($department->id); ?>)"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details"
                                                type="button">
                                                <span wire:loading.remove wire:target="view(<?php echo e($department->id); ?>)">
                                                    <i class="ti ti-eye fs-lg"></i>
                                                </span>
                                                <span wire:loading wire:target="view(<?php echo e($department->id); ?>)">
                                                    <span class="spinner-border spinner-border-sm"></span>
                                                </span>
                                            </button>

                                            
                                            <button wire:click="edit(<?php echo e($department->id); ?>)"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" 
                                                title="Edit"
                                                type="button">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>

                                            
                                            <button wire:click="confirmDelete(<?php echo e($department->id); ?>)"
                                                class="btn btn-danger btn-icon btn-sm rounded-circle" 
                                                title="Delete"
                                                type="button">
                                                <i class="ti ti-trash fs-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                
                                <tr>
                                    <td colspan="9" class="py-4 text-center">
                                        <i class="ti ti-building-factory-2" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Departments found</p>
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
                            <span class="fw-semibold"><?php echo e($departments->firstItem() ?? 0); ?></span> to
                            <span class="fw-semibold"><?php echo e($departments->lastItem() ?? 0); ?></span> of
                            <span class="fw-semibold"><?php echo e($departments->total()); ?></span> Departments
                        </div>

                        
                        <div>
                            <?php echo e($departments->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        

        
        <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
            <?php echo $__env->make('livewire.masters.department.add-department', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showOffcanvas && $viewDepartment): ?>
            <?php echo $__env->make('livewire.masters.department.view-department', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
            <?php echo $__env->make('livewire.masters.department.delete-department', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showBulkDeleteModal): ?>
            <?php echo $__env->make('livewire.masters.department.bulk-delete-department', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div><!-- end row -->

</div>


<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/department/index.blade.php ENDPATH**/ ?>