<?php
// app/Enums/TicketStatus.php

namespace App\Enums;

/**
 * Ticket Status Enum
 *
 * Defines the possible statuses for tickets throughout their lifecycle.
 *
 * Lifecycle Flow:
 * 1. DRAFT - Newly created, can be edited/deleted by creator
 * 2. POSTED - Finalized and sent to ERP, cannot be deleted (Admin can unpost)
 * 3. CANCELLED - Marked as cancelled but retained in system for audit
 *
 * File Location: app/Enums/TicketStatus.php
 *
 * Usage Example:
 * ```php
 * $ticket->status = TicketStatus::DRAFT;
 * if ($ticket->status->canEdit()) {
 *     // Allow editing
 * }
 * ```
 */
enum TicketStatus: string
{
    case DRAFT = 'draft';
    case POSTED = 'posted';
    case CANCELLED = 'cancelled';

    /**
     * Get human-readable label for display in UI
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::POSTED => 'Posted',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get Bootstrap badge color class for UI display
     *
     * @return string CSS class name
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-warning', // Yellow badge
            self::POSTED => 'bg-success', // Green badge
            self::CANCELLED => 'bg-danger', // Red badge
        };
    }

    /**
     * Get icon class for UI display
     *
     * @return string Material Design Icons class
     */
    public function iconClass(): string
    {
        return match ($this) {
            self::DRAFT => 'mdi mdi-file-edit-outline',
            self::POSTED => 'mdi mdi-check-circle',
            self::CANCELLED => 'mdi mdi-close-circle',
        };
    }

    /**
     * Check if ticket can be edited
     * Only draft tickets can be edited
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Check if ticket can be deleted
     * Only draft tickets can be deleted
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Check if ticket can be posted
     * Only draft tickets can be posted
     *
     * @return bool
     */
    public function canPost(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Check if ticket can be unposted
     * Only posted tickets can be unposted (Admin only)
     *
     * @return bool
     */
    public function canUnpost(): bool
    {
        return $this === self::POSTED;
    }

    /**
     * Check if ticket can be cancelled
     * Both draft and posted tickets can be cancelled (Admin only)
     *
     * @return bool
     */

    /**
     * OPTION C: Check if status can be cancelled
     */
    public function canCancel(): bool
    {
        return in_array($this, [self::DRAFT, self::POSTED]);
    }

    /**
     * Get all status values as array
     * Useful for validation rules
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all statuses with labels for dropdown
     * Returns: [['value' => 'draft', 'label' => 'Draft'], ...]
     *
     * @return array
     */
    public static function options(): array
    {
        return array_map(
            fn($status) => [
                'value' => $status->value,
                'label' => $status->label(),
                'badge_class' => $status->badgeClass(),
                'icon_class' => $status->iconClass(),
            ],
            self::cases(),
        );
    }

    /**
     * Create enum instance from string value
     *
     * @param string|null $value
     * @return self|null
     */
    public static function fromValue(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return self::tryFrom($value);
    }
}
