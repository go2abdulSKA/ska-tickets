<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo.png') }}" alt="logo" /></span>
            <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{ asset('backend/assets/images/logo-black.png') }}" alt="dark logo" /></span>
            <span class="logo-sm"><img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="small logo" /></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <i class="align-middle ti ti-menu-4 fs-22"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i class="align-middle ti ti-x"></i>
    </button>

    <div class="scrollbar" data-simplebar>
        <!-- User -->
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

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="mt-2 side-nav-title">Navigation</li>

            <!-- Dashboard -->
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="circle-gauge"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            {{-- Finance Tickets Section --}}
            @if(Auth::user()->hasPermission('view-finance-ticket') || Auth::user()->hasPermission('create-finance-ticket'))
            <li class="side-nav-title">Finance Tickets</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarFinanceTickets"
                   aria-expanded="{{ request()->routeIs('tickets.finance.*') ? 'true' : 'false' }}"
                   aria-controls="sidebarFinanceTickets"
                   class="side-nav-link {{ request()->routeIs('tickets.finance.*') ? '' : 'collapsed' }}">
                    <span class="menu-icon"><i data-lucide="file-text"></i></span>
                    <span class="menu-text">Finance Tickets</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('tickets.finance.*') ? 'show' : '' }}" id="sidebarFinanceTickets">
                    <ul class="sub-menu">
                        @if(Auth::user()->hasPermission('create-finance-ticket'))
                        <li class="side-nav-item">
                            <a href="{{ route('tickets.finance.create') }}"
                               class="side-nav-link {{ request()->routeIs('tickets.finance.create') ? 'active' : '' }}">
                                <span class="menu-text">Create New</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->hasPermission('view-finance-ticket'))
                        <li class="side-nav-item">
                            <a href="{{ route('tickets.finance.index') }}"
                               class="side-nav-link {{ request()->routeIs('tickets.finance.index') && !request('status') ? 'active' : '' }}">
                                <span class="menu-text">All Tickets</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('tickets.finance.index', ['status' => 'draft']) }}"
                               class="side-nav-link {{ request('status') === 'draft' ? 'active' : '' }}">
                                <span class="menu-text">Draft</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('tickets.finance.index', ['status' => 'posted']) }}"
                               class="side-nav-link {{ request('status') === 'posted' ? 'active' : '' }}">
                                <span class="menu-text">Posted</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            {{-- Fuel Sales Section --}}
            @if(Auth::user()->hasPermission('view-fuel-ticket') || Auth::user()->hasPermission('create-fuel-ticket'))
            <li class="side-nav-title">Fuel Sales</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarFuelSales"
                   aria-expanded="{{ request()->routeIs('tickets.fuel.*') ? 'true' : 'false' }}"
                   aria-controls="sidebarFuelSales"
                   class="side-nav-link {{ request()->routeIs('tickets.fuel.*') ? '' : 'collapsed' }}">
                    <span class="menu-icon"><i data-lucide="fuel"></i></span>
                    <span class="menu-text">Fuel Sales</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('tickets.fuel.*') ? 'show' : '' }}" id="sidebarFuelSales">
                    <ul class="sub-menu">
                        @if(Auth::user()->hasPermission('create-fuel-ticket'))
                        <li class="side-nav-item">
                            <a href="{{ route('tickets.fuel.create') }}"
                               class="side-nav-link {{ request()->routeIs('tickets.fuel.create') ? 'active' : '' }}">
                                <span class="menu-text">Create New</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->hasPermission('view-fuel-ticket'))
                        <li class="side-nav-item">
                            <a href="{{ route('tickets.fuel.index') }}"
                               class="side-nav-link {{ request()->routeIs('tickets.fuel.index') ? 'active' : '' }}">
                                <span class="menu-text">All Sales</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            {{-- Masters Section --}}
            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
            <li class="side-nav-title">Masters</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMasters"
                   aria-expanded="{{ request()->routeIs('masters.*') ? 'true' : 'false' }}"
                   aria-controls="sidebarMasters"
                   class="side-nav-link {{ request()->routeIs('masters.*') ? '' : 'collapsed' }}">
                    <span class="menu-icon"><i data-lucide="settings"></i></span>
                    <span class="menu-text">Master Data</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('masters.*') ? 'show' : '' }}" id="sidebarMasters">
                    <ul class="sub-menu">
                        @if(Auth::user()->hasPermission('manage-departments'))
                        <li class="side-nav-item">
                            <a href="{{ route('masters.departments.index') }}"
                               class="side-nav-link {{ request()->routeIs('masters.departments.*') ? 'active' : '' }}">
                                <span class="menu-text">Departments</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->hasPermission('manage-users'))
                        <li class="side-nav-item">
                            <a href="{{ route('masters.users.index') }}"
                               class="side-nav-link {{ request()->routeIs('masters.users.*') ? 'active' : '' }}">
                                <span class="menu-text">Users</span>
                            </a>
                        </li>
                        @endif

                        <li class="side-nav-item">
                            <a href="{{ route('masters.clients.index') }}"
                               class="side-nav-link {{ request()->routeIs('masters.clients.*') ? 'active' : '' }}">
                                <span class="menu-text">Clients</span>
                            </a>
                        </li>

                        @if(Auth::user()->isAdmin())
                        <li class="side-nav-item">
                            <a href="{{ route('masters.cost-centers.index') }}"
                               class="side-nav-link {{ request()->routeIs('masters.cost-centers.*') ? 'active' : '' }}">
                                <span class="menu-text">Cost Centers</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('masters.service-types.index') }}"
                               class="side-nav-link {{ request()->routeIs('masters.service-types.*') ? 'active' : '' }}">
                                <span class="menu-text">Service Types</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('masters.uom.index') }}"
                               class="side-nav-link {{ request()->routeIs('masters.uom.*') ? 'active' : '' }}">
                                <span class="menu-text">UOM</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            {{-- Reports Section --}}
            @if(Auth::user()->hasPermission('view-reports'))
            <li class="side-nav-title">Reports</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReports"
                   aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}"
                   aria-controls="sidebarReports"
                   class="side-nav-link {{ request()->routeIs('reports.*') ? '' : 'collapsed' }}">
                    <span class="menu-icon"><i data-lucide="bar-chart-2"></i></span>
                    <span class="menu-text">Reports</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="sidebarReports">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('reports.finance') }}"
                               class="side-nav-link {{ request()->routeIs('reports.finance') ? 'active' : '' }}">
                                <span class="menu-text">Finance Tickets</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('reports.fuel') }}"
                               class="side-nav-link {{ request()->routeIs('reports.fuel') ? 'active' : '' }}">
                                <span class="menu-text">Fuel Sales</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('reports.department') }}"
                               class="side-nav-link {{ request()->routeIs('reports.department') ? 'active' : '' }}">
                                <span class="menu-text">Department-wise</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            {{-- Settings Section (Super Admin Only) --}}
            @if(Auth::user()->hasPermission('manage-settings'))
            <li class="side-nav-title">System</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSettings"
                   aria-expanded="{{ request()->routeIs('settings.*') ? 'true' : 'false' }}"
                   aria-controls="sidebarSettings"
                   class="side-nav-link {{ request()->routeIs('settings.*') ? '' : 'collapsed' }}">
                    <span class="menu-icon"><i data-lucide="tool"></i></span>
                    <span class="menu-text">Settings</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('settings.*') ? 'show' : '' }}" id="sidebarSettings">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('settings.roles') }}"
                               class="side-nav-link {{ request()->routeIs('settings.roles') ? 'active' : '' }}">
                                <span class="menu-text">Roles & Permissions</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('settings.general') }}"
                               class="side-nav-link {{ request()->routeIs('settings.general') ? 'active' : '' }}">
                                <span class="menu-text">General</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('settings.backup') }}"
                               class="side-nav-link {{ request()->routeIs('settings.backup') ? 'active' : '' }}">
                                <span class="menu-text">Backup & Restore</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

        </ul>
    </div>
</div>
