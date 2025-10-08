<?php
// app/Enums/PaymentType.php

namespace App\Enums;

/**
 * Payment Type Enum
 *
 * Defines the available payment methods for tickets
 *
 * File Location: app/Enums/PaymentType.php
 *
 * Usage Example:
 * ```php
 * $ticket->payment_type = PaymentType::PO;
 * echo $ticket->payment_type->label(); // "Purchase Order"
 * ```
 */
enum PaymentType: string
{
    case PO = 'po';
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case NA = 'na';

    /**
     * Get human-readable label for display in UI
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::PO => 'Purchase Order',
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'Credit Card',
            self::NA => 'N/A',
        };
    }

    /**
     * Get short label for compact display
     *
     * @return string
     */
    public function shortLabel(): string
    {
        return match($this) {
            self::PO => 'PO',
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'CC',
            self::NA => 'N/A',
        };
    }

    /**
     * Get icon class for UI display
     *
     * @return string Material Design Icons class
     */
    public function iconClass(): string
    {
        return match($this) {
            self::PO => 'mdi mdi-file-document',
            self::CASH => 'mdi mdi-cash',
            self::CREDIT_CARD => 'mdi mdi-credit-card',
            self::NA => 'mdi mdi-help-circle',
        };
    }

    /**
     * Get badge color class for UI display
     *
     * @return string CSS class name
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::PO => 'bg-primary',
            self::CASH => 'bg-success',
            self::CREDIT_CARD => 'bg-info',
            self::NA => 'bg-secondary',
        };
    }

    /**
     * Check if this payment type requires reference number
     *
     * @return bool
     */
    public function requiresReference(): bool
    {
        return match($this) {
            self::PO => true,
            self::CREDIT_CARD => true,
            default => false,
        };
    }

    /**
     * Get all payment type values as array
     * Useful for validation rules
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all payment types with labels for dropdown
     * Returns: [['value' => 'po', 'label' => 'Purchase Order'], ...]
     *
     * @return array
     */
    public static function options(): array
    {
        return array_map(
            fn($type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'short_label' => $type->shortLabel(),
                'icon_class' => $type->iconClass(),
                'badge_class' => $type->badgeClass(),
                'requires_reference' => $type->requiresReference(),
            ],
            self::cases()
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
