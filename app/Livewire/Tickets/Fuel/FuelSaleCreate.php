<?php
// app/Livewire/Tickets/Fuel/FuelSaleCreate.php

namespace App\Livewire\Tickets\Fuel;

use App\Models\FuelSale;
use App\Models\Department;
use App\Models\Client;
use App\Models\CostCenter;
use App\Models\TicketAttachment;
use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\TicketStatus;
use App\Services\TicketNumberService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FuelSaleCreate extends Component
{
    use WithFileUploads;

    // Ticket Information
    public $preview_ticket_no = '';
    public $ticket_date;
    public $department_id;
    public $client_type = 'cost_center';
    public $client_id;
    public $cost_center_id;

    // Fuel Site & Product
    public $fuel_site = '';
    public $product_type = 'Diesel';

    // Vehicle Information
    public $vehicle_type = '';
    public $vehicle_driver = '';
    public $vehicle_reg_no = '';

    // Meter Readings
    public $meter_before;
    public $meter_after;

    // Sales Information
    public $sales_liter_price = 1.00;
    public $liters_qty = 0;

    // Financial
    public $currency = 'usd';
    public $vat_percentage = 0.00;
    public $payment_terms = '';

    // Notes
    public $notes = '';

    // Calculated fields
    public $subtotal = 0;
    public $vat_amount = 0;
    public $total_amount = 0;
    public $delivered_quantity = 0;

    // Attachments
    public $attachments = [];

    // Client Modal Properties
    public $editingClientId = null;
    public $clientName = '';
    public $clientCompanyName = '';
    public $clientEmail = '';
    public $clientPhone = '';
    public $clientAddress = '';
    public $clientIsActive = true;
    public $selectedClient = null;

    // Dropdowns
    public $departments = [];
    public $clients = [];
    public $costCenters = [];
    public $clientTypes = [];
    public $currencies = [];
    public $productTypes = ['Diesel', 'Petrol', 'Gas'];
    public $vehicleTypes = ['LAND CRUISER', 'HILUX', 'BUS', 'COASTER', 'PICKUP', 'SEDAN', 'VAN'];

    /**
     * Mount component - Check if user can create fuel tickets
     */
    public function mount()
    {
        $user = Auth::user();

        // Super admin can access everything
        // if (!$user->role->isSuperAdmin()) {
        //     // Regular users must be in Fuel Services department
        //     $userDepartment = $user->department;

        //     if (!$userDepartment || $userDepartment->department !== 'Fuel Services') {
        //         abort(403, 'Only Fuel Services department can create fuel tickets.');
        //     }
        // }

        // Initialize form with default values
        $this->ticket_date = now()->format('Y-m-d');

        // If user is in Fuel Services, auto-set department
        if ($user->department && $user->department->department === 'Fuel Services') {
            $this->department_id = $user->department_id;
        } else if ($user->role->isSuperAdmin()) {
            // For super admin, set to Fuel Services by default
            $fuelServicesDept = Department::where('department', 'Fuel Services')
                ->where('is_active', true)
                ->first();
            if ($fuelServicesDept) {
                $this->department_id = $fuelServicesDept->id;
            }
        }

        $this->loadDropdowns();

        if ($this->department_id) {
            $this->generatePreviewTicketNumber();
            $this->loadDepartmentData();
        }
    }

    /**
     * Load dropdown data
     */
    public function loadDropdowns()
    {
        $user = Auth::user();

        if ($user->role->isSuperAdmin()) {
            // Super admin can see Fuel Services department
            $this->departments = Department::where('department', 'Fuel Services')
                ->where('is_active', true)
                ->get();
        } else {
            // Regular users only see their department (which is Fuel Services)
            $this->departments = Department::where('id', $user->department_id)
                ->where('is_active', true)
                ->get();
        }

        $this->clientTypes = ClientType::options();
        $this->currencies = Currency::options();
    }

    /**
     * Load department-specific data
     */
    public function loadDepartmentData()
    {
        if (!$this->department_id) {
            $this->clients = [];
            $this->costCenters = [];
            return;
        }

        try {
            // Load Clients - filtered by department
            $this->clients = Client::where('department_id', $this->department_id)
                ->where('is_active', true)
                ->orderBy('client_name')
                ->get();

            // Load Cost Centers - NOT filtered by department (all active cost centers)
            $this->costCenters = CostCenter::where('is_active', true)
                ->orderBy('code')
                ->get();

            Log::info('Loaded department data', [
                'department_id' => $this->department_id,
                'clients_count' => $this->clients->count(),
                'cost_centers_count' => $this->costCenters->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading department data: ' . $e->getMessage());
            $this->clients = [];
            $this->costCenters = [];
        }
    }

    /**
     * Updated department ID
     */
    public function updatedDepartmentId()
    {
        $this->generatePreviewTicketNumber();
        $this->loadDepartmentData();
    }

    /**
     * Updated client type
     */
    public function updatedClientType()
    {
        $this->client_id = null;
        $this->cost_center_id = null;
        $this->selectedClient = null;
    }

    /**
     * Updated client ID - Load client details
     */
    public function updatedClientId($value)
    {
        if ($value) {
            $this->selectedClient = Client::find($value);
        } else {
            $this->selectedClient = null;
        }
    }

    /**
     * Generate preview ticket number
     */
    public function generatePreviewTicketNumber()
    {
        if (!$this->department_id) {
            $this->preview_ticket_no = '';
            return;
        }

        $department = Department::find($this->department_id);
        if ($department) {
            $lastTicket = FuelSale::where('department_id', $this->department_id)
                ->orderBy('ticket_no', 'desc')
                ->first();

            $nextNumber = $lastTicket ? ($lastTicket->ticket_no + 1) : 1;
            $this->preview_ticket_no = $department->prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Calculate totals - called on blur events
     */
    public function calculateTotals()
    {
        // Calculate subtotal
        $this->subtotal = $this->liters_qty * $this->sales_liter_price;

        // Calculate VAT
        $this->vat_amount = ($this->subtotal * $this->vat_percentage) / 100;

        // Calculate total
        $this->total_amount = $this->subtotal + $this->vat_amount;
    }

    /**
     * Calculate delivered quantity - called on blur events
     */
    public function calculateDeliveredQty()
    {
        if ($this->meter_before && $this->meter_after) {
            $this->delivered_quantity = $this->meter_after - $this->meter_before;
        }
    }

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
            'fuel_site' => 'required|string|max:255',
            'product_type' => 'required|in:Diesel,Petrol,Gas',
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_driver' => 'nullable|string|max:255',
            'vehicle_reg_no' => 'required|string|max:50',
            'meter_before' => 'required|numeric|min:0',
            'meter_after' => 'required|numeric|min:0|gte:meter_before',
            'sales_liter_price' => 'required|numeric|min:0',
            'liters_qty' => 'required|numeric|min:0.001',
            'currency' => 'required|in:usd,aed,euro,other',
            'vat_percentage' => 'required|numeric|min:0|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'department_id.required' => 'Department is required.',
            'department_id.exists' => 'Selected department is invalid.',
            'ticket_date.required' => 'Ticket date is required.',
            'ticket_date.date' => 'Please enter a valid date.',
            'client_type.required' => 'Please select a client type.',
            'client_type.in' => 'Client type must be either Client or Cost Center.',
            'client_id.required_if' => 'Client is required when client type is Client.',
            'client_id.exists' => 'Selected client is invalid.',
            'cost_center_id.required_if' => 'Cost Center is required when client type is Cost Center.',
            'cost_center_id.exists' => 'Selected cost center is invalid.',
            'fuel_site.required' => 'Fuel site is required.',
            'fuel_site.max' => 'Fuel site cannot exceed 255 characters.',
            'product_type.required' => 'Product type is required.',
            'product_type.in' => 'Product type must be Diesel, Petrol, or Gas.',
            'vehicle_type.max' => 'Vehicle type cannot exceed 255 characters.',
            'vehicle_driver.max' => 'Driver name cannot exceed 255 characters.',
            'vehicle_reg_no.required' => 'Vehicle registration number is required.',
            'vehicle_reg_no.max' => 'Vehicle registration number cannot exceed 50 characters.',
            'meter_before.required' => 'Meter before reading is required.',
            'meter_before.numeric' => 'Meter before must be a number.',
            'meter_before.min' => 'Meter before cannot be negative.',
            'meter_after.required' => 'Meter after reading is required.',
            'meter_after.numeric' => 'Meter after must be a number.',
            'meter_after.min' => 'Meter after cannot be negative.',
            'meter_after.gte' => 'Meter after must be greater than or equal to meter before.',
            'sales_liter_price.required' => 'Sales liter price is required.',
            'sales_liter_price.numeric' => 'Sales liter price must be a number.',
            'sales_liter_price.min' => 'Sales liter price cannot be negative.',
            'liters_qty.required' => 'Quantity is required.',
            'liters_qty.numeric' => 'Quantity must be a number.',
            'liters_qty.min' => 'Quantity must be at least 0.001.',
            'currency.required' => 'Currency is required.',
            'currency.in' => 'Selected currency is invalid.',
            'vat_percentage.required' => 'VAT percentage is required.',
            'vat_percentage.numeric' => 'VAT percentage must be a number.',
            'vat_percentage.min' => 'VAT percentage cannot be negative.',
            'vat_percentage.max' => 'VAT percentage cannot exceed 100.',
            'payment_terms.max' => 'Payment terms cannot exceed 255 characters.',
            'attachments.*.max' => 'Each file must not exceed 10MB.',
            'attachments.*.mimes' => 'Only PDF, JPG, PNG, DOC, DOCX, XLS, XLSX files are allowed.',
        ];
    }

    /**
     * Remove attachment from upload queue
     */
    public function removeAttachment($index)
    {
        array_splice($this->attachments, $index, 1);
    }

    /**
     * Handle file uploads and save to database
     */
    protected function handleAttachments($fuelSaleId)
    {
        if (empty($this->attachments)) {
            return;
        }

        foreach ($this->attachments as $file) {
            try {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $storedName = time() . '_' . uniqid() . '.' . $extension;

                $yearMonth = now()->format('Y/m');
                $filePath = $file->storeAs(
                    "attachments/{$yearMonth}",
                    $storedName
                );

                TicketAttachment::create([
                    'ticket_id' => $fuelSaleId,
                    'original_name' => $originalName,
                    'stored_name' => $storedName,
                    'file_path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'file_type' => $extension,
                    'uploaded_by' => Auth::id(),
                ]);

            } catch (\Exception $e) {
                Log::error('Error uploading attachment: ' . $e->getMessage());
            }
        }
    }

    /**
     * Save as draft
     */
    public function saveDraft()
    {
        // Log the attempt
        Log::info('Save Draft button clicked');
        Log::info('Form data:', [
            'department_id' => $this->department_id,
            'ticket_date' => $this->ticket_date,
            'client_type' => $this->client_type,
            'client_id' => $this->client_id,
            'cost_center_id' => $this->cost_center_id,
            'fuel_site' => $this->fuel_site,
            'vehicle_reg_no' => $this->vehicle_reg_no,
            'meter_before' => $this->meter_before,
            'meter_after' => $this->meter_after,
            'liters_qty' => $this->liters_qty,
            'sales_liter_price' => $this->sales_liter_price,
        ]);

        // Validate form
        try {
            $this->validate();
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            throw $e;
        }

        try {
            DB::beginTransaction();
            Log::info('Transaction started');

            $department = Department::findOrFail($this->department_id);
            Log::info('Department found:', ['id' => $department->id, 'prefix' => $department->prefix]);

            // Get the last ticket number for this department
            $lastTicket = FuelSale::where('department_id', $this->department_id)
                ->lockForUpdate()
                ->orderBy('ticket_no', 'desc')
                ->first();

            $nextNumber = $lastTicket ? ($lastTicket->ticket_no + 1) : 1;
            Log::info('Next ticket number:', ['number' => $nextNumber]);

            // Create fuel sale
            $fuelSale = FuelSale::create([
                'prefix' => $department->prefix,
                'ticket_no' => $nextNumber,
                'ticket_date' => $this->ticket_date,
                'department_id' => $this->department_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'host_name' => request()->getHost(),
                'client_type' => ClientType::from($this->client_type),
                'client_id' => $this->client_type === 'client' ? $this->client_id : null,
                'cost_center_id' => $this->client_type === 'cost_center' ? $this->cost_center_id : null,
                'fuel_site' => $this->fuel_site,
                'product_type' => $this->product_type,
                'vehicle_type' => $this->vehicle_type,
                'vehicle_driver' => $this->vehicle_driver,
                'vehicle_reg_no' => $this->vehicle_reg_no,
                'meter_before' => $this->meter_before,
                'meter_after' => $this->meter_after,
                'sales_liter_price' => $this->sales_liter_price,
                'liters_qty' => $this->liters_qty,
                'currency' => Currency::from($this->currency),
                'subtotal' => $this->subtotal,
                'vat_percentage' => $this->vat_percentage,
                'vat_amount' => $this->vat_amount,
                'total_amount' => $this->total_amount,
                'date_sold' => $this->ticket_date,
                'payment_terms' => $this->payment_terms,
                'notes' => $this->notes,
                'status' => TicketStatus::DRAFT,
                'created_by' => Auth::id(),
            ]);

            Log::info('Fuel sale created:', ['id' => $fuelSale->id]);

            // Handle attachments
            $this->handleAttachments($fuelSale->id);
            Log::info('Attachments handled');

            DB::commit();
            Log::info('Transaction committed');

            $ticketNo = $fuelSale->prefix . str_pad($fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);
            session()->flash('success', "Fuel sale ticket saved as draft successfully. Ticket No: {$ticketNo}");

            Log::info('Redirecting to index');
            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving fuel sale draft: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            session()->flash('error', 'Failed to save fuel sale: ' . $e->getMessage());
        }
    }

    /**
     * Save and post - Only for Admin & Super Admin
     */
    public function saveAndPost()
    {
        $user = Auth::user();

        // Check if user is Admin or Super Admin
        if (!$user->role->isAdmin() && !$user->role->isSuperAdmin()) {
            session()->flash('error', 'Only Admin and Super Admin can post fuel tickets.');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $department = Department::findOrFail($this->department_id);
            $lastTicket = FuelSale::where('department_id', $this->department_id)
                ->lockForUpdate()
                ->orderBy('ticket_no', 'desc')
                ->first();

            $nextNumber = $lastTicket ? ($lastTicket->ticket_no + 1) : 1;

            $fuelSale = FuelSale::create([
                'prefix' => $department->prefix,
                'ticket_no' => $nextNumber,
                'ticket_date' => $this->ticket_date,
                'department_id' => $this->department_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'host_name' => request()->getHost(),
                'client_type' => ClientType::from($this->client_type),
                'client_id' => $this->client_type === 'client' ? $this->client_id : null,
                'cost_center_id' => $this->client_type === 'cost_center' ? $this->cost_center_id : null,
                'fuel_site' => $this->fuel_site,
                'product_type' => $this->product_type,
                'vehicle_type' => $this->vehicle_type,
                'vehicle_driver' => $this->vehicle_driver,
                'vehicle_reg_no' => $this->vehicle_reg_no,
                'meter_before' => $this->meter_before,
                'meter_after' => $this->meter_after,
                'sales_liter_price' => $this->sales_liter_price,
                'liters_qty' => $this->liters_qty,
                'currency' => Currency::from($this->currency),
                'subtotal' => $this->subtotal,
                'vat_percentage' => $this->vat_percentage,
                'vat_amount' => $this->vat_amount,
                'total_amount' => $this->total_amount,
                'date_sold' => $this->ticket_date,
                'payment_terms' => $this->payment_terms,
                'notes' => $this->notes,
                'status' => TicketStatus::POSTED,
                'posted_date' => now(),
                'posted_by' => Auth::id(),
                'created_by' => Auth::id(),
            ]);

            $this->handleAttachments($fuelSale->id);

            DB::commit();

            session()->flash('success', 'Fuel sale ticket posted successfully. Ticket No: ' . $fuelSale->prefix . str_pad($fuelSale->ticket_no, 6, '0', STR_PAD_LEFT));

            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error posting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to post fuel sale: ' . $e->getMessage());
        }
    }

    /**
     * Cancel and return to list
     */
    public function cancel()
    {
        return redirect()->route('fuel-sales.index');
    }

    /**
     * Reset client modal
     */
    public function resetClientModal()
    {
        $this->editingClientId = null;
        $this->clientName = '';
        $this->clientCompanyName = '';
        $this->clientEmail = '';
        $this->clientPhone = '';
        $this->clientAddress = '';
        $this->clientIsActive = true;
        $this->resetErrorBag(['clientName', 'clientEmail']);
    }

    /**
     * Edit client - Load client data into modal
     */
    public function editClient($clientId)
    {
        $client = Client::findOrFail($clientId);

        $this->editingClientId = $client->id;
        $this->clientName = $client->client_name;
        $this->clientCompanyName = $client->company_name;
        $this->clientEmail = $client->email;
        $this->clientPhone = $client->phone;
        $this->clientAddress = $client->address;
        $this->clientIsActive = $client->is_active;
    }

    /**
     * Save client
     */
    public function saveClient()
    {
        $this->validate([
            'clientName' => 'required|string|max:255',
            'clientEmail' => 'nullable|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            if ($this->editingClientId) {
                // Update existing client
                $client = Client::findOrFail($this->editingClientId);
                $client->update([
                    'client_name' => $this->clientName,
                    'company_name' => $this->clientCompanyName,
                    'email' => $this->clientEmail,
                    'phone' => $this->clientPhone,
                    'address' => $this->clientAddress,
                    'is_active' => $this->clientIsActive,
                    'updated_by' => Auth::id(),
                ]);

                $message = 'Client updated successfully.';
            } else {
                // Create new client
                $client = Client::create([
                    'department_id' => $this->department_id,
                    'client_name' => $this->clientName,
                    'company_name' => $this->clientCompanyName,
                    'email' => $this->clientEmail,
                    'phone' => $this->clientPhone,
                    'address' => $this->clientAddress,
                    'is_active' => $this->clientIsActive,
                    'created_by' => Auth::id(),
                ]);

                $message = 'Client added successfully.';
            }

            DB::commit();

            // Reload clients
            $this->loadDepartmentData();

            // Set the newly created/updated client as selected
            $this->client_id = $client->id;
            $this->selectedClient = $client;

            // Show success message
            session()->flash('success', $message);

            // Dispatch events to close modal and update Select2
            $this->dispatch('closeClientModal');
            $this->dispatch('clientUpdated');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving client: ' . $e->getMessage());
            session()->flash('error', 'Failed to save client: ' . $e->getMessage());
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.tickets.fuel.fuel-sale-create')
            ->extends('admin.layout', ['pageTitle' => 'Create Fuel Sale Ticket']);
    }
}
