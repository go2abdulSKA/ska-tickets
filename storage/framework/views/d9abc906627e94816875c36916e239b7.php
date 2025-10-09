<head>
    <meta charset="utf-8" />
    
    <title><?php echo e(config('app.name')); ?> - <?php echo e($title ?? 'Admin Panel'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php echo e(config('app.name')); ?>" />
    <meta name="keywords" content="<?php echo e(config('app.name')); ?>" />
    <meta name="author" content="<?php echo e(config('app.devname')); ?>" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('backend/assets/images/favicon.ico')); ?>" />

    <!-- Theme Config Js -->
    <script src="<?php echo e(asset('backend/assets/js/config.js')); ?>"></script>

    <!-- Vendor css -->
    <link href="<?php echo e(asset('backend/assets/css/vendors.min.css')); ?>" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="<?php echo e(asset('backend/assets/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    

    <!-- Styles -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


    


</head>
<!-- End Page Header -->
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/admin/partials/header.blade.php ENDPATH**/ ?>