<!DOCTYPE html>
<html lang="en">


    <!-- Page Header -->
    <head>

        @include('admin.partials.header')

        @stack('styles')

        <!-- Livewire Styles -->
        @livewireStyles

    </head>
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

                <div class="container-fluid">

                    {{ $slot }}
                    {{-- @_yield('content') --}}
                    <!-- container -->

                </div> <!-- container-fluid -->

                <!-- Footer Start -->
                @include('admin.partials.footer')
                <!-- end Footer -->

            </div> <!-- content-page -->

            <!-- ============================================================== -->
            <!-- End of Main Content -->
            <!-- ============================================================== -->

            <!-- Theme Settings -->
            {{-- @include('admin.partials.theme_setting') --}}

        </div>
        <!-- END wrapper -->

        {{-- Additional Scripts --}}
        {{-- {{ $scripts ?? '' }} --}}

        @stack('scripts')

        @include('admin.partials.scripts')


        <!-- Livewire Scripts -->
        @livewireScripts

    </body>

</html>
