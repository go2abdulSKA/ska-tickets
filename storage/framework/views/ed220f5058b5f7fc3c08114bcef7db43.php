

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
                <p class="mb-3 text-muted">You are about to delete the following Cost Center:</p>

                
                <?php
                    $costCenterToDelete = \App\Models\CostCenter::find($deleteId);
                ?>

                
                <!--[if BLOCK]><![endif]--><?php if($costCenterToDelete): ?>
                    <div class="mb-4 alert alert-warning text-start">
                        
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Code:</div>
                            <div class="col-8">
                                <span class="badge badge-soft-primary"><?php echo e($costCenterToDelete->code); ?></span>
                            </div>
                        </div>

                        
                        <div class="mb-2 row">
                            <div class="col-4 fw-bold">Name:</div>
                            <div class="col-8"><?php echo e($costCenterToDelete->name); ?></div>
                        </div>

                        
                        <!--[if BLOCK]><![endif]--><?php if($costCenterToDelete->description): ?>
                            <div class="row">
                                <div class="col-4 fw-bold">Description:</div>
                                <div class="col-8"><?php echo e(Str::limit($costCenterToDelete->description, 50)); ?></div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <p class="mb-4 text-danger"><strong>This action cannot be undone!</strong></p>

                
                <div class="gap-2 d-grid">
                    
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
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/cost-center/delete-cost-center.blade.php ENDPATH**/ ?>