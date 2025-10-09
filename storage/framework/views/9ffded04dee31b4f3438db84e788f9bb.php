

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            
            <div class="pb-0 border-0 modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" wire:click="cancelDelete"></button>
            </div>

            
            <div class="p-4 text-center modal-body">

                
                <div class="mb-3">
                    <i class="ti ti-alert-triangle" style="font-size: 5rem; color: #f1556c;"></i>
                </div>

                
                <h4 class="mb-2">Are you sure?</h4>
                <p class="mb-3 text-muted">You are about to delete the following User:</p>

                
                <?php
                    $userToDelete = \App\Models\User::with(['role', 'departments'])->find($deleteId);
                ?>

                
                <!--[if BLOCK]><![endif]--><?php if($userToDelete): ?>
                    <div class="mb-4 alert alert-warning text-start">
                        
                        
                        <div class="gap-3 mb-3 d-flex align-items-center">
                            <!--[if BLOCK]><![endif]--><?php if($userToDelete->profile_photo_path): ?>
                                <img src="<?php echo e($userToDelete->profile_photo_url); ?>" 
                                     alt="<?php echo e($userToDelete->name); ?>"
                                     class="rounded-circle"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center bg-primary fw-bold" 
                                     style="width: 60px; height: 60px; font-size: 24px;">
                                    <?php echo e(strtoupper(substr($userToDelete->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <div>
                                <h6 class="mb-1"><?php echo e($userToDelete->name); ?></h6>
                                <p class="mb-0 text-muted small"><?php echo e($userToDelete->email); ?></p>
                            </div>
                        </div>

                        
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Role:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-info">
                                    <?php echo e($userToDelete->role->display_name ?? 'N/A'); ?>

                                </span>
                            </div>
                        </div>

                        
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Departments:</div>
                            <div class="col-8">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $userToDelete->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <span class="badge badge-soft-primary me-1">
                                        <?php echo e($dept->short_name ?? $dept->department); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <span class="text-muted small">None</span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        
                        <!--[if BLOCK]><![endif]--><?php if($userToDelete->phone): ?>
                            <div class="row">
                                <div class="col-4 fw-bold">Phone:</div>
                                <div class="col-8"><?php echo e($userToDelete->phone); ?></div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <?php
                        $ticketCount = $userToDelete->tickets()->count();
                    ?>

                    
                    <!--[if BLOCK]><![endif]--><?php if($userToDelete->id === auth()->id()): ?>
                        <div class="p-3 alert alert-danger">
                            <h6 class="mb-2"><i class="ti ti-alert-circle me-1"></i> Cannot Delete</h6>
                            <p class="mb-0 small">
                                You cannot delete your own account while logged in.
                            </p>
                        </div>
                        <p class="mb-4 text-muted small">
                            Please contact another administrator to delete this account.
                        </p>
                    
                    <?php elseif($ticketCount > 0): ?>
                        <div class="p-3 alert alert-danger">
                            <h6 class="mb-2"><i class="ti ti-alert-circle me-1"></i> Cannot Delete</h6>
                            <p class="mb-0 small">
                                This user has created <strong><?php echo e($ticketCount); ?> ticket(s)</strong> and cannot be deleted.
                            </p>
                        </div>
                        <p class="mb-4 text-muted small">
                            <strong>Suggestion:</strong> You can deactivate this user instead to prevent login while preserving data.
                        </p>
                    <?php else: ?>
                        <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <div class="gap-2 d-grid">
                    <!--[if BLOCK]><![endif]--><?php if($userToDelete && $userToDelete->id !== auth()->id() && $ticketCount == 0): ?>
                        
                        <button wire:click="delete" class="btn btn-danger btn-lg">
                            <i class="ti ti-trash me-1"></i> Yes, Delete User!
                        </button>
                    <?php else: ?>
                        
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="ti ti-ban me-1"></i> Cannot Delete
                        </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    
                    <button wire:click="cancelDelete" class="btn btn-light">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/user/delete-user.blade.php ENDPATH**/ ?>