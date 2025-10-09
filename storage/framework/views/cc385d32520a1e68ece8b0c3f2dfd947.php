





<div class="offcanvas offcanvas-end show" style="visibility: visible; width: 400px;" tabindex="-1">
    
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">User Profile</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>
    
    
    <div class="offcanvas-body">





        
        <div class="mb-4 text-center">
            <!--[if BLOCK]><![endif]--><?php if($viewUser->profile_photo_path): ?>
                
                <img src="<?php echo e(asset('storage/' . $viewUser->profile_photo_path)); ?>"
                     alt="<?php echo e($viewUser->name); ?>"
                     class="border rounded-circle"
                     style="width: 120px; height: 120px; object-fit: cover;">
            <?php else: ?>
                <div class="text-white border rounded-circle d-inline-flex align-items-center justify-content-center bg-primary" 
                     style="width: 120px; height: 120px;">
                    <span style="font-size: 48px; font-weight: bold;">
                        <?php echo e(strtoupper(substr($viewUser->name, 0, 1))); ?>

                    </span>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            
            <h4 class="mt-3 mb-1"><?php echo e($viewUser->name); ?></h4>
            
            
            <p class="mb-2 text-muted">
                <i class="ti ti-mail me-1"></i>
                <a href="mailto:<?php echo e($viewUser->email); ?>"><?php echo e($viewUser->email); ?></a>
            </p>
            
            
            <span class="badge badge-soft-<?php echo e($viewUser->is_active ? 'success' : 'danger'); ?>">
                <?php echo e($viewUser->is_active ? 'Active' : 'Inactive'); ?>

            </span>
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Role & Permissions</h6>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Role:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        <i class="ti ti-shield me-1"></i>
                        <?php echo e($viewUser->role->display_name ?? 'N/A'); ?>

                    </span>
                </p>
            </div>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Departments:</label>
                <div class="flex-wrap gap-1 mt-1 d-flex">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $viewUser->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="badge badge-soft-primary">
                            <?php echo e($dept->short_name ?? $dept->department); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="mb-0 text-muted small">No departments assigned</p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Contact Information</h6>

            
            <!--[if BLOCK]><![endif]--><?php if($viewUser->phone): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Phone:</label>
                    <p class="mb-0">
                        <i class="ti ti-phone me-1"></i>
                        <a href="tel:<?php echo e($viewUser->phone); ?>"><?php echo e($viewUser->phone); ?></a>
                    </p>
                </div>
            <?php else: ?>
                <p class="text-muted small">No phone number provided</p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Activity Statistics</h6>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Tickets Created:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-success font-14">
                        <i class="ti ti-file-text me-1"></i>
                        <?php echo e($viewUser->tickets_count ?? 0); ?> tickets
                    </span>
                </p>
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if($viewUser->two_factor_secret): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Two Factor Auth:</label>
                    <p class="mb-0">
                        <span class="badge badge-soft-success">
                            <i class="ti ti-lock me-1"></i> Enabled
                        </span>
                    </p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Audit Information</h6>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Created By:</label>
                <p class="mb-0"><?php echo e($viewUser->creator->name ?? 'System'); ?></p>
                <small class="text-muted"><?php echo e($viewUser->created_at->format('d M, Y h:i A')); ?></small>
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if($viewUser->updated_at != $viewUser->created_at): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0"><?php echo e($viewUser->updater->name ?? 'System'); ?></p>
                    <small class="text-muted"><?php echo e($viewUser->updated_at->format('d M, Y h:i A')); ?></small>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($viewUser->current_team_id): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Activity:</label>
                    <p class="mb-0">
                        <span class="badge badge-soft-secondary">
                            <?php echo e($viewUser->updated_at->diffForHumans()); ?>

                        </span>
                    </p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="gap-2 d-grid">
            
            <!--[if BLOCK]><![endif]--><?php if($viewUser->id !== auth()->id()): ?>
                <button type="button" wire:click="edit(<?php echo e($viewUser->id); ?>); $set('showOffcanvas', false)"
                    class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit User
                </button>
            <?php else: ?>
                <a href="<?php echo e(route('profile.show')); ?>" class="btn btn-primary">
                    <i class="ti ti-user-cog me-1"></i> Manage My Profile
                </a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            
            <button type="button" wire:click="closeOffcanvas" class="btn btn-light">
                Close
            </button>
        </div>
    </div>
</div>


<div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/user/view-user.blade.php ENDPATH**/ ?>