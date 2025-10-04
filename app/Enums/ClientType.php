<?php

namespace App\Enums;

/**
 * Client Type Enum
 *
 * Determines if a ticket is for an external client or internal cost center
 *
 * @package App\Enums
 */
enum ClientType: string
{
    case CLIENT = 'client';
    case COST_CENTER = 'cost_center';

    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Client',
            self::COST_CENTER => 'Cost Center',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::CLIENT => 'mdi mdi-account-multiple',
            self::COST_CENTER => 'mdi mdi-office-building',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::CLIENT => 'bg-primary',
            self::COST_CENTER => 'bg-info',
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
                'icon' => $type->icon(),
            ],
            self::cases()
        );
    }
}
