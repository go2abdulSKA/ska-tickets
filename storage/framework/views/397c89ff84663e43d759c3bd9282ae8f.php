

<div class="row g-3">

    
    <div class="col-md-4">
        <label for="ticket_date" class="form-label">
            Ticket Date <span class="text-danger">*</span>
        </label>
        <input type="date" wire:model.blur="ticket_date" class="form-control <?php $__errorArgs = ['ticket_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            id="ticket_date" max="<?php echo e(date('Y-m-d')); ?>">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ticket_date'];
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

    
    <div class="col-md-4">
        <label for="department_id" class="form-label">
            Department <span class="text-danger">*</span>
        </label>
        <select wire:model.live="department_id" class="form-select <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            id="department_id" <?php if($editMode): ?> disabled <?php endif; ?>>
            <option value="">-- Select Department --</option>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($dept->id); ?>">
                    <?php echo e($dept->department); ?> (<?php echo e($dept->prefix); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['department_id'];
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

    
    <div class="col-md-4">
        <label for="currency" class="form-label">
            Currency <span class="text-danger">*</span>
        </label>
        <select wire:model.live="currency" class="form-select <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="currency">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = \App\Enums\Currency::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($curr->value); ?>">
                    <?php echo e($curr->label()); ?> (<?php echo e($curr->symbol()); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['currency'];
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
        <hr class="my-2">
    </div>
    <div class="col-12">
        <h6 class="mb-0">Customer Information</h6>
        <p class="mb-2 text-muted small">Select whether this ticket is for an external client or internal cost center
        </p>
    </div>

    
    <div class="col-12">
        <div class="btn-group w-100" role="group">
            <input type="radio" wire:model.live="client_type" value="client" class="btn-check" id="client_type_client"
                autocomplete="off">
            <label class="btn btn-outline-primary" for="client_type_client">
                <i class="mdi mdi-account-multiple me-1"></i> External Client
            </label>

            <input type="radio" wire:model.live="client_type" value="cost_center" class="btn-check"
                id="client_type_cost_center" autocomplete="off">
            <label class="btn btn-outline-primary" for="client_type_cost_center">
                <i class="mdi mdi-office-building me-1"></i> Cost Center
            </label>
        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($client_type === 'client'): ?>
        <div class="col-md-6">
            <label for="client_id" class="form-label">
                Client <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <?php
                    $clientOptions = [];
                    if ($department_id && $clients) {
                        $clientOptions = $clients
                            ->map(function ($client) {
                                return [
                                    'value' => $client->id,
                                    'label' =>
                                        $client->client_name .
                                        ($client->company_name ? ' - ' . $client->company_name : ''),
                                ];
                            })
                            ->toArray();
                    }
                ?>

                <?php if (isset($component)) { $__componentOriginala8477b4ecee8eec802e9913415383e3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8477b4ecee8eec802e9913415383e3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.searchable-select','data' => ['id' => 'client_id','wireModel' => 'client_id','options' => $clientOptions,'placeholder' => ''.e(!$department_id ? 'Select Department First' : 'Search clients...').'','disabled' => !$department_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.searchable-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'client_id','wire-model' => 'client_id','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($clientOptions),'placeholder' => ''.e(!$department_id ? 'Select Department First' : 'Search clients...').'','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$department_id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8477b4ecee8eec802e9913415383e3a)): ?>
<?php $attributes = $__attributesOriginala8477b4ecee8eec802e9913415383e3a; ?>
<?php unset($__attributesOriginala8477b4ecee8eec802e9913415383e3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8477b4ecee8eec802e9913415383e3a)): ?>
<?php $component = $__componentOriginala8477b4ecee8eec802e9913415383e3a; ?>
<?php unset($__componentOriginala8477b4ecee8eec802e9913415383e3a); ?>
<?php endif; ?>

                <button type="button" wire:click="openQuickAddClient" class="btn btn-primary" title="Add New Client"
                    <?php if(!$department_id): ?> disabled <?php endif; ?>>
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            <!--[if BLOCK]><![endif]--><?php if(!$department_id): ?>
                <small class="text-muted">Please select a department first</small>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($client_type === 'cost_center'): ?>
        <div class="col-md-6">
            <label for="cost_center_id" class="form-label">
                Cost Center <span class="text-danger">*</span>
            </label>
            <?php
                $costCenterOptions = [];
                if ($costCenters) {
                    $costCenterOptions = $costCenters
                        ->map(function ($cc) {
                            return [
                                'value' => $cc->id,
                                'label' => $cc->code . ' - ' . $cc->name,
                            ];
                        })
                        ->toArray();
                }
            ?>

            <?php if (isset($component)) { $__componentOriginala8477b4ecee8eec802e9913415383e3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8477b4ecee8eec802e9913415383e3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.searchable-select','data' => ['id' => 'cost_center_id','wireModel' => 'cost_center_id','options' => $costCenterOptions,'placeholder' => ''.e(!$department_id ? 'Select Department First' : 'Search cost centers...').'','disabled' => !$department_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.searchable-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'cost_center_id','wire-model' => 'cost_center_id','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($costCenterOptions),'placeholder' => ''.e(!$department_id ? 'Select Department First' : 'Search cost centers...').'','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$department_id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8477b4ecee8eec802e9913415383e3a)): ?>
<?php $attributes = $__attributesOriginala8477b4ecee8eec802e9913415383e3a; ?>
<?php unset($__attributesOriginala8477b4ecee8eec802e9913415383e3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8477b4ecee8eec802e9913415383e3a)): ?>
<?php $component = $__componentOriginala8477b4ecee8eec802e9913415383e3a; ?>
<?php unset($__componentOriginala8477b4ecee8eec802e9913415383e3a); ?>
<?php endif; ?>

            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cost_center_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            <!--[if BLOCK]><![endif]--><?php if(!$department_id): ?>
                <small class="text-muted">Please select a department first</small>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <div class="col-md-6">
        <label for="service_type_id" class="form-label">
            Service Type
        </label>
        <div class="input-group">
            <?php
                $serviceTypeOptions = [];
                if ($department_id && $serviceTypes) {
                    $serviceTypeOptions = $serviceTypes
                        ->map(function ($st) {
                            return [
                                'value' => $st->id,
                                'label' => $st->service_type,
                            ];
                        })
                        ->toArray();
                }
            ?>

            <?php if (isset($component)) { $__componentOriginala8477b4ecee8eec802e9913415383e3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8477b4ecee8eec802e9913415383e3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.searchable-select','data' => ['id' => 'service_type_id','wireModel' => 'service_type_id','options' => $serviceTypeOptions,'placeholder' => ''.e(!$department_id ? 'Select Department First' : 'Search service types...').'','disabled' => !$department_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.searchable-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'service_type_id','wire-model' => 'service_type_id','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($serviceTypeOptions),'placeholder' => ''.e(!$department_id ? 'Select Department First' : 'Search service types...').'','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(!$department_id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8477b4ecee8eec802e9913415383e3a)): ?>
<?php $attributes = $__attributesOriginala8477b4ecee8eec802e9913415383e3a; ?>
<?php unset($__attributesOriginala8477b4ecee8eec802e9913415383e3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8477b4ecee8eec802e9913415383e3a)): ?>
<?php $component = $__componentOriginala8477b4ecee8eec802e9913415383e3a; ?>
<?php unset($__componentOriginala8477b4ecee8eec802e9913415383e3a); ?>
<?php endif; ?>

            <button type="button" wire:click="openQuickAddServiceType" class="btn btn-primary"
                title="Add New Service Type" <?php if(!$department_id): ?> disabled <?php endif; ?>>
                <i class="mdi mdi-plus"></i>
            </button>
        </div>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['service_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        <!--[if BLOCK]><![endif]--><?php if(!$department_id): ?>
            <small class="text-muted">Please select a department first</small>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <div class="col-12">
        <hr class="my-2">
    </div>
    <div class="col-12">
        <h6 class="mb-0">Project Details</h6>
    </div>

    
    <div class="col-md-4">
        <label for="project_code" class="form-label">Project Code</label>
        <input type="text" wire:model.blur="project_code"
            class="form-control <?php $__errorArgs = ['project_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="project_code"
            placeholder="e.g., PROJ-2025-001" maxlength="100">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['project_code'];
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

    
    <div class="col-md-4">
        <label for="contract_no" class="form-label">Contract No</label>
        <input type="text" wire:model.blur="contract_no"
            class="form-control <?php $__errorArgs = ['contract_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contract_no"
            placeholder="e.g., CTR-2025-001" maxlength="100">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['contract_no'];
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

    
    <div class="col-md-4">
        <label for="service_location" class="form-label">Service Location</label>
        <input type="text" wire:model.blur="service_location"
            class="form-control <?php $__errorArgs = ['service_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="service_location"
            placeholder="e.g., Mogadishu, Somalia" maxlength="100">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['service_location'];
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
        <hr class="my-2">
    </div>
    <div class="col-12">
        <h6 class="mb-0">Payment Information</h6>
    </div>

    
    <div class="col-md-4">
        <label for="payment_type" class="form-label">
            Payment Type <span class="text-danger">*</span>
        </label>
        <select wire:model.live="payment_type" class="form-select <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            id="payment_type">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = \App\Enums\PaymentType::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pt->value); ?>"><?php echo e($pt->label()); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['payment_type'];
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

    
    <div class="col-md-4">
        <label for="payment_terms" class="form-label">Payment Terms</label>
        <input type="text" wire:model.blur="payment_terms"
            class="form-control <?php $__errorArgs = ['payment_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="payment_terms"
            placeholder="e.g., Net 30 days" maxlength="100">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['payment_terms'];
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

    
    <div class="col-md-4">
        <label for="ref_no" class="form-label">Reference No</label>
        
        <input type="text" wire:model.blur="ref_no" id="ref_no" placeholder="e.g., PO-2025-001"
            maxlength="100">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ref_no'];
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

</div>







<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/partials/header-form.blade.php ENDPATH**/ ?>