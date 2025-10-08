<?php
// app/Livewire/Masters/CostCenter/Index.php

namespace App\Livewire\Masters\CostCenters;

use App\Models\CostCenter;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Cost Center Management Component
 *
 * Manages Cost Centers with full CRUD operations
 * Following the exact same pattern as UOM
 */
class CostCenterList extends Component
{
    use WithPagination;

    // Search & Filters
    public $search = '';
    public $statusFilter = ''; // all, active, inactive
    public $perPage = 10;

    // Sorting
    public $sortField = 'code';
    public $sortDirection = 'asc';

    // Modal State
    public $showModal = false;
    public $modalMode = 'create'; // create or edit
    public $costCenterId = null;

    // Form Fields
    public $code = '';
    public $name = '';
    public $description = '';
    public $is_active = true;

    // Delete Confirmation
    public $showDeleteModal = false;
    public $costCenterToDelete = null;

    // View Modal
    public $showViewModal = false;
    public $costCenterToView = null;

    // Bulk Selection
    public $selectedItems = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation Rules
     */
    protected function rules()
    {
        $costCenterId = $this->costCenterId;

        return [
            'code' => "required|string|max:50|unique:cost_centers,code,{$costCenterId}",
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    protected $validationAttributes = [
        'code' => 'code',
        'name' => 'name',
        'description' => 'description',
    ];

    protected $messages = [
        'code.required' => 'The code field is required.',
        'code.unique' => 'This code already exists.',
        'name.required' => 'The name field is required.',
    ];

    /**
     * Reset pagination when search changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * Sort by column
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
     * Open Create Modal
     */
    public function create()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->showModal = true;
        $this->dispatch('openModal', modalId: 'costCenterModal');
    }

    /**
     * Open Edit Modal
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

        $this->modalMode = 'edit';
        $this->showModal = true;
        $this->dispatch('openModal', modalId: 'costCenterModal');
    }

    /**
     * Save Cost Center (Create or Update)
     */
    public function save()
    {
        $this->validate();

        try {
            // Convert code to uppercase
            $this->code = strtoupper($this->code);

            if ($this->modalMode === 'create') {
                CostCenter::create([
                    'code' => $this->code,
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                    'created_by' => Auth::id(),
                ]);

                $message = 'Cost Center created successfully.';
            } else {
                $costCenter = CostCenter::findOrFail($this->costCenterId);
                $costCenter->update([
                    'code' => $this->code,
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                    'updated_by' => Auth::id(),
                ]);

                $message = 'Cost Center updated successfully.';
            }

            $this->showModal = false;
            $this->resetForm();
            $this->dispatch('closeModal', modalId: 'costCenterModal');
            $this->dispatch('showToast', type: 'success', message: $message);

        } catch (\Exception $e) {
            $this->dispatch('showToast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Confirm Delete
     */
    public function confirmDelete($id)
    {
        $this->costCenterToDelete = $id;
        $this->showDeleteModal = true;
        $this->dispatch('openModal', modalId: 'deleteModal');
    }

    /**
     * Delete Cost Center
     */
    public function delete()
    {
        try {
            $costCenter = CostCenter::findOrFail($this->costCenterToDelete);

            // Check if cost center is being used in tickets
            if ($costCenter->ticketMasters()->exists() || $costCenter->fuelSales()->exists()) {
                $this->dispatch('showToast', type: 'error', message: 'Cannot delete Cost Center. It is being used in tickets.');
                return;
            }

            $costCenter->delete();

            $this->showDeleteModal = false;
            $this->costCenterToDelete = null;
            $this->dispatch('closeModal', modalId: 'deleteModal');
            $this->dispatch('showToast', type: 'success', message: 'Cost Center deleted successfully.');

        } catch (\Exception $e) {
            $this->dispatch('showToast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancel Delete
     */
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->costCenterToDelete = null;
        $this->dispatch('closeModal', modalId: 'deleteModal');
    }

    /**
     * View Cost Center Details
     */
    public function view($id)
    {
        $this->costCenterToView = CostCenter::with(['createdBy', 'updatedBy'])->findOrFail($id);
        $this->showViewModal = true;
        $this->dispatch('openModal', modalId: 'viewModal');
    }

    /**
     * Edit from View Modal
     */
    public function editFromView()
    {
        if ($this->costCenterToView) {
            $this->dispatch('closeModal', modalId: 'viewModal');
            $this->edit($this->costCenterToView->id);
        }
    }

    /**
     * Close View Modal
     */
    public function closeView()
    {
        $this->showViewModal = false;
        $this->costCenterToView = null;
        $this->dispatch('closeModal', modalId: 'viewModal');
    }

    /**
     * Toggle Select All
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getCostCenters()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    /**
     * Delete Selected Items
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('showToast', type: 'error', message: 'No items selected.');
            return;
        }

        try {
            // Check if any selected cost centers are being used
            $usedCount = CostCenter::whereIn('id', $this->selectedItems)
                ->whereHas('ticketMasters')
                ->orWhereHas('fuelSales')
                ->count();

            if ($usedCount > 0) {
                $this->dispatch('showToast', type: 'error', message: "Cannot delete {$usedCount} cost center(s). They are being used in tickets.");
                return;
            }

            CostCenter::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;
            $this->dispatch('showToast', type: 'success', message: 'Selected cost centers deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('showToast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Close Modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('closeModal', modalId: 'costCenterModal');
    }

    /**
     * Reset Form
     */
    private function resetForm()
    {
        $this->costCenterId = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    /**
     * Get Cost Centers Query
     */
    private function getCostCenters()
    {
        $query = CostCenter::query();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Status Filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    /**
     * Render Component
     */
    public function render()
    {
        $costCenters = $this->getCostCenters()->paginate($this->perPage);

        return view('livewire.masters.cost-center.index', [
            'costCenters' => $costCenters,
        ])->layout('admin.layout');
    }
}
