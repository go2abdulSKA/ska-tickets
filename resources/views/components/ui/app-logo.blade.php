{{-- resources/views/components/admin/app-logo.blade.php --}}

<a href="{{ route('dashboard') }}" class="logo">
    <span class="logo logo-light">
        {{-- <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" /></span>
        <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" /></span> --}}
        <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" /></span>
        <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" /></span>

    </span>

    <span class="logo logo-dark">
        <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo-black.png') }}"
                alt="dark logo" /></span>
        <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" /></span>
    </span>
</a>
