<?php
// app/Models/TicketTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TicketTransaction Model
 *
 * Represents line items for finance tickets
 * Multiple transactions per ticket
 *
 * File Location: app/Models/TicketTransaction.php
 */
class TicketTransaction extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ticket_transactions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ticket_id',
        'sr_no',
        'description',
        'qty',
        'uom_id',
        'unit_cost',
        'total_cost',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'qty' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method to auto-calculate total_cost.
     */
    protected static function boot()
    {
        parent::boot();

        // Calculate total_cost before saving
        static::saving(function ($transaction) {
            $transaction->total_cost = $transaction->qty * $transaction->unit_cost;
        });

        // Update ticket totals after save
        static::saved(function ($transaction) {
            $transaction->ticket->calculateTotals();
        });

        // Update ticket totals after delete
        static::deleted(function ($transaction) {
            $transaction->ticket->calculateTotals();
        });
    }

    /**
     * Get the parent ticket.
     *
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketMaster::class, 'ticket_id');
    }

    /**
     * Get the unit of measurement.
     *
     * @return BelongsTo
     */
    public function uom(): BelongsTo
    {
        return $this->belongsTo(UOM::class);
    }

    /**
     * Get the user who created this transaction.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this transaction.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculate total cost (qty × unit_cost).
     *
     * @return float
     */
    public function calculateTotal(): float
    {
        return $this->qty * $this->unit_cost;
    }

    /**
     * Get formatted unit cost with currency.
     *
     * @return string
     */
    public function getFormattedUnitCostAttribute(): string
    {
        return $this->ticket->currency->format($this->unit_cost);
    }

    /**
     * Get formatted total cost with currency.
     *
     * @return string
     */
    public function getFormattedTotalCostAttribute(): string
    {
        return $this->ticket->currency->format($this->total_cost);
    }
}
