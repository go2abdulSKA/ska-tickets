

<div>
    
    <?php if (isset($component)) { $__componentOriginal91a231a9270579fa1ae9246bd51fb785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91a231a9270579fa1ae9246bd51fb785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.page-header','data' => ['title' => 'Finance Tickets','page' => 'Tickets','subpage' => 'Finance']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Finance Tickets','page' => 'Tickets','subpage' => 'Finance']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $attributes = $__attributesOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__attributesOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91a231a9270579fa1ae9246bd51fb785)): ?>
<?php $component = $__componentOriginal91a231a9270579fa1ae9246bd51fb785; ?>
<?php unset($__componentOriginal91a231a9270579fa1ae9246bd51fb785); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginalf2e394a2ecd19198970344c76e4108ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf2e394a2ecd19198970344c76e4108ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.flash-msg','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.flash-msg'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf2e394a2ecd19198970344c76e4108ce)): ?>
<?php $attributes = $__attributesOriginalf2e394a2ecd19198970344c76e4108ce; ?>
<?php unset($__attributesOriginalf2e394a2ecd19198970344c76e4108ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf2e394a2ecd19198970344c76e4108ce)): ?>
<?php $component = $__componentOriginalf2e394a2ecd19198970344c76e4108ce; ?>
<?php unset($__componentOriginalf2e394a2ecd19198970344c76e4108ce); ?>
<?php endif; ?>

    
    <div class="mb-3 row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Total Tickets</h6>
                            <h3 class="mb-0"><?php echo e($stats['total']); ?></h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-file-document text-primary fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Draft</h6>
                            <h3 class="mb-0"><?php echo e($stats['draft']); ?></h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-file-edit text-warning fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Posted</h6>
                            <h3 class="mb-0"><?php echo e($stats['posted']); ?></h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Total Amount</h6>
                            <h3 class="mb-0">$<?php echo e(number_format($stats['total_amount'], 2)); ?></h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-cash-multiple text-info fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                
                <div class="card-header border-light">
                    <div class="row g-3">
                        
                        
                        <div class="col-12">
                            <div class="flex-wrap gap-2 d-flex justify-content-between align-items-center">
                                
                                <div class="app-search" style="min-width: 300px;">
                                    <input wire:model.live.debounce.300ms="search" 
                                           type="search" 
                                           class="form-control"
                                           placeholder="Search by ticket no, client, project...">
                                    <i data-lucide="search" class="app-search-icon text-muted"></i>
                                </div>

                                
                                <a href="<?php echo e(route('tickets.finance.create')); ?>" class="btn btn-primary">
                                    <i data-lucide="plus" class="fs-sm me-2"></i> Create Ticket
                                </a>
                            </div>
                        </div>

                        
                        <div class="col-12">
                            <div class="flex-wrap gap-2 d-flex align-items-center">
                                
                                
                                <div class="flex-shrink-0">
                                    <select wire:model.live="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = \App\Enums\TicketStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status->value); ?>"><?php echo e($status->label()); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>

                                
                                <!--[if BLOCK]><![endif]--><?php if(Auth::user()->isSuperAdmin() || Auth::user()->departments->count() > 1): ?>
                                    <div class="flex-shrink-0">
                                        <select wire:model.live="departmentFilter" class="form-select form-select-sm">
                                            <option value="">All Departments</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->department); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                
                                <div class="flex-shrink-0">
                                    <select wire:model.live="clientTypeFilter" class="form-select form-select-sm">
                                        <option value="">All Types</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = \App\Enums\ClientType::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->value); ?>"><?php echo e($type->label()); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>

                                
                                <div class="flex-shrink-0">
                                    <input type="date" 
                                           wire:model.live="dateFrom" 
                                           class="form-control form-control-sm"
                                           placeholder="From Date">
                                </div>

                                
                                <div class="flex-shrink-0">
                                    <input type="date" 
                                           wire:model.live="dateTo" 
                                           class="form-control form-control-sm"
                                           placeholder="To Date">
                                </div>

                                
                                <button type="button" 
                                        wire:click="clearFilters" 
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Clear Filters">
                                    <i class="mdi mdi-filter-remove"></i> Clear
                                </button>

                                
                                <div class="flex-shrink-0 ms-auto">
                                    <select wire:model.live="perPage" class="form-select form-select-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                                
                                <!--[if BLOCK]><![endif]--><?php if(count($selectedItems) > 0): ?>
                                    <button type="button" 
                                            wire:click="confirmBulkDelete" 
                                            class="btn btn-sm btn-danger">
                                        <i class="mdi mdi-delete me-1"></i> Delete Selected (<?php echo e(count($selectedItems)); ?>)
                                    </button>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                
                                <div class="flex-shrink-0 btn-group">
                                    <button type="button" 
                                            wire:click="exportExcel" 
                                            class="btn btn-sm btn-outline-success"
                                            title="Export to Excel">
                                        <i class="mdi mdi-file-excel"></i>
                                    </button>
                                    <button type="button" 
                                            wire:click="exportPDF" 
                                            class="btn btn-sm btn-outline-danger"
                                            title="Export to PDF">
                                        <i class="mdi mdi-file-pdf-box"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="table-responsive d-none d-lg-block">
                    <table class="table mb-0 table-custom table-centered table-hover w-100">
                        
                        <thead class="align-middle bg-opacity-25 bg-light">
                            <tr class="text-uppercase" style="font-size: 0.75rem;">
                                
                                <th class="ps-3" style="width: 40px;">
                                    <input wire:model.live="selectAll"
                                        class="form-check-input" 
                                        type="checkbox">
                                </th>

                                
                                <th wire:click="sortBy('ticket_no')" style="cursor: pointer; width: 130px;">
                                    Ticket No
                                    <!--[if BLOCK]><![endif]--><?php if($sortField === 'ticket_no'): ?>
                                        <i class="mdi mdi-arrow-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?>"></i>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </th>

                                
                                <th wire:click="sortBy('ticket_date')" style="cursor: pointer; width: 110px;">
                                    Date
                                    <!--[if BLOCK]><![endif]--><?php if($sortField === 'ticket_date'): ?>
                                        <i class="mdi mdi-arrow-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?>"></i>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </th>

                                
                                <th>Customer</th>

                                
                                <th style="width: 120px;">Project</th>

                                
                                <th wire:click="sortBy('total_amount')" 
                                    class="text-end" 
                                    style="cursor: pointer; width: 120px;">
                                    Amount
                                    <!--[if BLOCK]><![endif]--><?php if($sortField === 'total_amount'): ?>
                                        <i class="mdi mdi-arrow-<?php echo e($sortDirection === 'asc' ? 'up' : 'down'); ?>"></i>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </th>

                                
                                <th class="text-center" style="width: 100px;">Status</th>

                                
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>

                        
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems" 
                                               value="<?php echo e($ticket->id); ?>"
                                               class="form-check-input" 
                                               type="checkbox">
                                    </td>

                                    
                                    <td>
                                        <a href="javascript:void(0);" 
                                           wire:click="view(<?php echo e($ticket->id); ?>)"
                                           class="text-decoration-none">
                                            <strong class="text-primary"><?php echo e($ticket->ticket_no); ?></strong>
                                        </a>
                                    </td>

                                    
                                    <td>
                                        <span class="text-muted"><?php echo e($ticket->ticket_date->format('d M, Y')); ?></span>
                                    </td>

                                    
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold"><?php echo e($ticket->customer_name); ?></span>
                                            <small class="text-muted">
                                                <span class="badge badge-soft-<?php echo e($ticket->client_type->value === 'client' ? 'primary' : 'info'); ?> badge-sm">
                                                    <?php echo e($ticket->client_type->label()); ?>

                                                </span>
                                            </small>
                                        </div>
                                    </td>

                                    
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($ticket->project_code): ?>
                                            <span class="badge badge-soft-secondary"><?php echo e($ticket->project_code); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>

                                    
                                    <td class="text-end">
                                        <strong class="text-primary">
                                            <?php echo e($ticket->currency->symbol()); ?><?php echo e(number_format($ticket->total_amount, 2)); ?>

                                        </strong>
                                    </td>

                                    
                                    <td class="text-center">
                                        <span class="badge <?php echo e($ticket->status->badgeClass()); ?>">
                                            <?php echo e($ticket->status->label()); ?>

                                        </span>
                                    </td>

                                    
                                    <td class="text-center">
                                        <div class="gap-1 d-flex justify-content-center">
                                            
                                            <button wire:click="view(<?php echo e($ticket->id); ?>)"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-light btn-icon btn-sm"
                                                    title="View Details">
                                                <i class="mdi mdi-eye"></i>
                                            </button>

                                            
                                            <!--[if BLOCK]><![endif]--><?php if($ticket->canEdit()): ?>
                                                <a href="<?php echo e(route('tickets.finance.edit', $ticket->id)); ?>"
                                                   class="btn btn-light btn-icon btn-sm"
                                                   title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                            
                                            <a href="<?php echo e(route('tickets.finance.duplicate', $ticket->id)); ?>"
                                               class="btn btn-light btn-icon btn-sm"
                                               title="Duplicate">
                                                <i class="mdi mdi-content-copy"></i>
                                            </a>

                                            
                                            <!--[if BLOCK]><![endif]--><?php if($ticket->canDelete()): ?>
                                                <button wire:click="confirmDelete(<?php echo e($ticket->id); ?>)"
                                                        class="btn btn-danger btn-icon btn-sm"
                                                        title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                
                                <tr>
                                    <td colspan="8" class="py-5 text-center">
                                        <div class="text-muted">
                                            <i class="mdi mdi-file-document-outline" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2 mb-0">No finance tickets found</p>
                                            <a href="<?php echo e(route('tickets.finance.create')); ?>" class="mt-2 btn btn-sm btn-primary">
                                                <i class="mdi mdi-plus me-1"></i> Create Your First Ticket
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                
                <div class="p-3 d-lg-none">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('livewire.tickets.finance.partials.ticket-card-mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="py-5 text-center text-muted">
                            <i class="mdi mdi-file-document-outline" style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="mt-2 mb-0">No finance tickets found</p>
                            <a href="<?php echo e(route('tickets.finance.create')); ?>" class="mt-2 btn btn-sm btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Create Your First Ticket
                            </a>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                
                <div class="card-footer border-top bg-light">
                    <div class="flex-wrap gap-2 d-flex justify-content-between align-items-center">
                        
                        <div class="text-muted small">
                            Showing
                            <span class="fw-semibold"><?php echo e($tickets->firstItem() ?? 0); ?></span> to
                            <span class="fw-semibold"><?php echo e($tickets->lastItem() ?? 0); ?></span> of
                            <span class="fw-semibold"><?php echo e($tickets->total()); ?></span> tickets
                        </div>

                        
                        <div>
                            <?php echo e($tickets->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    
    <!--[if BLOCK]><![endif]--><?php if($showViewOffcanvas && $viewTicketId): ?>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tickets.finance.view-finance-ticket', ['ticketId' => $viewTicketId]);

$__html = app('livewire')->mount($__name, $__params, 'view-'.$viewTicketId, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
        <?php echo $__env->make('livewire.tickets.finance.partials.delete-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($showBulkDeleteModal): ?>
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="pb-0 border-0 modal-header">
                        <h5 class="modal-title">Confirm Bulk Deletion</h5>
                        <button type="button" class="btn-close" wire:click="cancelBulkDelete"></button>
                    </div>
                    <div class="p-4 text-center modal-body">
                        <div class="mb-3">
                            <i class="mdi mdi-alert-triangle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="mb-2">Are you sure?</h4>
                        <p class="mb-3 text-muted">
                            You are about to delete <strong class="text-danger"><?php echo e(count($selectedItems)); ?> ticket(s)</strong>
                        </p>
                        <div class="alert alert-warning text-start">
                            <small><strong>Note:</strong> Only draft tickets will be deleted.</small>
                        </div>
                        <div class="gap-2 d-grid">
                            <button type="button" wire:click="bulkDelete" class="btn btn-danger" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="bulkDelete">
                                    <i class="mdi mdi-delete me-1"></i> Yes, Delete Selected!
                                </span>
                                <span wire:loading wire:target="bulkDelete">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Deleting...
                                </span>
                            </button>
                            <button type="button" wire:click="cancelBulkDelete" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>


<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Reinitialize icons after Livewire updates
    document.addEventListener('livewire:update', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Close offcanvas on event
    Livewire.on('close-offcanvas', () => {
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('showViewOffcanvas', false);
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('viewTicketId', null);
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/livewire/tickets/finance/index.blade.php ENDPATH**/ ?>