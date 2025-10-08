<?php
// app/Livewire/Masters/CostCenter/CostCenterList.php

namespace App\Livewire\Masters\CostCenters;

use App\Models\CostCenter;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

/**
 * Cost Center List Component
 *
 * Manages CRUD operations for Cost Centers
 * Follows the exact same pattern as UOM CRUD
 */
class CostCenterList extends Component
{
    use WithPagination, WithoutUrlPagination;

    // ==========================================
    // Properties
    // ==========================================

    /** @var string Search query */
    public $search = '';

    /** @var string Status filter (empty, 'Active', 'Inactive') */
    public $statusFilter = '';

    /** @var string Sort field */
    public $sortField = 'code';

    /** @var string Sort direction */
    public $sortDirection = 'asc';

    /** @var int Records per page */
    public $perPage = 5;

    // ==========================================
    // Modal Properties
    // ==========================================

    /** @var bool Show add/edit modal */
    public $showModal = false;

    /** @var bool Edit mode flag */
    public $editMode = false;

    /** @var int|null Cost center ID being edited */
    public $costCenterId = null;

    // ==========================================
    // Form Properties
    // ==========================================

    /** @var string Cost center code */
    public $code = '';

    /** @var string Cost center name */
    public $name = '';

    /** @var string Cost center description */
    public $description = '';

    /** @var bool Active status */
    public $is_active = true;

    // ==========================================
    // Selection Properties
    // ==========================================

    /** @var array Selected item IDs */
    public $selectedItems = [];

    /** @var bool Select all checkbox */
    public $selectAll = false;

    // ==========================================
    // View Offcanvas Properties
    // ==========================================

    /** @var bool Show view offcanvas */
    public $showOffcanvas = false;

    /** @var CostCenter|null Cost center being viewed */
    public $viewCostCenter = null;

    // ==========================================
    // Delete Modal Properties
    // ==========================================

    /** @var int|null Cost center ID to delete */
    public $deleteId = null;

    /** @var bool Show delete confirmation modal */
    public $showDeleteModal = false;

    /** @var string Pagination theme */
    protected $paginationTheme = 'bootstrap';

    // ==========================================
    // Validation Rules
    // ==========================================

    /**
     * Get validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:20|unique:cost_centers,code,' . $this->costCenterId,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    // ==========================================
    // Lifecycle Hooks
    // ==========================================

    /**
     * Reset pagination when search changes
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status filter changes
     */
    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when per page changes
     */
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    /**
     * Handle select all checkbox change
     *
     * @param bool $value
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getCostCentersProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    // ==========================================
    // Sorting Methods
    // ==========================================

    /**
     * Sort by field
     *
     * @param string $field
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

    // ==========================================
    // Modal Methods
    // ==========================================

    /**
     * Open modal for creating new cost center
     */
    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    /**
     * Close add/edit modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Open modal for editing cost center
     *
     * @param int $id
     */
    public function edit($id)
    {
        $this->resetForm();
        $costCenter = CostCenter::findOrFail($id);

        $this->costCenterId = $costCenter->id;
        $this->code = $costCenter->code;
        $this->name = $costCenter->name;
        $this->description = $costCenter->description;
        $this->is_active = $costCenter->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    // ==========================================
    // Offcanvas Methods
    // ==========================================

    /**
     * Open offcanvas to view cost center details
     *
     * @param int $id
     */
    public function view($id)
    {
        $this->viewCostCenter = CostCenter::with(['creator', 'updater', 'tickets'])->findOrFail($id);
        $this->showOffcanvas = true;
    }

    /**
     * Close view offcanvas
     */
    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewCostCenter = null;
    }

    // ==========================================
    // Delete Methods
    // ==========================================

    /**
     * Show delete confirmation modal
     *
     * @param int $id
     */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Cancel delete operation
     */
    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    // ==========================================
    // CRUD Operations
    // ==========================================

    /**
     * Save cost center (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            // Prepare data
            $data = [
                'code' => strtoupper($this->code), // Auto-uppercase code
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ];

            if ($this->editMode) {
                // Update existing cost center
                $costCenter = CostCenter::findOrFail($this->costCenterId);
                $data['updated_by'] = auth()->id();
                $costCenter->update($data);

                $this->dispatch('toast', type: 'success', message: 'Cost Center updated successfully!');
            } else {
                // Create new cost center
                $data['created_by'] = auth()->id();
                CostCenter::create($data);

                $this->dispatch('toast', type: 'success', message: 'Cost Center created successfully!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Delete cost center
     *
     * @param int|null $id
     */
    public function delete($id = null)
    {
        $id = $id ?? $this->deleteId;

        if (!$id) {
            return;
        }

        try {
            $costCenter = CostCenter::findOrFail($id);

            // Check if cost center is being used in tickets
            $usageCount = $costCenter->tickets()->count();

            if ($usageCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete Cost Center. It is being used in {$usageCount} ticket(s).");
                $this->cancelDelete();
                return;
            }

            // Delete the cost center
            $costCenter->delete();
            $this->dispatch('toast', type: 'success', message: 'Cost Center deleted successfully!');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    /**
     * Delete selected cost centers
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected.');
            return;
        }

        try {
            // Check if any selected cost center is being used
            $usedCostCenters = CostCenter::whereIn('id', $this->selectedItems)
                ->withCount('tickets')
                ->get()
                ->filter(fn($costCenter) => $costCenter->tickets_count > 0);

            if ($usedCostCenters->count() > 0) {
                $this->dispatch('toast', type: 'error', message: 'Some Cost Centers cannot be deleted as they are being used in tickets.');
                return;
            }

            // Delete selected cost centers
            CostCenter::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: 'Selected Cost Centers deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->costCenterId = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    /**
     * Get filtered and paginated cost centers
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCostCentersProperty()
    {
        return CostCenter::query()
            // Search filter
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%');
                });
            })
            // Status filter
            ->when($this->statusFilter !== '', function ($query) {
                $isActive = $this->statusFilter === 'Active';
                $query->where('is_active', $isActive);
            })
            // Load tickets count
            ->withCount('tickets')
            // Sorting
            ->orderBy($this->sortField, $this->sortDirection)
            // Pagination
            ->paginate($this->perPage);
    }

    // ==========================================
    // Render Method
    // ==========================================

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $costCenters = $this->getCostCentersProperty();

        // Get statistics
        $stats = [
            'total' => CostCenter::count(),
            'active' => CostCenter::where('is_active', true)->count(),
            'inactive' => CostCenter::where('is_active', false)->count(),
        ];

        return view('livewire.masters.cost-center.index', [
            'costCenters' => $costCenters,
            'stats' => $stats,
            'title' => 'Cost Center Management',
        ]);
    }
}
