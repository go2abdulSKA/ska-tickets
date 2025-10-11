<?php
// app/Livewire/Tickets/Finance/FinanceTicketList.php

namespace App\Livewire\Tickets\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\TicketMaster;
use App\Models\Department;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Support\Facades\Auth;
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

    // Sorting
    public $sortField = 'ticket_date';
    public $sortDirection = 'desc';

    // Selection for bulk actions
    public $selectedItems = [];
    public $selectAll = false;

    // Modals
    public $showViewOffcanvas = false;
    public $showDeleteModal = false;
    public $showBulkDeleteModal = false;
    public $showStatusModal = false;

    public $viewTicketId = null;
    public $deleteTicketId = null;
    public $statusTicketId = null;
    public $statusAction = ''; // 'post', 'unpost', 'cancel'

    public $deleteTicketNo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getTicketsProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    // ==========================================
    // Computed Properties
    // ==========================================

    public function getTicketsProperty()
    {
        $query = TicketMaster::query()
            ->with(['department:id,department,prefix', 'client:id,client_name,company_name', 'costCenter:id,code,name', 'user:id,name', 'serviceType:id,service_type', 'transactions:id,ticket_id,description,qty,total_cost'])
            ->where('ticket_type', TicketType::FINANCE);

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ticket_no', 'like', "%{$this->search}%")
                    ->orWhere('project_code', 'like', "%{$this->search}%")
                    ->orWhere('ref_no', 'like', "%{$this->search}%")
                    ->orWhere('contract_no', 'like', "%{$this->search}%")
                    ->orWhereHas('client', function ($q) {
                        $q->where('client_name', 'like', "%{$this->search}%")->orWhere('company_name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('costCenter', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%");
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
            $query->whereBetween('ticket_date', [Carbon::parse($this->dateFrom)->startOfDay(), Carbon::parse($this->dateTo)->endOfDay()]);
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

    public function getStatsProperty()
    {
        $query = TicketMaster::where('ticket_type', TicketType::FINANCE);

        // Apply date range to stats
        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('ticket_date', [Carbon::parse($this->dateFrom)->startOfDay(), Carbon::parse($this->dateTo)->endOfDay()]);
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

    public function getDepartmentsProperty()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return Department::active()->get();
        }

        return Department::active()->whereIn('id', $user->getDepartmentIds())->get();
    }

    // ==========================================
    // Actions
    // ==========================================

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo', 'departmentFilter', 'clientTypeFilter']);

        // Reset to current month
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');

        $this->resetPage();
    }

    public function view($ticketId)
    {
        $this->viewTicketId = $ticketId;
        $this->showViewOffcanvas = true;
    }

    public function closeViewOffcanvas()
    {
        $this->showViewOffcanvas = false;
        $this->viewTicketId = null;
    }

    public function edit($ticketId)
    {
        return redirect()->route('tickets.finance.edit', $ticketId);
    }

    public function duplicate($ticketId)
    {
        return redirect()->route('tickets.finance.duplicate', $ticketId);
    }

    // public function confirmDelete($ticketId)
    // {
    //     $ticket = TicketMaster::find($ticketId);

    //     if (!$ticket) {
    //         $this->dispatch('toast', type: 'error', message: 'Ticket not found.');
    //         return;
    //     }

    //     // Only draft tickets can be deleted
    //     if (!$ticket->canDelete()) {
    //         $this->dispatch('toast', type: 'error', message: 'Only draft tickets can be deleted.');
    //         return;
    //     }

    //     $this->deleteTicketId = $ticketId;
    //     $this->showDeleteModal = true;
    // }

    public function confirmDelete($ticketId)
    {
        $ticket = TicketMaster::find($ticketId);

        if (!$ticket) {
            $this->dispatch('toast', type: 'error', message: 'Ticket not found.');
            return;
        }

        // Only draft tickets can be deleted
        if (!$ticket->canDelete()) {
            $this->dispatch('toast', type: 'error', message: 'Only draft tickets can be deleted.');
            return;
        }

        $this->deleteTicketId = $ticketId;
        $this->deleteTicketNo = $ticket->ticket_no;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $ticket = TicketMaster::find($this->deleteTicketId);

        if (!$ticket) {
            $this->dispatch('toast', type: 'error', message: 'Ticket not found.');
            $this->cancelDelete();
            return;
        }

        if (!$ticket->canDelete()) {
            $this->dispatch('toast', type: 'error', message: 'This ticket cannot be deleted.');
            $this->cancelDelete();
            return;
        }

        try {
            // Soft delete preserves sequence integrity
            $ticket->delete();

            $this->dispatch('toast', type: 'success', message: 'Ticket deleted successfully!');
            $this->cancelDelete();
        } catch (\Exception $e) {
            \Log::error('Error deleting ticket: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Failed to delete ticket.');
        }
    }

    public function cancelDelete()
    {
        $this->deleteTicketId = null;
        $this->showDeleteModal = false;
    }

    public function confirmBulkDelete()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No tickets selected.');
            return;
        }

        // Check if all selected tickets can be deleted
        $tickets = TicketMaster::whereIn('id', $this->selectedItems)->get();
        $cannotDelete = $tickets->filter(fn($t) => !$t->canDelete());

        if ($cannotDelete->count() > 0) {
            $this->dispatch('toast', type: 'error', message: 'Some tickets cannot be deleted (only drafts allowed).');
            return;
        }

        $this->showBulkDeleteModal = true;
    }

    public function bulkDelete()
    {
        try {
            TicketMaster::whereIn('id', $this->selectedItems)->delete();

            $count = count($this->selectedItems);
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: "{$count} tickets deleted successfully!");
            $this->showBulkDeleteModal = false;
        } catch (\Exception $e) {
            \Log::error('Bulk delete error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Failed to delete tickets.');
        }
    }

    public function cancelBulkDelete()
    {
        $this->showBulkDeleteModal = false;
    }

    // Status management
    public function confirmStatusChange($ticketId, $action)
    {
        $ticket = TicketMaster::find($ticketId);

        if (!$ticket) {
            $this->dispatch('toast', type: 'error', message: 'Ticket not found.');
            return;
        }

        // Validate action permissions
        if ($action === 'post' && !$ticket->canPost()) {
            $this->dispatch('toast', type: 'error', message: 'This ticket cannot be posted.');
            return;
        }

        if ($action === 'unpost' && !$ticket->canUnpost()) {
            $this->dispatch('toast', type: 'error', message: 'Only admins can unpost tickets.');
            return;
        }

        if ($action === 'cancel' && !$ticket->canCancel()) {
            $this->dispatch('toast', type: 'error', message: 'This ticket cannot be cancelled.');
            return;
        }

        $this->statusTicketId = $ticketId;
        $this->statusAction = $action;
        $this->showStatusModal = true;
    }

    public function exportExcel()
    {
        // TODO: Implement Excel export
        $this->dispatch('toast', type: 'info', message: 'Excel export coming soon!');
    }

    public function exportPDF()
    {
        // TODO: Implement PDF export
        $this->dispatch('toast', type: 'info', message: 'PDF export coming soon!');
    }

    #[On('ticket-created')]
    #[On('ticket-updated')]
    #[On('ticket-deleted')]
    public function refreshList()
    {
        // Force refresh pagination
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
