<?php
// app/Livewire/Tickets/Finance/FinanceTicketView.php

namespace App\Livewire\Tickets\Finance;

use Livewire\Component;
use App\Enums\TicketStatus;
use App\Models\TicketMaster;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Finance Ticket View Component
 *
 * Displays complete ticket details including:
 * - Header information
 * - Line items with totals
 * - Attachments
 * - Status history
 * - Action buttons based on permissions
 */
class FinanceTicketView extends Component
{
    public $ticketId;
    public $ticket;

    /**
     * Mount the component
     */
    public function mount($id)
    {
        $this->ticketId = $id;
        $this->loadTicket();
    }

    /**
     * Load ticket with all relationships
     */
    public function loadTicket()
    {
        $this->ticket = TicketMaster::with(['department', 'user', 'client', 'costCenter', 'serviceType', 'transactions.uom', 'attachments.uploader', 'statusHistory.user', 'creator', 'updater'])->findOrFail($this->ticketId);

        // Check if user has access to view this ticket
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                // Admin can see tickets from their departments
                if (!in_array($this->ticket->department_id, $user->getDepartmentIds())) {
                    abort(403, 'Unauthorized access to this ticket.');
                }
            } else {
                // Regular user can only see their own tickets
                if ($this->ticket->user_id !== $user->id) {
                    abort(403, 'Unauthorized access to this ticket.');
                }
            }
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($attachmentId)
    {
        $attachment = $this->ticket->attachments()->findOrFail($attachmentId);

        if (!Storage::exists($attachment->file_path)) {
            $this->dispatch('error', message: 'File not found.');
            return;
        }

        return Storage::download($attachment->file_path, $attachment->original_name);
    }

    /**
     * Edit ticket
     */
    public function edit()
    {
        if (!$this->ticket->canEdit()) {
            $this->dispatch('error', message: 'This ticket cannot be edited.');
            return;
        }

        return redirect()->route('tickets.finance.edit', $this->ticketId);
    }

    /**
     * Delete ticket
     */
    public function delete()
    {
        if (!$this->ticket->canDelete()) {
            $this->dispatch('error', message: 'Only draft tickets can be deleted.');
            return;
        }

        try {
            // Delete related records
            $this->ticket->transactions()->delete();
            $this->ticket->attachments()->delete();
            $this->ticket->statusHistory()->delete();
            $this->ticket->delete();

            $this->dispatch('success', message: 'Ticket deleted successfully.');
            return redirect()->route('tickets.finance.index');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Post ticket (Admin only)
     */
    public function post()
    {
        if (!Auth::user()->hasPermission('post-ticket')) {
            $this->dispatch('error', message: 'You do not have permission to post tickets.');
            return;
        }

        if (!$this->ticket->canPost()) {
            $this->dispatch('error', message: 'This ticket cannot be posted.');
            return;
        }

        try {
            $this->ticket->update([
                'status' => TicketStatus::POSTED,
                'posted_date' => now(),
                'updated_by' => Auth::id(),
            ]);

            $this->ticket->statusHistory()->create([
                'from_status' => TicketStatus::DRAFT,
                'to_status' => TicketStatus::POSTED,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket posted',
            ]);

            $this->dispatch('success', message: 'Ticket posted successfully.');
            $this->loadTicket();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Unpost ticket (Admin only)
     */
    public function unpost()
    {
        if (!Auth::user()->hasPermission('unpost-ticket')) {
            $this->dispatch('error', message: 'You do not have permission to unpost tickets.');
            return;
        }

        if (!$this->ticket->status->canUnpost()) {
            $this->dispatch('error', message: 'Only posted tickets can be unposted.');
            return;
        }

        try {
            $this->ticket->update([
                'status' => TicketStatus::DRAFT,
                'posted_date' => null,
                'updated_by' => Auth::id(),
            ]);

            $this->ticket->statusHistory()->create([
                'from_status' => TicketStatus::POSTED,
                'to_status' => TicketStatus::DRAFT,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket unposted',
            ]);

            $this->dispatch('success', message: 'Ticket unposted successfully.');
            $this->loadTicket();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancel ticket (Admin only)
     */
    public function cancel()
    {
        if (!Auth::user()->hasPermission('cancel-ticket')) {
            $this->dispatch('error', message: 'You do not have permission to cancel tickets.');
            return;
        }

        if (!$this->ticket->status->canCancel()) {
            $this->dispatch('error', message: 'This ticket cannot be cancelled.');
            return;
        }

        try {
            $oldStatus = $this->ticket->status;

            $this->ticket->update([
                'status' => TicketStatus::CANCELLED,
                'updated_by' => Auth::id(),
            ]);

            $this->ticket->statusHistory()->create([
                'from_status' => $oldStatus,
                'to_status' => TicketStatus::CANCELLED,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket cancelled',
            ]);

            $this->dispatch('success', message: 'Ticket cancelled successfully.');
            $this->loadTicket();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Revert cancelled ticket (Admin only)
     * Reverts ticket to its previous status before cancellation
     */
    public function revertCancellation()
    {
        if (!Auth::user()->isAdmin()) {
            $this->dispatch('error', message: 'You do not have permission to revert cancelled tickets.');
            return;
        }

        if ($this->ticket->status !== TicketStatus::CANCELLED) {
            $this->dispatch('error', message: 'Only cancelled tickets can be reverted.');
            return;
        }

        try {
            // Get the last status history record to find what status it was before cancellation
            $lastHistory = $this->ticket->statusHistory()->where('to_status', TicketStatus::CANCELLED)->orderBy('changed_at', 'desc')->first();

            if (!$lastHistory || !$lastHistory->from_status) {
                $this->dispatch('error', message: 'Cannot determine previous status. Cannot revert.');
                return;
            }

            $previousStatus = $lastHistory->from_status;

            // Update ticket status back to previous status
            $this->ticket->update([
                'status' => $previousStatus,
                'updated_by' => Auth::id(),
            ]);

            // If reverting to POSTED, restore posted_date
            if ($previousStatus === TicketStatus::POSTED) {
                // Try to get the original posted_date from history
                $originalPostedHistory = $this->ticket->statusHistory()->where('to_status', TicketStatus::POSTED)->orderBy('changed_at', 'asc')->first();

                $this->ticket->update([
                    'posted_date' => $originalPostedHistory ? $originalPostedHistory->changed_at : now(),
                ]);
            }

            // Record the revert action in history
            $this->ticket->statusHistory()->create([
                'from_status' => TicketStatus::CANCELLED,
                'to_status' => $previousStatus,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket cancellation reverted',
            ]);

            $this->dispatch('success', message: "Ticket reverted to {$previousStatus->label()} status successfully.");
            $this->loadTicket();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Print ticket
     */
    // public function print()
    // {
    //     // This will be handled by JavaScript to open print dialog
    //     $this->dispatch('print-ticket');
    // }

    /**
     * Show the print view for the ticket
     */
    public function print($id)
    {
        $ticket = TicketMaster::with(['department', 'client', 'costCenter', 'serviceType', 'transactions'])->findOrFail($id);

        return view('tickets.finance.print', compact('ticket'));
    }

    /**
     * Generate PDF for the ticket
     */
    public function downloadPdf($id)
    {
        $ticket = TicketMaster::with(['department', 'client', 'costCenter', 'serviceType', 'transactions'])->findOrFail($id);

        $pdf = Pdf::loadView('tickets.finance.pdf', compact('ticket'));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Optional: Set additional options
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        // Generate filename
        $filename = $ticket->ticket_no . '_' . now()->format('Y-m-d') . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    /**
     * View PDF in browser (inline)
     */
    public function viewPdf($id)
    {
        $ticket = TicketMaster::with(['department', 'client', 'costCenter', 'serviceType', 'transactions'])->findOrFail($id);

        $pdf = Pdf::loadView('tickets.finance.pdf', compact('ticket'));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Stream the PDF (view in browser)
        $safeFileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $ticket->ticket_no);
        return $pdf->stream($safeFileName . '.pdf');
        // return $pdf->stream($ticket->ticket_no . '.pdf');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.tickets.finance.finance-ticket-view')
            ->extends('admin.layout', [
            'pageTitle' => 'View Finance Ticket - ' . $this->ticket->ticket_no,
        ]);
    }
}
