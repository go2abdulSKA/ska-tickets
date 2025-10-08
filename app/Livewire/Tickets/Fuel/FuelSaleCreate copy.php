<?php
// app/Livewire/Tickets/Fuel/FuelSaleCreate.php

namespace App\Livewire\Tickets\Fuel;

use App\Models\FuelSale;
use App\Models\Department;
use App\Models\Client;
use App\Models\CostCenter;
use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\TicketStatus;
use App\Services\TicketNumberService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FuelSaleCreate extends Component
{
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

    // Calculated fields (read-only in UI)
    public $subtotal = 0;
    public $vat_amount = 0;
    public $total_amount = 0;
    public $delivered_quantity = 0;

    // Dropdowns
    public $departments = [];
    public $clients = [];
    public $costCenters = [];
    public $clientTypes = [];
    public $currencies = [];
    public $productTypes = ['Diesel', 'Petrol', 'Gas'];
    public $vehicleTypes = ['LAND CRUISER', 'HILUX', 'BUS', 'TRUCK', 'VAN', 'SEDAN'];
    public $paymentTerms = ['SKA Asset', 'Cash', 'Credit'];

    protected $listeners = ['departmentChanged' => 'loadDepartmentData'];

    /**
     * Mount component
     */
    public function mount()
    {
        $this->ticket_date = now()->format('Y-m-d');

        // Load basic dropdowns without complex operations
        $this->loadDropdowns();

        // Simple initialization without errors
        if (Auth::check() && Auth::user()->department_id) {
            $this->department_id = Auth::user()->department_id;
            $this->loadDepartmentData();
        }
    }

    /**
     * Validation rules
     */
    protected function rules()
    {
        $rules = [
            'department_id' => 'required|exists:departments,id',
            'ticket_date' => 'required|date',
            'client_type' => 'required|in:client,cost_center',
            'fuel_site' => 'required|string|max:255',
            'product_type' => 'required|string|max:100',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_driver' => 'required|string|max:255',
            'vehicle_reg_no' => 'required|string|max:50',
            'meter_before' => 'required|numeric|min:0',
            'meter_after' => 'required|numeric|min:0|gte:meter_before',
            'sales_liter_price' => 'required|numeric|min:0',
            'liters_qty' => 'required|numeric|min:0.001',
            'currency' => 'required|in:usd,aed,euro,others',
            'vat_percentage' => 'required|numeric|min:0|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($this->client_type === 'client') {
            $rules['client_id'] = 'required|exists:clients,id';
        } else {
            $rules['cost_center_id'] = 'required|exists:cost_centers,id';
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    protected function messages()
    {
        return [
            'department_id.required' => 'Please select a department',
            'client_id.required' => 'Please select a client',
            'cost_center_id.required' => 'Please select a cost center',
            'meter_after.gte' => 'Meter reading after must be greater than or equal to meter reading before',
            'liters_qty.min' => 'Quantity must be greater than 0',
        ];
    }

    /**
     * Load dropdown data
     */
    public function loadDropdowns()
    {
        // Load departments based on user permissions
        try {
            if (Auth::check()) {
                $userRole = Auth::user()->role;

                // Check role using the enum values directly
                if ($userRole === \App\Enums\UserRole::SUPER_ADMIN || $userRole === \App\Enums\UserRole::ADMIN) {
                    $this->departments = Department::where('is_active', true)
                        ->orderBy('department')
                        ->get();
                } else {
                    if (Auth::user()->department_id) {
                        $this->departments = Department::where('id', Auth::user()->department_id)
                            ->where('is_active', true)
                            ->get();
                    } else {
                        $this->departments = collect();
                    }
                }
            }

            // Load enums
            $this->clientTypes = ClientType::options();
            $this->currencies = Currency::options();
        } catch (\Exception $e) {
            \Log::error('Error loading dropdowns: ' . $e->getMessage());
            $this->departments = collect();
            $this->clientTypes = [];
            $this->currencies = [];
        }
    }

    /**
     * Load department-specific data
     */
    public function loadDepartmentData()
    {
        try {
            if ($this->department_id) {
                $this->clients = Client::where('department_id', $this->department_id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get();

                $this->costCenters = CostCenter::where('is_active', true)
                    ->orderBy('name')
                    ->get();

                // Only generate preview if we have a valid department
                if ($dept = Department::find($this->department_id)) {
                    $lastTicket = FuelSale::where('department_id', $this->department_id)
                        ->orderBy('ticket_no', 'desc')
                        ->first();

                    $nextNumber = $lastTicket ? ($lastTicket->ticket_no + 1) : 1;
                    $this->preview_ticket_no = $dept->fuel_prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error loading department data: ' . $e->getMessage());
            $this->clients = collect();
            $this->costCenters = collect();
            $this->preview_ticket_no = '';
        }
    }

    /**
     * Watch for department changes
     */
    public function updatedDepartmentId()
    {
        $this->client_id = null;
        $this->cost_center_id = null;
        $this->loadDepartmentData();
    }

    /**
     * Watch for client type changes
     */
    public function updatedClientType()
    {
        $this->client_id = null;
        $this->cost_center_id = null;
    }

    /**
     * Calculate totals when inputs change
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['sales_liter_price', 'liters_qty', 'vat_percentage', 'meter_before', 'meter_after'])) {
            $this->calculateTotals();
        }
    }

    /**
     * Calculate all totals
     */
    public function calculateTotals()
    {
        // Calculate delivered quantity (meter difference)
        if ($this->meter_before && $this->meter_after && $this->meter_after >= $this->meter_before) {
            $this->delivered_quantity = $this->meter_after - $this->meter_before;
        } else {
            $this->delivered_quantity = 0;
        }

        // Calculate subtotal
        $this->subtotal = $this->liters_qty * $this->sales_liter_price;

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
        $this->validate();

        try {
            DB::beginTransaction();

            // Check if TicketNumberService exists
            if (class_exists('App\Services\TicketNumberService')) {
                $ticketNumberService = app(TicketNumberService::class);
                $department = Department::findOrFail($this->department_id);
                $ticketNumber = $ticketNumberService->generateTicketNumber($department, 'fuel');
            } else {
                // Fallback: Generate ticket number manually
                $department = Department::findOrFail($this->department_id);
                $lastTicket = FuelSale::where('department_id', $this->department_id)
                    ->lockForUpdate()
                    ->orderBy('ticket_no', 'desc')
                    ->first();

                $nextNumber = $lastTicket ? ($lastTicket->ticket_no + 1) : 1;
                $ticketNumber = [
                    'prefix' => $department->fuel_prefix,
                    'number' => $nextNumber
                ];
            }

            // Create fuel sale
            $fuelSale = FuelSale::create([
                'prefix' => $ticketNumber['prefix'],
                'ticket_no' => $ticketNumber['number'],
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

            DB::commit();

            session()->flash('success', 'Fuel sale ticket saved as draft successfully. Ticket No: ' . $fuelSale->prefix . $fuelSale->ticket_no);

            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save fuel sale: ' . $e->getMessage());
        }
    }

    /**
     * Save and post
     */
    public function saveAndPost()
    {
        // Check permission
        if (!Auth::user()->hasPermission('post-ticket')) {
            session()->flash('error', 'You do not have permission to post tickets.');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $ticketNumberService = app(TicketNumberService::class);
            $department = Department::findOrFail($this->department_id);

            // Generate actual ticket number
            $ticketNumber = $ticketNumberService->generateTicketNumber($department, 'fuel');

            // Create fuel sale
            $fuelSale = FuelSale::create([
                'prefix' => $ticketNumber['prefix'],
                'ticket_no' => $ticketNumber['number'],
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
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            session()->flash('success', 'Fuel sale ticket posted successfully. Ticket No: ' . $fuelSale->prefix . $fuelSale->ticket_no);

            return redirect()->route('fuel-sales.index');

        } catch (\Exception $e) {
            DB::rollBack();
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
     * Render component
     */
    public function render()
    {
        return view('livewire.tickets.fuel.fuel-sale-create')
            ->extends('admin.layout', ['pageTitle' => 'Create Fuel Sale Ticket']);
    }
}
