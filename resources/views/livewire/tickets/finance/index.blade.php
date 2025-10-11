{{-- resources/views/livewire/tickets/finance/index.blade.php --}}

<div>
    {{-- Page Header --}}
    <x-ui.page-header title='Finance Tickets' page='Tickets' subpage='Finance' />

    {{-- Flash Messages --}}
    <x-ui.flash-msg />

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
                            <div
                                class="avatar-sm rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
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
                            <div
                                class="avatar-sm rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
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
                            <div
                                class="avatar-sm rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
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
                            <div
                                class="avatar-sm rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
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

                        {{-- Top Row: Search, My Drafts Toggle, and Create Button --}}
                        <div class="col-12">
                            <div class="flex-wrap gap-2 d-flex justify-content-between align-items-center">
                                <div class="gap-2 d-flex align-items-center">
                                    {{-- Search Input --}}
                                    <div class="app-search" style="min-width: 300px;">
                                        <input wire:model.live.debounce.300ms="search" type="search"
                                            class="form-control" placeholder="Search by ticket no, client, project...">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" data-lucide="search"
                                            class="lucide lucide-search app-search-icon text-muted">
                                            <path d="m21 21-4.34-4.34"></path>
                                            <circle cx="11" cy="11" r="8"></circle>
                                        </svg>
                                    </div>

                                    {{-- OPTION C: My Drafts Toggle Button --}}
                                    <button type="button" wire:click="toggleMyDrafts"
                                        class="btn btn-sm {{ $showDraftsOnly ? 'btn-primary' : 'btn-outline-primary' }}"
                                        title="Show only my drafts">
                                        <i class="mdi mdi-file-edit me-1"></i>
                                        My Drafts
                                        @if ($showDraftsOnly)
                                            <span class="bg-white badge text-primary ms-1">ON</span>
                                        @endif
                                    </button>
                                </div>
                                <div>
                                    {{-- OPTION C: Bulk Delete (Max 5 Drafts) --}}
                                    @if (count($selectedItems) > 0)
                                        <button type="button" wire:click="confirmBulkDelete"
                                            class="btn btm-sm btn-danger">
                                            <i class="mdi mdi-delete me-1"></i>
                                            Delete Selected ({{ count($selectedItems) }})
                                            @if (count($selectedItems) > 5)
                                                <span class="bg-white badge text-danger ms-1">Max 5!</span>
                                            @endif
                                        </button>
                                    @endif

                                    {{-- Create Button --}}
                                    <a href="{{ route('tickets.finance.create') }}" class="btn btn-secondary"><i
                                            class="ti ti-plus fs-lg me-1"></i> Create Ticket</a>
                                </div>
                            </div>
                        </div>

                        {{-- Bottom Row: Filters --}}
                        <div class="col-12">
                            <div class="flex-wrap gap-2 d-flex align-items-center">

                                {{-- Status Filter --}}
                                <div class="flex-shrink-0">
                                    <select wire:model.live="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        @foreach (\App\Enums\TicketStatus::cases() as $status)
                                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Department Filter --}}
                                @if (Auth::user()->isSuperAdmin() || Auth::user()->departments->count() > 1)
                                    <div class="flex-shrink-0">
                                        <select wire:model.live="departmentFilter" class="form-select form-select-sm">
                                            <option value="">All Departments</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                {{-- Client Type Filter --}}
                                <div class="flex-shrink-0">
                                    <select wire:model.live="clientTypeFilter" class="form-select form-select-sm">
                                        <option value="">All Types</option>
                                        @foreach (\App\Enums\ClientType::cases() as $type)
                                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Date From --}}
                                <div class="flex-shrink-0">
                                    <input type="date" wire:model.live="dateFrom"
                                        class="form-control form-control-sm" placeholder="From Date">
                                </div>

                                {{-- Date To --}}
                                <div class="flex-shrink-0">
                                    <input type="date" wire:model.live="dateTo"
                                        class="form-control form-control-sm" placeholder="To Date">
                                </div>

                                {{-- Clear Filters Button --}}
                                <button type="button" wire:click="clearFilters"
                                class="btn btn-icon btn-sm btn-outline-secondary" title="Clear Filters">
                                <i class="ti ti-filter-off"></i>
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

                                {{-- OPTION C: Bulk Delete (Max 5 Drafts) --}}
                                {{-- @if (count($selectedItems) > 0)
                                    <button type="button" wire:click="confirmBulkDelete"
                                        class="btn btn-sm btn-danger">
                                        <i class="mdi mdi-delete me-1"></i>
                                        Delete Selected ({{ count($selectedItems) }})
                                        @if (count($selectedItems) > 5)
                                            <span class="bg-white badge text-danger ms-1">Max 5!</span>
                                        @endif
                                    </button>
                                @endif --}}

                                {{-- Export Buttons --}}
                                <div class="flex-shrink-0 btn-group">
                                    <button type="button" wire:click="exportExcel" {{-- class="btn btn-sm btn-outline-success" title="Export to Excel"> --}}
                                        class="btn btn-outline-success btn-icon btn-sm" title="Export to Excel">
                                        {{-- <i class="mdi mdi-file-excel"></i> --}}
                                        <i class="ti ti-file-type-xls"></i>
                                    </button>
                                    <button type="button" wire:click="exportPDF"
                                        class="btn btn-outline-danger btn-icon btn-sm" title="Export to PDF">
                                        <i class="ti ti-file-type-pdf"></i>
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
                                {{-- Select All Checkbox (Only for drafts) --}}
                                <th class="ps-3" style="width: 40px;">
                                    <input wire:model.live="selectAll" class="form-check-input" type="checkbox"
                                        title="Select all drafts">
                                </th>

                                {{-- Ticket No (Sortable) --}}
                                <th wire:click="sortBy('ticket_no')" style="cursor: pointer; width: 150px;">
                                    Ticket No
                                    @if ($sortField === 'ticket_no')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- Date (Sortable) --}}
                                <th wire:click="sortBy('ticket_date')" style="cursor: pointer; width: 110px;">
                                    Date
                                    @if ($sortField === 'ticket_date')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- Customer --}}
                                <th>Customer</th>

                                {{-- Project --}}
                                <th style="width: 120px;">Project</th>

                                {{-- Amount (Sortable) --}}
                                <th wire:click="sortBy('total_amount')" class="text-end"
                                    style="cursor: pointer; width: 120px;">
                                    Amount
                                    @if ($sortField === 'total_amount')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>

                                {{-- Status --}}
                                <th class="text-center" style="width: 100px;">Status</th>

                                {{-- Actions --}}
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    {{-- Checkbox (Only for drafts) --}}
                                    <td class="ps-3">
                                        @if ($ticket->status === \App\Enums\TicketStatus::DRAFT)
                                            <input wire:model.live="selectedItems" value="{{ $ticket->id }}"
                                                class="form-check-input" type="checkbox">
                                        @endif
                                    </td>

                                    {{-- OPTION C: Ticket No with DRAFT badge --}}
                                    <td>
                                        <a href="javascript:void(0);" wire:click="view({{ $ticket->id }})"
                                            class="text-decoration-none">
                                            <strong
                                                class="{{ str_starts_with($ticket->ticket_no, 'DRAFT-') ? 'text-warning' : 'text-primary' }}">
                                                {{ $ticket->ticket_no }}
                                            </strong>
                                        </a>
                                        {{-- Show DRAFT badge for draft tickets --}}
                                        @if (str_starts_with($ticket->ticket_no, 'DRAFT-'))
                                            <br>
                                            <small class="badge badge-soft-warning badge-sm">DRAFT ID</small>
                                        @endif
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
                                                <span
                                                    class="badge badge-soft-{{ $ticket->client_type->value === 'client' ? 'primary' : 'info' }} badge-sm">
                                                    {{ $ticket->client_type->label() }}
                                                </span>
                                            </small>
                                        </div>
                                    </td>

                                    {{-- Project --}}
                                    <td>
                                        @if ($ticket->project_code)
                                            <span
                                                class="badge badge-soft-secondary">{{ $ticket->project_code }}</span>
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

                                    {{-- OPTION C: Updated Actions --}}

                                    <td class="text-center">
                                        <div class="gap-1 d-flex justify-content-center">
                                            {{-- View Button --}}
                                            <button wire:click="view({{ $ticket->id }})"
                                                wire:loading.attr="disabled" {{-- class="btn btn-light btn-icon btn-sm" --}}
                                                class="btn btn-outline-primary btn-icon btn-sm" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </button>

                                            {{-- Edit Button (Only for drafts) --}}
                                            @if ($ticket->canEdit())
                                                <a href="{{ route('tickets.finance.edit', $ticket->id) }}"
                                                    class="btn btn-outline-primary btn-icon btn-sm" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endif

                                            {{-- Duplicate Button --}}
                                            <a href="{{ route('tickets.finance.duplicate', $ticket->id) }}"
                                                class="btn btn-outline-secondary btn-icon btn-sm" title="Duplicate">
                                                <i class="ti ti-copy"></i>
                                            </a>

                                            {{-- OPTION C: Delete Button (Only for DRAFTS) --}}
                                            @if ($ticket->status === \App\Enums\TicketStatus::DRAFT)
                                                <button wire:click="confirmDelete({{ $ticket->id }})"
                                                    class="btn btn-danger btn-icon btn-sm" title="Delete Draft">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @endif

                                            {{-- OPTION C: Cancel Button (For POSTED tickets) --}}
                                            @if ($ticket->status === \App\Enums\TicketStatus::POSTED && Auth::user()->isAdmin())
                                                <button wire:click="confirmCancel({{ $ticket->id }})"
                                                    class="btn btn-warning btn-icon btn-sm" title="Cancel Ticket">
                                                    <i class="ti ti-ban"></i>
                                                </button>
                                            @endif

                                            {{-- OPTION C: Unpost Button (Super Admin only) --}}
                                            @if ($ticket->status === \App\Enums\TicketStatus::POSTED && Auth::user()->isSuperAdmin())
                                                <button wire:click="confirmUnpost({{ $ticket->id }})"
                                                    class="btn btn-outline-secondary btn-icon btn-sm"
                                                    title="Unpost Ticket">
                                                    <i class="ti ti-rotate-ccw"></i>
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
                                            <i class="mdi mdi-file-document-outline"
                                                style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2 mb-0">
                                                @if ($showDraftsOnly)
                                                    No draft tickets found
                                                @else
                                                    No finance tickets found
                                                @endif
                                            </p>
                                            <a href="{{ route('tickets.finance.create') }}"
                                                class="mt-2 btn btn-sm btn-primary">
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

    {{-- OPTION C: Updated Modals --}}

    {{-- View Offcanvas --}}
    @if ($showViewOffcanvas && $viewTicketId)
        @livewire('tickets.finance.view-finance-ticket', ['ticketId' => $viewTicketId], key('view-' . $viewTicketId))
    @endif

    {{-- OPTION C: Delete Draft Modal --}}
    @if ($showDeleteModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="text-white modal-header bg-danger">
                        <h5 class="modal-title">
                            <i class="mdi mdi-delete-alert me-1"></i> Delete Draft Ticket
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <i class="mdi mdi-alert-circle text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mb-3 text-center">Are you sure?</h5>
                        <p class="mb-3 text-center">
                            You are about to delete draft ticket:
                            <br>
                            <strong class="text-danger">{{ $deleteTicketNo }}</strong>
                        </p>
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline me-1"></i>
                            <strong>OPTION C:</strong> Deleting drafts does not affect sequential numbering.
                            Only posted tickets get sequential numbers (C/A-00001, C/A-00002, etc.)
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="cancelDelete">
                            Cancel
                        </button>
                        <button type="button" wire:click="delete" class="btn btn-danger"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="delete">
                                <i class="mdi mdi-delete me-1"></i> Yes, Delete Draft
                            </span>
                            <span wire:loading wire:target="delete">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Deleting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- OPTION C: Cancel Posted Ticket Modal --}}
    @if ($showCancelModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="text-white modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="mdi mdi-cancel me-1"></i> Cancel Ticket
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closeCancelModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-outline me-1"></i>
                            <strong>Important:</strong> Cancelled tickets keep their number in the sequence for audit
                            purposes.
                        </div>

                        <div class="mb-3">
                            <label for="cancelReason" class="form-label">
                                Reason for Cancellation <span class="text-danger">*</span>
                            </label>
                            <textarea wire:model="cancelReason" class="form-control @error('cancelReason') is-invalid @enderror"
                                id="cancelReason" rows="4" placeholder="Please provide a detailed reason for cancelling this ticket..."
                                maxlength="500"></textarea>
                            @error('cancelReason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 10 characters required</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="closeCancelModal">
                            Close
                        </button>
                        <button type="button" wire:click="cancelTicket" class="btn btn-warning"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="cancelTicket">
                                <i class="mdi mdi-cancel me-1"></i> Cancel Ticket
                            </span>
                            <span wire:loading wire:target="cancelTicket">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- OPTION C: Bulk Delete Modal (Max 5) --}}
    @if ($showBulkDeleteModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="text-white modal-header bg-danger">
                        <h5 class="modal-title">Bulk Delete Draft Tickets</h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="cancelBulkDelete"></button>
                    </div>
                    <div class="p-4 text-center modal-body">
                        <div class="mb-3">
                            <i class="mdi mdi-delete-alert text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="mb-2">Delete {{ count($selectedItems) }} Draft Ticket(s)?</h4>
                        <p class="mb-3 text-muted">
                            You are about to delete <strong class="text-danger">{{ count($selectedItems) }} draft
                                ticket(s)</strong>
                        </p>
                        <div class="alert alert-info text-start">
                            <small>
                                <i class="mdi mdi-information-outline me-1"></i>
                                <strong>OPTION C:</strong> Draft tickets have DRAFT-xxx IDs.
                                Deleting them does not affect sequential numbering.
                                <br>
                                <strong>Limit:</strong> Maximum 5 drafts can be deleted at once.
                            </small>
                        </div>
                        <div class="gap-2 d-grid">
                            <button type="button" wire:click="bulkDelete" class="btn btn-danger"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="bulkDelete">
                                    <i class="mdi mdi-delete me-1"></i> Yes, Delete All Selected!
                                </span>
                                <span wire:loading wire:target="bulkDelete">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Deleting...
                                </span>
                            </button>
                            <button type="button" wire:click="cancelBulkDelete"
                                class="btn btn-light">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- OPTION C: Unpost Modal (Super Admin Only) --}}
    @if ($showUnpostModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="text-white modal-header bg-secondary">
                        <h5 class="modal-title">
                            <i class="mdi mdi-backup-restore me-1"></i> Unpost Ticket
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closeUnpostModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="mdi mdi-shield-alert me-1"></i>
                            <strong>Super Admin Action:</strong> This will revert the ticket to DRAFT status but keep
                            its sequential number.
                        </div>
                        <p>Are you sure you want to unpost this ticket?</p>
                        <p class="text-muted small">
                            This action should only be used in emergency situations for corrections.
                            The ticket number will be preserved for audit purposes.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="closeUnpostModal">
                            Cancel
                        </button>

                        <button type="button" wire:click="unpostTicket" class="btn btn-secondary"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="unpostTicket">
                                <i class="mdi mdi-backup-restore me-1"></i> Yes, Unpost Ticket
                            </span>
                            <span wire:loading wire:target="unpostTicket">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Processing...
                            </span>
                        </button>
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

        // OPTION C: Duplicate confirmation
        Livewire.on('confirm-duplicate', (data) => {
            if (confirm(`This will create a new DRAFT from posted ticket ${data[0].ticketNo}. Continue?`)) {
                window.location.href = `/tickets/finance/${data[0].ticketId}/duplicate`;
            }
        });
    </script>
@endpush
