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
 * Finance Ticket Create Component
 *
 * Handles creation of finance tickets with:
 * - Auto-generated ticket numbers
 * - Multiple line items
 * - VAT calculations
 * - File attachments
 * - Client or Cost Center selection
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

    // Available Options
    public $departments = [];
    public $clients = [];
    public $costCenters = [];
    public $serviceTypes = [];
    public $uoms = [];

    // Preview ticket number
    public $preview_ticket_no = '';

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
     * Save as draft
     */
    public function saveDraft()
    {
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
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $this->saveAttachment($ticket, $file);
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
