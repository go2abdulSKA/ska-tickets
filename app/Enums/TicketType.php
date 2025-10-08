<?php

namespace App\Enums;

/**
 * Ticket Type Enum
 *
 * Defines the three types of tickets in the system:
 * - Finance: Service invoices with payment tracking
 * - Delivery Note: Goods delivery without pricing (optional pricing)
 * - Fuel Sale: Fuel dispensing with vehicle tracking
 *
 * @package App\Enums
 */
enum TicketType: string
{
    case FINANCE = 'finance';
    case DELIVERY_NOTE = 'delivery_note';
    case FUEL_SALE = 'fuel_sale';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::FINANCE => 'Finance Ticket',
            self::DELIVERY_NOTE => 'Delivery Note',
            self::FUEL_SALE => 'Fuel Sale',
        };
    }

    /**
     * Get icon class for UI
     */
    public function icon(): string
    {
        return match($this) {
            self::FINANCE => 'mdi mdi-file-document',
            self::DELIVERY_NOTE => 'mdi mdi-truck-delivery',
            self::FUEL_SALE => 'mdi mdi-gas-station',
        };
    }

    /**
     * Get badge color class
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::FINANCE => 'bg-primary',
            self::DELIVERY_NOTE => 'bg-info',
            self::FUEL_SALE => 'bg-warning',
        };
    }

    /**
     * Check if this ticket type supports pricing
     */
    public function hasPricing(): bool
    {
        return match($this) {
            self::FINANCE => true,
            self::DELIVERY_NOTE => false, // Can be added later if needed
            self::FUEL_SALE => true,
        };
    }

    /**
     * Get all values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get options for dropdown
     */
    public static function options(): array
    {
        return array_map(
            fn($type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'icon' => $type->icon(),
                'badge_class' => $type->badgeClass(),
            ],
            self::cases()
        );
    }
}
