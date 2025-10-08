<?php

namespace App\Models;

use App\Enums\ClientType;
use App\Enums\Currency;
use App\Enums\PaymentType;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * TicketMaster Model
 *
 * Main ticket header model for all three ticket types:
 * - Finance Tickets
 * - Delivery Notes
 * - Fuel Sales
 *
 * Related line items are in TicketTransaction model
 *
 * @package App\Models
 */
class TicketMaster extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefix',
        'ticket_no',
        'ticket_type',
        'ticket_date',
        'department_id',
        'user_id',
        'user_name',
        'host_name',
        'client_type',
        'client_id',
        'cost_center_id',
        'project_code',
        'contract_no',
        'service_location',
        'service_type_id',
        'ref_no',
        'payment_terms',
        'payment_type',
        'currency',
        'subtotal',
        'vat_percentage',
        'vat_amount',
        'total_amount',
        'remarks',
        'status',
        'posted_date',
        'inv_ref',
        'sage_inv_date',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ticket_type' => TicketType::class,
            'ticket_date' => 'date',
            'client_type' => ClientType::class,
            'payment_type' => PaymentType::class,
            'currency' => Currency::class,
            'subtotal' => 'decimal:2',
            'vat_percentage' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'status' => TicketStatus::class,
            'posted_date' => 'datetime',
            'sage_inv_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the department that owns the ticket
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created the ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client (if client_type is 'client')
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the cost center (if client_type is 'cost_center')
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Get the service type
     */
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get all line items (transactions) for this ticket
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(TicketTransaction::class, 'ticket_id')->orderBy('sr_no');
    }

    /**
     * Get all attachments for this ticket
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    /**
     * Get status history for this ticket
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class, 'ticket_id')->orderBy('changed_at', 'desc');
    }

    /**
     * Get fuel sale data (for fuel sale tickets only)
     */
    public function fuelSale(): HasOne
    {
        return $this->hasOne(FuelSale::class, 'ticket_id');
    }

    /**
     * Get the user who created this ticket
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this ticket
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Filter by ticket type
     */
    public function scopeByType($query, TicketType|string $type)
    {
        if ($type instanceof TicketType) {
            return $query->where('ticket_type', $type->value);
        }
        return $query->where('ticket_type', $type);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, TicketStatus|string $status)
    {
        if ($status instanceof TicketStatus) {
            return $query->where('status', $status->value);
        }
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by department
     */
    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('ticket_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Search by ticket number or reference
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('ticket_no', 'like', "%{$search}%")
              ->orWhere('ref_no', 'like', "%{$search}%")
              ->orWhere('project_code', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Finance tickets only
     */
    public function scopeFinanceTickets($query)
    {
        return $query->where('ticket_type', TicketType::FINANCE->value);
    }

    /**
     * Scope: Delivery notes only
     */
    public function scopeDeliveryNotes($query)
    {
        return $query->where('ticket_type', TicketType::DELIVERY_NOTE->value);
    }

    /**
     * Scope: Fuel sales only
     */
    public function scopeFuelSales($query)
    {
        return $query->where('ticket_type', TicketType::FUEL_SALE->value);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the customer (client or cost center dynamically)
     */
    public function getCustomerAttribute()
    {
        if ($this->client_type === ClientType::CLIENT) {
            return $this->client;
        }
        return $this->costCenter;
    }

    /**
     * Get customer name
     */
    public function getCustomerNameAttribute(): string
    {
        if ($this->client_type === ClientType::CLIENT) {
            return $this->client?->full_name ?? 'N/A';
        }
        return $this->costCenter?->full_name ?? 'N/A';
    }

    /**
     * Get formatted total with currency
     */
    public function getFormattedTotalAttribute(): string
    {
        return $this->currency->format($this->total_amount);
    }

    /**
     * Get formatted subtotal with currency
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return $this->currency->format($this->subtotal);
    }

    /**
     * Get formatted VAT with currency
     */
    public function getFormattedVatAttribute(): string
    {
        return $this->currency->format($this->vat_amount);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate and update totals based on transactions
     */
    public function calculateTotals(): void
    {
        // Sum all transaction total costs
        $this->subtotal = $this->transactions()->sum('total_cost');

        // Calculate VAT
        $this->vat_amount = ($this->subtotal * $this->vat_percentage) / 100;

        // Calculate grand total
        $this->total_amount = $this->subtotal + $this->vat_amount;

        $this->save();
    }

    /**
     * Check if ticket can be edited
     */
    public function canEdit(): bool
    {
        return $this->status->canEdit();
    }

    /**
     * Check if ticket can be deleted
     */
    public function canDelete(): bool
    {
        return $this->status->canDelete();
    }

    /**
     * Check if ticket can be posted
     */
    public function canPost(): bool
    {
        return $this->status->canPost();
    }

    /**
     * Check if ticket can be unposted
     */
    public function canUnpost(): bool
    {
        return $this->status->canUnpost();
    }

    /**
     * Check if ticket can be cancelled
     */
    public function canCancel(): bool
    {
        return $this->status->canCancel();
    }

    /**
     * Check if this is a finance ticket
     */
    public function isFinanceTicket(): bool
    {
        return $this->ticket_type === TicketType::FINANCE;
    }

    /**
     * Check if this is a delivery note
     */
    public function isDeliveryNote(): bool
    {
        return $this->ticket_type === TicketType::DELIVERY_NOTE;
    }

    /**
     * Check if this is a fuel sale
     */
    public function isFuelSale(): bool
    {
        return $this->ticket_type === TicketType::FUEL_SALE;
    }
}
