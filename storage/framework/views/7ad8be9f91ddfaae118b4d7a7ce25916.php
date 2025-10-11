



<div>
    

    
    

    <div class="mb-4 page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="m-0 fs-xl fw-bold">
                <!--[if BLOCK]><![endif]--><?php if($editMode): ?>
                    Edit Finance Ticket
                <?php elseif($isDuplicate): ?>
                    Duplicate Finance Ticket
                <?php else: ?>
                    Create Finance Ticket
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </h4>
            <p class="mb-0 text-muted">
                
                <!--[if BLOCK]><![endif]--><?php if(!$editMode && !$isDuplicate && $previewTicketNumber): ?>
                    <span class="badge badge-soft-info">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Next Sequential Number: <strong><?php echo e($previewTicketNumber); ?></strong>
                    </span>
                    <br>
                    <small class="text-muted">
                        * Draft tickets get DRAFT-xxx IDs. Sequential numbers assigned when posted.
                    </small>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if($editMode && isset($ticket)): ?>
                    <!--[if BLOCK]><![endif]--><?php if(str_starts_with($ticket->ticket_no ?? '', 'DRAFT-')): ?>
                        <span class="badge badge-soft-warning"><?php echo e($ticket->ticket_no); ?></span>
                        <small class="text-muted">· Will get sequential number when posted</small>
                    <?php else: ?>
                        <span class="badge badge-soft-primary"><?php echo e($ticket->ticket_no ?? ''); ?></span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($lastSaved): ?>
                    <span class="ms-2 text-muted small">
                        <i class="mdi mdi-content-save-outline"></i> Last saved: <?php echo e($lastSaved); ?>

                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </p>
        </div>
        <div>
            <a href="<?php echo e(route('tickets.finance.index')); ?>" class="btn btn-light">
                <i class="mdi mdi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    

    
    <div class="mb-3 card">
        <div class="p-3 card-body">
            <div class="d-flex align-items-center">
                
                <div class="flex-grow-1">
                    <div class="mb-2 d-flex justify-content-between">
                        <!--[if BLOCK]><![endif]--><?php for($i = 1; $i <= $totalSteps; $i++): ?>
                            <div class="text-center" style="flex: 1;">
                                <div class="step-indicator <?php echo e($currentStep >= $i ? 'active' : ''); ?>"
                                    wire:click="goToStep(<?php echo e($i); ?>)" style="cursor: pointer;">
                                    <div
                                        class="step-circle <?php echo e($currentStep >= $i ? 'bg-primary text-white' : 'bg-light text-muted'); ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($currentStep > $i): ?>
                                            <i class="mdi mdi-check"></i>
                                        <?php else: ?>
                                            <?php echo e($i); ?>

                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div
                                        class="step-label small mt-1 <?php echo e($currentStep === $i ? 'text-primary fw-bold' : 'text-muted'); ?>">
                                        <!--[if BLOCK]><![endif]--><?php switch($i):
                                            case (1): ?>
                                                Header Info
                                            <?php break; ?>

                                            <?php case (2): ?>
                                                Line Items
                                            <?php break; ?>

                                            <?php case (3): ?>
                                                Totals & Files
                                            <?php break; ?>

                                            <?php case (4): ?>
                                                Review
                                            <?php break; ?>
                                        <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar"
                            style="width: <?php echo e($progressPercentage); ?>%" aria-valuenow="<?php echo e($progressPercentage); ?>"
                            aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-body">

            
            <!--[if BLOCK]><![endif]--><?php if($currentStep === 1): ?>
                <?php echo $__env->make('livewire.tickets.finance.partials.header-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($currentStep === 2): ?>
                <?php echo $__env->make('livewire.tickets.finance.partials.line-items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($currentStep === 3): ?>
                <?php echo $__env->make('livewire.tickets.finance.partials.totals-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($currentStep === 4): ?>
                <?php echo $__env->make('livewire.tickets.finance.partials.review-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>

        
        <div class="card-footer bg-light border-top">
            <div class="d-flex justify-content-between align-items-center">

                
                <button type="button" wire:click="previousStep" class="btn btn-light"
                    <?php if($currentStep === 1): ?> disabled <?php endif; ?>>
                    <i class="mdi mdi-arrow-left me-1"></i> Previous
                </button>

                
                <div class="d-md-none text-muted small">
                    Step <?php echo e($currentStep); ?> of <?php echo e($totalSteps); ?>

                </div>

                
                <div class="gap-2 d-flex">
                    <!--[if BLOCK]><![endif]--><?php if($currentStep < $totalSteps): ?>
                        
                        <button type="button" wire:click="nextStep" class="btn btn-primary"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="nextStep">
                                Next <i class="mdi mdi-arrow-right ms-1"></i>
                            </span>
                            <span wire:loading wire:target="nextStep">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Validating...
                            </span>
                        </button>
                    <?php else: ?>
                        
                        <button type="button" wire:click="saveDraft" class="btn btn-secondary"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveDraft">
                                <i class="mdi mdi-content-save-outline me-1"></i> Save as Draft
                            </span>
                            <span wire:loading wire:target="saveDraft">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Saving...
                            </span>
                        </button>

                        
                        <!--[if BLOCK]><![endif]--><?php if(Auth::user()->isAdmin()): ?>
                            <button type="button" wire:click="saveAndPost" class="btn btn-success"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveAndPost">
                                    <i class="mdi mdi-check-circle me-1"></i> Save & Post
                                </span>
                                <span wire:loading wire:target="saveAndPost">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Processing...
                                </span>
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($showQuickAddClient): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('masters.client.quick-add-client', ['departmentId' => $department_id]);

$__html = app('livewire')->mount($__name, $__params, 'quick-client-' . $department_id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($showQuickAddUOM): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('masters.uom.quick-add-uom');

$__html = app('livewire')->mount($__name, $__params, 'lw-1703090859-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($showQuickAddServiceType): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('masters.service-types.quick-add-service-type', ['departmentId' => $department_id]);

$__html = app('livewire')->mount($__name, $__params, 'quick-service-' . $department_id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>


<?php $__env->startPush('styles'); ?>
    <style>
        .step-indicator {
            display: inline-block;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .step-indicator.active .step-circle {
            border-color: var(--bs-primary);
            transform: scale(1.1);
        }

        .step-label {
            margin-top: 8px;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 0.875rem;
            }

            .step-label {
                font-size: 0.65rem;
            }
        }
    </style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        function financeTicketForm() {
            return {
                init() {
                    // Auto-save every 30 seconds (debounced)
                    setInterval(() => {
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('autoSaveDraft');
                    }, 30000);

                    // Listen for restore draft confirmation
                    Livewire.on('confirm-restore-draft', (data) => {
                        if (confirm('You have an unsaved draft. Would you like to restore it?')) {
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').dispatch('restore-draft', data.draft);
                        } else {
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').dispatch('discard-draft');
                        }
                    });

                    // Prevent accidental page leave
                    window.addEventListener('beforeunload', (e) => {
                        if (window.Livewire.find('<?php echo e($_instance->getId()); ?>').currentStep > 1) {
                            e.preventDefault();
                            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                            return e.returnValue;
                        }
                    });
                }
            }
        }
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/create.blade.php ENDPATH**/ ?>