{{-- Topbar Starts --}}

<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="gap-2 d-flex align-items-center">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="index.html" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" />
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo-black.png') }}" alt="dark logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" />
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button btn btn-default btn-icon">
                <i class="ti ti-menu-4 fs-22"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            {{-- <button class="px-2 topnav-toggle-button" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ti ti-menu-4 fs-22"></i>
            </button> --}}

            <!-- Mega Menu Dropdown -->
            {{-- <div class="topbar-item d-none d-md-flex">
                <div class="dropdown">
                    <button class="topbar-link btn fw-medium btn-link dropdown-toggle drop-arrow-none"
                        data-lang="mega-menu" data-bs-toggle="dropdown" data-bs-offset="0,17" type="button"
                        aria-haspopup="false" aria-expanded="false">
                        Mega Menu <i class="ti ti-chevron-down ms-1 fs-16"></i>
                    </button>
                    <div class="p-0 dropdown-menu dropdown-menu-xxl">
                        <div class="h-100" style="max-height: 380px" data-simplebar>
                            <div class="row g-0">
                                <!-- Dashboard & Analytics -->
                                <div class="col-md-4">
                                    <div class="p-2">
                                        <h5 class="mb-1 fw-semibold fs-sm dropdown-header">
                                            Dashboard & Analytics
                                        </h5>
                                        <ul class="list-unstyled megamenu-list">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-chart-line me-2 fs-16"></i>
                                                    Sales Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-bulb me-2 fs-16"></i>
                                                    Marketing Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-currency-dollar me-2 fs-16"></i>
                                                    Finance Overview</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-users me-2 fs-16"></i>
                                                    User Analytics</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-activity me-2 fs-16"></i>
                                                    Traffic Insights</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-gauge me-2 fs-16"></i>
                                                    Performance Metrics</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-zoom-check me-2 fs-16"></i>
                                                    Conversion Tracking</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Project Management -->
                                <div class="col-md-4">
                                    <div class="p-2">
                                        <h5 class="mb-1 fw-semibold fs-sm dropdown-header">
                                            Project Management
                                        </h5>
                                        <ul class="list-unstyled megamenu-list">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-layout-kanban me-2 fs-16"></i>
                                                    Kanban Workflow</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-calendar-stats me-2 fs-16"></i>
                                                    Project Timeline</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-list-check me-2 fs-16"></i>
                                                    Task Management</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-users-group me-2 fs-16"></i>
                                                    Team Members</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-clipboard-list me-2 fs-16"></i>
                                                    Assignments</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-chart-pie me-2 fs-16"></i>
                                                    Resource Allocation</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-file-invoice me-2 fs-16"></i>
                                                    Project Reports</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- User Management -->
                                <div class="col-md-4">
                                    <div class="p-2">
                                        <h5 class="mb-1 fw-semibold fs-sm dropdown-header">
                                            User Management
                                        </h5>
                                        <ul class="list-unstyled megamenu-list">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-user-circle me-2 fs-16"></i>
                                                    User Profiles</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-lock me-2 fs-16"></i>
                                                    Access Control</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-shield-lock me-2 fs-16"></i>
                                                    Role Permissions</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-notes me-2 fs-16"></i>
                                                    Activity Logs</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-settings me-2 fs-16"></i>
                                                    Security Settings</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-users me-2 fs-16"></i>
                                                    User Groups</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item"><i
                                                        class="align-middle ti ti-key me-2 fs-16"></i>
                                                    Authentication
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- .d-flex-->

        <div class="gap-2 d-flex align-items-center">
            <!-- Search -->
            {{-- <div class="app-search d-none d-xl-flex me-2">
                <input type="search" class="form-control topbar-search rounded-pill" name="search"
                    placeholder="Quick Search..." />
                <i data-lucide="search" class="app-search-icon text-muted"></i>
            </div> --}}

            <!-- Language Dropdown -->
            {{-- <div class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link fw-bold" data-bs-toggle="dropdown" data-bs-offset="0,24"
                        type="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('backend/assets/images/flags/us.svg') }}" alt="user-image" class="rounded"
                            height="20" id="selected-language-image" />
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item" data-translator-lang="en"
                            title="English">
                            <img src="{{ asset('backend/assets/images/flags/us.svg') }}" alt="English"
                                class="rounded me-1" height="18" data-translator-image />
                            <span class="align-middle">English</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item" data-translator-lang="de"
                            title="German">
                            <img src="{{ asset('backend/assets/images/flags/de.svg') }}" alt="German"
                                class="rounded me-1" height="18" data-translator-image />
                            <span class="align-middle">Deutsch</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item" data-translator-lang="it"
                            title="Italian">
                            <img src="{{ asset('backend/assets/images/flags/it.svg') }}" alt="Italian"
                                class="rounded me-1" height="18" data-translator-image />
                            <span class="align-middle">Italiano</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item" data-translator-lang="es"
                            title="Spanish">
                            <img src="{{ asset('backend/assets/images/flags/es.svg') }}" alt="Spanish"
                                class="rounded me-1" height="18" data-translator-image />
                            <span class="align-middle">Español</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item" data-translator-lang="ru"
                            title="Russian">
                            <img src="{{ asset('backend/assets/images/flags/ru.svg') }}" alt="Russian"
                                class="rounded me-1" height="18" data-translator-image />
                            <span class="align-middle">Русский</span>
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item" data-translator-lang="hi"
                            title="Hindi">
                            <img src="{{ asset('backend/assets/images/flags/in.svg') }}" alt="Hindi"
                                class="rounded me-1" height="18" data-translator-image />
                            <span class="align-middle">हिन्दी</span>
                        </a>
                    </div>
                    <!-- end dropdown-menu-->
                </div>
                <!-- end dropdown-->
            </div> --}}
            <!-- end topbar item-->

            <!-- Notification Dropdown -->
            {{-- <div class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
                        data-bs-offset="0,24" type="button" data-bs-auto-close="outside" aria-haspopup="false"
                        aria-expanded="false">
                        <i data-lucide="bell" class="fs-xxl"></i>
                        <span class="badge text-bg-danger badge-circle topbar-badge">5</span>
                    </button>

                    <div class="p-0 dropdown-menu dropdown-menu-end dropdown-menu-lg">
                        <div class="px-3 py-2 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-md fw-semibold">Notifications</h6>
                                </div>
                                <div class="col text-end">
                                    <a href="#!" class="py-1 badge badge-soft-success badge-label">07
                                        Notifications</a>
                                </div>
                            </div>
                        </div>

                        <div style="max-height: 300px" data-simplebar>
                            <!-- Notification 1 -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-1">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <img src="{{ asset('backend/assets/images/users/user-4.jpg') }}"
                                            class="avatar-md rounded-circle" alt="User Avatar" />
                                        <span class="position-absolute rounded-pill bg-success notification-badge">
                                            <i class="align-middle ti ti-bell"></i>
                                            <span class="visually-hidden">unread notification</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Emily Johnson</span>
                                        commented on a task in
                                        <span class="fw-medium text-body">Design Sprint</span><br />
                                        <span class="fs-xs">12 minutes ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-1">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 2 -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-2">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <img src="{{ asset('backend/assets/images/users/user-5.jpg') }}"
                                            class="avatar-md rounded-circle" alt="User Avatar" />
                                        <span class="position-absolute rounded-pill bg-info notification-badge">
                                            <i class="align-middle ti ti-cloud-upload"></i>
                                            <span class="visually-hidden">upload notification</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Michael Lee</span>
                                        uploaded files to
                                        <span class="fw-medium text-body">Marketing Assets</span><br />
                                        <span class="fs-xs">25 minutes ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-2">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 3 -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-3">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <img src="{{ asset('backend/assets/images/users/user-6.jpg') }}"
                                            class="avatar-md rounded-circle" alt="User Avatar" />
                                        <span class="position-absolute rounded-pill bg-warning notification-badge">
                                            <i class="align-middle ti ti-alert-triangle"></i>
                                            <span class="visually-hidden">alert</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Sophia Ray</span>
                                        flagged an issue in
                                        <span class="fw-medium text-body">Bug Tracker</span><br />
                                        <span class="fs-xs">40 minutes ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-3">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 4 -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-4">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <img src="{{ asset('backend/assets/images/users/user-7.jpg') }}"
                                            class="avatar-md rounded-circle" alt="User Avatar" />
                                        <span class="position-absolute rounded-pill bg-primary notification-badge">
                                            <i class="align-middle ti ti-calendar-event"></i>
                                            <span class="visually-hidden">event notification</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">David Kim</span>
                                        scheduled a meeting for
                                        <span class="fw-medium text-body">UX Review</span><br />
                                        <span class="fs-xs">1 hour ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-4">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 5 -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-5">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <img src="{{ asset('backend/assets/images/users/user-8.jpg') }}"
                                            class="avatar-md rounded-circle" alt="User Avatar" />
                                        <span class="position-absolute rounded-pill bg-secondary notification-badge">
                                            <i class="align-middle ti ti-edit-circle"></i>
                                            <span class="visually-hidden">edit</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Isabella White</span>
                                        updated the document in
                                        <span class="fw-medium text-body">Product Specs</span><br />
                                        <span class="fs-xs">2 hours ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-5">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 6 - Server CPU Alert -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-6">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <span
                                            class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center">
                                            <i class="ti ti-server-bolt fs-4 text-danger"></i>
                                        </span>
                                        <span class="position-absolute rounded-pill bg-danger notification-badge">
                                            <i class="align-middle ti ti-alert-circle"></i>
                                            <span class="visually-hidden">server alert</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Server #3</span> CPU
                                        usage exceeded 90%<br />
                                        <span class="fs-xs">Just now</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-6">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 7 - Deployment Success -->
                            <div class="py-2 dropdown-item notification-item text-wrap" id="message-7">
                                <span class="gap-3 d-flex align-items-center">
                                    <span class="flex-shrink-0 position-relative">
                                        <span
                                            class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center">
                                            <i class="ti ti-rocket fs-4 text-success"></i>
                                        </span>
                                        <span class="position-absolute rounded-pill bg-success notification-badge">
                                            <i class="align-middle ti ti-check"></i>
                                            <span class="visually-hidden">deployment</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Production Server</span>
                                        deployment completed successfully<br />
                                        <span class="fs-xs">30 minutes ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 p-0 text-muted btn btn-link position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-7">
                                        <i class="ti ti-xbox-x-filled fs-xxl"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- All-->
                        <a href="javascript:void(0);"
                            class="py-2 text-center dropdown-item text-reset text-decoration-underline link-offset-2 fw-bold notify-item border-top border-light">
                            Read All Messages
                        </a>
                    </div>
                    <!-- End dropdown-menu -->
                </div>
                <!-- end dropdown-->
            </div> --}}
            <!-- end topbar item-->

            <!-- Theme Mode Dropdown -->
            {{-- <div class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i data-lucide="layout-grid" class="fs-xxl"></i>
                    </button>

                    <div class="p-2 dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <div class="row align-items-center g-1">
                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title text-bg-light rounded-circle">
                                            <img src="{{ asset('backend/assets/images/logos/google.svg') }}"
                                                alt="Google Logo" height="18" />
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Google</span>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title text-bg-light rounded-circle">
                                            <img src="{{ asset('backend/assets/images/logos/figma.svg') }}"
                                                alt="Figma Logo" height="18" />
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Figma</span>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title text-bg-light rounded-circle">
                                            <img src="{{ asset('backend/assets/images/logos/slack.svg') }}"
                                                alt="Slack Logo" height="18" />
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Slack</span>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title text-bg-light rounded-circle">
                                            <img src="{{ asset('backend/assets/images/logos/dropbox.svg') }}"
                                                alt="Dropbox Logo" height="18" />
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Dropbox</span>
                                </a>
                            </div>

                            <div class="text-center col-4">
                                <a href="javascript:void(0);" class="btn btn-sm rounded-circle btn-icon btn-danger">
                                    <i data-lucide="circle-plus" class="fs-18"></i>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ti ti-calendar fs-18"></i>
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Calendar</span>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ti ti-message-circle fs-18"></i>
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Chat</span>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ti ti-folder fs-18"></i>
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Files</span>
                                </a>
                            </div>

                            <div class="col-4">
                                <a href="javascript:void(0);"
                                    class="py-2 text-center border border-dashed rounded dropdown-item">
                                    <span class="mx-auto mb-1 avatar-sm d-block">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ti ti-users fs-18"></i>
                                        </span>
                                    </span>
                                    <span class="align-middle fw-medium">Team</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end dropdown-->
            </div> --}}
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
                                <input class="form-check-input" type="radio" name="data-bs-theme" value="light" />
                            </label>
                        </li>

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="moon" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">Dark</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme" value="dark" />
                            </label>
                        </li>

                        <li>
                            <label class="dropdown-item">
                                <i data-lucide="monitor-cog" class="align-middle me-1 fs-16"></i>
                                <span class="align-middle">System</span>
                                <input class="form-check-input" type="radio" name="data-bs-theme" value="system" />
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

                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('backend/assets/images/users/user-3.jpg') }}"
                            width="32" class="rounded-circle me-lg-2 d-flex" alt="user-image" />

                        {{-- <img src="{{ Auth::user()->profile_photo_path ?? asset('storage/' . Auth::user()->profile_photo_path) }} :: asset('backend/assets/images/users/user-3.jpg') }}" width="32"
                            class="rounded-circle me-lg-2 d-flex" alt="user-image" /> --}}

                        <div class="gap-1 d-lg-flex align-items-center d-none">
                            <h5 class="my-0">{{ Auth::user()->name ?? 'testing' }}</h5>
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
                        {{-- <a href="{{ route('admin.logout') }}" class="dropdown-item fw-semibold"> --}}
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
