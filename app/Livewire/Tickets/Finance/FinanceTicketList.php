<?php
// app/Livewire/Tickets/Finance/FinanceTicketList.php

namespace App\Livewire\Tickets\Finance;

use App\Livewire\Components\DataTable;
use App\Models\TicketMaster;
use App\Models\Department;
use App\Enums\TicketStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

/**
 * Finance Ticket List Component
 *
 * Displays all finance tickets with filtering and search capabilities
 * Implements role-based access control
 */
class FinanceTicketList extends DataTable
{
    use WithPagination;

    // Entity name for generic components
    public $entityName = 'Finance Ticket';

    // Filter properties
    public $filterStatus = '';
    public $filterDepartment = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    // Available filters
    public $departments = [];
    public $statuses = [];

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

        // Load available statuses
        $this->statuses = TicketStatus::options();

        // Set default filter from URL if exists
        $this->filterStatus = request('status', '');
    }

    /**
     * Get query for data table with role-based filtering
     */
    protected function getQuery()
    {
        $user = Auth::user();

        $query = TicketMaster::query()
            ->with(['department', 'user', 'client', 'costCenter', 'serviceType'])
            ->when(!$user->isSuperAdmin(), function ($q) use ($user) {
                if ($user->isAdmin()) {
                    // Admin sees tickets from their departments
                    $q->whereIn('department_id', $user->getDepartmentIds());
                } else {
                    // Regular user sees only their own tickets
                    $q->where('user_id', $user->id);
                }
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query
                        ->where('ticket_no', 'like', '%' . $this->search . '%')
                        ->orWhere('ref_no', 'like', '%' . $this->search . '%')
                        ->orWhere('project_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('client', function ($q) {
                            $q->where('client_name', 'like', '%' . $this->search . '%')->orWhere('company_name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('costCenter', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->when($this->filterDepartment, function ($q) {
                $q->where('department_id', $this->filterDepartment);
            })
            ->when($this->filterDateFrom, function ($q) {
                $q->whereDate('ticket_date', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($q) {
                $q->whereDate('ticket_date', '<=', $this->filterDateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterDepartment = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->search = '';
        $this->resetPage();
    }

    /**
     * View ticket details
     */
    public function view($id)
    {
        return redirect()->route('tickets.finance.view', $id);
    }

    /**
     * Edit ticket
     */
    public function edit($id)
    {
        $ticket = TicketMaster::findOrFail($id);

        // Check if user can edit this ticket
        if (!$ticket->canEdit()) {
            $this->dispatch('error', message: 'This ticket cannot be edited.');
            return;
        }

        return redirect()->route('tickets.finance.edit', $id);
    }

    /**
     * Delete ticket
     */
    public function delete($id)
    {
        try {
            $ticket = TicketMaster::findOrFail($id);

            // Check if user can delete this ticket
            if (!$ticket->canDelete()) {
                $this->dispatch('error', message: 'Only draft tickets can be deleted.');
                return;
            }

            // Delete related transactions
            $ticket->transactions()->delete();

            // Delete attachments (files will be deleted by model event)
            $ticket->attachments()->delete();

            // Delete the ticket
            $ticket->delete();

            $this->dispatch('success', message: 'Ticket deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Post ticket (Admin only)
     */
    public function post($id)
    {
        try {
            $ticket = TicketMaster::findOrFail($id);

            // Check permission
            if (!Auth::user()->hasPermission('post-ticket')) {
                $this->dispatch('error', message: 'You do not have permission to post tickets.');
                return;
            }

            if (!$ticket->canPost()) {
                $this->dispatch('error', message: 'This ticket cannot be posted.');
                return;
            }

            // Update status to posted
            $ticket->update([
                'status' => TicketStatus::POSTED,
                'posted_date' => now(),
                'updated_by' => Auth::id(),
            ]);

            // Record status change in history
            $ticket->statusHistory()->create([
                'from_status' => TicketStatus::DRAFT,
                'to_status' => TicketStatus::POSTED,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket posted',
            ]);

            $this->dispatch('success', message: 'Ticket posted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Unpost ticket (Admin only)
     */
    public function unpost($id)
    {
        try {
            $ticket = TicketMaster::findOrFail($id);

            // Check permission
            if (!Auth::user()->hasPermission('unpost-ticket')) {
                $this->dispatch('error', message: 'You do not have permission to unpost tickets.');
                return;
            }

            if ($ticket->status !== TicketStatus::POSTED) {
                $this->dispatch('error', message: 'Only posted tickets can be unposted.');
                return;
            }

            // Update status to draft
            $ticket->update([
                'status' => TicketStatus::DRAFT,
                'posted_date' => null,
                'updated_by' => Auth::id(),
            ]);

            // Record status change in history
            $ticket->statusHistory()->create([
                'from_status' => TicketStatus::POSTED,
                'to_status' => TicketStatus::DRAFT,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket unposted',
            ]);

            $this->dispatch('success', message: 'Ticket unposted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Revert cancelled ticket (Admin only)
     */
    public function revertCancellation($id)
    {
        try {
            $ticket = TicketMaster::findOrFail($id);

            // Check permission
            if (!Auth::user()->isAdmin()) {
                $this->dispatch('error', message: 'You do not have permission to revert cancelled tickets.');
                return;
            }

            if ($ticket->status !== TicketStatus::CANCELLED) {
                $this->dispatch('error', message: 'Only cancelled tickets can be reverted.');
                return;
            }

            // Get the last status history record to find what status it was before cancellation
            $lastHistory = $ticket->statusHistory()->where('to_status', TicketStatus::CANCELLED)->orderBy('changed_at', 'desc')->first();

            if (!$lastHistory || !$lastHistory->from_status) {
                $this->dispatch('error', message: 'Cannot determine previous status. Cannot revert.');
                return;
            }

            $previousStatus = $lastHistory->from_status;

            // Update ticket status back to previous status
            $ticket->update([
                'status' => $previousStatus,
                'updated_by' => Auth::id(),
            ]);

            // If reverting to POSTED, restore posted_date
            if ($previousStatus === TicketStatus::POSTED) {
                // Try to get the original posted_date from history
                $originalPostedHistory = $ticket->statusHistory()->where('to_status', TicketStatus::POSTED)->orderBy('changed_at', 'asc')->first();

                $ticket->update([
                    'posted_date' => $originalPostedHistory ? $originalPostedHistory->changed_at : now(),
                ]);
            }

            // Record the revert action in history
            $ticket->statusHistory()->create([
                'from_status' => TicketStatus::CANCELLED,
                'to_status' => $previousStatus,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket cancellation reverted',
            ]);

            $this->dispatch('success', message: "Ticket reverted to {$previousStatus->label()} status successfully.");
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.tickets.finance.finance-ticket-list', [
            'tickets' => $this->getData(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Finance Tickets',
        ]);
    }
}
