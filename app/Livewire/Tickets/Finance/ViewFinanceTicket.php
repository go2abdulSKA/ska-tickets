<?php
// app/Livewire/Tickets/Finance/ViewFinanceTicket.php

namespace App\Livewire\Tickets\Finance;

use Livewire\Component;
use App\Models\TicketMaster;
use Illuminate\Support\Facades\Auth;

class ViewFinanceTicket extends Component
{
    public $ticketId;
    public $ticket;

    public function mount($ticketId)
    {
        $this->ticketId = $ticketId;
        $this->loadTicket();
    }

    public function loadTicket()
    {
        $this->ticket = TicketMaster::with([
            'department',
            'user',
            'client',
            'costCenter',
            'serviceType',
            'transactions.uom',
            'attachments',
            'statusHistory.changedBy',
            'creator',
            'updater'
        ])->findOrFail($this->ticketId);

        // Check if user has access to this ticket
        if (!Auth::user()->isSuperAdmin()) {
            $userDeptIds = Auth::user()->getDepartmentIds();
            if (!in_array($this->ticket->department_id, $userDeptIds)) {
                abort(403, 'You do not have access to this ticket.');
            }
        }
    }

    public function downloadPDF()
    {
        try {
            $pdfService = app(\App\Services\TicketPDFService::class);
            return $pdfService->generateFinanceTicket($this->ticket);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    public function downloadAttachment($attachmentId)
    {
        $attachment = $this->ticket->attachments()->find($attachmentId);

        if (!$attachment) {
            $this->dispatch('toast', type: 'error', message: 'Attachment not found.');
            return;
        }

        return response()->download(
            storage_path('app/public/' . $attachment->file_path),
            $attachment->original_name
        );
    }

    public function edit()
    {
        if (!$this->ticket->canEdit()) {
            $this->dispatch('toast', type: 'error', message: 'This ticket cannot be edited.');
            return;
        }

        return redirect()->route('tickets.finance.edit', $this->ticket->id);
    }

    public function duplicate()
    {
        return redirect()->route('tickets.finance.duplicate', $this->ticket->id);
    }

    public function closeOffcanvas()
    {
        $this->dispatch('close-offcanvas');
    }

    public function render()
    {
        return view('livewire.tickets.finance.view');
    }
}
