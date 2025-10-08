<?php
// app/Enums/UserRole.php

namespace App\Enums;

/**
 * User Role Enum
 *
 * Defines the three main user roles in the system with their permissions
 *
 * Role Hierarchy:
 * 1. USER - Basic user with limited access to their own departments
 * 2. ADMIN - Can manage tickets, post/unpost, manage master data
 * 3. SUPER_ADMIN - Full system access including user management
 *
 * File Location: app/Enums/UserRole.php
 *
 * Usage Example:
 * ```php
 * if ($user->role === UserRole::ADMIN) {
 *     // Show admin menu
 * }
 *
 * if (UserRole::SUPER_ADMIN->hasPermission('manage-users')) {
 *     // Allow user management
 * }
 * ```
 */
enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    /**
     * Get human-readable label for display in UI
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }

    /**
     * Get role description
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            self::USER => 'Basic user with access to create and manage their own tickets',
            self::ADMIN => 'Administrator with ability to post/unpost tickets and manage master data',
            self::SUPER_ADMIN => 'Super Administrator with full system access',
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
            self::USER => 'bg-secondary',
            self::ADMIN => 'bg-primary',
            self::SUPER_ADMIN => 'bg-danger',
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
            self::USER => 'mdi mdi-account',
            self::ADMIN => 'mdi mdi-shield-account',
            self::SUPER_ADMIN => 'mdi mdi-shield-crown',
        };
    }

    /**
     * Get all permissions for this role
     *
     * @return array List of permission names
     */
    public function permissions(): array
    {
        return match($this) {
            self::USER => [
                // Finance Tickets
                'view-finance-ticket',
                'create-finance-ticket',
                'edit-own-finance-ticket',
                'delete-own-draft-ticket',

                // Fuel Tickets
                'view-fuel-ticket',
                'create-fuel-ticket',
                'edit-own-fuel-ticket',

                // Clients
                'view-clients',
                'create-client',
                'edit-own-client',

                // General
                'view-own-reports',
            ],

            self::ADMIN => [
                // Finance Tickets (Full Access)
                'view-finance-ticket',
                'create-finance-ticket',
                'edit-finance-ticket',
                'delete-draft-ticket',
                'post-ticket',
                'unpost-ticket',
                'cancel-ticket',
                'update-sage-fields',

                // Fuel Tickets (Full Access)
                'view-fuel-ticket',
                'create-fuel-ticket',
                'edit-fuel-ticket',

                // Clients
                'view-clients',
                'create-client',
                'edit-client',
                'delete-client',

                // Master Data
                'manage-cost-centers',
                'manage-service-types',
                'manage-uom',

                // Reports
                'view-reports',
                'export-reports',
            ],

            self::SUPER_ADMIN => [
                // All Admin permissions plus:
                'view-finance-ticket',
                'create-finance-ticket',
                'edit-finance-ticket',
                'delete-draft-ticket',
                'post-ticket',
                'unpost-ticket',
                'cancel-ticket',
                'update-sage-fields',
                'view-fuel-ticket',
                'create-fuel-ticket',
                'edit-fuel-ticket',
                'view-clients',
                'create-client',
                'edit-client',
                'delete-client',
                'manage-cost-centers',
                'manage-service-types',
                'manage-uom',
                'view-reports',
                'export-reports',

                // Super Admin Exclusive
                'manage-departments',
                'manage-users',
                'manage-roles',
                'manage-permissions',
                'manage-settings',
                'view-audit-logs',
                'system-backup',
                'system-restore',
            ],
        };
    }

    /**
     * Check if this role has a specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }

    /**
     * Check if this role has any of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return !empty(array_intersect($permissions, $this->permissions()));
    }

    /**
     * Check if this role has all of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return empty(array_diff($permissions, $this->permissions()));
    }

    /**
     * Check if this is a user role
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this === self::USER;
    }

    /**
     * Check if this is an admin role (Admin or Super Admin)
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN || $this === self::SUPER_ADMIN;
    }

    /**
     * Check if this is a super admin role
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this === self::SUPER_ADMIN;
    }

    /**
     * Get role level (for hierarchy comparison)
     * Higher number = more privileges
     *
     * @return int
     */
    public function level(): int
    {
        return match($this) {
            self::USER => 1,
            self::ADMIN => 2,
            self::SUPER_ADMIN => 3,
        };
    }

    /**
     * Check if this role has higher privileges than another role
     *
     * @param self $role
     * @return bool
     */
    public function isHigherThan(self $role): bool
    {
        return $this->level() > $role->level();
    }

    /**
     * Check if this role has lower privileges than another role
     *
     * @param self $role
     * @return bool
     */
    public function isLowerThan(self $role): bool
    {
        return $this->level() < $role->level();
    }

    /**
     * Get all role values as array
     * Useful for validation rules
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all roles with labels and metadata for dropdown
     * Returns: [['value' => 'user', 'label' => 'User', ...], ...]
     *
     * @return array
     */
    public static function options(): array
    {
        return array_map(
            fn($role) => [
                'value' => $role->value,
                'label' => $role->label(),
                'description' => $role->description(),
                'badge_class' => $role->badgeClass(),
                'icon_class' => $role->iconClass(),
                'level' => $role->level(),
                'permissions_count' => count($role->permissions()),
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

    /**
     * Get default role for new users
     *
     * @return self
     */
    public static function default(): self
    {
        return self::USER;
    }
}
