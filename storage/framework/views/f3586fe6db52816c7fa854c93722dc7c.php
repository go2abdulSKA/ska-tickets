<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'page' => '', 'subpage' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title', 'page' => '', 'subpage' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="page-title-head d-flex align-items-center">
    <div class="flex-grow-1">
        <h4 class="m-0 fs-xl fw-bold"><?php echo e($title); ?></h4>
    </div>

    <div class="text-end">
        <ol class="py-0 m-0 breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript: void(0);"><?php echo e(config('app.name')); ?></a>
            </li>

            <!--[if BLOCK]><![endif]--><?php if(isset($page)): ?>
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);"><?php echo e($page); ?></a>
                </li>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if(isset($subpage)): ?>
                 <li class="breadcrumb-item active">
                    <a href="javascript: void(0);"><?php echo e($subpage); ?></a>
                </li>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </ol>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/components/ui/page-header.blade.php ENDPATH**/ ?>