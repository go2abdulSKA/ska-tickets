<?php
// app/Livewire/Tickets/Fuel/FuelSaleList.php

namespace App\Livewire\Tickets\Fuel;

use App\Models\FuelSale;
use App\Models\Department;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\FuelType;
use App\Enums\TicketStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FuelSaleList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Search and filters
    public $search = '';
    public $filterStatus = '';
    public $filterDepartment = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $perPage = 15;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Bulk actions
    public $selectedItems = [];
    public $selectAll = false;

    // Dropdowns
    public $departments = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterDepartment' => ['except' => ''],
    ];

    /**
     * Mount component
     */
    public function mount()
    {
        $this->loadDropdowns();
        $this->filterDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->filterDateTo = now()->format('Y-m-d');
    }

    /**
     * Load dropdown data
     */
    public function loadDropdowns()
    {
        // Only load Fuel Services department
        $fuelServicesDept = Department::where('department', 'Fuel Services')
            ->where('is_active', true)
            ->first();
            
        if ($fuelServicesDept) {
            $this->departments = collect([$fuelServicesDept]);
        } else {
            $this->departments = collect([]);
        }
    }

    /**
     * Reset pagination when search/filter changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDepartment()
    {
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
     * Clear all filters
     */
    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterDepartment', 'filterDateFrom', 'filterDateTo']);
        $this->filterDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->filterDateTo = now()->format('Y-m-d');
    }

    /**
     * Delete fuel sale (only draft)
     */
    public function delete($id)
    {
        try {
            $fuelSale = FuelSale::findOrFail($id);

            // Check if ticket can be deleted
            if (!$fuelSale->canDelete()) {
                session()->flash('error', 'Only draft tickets can be deleted.');
                return;
            }

            // Check if user owns this ticket or is admin
            if (!Auth::user()->role->isAdmin() && $fuelSale->created_by !== Auth::id()) {
                session()->flash('error', 'You can only delete your own tickets.');
                return;
            }

            $ticketNo = $fuelSale->ticket_no;
            $fuelSale->delete();

            session()->flash('success', "Fuel sale ticket {$ticketNo} deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Error deleting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete fuel sale ticket.');
        }
    }

    /**
     * Post fuel sale ticket
     */
    public function post($id)
    {
        try {
            // Check permission
            if (!Auth::user()->hasPermission('post-fuel-ticket')) {
                session()->flash('error', 'You do not have permission to post fuel tickets.');
                return;
            }

            $fuelSale = FuelSale::findOrFail($id);

            // Check if can post
            if (!$fuelSale->canPost()) {
                session()->flash('error', 'This ticket cannot be posted.');
                return;
            }

            DB::beginTransaction();

            // Update status
            $fuelSale->status = TicketStatus::POSTED;
            $fuelSale->posted_at = now();
            $fuelSale->posted_by = Auth::id();
            $fuelSale->save();

            // Log status change
            $fuelSale->statusHistories()->create([
                'previous_status' => TicketStatus::DRAFT,
                'new_status' => TicketStatus::POSTED,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
                'remarks' => 'Ticket posted'
            ]);

            DB::commit();

            session()->flash('success', "Fuel sale ticket {$fuelSale->ticket_no} posted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to post fuel sale ticket.');
        }
    }

    /**
     * Unpost fuel sale ticket
     */
    public function unpost($id)
    {
        try {
            // Check permission
            if (!Auth::user()->hasPermission('unpost-fuel-ticket')) {
                session()->flash('error', 'You do not have permission to unpost fuel tickets.');
                return;
            }

            $fuelSale = FuelSale::findOrFail($id);

            // Check if ticket is posted
            if ($fuelSale->status !== TicketStatus::POSTED) {
                session()->flash('error', 'Only posted tickets can be unposted.');
                return;
            }

            DB::beginTransaction();

            // Update status
            $fuelSale->status = TicketStatus::DRAFT;
            $fuelSale->posted_at = null;
            $fuelSale->posted_by = null;
            $fuelSale->save();

            // Log status change
            $fuelSale->statusHistories()->create([
                'previous_status' => TicketStatus::POSTED,
                'new_status' => TicketStatus::DRAFT,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
                'remarks' => 'Ticket unposted'
            ]);

            DB::commit();

            session()->flash('success', "Fuel sale ticket {$fuelSale->ticket_no} unposted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unposting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to unpost fuel sale ticket.');
        }
    }

    /**
     * Revert cancelled ticket (Admin only)
     */
    public function revertCancellation($id)
    {
        try {
            // Check if user is admin
            if (!Auth::user()->isAdmin()) {
                session()->flash('error', 'Only administrators can revert cancelled tickets.');
                return;
            }

            $fuelSale = FuelSale::with('statusHistories')->findOrFail($id);

            // Check if ticket is cancelled
            if ($fuelSale->status !== TicketStatus::CANCELLED) {
                session()->flash('error', 'This ticket is not cancelled.');
                return;
            }

            DB::beginTransaction();

            // Get the last status before cancellation
            $previousStatusHistory = $fuelSale->statusHistories()
                ->where('new_status', TicketStatus::CANCELLED)
                ->latest()
                ->first();

            $previousStatus = $previousStatusHistory ? $previousStatusHistory->previous_status : TicketStatus::DRAFT;

            // Revert to previous status
            $fuelSale->status = $previousStatus;
            
            // If reverting to posted, restore posted date
            if ($previousStatus === TicketStatus::POSTED) {
                $postedHistory = $fuelSale->statusHistories()
                    ->where('new_status', TicketStatus::POSTED)
                    ->latest()
                    ->first();
                
                if ($postedHistory) {
                    $fuelSale->posted_at = $postedHistory->changed_at;
                    $fuelSale->posted_by = $postedHistory->changed_by;
                }
            }
            
            $fuelSale->save();

            // Log status change
            $fuelSale->statusHistories()->create([
                'previous_status' => TicketStatus::CANCELLED,
                'new_status' => $previousStatus,
                'changed_by' => Auth::id(),
                'changed_at' => now(),
                'remarks' => 'Cancellation reverted by admin'
            ]);

            DB::commit();

            session()->flash('success', "Fuel sale ticket {$fuelSale->ticket_no} reverted to {$previousStatus->label()} successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reverting fuel sale cancellation: ' . $e->getMessage());
            session()->flash('error', 'Failed to revert cancelled fuel sale ticket.');
        }
    }

    /**
     * Export to Excel
     */
    public function exportToExcel()
    {
        try {
            // Get filtered data
            $fuelSales = $this->getFuelSalesQuery()->get();

            // TODO: Implement Excel export logic here
            // You can use Laravel Excel package or similar

            session()->flash('info', 'Excel export functionality will be implemented.');
        } catch (\Exception $e) {
            Log::error('Error exporting fuel sales: ' . $e->getMessage());
            session()->flash('error', 'Failed to export fuel sales.');
        }
    }

    /**
     * Toggle select all
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getFuelSalesQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    /**
     * Get fuel sales query
     */
    protected function getFuelSalesQuery()
    {
        // Get Fuel Services department
        $fuelServicesDept = Department::where('department', 'Fuel Services')->first();
        
        if (!$fuelServicesDept) {
            return FuelSale::whereRaw('1 = 0'); // Return empty query if dept not found
        }

        $query = FuelSale::with([
            'department',
            'client',
            'costCenter'
        ])->where('department_id', $fuelServicesDept->id); // Only Fuel Services tickets

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('ticket_no', 'like', '%' . $this->search . '%')
                    ->orWhere('vehicle_reg_no', 'like', '%' . $this->search . '%')
                    ->orWhere('vehicle_type', 'like', '%' . $this->search . '%')
                    ->orWhere('vehicle_driver', 'like', '%' . $this->search . '%')
                    ->orWhere('product_type', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by department (will always be Fuel Services, but keep for consistency)
        if ($this->filterDepartment) {
            $query->where('department_id', $this->filterDepartment);
        }

        // Filter by status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Filter by date range
        if ($this->filterDateFrom) {
            $query->whereDate('ticket_date', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('ticket_date', '<=', $this->filterDateTo);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    /**
     * Render component
     */
    public function render()
    {
        $fuelSales = $this->getFuelSalesQuery()->paginate($this->perPage);

        return view('livewire.tickets.fuel.fuel-sale-list', [
            'fuelSales' => $fuelSales
        ])->extends('admin.layout', ['pageTitle' => 'Fuel Sales Tickets']);
    }
}
