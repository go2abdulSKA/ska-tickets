

<div class="modal fade show" 
     style="display: block; background: rgba(0,0,0,0.5);" 
     tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            
            
            <div class="text-white modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="mdi mdi-account-plus me-2"></i>
                    Quick Add Client
                </h5>
                <button type="button" 
                        class="btn-close btn-close-white" 
                        wire:click="closeModal"></button>
            </div>

            
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    
                    
                    <!--[if BLOCK]><![endif]--><?php if($department): ?>
                        <div class="mb-3 alert alert-info">
                            <i class="mdi mdi-information-outline me-1"></i>
                            Adding client to: <strong><?php echo e($department->department); ?></strong>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <div class="row g-3">
                        
                        
                        <div class="col-md-6">
                            <label for="quick_client_name" class="form-label">
                                Client Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   wire:model.blur="client_name" 
                                   class="form-control <?php $__errorArgs = ['client_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="quick_client_name"
                                   placeholder="e.g., John Doe"
                                   maxlength="100"
                                   autofocus>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['client_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="col-md-6">
                            <label for="quick_company_name" class="form-label">
                                Company Name
                            </label>
                            <input type="text" 
                                   wire:model.blur="company_name" 
                                   class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="quick_company_name"
                                   placeholder="e.g., Acme Corporation"
                                   maxlength="100">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="col-md-6">
                            <label for="quick_phone" class="form-label">
                                Phone Number
                            </label>
                            <input type="tel" 
                                   wire:model.blur="phone" 
                                   class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="quick_phone"
                                   placeholder="e.g., +252 61 234 5678"
                                   maxlength="20">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="col-md-6">
                            <label for="quick_email" class="form-label">
                                Email Address
                            </label>
                            <input type="email" 
                                   wire:model.blur="email" 
                                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="quick_email"
                                   placeholder="e.g., client@example.com"
                                   maxlength="100">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="col-12">
                            <label for="quick_address" class="form-label">
                                Address
                            </label>
                            <textarea 
                                wire:model.blur="address" 
                                class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="quick_address"
                                rows="3"
                                placeholder="Enter full address..."
                                maxlength="255"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div class="col-12">
                            <div class="form-check">
                                <input wire:model="is_active" 
                                       type="checkbox" 
                                       class="form-check-input" 
                                       id="quick_is_active">
                                <label class="form-check-label" for="quick_is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-light" 
                            wire:click="closeModal">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="btn btn-primary" 
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="mdi mdi-check me-1"></i> Save Client
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal-backdrop fade show"></div>


<?php $__env->startPush('scripts'); ?>
<script>
    // Close modal on event
    Livewire.on('close-quick-add-client', () => {
        // Add any cleanup if needed
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/masters/client/quick-add-client.blade.php ENDPATH**/ ?>