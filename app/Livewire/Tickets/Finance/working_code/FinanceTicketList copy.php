<?php
// app/Livewire/Tickets/Finance/FinanceTicketList.php

namespace App\Livewire\Tickets\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\TicketMaster;
use App\Models\Department;
use App\Models\ActivityLog;
use App\Models\TicketStatusHistory;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class FinanceTicketList extends Component
{
    use WithPagination;

    // ==========================================
    // Properties
    // ==========================================

    // Search & Filters
    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $departmentFilter = '';
    public $clientTypeFilter = '';
    public $perPage = 10;

    // OPTION C: New filter for drafts
    public $showDraftsOnly = false;

    // Sorting
    public $sortField = 'ticket_date';
    public $sortDirection = 'desc';

    // Selection for bulk actions
    public $selectedItems = [];
    public $selectAll = false;

    // Modals
    public $showViewOffcanvas = false;
    public $showDeleteModal = false; // For DRAFTS only
    public $showCancelModal = false; // For POSTED tickets
    public $showBulkDeleteModal = false;
    public $showUnpostModal = false; // For Super Admins

    // Modal data
    public $viewTicketId = null;
    public $deleteTicketId = null;
    public $deleteTicketNo = '';
    public $cancelTicketId = null;
    public $cancelReason = '';
    public $unpostTicketId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'showDraftsOnly' => ['except' => false],
    ];

    protected $paginationTheme = 'bootstrap';

    // ==========================================
    // Lifecycle Hooks
    // ==========================================

    public function mount()
    {
        // Set default date range (current month)
        if (!$this->dateFrom) {
            $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (!$this->dateTo) {
            $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
    }

    // Update hooks
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedShowDraftsOnly()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all DRAFT tickets only (for bulk delete)
            $this->selectedItems = $this->getTicketsProperty()
                ->filter(fn($ticket) => $ticket->status === TicketStatus::DRAFT)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    // ==========================================
    // Computed Properties
    // ==========================================

    /**
     * Get tickets with filters applied
     */
    public function getTicketsProperty()
    {
        $query = TicketMaster::query()
            ->with([
                'department:id,department,prefix',
                'client:id,client_name,company_name',
                'costCenter:id,code,name',
                'user:id,name',
                'serviceType:id,service_type',
                'transactions:id,ticket_id,description,qty,total_cost'
            ])
            ->where('ticket_type', TicketType::FINANCE);

        // OPTION C: Filter for "My Drafts" view
        if ($this->showDraftsOnly) {
            $query->where('status', TicketStatus::DRAFT)
                  ->where('user_id', Auth::id());
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ticket_no', 'like', "%{$this->search}%")
                    ->orWhere('project_code', 'like', "%{$this->search}%")
                    ->orWhere('ref_no', 'like', "%{$this->search}%")
                    ->orWhere('contract_no', 'like', "%{$this->search}%")
                    ->orWhereHas('client', function ($q) {
                        $q->where('client_name', 'like', "%{$this->search}%")
                          ->orWhere('company_name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('costCenter', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%")
                          ->orWhere('code', 'like', "%{$this->search}%");
                    });
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply department filter
        if ($this->departmentFilter) {
            $query->where('department_id', $this->departmentFilter);
        }

        // Apply date range filter
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('ticket_date', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay()
            ]);
        }

        // Apply client type filter
        if ($this->clientTypeFilter) {
            $query->where('client_type', $this->clientTypeFilter);
        }

        // User access control
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            $userDeptIds = $user->getDepartmentIds();
            $query->whereIn('department_id', $userDeptIds);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /**
     * Get statistics for dashboard cards
     */
    public function getStatsProperty()
    {
        $query = TicketMaster::where('ticket_type', TicketType::FINANCE);

        // Apply date range to stats
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('ticket_date', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay()
            ]);
        }

        // User access control
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            $query->whereIn('department_id', $user->getDepartmentIds());
        }

        return [
            'total' => $query->count(),
            'draft' => $query->clone()->where('status', TicketStatus::DRAFT)->count(),
            'posted' => $query->clone()->where('status', TicketStatus::POSTED)->count(),
            'cancelled' => $query->clone()->where('status', TicketStatus::CANCELLED)->count(),
            'total_amount' => $query->clone()->where('status', TicketStatus::POSTED)->sum('total_amount'),
        ];
    }

    /**
     * Get departments for filter dropdown
     */
    public function getDepartmentsProperty()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return Department::active()->get();
        }

        return Department::active()
            ->whereIn('id', $user->getDepartmentIds())
            ->get();
    }

    // ==========================================
    // Actions
    // ==========================================

    /**
     * Sort by field
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->reset([
            'search',
            'statusFilter',
            'dateFrom',
            'dateTo',
            'departmentFilter',
            'clientTypeFilter',
            'showDraftsOnly'
        ]);

        // Reset to current month
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');

        $this->resetPage();
    }

    /**
     * OPTION C: Toggle "My Drafts" view
     */
    public function toggleMyDrafts()
    {
        $this->showDraftsOnly = !$this->showDraftsOnly;
        $this->resetPage();
    }

    /**
     * View ticket details in offcanvas
     */
    public function view($ticketId)
    {
        $this->viewTicketId = $ticketId;
        $this->showViewOffcanvas = true;
    }

    /**
     * Close view offcanvas
     */
    public function closeViewOffcanvas()
    {
        $this->showViewOffcanvas = false;
        $this->viewTicketId = null;
    }

    /**
     * Edit ticket (redirect to edit page)
     */
    public function edit($ticketId)
    {
        return redirect()->route('tickets.finance.edit', $ticketId);
    }

    /**
     * Duplicate ticket with confirmation
     * OPTION C: Creates new DRAFT ticket
     */
    public function duplicate($ticketId)
    {
        // Show confirmation for posted tickets
        $ticket = TicketMaster::find($ticketId);

        if ($ticket && $ticket->status === TicketStatus::POSTED) {
            $this->dispatch('confirm-duplicate', [
                'ticketId' => $ticketId,
                'ticketNo' => $ticket->ticket_no
            ]);
        } else {
            return redirect()->route('tickets.finance.duplicate', $ticketId);
        }
    }

    // ==========================================
    // OPTION C: Delete Draft Tickets
    // ==========================================

    /**
     * Confirm deletion of DRAFT ticket
     *
     * OPTION C: Only DRAFT tickets can be deleted
     * Posted tickets can only be CANCELLED
     */
    public function confirmDelete($ticketId)
    {
        $ticket = TicketMaster::find($ticketId);

        if (!$ticket) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Ticket not found.'
            ]);
            return;
        }

        // OPTION C: Only drafts can be deleted
        if ($ticket->status !== TicketStatus::DRAFT) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only draft tickets can be deleted. Posted tickets must be cancelled instead.'
            ]);
            return;
        }

        $this->deleteTicketId = $ticketId;
        $this->deleteTicketNo = $ticket->ticket_no;
        $this->showDeleteModal = true;
    }

    /**
     * Delete DRAFT ticket permanently
     *
     * OPTION C: Deleting drafts does NOT affect sequential numbering
     * because drafts have DRAFT-xxx IDs, not sequential numbers
     */
    public function delete()
    {
        $ticket = TicketMaster::find($this->deleteTicketId);

        if (!$ticket) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Ticket not found.'
            ]);
            $this->cancelDelete();
            return;
        }

        // Double-check: Only drafts can be deleted
        if ($ticket->status !== TicketStatus::DRAFT) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only draft tickets can be deleted.'
            ]);
            $this->cancelDelete();
            return;
        }

        try {
            $ticketNo = $ticket->ticket_no;

            // Delete ticket (soft delete)
            $ticket->delete();

            // Log deletion for audit trail
            ActivityLog::log(
                description: "Deleted draft ticket {$ticketNo}",
                subject: $ticket,
                event: 'deleted',
                properties: [
                    'ticket_no' => $ticketNo,
                    'department' => $ticket->department->department,
                    'reason' => 'Draft ticket deleted by user'
                ]
            );

            Log::info("Draft ticket {$ticketNo} deleted by " . Auth::user()->name);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Draft ticket deleted successfully. No impact on sequential numbering.'
            ]);

            $this->cancelDelete();
        } catch (\Exception $e) {
            Log::error('Error deleting draft ticket: ' . $e->getMessage());
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Failed to delete ticket.'
            ]);
        }
    }

    /**
     * Cancel delete operation
     */
    public function cancelDelete()
    {
        $this->deleteTicketId = null;
        $this->deleteTicketNo = '';
        $this->showDeleteModal = false;
    }

    // ==========================================
    // OPTION C: Bulk Delete Drafts (Max 5)
    // ==========================================

    /**
     * Confirm bulk deletion of draft tickets
     *
     * OPTION C: Maximum 5 drafts at once with warning message
     */
    public function confirmBulkDelete()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'No tickets selected.'
            ]);
            return;
        }

        // OPTION C: Limit to 5 drafts at once
        if (count($this->selectedItems) > 5) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'You can only delete up to 5 draft tickets at once.'
            ]);
            return;
        }

        // Check if all selected tickets are drafts
        $tickets = TicketMaster::whereIn('id', $this->selectedItems)->get();
        $nonDrafts = $tickets->filter(fn($t) => $t->status !== TicketStatus::DRAFT);

        if ($nonDrafts->count() > 0) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only draft tickets can be deleted. Please unselect posted/cancelled tickets.'
            ]);
            return;
        }

        $this->showBulkDeleteModal = true;
    }

    /**
     * Bulk delete draft tickets
     *
     * OPTION C: Delete multiple drafts at once (max 5)
     * No impact on sequential numbering
     */
    public function bulkDelete()
    {
        try {
            $tickets = TicketMaster::whereIn('id', $this->selectedItems)->get();

            $deletedCount = 0;
            foreach ($tickets as $ticket) {
                // Only delete drafts
                if ($ticket->status === TicketStatus::DRAFT) {
                    $ticketNo = $ticket->ticket_no;

                    // Soft delete
                    $ticket->delete();

                    // Log for audit
                    ActivityLog::log(
                        description: "Bulk deleted draft ticket {$ticketNo}",
                        subject: $ticket,
                        event: 'bulk_deleted'
                    );

                    $deletedCount++;
                }
            }

            // Clear selection
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => "{$deletedCount} draft ticket(s) deleted successfully!"
            ]);

            $this->showBulkDeleteModal = false;

            Log::info("Bulk deleted {$deletedCount} draft tickets by " . Auth::user()->name);

        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Failed to delete tickets.'
            ]);
        }
    }

    /**
     * Cancel bulk delete
     */
    public function cancelBulkDelete()
    {
        $this->showBulkDeleteModal = false;
    }

    // ==========================================
    // OPTION C: Cancel Posted Tickets
    // ==========================================

    /**
     * Confirm cancellation of POSTED ticket
     *
     * OPTION C: Posted tickets cannot be deleted, only CANCELLED
     * Requires reason (minimum 10 characters)
     */
    public function confirmCancel($ticketId)
    {
        $ticket = TicketMaster::find($ticketId);

        if (!$ticket) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Ticket not found.'
            ]);
            return;
        }

        // Check permissions
        if ($ticket->status === TicketStatus::POSTED && !Auth::user()->isAdmin()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only Admins can cancel posted tickets.'
            ]);
            return;
        }

        // Check if can be cancelled
        if ($ticket->status === TicketStatus::CANCELLED) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'This ticket is already cancelled.'
            ]);
            return;
        }

        $this->cancelTicketId = $ticketId;
        $this->showCancelModal = true;
    }

    /**
     * Cancel ticket (change status to CANCELLED)
     *
     * OPTION C: Ticket keeps its sequential number
     * Full audit trail preserved
     */
    public function cancelTicket()
    {
        // Validate reason
        $this->validate([
            'cancelReason' => 'required|string|min:10|max:500',
        ], [
            'cancelReason.required' => 'Please provide a reason for cancellation.',
            'cancelReason.min' => 'Reason must be at least 10 characters.',
            'cancelReason.max' => 'Reason cannot exceed 500 characters.',
        ]);

        $ticket = TicketMaster::find($this->cancelTicketId);

        if (!$ticket) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Ticket not found.'
            ]);
            $this->closeCancelModal();
            return;
        }

        try {
            DB::transaction(function () use ($ticket) {
                $oldStatus = $ticket->status;
                $ticketNo = $ticket->ticket_no;

                // Change status to CANCELLED
                $ticket->status = TicketStatus::CANCELLED;
                $ticket->updated_by = Auth::id();
                $ticket->save();

                // Log status change with reason
                TicketStatusHistory::create([
                    'ticket_id' => $ticket->id,
                    'from_status' => $oldStatus->value,
                    'to_status' => TicketStatus::CANCELLED->value,
                    'notes' => $this->cancelReason,
                    'changed_by' => Auth::id(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'changed_at' => now(),
                ]);

                // Log activity
                ActivityLog::log(
                    description: "Cancelled ticket {$ticketNo}",
                    subject: $ticket,
                    event: 'cancelled',
                    properties: ['reason' => $this->cancelReason]
                );

                Log::info("Ticket {$ticketNo} cancelled by " . Auth::user()->name . ". Reason: {$this->cancelReason}");
            });

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Ticket cancelled successfully. Number preserved for audit purposes.'
            ]);

            $this->closeCancelModal();

        } catch (\Exception $e) {
            Log::error('Error cancelling ticket: ' . $e->getMessage());
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Failed to cancel ticket.'
            ]);
        }
    }

    /**
     * Close cancel modal
     */
    public function closeCancelModal()
    {
        $this->cancelTicketId = null;
        $this->cancelReason = '';
        $this->showCancelModal = false;
        $this->resetValidation();
    }

    // ==========================================
    // OPTION C: Unpost Tickets (Super Admin Only)
    // ==========================================

    /**
     * Confirm unposting a ticket
     *
     * OPTION C: Only Super Admins can unpost
     * Used in worst-case scenarios
     * Ticket reverts to DRAFT but KEEPS its sequential number
     */
    public function confirmUnpost($ticketId)
    {
        if (!Auth::user()->isSuperAdmin()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only Super Admins can unpost tickets.'
            ]);
            return;
        }

        $ticket = TicketMaster::find($ticketId);

        if (!$ticket || $ticket->status !== TicketStatus::POSTED) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only posted tickets can be unposted.'
            ]);
            return;
        }

        $this->unpostTicketId = $ticketId;
        $this->showUnpostModal = true;
    }

    /**
     * Unpost ticket (revert POSTED to DRAFT)
     *
     * OPTION C: Ticket keeps its sequential number
     * Used in emergency/correction situations only
     */
    public function unpostTicket()
    {
        if (!Auth::user()->isSuperAdmin()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Unauthorized action.'
            ]);
            return;
        }

        $ticket = TicketMaster::find($this->unpostTicketId);

        if (!$ticket) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Ticket not found.'
            ]);
            return;
        }

        try {
            DB::transaction(function () use ($ticket) {
                $ticketNo = $ticket->ticket_no;

                // Revert to DRAFT (but keep sequential number!)
                $ticket->status = TicketStatus::DRAFT;
                $ticket->posted_date = null;
                $ticket->updated_by = Auth::id();
                $ticket->save();

                // Log status change
                TicketStatusHistory::create([
                    'ticket_id' => $ticket->id,
                    'from_status' => TicketStatus::POSTED->value,
                    'to_status' => TicketStatus::DRAFT->value,
                    'notes' => 'Unposted by Super Admin for corrections',
                    'changed_by' => Auth::id(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'changed_at' => now(),
                ]);

                // Log activity
                ActivityLog::log(
                    description: "Unposted ticket {$ticketNo} (reverted to draft)",
                    subject: $ticket,
                    event: 'unposted'
                );

                Log::warning("Ticket {$ticketNo} unposted by Super Admin: " . Auth::user()->name);
            });

            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'Ticket unposted successfully. Ticket number retained.'
            ]);

            $this->showUnpostModal = false;
            $this->unpostTicketId = null;

        } catch (\Exception $e) {
            Log::error('Error unposting ticket: ' . $e->getMessage());
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Failed to unpost ticket.'
            ]);
        }
    }

    /**
     * Close unpost modal
     */
    public function closeUnpostModal()
    {
        $this->unpostTicketId = null;
        $this->showUnpostModal = false;
    }

    // ==========================================
    // Export Functions
    // ==========================================

    public function exportExcel()
    {
        // TODO: Implement Excel export
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'Excel export coming soon!'
        ]);
    }

    public function exportPDF()
    {
        // TODO: Implement PDF export
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'PDF export coming soon!'
        ]);
    }

    // ==========================================
    // Event Listeners
    // ==========================================

    #[On('ticket-created')]
    #[On('ticket-updated')]
    #[On('ticket-deleted')]
    #[On('close-offcanvas')]
    public function refreshList()
    {
        $this->closeViewOffcanvas();
        $this->resetPage();
    }

    // ==========================================
    // Render
    // ==========================================

    public function render()
    {
        return view('livewire.tickets.finance.index', [
            'tickets' => $this->tickets,
            'stats' => $this->stats,
            'departments' => $this->departments,
        ])->layout('admin.layout');
    }
}
