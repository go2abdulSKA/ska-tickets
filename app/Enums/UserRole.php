<?php

namespace App\Enums;

/**
 * User Role Enum
 *
 * Three-tier permission system:
 * USER → ADMIN → SUPER_ADMIN
 *
 * @package App\Enums
 */
enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    public function label(): string
    {
        return match($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::USER => 'Basic user with access to create and manage own tickets',
            self::ADMIN => 'Can post/unpost tickets and manage master data',
            self::SUPER_ADMIN => 'Full system access including user management',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::USER => 'bg-secondary',
            self::ADMIN => 'bg-primary',
            self::SUPER_ADMIN => 'bg-danger',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::USER => 'mdi mdi-account',
            self::ADMIN => 'mdi mdi-shield-account',
            self::SUPER_ADMIN => 'mdi mdi-shield-crown',
        };
    }

    /**
     * Get role hierarchy level (higher = more privileges)
     */
    public function level(): int
    {
        return match($this) {
            self::USER => 1,
            self::ADMIN => 2,
            self::SUPER_ADMIN => 3,
        };
    }

    public function isUser(): bool
    {
        return $this === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN || $this === self::SUPER_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this === self::SUPER_ADMIN;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return array_map(
            fn($role) => [
                'value' => $role->value,
                'label' => $role->label(),
                'description' => $role->description(),
                'badge_class' => $role->badgeClass(),
                'level' => $role->level(),
            ],
            self::cases()
        );
    }

    public static function default(): self
    {
        return self::USER;
    }
}
