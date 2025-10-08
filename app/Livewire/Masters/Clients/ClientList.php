<?php
// app/Livewire/Masters/Clients/ClientList.php

namespace App\Livewire\Masters\Clients;

use App\Livewire\Components\DataTable;
use App\Models\Client;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

/**
 * Client List Component
 *
 * Displays all clients in a data table with CRUD operations
 * Filters clients by department based on user access
 */
class ClientList extends DataTable
{
    // Entity name for generic components
    public $entityName = 'Client';

    // Form properties
    public $clientId;
    public $department_id = '';
    public $client_name = '';
    public $company_name = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $is_active = true;

    // View detail properties
    public $viewClient;

    // Available departments
    public $departments = [];

    // Filter properties
    public $filterDepartment = '';
    public $filterStatus = '';

    /**
     * Livewire listeners
     */
    protected $listeners = ['refreshClients' => '$refresh'];

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
            'client_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Custom attribute names for validation errors
     */
    protected $validationAttributes = [
        'department_id' => 'department',
        'client_name' => 'client name',
        'company_name' => 'company name',
    ];

    /**
     * Get query for data table
     */
    protected function getQuery()
    {
        $user = Auth::user();

        $query = Client::with(['department'])
            ->when(!$user->isSuperAdmin(), function ($q) use ($user) {
                // Non-super admins only see clients from their departments
                $q->whereIn('department_id', $user->getDepartmentIds());
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query
                        ->where('client_name', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
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
     * Open modal for creating new client
     */
public function create()
    {
        $this->resetForm();
        $this->resetValidation();

        // Dispatch browser event that JavaScript can catch
        $this->js("
            const modal = new bootstrap.Modal(document.getElementById('clientModal'));
            modal.show();
        ");
    }

    /**
     * Open modal for editing client
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);

        // Check if user can edit this client
        if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($client->department_id)) {
            session()->flash('error', 'You do not have permission to edit this client.');
            return;
        }

        $this->clientId = $client->id;
        $this->department_id = $client->department_id;
        $this->client_name = $client->client_name;
        $this->company_name = $client->company_name;
        $this->phone = $client->phone;
        $this->email = $client->email;
        $this->address = $client->address;
        $this->is_active = $client->is_active;

        $this->resetValidation();
        $this->dispatch('openModal');
    }

    /**
     * Save client (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'department_id' => $this->department_id,
                'client_name' => $this->client_name,
                'company_name' => $this->company_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'is_active' => $this->is_active,
            ];

            if ($this->clientId) {
                // Update existing client
                $client = Client::findOrFail($this->clientId);

                // Check permission
                if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($client->department_id)) {
                    session()->flash('error', 'You do not have permission to edit this client.');
                    return;
                }

                $data['updated_by'] = Auth::id();
                $client->update($data);
                $message = 'Client updated successfully.';
            } else {
                // Create new client
                $data['created_by'] = Auth::id();
                Client::create($data);
                $message = 'Client created successfully.';
            }

            session()->flash('success', $message);
            $this->dispatch('closeModal');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * View client details in offcanvas
     */
    public function view($id)
    {
        $this->viewClient = Client::with(['department', 'creator', 'updater'])->findOrFail($id);
        $this->dispatch('openOffcanvas');
    }

    /**
     * Delete client
     */
    public function delete($id)
    {
        try {
            $client = Client::findOrFail($id);

            // Check permission
            if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($client->department_id)) {
                session()->flash('error', 'You do not have permission to delete this client.');
                return;
            }

            // Check if client has tickets
            if ($client->tickets()->count() > 0) {
                session()->flash('error', 'Cannot delete client with existing tickets.');
                return;
            }

            $client->delete();
            session()->flash('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle client active status
     */
    public function toggleStatus($id)
    {
        try {
            $client = Client::findOrFail($id);

            // Check permission
            if (!Auth::user()->isSuperAdmin() && !Auth::user()->belongsToDepartment($client->department_id)) {
                session()->flash('error', 'You do not have permission to modify this client.');
                return;
            }

            $client->update([
                'is_active' => !$client->is_active,
                'updated_by' => Auth::id(),
            ]);

            $status = $client->is_active ? 'activated' : 'deactivated';
            session()->flash('success', "Client {$status} successfully.");

            // Refresh the offcanvas view if open
            if ($this->viewClient && $this->viewClient->id === $id) {
                $this->viewClient = Client::with(['department', 'creator', 'updater'])->findOrFail($id);
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
        $this->clientId = null;
        $this->department_id = $this->departments->count() === 1 ? $this->departments->first()->id : '';
        $this->client_name = '';
        $this->company_name = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->is_active = true;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.masters.clients.client-list', [
            'clients' => $this->getData(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Clients',
        ]);
    }
}
