<!DOCTYPE html>
<html lang="en">

<!-- Page Header -->
@include('admin.partials.header')
<!-- Page Header End-->

<body>

    <!-- Begin page -->
    <div class="wrapper">

        <!-- Sidenav Menu Start -->
        @include('admin.partials.sidebar')
        <!-- Sidenav Menu End -->

        <!-- Topbar Start -->
        @include('admin.partials.topbar')
        <!-- Topbar End -->

        <!-- ============================================================== -->
        <!-- Start Main Content -->
        <!-- ============================================================== -->
        <div class="content-page">

            @yield('content')
            <!-- container -->

            <!-- Footer Start -->
            @include('admin.partials.footer')
            <!-- end Footer -->
        </div>

        <!-- ============================================================== -->
        <!-- End of Main Content -->
        <!-- ============================================================== -->

        <!-- Theme Settings -->
        @include('admin.partials.theme_setting')

    </div>
    <!-- END wrapper -->

    @include('admin.partials.scripts')

    @livewireScripts
    @stack('scripts')

    </body>

    </html>
