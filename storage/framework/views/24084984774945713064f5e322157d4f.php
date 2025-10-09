

<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="gap-2 d-flex align-items-center">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="index.html" class="logo-light">
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('backend/assets/images/logo.png')); ?>" alt="logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('backend/assets/images/logo-sm.png')); ?>" alt="small logo" />
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('backend/assets/images/logo-black.png')); ?>" alt="dark logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('backend/assets/images/logo-sm.png')); ?>" alt="small logo" />
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button btn btn-default btn-icon">
                <i class="ti ti-menu-4 fs-22"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            

            <!-- Mega Menu Dropdown -->
            
        </div>
        <!-- .d-flex-->

        <div class="gap-2 d-flex align-items-center">
            <!-- Search -->
            

            <!-- Language Dropdown -->
            
            <!-- end topbar item-->

            <!-- Notification Dropdown -->
            
            <!-- end topbar item-->

            <!-- Theme Mode Dropdown -->
            
            <!-- end topbar item-->

            <!-- Theme Mode Dropdown -->
            <div class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i data-lucide="sun" class="fs-xxl"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end thememode-dropdown">
                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="sun" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">Light</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme"
                                    value="light" />
                            </label>
                        </li>

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="moon" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">Dark</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme"
                                    value="dark" />
                            </label>
                        </li>

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="monitor-cog" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">System</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme"
                                    value="system" />
                            </label>
                        </li>
                    </ul>
                    <!-- end dropdown-menu-->
                </div>
                <!-- end dropdown-->
            </div>
            <!-- end topbar item-->

            <!-- FullScreen -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" type="button" data-toggle="fullscreen">
                    <i data-lucide="maximize" class="fs-xxl fullscreen-off"></i>
                    <i data-lucide="minimize" class="fs-xxl fullscreen-on"></i>
                </button>
            </div>

            <!-- Light/Dark Mode Button -->
            <div class="topbar-item d-none">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                </button>
            </div>

            <!-- Monocrome Mode Button -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" type="button" id="monochrome-mode">
                    <i data-lucide="palette" class="fs-xxl"></i>
                </button>
            </div>

            <!-- User Dropdown -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="px-2 topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
                        data-bs-offset="0,19" href="#!" aria-haspopup="false" aria-expanded="false">

<img 
                        src="<?php echo e(Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('backend/assets/images/users/user-3.jpg')); ?>" 
                            width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image" />

                        

                            <div class="gap-1 d-lg-flex align-items-center d-none">
                            <h5 class="my-0"><?php echo e(Auth::user()->name ?? 'testing'); ?></h5>
                            <i class="align-middle ti ti-chevron-down"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="m-0 text-overflow">Welcome back 👋!</h6>
                        </div>

                        <!-- My Profile -->
                        <a href="#" class="dropdown-item">
                            <i class="align-middle ti ti-user-circle me-1 fs-17"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <!-- Notifications -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="align-middle ti ti-bell-ringing me-1 fs-17"></i>
                            <span class="align-middle">Notifications</span>
                        </a>

                        <!-- Settings -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="align-middle ti ti-settings-2 me-1 fs-17"></i>
                            <span class="align-middle">Account Settings</span>
                        </a>

                        <!-- Support -->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="align-middle ti ti-headset me-1 fs-17"></i>
                            <span class="align-middle">Support Center</span>
                        </a>

                        <!-- Divider -->
                        <div class="dropdown-divider"></div>

                        <!-- Lock -->
                        <a href="#" class="dropdown-item">
                            <i class="align-middle ti ti-lock me-1 fs-17"></i>
                            <span class="align-middle">Lock Screen</span>
                        </a>

                        <!-- Logout -->
                        
                        <a href="#" class="dropdown-item fw-semibold">
                            <i class="align-middle ti ti-logout-2 me-1 fs-17"></i>
                            <span class="align-middle">Log Out</span>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Button Trigger Customizer Offcanvas -->
            <div class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                    type="button">
                    <i class="ti ti-settings icon-spin fs-24"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<?php /**PATH C:\xampp\htdocs\ska-tickets\resources\views/admin/partials/topbar.blade.php ENDPATH**/ ?>