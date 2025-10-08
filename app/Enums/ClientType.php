<?php
// app/Enums/ClientType.php

namespace App\Enums;

/**
 * Client Type Enum
 *
 * Defines whether a ticket is for an external client or internal cost center
 * This is used to determine which ID field (client_id or cost_center_id) is populated
 *
 * File Location: app/Enums/ClientType.php
 *
 * Usage Example:
 * ```php
 * if ($ticket->client_type === ClientType::CLIENT) {
 *     $customer = $ticket->client;
 * } else {
 *     $customer = $ticket->costCenter;
 * }
 * ```
 */
enum ClientType: string
{
    case CLIENT = 'client';
    case COST_CENTER = 'cost_center';

    /**
     * Get human-readable label for display in UI
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Client',
            self::COST_CENTER => 'Cost Center',
        };
    }

    /**
     * Get plural form of label
     *
     * @return string
     */
    public function pluralLabel(): string
    {
        return match($this) {
            self::CLIENT => 'Clients',
            self::COST_CENTER => 'Cost Centers',
        };
    }

    /**
     * Get description for help text
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            self::CLIENT => 'External customer or client company',
            self::COST_CENTER => 'Internal company cost center or department',
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
            self::CLIENT => 'mdi mdi-account-multiple',
            self::COST_CENTER => 'mdi mdi-office-building',
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
            self::CLIENT => 'bg-primary',
            self::COST_CENTER => 'bg-info',
        };
    }

    /**
     * Get the model class name for this client type
     *
     * @return string
     */
    public function modelClass(): string
    {
        return match($this) {
            self::CLIENT => 'App\\Models\\Client',
            self::COST_CENTER => 'App\\Models\\CostCenter',
        };
    }

    /**
     * Get the foreign key field name
     *
     * @return string
     */
    public function foreignKey(): string
    {
        return match($this) {
            self::CLIENT => 'client_id',
            self::COST_CENTER => 'cost_center_id',
        };
    }

    /**
     * Get the relationship method name
     *
     * @return string
     */
    public function relationshipName(): string
    {
        return match($this) {
            self::CLIENT => 'client',
            self::COST_CENTER => 'costCenter',
        };
    }

    /**
     * Check if this is a client type
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return $this === self::CLIENT;
    }

    /**
     * Check if this is a cost center type
     *
     * @return bool
     */
    public function isCostCenter(): bool
    {
        return $this === self::COST_CENTER;
    }

    /**
     * Get all client type values as array
     * Useful for validation rules
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all client types with labels for dropdown
     * Returns: [['value' => 'client', 'label' => 'Client'], ...]
     *
     * @return array
     */
    public static function options(): array
    {
        return array_map(
            fn($type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'plural_label' => $type->pluralLabel(),
                'description' => $type->description(),
                'icon_class' => $type->iconClass(),
                'badge_class' => $type->badgeClass(),
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
