<?php
// app/Livewire/Masters/Users/UserList.php

namespace App\Livewire\Masters\Users;

use App\Livewire\Components\DataTable;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

/**
 * User List Component
 *
 * Displays all users in a data table with CRUD operations
 * Only accessible by Super Admin and Admin users
 */
class UserList extends DataTable
{
    use WithFileUploads;

    public $entityName = 'User';

    // Form properties
    public $userId;
    public $name = '';
    public $full_name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = '';
    public $phone = '';
    public $address = '';
    public $country = 'Iraq';
    public $profile_photo;
    public $existing_profile_photo = '';
    public $is_active = true;

    // Department assignments (array of department IDs)
    public $selectedDepartments = [];

    // View detail properties
    public $viewUser;

    // Available options
    public $roles = [];
    public $departments = [];

    // Filter properties
    public $filterRole = '';
    public $filterDepartment = '';
    public $filterStatus = '';

    /**
     * Mount the component
     */
    public function mount()
    {
        // Load roles
        $this->roles = Role::all();

        // Load departments
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $this->departments = Department::active()->get();
        } else {
            $this->departments = $user->departments;
        }
    }

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'profile_photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'selectedDepartments' => 'required|array|min:1',
            'selectedDepartments.*' => 'exists:departments,id',
        ];

        // Password is required only when creating new user
        if (!$this->userId) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        } else {
            // When editing, password is optional
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Custom attribute names
     */
    protected $validationAttributes = [
        'name' => 'username',
        'full_name' => 'full name',
        'role_id' => 'role',
        'selectedDepartments' => 'departments',
    ];

    /**
     * Get query for data table
     */
    protected function getQuery()
    {
        $user = Auth::user();

        $query = User::with(['role', 'departments'])
            ->when(!$user->isSuperAdmin(), function ($q) use ($user) {
                // Non-super admins only see users from their departments
                $q->whereHas('departments', function ($query) use ($user) {
                    $query->whereIn('department_id', $user->getDepartmentIds());
                });
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($q) {
                $q->where('role_id', $this->filterRole);
            })
            ->when($this->filterDepartment, function ($q) {
                $q->whereHas('departments', function ($query) {
                    $query->where('department_id', $this->filterDepartment);
                });
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
        $this->filterRole = '';
        $this->filterDepartment = '';
        $this->filterStatus = '';
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Open modal for creating new user
     */
    public function create()
    {
        $this->resetForm();
        $this->resetValidation();
        $this->dispatch('openModal');
    }

    /**
     * Open modal for editing user
     */
    public function edit($id)
    {
        $user = User::with('departments')->findOrFail($id);

        // Check permissions
        if (!Auth::user()->isSuperAdmin() && $user->isSuperAdmin()) {
            session()->flash('error', 'You cannot edit super admin users.');
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->full_name = $user->full_name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->country = $user->country;
        $this->existing_profile_photo = $user->profile_photo;
        $this->is_active = $user->is_active;
        $this->selectedDepartments = $user->departments->pluck('id')->toArray();

        // Clear password fields when editing
        $this->password = '';
        $this->password_confirmation = '';

        $this->resetValidation();
        $this->dispatch('openModal');
    }

    /**
     * Save user
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'full_name' => $this->full_name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'phone' => $this->phone,
                'address' => $this->address,
                'country' => $this->country,
                'is_active' => $this->is_active,
            ];

            // Handle password
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            // Handle profile photo upload
            if ($this->profile_photo) {
                $photoPath = $this->profile_photo->store('profile-photos', 'public');
                $data['profile_photo'] = $photoPath;
            }

            if ($this->userId) {
                // Update existing user
                $user = User::findOrFail($this->userId);

                // Check permissions
                if (!Auth::user()->isSuperAdmin() && $user->isSuperAdmin()) {
                    session()->flash('error', 'You cannot edit super admin users.');
                    return;
                }

                $data['updated_by'] = Auth::id();
                $user->update($data);

                // Sync departments
                $user->departments()->sync($this->selectedDepartments);

                $message = 'User updated successfully.';
            } else {
                // Create new user
                $data['created_by'] = Auth::id();
                $data['email_verified_at'] = now(); // Auto-verify

                $user = User::create($data);

                // Attach departments
                $user->departments()->attach($this->selectedDepartments);

                $message = 'User created successfully.';
            }

            session()->flash('success', $message);
            $this->dispatch('closeModal');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * View user details
     */
    public function view($id)
    {
        $this->viewUser = User::with(['role', 'departments', 'creator', 'updater'])->findOrFail($id);
        $this->dispatch('openOffcanvas');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            // Cannot delete yourself
            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot delete your own account.');
                return;
            }

            // Cannot delete super admin (unless you are super admin)
            if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
                session()->flash('error', 'You cannot delete super admin users.');
                return;
            }

            // Check if user has tickets
            if ($user->tickets()->count() > 0) {
                session()->flash('error', 'Cannot delete user with existing tickets.');
                return;
            }

            $user->delete();
            session()->flash('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // Cannot deactivate yourself
            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot deactivate your own account.');
                return;
            }

            // Cannot deactivate super admin (unless you are super admin)
            if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
                session()->flash('error', 'You cannot modify super admin users.');
                return;
            }

            $user->update([
                'is_active' => !$user->is_active,
                'updated_by' => Auth::id(),
            ]);

            $status = $user->is_active ? 'activated' : 'deactivated';
            session()->flash('success', "User {$status} successfully.");

            if ($this->viewUser && $this->viewUser->id === $id) {
                $this->viewUser = User::with(['role', 'departments', 'creator', 'updater'])->findOrFail($id);
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
        $this->userId = null;
        $this->name = '';
        $this->full_name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_id = '';
        $this->phone = '';
        $this->address = '';
        $this->country = 'Iraq';
        $this->profile_photo = null;
        $this->existing_profile_photo = '';
        $this->is_active = true;
        $this->selectedDepartments = [];
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.masters.users.user-list', [
            'users' => $this->getData(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Users',
        ]);
    }
}
