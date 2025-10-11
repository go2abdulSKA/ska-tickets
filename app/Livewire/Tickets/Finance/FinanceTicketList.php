<?php

namespace App\Livewire\Tickets\Finance;

use App\Enums\ClientType;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Department;
use App\Models\TicketMaster;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class FinanceTicketList extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $statusFilter = '';
    public $departmentFilter = '';
    public $clientTypeFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 25;

    // OPTION C: My Drafts Toggle
    public $showDraftsOnly = false;

    // Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Selection
    public $selectedItems = [];
    public $selectAll = false;

    // Modals
    public $showViewOffcanvas = false;
    public $viewTicketId = null;
    public $showDeleteModal = false;
    public $deleteTicketId = null;
    public $deleteTicketNo = '';
    public $showBulkDeleteModal = false;
    public $showCancelModal = false;
    public $cancelTicketId = null;
    public $cancelReason = '';
    public $showUnpostModal = false;
    public $unpostTicketId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'clientTypeFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 25],
        'showDraftsOnly' => ['except' => false],
    ];

    /**
     * OPTION C: Toggle "My Drafts" filter
     */
    public function toggleMyDrafts()
    {
        $this->showDraftsOnly = !$this->showDraftsOnly;
        $this->resetPage();
    }

    /**
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'departmentFilter', 'clientTypeFilter', 'dateFrom', 'dateTo', 'showDraftsOnly']);
        $this->resetPage();
    }

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
     * Get tickets query
     */
    public function getTicketsProperty()
    {
        $query = TicketMaster::query()
            ->with(['department', 'user', 'client', 'costCenter', 'serviceType'])
            ->where('ticket_type', TicketType::FINANCE);

        // OPTION C: My Drafts Filter
        if ($this->showDraftsOnly) {
            $query->where('status', TicketStatus::DRAFT)->where('user_id', Auth::id());
        }

        // Department filter
        if ($this->departmentFilter) {
            $query->where('department_id', $this->departmentFilter);
        } elseif (!Auth::user()->isSuperAdmin()) {
            $query->whereIn('department_id', Auth::user()->getDepartmentIds());
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Client type filter
        if ($this->clientTypeFilter) {
            $query->where('client_type', $this->clientTypeFilter);
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->where('ticket_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->where('ticket_date', '<=', $this->dateTo);
        }

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ticket_no', 'like', "%{$this->search}%")
                    ->orWhere('project_code', 'like', "%{$this->search}%")
                    ->orWhere('ref_no', 'like', "%{$this->search}%")
                    ->orWhereHas('client', function ($sq) {
                        $sq->where('client_name', 'like', "%{$this->search}%")->orWhere('company_name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('costCenter', function ($sq) {
                        $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%");
                    });
            });
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /**
     * Get departments for filter
     */
    public function getDepartmentsProperty()
    {
        if (Auth::user()->isSuperAdmin()) {
            return Department::active()->get();
        }
        return Auth::user()->departments;
    }

    /**
     * Get statistics
     */
    public function getStatsProperty()
    {
        $query = TicketMaster::where('ticket_type', TicketType::FINANCE);

        if (!Auth::user()->isSuperAdmin()) {
            $query->whereIn('department_id', Auth::user()->getDepartmentIds());
        }

        return [
            'total' => $query->count(),
            'draft' => (clone $query)->where('status', TicketStatus::DRAFT)->count(),
            'posted' => (clone $query)->where('status', TicketStatus::POSTED)->count(),
            'total_amount' => (clone $query)->where('status', TicketStatus::POSTED)->sum('total_amount'),
        ];
    }

    /**
     * View ticket
     */
    public function view($ticketId)
    {
        $this->viewTicketId = $ticketId;
        $this->showViewOffcanvas = true;
    }

    /**
     * OPTION C: Confirm delete draft
     */
    public function confirmDelete($ticketId)
    {
        $ticket = TicketMaster::findOrFail($ticketId);

        if (!$ticket->canDelete()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only draft tickets can be deleted.',
            ]);
            return;
        }

        $this->deleteTicketId = $ticketId;
        $this->deleteTicketNo = $ticket->ticket_no;
        $this->showDeleteModal = true;
    }

    /**
     * OPTION C: Delete draft ticket
     */
    public function delete()
    {
        try {
            $ticket = TicketMaster::findOrFail($this->deleteTicketId);

            if (!$ticket->canDelete()) {
                throw new \Exception('Only draft tickets can be deleted.');
            }

            DB::transaction(function () use ($ticket) {
                $ticketNo = $ticket->ticket_no;

                // Log before deletion
                $activityLog = app(ActivityLogService::class);
                $activityLog->logDeleted($ticket, "Deleted draft ticket {$ticketNo}");

                // Delete ticket (cascades to transactions and attachments)
                $ticket->delete();
            });

            $this->showDeleteModal = false;
            $this->reset(['deleteTicketId', 'deleteTicketNo']);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Draft ticket deleted successfully.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->reset(['deleteTicketId', 'deleteTicketNo']);
    }

    /**
     * OPTION C: Confirm bulk delete (max 5 drafts)
     */
    public function confirmBulkDelete()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'Please select tickets to delete.',
            ]);
            return;
        }

        if (count($this->selectedItems) > 5) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'Maximum 5 drafts can be deleted at once.',
            ]);
            return;
        }

        $this->showBulkDeleteModal = true;
    }

    /**
     * OPTION C: Bulk delete drafts
     */
    public function bulkDelete()
    {
        try {
            $deleted = 0;
            $failed = 0;

            foreach ($this->selectedItems as $ticketId) {
                $ticket = TicketMaster::find($ticketId);

                if ($ticket && $ticket->canDelete()) {
                    $activityLog = app(ActivityLogService::class);
                    $activityLog->logDeleted($ticket, "Bulk deleted draft ticket {$ticket->ticket_no}");
                    $ticket->delete();
                    $deleted++;
                } else {
                    $failed++;
                }
            }

            $this->showBulkDeleteModal = false;
            $this->reset(['selectedItems', 'selectAll']);

            $message = "Deleted {$deleted} draft ticket(s).";
            if ($failed > 0) {
                $message .= " {$failed} ticket(s) could not be deleted (not drafts).";
            }

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Error during bulk delete: ' . $e->getMessage(),
            ]);
        }
    }

    public function cancelBulkDelete()
    {
        $this->showBulkDeleteModal = false;
    }

    /**
     * Export functions (placeholders)
     */
    public function exportExcel()
    {
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'Excel export feature coming soon!',
        ]);
    }

    public function exportPDF()
    {
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'PDF export feature coming soon!',
        ]);
    }

    public function render()
    {
        return view('livewire.tickets.finance.index', [
            'tickets' => $this->tickets,
            'departments' => $this->departments,
            'stats' => $this->stats,
        ])->layout('admin.layout');
    }
}
