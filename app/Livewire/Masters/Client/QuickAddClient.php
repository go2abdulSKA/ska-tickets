<?php
// app/Livewire/Masters/Client/QuickAddClient.php

namespace App\Livewire\Masters\Client;

use App\Models\Client;
use Livewire\Component;
use App\Models\Department;
use Illuminate\Validation\Rule;
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
            'client_name' => [
                'required',
                'string',
                'max:100',
                // Prevent duplicate client name within the same department
                Rule::unique('clients', 'client_name')->where('department_id', $this->departmentId)->whereNull('deleted_at'),
            ],
            'company_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:100',
                // Prevent duplicate email
                \Illuminate\Validation\Rule::unique('clients', 'email')->whereNull('deleted_at')->whereNotNull('email'), // Only check if email is provided
            ],
            'address' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'departmentId.required' => 'Department is required.',
        'client_name.required' => 'Client name is required.',
        'client_name.max' => 'Client name cannot exceed 100 characters.',
        'client_name.unique' => 'A client with this name already exists in this department.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already registered.',
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

            // Log for debugging
            \Log::info('Client created with ID: ' . $client->id);

            // Dispatch event to parent component WITH client data
            $this->dispatch('client-created', [
                'clientId' => $client->id,
                'clientName' => $client->client_name,
                'companyName' => $client->company_name,
            ]);

            // Close modal
            $this->closeModal();

        } catch (\Exception $e) {
            \Log::error('Error creating client: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Failed to create client: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->reset(['client_name', 'company_name', 'phone', 'email', 'address', 'is_active']);
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
