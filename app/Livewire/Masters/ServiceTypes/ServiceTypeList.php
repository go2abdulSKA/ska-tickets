<?php
// app/Livewire/Masters/ServiceTypes/ServiceTypeList.php

namespace App\Livewire\Masters\ServiceTypes;

use App\Livewire\Components\DataTable;
use App\Models\ServiceType;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

/**
 * Service Type List Component
 *
 * Displays all service types in a data table with CRUD operations
 * Service types are department-specific
 */
class ServiceTypeList extends DataTable
{
    public $entityName = 'Service Type';

    // Form properties
    public $serviceTypeId;
    public $department_id = '';
    public $service_type = '';
    public $description = '';
    public $is_active = true;

    // View detail properties
    public $viewServiceType;

    // Available departments
    public $departments = [];

    // Filter properties
    public $filterDepartment = '';
    public $filterStatus = '';

    /**
     * Mount the component
     */
    public function mount()
    {
        // Load departments based on user role
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $this->departments = Department::active()->get();
        } else {
            $this->departments = $user->departments;
        }

        // Auto-select department if user has only one
        if ($this->departments->count() === 1) {
            $this->department_id = $this->departments->first()->id;
        }
    }

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'service_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Custom attribute names
     */
    protected $validationAttributes = [
        'department_id' => 'department',
        'service_type' => 'service type',
    ];

    /**
     * Get query for data table
     */
    protected function getQuery()
    {
        $user = Auth::user();

        $query = ServiceType::with(['department'])
            ->when(!$user->isSuperAdmin(), function ($q) use ($user) {
                // Non-super admins only see service types from their departments
                $q->whereIn('department_id', $user->getDepartmentIds());
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('service_type', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterDepartment, function ($q) {
                $q->where('department_id', $this->filterDepartment);
            })
            ->when($this->filterStatus !== '', function ($q) {
                $q->where('is_active', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->filterDepartment = '';
        $this->filterStatus = '';
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Open modal for creating new service type
     */
    public function create()
    {
        $this->resetForm();
        $this->resetValidation();
        $this->dispatch('openModal');
    }

    /**
     * Open modal for editing service type
     */
    public function edit($id)
    {
        $serviceType = ServiceType::findOrFail($id);

        // Check if user can edit this service type
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($serviceType->department_id)) {
            session()->flash('error', 'You do not have permission to edit this service type.');
            return;
        }

        $this->serviceTypeId = $serviceType->id;
        $this->department_id = $serviceType->department_id;
        $this->service_type = $serviceType->service_type;
        $this->description = $serviceType->description;
        $this->is_active = $serviceType->is_active;

        $this->resetValidation();
        $this->dispatch('openModal');
    }

    /**
     * Save service type
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'department_id' => $this->department_id,
                'service_type' => $this->service_type,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ];

            if ($this->serviceTypeId) {
                // Update existing service type
                $serviceType = ServiceType::findOrFail($this->serviceTypeId);

                // Check permission
                if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($serviceType->department_id)) {
                    session()->flash('error', 'You do not have permission to edit this service type.');
                    return;
                }

                $data['updated_by'] = Auth::id();
                $serviceType->update($data);
                $message = 'Service type updated successfully.';
            } else {
                // Create new service type
                $data['created_by'] = Auth::id();
                ServiceType::create($data);
                $message = 'Service type created successfully.';
            }

            session()->flash('success', $message);
            $this->dispatch('closeModal');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * View service type details
     */
    public function view($id)
    {
        $this->viewServiceType = ServiceType::with(['department', 'creator', 'updater'])->findOrFail($id);
        $this->dispatch('openOffcanvas');
    }

    /**
     * Delete service type
     */
    public function delete($id)
    {
        try {
            $serviceType = ServiceType::findOrFail($id);

            // Check permission
            if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($serviceType->department_id)) {
                session()->flash('error', 'You do not have permission to delete this service type.');
                return;
            }

            // Check if service type has tickets
            if ($serviceType->tickets()->count() > 0) {
                session()->flash('error', 'Cannot delete service type with existing tickets.');
                return;
            }

            $serviceType->delete();
            session()->flash('success', 'Service type deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle service type active status
     */
    public function toggleStatus($id)
    {
        try {
            $serviceType = ServiceType::findOrFail($id);

            // Check permission
            if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($serviceType->department_id)) {
                session()->flash('error', 'You do not have permission to modify this service type.');
                return;
            }

            $serviceType->update([
                'is_active' => !$serviceType->is_active,
                'updated_by' => Auth::id(),
            ]);

            $status = $serviceType->is_active ? 'activated' : 'deactivated';
            session()->flash('success', "Service type {$status} successfully.");

            if ($this->viewServiceType && $this->viewServiceType->id === $id) {
                $this->viewServiceType = ServiceType::with(['department', 'creator', 'updater'])->findOrFail($id);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Reset form
     */
    private function resetForm()
    {
        $this->serviceTypeId = null;
        $this->department_id = $this->departments->count() === 1 ? $this->departments->first()->id : '';
        $this->service_type = '';
        $this->description = '';
        $this->is_active = true;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.masters.service-types.service-type-list', [
            'serviceTypes' => $this->getData(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Service Types',
        ]);
    }
}
