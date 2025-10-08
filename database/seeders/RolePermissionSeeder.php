<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * RolePermissionSeeder
 *
 * Assigns permissions to each role based on their access level
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $userRole = Role::where('name', 'user')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        // USER PERMISSIONS (Basic ticket creation)
        $userPermissions = Permission::whereIn('name', [
            'view-tickets',
            'create-ticket',
            'edit-own-ticket',
            'delete-own-draft-ticket',
            'view-clients',
            'create-client',
            'view-cost-centers',
            'view-service-types',
            'view-uom',
        ])->pluck('id');

        $userRole->permissions()->sync($userPermissions);

        // ADMIN PERMISSIONS (All user permissions + management capabilities)
        $adminPermissions = Permission::whereIn('name', [
            // All user permissions
            'view-tickets',
            'create-ticket',
            'edit-own-ticket',
            'edit-any-ticket',
            'delete-own-draft-ticket',
            'delete-any-draft-ticket',
            'post-ticket',
            'unpost-ticket',
            'cancel-ticket',

            // Client management
            'view-clients',
            'create-client',
            'edit-client',
            'delete-client',

            // Master data management
            'view-cost-centers',
            'manage-cost-centers',
            'view-service-types',
            'manage-service-types',
            'view-uom',
            'manage-uom',

            // Reports
            'view-reports',
            'export-reports',
        ])->pluck('id');

        $adminRole->permissions()->sync($adminPermissions);

        // SUPER ADMIN PERMISSIONS (All permissions)
        $superAdminPermissions = Permission::all()->pluck('id');
        $superAdminRole->permissions()->sync($superAdminPermissions);

        $this->command->info('✓ Role permissions assigned successfully!');
    }
}
