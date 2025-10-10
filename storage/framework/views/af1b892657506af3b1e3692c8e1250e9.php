<!DOCTYPE html>
<html lang="en">


    <!-- Page Header -->
    <head>

        <?php echo $__env->make('admin.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->yieldPushContent('styles'); ?>

        <!-- Livewire Styles -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


    </head>
    <!-- Page Header End-->

    <body>

        <!-- Begin page -->
        <div class="wrapper">

            <!-- Sidenav Menu Start -->
            <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Sidenav Menu End -->

            <!-- Topbar Start -->
            <?php echo $__env->make('admin.partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Topbar End -->

            <!-- ============================================================== -->
            <!-- Start Main Content -->
            <!-- ============================================================== -->
            <div class="content-page">

                <div class="container-fluid">

                    <?php echo e($slot); ?>

                    
                    <!-- container -->

                </div> <!-- container-fluid -->

                <!-- Footer Start -->
                <?php echo $__env->make('admin.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <!-- end Footer -->

            </div> <!-- content-page -->

            <!-- ============================================================== -->
            <!-- End of Main Content -->
            <!-- ============================================================== -->

            <!-- Theme Settings -->
            

        </div>
        <!-- END wrapper -->

        
        

        <?php echo $__env->yieldPushContent('scripts'); ?>

        <?php echo $__env->make('admin.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        <!-- Livewire Scripts -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    </body>

</html>
<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/admin/layout.blade.php ENDPATH**/ ?>