<?php

namespace App\Enums;

/**
 * Ticket Status Enum
 *
 * Lifecycle:
 * DRAFT → POSTED → (optionally) CANCELLED
 *
 * @package App\Enums
 */
enum TicketStatus: string
{
    case DRAFT = 'draft';
    case POSTED = 'posted';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::POSTED => 'Posted',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::DRAFT => 'bg-warning',
            self::POSTED => 'bg-success',
            self::CANCELLED => 'bg-danger',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DRAFT => 'mdi mdi-file-edit-outline',
            self::POSTED => 'mdi mdi-check-circle',
            self::CANCELLED => 'mdi mdi-close-circle',
        };
    }

    /**
     * Can this status be edited?
     */
    public function canEdit(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Can this status be deleted?
     */
    public function canDelete(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Can this status be posted?
     */
    public function canPost(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Can this status be unposted?
     */
    public function canUnpost(): bool
    {
        return $this === self::POSTED;
    }

    /**
     * Can this status be cancelled?
     */
    public function canCancel(): bool
    {
        return $this !== self::CANCELLED;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(
            fn($status) => [
                'value' => $status->value,
                'label' => $status->label(),
                'badge_class' => $status->badgeClass(),
                'icon' => $status->icon(),
            ],
            self::cases()
        );
    }
}
