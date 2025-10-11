<?php
// app/Livewire/Tickets/Finance/CreateFinanceTicket.php

namespace App\Livewire\Tickets\Finance;

use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\PaymentType;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Client;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\ServiceType;
use App\Models\TicketAttachment;
use App\Models\TicketMaster;
use App\Models\TicketStatusHistory;
use App\Models\TicketTransaction;
use App\Models\UOM;
use App\Services\TicketNumberService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ActivityLogService;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketPostedNotification;

class CreateFinanceTicket extends Component
{
    use WithFileUploads;

    // ==========================================
    // Properties
    // ==========================================

    // Mode flags
    public $editMode = false;
    public $ticketId = null;
    public $isDuplicate = false;

    // Step wizard
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

    // Line Items (Step 2)
    public $transactions = [];
    public $transactionCounter = 0;

    // Totals (Step 3)
    public $subtotal = 0;
    public $vat_percentage = 5;
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

    // Preview ticket number (for display only)
    public $previewTicketNumber = null;

    // Validation tracking
    public $validatedSteps = [];

    // Services
    protected $ticketNumberService;

    // ==========================================
    // Lifecycle Hooks
    // ==========================================

    /**
     * Inject TicketNumberService dependency
     */
    public function boot(TicketNumberService $ticketNumberService)
    {
        $this->ticketNumberService = $ticketNumberService;
    }

    /**
     * Component initialization
     *
     * @param int|null $ticketId Ticket ID for edit mode
     * @param bool $duplicate Whether this is a duplicate operation
     */
    public function mount($ticketId = null, $duplicate = false)
    {
        // Set default date
        $this->ticket_date = now()->format('Y-m-d');

        // Get user's first department
        $userDepartments = Auth::user()->departments;
        if ($userDepartments->isNotEmpty()) {
            $this->department_id = $userDepartments->first()->id;
        }

        // Check if this is duplicate from route name
        if (request()->routeIs('tickets.finance.duplicate')) {
            $duplicate = true;
        }

        // Edit or Duplicate mode
        if ($ticketId) {
            $this->ticketId = $ticketId;
            $this->isDuplicate = $duplicate;
            $this->editMode = !$duplicate;

            $this->loadTicket($ticketId);

            // If duplicate, reset ID and status
            if ($duplicate) {
                $this->ticketId = null;
            }
        } else {
            // New ticket - add empty transaction row
            $this->addTransaction();
        }

        // Load preview number for new tickets
        // IMPORTANT: This is just a preview, actual number assigned on POST
        if (!$this->editMode && $this->department_id) {
            $this->previewTicketNumber = $this->ticketNumberService->previewNextNumber($this->department_id);
        }

        // Check for draft in session
        $this->checkForDraft();
    }

    /**
     * Load existing ticket data for edit/duplicate
     *
     * @param int $ticketId
     */
    protected function loadTicket($ticketId)
    {
        $ticket = TicketMaster::with(['transactions', 'attachments'])->findOrFail($ticketId);

        // Check access
        if (!Auth::user()->isSuperAdmin()) {
            $userDeptIds = Auth::user()->getDepartmentIds();
            if (!in_array($ticket->department_id, $userDeptIds)) {
                abort(403, 'You do not have access to this ticket.');
            }
        }

        // Load header data
        $this->ticket_date = $ticket->ticket_date->format('Y-m-d');
        $this->department_id = $ticket->department_id;
        $this->client_type = $ticket->client_type->value;
        $this->client_id = $ticket->client_id;
        $this->cost_center_id = $ticket->cost_center_id;
        $this->project_code = $ticket->project_code ?? '';
        $this->contract_no = $ticket->contract_no ?? '';
        $this->service_location = $ticket->service_location ?? '';
        $this->service_type_id = $ticket->service_type_id;
        $this->ref_no = $ticket->ref_no ?? '';
        $this->payment_terms = $ticket->payment_terms ?? '';
        $this->payment_type = $ticket->payment_type->value;
        $this->currency = $ticket->currency->value;
        $this->vat_percentage = $ticket->vat_percentage;
        $this->remarks = $ticket->remarks ?? '';

        // Load transactions
        $this->transactions = $ticket->transactions
            ->map(function ($trans) {
                return [
                    'temp_id' => uniqid(),
                    'sr_no' => $trans->sr_no,
                    'description' => $trans->description,
                    'qty' => $trans->qty,
                    'uom_id' => $trans->uom_id,
                    'unit_cost' => $trans->unit_cost,
                    'total_cost' => $trans->total_cost,
                ];
            })
            ->toArray();

        // Load attachments (for edit mode only)
        if (!$this->isDuplicate) {
            $this->existingAttachments = $ticket->attachments
                ->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'name' => $att->original_name,
                        'size' => $att->human_file_size,
                    ];
                })
                ->toArray();
        }

        // Calculate totals
        $this->calculateTotals();
    }

    // ==========================================
    // Step Navigation
    // ==========================================

    /**
     * Move to next step with validation
     */
    public function nextStep()
    {
        // Validate current step
        $this->validateCurrentStep();

        // Mark step as validated
        $this->validatedSteps[] = $this->currentStep;

        // Move forward
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    /**
     * Move to previous step
     */
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Jump to specific step
     * Only allowed if previous steps are validated
     */
    public function goToStep($step)
    {
        // Validate range
        if ($step < 1 || $step > $this->totalSteps) {
            return;
        }

        // Moving forward - validate current step first
        if ($step > $this->currentStep) {
            try {
                $this->validateCurrentStep();
                if (!in_array($this->currentStep, $this->validatedSteps)) {
                    $this->validatedSteps[] = $this->currentStep;
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => 'Please fix validation errors before proceeding.',
                    'title' => 'Validation Error',
                ]);
                throw $e;
            }
        }

        // Update current step
        $this->currentStep = $step;
    }

    /**
     * Get progress percentage for progress bar
     */
    public function getProgressPercentageProperty()
    {
        return ($this->currentStep / $this->totalSteps) * 100;
    }

    // ==========================================
    // Validation
    // ==========================================

    /**
     * Validate current step
     */
    protected function validateCurrentStep()
    {
        if ($this->currentStep === 1) {
            $this->validate(
                [
                    'ticket_date' => 'required|date|before_or_equal:today',
                    'department_id' => 'required|exists:departments,id',
                    'client_type' => 'required|in:client,cost_center',
                    'client_id' => 'required_if:client_type,client|exists:clients,id',
                    'cost_center_id' => 'required_if:client_type,cost_center|exists:cost_centers,id',
                    'payment_type' => 'required|in:po,cash,credit_card,na',
                    'currency' => 'required|in:usd,aed,euro,others',
                ],
                [
                    'client_id.required_if' => 'Please select a client.',
                    'cost_center_id.required_if' => 'Please select a cost center.',
                    'ticket_date.required' => 'Ticket date is required.',
                    'ticket_date.before_or_equal' => 'Ticket date cannot be in the future.',
                    'department_id.required' => 'Department is required.',
                ],
            );
        }

        if ($this->currentStep === 2) {
            $this->validate(
                [
                    'transactions' => 'required|array|min:1',
                    'transactions.*.description' => 'required|string|max:500',
                    'transactions.*.qty' => 'required|numeric|min:0.001',
                    'transactions.*.uom_id' => 'required|exists:uom,id',
                    'transactions.*.unit_cost' => 'required|numeric|min:0',
                ],
                [
                    'transactions.required' => 'At least one line item is required.',
                    'transactions.*.description.required' => 'Description is required for all items.',
                    'transactions.*.qty.required' => 'Quantity is required for all items.',
                    'transactions.*.qty.min' => 'Quantity must be greater than 0.',
                    'transactions.*.uom_id.required' => 'UOM is required for all items.',
                    'transactions.*.unit_cost.required' => 'Unit cost is required for all items.',
                ],
            );
        }
    }

    /**
     * Get all validation rules (for final save)
     */
    protected function getAllValidationRules()
    {
        return [
            'ticket_date' => 'required|date|before_or_equal:today',
            'department_id' => 'required|exists:departments,id',
            'client_type' => 'required|in:client,cost_center',
            'client_id' => 'required_if:client_type,client|exists:clients,id',
            'cost_center_id' => 'required_if:client_type,cost_center|exists:cost_centers,id',
            'payment_type' => 'required|in:po,cash,credit_card,na',
            'currency' => 'required|in:usd,aed,euro,others',
            'transactions' => 'required|array|min:1',
            'transactions.*.description' => 'required|string|max:500',
            'transactions.*.qty' => 'required|numeric|min:0.001',
            'transactions.*.uom_id' => 'required|exists:uom,id',
            'transactions.*.unit_cost' => 'required|numeric|min:0',
        ];
    }

    // ==========================================
    // Save Operations
    // ==========================================

    /**
     * Save ticket as DRAFT
     *
     * OPTION C: Drafts get DRAFT-xxx IDs (no sequential number)
     * They can be freely deleted without affecting sequence
     */
    public function saveDraft()
    {
        // Validate all fields
        $this->validate($this->getAllValidationRules());

        DB::transaction(function () {
            if ($this->editMode) {
                // Update existing ticket
                $ticket = TicketMaster::findOrFail($this->ticketId);

                // Capture old state for logging
                $oldTicketNo = $ticket->ticket_no;

                $this->updateTicket($ticket);

                // Log update
                $activityLog = app(ActivityLogService::class);
                $activityLog->log(description: "Updated ticket {$oldTicketNo}", subject: $ticket, event: 'updated', logName: 'tickets');

                $message = 'Ticket updated successfully!';
            } else {
                // Create new DRAFT ticket
                $ticket = $this->createDraftTicket();

                $message = 'Ticket saved as draft successfully!';
            }

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => $message,
                'title' => 'Success',
            ]);

            return redirect()->route('tickets.finance.index');
        });
    }

    /**
     * Save ticket and immediately POST it
     *
     * OPTION C: ONLY posted tickets get sequential numbers
     * This is where the TicketNumberService is called
     */
    public function saveAndPost()
    {
        // Validate all fields
        $this->validate($this->getAllValidationRules());

        // Admin check
        if (!Auth::user()->isAdmin()) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Only Admins can post tickets.',
            ]);
            return;
        }

        DB::transaction(function () {
            if ($this->editMode) {
                // Update existing ticket
                $ticket = TicketMaster::findOrFail($this->ticketId);

                $oldTicketNo = $ticket->ticket_no;
                $oldStatus = $ticket->status;

                // If draft being posted for first time, assign sequential number NOW
                if ($ticket->status === TicketStatus::DRAFT && str_starts_with($ticket->ticket_no, 'DRAFT-')) {
                    // Replace DRAFT-xxx with sequential number
                    $department = Department::findOrFail($this->department_id);
                    $ticket->ticket_no = $this->ticketNumberService->generateTicketNumber($this->department_id);
                    $ticket->prefix = $department->prefix;
                }

                $this->updateTicket($ticket);

                // Change status to POSTED
                $ticket->status = TicketStatus::POSTED;
                $ticket->posted_date = now();
                $ticket->save();

                // Log status change
                $this->logStatusChange($ticket, $oldStatus, TicketStatus::POSTED, 'Ticket posted');

                // Log activity
                $activityLog = app(ActivityLogService::class);
                $activityLog->log(
                    description: "Posted ticket {$ticket->ticket_no}" . ($oldTicketNo !== $ticket->ticket_no ? " (was {$oldTicketNo})" : ''),
                    subject: $ticket,
                    event: 'posted',
                    properties: [
                        'old_ticket_no' => $oldTicketNo,
                        'new_ticket_no' => $ticket->ticket_no,
                        'old_status' => $oldStatus->value,
                        'new_status' => TicketStatus::POSTED->value,
                    ],
                    logName: 'tickets',
                );

                // Send notification
                try {
                    $ticket->user->notify(new TicketPostedNotification($ticket));
                } catch (\Exception $e) {
                    \Log::error('Failed to send post notification: ' . $e->getMessage());
                }
            } else {
                // Create new ticket and POST immediately
                $ticket = $this->createPostedTicket();

                // Log status change
                $this->logStatusChange($ticket, null, TicketStatus::POSTED, 'Ticket created and posted');
            }

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Ticket posted successfully!',
                'title' => 'Success',
            ]);

            return redirect()->route('tickets.finance.index');
        });
    }

    /**
     * Create a DRAFT ticket
     *
     * OPTION C: Draft tickets get DRAFT-{uniqid} as ticket number
     * NO sequential number assigned yet
     * Can be freely deleted without affecting the sequence
     */
    protected function createDraftTicket()
    {
        $department = Department::findOrFail($this->department_id);

        // OPTION C: Generate temporary DRAFT ID (not sequential)
        $draftId = 'DRAFT-' . uniqid();

        $ticketData = [
            // IMPORTANT: DRAFT-xxx instead of sequential number
            'ticket_no' => $draftId,
            'prefix' => 'DRAFT', // Temporary prefix
            'ticket_type' => TicketType::FINANCE,
            'ticket_date' => $this->ticket_date,
            'department_id' => $this->department_id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'host_name' => request()->ip(),
            'client_type' => $this->client_type,
            'client_id' => $this->client_type === 'client' ? $this->client_id : null,
            'cost_center_id' => $this->client_type === 'cost_center' ? $this->cost_center_id : null,
            'project_code' => $this->project_code ?: null,
            'contract_no' => $this->contract_no ?: null,
            'service_location' => $this->service_location ?: null,
            'service_type_id' => $this->service_type_id,
            'ref_no' => $this->ref_no ?: null,
            'payment_terms' => $this->payment_terms ?: null,
            'payment_type' => $this->payment_type,
            'currency' => $this->currency,
            'vat_percentage' => $this->vat_percentage,
            'remarks' => $this->remarks ?: null,
            'status' => TicketStatus::DRAFT,
            'created_by' => Auth::id(),
        ];

        $ticket = TicketMaster::create($ticketData);

        // Save line items
        $this->saveTransactions($ticket);

        // Save attachments
        $this->saveAttachments($ticket);

        // Calculate and update totals
        $ticket->calculateTotals();

        // Log creation
        $this->logStatusChange($ticket, null, TicketStatus::DRAFT, 'Draft ticket created');

        // OPTION C: Add activity log
        $activityLog = app(ActivityLogService::class);
        $activityLog->logCreated($ticket, "Created draft ticket {$draftId} for " . ($this->client_type === 'client' ? Client::find($this->client_id)?->client_name : CostCenter::find($this->cost_center_id)?->name));

        Log::info("Draft ticket created with temporary ID: {$draftId} by " . Auth::user()->name);

        return $ticket;
    }

    /**
     * Create a POSTED ticket (new ticket being posted immediately)
     *
     * OPTION C: Posted tickets get SEQUENTIAL numbers
     * This is where TicketNumberService.generateTicketNumber() is called
     */
    protected function createPostedTicket()
    {
        $department = Department::findOrFail($this->department_id);

        // OPTION C: Generate sequential number using service (with database locking)
        // This is the ONLY place where sequential numbers are assigned
        $sequentialNumber = $this->ticketNumberService->generateTicketNumber($this->department_id);

        $ticketData = [
            // IMPORTANT: Sequential number assigned HERE
            'ticket_no' => $sequentialNumber,
            'prefix' => $department->prefix,
            'ticket_type' => TicketType::FINANCE,
            'ticket_date' => $this->ticket_date,
            'department_id' => $this->department_id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'host_name' => request()->ip(),
            'client_type' => $this->client_type,
            'client_id' => $this->client_type === 'client' ? $this->client_id : null,
            'cost_center_id' => $this->client_type === 'cost_center' ? $this->cost_center_id : null,
            'project_code' => $this->project_code ?: null,
            'contract_no' => $this->contract_no ?: null,
            'service_location' => $this->service_location ?: null,
            'service_type_id' => $this->service_type_id,
            'ref_no' => $this->ref_no ?: null,
            'payment_terms' => $this->payment_terms ?: null,
            'payment_type' => $this->payment_type,
            'currency' => $this->currency,
            'vat_percentage' => $this->vat_percentage,
            'remarks' => $this->remarks ?: null,
            'status' => TicketStatus::POSTED,
            'posted_date' => now(),
            'created_by' => Auth::id(),
        ];

        $ticket = TicketMaster::create($ticketData);

        // Save line items
        $this->saveTransactions($ticket);

        // Save attachments
        $this->saveAttachments($ticket);

        // Calculate and update totals
        $ticket->calculateTotals();

        // OPTION C: Add activity log
        $activityLog = app(ActivityLogService::class);
        $activityLog->logCreated($ticket, "Created and posted ticket {$sequentialNumber} for " . ($this->client_type === 'client' ? Client::find($this->client_id)?->client_name : CostCenter::find($this->cost_center_id)?->name));

        // Send notification
        try {
            $ticket->user->notify(new TicketPostedNotification($ticket));
        } catch (\Exception $e) {
            Log::error('Failed to send post notification: ' . $e->getMessage());
        }

        Log::info("Posted ticket created with sequential number: {$sequentialNumber} by " . Auth::user()->name);

        return $ticket;
    }

    /**
     * Update existing ticket (edit mode)
     */
    protected function updateTicket($ticket)
    {
        // CRITICAL: Capture old attributes BEFORE any changes for activity logging
        $oldAttributes = [
            'ticket_date' => $ticket->ticket_date?->format('Y-m-d'),
            'client_type' => $ticket->client_type->value ?? null,
            'client_id' => $ticket->client_id,
            'cost_center_id' => $ticket->cost_center_id,
            'project_code' => $ticket->project_code,
            'contract_no' => $ticket->contract_no,
            'service_location' => $ticket->service_location,
            'service_type_id' => $ticket->service_type_id,
            'ref_no' => $ticket->ref_no,
            'payment_terms' => $ticket->payment_terms,
            'payment_type' => $ticket->payment_type->value ?? null,
            'currency' => $ticket->currency->value ?? null,
            'vat_percentage' => $ticket->vat_percentage,
            'remarks' => $ticket->remarks,
            'transaction_count' => $ticket->transactions()->count(),
        ];

        // Update header data
        $ticket->ticket_date = $this->ticket_date;
        $ticket->client_type = $this->client_type;
        $ticket->client_id = $this->client_type === 'client' ? $this->client_id : null;
        $ticket->cost_center_id = $this->client_type === 'cost_center' ? $this->cost_center_id : null;
        $ticket->project_code = $this->project_code ?: null;
        $ticket->contract_no = $this->contract_no ?: null;
        $ticket->service_location = $this->service_location ?: null;
        $ticket->service_type_id = $this->service_type_id;
        $ticket->ref_no = $this->ref_no ?: null;
        $ticket->payment_terms = $this->payment_terms ?: null;
        $ticket->payment_type = $this->payment_type;
        $ticket->currency = $this->currency;
        $ticket->vat_percentage = $this->vat_percentage;
        $ticket->remarks = $this->remarks ?: null;
        $ticket->updated_by = Auth::id();
        $ticket->save();

        // Delete old transactions
        $ticket->transactions()->delete();

        // Save new transactions
        $this->saveTransactions($ticket);

        // Save new attachments
        $this->saveAttachments($ticket);

        // Recalculate totals
        $ticket->calculateTotals();

        // Activity log
        try {
            $activityLog = app(ActivityLogService::class);
            $activityLog->logUpdated($ticket, $oldAttributes, "Updated ticket {$ticket->ticket_no}");
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
            // Don't fail the update if logging fails
        }
    }

    /**
     * Save transaction line items
     */
    protected function saveTransactions($ticket)
    {
        foreach ($this->transactions as $index => $trans) {
            TicketTransaction::create([
                'ticket_id' => $ticket->id,
                'sr_no' => $index + 1,
                'description' => $trans['description'],
                'qty' => $trans['qty'],
                'uom_id' => $trans['uom_id'],
                'unit_cost' => $trans['unit_cost'],
                'total_cost' => $trans['qty'] * $trans['unit_cost'],
                'created_by' => Auth::id(),
            ]);
        }
    }

    /**
     * Save file attachments
     */
    protected function saveAttachments($ticket)
    {
        if (empty($this->attachments)) {
            return;
        }

        foreach ($this->attachments as $file) {
            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $storedName = $ticket->ticket_no . '_' . now()->format('YmdHis') . '_' . uniqid() . '.' . $extension;

            // Store file
            $path = $file->storeAs('attachments/' . now()->format('Y/m'), $storedName, 'public');

            // Determine file type
            $mimeType = $file->getMimeType();
            $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'document';

            // Save to database
            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'file_path' => $path,
                'mime_type' => $mimeType,
                'file_size' => $file->getSize(),
                'file_type' => $fileType,
                'uploaded_by' => Auth::id(),
            ]);
        }
    }

    /**
     * Log ticket status change to history
     */
    protected function logStatusChange($ticket, $fromStatus, $toStatus, $notes = null)
    {
        TicketStatusHistory::create([
            'ticket_id' => $ticket->id,
            'from_status' => $fromStatus?->value,
            'to_status' => $toStatus->value,
            'notes' => $notes,
            'changed_by' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changed_at' => now(),
        ]);
    }

    // ==========================================
    // Line Items Management
    // ==========================================

    /**
     * Add new transaction row
     */
    public function addTransaction()
    {
        $this->transactions[] = [
            'temp_id' => uniqid(),
            'sr_no' => count($this->transactions) + 1,
            'description' => '',
            'qty' => 1,
            'uom_id' => null,
            'unit_cost' => 0,
            'total_cost' => 0,
        ];
    }

    /**
     * Remove transaction row
     */
    public function removeTransaction($index)
    {
        unset($this->transactions[$index]);
        $this->transactions = array_values($this->transactions); // Re-index

        // Update serial numbers
        foreach ($this->transactions as $i => &$trans) {
            $trans['sr_no'] = $i + 1;
        }

        $this->calculateTotals();
    }

    /**
     * Update transaction when qty or unit_cost changes
     */
    public function updatedTransactions()
    {
        // Recalculate line totals
        foreach ($this->transactions as &$trans) {
            $trans['total_cost'] = $trans['qty'] * $trans['unit_cost'];
        }

        $this->calculateTotals();
    }

    /**
     * Calculate ticket totals (subtotal, VAT, grand total)
     */
    public function calculateTotals()
    {
        // Calculate subtotal
        $this->subtotal = collect($this->transactions)->sum('total_cost');

        // Calculate VAT
        $this->vat_amount = ($this->subtotal * $this->vat_percentage) / 100;

        // Calculate grand total
        $this->total_amount = $this->subtotal + $this->vat_amount;
    }

    /**
     * Update VAT percentage
     */
    public function updatedVatPercentage()
    {
        $this->calculateTotals();
    }

    // ==========================================
    // Quick Add Modals
    // ==========================================

    public function openQuickAddClient()
    {
        $this->showQuickAddClient = true;
    }

    public function closeQuickAddClient()
    {
        $this->showQuickAddClient = false;
    }

    public function openQuickAddUom()
    {
        $this->showQuickAddUOM = true;
    }

    public function closeQuickAddUom()
    {
        $this->showQuickAddUOM = false;
    }

    public function openQuickAddServiceType()
    {
        $this->showQuickAddServiceType = true;
    }

    public function closeQuickAddServiceType()
    {
        $this->showQuickAddServiceType = false;
    }

    // Handle events from quick add modals
    protected $listeners = [
        'client-created' => 'handleClientCreated',
        'uom-created' => 'handleUomCreated',
        'service-type-created' => 'handleServiceTypeCreated',
        'close-quick-add-client' => 'closeQuickAddClient',
        'close-quick-add-uom' => 'closeQuickAddUom',
        'close-quick-add-service-type' => 'closeQuickAddServiceType',
    ];

    public function handleClientCreated($data)
    {
        $this->client_id = $data['clientId'];
        $this->closeQuickAddClient();
    }

    public function handleUomCreated($data)
    {
        // Refresh UOMs list
        $this->closeQuickAddUom();
    }

    public function handleServiceTypeCreated($data)
    {
        $this->service_type_id = $data['serviceTypeId'];
        $this->closeQuickAddServiceType();
    }

    // ==========================================
    // Auto-save & Draft Management
    // ==========================================

    /**
     * Auto-save draft to session (every 30 seconds via JS)
     */
    public function autoSaveDraft()
    {
        if (!$this->autoSaveEnabled || $this->editMode) {
            return;
        }

        // Save to session
        session()->put('finance_ticket_draft', [
            'data' => [
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
            ],
            'timestamp' => now()->toISOString(),
        ]);

        $this->lastSaved = now()->format('H:i:s');
    }

    /**
     * Check if there's a draft in session on mount
     */
    protected function checkForDraft()
    {
        if ($this->editMode || session()->has('finance_ticket_draft')) {
            $draft = session('finance_ticket_draft');
            $this->dispatch('confirm-restore-draft', ['draft' => $draft]);
        }
    }

    // ==========================================
    // Computed Properties
    // ==========================================

    public function getClientsProperty()
    {
        return Client::where('department_id', $this->department_id)
            ->where('is_active', true)
            ->orderBy('client_name')
            ->get()
            ->map(
                fn($client) => [
                    'value' => $client->id,
                    'label' => $client->full_name,
                ],
            )
            ->toArray();
    }

    public function getCostCentersProperty()
    {
        return CostCenter::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(
                fn($cc) => [
                    'value' => $cc->id,
                    'label' => $cc->full_name,
                ],
            )
            ->toArray();
    }

    public function getServiceTypesProperty()
    {
        return ServiceType::where('department_id', $this->department_id)
            ->where('is_active', true)
            ->orderBy('service_type')
            ->get()
            ->map(
                fn($st) => [
                    'value' => $st->id,
                    'label' => $st->service_type,
                ],
            )
            ->toArray();
    }

    public function getUomsProperty()
    {
        return UOM::where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(
                fn($uom) => [
                    'value' => $uom->id,
                    'label' => $uom->code . ' - ' . $uom->name,
                ],
            )
            ->toArray();
    }

    /**
     * Get departments accessible by current user
     */
    public function getDepartmentsProperty()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            // Super admin can see all active departments
            return Department::where('is_active', true)->orderBy('department')->get();
        }

        // Regular users see only their assigned departments
        return $user->departments()->where('is_active', true)->orderBy('department')->get();
    }
    /**
     * Get currently selected department (single model)
     */
    public function getCurrentDepartmentProperty()
    {
        if (!$this->department_id) {
            return null;
        }

        return Department::find($this->department_id);
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
            'departments' => $this->departments, // This should be a collection now
            'progressPercentage' => $this->progressPercentage,
        ])->layout('admin.layout');
    }
}
