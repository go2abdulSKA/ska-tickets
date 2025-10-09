

<div>
    
    
    <div class="mb-4 alert alert-info">
        <i class="mdi mdi-check-circle-outline me-2"></i>
        <strong>Review Your Ticket</strong> - Please review all information before saving
    </div>

    
    <div class="mb-3 card">
        <div class="card-header bg-light">
            <h6 class="mb-0">Header Information</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Ticket Number:</strong>
                        <span class="badge badge-soft-primary"><?php echo e($previewTicketNumber); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Date:</strong>
                        <span><?php echo e(\Carbon\Carbon::parse($ticket_date)->format('d M, Y')); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Department:</strong>
                        <span><?php echo e($departments->find($department_id)?->department ?? 'N/A'); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Currency:</strong>
                        <span><?php echo e(\App\Enums\Currency::from($currency)->fullName()); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Customer Type:</strong>
                        <span class="badge badge-soft-<?php echo e($client_type === 'client' ? 'primary' : 'info'); ?>">
                            <?php echo e(\App\Enums\ClientType::from($client_type)->label()); ?>

                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Customer:</strong>
                        <span>
                            <!--[if BLOCK]><![endif]--><?php if($client_type === 'client'): ?>
                                <?php echo e($clients->find($client_id)?->full_name ?? 'N/A'); ?>

                            <?php else: ?>
                                <?php echo e($costCenters->find($cost_center_id)?->full_name ?? 'N/A'); ?>

                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </span>
                    </div>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($service_type_id): ?>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Service Type:</strong>
                            <span><?php echo e($serviceTypes->find($service_type_id)?->service_type ?? 'N/A'); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($project_code): ?>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Project Code:</strong>
                            <span><?php echo e($project_code); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($contract_no): ?>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Contract No:</strong>
                            <span><?php echo e($contract_no); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($service_location): ?>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Service Location:</strong>
                            <span><?php echo e($service_location); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Payment Type:</strong>
                        <span><?php echo e(\App\Enums\PaymentType::from($payment_type)->label()); ?></span>
                    </div>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($payment_terms): ?>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Payment Terms:</strong>
                            <span><?php echo e($payment_terms); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($ref_no): ?>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Reference No:</strong>
                            <span><?php echo e($ref_no); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div class="mt-3">
                <button type="button" 
                        wire:click="goToStep(1)" 
                        class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-pencil me-1"></i> Edit Header Info
                </button>
            </div>
        </div>
    </div>

    
    <div class="mb-3 card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Line Items (<?php echo e(count($transactions)); ?>)</h6>
            <button type="button" 
                    wire:click="goToStep(2)" 
                    class="btn btn-sm btn-outline-primary">
                <i class="mdi mdi-pencil me-1"></i> Edit Line Items
            </button>
        </div>
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Description</th>
                            <th class="text-end" style="width: 100px;">Qty</th>
                            <th style="width: 80px;">UOM</th>
                            <th class="text-end" style="width: 120px;">Unit Cost</th>
                            <th class="text-end" style="width: 120px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($item['sr_no']); ?></td>
                                <td><?php echo e($item['description']); ?></td>
                                <td class="text-end"><?php echo e(number_format($item['qty'], 3)); ?></td>
                                <td>
                                    <?php
                                        $uom = $uoms->find($item['uom_id']);
                                    ?>
                                    <?php echo e($uom?->code ?? 'N/A'); ?>

                                </td>
                                <td class="text-end"><?php echo e(number_format($item['unit_cost'], 2)); ?></td>
                                <td class="text-end fw-bold"><?php echo e(number_format($item['total_cost'], 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-6">
            
            <!--[if BLOCK]><![endif]--><?php if($remarks): ?>
                <div class="mb-3 card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Remarks</h6>
                        <button type="button" 
                                wire:click="goToStep(3)" 
                                class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted"><?php echo e($remarks); ?></p>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if(!empty($attachments) || !empty($existingAttachments)): ?>
                <div class="mb-3 card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            Attachments (<?php echo e(count($attachments) + count($existingAttachments)); ?>)
                        </h6>
                        <button type="button" 
                                wire:click="goToStep(3)" 
                                class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 list-unstyled">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2">
                                    <i class="mdi mdi-paperclip text-primary me-1"></i>
                                    <?php echo e($file->getClientOriginalName()); ?>

                                    <span class="text-muted small">(<?php echo e(number_format($file->getSize() / 1024, 2)); ?> KB)</span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $existingAttachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-2">
                                    <i class="<?php echo e($att['icon']); ?> text-info me-1"></i>
                                    <?php echo e($att['original_name']); ?>

                                    <span class="text-muted small">(<?php echo e($att['human_file_size']); ?>)</span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="col-lg-6">
            
            <div class="mb-3 card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Totals Summary</h6>
                    <button type="button" 
                            wire:click="goToStep(3)" 
                            class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-pencil me-1"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span class="fw-bold">
                            <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?><?php echo e(number_format($subtotal, 2)); ?>

                        </span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>VAT (<?php echo e($vat_percentage); ?>%):</span>
                        <span class="fw-bold">
                            <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?><?php echo e(number_format($vat_amount, 2)); ?>

                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">GRAND TOTAL:</span>
                        <span class="fs-4 fw-bold text-primary">
                            <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?><?php echo e(number_format($total_amount, 2)); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="alert alert-success">
        <h6 class="alert-heading">
            <i class="mdi mdi-check-circle me-1"></i> Ready to Save
        </h6>
        <p class="mb-0">
            Click <strong>"Save as Draft"</strong> to save without posting, or 
            <!--[if BLOCK]><![endif]--><?php if(Auth::user()->isAdmin()): ?>
                <strong>"Save & Post"</strong> to post immediately to ERP.
            <?php else: ?>
                submit for approval.
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </p>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/partials/review-section.blade.php ENDPATH**/ ?>