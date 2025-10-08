<?php
// app/Livewire/Tickets/Fuel/FuelSaleEdit.php

namespace App\Livewire\Tickets\Fuel;

use App\Models\FuelSale;
use App\Models\Department;
use App\Models\Client;
use App\Models\CostCenter;
use App\Models\TicketAttachment;
use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\TicketStatus;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FuelSaleEdit extends Component
{
    use WithFileUploads;

    public $fuelSaleId;
    public $fuelSale;

    // Ticket Information
    public $ticket_no_display = '';
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
    public $existingAttachments = [];

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
     * Mount component
     */
    public function mount($id)
    {
        $this->fuelSaleId = $id;
        $this->fuelSale = FuelSale::with(['attachments', 'client', 'costCenter'])->findOrFail($id);

        // Check permissions
        $user = Auth::user();
        
        // Only allow editing DRAFT tickets
        if ($this->fuelSale->status !== TicketStatus::DRAFT) {
            abort(403, 'Only draft tickets can be edited.');
        }

        // Super admin can edit any draft ticket
        if (!$user->role->isSuperAdmin()) {
            // Regular users must be in Fuel Services department
            $userDepartment = $user->department;
            
            if (!$userDepartment || $userDepartment->department !== 'Fuel Services') {
                abort(403, 'Only Fuel Services department can edit fuel tickets.');
            }

            // Users can only edit their own tickets
            if ($this->fuelSale->created_by !== $user->id) {
                abort(403, 'You can only edit your own tickets.');
            }
        }

        // Load data from existing ticket
        $this->loadTicketData();
        $this->loadDropdowns();
        $this->loadDepartmentData();
    }

    /**
     * Load ticket data into form
     */
    public function loadTicketData()
    {
        $this->ticket_no_display = $this->fuelSale->prefix . str_pad($this->fuelSale->ticket_no, 6, '0', STR_PAD_LEFT);
        $this->ticket_date = $this->fuelSale->ticket_date;
        $this->department_id = $this->fuelSale->department_id;
        $this->client_type = $this->fuelSale->client_type->value;
        $this->client_id = $this->fuelSale->client_id;
        $this->cost_center_id = $this->fuelSale->cost_center_id;
        $this->fuel_site = $this->fuelSale->fuel_site;
        $this->product_type = $this->fuelSale->product_type;
        $this->vehicle_type = $this->fuelSale->vehicle_type;
        $this->vehicle_driver = $this->fuelSale->vehicle_driver;
        $this->vehicle_reg_no = $this->fuelSale->vehicle_reg_no;
        $this->meter_before = $this->fuelSale->meter_before;
        $this->meter_after = $this->fuelSale->meter_after;
        $this->sales_liter_price = $this->fuelSale->sales_liter_price;
        $this->liters_qty = $this->fuelSale->liters_qty;
        $this->currency = $this->fuelSale->currency->value;
        $this->vat_percentage = $this->fuelSale->vat_percentage;
        $this->payment_terms = $this->fuelSale->payment_terms;
        $this->notes = $this->fuelSale->notes;
        
        // Calculated fields
        $this->subtotal = $this->fuelSale->subtotal;
        $this->vat_amount = $this->fuelSale->vat_amount;
        $this->total_amount = $this->fuelSale->total_amount;
        $this->delivered_quantity = $this->meter_after - $this->meter_before;

        // Load existing attachments
        $this->existingAttachments = $this->fuelSale->attachments;

        // Load selected client if applicable
        if ($this->client_id) {
            $this->selectedClient = Client::find($this->client_id);
        }
    }

    /**
     * Load dropdown data
     */
    public function loadDropdowns()
    {
        $user = Auth::user();
        
        if ($user->role->isSuperAdmin()) {
            $this->departments = Department::where('department', 'Fuel Services')
                ->where('is_active', true)
                ->get();
        } else {
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
            $this->clients = Client::where('department_id', $this->department_id)
                ->where('is_active', true)
                ->orderBy('client_name')
                ->get();

            $this->costCenters = CostCenter::where('is_active', true)
                ->orderBy('code')
                ->get();
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
     * Updated client ID
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
     * Calculate totals - called on blur events
     */
    public function calculateTotals()
    {
        $this->subtotal = $this->liters_qty * $this->sales_liter_price;
        $this->vat_amount = ($this->subtotal * $this->vat_percentage) / 100;
        $this->total_amount = $this->subtotal + $this->vat_amount;
    }

    /**
     * Calculate delivered quantity
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
     * Delete existing attachment
     */
    public function deleteExistingAttachment($attachmentId)
    {
        try {
            $attachment = TicketAttachment::findOrFail($attachmentId);
            
            // Delete file from storage
            if (Storage::exists($attachment->file_path)) {
                Storage::delete($attachment->file_path);
            }
            
            // Delete record
            $attachment->delete();
            
            // Reload attachments
            $this->existingAttachments = $this->fuelSale->attachments()->get();
            
            session()->flash('success', 'Attachment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting attachment: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete attachment.');
        }
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
     * Update ticket
     */
    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->fuelSale->update([
                'ticket_date' => $this->ticket_date,
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
                'payment_terms' => $this->payment_terms,
                'notes' => $this->notes,
                'updated_by' => Auth::id(),
            ]);

            // Handle new attachments
            $this->handleAttachments($this->fuelSale->id);

            DB::commit();

            session()->flash('success', 'Fuel sale ticket updated successfully.');

            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to update fuel sale: ' . $e->getMessage());
        }
    }

    /**
     * Update and post
     */
    public function updateAndPost()
    {
        $user = Auth::user();
        
        if (!$user->role->isAdmin() && !$user->role->isSuperAdmin()) {
            session()->flash('error', 'Only Admin and Super Admin can post fuel tickets.');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $this->fuelSale->update([
                'ticket_date' => $this->ticket_date,
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
                'payment_terms' => $this->payment_terms,
                'notes' => $this->notes,
                'status' => TicketStatus::POSTED,
                'posted_date' => now(),
                'posted_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->handleAttachments($this->fuelSale->id);

            DB::commit();

            session()->flash('success', 'Fuel sale ticket updated and posted successfully.');

            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating and posting fuel sale: ' . $e->getMessage());
            session()->flash('error', 'Failed to update and post fuel sale: ' . $e->getMessage());
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
     * Edit client
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

            $this->loadDepartmentData();
            $this->client_id = $client->id;
            $this->selectedClient = $client;

            session()->flash('success', $message);

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
        return view('livewire.tickets.fuel.fuel-sale-edit')
            ->extends('admin.layout', ['pageTitle' => 'Edit Fuel Sale Ticket']);
    }
}
