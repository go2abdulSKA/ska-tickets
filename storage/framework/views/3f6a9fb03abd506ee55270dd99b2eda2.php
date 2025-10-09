

<div x-data="{
    transactions: <?php if ((object) ('transactions') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('transactions'->value()); ?>')<?php echo e('transactions'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('transactions'); ?>')<?php endif; ?>.live,
    uoms: <?php echo \Illuminate\Support\Js::from($uoms->toArray())->toHtml() ?>,

    addLine() {
        $wire.addLineItem();
    },

    removeLine(index) {
        if (this.transactions.length > 1) {
            $wire.removeLineItem(index);
        }
    },

    duplicateLine(index) {
        $wire.duplicateLineItem(index);
    },

    calculateLineTotal(index) {
        let item = this.transactions[index];
        if (item) {
            item.total_cost = (parseFloat(item.qty) || 0) * (parseFloat(item.unit_cost) || 0);
            $wire.calculateTotals();
        }
    }
}">

    
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Line Items</h5>
            <p class="mb-0 text-muted small">Add services or items with quantities and prices</p>
        </div>
        <button type="button" 
                @click="addLine()" 
                class="btn btn-primary btn-sm">
            <i class="mdi mdi-plus me-1"></i> Add Line Item
        </button>
    </div>

    
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">#</th>
                    <th style="width: 40%;">Description <span class="text-danger">*</span></th>
                    <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                    <th style="width: 15%;">UOM <span class="text-danger">*</span></th>
                    <th style="width: 12%;">Unit Cost <span class="text-danger">*</span></th>
                    <th style="width: 13%;">Total</th>
                    <th style="width: 10%;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr wire:key="transaction-<?php echo e($item['temp_id']); ?>">
                        
                        <td class="text-center align-middle">
                            <span class="fw-bold"><?php echo e($index + 1); ?></span>
                        </td>

                        
                        <td>
                            <textarea 
                                wire:model.live.debounce.300ms="transactions.<?php echo e($index); ?>.description"
                                class="form-control form-control-sm" 
                                rows="2" 
                                placeholder="Enter service description..." 
                                maxlength="500"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["transactions.{$index}.description"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        
                        <td>
                            <input type="number" 
                                   wire:model.live.debounce.300ms="transactions.<?php echo e($index); ?>.qty"
                                   wire:change="calculateLineItemTotal(<?php echo e($index); ?>)"
                                   class="form-control form-control-sm text-end" 
                                   step="0.001" 
                                   min="0.001"
                                   placeholder="1.000">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["transactions.{$index}.qty"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        
                        <td>
                            <div class="input-group input-group-sm">
                                <select wire:model.live="transactions.<?php echo e($index); ?>.uom_id"
                                        class="form-select form-select-sm">
                                    <option value="">-- Select --</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($uom->id); ?>"><?php echo e($uom->code); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <button type="button" 
                                        wire:click="openQuickAddUOM"
                                        class="btn btn-outline-secondary btn-sm" 
                                        title="Add UOM">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["transactions.{$index}.uom_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        
                        <td>
                            <input type="number" 
                                   wire:model.live.debounce.300ms="transactions.<?php echo e($index); ?>.unit_cost"
                                   wire:change="calculateLineItemTotal(<?php echo e($index); ?>)"
                                   class="form-control form-control-sm text-end" 
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["transactions.{$index}.unit_cost"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </td>

                        
                        <td class="align-middle">
                            <strong class="text-primary"><?php echo e(number_format($item['total_cost'], 2)); ?></strong>
                        </td>

                        
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm">
                                <button type="button" 
                                        wire:click="duplicateLineItem(<?php echo e($index); ?>)"
                                        class="btn btn-outline-info"
                                        title="Duplicate">
                                    <i class="mdi mdi-content-copy"></i>
                                </button>
                                <button type="button" 
                                        wire:click="removeLineItem(<?php echo e($index); ?>)"
                                        class="btn btn-outline-danger"
                                        title="Remove"
                                        <?php if(count($transactions) === 1): ?> disabled <?php endif; ?>>
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>

    
    <div class="d-lg-none">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-3 shadow-sm card" wire:key="transaction-mobile-<?php echo e($item['temp_id']); ?>">
                <div class="py-2 card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Item #<?php echo e($index + 1); ?></span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" 
                                wire:click="duplicateLineItem(<?php echo e($index); ?>)"
                                class="btn btn-outline-info btn-sm"
                                title="Duplicate">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                        <button type="button" 
                                wire:click="removeLineItem(<?php echo e($index); ?>)"
                                class="btn btn-outline-danger btn-sm"
                                title="Remove"
                                <?php if(count($transactions) === 1): ?> disabled <?php endif; ?>>
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            wire:model.live.debounce.300ms="transactions.<?php echo e($index); ?>.description"
                            class="form-control form-control-sm" 
                            rows="3" 
                            placeholder="Enter service description..." 
                            maxlength="500"></textarea>
                    </div>

                    
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                Qty <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   wire:model.live.debounce.300ms="transactions.<?php echo e($index); ?>.qty"
                                   wire:change="calculateLineItemTotal(<?php echo e($index); ?>)"
                                   class="form-control form-control-sm text-end" 
                                   step="0.001" 
                                   min="0.001"
                                   placeholder="1.000">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                UOM <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <select wire:model.live="transactions.<?php echo e($index); ?>.uom_id"
                                        class="form-select form-select-sm">
                                    <option value="">-- Select --</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($uom->id); ?>"><?php echo e($uom->code); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <button type="button" 
                                        wire:click="openQuickAddUOM"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                Unit Cost <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   wire:model.live.debounce.300ms="transactions.<?php echo e($index); ?>.unit_cost"
                                   wire:change="calculateLineItemTotal(<?php echo e($index); ?>)"
                                   class="form-control form-control-sm text-end" 
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Total</label>
                            <div class="p-2 rounded text-end bg-light">
                                <strong class="text-primary fs-5"><?php echo e(number_format($item['total_cost'], 2)); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

        
        <button type="button" 
                wire:click="addLineItem" 
                class="mb-3 btn btn-primary w-100">
            <i class="mdi mdi-plus me-1"></i> Add Another Line Item
        </button>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['transactions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="mt-3 alert alert-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

    
    <div class="mt-3 alert alert-info d-flex justify-content-between align-items-center">
        <span>
            <i class="mdi mdi-information-outline me-1"></i>
            Total Line Items: <strong><?php echo e(count($transactions)); ?></strong>
        </span>
        <span class="fw-bold">
            Subtotal: <?php echo e(\App\Enums\Currency::from($currency)->symbol()); ?><?php echo e(number_format($subtotal, 2)); ?>

        </span>
    </div>

</div>


<div class="mt-3 text-muted small">
    <i class="mdi mdi-keyboard-outline me-1"></i>
    <strong>Tips:</strong> Use Tab to navigate between fields. Totals calculate automatically.
</div>

<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/partials/line-items.blade.php ENDPATH**/ ?>