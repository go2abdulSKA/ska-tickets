

<div class="mb-3 shadow-sm card">
    <div class="card-body">
        
        
        <div class="mb-3 d-flex justify-content-between align-items-start">
            <div>
                <a href="javascript:void(0);" 
                   wire:click="view(<?php echo e($ticket->id); ?>)"
                   class="text-decoration-none">
                    <h6 class="mb-1 text-primary"><?php echo e($ticket->ticket_no); ?></h6>
                </a>
                <small class="text-muted"><?php echo e($ticket->ticket_date->format('d M, Y')); ?></small>
            </div>
            <span class="badge <?php echo e($ticket->status->badgeClass()); ?>">
                <?php echo e($ticket->status->label()); ?>

            </span>
        </div>

        
        <div class="mb-3">
            <label class="mb-1 text-muted small d-block">Customer</label>
            <div class="fw-semibold"><?php echo e($ticket->customer_name); ?></div>
            <span class="badge badge-soft-<?php echo e($ticket->client_type->value === 'client' ? 'primary' : 'info'); ?> badge-sm mt-1">
                <?php echo e($ticket->client_type->label()); ?>

            </span>
        </div>

        
        <div class="mb-3 row">
            <!--[if BLOCK]><![endif]--><?php if($ticket->project_code): ?>
                <div class="col-6">
                    <label class="mb-1 text-muted small d-block">Project</label>
                    <span class="badge badge-soft-secondary"><?php echo e($ticket->project_code); ?></span>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <div class="col-<?php echo e($ticket->project_code ? '6' : '12'); ?>">
                <label class="mb-1 text-muted small d-block">Amount</label>
                <strong class="text-primary fs-5">
                    <?php echo e($ticket->currency->symbol()); ?><?php echo e(number_format($ticket->total_amount, 2)); ?>

                </strong>
            </div>
        </div>

        
        <div class="gap-2 d-grid">
            <div class="btn-group">
                <button wire:click="view(<?php echo e($ticket->id); ?>)"
                        class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-eye me-1"></i> View
                </button>
                
                <!--[if BLOCK]><![endif]--><?php if($ticket->canEdit()): ?>
                    <a href="<?php echo e(route('tickets.finance.edit', $ticket->id)); ?>"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-pencil me-1"></i> Edit
                    </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <a href="<?php echo e(route('tickets.finance.duplicate', $ticket->id)); ?>"
                   class="btn btn-sm btn-outline-info">
                    <i class="mdi mdi-content-copy me-1"></i> Duplicate
                </a>

                <!--[if BLOCK]><![endif]--><?php if($ticket->canDelete()): ?>
                    <button wire:click="confirmDelete(<?php echo e($ticket->id); ?>)"
                            class="btn btn-sm btn-outline-danger">
                        <i class="mdi mdi-delete me-1"></i> Delete
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/partials/ticket-card-mobile.blade.php ENDPATH**/ ?>