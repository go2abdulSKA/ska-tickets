<?php
// app/Livewire/Components/DataTable.php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;

/**
 * Generic DataTable Component
 *
 * This is a reusable component for displaying data in a table format
 * with server-side pagination, search, and actions.
 *
 * Usage in other components:
 * 1. Extend this component in your specific list components
 * 2. Override the query() method to return your data
 * 3. Define columns in the view
 */
class DataTable extends Component
{
    use WithPagination;

    // Pagination theme (Bootstrap for uBold)
    protected $paginationTheme = 'bootstrap';

    // Public properties for search and filters
    public $search = '';
    public $perPage = 5;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Selected items for bulk actions
    public $selectedItems = [];
    public $selectAll = false;

    /**
     * Reset pagination when search changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Update per page and reset pagination
     */
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Sort by field
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Toggle direction if same field
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // New field, default to ascending
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Select all items on current page
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Get IDs of items on current page
            $this->selectedItems = $this->getQuery()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    /**
     * Get query for data
     * Override this method in child components
     */
    protected function getQuery()
    {
        // This should be overridden in child components
        return collect([]);
    }

    /**
     * Get paginated data
     */
    protected function getData()
    {
        return $this->getQuery()->paginate($this->perPage);
    }

    /**
     * Export selected items
     */
    public function exportSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('error', message: 'No items selected for export.');
            return;
        }

        // Implement export logic
        $this->dispatch('success', message: count($this->selectedItems) . ' items exported successfully.');
    }

    /**
     * Delete selected items
     */
    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('error', message: 'No items selected for deletion.');
            return;
        }

        // Implement delete logic
        $this->dispatch('success', message: count($this->selectedItems) . ' items deleted successfully.');
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.components.data-table', [
            'data' => $this->getData(),
        ]);
    }
}
