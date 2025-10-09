

<div>
    
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Users','page' => 'Masters','subpage' => 'Users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Users','page' => 'Masters','subpage' => 'Users']); ?>
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

    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-all me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-header border-light justify-content-between">

                    
                    <div class="gap-2 d-flex">
                        
                        <div class="app-search">
                            <input wire:model.live.debounce.300ms="search" type="search" class="form-control"
                                placeholder="Search User...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>
                    </div>

                    
                    <div class="gap-1 d-flex align-items-center">
                        
                        <div>
                            <select wire:model.live="perPage" class="my-1 form-select form-control my-md-0">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                        
                        <div class="app-search">
                            <select wire:model.live="roleFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All Roles</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <i data-lucide="shield" class="app-search-icon text-muted"></i>
                        </div>

                        
                        <div class="app-search">
                            <select wire:model.live="departmentFilter" class="my-1 form-select form-control my-md-0">
                                <option value="">All Departments</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->short_name ?? $dept->department); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <i data-lucide="building" class="app-search-icon text-muted"></i>
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
                            <i data-lucide="user-plus" class="fs-sm me-2"></i> Add User
                        </button>
                    </div>
                </div>

                
                <div class="table-responsive">
                    <table class="table mb-0 table-custom table-centered table-hover w-100">
                        
                        <thead class="align-middle bg-opacity-25 bg-light thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                
                                <th style="width: 60px;">Photo</th>

                                
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Name
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                
                                <th wire:click="sortBy('email')" style="cursor: pointer;">
                                    Email
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                
                                <th>Role</th>

                                
                                <th>Departments</th>

                                
                                <th>Phone</th>

                                
                                <th>Tickets</th>

                                
                                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                    Created
                                    <i class="ti ti-arrows-sort fs-xs ms-1"></i>
                                </th>

                                
                                <th>Status</th>

                                
                                <th class="text-center" style="width: 1%;">Actions</th>
                            </tr>
                        </thead>

                        
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($user->profile_photo_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $user->profile_photo_path)); ?>" 
                                                 alt="<?php echo e($user->name); ?>"
                                                 class="rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="text-white rounded-circle d-flex align-items-center justify-content-center bg-primary fw-bold" 
                                                 style="width: 40px; height: 40px; font-size: 16px;">
                                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view(<?php echo e($user->id); ?>)">
                                            <h5 class="mb-0 fs-base"><?php echo e($user->name); ?></h5>
                                        </a>
                                    </td>

                                    
                                    <td>
                                        <span class="text-muted small"><?php echo e($user->email); ?></span>
                                    </td>

                                    
                                    <td>
                                        <span class="badge badge-soft-info fs-xs">
                                            <?php echo e($user->role->display_name ?? 'N/A'); ?>

                                        </span>
                                    </td>

                                    
                                    <td>
                                        <div class="flex-wrap gap-1 d-flex">
                                            <!--[if BLOCK]><![endif]--><?php $__empty_2 = true; $__currentLoopData = $user->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <span class="badge badge-soft-primary fs-xxs">
                                                    <?php echo e($dept->short_name ?? $dept->department); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <span class="text-muted small">None</span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </td>

                                    
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($user->phone): ?>
                                            <span class="small"><?php echo e($user->phone); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">N/A</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    
                                    <td>
                                        <span class="badge badge-soft-success fs-xxs">
                                            <?php echo e($user->tickets_count); ?>

                                        </span>
                                    </td>

                                    
                                    <td>
                                        <?php echo e($user->created_at->format('d M, Y')); ?>

                                        <small class="text-muted d-block"><?php echo e($user->created_at->format('h:i A')); ?></small>
                                    </td>

                                    
                                    <td>
                                        <span class="badge badge-soft-<?php echo e($user->is_active ? 'success' : 'danger'); ?> fs-xxs">
                                            <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </td>

                                    
                                    <td>
                                        <div class="gap-1 d-flex justify-content-center">
                                            
                                            <button wire:click="view(<?php echo e($user->id); ?>)"
                                                wire:loading.attr="disabled"
                                                wire:target="view(<?php echo e($user->id); ?>)"
                                                class="btn btn-default btn-icon btn-sm rounded-circle"
                                                title="View Details"
                                                type="button">
                                                <span wire:loading.remove wire:target="view(<?php echo e($user->id); ?>)">
                                                    <i class="ti ti-eye fs-lg"></i>
                                                </span>
                                                <span wire:loading wire:target="view(<?php echo e($user->id); ?>)">
                                                    <span class="spinner-border spinner-border-sm"></span>
                                                </span>
                                            </button>

                                            
                                            <button wire:click="edit(<?php echo e($user->id); ?>)"
                                                class="btn btn-default btn-icon btn-sm rounded-circle" 
                                                title="Edit"
                                                type="button">
                                                <i class="ti ti-edit fs-lg"></i>
                                            </button>

                                            
                                            
                                                <button wire:click="confirmDelete(<?php echo e($user->id); ?>)"
                                                    class="btn btn-danger btn-icon btn-sm rounded-circle <?php echo e($user->id !== auth()->id() ? '' : 'disabled'); ?>" 
                                                    title="Delete"
                                                    type="button">
                                                    <i class="ti ti-trash fs-lg"></i>
                                                </button>
                                            
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                
                                <tr>
                                    <td colspan="10" class="py-4 text-center">
                                        <i class="ti ti-users" style="font-size: 48px; color: #dee2e6;"></i>
                                        <p class="mt-2 text-muted">No Users found</p>
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
                            <span class="fw-semibold"><?php echo e($users->firstItem() ?? 0); ?></span> to
                            <span class="fw-semibold"><?php echo e($users->lastItem() ?? 0); ?></span> of
                            <span class="fw-semibold"><?php echo e($users->total()); ?></span> Users
                        </div>

                        
                        <div>
                            <?php echo e($users->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        

        
        <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
            <?php echo $__env->make('livewire.masters.user.add-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showOffcanvas && $viewUser): ?>
            <?php echo $__env->make('livewire.masters.user.view-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
            <?php echo $__env->make('livewire.masters.user.delete-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div><!-- end row -->

</div>


<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/user/index.blade.php ENDPATH**/ ?>