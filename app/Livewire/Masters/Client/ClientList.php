<?php
// app/Livewire/Masters/Client/ClientList.php

namespace App\Livewire\Masters\Client;

use App\Models\Client;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

/**
 * Client List Component
 *
 * Manages CRUD operations for Clients
 * Follows the exact same pattern as Department CRUD
 */
class ClientList extends Component
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
    public $sortField = 'client_name';

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

    /** @var int|null Client ID being edited */
    public $clientId = null;

    // ==========================================
    // Form Properties
    // ==========================================

    /** @var int Department ID */
    public $department_id = '';

    /** @var string Client name */
    public $client_name = '';

    /** @var string Company name */
    public $company_name = '';

    /** @var string Phone */
    public $phone = '';

    /** @var string Email */
    public $email = '';

    /** @var string Address */
    public $address = '';

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

    /** @var Client|null Client being viewed */
    public $viewClient = null;

    // ==========================================
    // Delete Modal Properties
    // ==========================================

    /** @var int|null Client ID to delete */
    public $deleteId = null;

    /** @var bool Show delete confirmation modal */
    public $showDeleteModal = false;

    /** @var bool Show bulk delete confirmation modal */
    public $showBulkDeleteModal = false;

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
            'department_id' => 'required|exists:departments,id',
            'client_name' => 'required|string|max:100',
            'company_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
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
            $this->selectedItems = $this->getClientsProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
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
     * Open modal for creating new client
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
     * Open modal for editing client
     *
     * @param int $id
     */
    public function edit($id)
    {
        $this->resetForm();
        $client = Client::findOrFail($id);

        $this->clientId = $client->id;
        $this->department_id = $client->department_id;
        $this->client_name = $client->client_name;
        $this->company_name = $client->company_name;
        $this->phone = $client->phone;
        $this->email = $client->email;
        $this->address = $client->address;
        $this->is_active = $client->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    // ==========================================
    // Offcanvas Methods
    // ==========================================

    /**
     * Open offcanvas to view client details
     *
     * @param int $id
     */
    public function view($id)
    {
        try {
            $this->viewClient = Client::with(['department', 'creator', 'updater'])
                ->withCount(['tickets'])
                ->findOrFail($id);
            
            $this->showOffcanvas = true;
            
            \Log::info('Client viewed', [
                'id' => $id,
                'client' => $this->viewClient->client_name
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error viewing client: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Error loading client details: ' . $e->getMessage());
        }
    }

    /**
     * Close view offcanvas
     */
    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewClient = null;
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

    /**
     * Show bulk delete confirmation modal
     */
    public function confirmBulkDelete()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected.');
            return;
        }
        
        $this->showBulkDeleteModal = true;
    }

    /**
     * Cancel bulk delete operation
     */
    public function cancelBulkDelete()
    {
        $this->showBulkDeleteModal = false;
    }

    // ==========================================
    // CRUD Operations
    // ==========================================

    /**
     * Save client (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            // Prepare data
            $data = [
                'department_id' => $this->department_id,
                'client_name' => $this->client_name,
                'company_name' => $this->company_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'is_active' => $this->is_active,
            ];

            if ($this->editMode) {
                // Update existing client
                $client = Client::findOrFail($this->clientId);
                $data['updated_by'] = auth()->id();
                $client->update($data);

                $this->dispatch('toast', type: 'success', message: 'Client updated successfully!');
            } else {
                // Create new client
                $data['created_by'] = auth()->id();
                Client::create($data);

                $this->dispatch('toast', type: 'success', message: 'Client created successfully!');
            }

            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Delete client
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
            $client = Client::findOrFail($id);

            // Check if client is being used in tickets
            $ticketCount = $client->tickets()->count();

            if ($ticketCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete Client. It is being used in {$ticketCount} ticket(s).");
                $this->cancelDelete();
                return;
            }

            // Delete the client
            $client->delete();
            $this->dispatch('toast', type: 'success', message: 'Client deleted successfully!');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    /**
     * Delete selected clients
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected.');
            return;
        }

        try {
            // Check if any selected client is being used
            $usedClients = Client::whereIn('id', $this->selectedItems)
                ->withCount('tickets')
                ->get()
                ->filter(fn($client) => $client->tickets_count > 0);

            if ($usedClients->count() > 0) {
                $this->dispatch('toast', type: 'error', message: 'Some Clients cannot be deleted as they are being used in tickets.');
                $this->cancelBulkDelete();
                return;
            }

            // Delete selected clients
            Client::whereIn('id', $this->selectedItems)->delete();
            
            $count = count($this->selectedItems);
            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: "{$count} Clients deleted successfully!");
            $this->cancelBulkDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelBulkDelete();
        }
    }

    /**
     * Toggle client status
     *
     * @param int $id
     */
    public function toggleStatus($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->is_active = !$client->is_active;
            $client->updated_by = auth()->id();
            $client->save();

            $status = $client->is_active ? 'activated' : 'deactivated';
            $this->dispatch('toast', type: 'success', message: "Client {$status} successfully!");

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
        $this->clientId = null;
        $this->department_id = '';
        $this->client_name = '';
        $this->company_name = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    /**
     * Get filtered and paginated clients
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getClientsProperty()
    {
        return Client::query()
            ->with('department')
            // Search filter
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('client_name', 'like', '%' . $this->search . '%')
                      ->orWhere('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
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
            // Load counts
            ->withCount('tickets')
            // Sorting
            ->orderBy($this->sortField, $this->sortDirection)
            // Pagination
            ->paginate($this->perPage);
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
        $clients = $this->getClientsProperty();
        $departments = Department::where('is_active', true)->orderBy('department')->get();

        // Get statistics
        $stats = [
            'total' => Client::count(),
            'active' => Client::where('is_active', true)->count(),
            'inactive' => Client::where('is_active', false)->count(),
        ];

        return view('livewire.masters.client.index', [
            'clients' => $clients,
            'departments' => $departments,
            'stats' => $stats,
            'title' => 'Client Management',
        ]);
    }
}
