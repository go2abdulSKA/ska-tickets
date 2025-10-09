<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="m-0 fs-xl fw-bold">Dashboard</h4>
        </div>

        <div class="text-end">
            <ol class="py-0 m-0 breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);"><?php echo e(config('app.name')); ?></a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>

    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">Total Tickets</span>
                            
                            <h3 class="mb-0">10</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i data-lucide="file-text" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">Draft Tickets</span>
                            
                            <h3 class="mb-0">20</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i data-lucide="edit" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">Posted Tickets</span>
                            
                            <h3 class="mb-0">2</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i data-lucide="check-circle" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">This Month</span>
                            
                            <h3 class="mb-0">14</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-info">
                                    <i data-lucide="calendar" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>