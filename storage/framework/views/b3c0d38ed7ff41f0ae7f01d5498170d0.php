

<div class="offcanvas offcanvas-end show" style="visibility: visible; width: 400px;" tabindex="-1">
    
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Department Details</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>
    
    
    <div class="offcanvas-body">
        
        
        <div class="mb-4 text-center">
            <!--[if BLOCK]><![endif]--><?php if($viewDepartment->logo_path): ?>
                <div class="p-3 mb-2 border rounded" style="background: #f8f9fa;">
                    <img src="<?php echo e(asset('storage/' . $viewDepartment->logo_path)); ?>" 
                         alt="<?php echo e($viewDepartment->department); ?>"
                         class="img-fluid"
                         style="max-height: 120px; max-width: 100%; object-fit: contain;">
                </div>
            <?php else: ?>
                <div class="p-4 mb-2 border rounded d-flex flex-column align-items-center justify-content-center" 
                     style="background: #f8f9fa; min-height: 120px;">
                    <i class="mb-2 ti ti-building-factory-2 text-muted" style="font-size: 48px;"></i>
                    <p class="mb-0 text-muted small">No logo uploaded</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Basic Information</h6>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Department:</label>
                <p class="mb-0"><strong><?php echo e($viewDepartment->department); ?></strong></p>
            </div>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Short Name:</label>
                <p class="mb-0"><?php echo e($viewDepartment->short_name ?? 'N/A'); ?></p>
            </div>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Prefix:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-primary font-14"><?php echo e($viewDepartment->prefix); ?></span>
                </p>
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if($viewDepartment->form_name): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Form Name:</label>
                    <p class="mb-0"><?php echo e($viewDepartment->form_name); ?></p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($viewDepartment->notes): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Notes:</label>
                    <p class="mb-0"><?php echo e($viewDepartment->notes); ?></p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Status:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-<?php echo e($viewDepartment->is_active ? 'success' : 'danger'); ?>">
                        <?php echo e($viewDepartment->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </p>
            </div>
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Usage Statistics</h6>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Assigned Users:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        <?php echo e($viewDepartment->users_count ?? 0); ?> users
                    </span>
                </p>
            </div>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Tickets Created:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-success font-14">
                        <?php echo e($viewDepartment->tickets_count ?? 0); ?> tickets
                    </span>
                </p>
            </div>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Associated Clients:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-warning font-14">
                        <?php echo e($viewDepartment->clients_count ?? 0); ?> clients
                    </span>
                </p>
            </div>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Service Types:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-secondary font-14">
                        <?php echo e($viewDepartment->service_types_count ?? 0); ?> services
                    </span>
                </p>
            </div>
        </div>

        
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Audit Information</h6>

            
            <div class="mb-3">
                <label class="text-muted fw-bold">Created By:</label>
                <p class="mb-0"><?php echo e($viewDepartment->creator->name ?? 'N/A'); ?></p>
                <small class="text-muted"><?php echo e($viewDepartment->created_at->format('d M, Y h:i A')); ?></small>
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if($viewDepartment->updated_at != $viewDepartment->created_at): ?>
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0"><?php echo e($viewDepartment->updater->name ?? 'N/A'); ?></p>
                    <small class="text-muted"><?php echo e($viewDepartment->updated_at->format('d M, Y h:i A')); ?></small>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="gap-2 d-grid">
            
            <button type="button" wire:click="edit(<?php echo e($viewDepartment->id); ?>); $set('showOffcanvas', false)"
                class="btn btn-primary">
                <i class="ti ti-edit me-1"></i> Edit Department
            </button>
            
            
            <button type="button" wire:click="closeOffcanvas" class="btn btn-light">
                Close
            </button>
        </div>
    </div>
</div>


<div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/department/view-department.blade.php ENDPATH**/ ?>