<?php
// app/Livewire/Tickets/Finance/CreateFinanceTicket.php

namespace App\Livewire\Tickets\Finance;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\TicketMaster;
use App\Models\TicketTransaction;
use App\Models\TicketAttachment;
use App\Models\Department;
use App\Models\Client;
use App\Models\CostCenter;
use App\Models\ServiceType;
use App\Models\UOM;
use App\Enums\TicketType;
use App\Enums\TicketStatus;
use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\PaymentType;
use App\Services\TicketNumberService;
use App\Services\TicketAutoSaveService;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketPostedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateFinanceTicket extends Component
{
    use WithFileUploads;

    // ==========================================
    // Properties
    // ==========================================

    // Mode
    public $editMode = false;
    public $ticketId = null;
    public $isDuplicate = false;

    // Step Management
    public $currentStep = 1;
    public $totalSteps = 4;

    // Header Information (Step 1)
    public $ticket_date;
    public $department_id;
    public $client_type = 'client';
    public $client_id = null;
    public $cost_center_id = null;
    public $project_code = '';
    public $contract_no = '';
    public $service_location = '';
    public $service_type_id = null;
    public $ref_no = '';
    public $payment_terms = '';
    public $payment_type = 'na';
    public $currency = 'usd';

    // Line Items (Step 2) - Using Alpine.js
    public $transactions = [];
    public $transactionCounter = 0;

    // Totals & Remarks (Step 3)
    public $subtotal = 0;
    public $vat_percentage = 5.0;
    public $vat_amount = 0;
    public $total_amount = 0;
    public $remarks = '';

    // Attachments (Step 3)
    public $attachments = [];
    public $existingAttachments = [];

    // Auto-save
    public $autoSaveEnabled = true;
    public $lastSaved = null;

    // Quick Add Modals
    public $showQuickAddClient = false;
    public $showQuickAddUOM = false;
    public $showQuickAddServiceType = false;

    // Preview Ticket Number
    public $previewTicketNumber = '';

    // Validation tracking
    public $validatedSteps = [];

    
    // ==========================================
    // Validation Rules
    // ==========================================

    protected function rules()
    {
        $rules = [
            // Step 1: Header
            'ticket_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'client_type' => 'required|in:client,cost_center',
            'service_type_id' => 'nullable|exists:service_types,id',
            'project_code' => 'nullable|string|max:100',
            'contract_no' => 'nullable|string|max:100',
            'service_location' => 'nullable|string|max:100',
            'ref_no' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'payment_type' => 'required|in:po,cash,credit_card,na',
            'currency' => 'required|in:usd,aed,euro,others',

            // Step 2: Line Items
            'transactions' => 'required|array|min:1',
            'transactions.*.description' => 'required|string|max:500',
            'transactions.*.qty' => 'required|numeric|min:0.001',
            'transactions.*.uom_id' => 'required|exists:uom,id',
            'transactions.*.unit_cost' => 'required|numeric|min:0',

            // Step 3: Totals
            'vat_percentage' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:1000',

            // Attachments
            'attachments.*' => 'nullable|file|max:5120', // 5MB max
        ];

        // Conditional validation for client/cost center
        if ($this->client_type === ClientType::CLIENT->value) {
            $rules['client_id'] = 'required|exists:clients,id';
        } else {
            $rules['cost_center_id'] = 'required|exists:cost_centers,id';
        }

        return $rules;
    }

    protected $messages = [
        'ticket_date.required' => 'Ticket date is required.',
        'department_id.required' => 'Please select a department.',
        'client_id.required' => 'Please select a client.',
        'cost_center_id.required' => 'Please select a cost center.',
        'transactions.required' => 'At least one line item is required.',
        'transactions.min' => 'At least one line item is required.',
        'transactions.*.description.required' => 'Description is required for all line items.',
        'transactions.*.qty.required' => 'Quantity is required.',
        'transactions.*.qty.min' => 'Quantity must be at least 0.001.',
        'transactions.*.uom_id.required' => 'Unit of measurement is required.',
        'transactions.*.unit_cost.required' => 'Unit cost is required.',
    ];

    // ==========================================
    // Lifecycle Hooks
    // ==========================================

    public function mount($ticketId = null, $duplicate = false)
    {
        $user = Auth::user();

        // Set defaults
        $this->ticket_date = now()->format('Y-m-d');
        $this->department_id = $user->departments->first()?->id;

        // Preview ticket number
        if ($this->department_id) {
            $this->updatePreviewTicketNumber();
        }

        // Edit mode
        if ($ticketId) {
            $this->loadTicket($ticketId, $duplicate);
        }

        // Check for auto-saved draft
        if (!$ticketId && !$duplicate) {
            $this->checkAutoSavedDraft();
        }

        // Initialize with one empty line item
        if (empty($this->transactions)) {
            $this->addLineItem();
        }
    }

    public function dehydrate()
    {
        // Auto-save draft every update (debounced on frontend)
        if ($this->autoSaveEnabled && !$this->editMode) {
            $this->autoSaveDraft();
        }
    }

    // ==========================================
    // Computed Properties
    // ==========================================

    public function getClientsProperty()
    {
        $query = Client::active()->with('department:id,department');

        if ($this->department_id) {
            $query->where('department_id', $this->department_id);
        }

        return $query->orderBy('client_name')->get();
    }

    public function getCostCentersProperty()
    {
        return CostCenter::active()->orderBy('code')->get();
    }

    public function getServiceTypesProperty()
    {
        $query = ServiceType::active();

        if ($this->department_id) {
            $query->where('department_id', $this->department_id);
        }

        return $query->orderBy('service_type')->get();
    }

    public function getUomsProperty()
    {
        return UOM::active()->orderBy('code')->get();
    }

    public function getDepartmentsProperty()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return Department::active()->get();
        }

        return Department::active()->whereIn('id', $user->getDepartmentIds())->get();
    }

    public function getProgressPercentageProperty()
    {
        return round(($this->currentStep / $this->totalSteps) * 100);
    }

    // ==========================================
    // Step Navigation
    // ==========================================

    public function nextStep()
    {
        // Validate current step before proceeding
        if (!$this->validateCurrentStep()) {
            return;
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->validatedSteps[] = $this->currentStep - 1;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Can only jump to already validated steps or next step
            if (in_array($step - 1, $this->validatedSteps) || $step <= max($this->validatedSteps) + 2) {
                $this->currentStep = $step;
            }
        }
    }

    private function validateCurrentStep(): bool
    {
        try {
            switch ($this->currentStep) {
                case 1:
                    $this->validateOnly(['ticket_date', 'department_id', 'client_type', $this->client_type === ClientType::CLIENT->value ? 'client_id' : 'cost_center_id', 'service_type_id', 'payment_type', 'currency']);
                    break;

                case 2:
                    $this->validateOnly(['transactions', 'transactions.*.description', 'transactions.*.qty', 'transactions.*.uom_id', 'transactions.*.unit_cost']);

                    if (empty($this->transactions)) {
                        $this->dispatch('toast', type: 'error', message: 'Please add at least one line item.');
                        return false;
                    }
                    break;

                case 3:
                    $this->validateOnly(['vat_percentage', 'remarks']);
                    break;
            }

            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('toast', type: 'error', message: 'Please fix validation errors before continuing.');
            return false;
        }
    }

    // ==========================================
    // Line Items Management (Alpine.js assisted)
    // ==========================================

    public function addLineItem()
    {
        $this->transactions[] = [
            'temp_id' => Str::uuid(),
            'sr_no' => count($this->transactions) + 1,
            'description' => '',
            'qty' => 1,
            'uom_id' => null,
            'unit_cost' => 0,
            'total_cost' => 0,
        ];
    }

    public function removeLineItem($index)
    {
        unset($this->transactions[$index]);
        $this->transactions = array_values($this->transactions); // Re-index array

        // Update serial numbers
        foreach ($this->transactions as $key => $transaction) {
            $this->transactions[$key]['sr_no'] = $key + 1;
        }

        $this->calculateTotals();
    }

    public function duplicateLineItem($index)
    {
        if (isset($this->transactions[$index])) {
            $item = $this->transactions[$index];
            $item['temp_id'] = Str::uuid();
            $item['sr_no'] = count($this->transactions) + 1;
            $this->transactions[] = $item;
        }
    }

    public function updatedTransactions()
    {
        $this->calculateTotals();
    }

    public function calculateLineItemTotal($index)
    {
        if (isset($this->transactions[$index])) {
            $qty = (float) ($this->transactions[$index]['qty'] ?? 0);
            $unitCost = (float) ($this->transactions[$index]['unit_cost'] ?? 0);
            $this->transactions[$index]['total_cost'] = $qty * $unitCost;
            $this->calculateTotals();
        }
    }

    // ==========================================
    // Calculations
    // ==========================================

    public function calculateTotals()
    {
        // Calculate subtotal
        $this->subtotal = collect($this->transactions)->sum('total_cost');

        // Calculate VAT
        $this->vat_amount = ($this->subtotal * $this->vat_percentage) / 100;

        // Calculate total
        $this->total_amount = $this->subtotal + $this->vat_amount;
    }

    public function updatedVatPercentage()
    {
        $this->calculateTotals();
    }

    // ==========================================
    // Department & Client Type Changes
    // ==========================================

    public function updatedDepartmentId()
    {
        // Reset dependent fields
        $this->service_type_id = null;

        // Update preview ticket number
        $this->updatePreviewTicketNumber();
    }

    public function updatedClientType()
    {
        // Reset selected client/cost center
        $this->client_id = null;
        $this->cost_center_id = null;
    }

    private function updatePreviewTicketNumber()
    {
        if ($this->department_id) {
            $service = app(TicketNumberService::class);
            $this->previewTicketNumber = $service->previewNextNumber($this->department_id);
        }
    }

    // ==========================================
    // File Upload Management
    // ==========================================

    public function updatedAttachments()
    {
        // Validate each file as it's uploaded
        $this->validate([
            'attachments.*' => 'file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
        ]);
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function removeExistingAttachment($attachmentId)
    {
        try {
            $attachment = TicketAttachment::find($attachmentId);
            if ($attachment) {
                $attachment->delete();
                $this->existingAttachments = collect($this->existingAttachments)->reject(fn($att) => $att['id'] === $attachmentId)->values()->toArray();

                $this->dispatch('toast', type: 'success', message: 'Attachment removed.');
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Failed to remove attachment.');
        }
    }

    // ==========================================
    // Auto-Save Draft
    // ==========================================

    private function autoSaveDraft()
    {
        if ($this->editMode) {
            return; // Don't auto-save in edit mode
        }

        try {
            $autoSaveService = app(TicketAutoSaveService::class);

            $autoSaveService->saveDraft(Auth::id(), [
                'ticket_date' => $this->ticket_date,
                'department_id' => $this->department_id,
                'client_type' => $this->client_type,
                'client_id' => $this->client_id,
                'cost_center_id' => $this->cost_center_id,
                'project_code' => $this->project_code,
                'contract_no' => $this->contract_no,
                'service_location' => $this->service_location,
                'service_type_id' => $this->service_type_id,
                'ref_no' => $this->ref_no,
                'payment_terms' => $this->payment_terms,
                'payment_type' => $this->payment_type,
                'currency' => $this->currency,
                'transactions' => $this->transactions,
                'vat_percentage' => $this->vat_percentage,
                'remarks' => $this->remarks,
            ]);

            $this->lastSaved = now()->format('H:i:s');
        } catch (\Exception $e) {
            \Log::error('Auto-save failed: ' . $e->getMessage());
        }
    }

    private function checkAutoSavedDraft()
    {
        $autoSaveService = app(TicketAutoSaveService::class);

        if ($autoSaveService->hasDraft(Auth::id())) {
            $draft = $autoSaveService->getDraft(Auth::id());

            // Ask user if they want to restore
            $this->dispatch('confirm-restore-draft', draft: $draft);
        }
    }

    #[On('restore-draft')]
    public function restoreDraft($draft)
    {
        foreach ($draft['data'] as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $this->calculateTotals();
        $this->dispatch('toast', type: 'success', message: 'Draft restored successfully!');
    }

    #[On('discard-draft')]
    public function discardDraft()
    {
        $autoSaveService = app(TicketAutoSaveService::class);
        $autoSaveService->clearDraft(Auth::id());

        $this->dispatch('toast', type: 'info', message: 'Draft discarded.');
    }

    // ==========================================
    // Save Actions
    // ==========================================

    public function saveDraft()
    {
        $this->save(false);
    }

    public function saveAndPost()
    {
        $this->save(true);
    }

    private function save($postImmediately = false)
    {
        // Final validation
        $this->validate();

        if (empty($this->transactions)) {
            $this->dispatch('toast', type: 'error', message: 'Please add at least one line item.');
            return;
        }

        try {
            DB::transaction(function () use ($postImmediately) {
                $ticketNumberService = app(TicketNumberService::class);

                if ($this->editMode) {
                    // Update existing ticket
                    $ticket = TicketMaster::findOrFail($this->ticketId);

                    // Only allow editing drafts
                    if (!$ticket->canEdit()) {
                        throw new \Exception('This ticket cannot be edited.');
                    }

                    $this->updateTicket($ticket);
                } else {
                    // Create new ticket
                    $ticketNumber = $ticketNumberService->reserveTicketNumber($this->department_id);

                    $ticket = $this->createTicket($ticketNumber['number']);
                }

                // Save line items
                $this->saveTransactions($ticket);

                // Save attachments
                $this->saveAttachments($ticket);

                // Post if requested and user has permission
                if ($postImmediately && Auth::user()->isAdmin()) {
                    $this->postTicket($ticket);
                }

                // Clear auto-saved draft
                if (!$this->editMode) {
                    $autoSaveService = app(TicketAutoSaveService::class);
                    $autoSaveService->clearDraft(Auth::id());
                }

                $message = $this->editMode ? 'Ticket updated successfully!' : 'Ticket created successfully!';
                $this->dispatch('toast', type: 'success', message: $message);

                // Redirect to list
                return redirect()->route('tickets.finance.index');
            });
        } catch (\Exception $e) {
            \Log::error('Error saving ticket: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Failed to save ticket: ' . $e->getMessage());
        }
    }

    private function createTicket($ticketNumber): TicketMaster
    {
        $department = Department::find($this->department_id);

        return TicketMaster::create([
            'prefix' => $department->prefix,
            'ticket_no' => $ticketNumber,
            'ticket_type' => TicketType::FINANCE,
            'ticket_date' => $this->ticket_date,
            'department_id' => $this->department_id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'host_name' => request()->ip(),
            'client_type' => ClientType::from($this->client_type),
            'client_id' => $this->client_id,
            'cost_center_id' => $this->cost_center_id,
            'project_code' => $this->project_code,
            'contract_no' => $this->contract_no,
            'service_location' => $this->service_location,
            'service_type_id' => $this->service_type_id,
            'ref_no' => $this->ref_no,
            'payment_terms' => $this->payment_terms,
            'payment_type' => PaymentType::from($this->payment_type),
            'currency' => Currency::from($this->currency),
            'subtotal' => $this->subtotal,
            'vat_percentage' => $this->vat_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
            'remarks' => $this->remarks,
            'status' => TicketStatus::DRAFT,
            'created_by' => Auth::id(),
        ]);
    }

    private function updateTicket(TicketMaster $ticket): void
    {
        $ticket->update([
            'ticket_date' => $this->ticket_date,
            'client_type' => ClientType::from($this->client_type),
            'client_id' => $this->client_id,
            'cost_center_id' => $this->cost_center_id,
            'project_code' => $this->project_code,
            'contract_no' => $this->contract_no,
            'service_location' => $this->service_location,
            'service_type_id' => $this->service_type_id,
            'ref_no' => $this->ref_no,
            'payment_terms' => $this->payment_terms,
            'payment_type' => PaymentType::from($this->payment_type),
            'currency' => Currency::from($this->currency),
            'subtotal' => $this->subtotal,
            'vat_percentage' => $this->vat_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
            'remarks' => $this->remarks,
            'updated_by' => Auth::id(),
        ]);
    }

    private function saveTransactions(TicketMaster $ticket): void
    {
        // Delete existing transactions if editing
        if ($this->editMode) {
            $ticket->transactions()->delete();
        }

        foreach ($this->transactions as $index => $transaction) {
            TicketTransaction::create([
                'ticket_id' => $ticket->id,
                'sr_no' => $index + 1,
                'description' => $transaction['description'],
                'qty' => $transaction['qty'],
                'uom_id' => $transaction['uom_id'],
                'unit_cost' => $transaction['unit_cost'],
                'total_cost' => $transaction['total_cost'],
                'created_by' => Auth::id(),
            ]);
        }
    }

    private function saveAttachments(TicketMaster $ticket): void
    {
        foreach ($this->attachments as $file) {
            try {
                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $storedName = $ticket->ticket_no . '_' . time() . '_' . Str::random(8) . '.' . $extension;

                // Store file
                $path = $file->storeAs('attachments/' . date('Y/m'), $storedName, 'public');

                // Save to database
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'original_name' => $originalName,
                    'stored_name' => $storedName,
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'file_type' => $this->determineFileType($file->getMimeType()),
                    'uploaded_by' => Auth::id(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Error saving attachment: ' . $e->getMessage());
            }
        }
    }

    private function determineFileType($mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif ($mimeType === 'application/pdf') {
            return 'document';
        } else {
            return 'other';
        }
    }

    private function postTicket(TicketMaster $ticket): void
    {
        $ticket->update([
            'status' => TicketStatus::POSTED,
            'posted_date' => now(),
        ]);

        // Send notification
        try {
            $ticket->user->notify(new TicketPostedNotification($ticket));
        } catch (\Exception $e) {
            \Log::error('Failed to send post notification: ' . $e->getMessage());
        }
    }

    // ==========================================
    // Load Ticket (Edit/Duplicate Mode)
    // ==========================================

    private function loadTicket($ticketId, $duplicate = false)
    {
        $ticket = TicketMaster::with(['transactions', 'attachments'])->findOrFail($ticketId);

        // Check permissions
        if (!$ticket->canEdit() && !$duplicate) {
            abort(403, 'You cannot edit this ticket.');
        }

        $this->editMode = !$duplicate;
        $this->isDuplicate = $duplicate;
        $this->ticketId = $duplicate ? null : $ticket->id;

        // Load header data
        $this->ticket_date = $duplicate ? now()->format('Y-m-d') : $ticket->ticket_date->format('Y-m-d');
        $this->department_id = $ticket->department_id;
        $this->client_type = $ticket->client_type->value;
        $this->client_id = $ticket->client_id;
        $this->cost_center_id = $ticket->cost_center_id;
        $this->project_code = $ticket->project_code;
        $this->contract_no = $ticket->contract_no;
        $this->service_location = $ticket->service_location;
        $this->service_type_id = $ticket->service_type_id;
        $this->ref_no = $ticket->ref_no;
        $this->payment_terms = $ticket->payment_terms;
        $this->payment_type = $ticket->payment_type->value;
        $this->currency = $ticket->currency->value;
        $this->vat_percentage = $ticket->vat_percentage;
        $this->remarks = $ticket->remarks;

        // Load transactions
        $this->transactions = $ticket->transactions
            ->map(function ($trans) {
                return [
                    'temp_id' => Str::uuid(),
                    'sr_no' => $trans->sr_no,
                    'description' => $trans->description,
                    'qty' => $trans->qty,
                    'uom_id' => $trans->uom_id,
                    'unit_cost' => $trans->unit_cost,
                    'total_cost' => $trans->total_cost,
                ];
            })
            ->toArray();

        // Load existing attachments (don't duplicate attachments)
        if (!$duplicate) {
            $this->existingAttachments = $ticket->attachments
                ->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'original_name' => $att->original_name,
                        'file_size' => $att->file_size,
                        'human_file_size' => $att->human_file_size,
                        'file_type' => $att->file_type,
                        'icon' => $att->icon,
                    ];
                })
                ->toArray();
        }

        $this->calculateTotals();

        // Update preview ticket number for duplicates
        if ($duplicate) {
            $this->updatePreviewTicketNumber();
        }
    }

    // ==========================================
    // Quick Add Modals
    // ==========================================

    // public function openQuickAddClient()
    // {
    //     $this->showQuickAddClient = true;
    // }

    // public function openQuickAddUOM()
    // {
    //     $this->showQuickAddUOM = true;
    // }

    // public function openQuickAddServiceType()
    // {
    //     $this->showQuickAddServiceType = true;
    // }

    // #[On('client-created')]
    // public function clientCreated($clientId)
    // {
    //     $this->client_id = $clientId;
    //     $this->showQuickAddClient = false;
    //     $this->dispatch('toast', type: 'success', message: 'Client added successfully!');
    // }

    // #[On('uom-created')]
    // public function uomCreated($uomId)
    // {
    //     $this->showQuickAddUOM = false;
    //     $this->dispatch('toast', type: 'success', message: 'UOM added successfully!');
    //     $this->dispatch('uom-list-updated');
    // }

    // #[On('service-type-created')]
    // public function serviceTypeCreated($serviceTypeId)
    // {
    //     $this->service_type_id = $serviceTypeId;
    //     $this->showQuickAddServiceType = false;
    //     $this->dispatch('toast', type: 'success', message: 'Service Type added successfully!');
    // }


    // Add method to open modal
    public function openQuickAddClient()
    {
        if (!$this->department_id) {
            $this->dispatch('toast', type: 'warning', message: 'Please select a department first.');
            return;
        }

        $this->showQuickAddClient = true;
    }

    // Update the existing listener
    #[On('client-created')]
    public function clientCreated($clientId)
    {
        $this->client_id = $clientId;
        $this->showQuickAddClient = false;
        $this->dispatch('toast', type: 'success', message: 'Client added successfully!');
    }

    #[On('close-quick-add-client')]
    public function closeQuickAddClient()
    {
        $this->showQuickAddClient = false;
    }

    // ==========================================
    // Render
    // ==========================================

    public function render()
    {
        return view('livewire.tickets.finance.create', [
            'clients' => $this->clients,
            'costCenters' => $this->costCenters,
            'serviceTypes' => $this->serviceTypes,
            'uoms' => $this->uoms,
            'departments' => $this->departments,
            'progressPercentage' => $this->progressPercentage,
        ])->layout('admin.layout');
    }
}
