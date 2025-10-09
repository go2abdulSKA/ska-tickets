

<div class="row">
    
    
    <div class="col-lg-6">
        
        
        <div class="mb-3 card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="mdi mdi-text-box-outline me-1"></i> Remarks
                </h6>
            </div>
            <div class="card-body">
                <textarea 
                    wire:model.blur="remarks" 
                    class="form-control <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    rows="5"
                    placeholder="Enter any additional notes or remarks..."
                    maxlength="1000"></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                <small class="text-muted">
                    <span x-data="{ count: $wire.entangle('remarks').length }">
                        <span x-text="count"></span>/1000 characters
                    </span>
                </small>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="mdi mdi-paperclip me-1"></i> Attachments
                </h6>
            </div>
            <div class="card-body">
                
                
                <div class="mb-3">
                    <label for="attachments" class="form-label">Upload Files</label>
                    <input type="file" 
                           wire:model="attachments" 
                           class="form-control <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="attachments"
                           multiple
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <small class="mt-1 text-muted d-block">
                        Max 5MB per file. Supported: PDF, Images, Word, Excel
                    </small>
                </div>

                
                <div wire:loading wire:target="attachments" class="mb-3">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: 100%">
                            Uploading...
                        </div>
                    </div>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if(!empty($attachments)): ?>
                    <div class="mb-3">
                        <p class="mb-2 small fw-bold">New Files (<?php echo e(count($attachments)); ?>):</p>
                        <div class="list-group">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="py-2 list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="mdi mdi-file-document-outline me-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <div class="small"><?php echo e($file->getClientOriginalName()); ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                <?php echo e(number_format($file->getSize() / 1024, 2)); ?> KB
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            wire:click="removeAttachment(<?php echo e($index); ?>)"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if(!empty($existingAttachments)): ?>
                    <div>
                        <p class="mb-2 small fw-bold">Existing Files (<?php echo e(count($existingAttachments)); ?>):</p>
                        <div class="list-group">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $existingAttachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="py-2 list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="<?php echo e($attachment['icon']); ?> me-2 text-info"></i>
                                        <div class="flex-grow-1">
                                            <div class="small"><?php echo e($attachment['original_name']); ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                <?php echo e($attachment['human_file_size']); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            wire:click="removeExistingAttachment(<?php echo e($attachment['id']); ?>)"
                                            wire:confirm="Are you sure you want to remove this file?"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <!--[if BLOCK]><![endif]--><?php if(empty($attachments) && empty($existingAttachments)): ?>
                    <div class="py-4 text-center text-muted">
                        <i class="mdi mdi-file-upload-outline" style="font-size: 48px; opacity: 0.3;"></i>
                        <p class="mb-0 small">No files uploaded yet</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            </div>
        </div>

    </div>

    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="mdi mdi-calculator me-1"></i> Totals Calculation
                </h6>
            </div>
            <div class="card-body">

                
                <div class="pb-3 mb-3 d-flex justify-content-between align-items-center border-bottom">
                    <span class="text-muted">Subtotal</span>
                    <span class="fs-5">
                        <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?>

                        <span wire:loading.remove><?php echo e(number_format($subtotal, 2)); ?></span>
                        <span wire:loading wire:target="calculateTotals">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </span>
                </div>

                
                <div class="mb-3">
                    <label for="vat_percentage" class="form-label">
                        VAT Percentage (%) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               wire:model.live="vat_percentage" 
                               class="form-control form-control-lg text-end <?php $__errorArgs = ['vat_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="vat_percentage"
                               step="0.01"
                               min="0"
                               max="100"
                               placeholder="5.00">
                        <span class="input-group-text">%</span>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['vat_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <small class="text-muted">Default: 5%</small>
                </div>

                
                <div class="pb-3 mb-3 d-flex justify-content-between align-items-center border-bottom">
                    <span class="text-muted">
                        VAT Amount 
                        <!--[if BLOCK]><![endif]--><?php if($vat_percentage): ?>
                            (<?php echo e($vat_percentage); ?>%)
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                    <span class="fs-5">
                        <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?>

                        <span wire:loading.remove><?php echo e(number_format($vat_amount, 2)); ?></span>
                        <span wire:loading wire:target="calculateTotals,vat_percentage">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </span>
                </div>

                
                <div class="p-3 rounded d-flex justify-content-between align-items-center bg-primary bg-opacity-10">
                    <span class="fw-bold fs-5">GRAND TOTAL</span>
                    <span class="fw-bold text-primary" style="font-size: 1.75rem;">
                        <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?>

                        <span wire:loading.remove><?php echo e(number_format($total_amount, 2)); ?></span>
                        <span wire:loading wire:target="calculateTotals,vat_percentage">
                            <span class="spinner-border"></span>
                        </span>
                    </span>
                </div>

                
                <div class="mt-3 small text-muted">
                    <p class="mb-1"><strong>Calculation:</strong></p>
                    <ul class="mb-0 ps-3">
                        <li>Subtotal: <?php echo e(number_format($subtotal, 2)); ?></li>
                        <li>VAT (<?php echo e($vat_percentage); ?>%): <?php echo e(number_format($vat_amount, 2)); ?></li>
                        <li>Total: <?php echo e(number_format($total_amount, 2)); ?></li>
                    </ul>
                </div>

            </div>
        </div>

        
        <div class="mt-3 card">
            <div class="py-2 card-body">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-information-outline text-info me-2"></i>
                    <small class="text-muted">
                        All amounts are in <strong><?php echo e(\App\Enums\Currency::from($currency)->fullName()); ?></strong>
                    </small>
                </div>
            </div>
        </div>

    </div>

</div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/partials/totals-section.blade.php ENDPATH**/ ?>