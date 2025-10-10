<?php
// app/Models/TicketStatusHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatusHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'ticket_status_history';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ticket_id',
        'from_status',
        'to_status',
        'notes',
        'changed_by',
        'ip_address',
        'user_agent',
        'changed_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * Ticket that this history belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketMaster::class, 'ticket_id');
    }

    /**
     * User who made the status change
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // ==========================================
    // Accessors
    // ==========================================

    /**
     * Get formatted status badge for from status
     */
    public function getFromStatusBadgeAttribute()
    {
        if (!$this->from_status) {
            return '<span class="badge badge-soft-secondary">New</span>';
        }

        try {
            $status = \App\Enums\TicketStatus::from($this->from_status);
            return '<span class="badge ' . $status->badgeClass() . '">' . $status->label() . '</span>';
        } catch (\Exception $e) {
            return '<span class="badge badge-soft-secondary">' . ucfirst($this->from_status) . '</span>';
        }
    }

    /**
     * Get formatted status badge for to status
     */
    public function getToStatusBadgeAttribute()
    {
        try {
            $status = \App\Enums\TicketStatus::from($this->to_status);
            return '<span class="badge ' . $status->badgeClass() . '">' . $status->label() . '</span>';
        } catch (\Exception $e) {
            return '<span class="badge badge-soft-secondary">' . ucfirst($this->to_status) . '</span>';
        }
    }
}
