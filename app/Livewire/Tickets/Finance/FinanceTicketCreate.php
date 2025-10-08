<?php
// app/Livewire/Tickets/Finance/FinanceTicketCreate.php

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
use App\Services\TicketNumberService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Finance Ticket Create Component - Enhanced
 *
 * Features:
 * - Auto-generated ticket numbers
 * - Multiple line items
 * - VAT calculations
 * - File attachments with preview
 * - Client modal for adding new clients
 * - Client info offcanvas for viewing details
 * - Role-based permissions
 */
class FinanceTicketCreate extends Component
{
    use WithFileUploads;

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

    // Calculated Totals
    public $subtotal = 0;
    public $vat_amount = 0;
    public $total_amount = 0;

    // File Attachments
    public $attachments = [];
    public $uploadedFiles = [];

    // Available Options
    public $departments = [];
    public $clients = [];
    public $costCenters = [];
    public $serviceTypes = [];
    public $uoms = [];

    // Preview ticket number
    public $preview_ticket_no = '';

    // Selected client/cost center info
    public $selectedClient = null;
    public $selectedCostCenter = null;

    // Client Modal Fields
    public $modalClientName = '';
    public $modalCompanyName = '';
    public $modalPhone = '';
    public $modalEmail = '';
    public $modalAddress = '';
    public $editingClientId = null; // Track if editing

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'ticket_date' => 'required|date',
            'client_type' => 'required|in:client,cost_center',
            'client_id' => 'required_if:client_type,client|nullable|exists:clients,id',
            'cost_center_id' => 'required_if:client_type,cost_center|nullable|exists:cost_centers,id',
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
    public function mount()
    {
        // Set default values
        $this->ticket_date = now()->format('Y-m-d');

        // Load user's departments
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            $this->departments = Department::active()->get();
        } else {
            $this->departments = $user->departments()->where('is_active', true)->get();
        }

        // Auto-select department if user has only one
        if ($this->departments->count() === 1) {
            $this->department_id = $this->departments->first()->id;
            $this->loadDepartmentData();
        }

        // Load cost centers (company-wide)
        $this->costCenters = CostCenter::active()->orderBy('name')->get();

        // Load UOMs
        $this->uoms = UOM::active()->orderBy('name')->get();

        // Add one empty item row
        $this->addItem();
    }

    /**
     * When department changes, load related data
     */
    public function updatedDepartmentId($value)
    {
        if ($value) {
            $this->loadDepartmentData();
            $this->previewTicketNumber();
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

        // Reset dependent fields
        $this->client_id = '';
        $this->service_type_id = '';
        $this->selectedClient = null;
    }

    /**
     * Preview the next ticket number
     */
    public function previewTicketNumber()
    {
        if ($this->department_id) {
            $service = new TicketNumberService();
            $this->preview_ticket_no = $service->previewNextTicketNumber($this->department_id);
        }
    }

    /**
     * When client type changes
     */
    public function updatedClientType($value)
    {
        // Reset both client and cost center
        $this->client_id = '';
        $this->cost_center_id = '';
        $this->selectedClient = null;
        $this->selectedCostCenter = null;
    }

    /**
     * When client changes, load client info
     */
    public function updatedClientId($value)
    {
        if ($value) {
            $this->selectedClient = Client::with('department')->find($value);
        } else {
            $this->selectedClient = null;
        }
    }

    /**
     * When cost center changes, load cost center info
     */
    public function updatedCostCenterId($value)
    {
        if ($value) {
            $this->selectedCostCenter = CostCenter::find($value);
        } else {
            $this->selectedCostCenter = null;
        }
    }

    /**
     * Reset client modal
     */
    public function resetClientModal()
    {
        $this->modalClientName = '';
        $this->modalCompanyName = '';
        $this->modalPhone = '';
        $this->modalEmail = '';
        $this->modalAddress = '';
        $this->editingClientId = null;
        $this->resetValidation(['modalClientName', 'modalCompanyName', 'modalPhone', 'modalEmail', 'modalAddress']);
    }

    /**
     * Save new client from modal
     */
    public function saveClient()
    {
        // Check permission
        $permission = $this->editingClientId ? 'edit-client' : 'create-client';
        if (!Auth::user()->hasPermission($permission)) {
            $this->dispatch('error', message: 'You do not have permission to ' . ($this->editingClientId ? 'edit' : 'create') . ' clients.');
            return;
        }

        $this->validate([
            'modalClientName' => 'required|string|max:255',
            'modalCompanyName' => 'nullable|string|max:255',
            'modalPhone' => 'nullable|string|max:20',
            'modalEmail' => 'nullable|email|max:255',
            'modalAddress' => 'nullable|string',
        ]);

        try {
            if ($this->editingClientId) {
                // Update existing client
                $client = Client::findOrFail($this->editingClientId);
                $client->update([
                    'client_name' => $this->modalClientName,
                    'company_name' => $this->modalCompanyName,
                    'phone' => $this->modalPhone,
                    'email' => $this->modalEmail,
                    'address' => $this->modalAddress,
                    'updated_by' => Auth::id(),
                ]);

                $message = 'Client updated successfully!';
            } else {
                // Create new client
                $client = Client::create([
                    'department_id' => $this->department_id,
                    'client_name' => $this->modalClientName,
                    'company_name' => $this->modalCompanyName,
                    'phone' => $this->modalPhone,
                    'email' => $this->modalEmail,
                    'address' => $this->modalAddress,
                    'is_active' => true,
                    'created_by' => Auth::id(),
                ]);

                $message = 'Client created successfully!';
            }

            // Reload clients
            $this->loadDepartmentData();

            // Auto-select the client
            $this->client_id = $client->id;
            $this->selectedClient = $client->load('department');

            // Close modal
            $this->dispatch('closeClientModal');
            $this->dispatch('success', message: $message);

            // Reset modal fields
            $this->resetClientModal();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Add a new item row
     */
    public function addItem()
    {
        $this->items[] = [
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
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Re-index array
            $this->calculateTotals();
        }
    }

    /**
     * Calculate item total when qty or unit_cost changes
     */
    public function calculateItemTotal($index)
    {
        if (isset($this->items[$index])) {
            $item = &$this->items[$index];
            $item['total_cost'] = $item['qty'] * $item['unit_cost'];
            $this->calculateTotals();
        }
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
     * When attachments are uploaded
     */
    public function updatedAttachments()
    {
        $this->validate([
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        foreach ($this->attachments as $attachment) {
            $this->uploadedFiles[] = [
                'file' => $attachment,
                'name' => $attachment->getClientOriginalName(),
                'size' => $attachment->getSize(),
                'extension' => $attachment->getClientOriginalExtension(),
                'temp_id' => uniqid(),
            ];
        }
        
        // Clear attachments array to allow new uploads
        $this->reset('attachments');
    }

    /**
     * Edit client - redirect to client edit page or open edit modal
     */
    public function editClient($clientId)
    {
        // Load client data for editing
        $client = Client::find($clientId);
        
        if ($client) {
            $this->editingClientId = $client->id;
            $this->modalClientName = $client->client_name;
            $this->modalCompanyName = $client->company_name;
            $this->modalPhone = $client->phone;
            $this->modalEmail = $client->email;
            $this->modalAddress = $client->address;
            
            // Dispatch event to open modal - using Livewire 3 syntax
            $this->dispatch('openClientModal');
        }
    }

    /**
     * Remove uploaded file
     */
    public function removeFile($index)
    {
        unset($this->uploadedFiles[$index]);
        $this->uploadedFiles = array_values($this->uploadedFiles);
    }

    /**
     * Save as draft
     */
    public function saveDraft()
    {
        // Check permission
        if (!Auth::user()->hasPermission('create-finance-ticket')) {
            $this->dispatch('error', message: 'You do not have permission to create tickets.');
            return;
        }

        $this->save(TicketStatus::DRAFT);
    }

    /**
     * Save and post
     */
    public function saveAndPost()
    {
        // Check permission
        if (!Auth::user()->hasPermission('post-ticket')) {
            $this->dispatch('error', message: 'You do not have permission to post tickets.');
            return;
        }

        $this->save(TicketStatus::POSTED);
    }

    /**
     * Main save method
     */
    private function save($status)
    {
        // Validate
        $this->validate();

        try {
            DB::beginTransaction();

            // Generate ticket number
            $ticketNumberService = new TicketNumberService();
            $ticketNumber = $ticketNumberService->generateTicketNumber($this->department_id);

            // Get department prefix
            $department = Department::findOrFail($this->department_id);

            // Create ticket master
            $ticket = TicketMaster::create([
                'prefix' => $department->prefix,
                'ticket_no' => $ticketNumber,
                'ticket_date' => $this->ticket_date,
                'department_id' => $this->department_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'host_name' => request()->ip(),
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
                'status' => $status,
                'posted_date' => $status === TicketStatus::POSTED ? now() : null,
                'created_by' => Auth::id(),
            ]);

            // Create ticket transactions (line items)
            foreach ($this->items as $index => $item) {
                $ticket->transactions()->create([
                    'sr_no' => $index + 1,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'uom_id' => $item['uom_id'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['total_cost'],
                    'created_by' => Auth::id(),
                ]);
            }

            // Handle file attachments
            if (!empty($this->uploadedFiles)) {
                foreach ($this->uploadedFiles as $fileData) {
                    $this->saveAttachment($ticket, $fileData['file']);
                }
            }

            // Record status history
            $ticket->statusHistory()->create([
                'from_status' => null,
                'to_status' => $status,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket created',
            ]);

            DB::commit();

            $statusLabel = $status === TicketStatus::POSTED ? 'posted' : 'saved as draft';
            $this->dispatch('success', message: "Ticket {$ticketNumber} {$statusLabel} successfully!");

            // Redirect to ticket list
            return redirect()->route('tickets.finance.index');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
            logger()->error('Failed to create ticket', [
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
        return view('livewire.tickets.finance.finance-ticket-create', [
            'clientTypes' => ClientType::options(),
            'currencies' => Currency::options(),
            'paymentTypes' => PaymentType::options(),
        ])->extends('admin.layout', [
            'pageTitle' => 'Create Finance Ticket',
        ]);
    }
}
