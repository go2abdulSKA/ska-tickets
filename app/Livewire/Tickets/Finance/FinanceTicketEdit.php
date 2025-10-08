<?php
// app/Livewire/Tickets/Finance/FinanceTicketEdit.php

namespace App\Livewire\Tickets\Finance;

use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\PaymentType;
use App\Enums\TicketStatus;
use App\Models\Client;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\ServiceType;
use App\Models\TicketMaster;
use App\Models\UOM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Finance Ticket Edit Component
 *
 * Allows editing of DRAFT tickets only
 * Admins can edit any draft ticket, users can only edit their own
 */
class FinanceTicketEdit extends Component
{
    use WithFileUploads;

    public $ticketId;
    public $ticket;

    // Ticket Header Fields
    public $department_id = '';
    public $ticket_date;
    public $client_type = 'client';
    public $client_id = '';
    public $cost_center_id = '';
    public $project_code = '';
    public $contract_no = '';
    public $service_location = '';
    public $service_type_id = '';
    public $ref_no = '';
    public $payment_terms = '';
    public $payment_type = 'na';
    public $currency = 'usd';
    public $vat_percentage = 5.00;
    public $remarks = '';

    // Line Items (Transactions)
    public $items = [];
    public $deletedItemIds = []; // Track deleted items for database cleanup

    // Calculated Totals
    public $subtotal = 0;
    public $vat_amount = 0;
    public $total_amount = 0;

    // File Attachments
    public $attachments = [];
    public $existingAttachments = [];
    public $deletedAttachmentIds = [];

    // Available Options
    public $departments = [];
    public $clients = [];
    public $costCenters = [];
    public $serviceTypes = [];
    public $uoms = [];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'ticket_date' => 'required|date',
            'client_type' => 'required|in:client,cost_center',
            'client_id' => 'required_if:client_type,client|exists:clients,id',
            'cost_center_id' => 'required_if:client_type,cost_center|exists:cost_centers,id',
            'project_code' => 'nullable|string|max:100',
            'contract_no' => 'nullable|string|max:100',
            'service_location' => 'nullable|string|max:255',
            'service_type_id' => 'nullable|exists:service_types,id',
            'ref_no' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'payment_type' => 'required|in:po,cash,credit_card,na',
            'currency' => 'required|in:usd,aed,euro,others',
            'vat_percentage' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.uom_id' => 'required|exists:uom,id',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    /**
     * Custom validation messages
     */
    protected $messages = [
        'department_id.required' => 'Please select a department.',
        'ticket_date.required' => 'Ticket date is required.',
        'client_id.required_if' => 'Please select a client.',
        'cost_center_id.required_if' => 'Please select a cost center.',
        'items.required' => 'Please add at least one item.',
        'items.min' => 'Please add at least one item.',
        'items.*.description.required' => 'Item description is required.',
        'items.*.qty.required' => 'Quantity is required.',
        'items.*.uom_id.required' => 'Unit of measurement is required.',
        'items.*.unit_cost.required' => 'Unit cost is required.',
    ];

    /**
     * Mount the component
     */
    public function mount($id)
    {
        $this->ticketId = $id;
        $this->loadTicket();
        $this->loadMasterData();
        $this->loadTicketData();
    }

    /**
     * Load ticket and check permissions
     */
    private function loadTicket()
    {
        $this->ticket = TicketMaster::with(['transactions', 'attachments'])->findOrFail($this->ticketId);

        // Check if ticket can be edited
        if (!$this->ticket->canEdit()) {
            abort(403, 'This ticket cannot be edited. Only draft tickets can be modified.');
        }

        // Check user permissions
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                // Admin can edit tickets from their departments
                if (!in_array($this->ticket->department_id, $user->getDepartmentIds())) {
                    abort(403, 'You do not have permission to edit this ticket.');
                }
            } else {
                // Regular user can only edit their own tickets
                if ($this->ticket->user_id !== $user->id) {
                    abort(403, 'You can only edit your own tickets.');
                }
            }
        }
    }

    /**
     * Load master data (departments, clients, etc.)
     */
    private function loadMasterData()
    {
        // Load user's departments
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $this->departments = Department::active()->get();
        } else {
            $this->departments = $user->departments()->where('is_active', true)->get();
        }

        // Load cost centers (company-wide)
        $this->costCenters = CostCenter::active()->orderBy('name')->get();

        // Load UOMs
        $this->uoms = UOM::active()->orderBy('name')->get();
    }

    /**
     * Load ticket data into form fields
     */
    private function loadTicketData()
    {
        // Load header fields
        $this->department_id = $this->ticket->department_id;
        $this->ticket_date = $this->ticket->ticket_date->format('Y-m-d');
        $this->client_type = $this->ticket->client_type->value;
        $this->client_id = $this->ticket->client_id ?? '';
        $this->cost_center_id = $this->ticket->cost_center_id ?? '';
        $this->project_code = $this->ticket->project_code ?? '';
        $this->contract_no = $this->ticket->contract_no ?? '';
        $this->service_location = $this->ticket->service_location ?? '';
        $this->service_type_id = $this->ticket->service_type_id ?? '';
        $this->ref_no = $this->ticket->ref_no ?? '';
        $this->payment_terms = $this->ticket->payment_terms ?? '';
        $this->payment_type = $this->ticket->payment_type->value;
        $this->currency = $this->ticket->currency->value;
        $this->vat_percentage = $this->ticket->vat_percentage;
        $this->remarks = $this->ticket->remarks ?? '';

        // Load department-specific data
        $this->loadDepartmentData();

        // Load line items
        $this->items = $this->ticket->transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'description' => $transaction->description,
                'qty' => $transaction->qty,
                'uom_id' => $transaction->uom_id,
                'unit_cost' => $transaction->unit_cost,
                'total_cost' => $transaction->total_cost,
            ];
        })->toArray();

        // Load existing attachments
        $this->existingAttachments = $this->ticket->attachments->toArray();

        // Calculate totals
        $this->calculateTotals();
    }

    /**
     * When department changes, load related data
     */
    public function updatedDepartmentId($value)
    {
        if ($value) {
            $this->loadDepartmentData();
        }
    }

    /**
     * Load department-specific data
     */
    private function loadDepartmentData()
    {
        // Load clients for this department
        $this->clients = Client::where('department_id', $this->department_id)
            ->active()
            ->orderBy('client_name')
            ->get();

        // Load service types for this department
        $this->serviceTypes = ServiceType::where('department_id', $this->department_id)
            ->active()
            ->orderBy('service_type')
            ->get();
    }

    /**
     * When client type changes
     */
    public function updatedClientType($value)
    {
        // Reset both client and cost center when type changes
        if ($value === 'client') {
            $this->cost_center_id = '';
        } else {
            $this->client_id = '';
        }
    }

    /**
     * Add a new item row
     */
    public function addItem()
    {
        $this->items[] = [
            'id' => null, // New item, no ID yet
            'description' => '',
            'qty' => 1,
            'uom_id' => '',
            'unit_cost' => 0,
            'total_cost' => 0,
        ];
    }

    /**
     * Remove an item row
     */
    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            // If item has an ID, track it for deletion
            if (isset($this->items[$index]['id']) && $this->items[$index]['id']) {
                $this->deletedItemIds[] = $this->items[$index]['id'];
            }

            unset($this->items[$index]);
            $this->items = array_values($this->items); // Re-index array
            $this->calculateTotals();
        }
    }

    /**
     * Remove existing attachment
     */
    public function removeExistingAttachment($index)
    {
        if (isset($this->existingAttachments[$index])) {
            $this->deletedAttachmentIds[] = $this->existingAttachments[$index]['id'];
            unset($this->existingAttachments[$index]);
            $this->existingAttachments = array_values($this->existingAttachments);
        }
    }

    /**
     * Calculate item total when qty or unit_cost changes
     */
    public function updatedItems($value, $key)
    {
        // Extract index and field name from key (e.g., "0.qty" or "1.unit_cost")
        $parts = explode('.', $key);
        $index = $parts[0];

        if (isset($this->items[$index])) {
            $item = &$this->items[$index];
            $item['total_cost'] = $item['qty'] * $item['unit_cost'];
        }

        $this->calculateTotals();
    }

    /**
     * When VAT percentage changes
     */
    public function updatedVatPercentage()
    {
        $this->calculateTotals();
    }

    /**
     * Calculate totals
     */
    public function calculateTotals()
    {
        // Calculate subtotal (sum of all items)
        $this->subtotal = collect($this->items)->sum('total_cost');

        // Calculate VAT
        $this->vat_amount = ($this->subtotal * $this->vat_percentage) / 100;

        // Calculate total
        $this->total_amount = $this->subtotal + $this->vat_amount;
    }

    /**
     * Update ticket
     */
    public function update()
    {
        // Validate
        $this->validate();

        try {
            DB::beginTransaction();

            // Update ticket master
            $this->ticket->update([
                'ticket_date' => $this->ticket_date,
                'department_id' => $this->department_id,
                'client_type' => $this->client_type,
                'client_id' => $this->client_type === 'client' ? $this->client_id : null,
                'cost_center_id' => $this->client_type === 'cost_center' ? $this->cost_center_id : null,
                'project_code' => $this->project_code,
                'contract_no' => $this->contract_no,
                'service_location' => $this->service_location,
                'service_type_id' => $this->service_type_id,
                'ref_no' => $this->ref_no,
                'payment_terms' => $this->payment_terms,
                'payment_type' => $this->payment_type,
                'currency' => $this->currency,
                'subtotal' => $this->subtotal,
                'vat_percentage' => $this->vat_percentage,
                'vat_amount' => $this->vat_amount,
                'total_amount' => $this->total_amount,
                'remarks' => $this->remarks,
                'updated_by' => Auth::id(),
            ]);

            // Delete removed items
            if (!empty($this->deletedItemIds)) {
                $this->ticket->transactions()->whereIn('id', $this->deletedItemIds)->delete();
            }

            // Update or create transactions
            foreach ($this->items as $index => $item) {
                if (isset($item['id']) && $item['id']) {
                    // Update existing transaction
                    $this->ticket->transactions()->where('id', $item['id'])->update([
                        'sr_no' => $index + 1,
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'uom_id' => $item['uom_id'],
                        'unit_cost' => $item['unit_cost'],
                        'total_cost' => $item['total_cost'],
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    // Create new transaction
                    $this->ticket->transactions()->create([
                        'sr_no' => $index + 1,
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'uom_id' => $item['uom_id'],
                        'unit_cost' => $item['unit_cost'],
                        'total_cost' => $item['total_cost'],
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            // Delete removed attachments
            if (!empty($this->deletedAttachmentIds)) {
                $this->ticket->attachments()->whereIn('id', $this->deletedAttachmentIds)->delete();
            }

            // Handle new file attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $this->saveAttachment($this->ticket, $file);
                }
            }

            DB::commit();

            $this->dispatch('success', message: "Ticket {$this->ticket->ticket_no} updated successfully!");

            // Redirect to view page
            return redirect()->route('tickets.finance.view', $this->ticketId);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
            logger()->error('Failed to update ticket', [
                'ticket_id' => $this->ticketId,
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
        }
    }

    /**
     * Save attachment to file system
     */
    private function saveAttachment($ticket, $file)
    {
        // Create directory structure: storage/app/attachments/YYYY/MM/
        $year = now()->format('Y');
        $month = now()->format('m');
        $directory = "attachments/{$year}/{$month}";

        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $storedName = $ticket->ticket_no . '_' . now()->format('YmdHis') . '_' . uniqid() . '.' . $extension;

        // Store file
        $path = $file->storeAs($directory, $storedName);

        // Save attachment record
        $ticket->attachments()->create([
            'original_name' => $originalName,
            'stored_name' => $storedName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_type' => $this->determineFileType($extension),
            'uploaded_by' => Auth::id(),
        ]);
    }

    /**
     * Determine file type category
     */
    private function determineFileType($extension)
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $documentTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];

        if (in_array(strtolower($extension), $imageTypes)) {
            return 'image';
        } elseif (in_array(strtolower($extension), $documentTypes)) {
            return 'document';
        }

        return 'other';
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.tickets.finance.finance-ticket-edit', [
            'clientTypes' => ClientType::options(),
            'currencies' => Currency::options(),
            'paymentTypes' => PaymentType::options(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Edit Finance Ticket - ' . $this->ticket->ticket_no,
        ]);
    }
}
