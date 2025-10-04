<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * FuelSale Model
 * 
 * Fuel-specific fields for fuel sale tickets
 * Links to TicketMaster via ticket_id (one-to-one)
 * 
 * @package App\Models
 */
class FuelSale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'vehicle_no',
        'vehicle_type',
        'driver_name',
        'meter_reading_before',
        'meter_reading_after',
        'meter_difference',
        'fuel_type',
        'quantity',
        'unit_price',
        'total_amount',
        'pump_no',
        'remarks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meter_reading_before' => 'decimal:2',
            'meter_reading_after' => 'decimal:2',
            'meter_difference' => 'decimal:2',
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Boot method to auto-calculate fields
     */
    protected static function boot()
    {
        parent::boot();

        // Calculate meter difference before saving
        static::saving(function ($fuelSale) {
            if ($fuelSale->meter_reading_before && $fuelSale->meter_reading_after) {
                $fuelSale->meter_difference = $fuelSale->meter_reading_after - $fuelSale->meter_reading_before;
            }

            // Calculate total amount
            $fuelSale->total_amount = $fuelSale->quantity * $fuelSale->unit_price;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the parent ticket
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketMaster::class, 'ticket_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Filter by vehicle
     */
    public function scopeByVehicle($query, string $vehicleNo)
    {
        return $query->where('vehicle_no', $vehicleNo);
    }

    /**
     * Scope: Filter by fuel type
     */
    public function scopeByFuelType($query, string $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    /**
     * Scope: Filter by driver
     */
    public function scopeByDriver($query, string $driverName)
    {
        return $query->where('driver_name', 'like', "%{$driverName}%");
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get fuel type label
     */
    public function getFuelTypeLabelAttribute(): string
    {
        return match($this->fuel_type) {
            'diesel' => 'Diesel',
            'petrol' => 'Petrol',
            'gas' => 'Gas',
            'other' => 'Other',
            default => ucfirst($this->fuel_type),
        };
    }

    /**
     * Get formatted quantity
     */
    public function getFormattedQuantityAttribute(): string
    {
        return number_format($this->quantity, 3) . ' L';
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total_amount, 2);
    }
}
