<?php
// app/Livewire/Masters/Uom/QuickAddUom.php

namespace App\Livewire\Masters\Uom;

use Livewire\Component;
use App\Models\Uom;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class QuickAddUom extends Component
{
    // ==========================================
    // Properties
    // ==========================================

    // Form Fields
    public $code = '';
    public $name = '';
    public $description = '';
    public $is_active = true;

    // ==========================================
    // Validation Rules
    // ==========================================

    protected function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('uom', 'code')->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('uom', 'name')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'code.required' => 'UOM code is required.',
        'code.max' => 'UOM code cannot exceed 10 characters.',
        'code.unique' => 'This UOM code already exists.',
        'name.required' => 'UOM name is required.',
        'name.max' => 'UOM name cannot exceed 100 characters.',
        'name.unique' => 'This UOM name already exists.',
    ];

    // ==========================================
    // Actions
    // ==========================================

    public function save()
    {
        $this->validate();

        try {
            $uom = Uom::create([
                'code' => strtoupper($this->code),
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
            ]);

            // Log for debugging
            \Log::info('UOM created with ID: ' . $uom->id);

            // Dispatch event to parent component WITH UOM data
            $this->dispatch('uom-created', [
                'uomId' => $uom->id,
                'uomName' => $uom->code . ' - ' . $uom->name,
            ]);

            // Dispatch toast notification
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'UOM created successfully!',
                'title' => 'Success'
            ]);

            // Close modal
            $this->closeModal();
        } catch (\Exception $e) {
            \Log::error('Error creating UOM: ' . $e->getMessage());
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Failed to create UOM: ' . $e->getMessage(),
                'title' => 'Error'
            ]);
        }
    }

    public function closeModal()
    {
        $this->reset(['code', 'name', 'description']);
        $this->is_active = true;
        $this->resetValidation();

        // Emit close event
        $this->dispatch('close-quick-add-uom');
    }

    // ==========================================
    // Render
    // ==========================================

    public function render()
    {
        return view('livewire.masters.uom.quick-add-uom');
    }
}
