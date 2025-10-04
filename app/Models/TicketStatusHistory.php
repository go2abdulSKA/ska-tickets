<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TicketDescriptionLibrary Model
 *
 * Smart library of previously used line item descriptions
 * Powers the searchable dropdown with auto-complete
 * Tracks usage frequency and average costs for better suggestions
 *
 * @package App\Models
 */
class TicketDescriptionLibrary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id',
        'description',
        'usage_count',
        'avg_unit_cost',
        'last_uom_id',
        'last_used_at',
        'category',
        'is_template',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'usage_count' => 'integer',
            'avg_unit_cost' => 'decimal:2',
            'is_template' => 'boolean',
            'last_used_at' => 'datetime',
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
     * Get the department that owns this description
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the last used UOM
     */
    public function lastUom(): BelongsTo
    {
        return $this->belongsTo(UOM::class, 'last_uom_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Filter by department
     */
    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope: Search by description
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('description', 'like', "%{$search}%");
    }

    /**
     * Scope: Most frequently used
     */
    public function scopeMostUsed($query, int $limit = 10)
    {
        return $query->orderByDesc('usage_count')
            ->orderByDesc('last_used_at')
            ->limit($limit);
    }

    /**
     * Scope: Recently used
     */
    public function scopeRecentlyUsed($query, int $days = 30)
    {
        return $query->where('last_used_at', '>=', now()->subDays($days))
            ->orderByDesc('last_used_at');
    }

    /**
     * Scope: Templates only
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope: By category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Increment usage count and update tracking
     */
    public function incrementUsage(float $unitCost, int $uomId): void
    {
        // Calculate new average cost
        $totalCost = ($this->avg_unit_cost ?? 0) * $this->usage_count;
        $newTotalCost = $totalCost + $unitCost;
        $newUsageCount = $this->usage_count + 1;
        $newAvgCost = $newTotalCost / $newUsageCount;

        $this->update([
            'usage_count' => $newUsageCount,
            'avg_unit_cost' => $newAvgCost,
            'last_uom_id' => $uomId,
            'last_used_at' => now(),
        ]);
    }

    /**
     * Mark as template
     */
    public function markAsTemplate(): void
    {
        $this->update(['is_template' => true]);
    }

    /**
     * Get formatted average cost
     */
    public function getFormattedAvgCostAttribute(): string
    {
        if (!$this->avg_unit_cost) {
            return 'N/A';
        }
        return '$' . number_format($this->avg_unit_cost, 2);
    }
}
