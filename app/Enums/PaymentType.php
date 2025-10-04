<?php

namespace App\Enums;

/**
 * Payment Type Enum
 *
 * Available payment methods for finance tickets
 *
 * @package App\Enums
 */
enum PaymentType: string
{
    case PO = 'po';
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case NA = 'na';

    public function label(): string
    {
        return match($this) {
            self::PO => 'Purchase Order',
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'Credit Card',
            self::NA => 'N/A',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::PO => 'PO',
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'CC',
            self::NA => 'N/A',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PO => 'mdi mdi-file-document',
            self::CASH => 'mdi mdi-cash',
            self::CREDIT_CARD => 'mdi mdi-credit-card',
            self::NA => 'mdi mdi-help-circle',
        };
    }

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
     */
    public function requiresReference(): bool
    {
        return match($this) {
            self::PO => true,
            self::CREDIT_CARD => true,
            default => false,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(
            fn($type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'short_label' => $type->shortLabel(),
                'icon' => $type->icon(),
                'requires_reference' => $type->requiresReference(),
            ],
            self::cases()
        );
    }
}
