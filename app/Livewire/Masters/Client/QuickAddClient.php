<?php
// app/Livewire/Masters/Client/QuickAddClient.php

namespace App\Livewire\Masters\Client;

use Livewire\Component;
use App\Models\Client;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class QuickAddClient extends Component
{
    // ==========================================
    // Properties
    // ==========================================
    
    public $departmentId;
    
    // Form Fields
    public $client_name = '';
    public $company_name = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $is_active = true;

    // ==========================================
    // Validation Rules
    // ==========================================
    
    protected function rules()
    {
        return [
            'departmentId' => 'required|exists:departments,id',
            'client_name' => 'required|string|max:100',
            'company_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'departmentId.required' => 'Department is required.',
        'client_name.required' => 'Client name is required.',
        'client_name.max' => 'Client name cannot exceed 100 characters.',
        'email.email' => 'Please enter a valid email address.',
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
            $client = Client::create([
                'department_id' => $this->departmentId,
                'client_name' => $this->client_name,
                'company_name' => $this->company_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
            ]);

            // Dispatch event to parent component
            $this->dispatch('client-created', clientId: $client->id);
            
            // Close modal
            $this->closeModal();
            
        } catch (\Exception $e) {
            \Log::error('Error creating client: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Failed to create client: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->reset([
            'client_name',
            'company_name', 
            'phone',
            'email',
            'address',
            'is_active'
        ]);
        $this->resetValidation();
        $this->dispatch('close-quick-add-client');
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
        return view('livewire.masters.client.quick-add-client', [
            'department' => $this->department,
        ]);
    }
}
