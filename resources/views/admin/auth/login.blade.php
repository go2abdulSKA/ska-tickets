<!DOCTYPE html>
<html lang="en">

@include('admin.auth.partials.header');

<body>
    <div class="overflow-hidden auth-box align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="p-4 card">

                        {{-- Background SVG and logos --}}
                        @include('admin.auth.partials.loginbg');

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input value="admin@ska.com" type="email" class="form-control" id="email"
                                        name="email" autofocus="autofocus" autocomplete="username"
                                        placeholder="you@example.com" required />
                                </div>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input value="password" name="password" type="password" class="form-control"
                                        id="password" autocomplete="current-password" placeholder="••••••••"
                                        required />
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input-light fs-14" type="checkbox"
                                        checked id="remember_me" name="remember" />
                                    <label class="form-check-label" for="remember_me">Keep me signed in</label>
                                </div>
                                <a href="{{ route('password.request') }}"
                                    class="text-decoration-underline link-offset-3 text-muted">Forgot Password?</a>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="py-2 btn btn-primary fw-semibold">
                                    Sign In
                                </button>
                            </div>
                        </form>

                        <!-- Registeration link -->
                        {{-- <p class="mt-4 mb-0 text-center text-muted">
                            New here?
                            <a href="{{ route('register') }}"
                                class="text-decoration-underline link-offset-3 fw-semibold">Create an account</a>
                        </p> --}}
                    </div>

                    <p class="mt-4 mb-0 text-center text-muted">
                        ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        {{ config('app.name') }} — by <span class="fw-semibold">{{ config('app.devname') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- end auth-fluid-->
    <!-- Vendor js -->
    <script src="{{ asset('backend/assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
</body>

</html>
