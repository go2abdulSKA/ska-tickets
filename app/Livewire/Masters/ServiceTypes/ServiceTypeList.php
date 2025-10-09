<?php
// app/Livewire/Masters/ServiceTypes/ServiceTypeList.php

namespace App\Livewire\Masters\ServiceTypes;

use App\Models\ServiceType;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

/**
 * Service Type List Component
 *
 * Manages CRUD operations for Service Types
 * Includes department relationship
 */
class ServiceTypeList extends Component
{
    use WithPagination, WithoutUrlPagination;

    // ==========================================
    // Properties
    // ==========================================

    /** @var string Search query */
    public $search = '';

    /** @var string Status filter (empty, 'Active', 'Inactive') */
    public $statusFilter = '';

    /** @var string Department filter */
    public $departmentFilter = '';

    /** @var string Sort field */
    // public $sortField = 'code';

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

    /** @var int|null Service type ID being edited */
    public $serviceTypeId = null;

    // ==========================================
    // Form Properties
    // ==========================================

    /** @var string Service type code */
    // public $code = '';

    /** @var string Service type name */
    public $name = '';

    /** @var string Service type description */
    public $description = '';

    /** @var int|null Department ID */
    public $department_id = null;

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

    /** @var ServiceType|null Service type being viewed */
    public $viewServiceType = null;

    // ==========================================
    // Delete Modal Properties
    // ==========================================

    /** @var int|null Service type ID to delete */
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
            // 'code' => 'required|string|max:20|unique:service_types,service_type,' . $this->serviceTypeId . ',id,department_id,' . $this->department_id,
            'name' => 'required|string|max:100|unique:service_types,service_type,' . $this->serviceTypeId . ',id,department_id,' . $this->department_id,
            'description' => 'nullable|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'Selected department is invalid.',
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
     * Reset pagination when department filter changes
     */
    public function updatedDepartmentFilter()
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
            $this->selectedItems = $this->getServiceTypesProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
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
     * Open modal for creating new service type
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
     * Open modal for editing service type
     *
     * @param int $id
     */
    public function edit($id)
    {
        $this->resetForm();
        $serviceType = ServiceType::findOrFail($id);

        $this->serviceTypeId = $serviceType->id;
        // $this->code = ''; // Not used, but keep for form compatibility
        $this->name = $serviceType->service_type; // Get from service_type field
        $this->description = $serviceType->description;
        $this->department_id = $serviceType->department_id;
        $this->is_active = $serviceType->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    // ==========================================
    // Offcanvas Methods
    // ==========================================

    /**
     * Open offcanvas to view service type details
     *
     * @param int $id
     */
    public function view($id)
    {
        $this->viewServiceType = ServiceType::with(['department', 'creator', 'updater', 'tickets'])->findOrFail($id);
        $this->showOffcanvas = true;
    }

    /**
     * Close view offcanvas
     */
    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewServiceType = null;
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
     * Save service type (create or update)
     */
    public function save()
    {
        $this->validate();
        // dd('validate passed');

        try {
            // Prepare data - note: ServiceType model uses 'service_type' field, not 'code' and 'name'
            $data = [
                'service_type' => $this->name, // Store name in service_type field
                'description' => $this->description,
                'department_id' => $this->department_id,
                'is_active' => $this->is_active,
            ];

            if ($this->editMode) {
                // Update existing service type
                $serviceType = ServiceType::findOrFail($this->serviceTypeId);
                $data['updated_by'] = auth()->id();
                $serviceType->update($data);

                $this->dispatch('toast', type: 'success', message: 'Service Type updated successfully!');
            } else {
                // Create new service type
                $data['created_by'] = auth()->id();
                ServiceType::create($data);

                $this->dispatch('toast', type: 'success', message: 'Service Type created successfully!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Delete service type
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
            $serviceType = ServiceType::findOrFail($id);

            // Check if service type is being used in tickets (not transactions)
            $usageCount = $serviceType->tickets()->count();

            if ($usageCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete Service Type. It is being used in {$usageCount} ticket(s).");
                $this->cancelDelete();
                return;
            }

            // Delete the service type
            $serviceType->delete();
            $this->dispatch('toast', type: 'success', message: 'Service Type deleted successfully!');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    /**
     * Delete selected service types
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected.');
            return;
        }

        try {
            // Check if any selected service type is being used
            $usedServiceTypes = ServiceType::whereIn('id', $this->selectedItems)
                ->withCount('tickets')
                ->get()
                ->filter(fn($serviceType) => $serviceType->tickets_count > 0);

            if ($usedServiceTypes->count() > 0) {
                $this->dispatch('toast', type: 'error', message: 'Some Service Types cannot be deleted as they are being used in tickets.');
                return;
            }

            // Delete selected service types
            ServiceType::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: 'Selected Service Types deleted successfully!');

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
        $this->serviceTypeId = null;
        // $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->department_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    /**
     * Get filtered and paginated service types
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getServiceTypesProperty()
    {
        return ServiceType::query()
            // Search filter - search in service_type field
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('service_type', 'like', '%' . $this->search . '%');
                });
            })
            // Status filter
            ->when($this->statusFilter !== '', function ($query) {
                $isActive = $this->statusFilter === 'Active';
                $query->where('is_active', $isActive);
            })
            // Department filter
            ->when($this->departmentFilter, function ($query) {
                $query->where('department_id', $this->departmentFilter);
            })
            // Load relationships
            ->with('department')
            ->withCount('tickets')
            // Sorting - sort by service_type field
            ->orderBy('service_type', $this->sortDirection)
            // Pagination
            ->paginate($this->perPage);
    }

    /**
     * Get active departments for dropdown
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDepartmentsProperty()
    {
        return Department::where('is_active', true)
            ->orderBy('department')
            ->get();
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
        $serviceTypes = $this->getServiceTypesProperty();
        $departments = $this->getDepartmentsProperty();

        // Get statistics
        $stats = [
            'total' => ServiceType::count(),
            'active' => ServiceType::where('is_active', true)->count(),
            'inactive' => ServiceType::where('is_active', false)->count(),
        ];

        return view('livewire.masters.service-types.index', [
            'serviceTypes' => $serviceTypes,
            'departments' => $departments,
            'stats' => $stats,
            'title' => 'Service Type Management',
        ]);
    }
}
