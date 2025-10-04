<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TicketNumber Model
 *
 * CRITICAL: Maintains sequential ticket numbering per department
 * Ensures no gaps in numbering even with concurrent users
 *
 * Usage: See TicketNumberService for proper implementation with locking
 *
 * @package App\Models
 */
class TicketNumber extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id',
        'last_used',
        'last_user',
        'is_adding',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_used' => 'integer',
            'is_adding' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the department that owns this ticket number counter
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Generate next ticket number for a department
     *
     * IMPORTANT: This method should be called within a database transaction
     * with lockForUpdate() to prevent race conditions
     *
     * Example usage:
     * DB::transaction(function() use ($departmentId, $prefix) {
     *     $ticketNumber = TicketNumber::where('department_id', $departmentId)
     *         ->lockForUpdate()
     *         ->first();
     *     return $ticketNumber->getNextNumber($prefix);
     * });
     *
     * @param string $prefix Department prefix
     * @return string Full ticket number (e.g., C/A-00001)
     */
    public function getNextNumber(string $prefix): string
    {
        // Increment the counter
        $this->last_used++;
        $this->last_user = auth()->user()?->name ?? 'System';
        $this->save();

        // Format: PREFIX-00001
        return $prefix . '-' . str_pad($this->last_used, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get current ticket number without incrementing
     *
     * @param string $prefix
     * @return string
     */
    public function getCurrentNumber(string $prefix): string
    {
        return $prefix . '-' . str_pad($this->last_used, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get next number preview (without saving)
     *
     * @param string $prefix
     * @return string
     */
    public function getNextNumberPreview(string $prefix): string
    {
        $nextNumber = $this->last_used + 1;
        return $prefix . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Reset counter to a specific number
     * Use with caution - for admin purposes only!
     *
     * @param int $number
     * @return bool
     */
    public function resetTo(int $number): bool
    {
        $this->last_used = $number;
        return $this->save();
    }
}
