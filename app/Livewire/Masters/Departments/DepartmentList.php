<?php
// app/Livewire/Masters/Departments/DepartmentList.php

namespace App\Livewire\Masters\Departments;

use App\Livewire\Components\DataTable;
use App\Models\Department;
use Livewire\WithFileUploads;

/**
 * Department List Component
 *
 * Displays all departments in a uBold-styled data table with:
 * - Search functionality
 * - Server-side pagination
 * - Inline add/edit via modal
 * - View details via offcanvas
 * - Logo upload
 */
class DepartmentList extends DataTable
{
    use WithFileUploads;

    // Entity name for generic components
    public $entityName = 'Department';

    // Form properties
    public $departmentId;
    public $department = '';
    public $short_name = '';
    public $prefix = '';
    public $form_name = '';
    public $notes = '';
    public $logo;
    public $existing_logo = '';

    // View detail properties
    public $viewDepartment;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'department' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'prefix' => 'required|string|max:10|unique:departments,prefix,' . $this->departmentId,
            'form_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    /**
     * Custom attribute names for validation errors
     */
    protected $validationAttributes = [
        'department' => 'department name',
        'short_name' => 'short name',
        'prefix' => 'department prefix',
        'form_name' => 'form name',
    ];

    /**
     * Get query for data table
     */
    protected function getQuery()
    {
        return Department::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('department', 'like', '%' . $this->search . '%')
                      ->orWhere('short_name', 'like', '%' . $this->search . '%')
                      ->orWhere('prefix', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    /**
     * Open modal for creating new department
     */
    public function create()
    {
        $this->resetForm();
        $this->dispatch('openModal');
    }

    /**
     * Open modal for editing department
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);

        $this->departmentId = $department->id;
        $this->department = $department->department;
        $this->short_name = $department->short_name;
        $this->prefix = $department->prefix;
        $this->form_name = $department->form_name;
        $this->notes = $department->notes;
        $this->existing_logo = $department->logo_path;

        $this->dispatch('openModal');
    }

    /**
     * Save department (create or update)
     */
    public function save()
    {
        $this->validate();

        try {
            $data = [
                'department' => $this->department,
                'short_name' => $this->short_name,
                'prefix' => strtoupper($this->prefix),
                'form_name' => $this->form_name,
                'notes' => $this->notes,
            ];

            // Handle logo upload
            if ($this->logo) {
                $logoPath = $this->logo->store('logos', 'public');
                $data['logo_path'] = $logoPath;
            }

            if ($this->departmentId) {
                // Update existing department
                $department = Department::findOrFail($this->departmentId);
                $department->update($data);
                $message = 'Department updated successfully.';
            } else {
                // Create new department
                Department::create($data);
                $message = 'Department created successfully.';
            }

            $this->dispatch('success', message: $message);
            $this->dispatch('closeModal');
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * View department details in offcanvas
     */
    public function view($id)
    {
        $this->viewDepartment = Department::findOrFail($id);
        $this->dispatch('openOffcanvas');
    }

    /**
     * Delete department
     */
    public function delete($id)
    {
        try {
            $department = Department::findOrFail($id);

            // Check if department has tickets
            if ($department->tickets()->count() > 0) {
                $this->dispatch('error', message: 'Cannot delete department with existing tickets.');
                return;
            }

            $department->delete();
            $this->dispatch('success', message: 'Department deleted successfully.');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Reset form
     */
    private function resetForm()
    {
        $this->departmentId = null;
        $this->department = '';
        $this->short_name = '';
        $this->prefix = '';
        $this->form_name = '';
        $this->notes = '';
        $this->logo = null;
        $this->existing_logo = '';
        $this->resetValidation();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.masters.departments.department-list', [
            'departments' => $this->getData(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Departments',
        ]);
    }
}
