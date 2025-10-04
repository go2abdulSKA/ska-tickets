<!-- end sidenav-user -->

<div class="sidenav-user">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('dashboard') }}" class="link-reset">
                <img src="{{ Auth::user()->avatar ?? asset('backend/assets/images/users/user-3.jpg') }}"
                    alt="user-image" class="mb-2 rounded-circle avatar-md" />
                <span class="sidenav-user-name fw-bold">{{ Auth::user()->name }}</span>
                <span class="fs-12 fw-semibold">{{ Auth::user()->role->name ?? 'User' }}</span>
            </a>
        </div>
        <div>
            <a class="dropdown-toggle drop-arrow-none link-reset sidenav-user-set-icon"
                data-bs-toggle="dropdown" data-bs-offset="0,12" href="#!" aria-haspopup="false"
                aria-expanded="false">
                <i class="align-middle ti ti-settings fs-24 ms-1"></i>
            </a>

            <div class="dropdown-menu">
                <!-- Header -->
                <div class="dropdown-header noti-title">
                    <h6 class="m-0 text-overflow">Welcome back!</h6>
                </div>

                <!-- My Profile -->
                <a href="{{ route('dashboard') }}" class="dropdown-item">
                    <i class="align-middle ti ti-user-circle me-2 fs-17"></i>
                    <span class="align-middle">Profile</span>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item fw-semibold">
                        <i class="align-middle ti ti-logout-2 me-2 fs-17"></i>
                        <span class="align-middle">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
