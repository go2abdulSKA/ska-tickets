<?php
// app/Livewire/Masters/ServiceTypes/QuickAddServiceType.php

namespace App\Livewire\Masters\ServiceTypes;

use Livewire\Component;
use App\Models\Department;
use App\Models\ServiceType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class QuickAddServiceType extends Component
{
    // ==========================================
    // Properties
    // ==========================================

    public $departmentId;

    // Form Fields
    public $service_type = '';
    public $description = '';
    public $is_active = true;

    // ==========================================
    // Validation Rules
    // ==========================================

    protected function rules()
    {
        return [
            'departmentId' => 'required|exists:departments,id',
            'service_type' => [
                'required',
                'string',
                'max:100',
                // Prevent duplicate service type within the same department
                Rule::unique('service_types', 'service_type')->where('department_id', $this->departmentId)->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'departmentId.required' => 'Department is required.',
        'service_type.required' => 'Service type name is required.',
        'service_type.max' => 'Service type name cannot exceed 100 characters.',
        'service_type.unique' => 'This service type already exists in this department.',
    ];

    // ==========================================
    // Lifecycle Hooks
    // ==========================================

    public function mount($departmentId = null)
    {
        $this->departmentId = $departmentId;
    }

    // ==========================================
    // Actions
    // ==========================================

    public function save()
    {
        $this->validate();

        try {
            $serviceType = ServiceType::create([
                'department_id' => $this->departmentId,
                'service_type' => $this->service_type,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
            ]);

            // Log for debugging
            \Log::info('Service Type created with ID: ' . $serviceType->id);

            // Dispatch event to parent component WITH service type data
            $this->dispatch('service-type-created', [
                'serviceTypeId' => $serviceType->id,
                'serviceTypeName' => $serviceType->service_type,
            ]);

            // Close modal
            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Error creating service type: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Failed to create service type: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->reset(['service_type', 'description']);
        $this->resetValidation();

        // Emit close event
        $this->dispatch('close-quick-add-service-type');
    }

    // ==========================================
    // Computed Properties
    // ==========================================

    public function getDepartmentProperty()
    {
        return Department::find($this->departmentId);
    }

    // ==========================================
    // Render
    // ==========================================

    public function render()
    {
        return view('livewire.masters.service-types.quick-add-service-type', [
            'department' => $this->department,
        ]);
    }
}
