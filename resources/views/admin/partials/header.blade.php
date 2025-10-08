<head>
    <meta charset="utf-8" />
    {{-- <title>{{ config('app.name') }} - @yield('title', 'Admin Panel')</title> --}}
    <title>{{ config('app.name') }} - {{ $title ?? 'Admin Panel' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}" />
    <meta name="author" content="{{ config('app.devname') }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}" />

    <!-- Theme Config Js -->
    <script src="{{ asset('backend/assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('backend/assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Styles -->
    @livewireStyles

    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Alpine) {
                console.log('%c✅ Alpine.js is loaded', 'color: green');
            } else {
                console.warn('%c❌ Alpine.js NOT loaded', 'color: red');
            }
        });
    </script> --}}


</head>
<!-- End Page Header -->
