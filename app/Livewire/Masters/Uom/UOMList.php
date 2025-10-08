<?php
// app/Livewire/Masters/UOM/UOMList.php

namespace App\Livewire\Masters\UOM;

use App\Models\UOM;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class UOMList extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Properties
    public $search = '';
    public $statusFilter = '';
    public $sortField = 'code';
    public $sortDirection = 'asc';
    public $perPage = 5;

    // Modal properties
    public $showModal = false;
    public $editMode = false;
    public $uomId = null;

    // Form properties
    public $code = '';
    public $name = '';
    public $description = '';
    public $is_active = true;

    // Selection
    public $selectedItems = [];
    public $selectAll = false;

    // View offcanvas
    public $showOffcanvas = false;
    public $viewUom = null;

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
            'code' => 'required|string|max:20|unique:uom,code,' . $this->uomId,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
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
            $this->selectedItems = $this->getUomsProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
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
        $uom = UOM::findOrFail($id);

        // dd( $uom );

        $this->uomId = $uom->id;
        $this->code = $uom->code;
        $this->name = $uom->name;
        $this->description = $uom->description;
        $this->is_active = $uom->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function view($id)
    {
        $this->viewUom = UOM::with(['creator', 'updater', 'ticketTransactions'])->findOrFail($id);
        $this->showOffcanvas = true;
    }

    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewUom = null;
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
                $uom = UOM::findOrFail($this->uomId);
                $data['updated_by'] = auth()->id();
                $uom->update($data);

                $this->dispatch('toast', type: 'success', message: 'UOM updated successfully!');
            } else {
                $data['created_by'] = auth()->id();
                UOM::create($data);

                $this->dispatch('toast', type: 'success', message: 'UOM created successfully!');
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
            $uom = UOM::findOrFail($id);

            // Check if UOM is being used
            $usageCount = $uom->ticketTransactions()->count();

            if ($usageCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete UOM. It is being used in {$usageCount} ticket transaction(s).");
                $this->cancelDelete();
                return;
            }

            $uom->delete();
            $this->dispatch('toast', type: 'success', message: 'UOM deleted successfully!');
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
            // Check if any selected UOM is being used
            $usedUoms = UOM::whereIn('id', $this->selectedItems)
                ->withCount('ticketTransactions')
                ->get()
                ->filter(fn($uom) => $uom->ticket_transactions_count > 0);

            if ($usedUoms->count() > 0) {
                $this->dispatch('toast', type: 'error', message: 'Some UOMs cannot be deleted as they are being used in transactions.');
                return;
            }

            UOM::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: 'Selected UOMs deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->uomId = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function getUomsProperty()
    {
        return UOM::query()
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
        $uoms = $this->getUomsProperty();

        $stats = [
            'total' => UOM::count(),
            'active' => UOM::where('is_active', true)->count(),
            'inactive' => UOM::where('is_active', false)->count(),
        ];

        return view('livewire.masters.uom.index', [
            'uoms' => $uoms,
            'stats' => $stats,
            'title' => 'UOM Management',
        ]);
        // ])->layout('admin.layout', ['title' => 'UOM Management']);
    }
}
