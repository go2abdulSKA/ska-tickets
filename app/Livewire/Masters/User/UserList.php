<?php
// app/Livewire/Masters/User/UserList.php

namespace App\Livewire\Masters\User;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * User List Component
 *
 * Manages CRUD operations for Users with Jetstream integration
 */
class UserList extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    // ==========================================
    // Properties
    // ==========================================

    public $search = '';
    public $statusFilter = '';
    public $roleFilter = '';
    public $departmentFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // ==========================================
    // Modal Properties
    // ==========================================

    public $showModal = false;
    public $editMode = false;
    public $userId = null;

    // ==========================================
    // Form Properties
    // ==========================================

    /** @var string User name */
    public $name = '';

    /** @var string Email */
    public $email = '';

    /** @var string Password */
    public $password = '';

    /** @var string Password confirmation */
    public $password_confirmation = '';

    /** @var int Role ID */
    public $role_id = '';

    /** @var string Phone */
    public $phone = '';

    /** @var bool Active status */
    public $is_active = true;

    /** @var mixed Profile photo file upload */
    public $profile_photo_path;

    /** @var string Existing photo path */
    public $existing_photo = '';
    
    /** @var array Department assignments */
    public $selectedDepartments = [];

    // ==========================================
    // View Properties
    // ==========================================

    public $showOffcanvas = false;
    public $viewUser = null;

    // ==========================================
    // Delete Properties
    // ==========================================

    public $deleteId = null;
    public $showDeleteModal = false;

    protected $paginationTheme = 'bootstrap';

    // ==========================================
    // Validation Rules
    // ==========================================

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'selectedDepartments' => 'required|array|min:1',
            'profile_photo_path' => 'nullable|image|max:2048',
        ];

        // Password required only when creating new user
        if (!$this->editMode) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } elseif ($this->password) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    // ==========================================
    // Lifecycle Hooks
    // ==========================================

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // ==========================================
    // Sorting Methods
    // ==========================================

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
        $user = User::with('departments')->findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->phone = $user->phone;
        $this->is_active = $user->is_active;
        $this->existing_photo = $user->profile_photo_path;
        $this->selectedDepartments = $user->departments->pluck('id')->toArray();

        $this->editMode = true;
        $this->showModal = true;
    }

    // ==========================================
    // Offcanvas Methods
    // ==========================================

    public function view($id)
    {
        try {
            $this->viewUser = User::with(['role', 'departments', 'creator', 'updater'])
                ->withCount(['tickets'])
                ->findOrFail($id);
            
            $this->showOffcanvas = true;
            
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error loading user details');
        }
    }

    public function closeOffcanvas()
    {
        $this->showOffcanvas = false;
        $this->viewUser = null;
    }

    // ==========================================
    // Delete Methods
    // ==========================================

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

    // ==========================================
    // CRUD Operations
    // ==========================================

    public function save()
    {
        // Set longer timeout for file uploads
        set_time_limit(300);
        
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
            ];

            // Handle password
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            // CRITICAL: Handle profile photo upload
            if ($this->profile_photo_path && is_object($this->profile_photo_path)) {
                try {
                    \Log::info('Processing profile photo upload', [
                        'original_name' => $this->profile_photo_path->getClientOriginalName(),
                        'size' => $this->profile_photo_path->getSize()
                    ]);
                    
                    // Delete old photo if exists
                    if ($this->existing_photo && Storage::disk('public')->exists($this->existing_photo)) {
                        Storage::disk('public')->delete($this->existing_photo);
                        \Log::info('Deleted old photo: ' . $this->existing_photo);
                    }
                    
                    // Store new photo
                    $path = $this->profile_photo_path->store('profile-photos', 'public');
                    
                    if (!$path) {
                        throw new \Exception('Failed to save profile photo - store returned false');
                    }
                    
                    $data['profile_photo_path'] = $path;
                    \Log::info('Profile photo saved successfully: ' . $path);
                    
                } catch (\Exception $photoError) {
                    \Log::error('Profile photo upload failed', [
                        'error' => $photoError->getMessage(),
                        'trace' => $photoError->getTraceAsString()
                    ]);
                    $this->dispatch('toast', type: 'error', message: 'Photo upload failed: ' . $photoError->getMessage());
                    return;
                }
            }

            if ($this->editMode) {
                $user = User::findOrFail($this->userId);
                $data['updated_by'] = auth()->id();
                $user->update($data);
                
                \Log::info('User updated', ['user_id' => $user->id, 'has_photo' => isset($data['profile_photo_path'])]);
                
                // Sync departments
                $user->departments()->sync($this->selectedDepartments);

                $this->dispatch('toast', type: 'success', message: 'User updated successfully!');
            } else {
                $data['created_by'] = auth()->id();
                $user = User::create($data);
                
                \Log::info('User created', ['user_id' => $user->id, 'has_photo' => isset($data['profile_photo_path'])]);
                
                // Sync departments
                $user->departments()->sync($this->selectedDepartments);

                $this->dispatch('toast', type: 'success', message: 'User created successfully!');
            }

            $this->closeModal();
            
            // Force refresh
            $this->dispatch('$refresh');

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error in user save: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error in user save: ' . $e->getMessage());
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
            $user = User::findOrFail($id);

            // Prevent deleting yourself
            if ($user->id === auth()->id()) {
                $this->dispatch('toast', type: 'error', message: 'You cannot delete your own account!');
                $this->cancelDelete();
                return;
            }

            // Check if user has tickets
            $ticketCount = $user->tickets()->count();

            if ($ticketCount > 0) {
                $this->dispatch('toast', type: 'error', message: "Cannot delete user. They have {$ticketCount} ticket(s).");
                $this->cancelDelete();
                return;
            }

            // Delete profile photo
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->delete();
            $this->dispatch('toast', type: 'success', message: 'User deleted successfully!');
            $this->cancelDelete();

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deactivating yourself
            if ($user->id === auth()->id()) {
                $this->dispatch('toast', type: 'error', message: 'You cannot deactivate your own account!');
                return;
            }
            
            $user->is_active = !$user->is_active;
            $user->updated_by = auth()->id();
            $user->save();

            $status = $user->is_active ? 'activated' : 'deactivated';
            $this->dispatch('toast', type: 'success', message: "User {$status} successfully!");

        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'An error occurred: ' . $e->getMessage());
        }
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_id = '';
        $this->phone = '';
        $this->is_active = true;
        $this->profile_photo_path = null;
        $this->existing_photo = '';
        $this->selectedDepartments = [];
        $this->resetValidation();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->with(['role', 'departments'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $isActive = $this->statusFilter === 'Active';
                $query->where('is_active', $isActive);
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role_id', $this->roleFilter);
            })
            ->when($this->departmentFilter, function ($query) {
                $query->whereHas('departments', function ($q) {
                    $q->where('departments.id', $this->departmentFilter);
                });
            })
            ->withCount('tickets')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // ==========================================
    // Render Method
    // ==========================================

    public function render()
    {
        $users = $this->getUsersProperty();
        $roles = Role::all();
        $departments = Department::where('is_active', true)->orderBy('department')->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];

        return view('livewire.masters.user.index', [
            'users' => $users,
            'roles' => $roles,
            'departments' => $departments,
            'stats' => $stats,
            'title' => 'User Management',
        ]);
    }
}
