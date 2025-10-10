{{-- resources/views/livewire/tickets/finance/index.blade.php --}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Finance Tickets' page='Tickets' subpage='Finance' />

    {{-- Flash Messages --}}
    <x-ui.flash-msg />

@php
    // dd(Livewire::isDiscoverable('tickets.finance.view-finance-ticket'));
@endphp

    {{-- Statistics Cards --}}
    <div class="mb-3 row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Total Tickets</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-file-document text-primary fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Draft</h6>
                            <h3 class="mb-0">{{ $stats['draft'] }}</h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-file-edit text-warning fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Posted</h6>
                            <h3 class="mb-0">{{ $stats['posted'] }}</h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-check-circle text-success fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-2 text-muted text-uppercase">Total Amount</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_amount'], 2) }}</h3>
                        </div>
                        <div>
                            <div class="avatar-sm rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-cash-multiple text-info fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">

                {{-- Card Header with Filters and Actions --}}
                <div class="card-header border-light">
                    <div class="row g-3">

                        {{-- Top Row: Search and Create Button --}}
                        <div class="col-12">
                            <div class="flex-wrap gap-2 d-flex justify-content-between align-items-center">
                                {{-- Search Input --}}
                                <div class="app-search" style="min-width: 300px;">
                                    <input wire:model.live.debounce.300ms="search"
                                           type="search"
                                           class="form-control"
                                           placeholder="Search by ticket no, client, project...">
                                    <i data-lucide="search" class="app-search-icon text-muted"></i>
                                </div>

                                {{-- Create Button --}}
                                <a href="{{ route('tickets.finance.create') }}" class="btn btn-primary">
                                    <i data-lucide="plus" class="fs-sm me-2"></i> Create Ticket
                                </a>
                            </div>
                        </div>

                        {{-- Bottom Row: Filters --}}
                        <div class="col-12">
                            <div class="flex-wrap gap-2 d-flex align-items-center">

                                {{-- Status Filter --}}
                                <div class="flex-shrink-0">
                                    <select wire:model.live="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        @foreach(\App\Enums\TicketStatus::cases() as $status)
                                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Department Filter --}}
                                @if(Auth::user()->isSuperAdmin() || Auth::user()->departments->count() > 1)
                                    <div class="flex-shrink-0">
                                        <select wire:model.live="departmentFilter" class="form-select form-select-sm">
                                            <option value="">All Departments</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                {{-- Client Type Filter --}}
                                <div class="flex-shrink-0">
                                    <select wire:model.live="clientTypeFilter" class="form-select form-select-sm">
                                        <option value="">All Types</option>
                                        @foreach(\App\Enums\ClientType::cases() as $type)
                                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Date From --}}
                                <div class="flex-shrink-0">
                                    <input type="date"
                                           wire:model.live="dateFrom"
                                           class="form-control form-control-sm"
                                           placeholder="From Date">
                                </div>

                                {{-- Date To --}}
                                <div class="flex-shrink-0">
                                    <input type="date"
                                           wire:model.live="dateTo"
                                           class="form-control form-control-sm"
                                           placeholder="To Date">
                                </div>

                                {{-- Clear Filters Button --}}
                                <button type="button"
                                        wire:click="clearFilters"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Clear Filters">
                                    <i class="mdi mdi-filter-remove"></i> Clear
                                </button>

                                {{-- Records Per Page --}}
                                <div class="flex-shrink-0 ms-auto">
                                    <select wire:model.live="perPage" class="form-select form-select-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                                {{-- Bulk Actions --}}
                                @if(count($selectedItems) > 0)
                                    <button type="button"
                                            wire:click="confirmBulkDelete"
                                            class="btn btn-sm btn-danger">
                                        <i class="mdi mdi-delete me-1"></i> Delete Selected ({{ count($selectedItems) }})
                                    </button>
                                @endif

                                {{-- Export Buttons --}}
                                <div class="flex-shrink-0 btn-group">
                                    <button type="button"
                                            wire:click="exportExcel"
                                            class="btn btn-sm btn-outline-success"
                                            title="Export to Excel">
                                        <i class="mdi mdi-file-excel"></i>
                                    </button>
                                    <button type="button"
                                            wire:click="exportPDF"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Export to PDF">
                                        <i class="mdi mdi-file-pdf-box"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Desktop Table View --}}
                <div class="table-responsive d-none d-lg-block">
                    <table class="table mb-0 table-custom table-centered table-hover w-100">
                        {{-- Table Head --}}
                        <thead class="align-middle bg-opacity-25 bg-light">
                            <tr class="text-uppercase" style="font-size: 0.75rem;">
                                {{-- Select All Checkbox --}}
                                <th class="ps-3" style="width: 40px;">
                                    <input wire:model.live="selectAll"
                                        class="form-check-input"
                                        type="checkbox">
                                </th>

                                {{-- Ticket No (Sortable) --}}
                                <th wire:click="sortBy('ticket_no')" style="cursor: pointer; width: 130px;">
                                    Ticket No
                                    @if($sortField === 'ticket_no')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- Date (Sortable) --}}
                                <th wire:click="sortBy('ticket_date')" style="cursor: pointer; width: 110px;">
                                    Date
                                    @if($sortField === 'ticket_date')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- Customer --}}
                                <th>Customer</th>

                                {{-- Project --}}
                                <th style="width: 120px;">Project</th>

                                {{-- Amount (Sortable) --}}
                                <th wire:click="sortBy('total_amount')"
                                    class="text-end"
                                    style="cursor: pointer; width: 120px;">
                                    Amount
                                    @if($sortField === 'total_amount')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- Status --}}
                                <th class="text-center" style="width: 100px;">Status</th>

                                {{-- Actions --}}
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    {{-- Checkbox --}}
                                    <td class="ps-3">
                                        <input wire:model.live="selectedItems"
                                               value="{{ $ticket->id }}"
                                               class="form-check-input"
                                               type="checkbox">
                                    </td>

                                    {{-- Ticket No (Clickable) --}}
                                    <td>
                                        <a href="javascript:void(0);"
                                           wire:click="view({{ $ticket->id }})"
                                           class="text-decoration-none">
                                            <strong class="text-primary">{{ $ticket->ticket_no }}</strong>
                                        </a>
                                    </td>

                                    {{-- Date --}}
                                    <td>
                                        <span class="text-muted">{{ $ticket->ticket_date->format('d M, Y') }}</span>
                                    </td>

                                    {{-- Customer --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $ticket->customer_name }}</span>
                                            <small class="text-muted">
                                                <span class="badge badge-soft-{{ $ticket->client_type->value === 'client' ? 'primary' : 'info' }} badge-sm">
                                                    {{ $ticket->client_type->label() }}
                                                </span>
                                            </small>
                                        </div>
                                    </td>

                                    {{-- Project --}}
                                    <td>
                                        @if($ticket->project_code)
                                            <span class="badge badge-soft-secondary">{{ $ticket->project_code }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Amount --}}
                                    <td class="text-end">
                                        <strong class="text-primary">
                                            {{ $ticket->currency->symbol() }}{{ number_format($ticket->total_amount, 2) }}
                                        </strong>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        <span class="badge {{ $ticket->status->badgeClass() }}">
                                            {{ $ticket->status->label() }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">
                                        <div class="gap-1 d-flex justify-content-center">
                                            {{-- View Button --}}
                                            <button wire:click="view({{ $ticket->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-light btn-icon btn-sm"
                                                    title="View Details">
                                                <i class="mdi mdi-eye"></i>
                                            </button>

                                            {{-- Edit Button (Only for drafts) --}}
                                            @if($ticket->canEdit())
                                                <a href="{{ route('tickets.finance.edit', $ticket->id) }}"
                                                   class="btn btn-light btn-icon btn-sm"
                                                   title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            @endif

                                            {{-- Duplicate Button --}}
                                            <a href="{{ route('tickets.finance.duplicate', $ticket->id) }}"
                                               class="btn btn-light btn-icon btn-sm"
                                               title="Duplicate">
                                                <i class="mdi mdi-content-copy"></i>
                                            </a>

                                            {{-- Delete Button (Only for drafts) --}}
                                            @if($ticket->canDelete())
                                                <button wire:click="confirmDelete({{ $ticket->id }})"
                                                        class="btn btn-danger btn-icon btn-sm"
                                                        title="Delete">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="8" class="py-5 text-center">
                                        <div class="text-muted">
                                            <i class="mdi mdi-file-document-outline" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2 mb-0">No finance tickets found</p>
                                            <a href="{{ route('tickets.finance.create') }}" class="mt-2 btn btn-sm btn-primary">
                                                <i class="mdi mdi-plus me-1"></i> Create Your First Ticket
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="p-3 d-lg-none">
                    @forelse($tickets as $ticket)
                        @include('livewire.tickets.finance.partials.ticket-card-mobile')
                    @empty
                        <div class="py-5 text-center text-muted">
                            <i class="mdi mdi-file-document-outline" style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="mt-2 mb-0">No finance tickets found</p>
                            <a href="{{ route('tickets.finance.create') }}" class="mt-2 btn btn-sm btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Create Your First Ticket
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Card Footer with Pagination --}}
                <div class="card-footer border-top bg-light">
                    <div class="flex-wrap gap-2 d-flex justify-content-between align-items-center">
                        {{-- Pagination Info --}}
                        <div class="text-muted small">
                            Showing
                            <span class="fw-semibold">{{ $tickets->firstItem() ?? 0 }}</span> to
                            <span class="fw-semibold">{{ $tickets->lastItem() ?? 0 }}</span> of
                            <span class="fw-semibold">{{ $tickets->total() }}</span> tickets
                        </div>

                        {{-- Pagination Links --}}
                        <div>
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals and Offcanvas --}}

    {{-- View Offcanvas --}}
    @if($showViewOffcanvas && $viewTicketId)
        @livewire('tickets.finance.view-finance-ticket', ['ticketId' => $viewTicketId], key('view-'.$viewTicketId))
    @endif

    {{-- Delete Modal --}}
    @if($showDeleteModal)
        @include('livewire.tickets.finance.partials.delete-modal')
    @endif

    {{-- Bulk Delete Modal --}}
    @if($showBulkDeleteModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="pb-0 border-0 modal-header">
                        <h5 class="modal-title">Confirm Bulk Deletion</h5>
                        <button type="button" class="btn-close" wire:click="cancelBulkDelete"></button>
                    </div>
                    <div class="p-4 text-center modal-body">
                        <div class="mb-3">
                            <i class="mdi mdi-alert-triangle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="mb-2">Are you sure?</h4>
                        <p class="mb-3 text-muted">
                            You are about to delete <strong class="text-danger">{{ count($selectedItems) }} ticket(s)</strong>
                        </p>
                        <div class="alert alert-warning text-start">
                            <small><strong>Note:</strong> Only draft tickets will be deleted.</small>
                        </div>
                        <div class="gap-2 d-grid">
                            <button type="button" wire:click="bulkDelete" class="btn btn-danger" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="bulkDelete">
                                    <i class="mdi mdi-delete me-1"></i> Yes, Delete Selected!
                                </span>
                                <span wire:loading wire:target="bulkDelete">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Deleting...
                                </span>
                            </button>
                            <button type="button" wire:click="cancelBulkDelete" class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Scripts --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Reinitialize icons after Livewire updates
    document.addEventListener('livewire:update', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Close offcanvas on event
    Livewire.on('close-offcanvas', () => {
        @this.set('showViewOffcanvas', false);
        @this.set('viewTicketId', null);
    });
</script>
@endpush
