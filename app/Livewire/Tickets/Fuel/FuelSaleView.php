<?php
// app/Livewire/Tickets/Fuel/FuelSaleView.php

namespace App\Livewire\Tickets\Fuel;

use Storage;
use Livewire\Component;
use App\Models\FuelSale;
use Illuminate\Support\Facades\Auth;

class FuelSaleView extends Component
{
    public $fuelSaleId;
    public $fuelSale;
    public $ticket_no_display;
    public $canEdit = false;
    public $canDelete = false;

    /**
     * Mount component
     */
    public function mount($id)
    {
        $this->fuelSaleId = $id;
        $this->fuelSale = FuelSale::with([
            'department',
            'client',
            'costCenter',
            'attachments',
            'creator',
            'updater',
            'poster'
        ])->findOrFail($id);

        // Generate ticket number display
        $this->ticket_no_display = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);

        // Check permissions
        $this->checkPermissions();
    }

    /**
     * Check user permissions
     */
    public function checkPermissions()
    {
        $user = Auth::user();

        // Super admin can edit and delete
        if ($user->role->isSuperAdmin()) {
            $this->canEdit = $this->fuelSale->status->value === 'draft';
            $this->canDelete = true;
            return;
        }

        // Regular users in Fuel Services can edit their own draft tickets
        if ($user->department && $user->department->department === 'Fuel Services') {
            $this->canEdit = $this->fuelSale->status->value === 'draft' 
                && $this->fuelSale->created_by === $user->id;
            $this->canDelete = $this->fuelSale->created_by === $user->id;
        }
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($attachmentId)
    {
        $attachment = $this->fuelSale->attachments()->findOrFail($attachmentId);
        return response()->download(storage_path('app/' . $attachment->file_path), $attachment->original_name);
    }

    /**
     * Go back to list
     */
    public function backToList()
    {
        return redirect()->route('fuel-sales.index');
    }

    /**
     * Edit ticket
     */
    public function editTicket()
    {
        if (!$this->canEdit) {
            session()->flash('error', 'You do not have permission to edit this ticket.');
            return;
        }

        return redirect()->route('fuel-sales.edit', $this->fuelSaleId);
    }

    /**
     * Delete ticket
     */
    public function deleteTicket()
    {
        if (!$this->canDelete) {
            session()->flash('error', 'You do not have permission to delete this ticket.');
            return;
        }

        try {
            // Delete attachments from storage
            foreach ($this->fuelSale->attachments as $attachment) {
                if (Storage::exists($attachment->file_path)) {
                    \Storage::delete($attachment->file_path);
                }
                $attachment->delete();
            }

            // Delete ticket
            $this->fuelSale->delete();

            session()->flash('success', 'Fuel sale ticket deleted successfully.');
            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            \Log::error('Error deleting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete fuel sale ticket.');
        }
    }

    /**
     * Print ticket
     */
    public function printTicket()
    {
        // This would typically generate a PDF or open print dialog
        // For now, just redirect to a print view
        return redirect()->route('fuel-sales.print', $this->fuelSaleId);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.tickets.fuel.fuel-sale-view')
            ->extends('admin.layout', ['pageTitle' => 'View Fuel Sale Ticket']);
    }
}
