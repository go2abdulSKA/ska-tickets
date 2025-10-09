<?php
// app/Livewire/Masters/Department/DepartmentList.php

namespace App\Livewire\Masters\Department;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

/**
 * Department List Component
 *
 * Manages CRUD operations for Departments
 * Follows the exact same pattern as CostCenter CRUD
 */
class DepartmentList extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    // ==========================================
    // Properties
    // ==========================================

    /** @var string Search query */
    public $search = '';

    /** @var string Status filter (empty, 'Active', 'Inactive') */
    public $statusFilter = '';

    /** @var string Sort field */
    public $sortField = 'department';

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

    /** @var int|null Department ID being edited */
    public $departmentId = null;

    // ==========================================
    // Form Properties
    // ==========================================

    /** @var string Department name */
    public $department = '';

    /** @var string Short name */
    public $short_name = '';

    /** @var string Prefix */
    public $prefix = '';

    /** @var string Form name */
    public $form_name = '';

    /** @var string Notes */
    public $notes = '';

    /** @var bool Active status */
    public $is_active = true;

    /** @var mixed Logo file */
    public $logo = null;

    /** @var string Existing logo path */
    public $existing_logo = '';

    /** @var string Selected predefined logo */
    public $selectedPredefinedLogo = '';

    /** @var bool Show custom upload section */
    public $showCustomUpload = false;

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

    /** @var Department|null Department being viewed */
    public $viewDepartment = null;

    // ==========================================
    // Delete Modal Properties
    // ==========================================

    /** @var int|null Department ID to delete */
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
            'department' => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'prefix' => 'required|string|max:10|unique:departments,prefix,' . $this->departmentId,
            'form_name' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'selectedPredefinedLogo' => 'nullable|string',
        ];
    }

    // ==========================================
    // Predefined Logos Methods
    // ==========================================

    /**
     * Get list of predefined logos
     *
     * @return array
     */
    public function getPredefinedLogos()
    {
        $predefinedPath = public_path('storage/logos/predefined');
        
        // Create directory if it doesn't exist
        if (!file_exists($predefinedPath)) {
            mkdir($predefinedPath, 0755, true);
        }

        $logos = [];
        $files = glob($predefinedPath . '/*.{jpg,jpeg,png,gif,svg}', GLOB_BRACE);
        
        foreach ($files as $file) {
            $filename = basename($file);
            $name = ucwords(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
            
            $logos[] = [
                'path' => 'logos/predefined/' . $filename,
                'name' => $name,
                'filename' => $filename,
            ];
        }

        return $logos;
    }

    /**
     * Handle predefined logo selection change
     */
    public function updatedSelectedPredefinedLogo($value)
    {
        if ($value === 'custom') {
            $this->showCustomUpload = true;
        } else {
            $this->showCustomUpload = false;
            $this->logo = null;
        }
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
            $this->selectedItems = $this->getDepartmentsProperty()->pluck('id')->map(fn($id) => (string) $id)->toArray();
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
     * Open modal for creating new department
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
     * Open modal for editing department
     *
     * @param int $id
     */
    public function edit($id)
    {
        $this->resetForm();
        $department = Department::findOrFail($id);

        $this->departmentId = $department->id;
        $this->department = $department->department;
        $this->short_name = $department->short_name;
        $this->prefix = $department->prefix;
        $this->form_name = $department->form_name;
        $this->notes = $department->notes;
        $this->is_active = $department->is_active;
        $this->existing_logo = $department->logo_path;

        $this->editMode = true;
        $this->showModal = true;
    }

    // ==========================================
    // Offcanvas Methods
    // ==========================================

    /**
     * Open offcanvas to view department details
     *
     * @param int $id
     */
    public function view($id)
    {
        try {
            $this->viewDepartment = Department::with(['creator', 'updater'])
                ->withCount(['users', 'tickets', 'clients', 'serviceTypes'])
                ->findOrFail($id);
            
            $this->showOffcanvas = true;
            
            // Log for debugging
            \Log::info('Department viewed', [
                'id' => $id,
                'department' => $this->viewDepartment->department
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error viewing department: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Error loading department details: ' . $e->getMessage());
        }
    }

    /**
     * Close view offcanvas
     */
    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewDepartment = null;
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
     * Save department (create or update)
     */
    public function save()
    {
        // Set longer timeout for file uploads
        set_time_limit(300); // 5 minutes
        
        $this->validate();

        try {
            // Prepare data
            $data = [
                'department' => $this->department,
                'short_name' => $this->short_name,
                'prefix' => strtoupper($this->prefix),
                'form_name' => $this->form_name,
                'notes' => $this->notes,
                'is_active' => $this->is_active,
            ];

            // Handle logo
            if ($this->selectedPredefinedLogo && $this->selectedPredefinedLogo !== 'custom') {
                // Use predefined logo
                $data['logo_path'] = $this->selectedPredefinedLogo;
            } elseif ($this->logo) {
                try {
                    // Custom upload
                    if ($this->showCustomUpload) {
                        // Save to predefined folder for future use
                        $filename = time() . '_' . $this->logo->getClientOriginalName();
                        $path = $this->logo->storeAs('logos/predefined', $filename, 'public');
                        
                        if (!$path) {
                            throw new \Exception('Failed to save logo to predefined folder');
                        }
                        
                        $data['logo_path'] = $path;
                    } else {
                        // Regular upload
                        // Delete old logo if exists and it's not a predefined logo
                        if ($this->existing_logo && !str_starts_with($this->existing_logo, 'logos/predefined/')) {
                            Storage::disk('public')->delete($this->existing_logo);
                        }
                        // Store new logo
                        $path = $this->logo->store('departments/logos', 'public');
                        
                        if (!$path) {
                            throw new \Exception('Failed to save logo');
                        }
                        
                        $data['logo_path'] = $path;
                    }
                } catch (\Exception $logoError) {
                    \Log::error('Logo upload failed: ' . $logoError->getMessage());
                    $this->dispatch('toast', type: 'error', message: 'Logo upload failed: ' . $logoError->getMessage());
                    return;
                }
            }

            if ($this->editMode) {
                // Update existing department
                $department = Department::findOrFail($this->departmentId);
                $data['updated_by'] = auth()->id();
                $department->update($data);

                $this->dispatch('toast', type: 'success', message: 'Department updated successfully!');
            } else {
                // Create new department
                $data['created_by'] = auth()->id();
                Department::create($data);

                $this->dispatch('toast', type: 'success', message: 'Department created successfully!');
            }

            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error in department save: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error in department save: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Delete department
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
            $department = Department::findOrFail($id);

            // Check if department is being used
            $userCount = $department->users()->count();
            $ticketCount = $department->tickets()->count();
            $clientCount = $department->clients()->count();
            $serviceTypeCount = $department->serviceTypes()->count();
            
            $usageCount = $userCount + $ticketCount + $clientCount + $serviceTypeCount;

            if ($usageCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete Department. It is currently in use.");
                $this->cancelDelete();
                return;
            }

            // Delete logo if exists
            if ($department->logo_path) {
                Storage::disk('public')->delete($department->logo_path);
            }

            // Delete the department
            $department->delete();
            $this->dispatch('toast', type: 'success', message: 'Department deleted successfully!');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    /**
     * Delete selected departments
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', type: 'warning', message: 'No items selected.');
            return;
        }

        try {
            // Check if any selected department is being used
            $usedDepartments = Department::whereIn('id', $this->selectedItems)
                ->withCount(['users', 'tickets', 'clients', 'serviceTypes'])
                ->get()
                ->filter(fn($dept) => ($dept->users_count + $dept->tickets_count + $dept->clients_count + $dept->serviceTypes_count) > 0);

            if ($usedDepartments->count() > 0) {
                $this->dispatch('toast', type: 'error', message: 'Some Departments cannot be deleted as they are being used.');
                return;
            }

            // Delete selected departments
            $departments = Department::whereIn('id', $this->selectedItems)->get();
            foreach ($departments as $dept) {
                if ($dept->logo_path) {
                    Storage::disk('public')->delete($dept->logo_path);
                }
                $dept->delete();
            }

            $this->selectedItems = [];
            $this->selectAll = false;

            $this->dispatch('toast', type: 'success', message: 'Selected Departments deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Toggle department status
     *
     * @param int $id
     */
    public function toggleStatus($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->is_active = !$department->is_active;
            $department->updated_by = auth()->id();
            $department->save();

            $status = $department->is_active ? 'activated' : 'deactivated';
            $this->dispatch('toast', type: 'success', message: "Department {$status} successfully!");

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
        $this->departmentId = null;
        $this->department = '';
        $this->short_name = '';
        $this->prefix = '';
        $this->form_name = '';
        $this->notes = '';
        $this->is_active = true;
        $this->logo = null;
        $this->existing_logo = '';
        $this->selectedPredefinedLogo = '';
        $this->showCustomUpload = false;
        $this->resetValidation();
    }

    /**
     * Get filtered and paginated departments
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDepartmentsProperty()
    {
        return Department::query()
            // Search filter
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('department', 'like', '%' . $this->search . '%')
                      ->orWhere('short_name', 'like', '%' . $this->search . '%')
                      ->orWhere('prefix', 'like', '%' . $this->search . '%');
                });
            })
            // Status filter
            ->when($this->statusFilter !== '', function ($query) {
                $isActive = $this->statusFilter === 'Active';
                $query->where('is_active', $isActive);
            })
            // Load counts
            ->withCount(['users', 'tickets', 'clients', 'serviceTypes'])
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
        $departments = $this->getDepartmentsProperty();
        $predefinedLogos = $this->getPredefinedLogos();

        // Get statistics
        $stats = [
            'total' => Department::count(),
            'active' => Department::where('is_active', true)->count(),
            'inactive' => Department::where('is_active', false)->count(),
        ];

        return view('livewire.masters.department.index', [
            'departments' => $departments,
            'predefinedLogos' => $predefinedLogos,
            'stats' => $stats,
            'title' => 'Department Management',
        ]);
    }
}
