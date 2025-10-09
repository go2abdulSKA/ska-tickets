

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
                <p class="mb-3 text-muted">You are about to delete the following Department:</p>

                
                <?php
                    $departmentToDelete = \App\Models\Department::find($deleteId);
                ?>

                
                <!--[if BLOCK]><![endif]--><?php if($departmentToDelete): ?>
                    <div class="mb-4 alert alert-warning text-start">
                        
                        
                        <div class="gap-3 mb-3 d-flex align-items-start">
                            
                            <!--[if BLOCK]><![endif]--><?php if($departmentToDelete->logo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $departmentToDelete->logo_path)); ?>" 
                                     alt="<?php echo e($departmentToDelete->department); ?>"
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: contain;">
                            <?php else: ?>
                                <div class="bg-white border rounded d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="ti ti-building-factory-2 text-muted" style="font-size: 28px;"></i>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo e($departmentToDelete->department); ?></h6>
                                <!--[if BLOCK]><![endif]--><?php if($departmentToDelete->short_name): ?>
                                    <p class="mb-1 text-muted small">
                                        <strong>Short Name:</strong> <?php echo e($departmentToDelete->short_name); ?>

                                    </p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <p class="mb-0 text-muted small">
                                    <strong>Prefix:</strong> 
                                    <span class="badge badge-soft-primary"><?php echo e($departmentToDelete->prefix); ?></span>
                                </p>
                            </div>
                        </div>

                        
                        <?php
                            // Check if department is being used
                            $usageCount = 0;
                            $usageDetails = [];
                            
                            // Check users
                            $userCount = $departmentToDelete->users()->count();
                            if($userCount > 0) {
                                $usageCount += $userCount;
                                $usageDetails[] = "$userCount user(s)";
                            }
                            
                            // Check tickets
                            $ticketCount = $departmentToDelete->tickets()->count();
                            if($ticketCount > 0) {
                                $usageCount += $ticketCount;
                                $usageDetails[] = "$ticketCount ticket(s)";
                            }
                            
                            // Check clients
                            $clientCount = $departmentToDelete->clients()->count();
                            if($clientCount > 0) {
                                $usageCount += $clientCount;
                                $usageDetails[] = "$clientCount client(s)";
                            }

                            // Check service types
                            $serviceTypeCount = $departmentToDelete->serviceTypes()->count();
                            if($serviceTypeCount > 0) {
                                $usageCount += $serviceTypeCount;
                                $usageDetails[] = "$serviceTypeCount service type(s)";
                            }
                        ?>

                        
                        <!--[if BLOCK]><![endif]--><?php if($usageCount > 0): ?>
                            <div class="p-3 mt-3 alert alert-danger">
                                <h6 class="mb-2"><i class="ti ti-alert-circle me-1"></i> Cannot Delete</h6>
                                <p class="mb-2 small">
                                    This department is currently being used in the system:
                                </p>
                                <ul class="mb-0 ps-3 small">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $usageDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($detail); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </ul>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <!--[if BLOCK]><![endif]--><?php if($usageCount == 0): ?>
                        <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>
                    <?php else: ?>
                        <p class="mb-4 text-muted small">
                            <strong>Suggestion:</strong> Instead of deleting, you can deactivate this department to prevent future use while preserving existing data.
                        </p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <div class="gap-2 d-grid">
                    <!--[if BLOCK]><![endif]--><?php if($departmentToDelete && $usageCount == 0): ?>
                        
                        <button wire:click="delete" class="btn btn-danger btn-lg">
                            <i class="ti ti-trash me-1"></i> Yes, Delete It!
                        </button>
                    <?php else: ?>
                        
                        <button wire:click="$set('showDeleteModal', false)" class="btn btn-warning btn-lg" disabled>
                            <i class="ti ti-ban me-1"></i> Cannot Delete (In Use)
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
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/department/delete-department.blade.php ENDPATH**/ ?>