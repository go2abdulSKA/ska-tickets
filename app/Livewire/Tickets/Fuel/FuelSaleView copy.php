<?php
// app/Livewire/Tickets/Fuel/FuelSaleView.php

namespace App\Livewire\Tickets\Fuel;

use App\Models\FuelSale;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class FuelSaleView extends Component
{
    public $fuelSaleId;
    public $fuelSale;

    /**
     * Mount component
     */
    public function mount($id)
    {
        $this->fuelSaleId = $id;
        $this->loadFuelSale();
    }

    /**
     * Load fuel sale with all relationships
     */
    public function loadFuelSale()
    {
        $this->fuelSale = FuelSale::with([
            'department',
            'client',
            'costCenter',
            'user',
            'creator',
            'updater',
            'attachments'
        ])->findOrFail($this->fuelSaleId);

        // Check if user has access to this fuel sale
        if (!Auth::user()->role->isSuperAdmin() && !Auth::user()->role->isAdmin()) {
            if ($this->fuelSale->department_id !== Auth::user()->department_id) {
                abort(403, 'You do not have access to this fuel sale ticket.');
            }
        }
    }

    /**
     * Print ticket
     */
    public function print()
    {
        // This will trigger browser print dialog via JavaScript
        $this->dispatch('print-ticket');
    }

    /**
     * Download PDF
     */
    public function downloadPdf()
    {
        try {
            $pdf = Pdf::loadView('pdf.fuel-sale-ticket', [
                'fuelSale' => $this->fuelSale
            ]);

            $filename = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT) . '.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);

        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            session()->flash('error', 'Failed to generate PDF.');
        }
    }

    /**
     * Delete fuel sale ticket
     */
    public function delete()
    {
        try {
            // Check if ticket can be deleted
            if ($this->fuelSale->status->value !== 'draft') {
                session()->flash('error', 'Only draft tickets can be deleted.');
                return;
            }

            // Check if user owns this ticket or is admin
            if (!Auth::user()->role->isAdmin() && $this->fuelSale->created_by !== Auth::id()) {
                session()->flash('error', 'You can only delete your own tickets.');
                return;
            }

            $ticketNo = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);
            
            $this->fuelSale->delete();

            session()->flash('success', "Fuel sale ticket {$ticketNo} deleted successfully.");
            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            Log::error('Error deleting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete fuel sale ticket.');
        }
    }

    /**
     * Post fuel sale ticket
     */
    public function post()
    {
        try {
            // Check permission
            if (!Auth::user()->hasPermission('post-fuel-ticket')) {
                session()->flash('error', 'You do not have permission to post fuel tickets.');
                return;
            }

            // Check if ticket is draft
            if ($this->fuelSale->status->value !== 'draft') {
                session()->flash('error', 'Only draft tickets can be posted.');
                return;
            }

            DB::beginTransaction();

            // Update status
            $this->fuelSale->status = \App\Enums\TicketStatus::POSTED;
            $this->fuelSale->posted_date = now();
            $this->fuelSale->save();

            DB::commit();

            $ticketNo = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);
            session()->flash('success', "Fuel sale ticket {$ticketNo} posted successfully.");
            
            // Reload fuel sale
            $this->loadFuelSale();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to post fuel sale ticket.');
        }
    }

    /**
     * Unpost fuel sale ticket
     */
    public function unpost()
    {
        try {
            // Check permission
            if (!Auth::user()->hasPermission('unpost-fuel-ticket')) {
                session()->flash('error', 'You do not have permission to unpost fuel tickets.');
                return;
            }

            // Check if ticket is posted
            if ($this->fuelSale->status->value !== 'posted') {
                session()->flash('error', 'Only posted tickets can be unposted.');
                return;
            }

            DB::beginTransaction();

            // Update status
            $this->fuelSale->status = \App\Enums\TicketStatus::DRAFT;
            $this->fuelSale->posted_date = null;
            $this->fuelSale->save();

            DB::commit();

            $ticketNo = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);
            session()->flash('success', "Fuel sale ticket {$ticketNo} unposted successfully.");
            
            // Reload fuel sale
            $this->loadFuelSale();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unposting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to unpost fuel sale ticket.');
        }
    }

    /**
     * Cancel fuel sale ticket
     */
    public function cancel()
    {
        try {
            // Check permission
            if (!Auth::user()->hasPermission('cancel-fuel-ticket')) {
                session()->flash('error', 'You do not have permission to cancel fuel tickets.');
                return;
            }

            // Check if ticket can be cancelled
            if ($this->fuelSale->status->value === 'cancelled') {
                session()->flash('error', 'This ticket is already cancelled.');
                return;
            }

            DB::beginTransaction();

            // Update status
            $this->fuelSale->status = \App\Enums\TicketStatus::CANCELLED;
            $this->fuelSale->save();

            DB::commit();

            $ticketNo = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);
            session()->flash('success', "Fuel sale ticket {$ticketNo} cancelled successfully.");
            
            // Reload fuel sale
            $this->loadFuelSale();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to cancel fuel sale ticket.');
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($attachmentId)
    {
        try {
            $attachment = $this->fuelSale->attachments()->findOrFail($attachmentId);
            
            if (\Storage::exists($attachment->file_path)) {
                return \Storage::download($attachment->file_path, $attachment->original_name);
            }

            session()->flash('error', 'File not found.');
        } catch (\Exception $e) {
            Log::error('Error downloading attachment: ' . $e->getMessage());
            session()->flash('error', 'Failed to download attachment.');
        }
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment($attachmentId)
    {
        try {
            // Check if ticket can be edited
            if ($this->fuelSale->status->value !== 'draft') {
                session()->flash('error', 'Cannot delete attachments from non-draft tickets.');
                return;
            }

            // Check if user owns this ticket or is admin
            if (!Auth::user()->role->isAdmin() && $this->fuelSale->created_by !== Auth::id()) {
                session()->flash('error', 'You can only delete attachments from your own tickets.');
                return;
            }

            $attachment = $this->fuelSale->attachments()->findOrFail($attachmentId);
            
            // Delete will be handled by the model's boot method
            $attachment->delete();

            session()->flash('success', 'Attachment deleted successfully.');
            
            // Reload fuel sale
            $this->loadFuelSale();

        } catch (\Exception $e) {
            Log::error('Error deleting attachment: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete attachment.');
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.tickets.fuel.fuel-sale-view')
            ->extends('admin.layout', [
                'pageTitle' => 'View Fuel Sale Ticket - ' . $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT)
            ]);
    }
}
