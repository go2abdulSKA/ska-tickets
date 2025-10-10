

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
                <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->department); ?> (<?php echo e($dept->prefix); ?>)</option>
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
                <select wire:model.live="client_id"
                    class="form-select searchable-select <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="client_id"
                    data-placeholder="Search clients..." <?php if(!$department_id): ?> disabled <?php endif; ?>>
                    <option value="">
                        <!--[if BLOCK]><![endif]--><?php if(!$department_id): ?>
                            -- Select Department First --
                        <?php else: ?>
                            -- Search & Select Client --
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </option>
                    <!--[if BLOCK]><![endif]--><?php if($department_id): ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($client->id); ?>">
                                <?php echo e($client->client_name); ?>

                                <!--[if BLOCK]><![endif]--><?php if($client->company_name): ?>
                                    - <?php echo e($client->company_name); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <button type="button" wire:click="openQuickAddClient" class="btn btn-outline-primary"
                    title="Add New Client" <?php if(!$department_id): ?> disabled <?php endif; ?>>
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

    
    <div class="col-md-6">
        <label for="service_type_id" class="form-label">
            Service Type
        </label>
        <div class="input-group">
            <select wire:model.live="service_type_id"
                class="form-select searchable-select <?php $__errorArgs = ['service_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="service_type_id" data-placeholder="Search service types..."
                <?php if(!$department_id): ?> disabled <?php endif; ?>>
                <option value="">
                    <!--[if BLOCK]><![endif]--><?php if(!$department_id): ?>
                        -- Select Department First --
                    <?php else: ?>
                        -- Search & Select Service Type --
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </option>
                <!--[if BLOCK]><![endif]--><?php if($department_id): ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $serviceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st->id); ?>"><?php echo e($st->service_type); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </select>
            <button type="button" wire:click="openQuickAddServiceType" class="btn btn-outline-primary"
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


    
    <!--[if BLOCK]><![endif]--><?php if($client_type === 'cost_center'): ?>
        <div class="col-md-6">
            <label for="cost_center_id" class="form-label">
                Cost Center <span class="text-danger">*</span>
            </label>
            <select wire:model.live="cost_center_id" class="form-select <?php $__errorArgs = ['cost_center_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="cost_center_id">
                <option value="">-- Select Cost Center --</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $costCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cc->id); ?>"><?php echo e($cc->code); ?> - <?php echo e($cc->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cost_center_id'];
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
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


    
    

    
    

    
    


    
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
        <input type="text" wire:model.blur="ref_no" class="form-control <?php $__errorArgs = ['ref_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            id="ref_no" placeholder="e.g., PO-2025-001" maxlength="100">
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

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initSearchableSelects();
    });

    // Reinitialize after Livewire updates
    document.addEventListener('livewire:update', function() {
        initSearchableSelects();
    });

    function initSearchableSelects() {
        // Destroy existing instances first
        document.querySelectorAll('.searchable-select').forEach(function(select) {
            if (select.tomselect) {
                select.tomselect.destroy();
            }
        });

        // Initialize Tom Select on all searchable selects
        document.querySelectorAll('.searchable-select').forEach(function(select) {
            if (!select.disabled) {
                new TomSelect(select, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: select.dataset.placeholder || 'Search...',
                    plugins: ['dropdown_input'],
                    onItemAdd: function(value, item) {
                        // Trigger Livewire update
                        this.wrapper.querySelector('select').dispatchEvent(new Event('change', { bubbles: true }));
                    },
                    onClear: function() {
                        // Trigger Livewire update on clear
                        this.wrapper.querySelector('select').dispatchEvent(new Event('change', { bubbles: true }));
                    },
                    maxOptions: 500, // Show up to 500 options
                    render: {
                        no_results: function(data, escape) {
                            return '<div class="no-results">No results found for "' + escape(data.input) + '"</div>';
                        },
                    }
                });
            }
        });
    }

    // Listen for client/service type creation events to refresh
    Livewire.on('client-created', () => {
        setTimeout(() => {
            initSearchableSelects();
        }, 100);
    });

    Livewire.on('service-type-created', () => {
        setTimeout(() => {
            initSearchableSelects();
        }, 100);
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Custom styling for Tom Select */
    .ts-wrapper {
        width: 100% !important;
    }

    .ts-control {
        min-height: 38px !important;
        border-radius: 0.25rem !important;
        border-color: #dee2e6 !important;
    }

    .ts-control:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    .input-group .ts-wrapper {
        flex: 1 1 auto;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .input-group .ts-wrapper .ts-control {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .ts-dropdown {
        border-color: #dee2e6 !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .no-results {
        padding: 8px 12px;
        color: #6c757d;
        text-align: center;
    }

    /* Fix for disabled state */
    select:disabled + .ts-wrapper {
        pointer-events: none;
        opacity: 0.6;
    }
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/partials/header-form.blade.php ENDPATH**/ ?>