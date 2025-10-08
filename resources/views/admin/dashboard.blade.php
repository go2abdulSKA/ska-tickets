<x-app-layout>
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="m-0 fs-xl fw-bold">Dashboard</h4>
        </div>

        <div class="text-end">
            <ol class="py-0 m-0 breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">Total Tickets</span>
                            {{-- <h3 class="mb-0">{{ $stats['total_tickets'] }}</h3> --}}
                            <h3 class="mb-0">10</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i data-lucide="file-text" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">Draft Tickets</span>
                            {{-- <h3 class="mb-0">{{ $stats['draft_tickets'] }}</h3> --}}
                            <h3 class="mb-0">20</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i data-lucide="edit" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">Posted Tickets</span>
                            {{-- <h3 class="mb-0">{{ $stats['posted_tickets'] }}</h3> --}}
                            <h3 class="mb-0">2</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i data-lucide="check-circle" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted text-uppercase fs-12 fw-bold">This Month</span>
                            {{-- <h3 class="mb-0">{{ $stats['this_month_tickets'] }}</h3> --}}
                            <h3 class="mb-0">14</h3>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-info">
                                    <i data-lucide="calendar" class="icon-dual-light"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
