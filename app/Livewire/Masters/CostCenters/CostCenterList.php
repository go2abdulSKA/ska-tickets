<?php
// app/Livewire/Masters/CostCenters/CostCenterList.php

namespace App\Livewire\Masters\CostCenters;

use App\Models\CostCenter;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class CostCenterList extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Public properties
    public $search = '';
    public $statusFilter = '';
    public $sortField = 'code';
    public $sortDirection = 'asc';
    public $perPage = 5;

    // Modal properties
    public $showModal = false;
    public $editMode = false;
    public $costCenterId = null;

    // Form properties
    public $code = '';
    public $name = '';
    public $description = '';
    public $is_active = true;

    // Selection properties
    public $selectedItems = [];
    public $selectAll = false;

    // View offcanvas
    public $showOffcanvas = false;
    public $viewCostCenter = null;

    // Delete confirmation
    public $deleteId = null;
    public $showDeleteModal = false;

    protected $paginationTheme = 'bootstrap';

    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:cost_centers,code,' . $this->costCenterId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Reset pagination when search changes
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getCostCentersProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
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

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

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

    public function view($id)
    {
        $this->viewCostCenter = costCenter::with(['creator', 'updater', 'ticketTransactions'])->findOrFail($id);
        $this->showOffcanvas = true;
    }

    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewCostCenter = null;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    /**
     * Save cost center
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'code' => strtoupper($this->code),
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ];

            if ($this->editMode) {
                $costCenter = CostCenter::findOrFail($this->CostCenterId);
                $data['updated_by'] = auth()->id();
                $costCenter->update($data);

                $this->dispatch('toast', type: 'success', message: 'Cost Center updated successfully!');
            } else {
                $data['created_by'] = auth()->id();
                CostCenter::create($data);

                $this->dispatch('toast', type: 'success', message: 'Cost Center created successfully!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        $id = $id ?? $this->deleteId;

        if (!$id) {
            return;
        }

        try {
            $CostCenter = CostCenter::findOrFail($id);

            // Check if Cost Center is being used
            $usageCount = $costCenter->ticketTransactions()->count();

            if ($usageCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete Cost Center. It is being used in {$usageCount} ticket transaction(s).");
                $this->cancelDelete();
                return;
            }

            $CostCenter->delete();
            $this->dispatch('toast', type: 'success', message: 'Cost Center deleted successfully!');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected.');
            return;
        }

        try {
            // Check if any selected Cost Center is being used
            $usedCostCenters = CostCenter::whereIn('id', $this->selectedItems)
                ->withCount('ticketTransactions')
                ->get()
                ->filter(fn($CostCenter) => $CostCenter->ticket_transactions_count > 0);

            if ($usedCostCenters->count() > 0) {
                $this->dispatch('toast', type: 'error', message: 'Some Cost Centers cannot be deleted as they are being used in transactions.');
                return;
            }

            CostCenter::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: 'Selected Cost Centers deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->CostCenterId = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function getCostCentersProperty()
    {
        return CostCenter::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $isActive = $this->statusFilter === 'Active';
                $query->where('is_active', $isActive);
            })
            ->withCount('ticketTransactions')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $CostCenters = $this->getCostCentersProperty();

        $stats = [
            'total' => CostCenter::count(),
            'active' => CostCenter::where('is_active', true)->count(),
            'inactive' => CostCenter::where('is_active', false)->count(),
        ];

        return view('livewire.masters.cost-centers.cost-center-list', [
            'CostCenters' => $CostCenters,
            'stats' => $stats,
            'title' => 'Cost Center Management',
        ]);
        // ])->layout('admin.layout', ['title' => 'Cost Center Management']);

    // public function render()
    // {
    //     return view('livewire.masters.cost-centers.cost-center-list', [
    //         'costCenters' => $this->getQuery()->paginate($this->perPage),
    //     ]);
    // }
    }

}
