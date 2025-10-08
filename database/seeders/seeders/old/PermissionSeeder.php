<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * PermissionSeeder
 *
 * Creates all permissions and assigns them to roles
 *
 * To run:
 * php artisan db:seed --class=PermissionSeeder
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing permissions and pivot table
        DB::table('role_permissions')->truncate();
        Permission::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Define all permissions
        $permissions = [
            // Finance Tickets
            ['name' => 'view-finance-ticket', 'display_name' => 'View Finance Tickets', 'module' => 'tickets'],
            ['name' => 'create-finance-ticket', 'display_name' => 'Create Finance Tickets', 'module' => 'tickets'],
            ['name' => 'edit-finance-ticket', 'display_name' => 'Edit Finance Tickets', 'module' => 'tickets'],
            ['name' => 'edit-own-finance-ticket', 'display_name' => 'Edit Own Finance Tickets', 'module' => 'tickets'],
            ['name' => 'delete-draft-ticket', 'display_name' => 'Delete Draft Tickets', 'module' => 'tickets'],
            ['name' => 'delete-own-draft-ticket', 'display_name' => 'Delete Own Draft Tickets', 'module' => 'tickets'],
            ['name' => 'post-ticket', 'display_name' => 'Post Tickets', 'module' => 'tickets'],
            ['name' => 'unpost-ticket', 'display_name' => 'Unpost Tickets', 'module' => 'tickets'],
            ['name' => 'cancel-ticket', 'display_name' => 'Cancel Tickets', 'module' => 'tickets'],
            ['name' => 'update-sage-fields', 'display_name' => 'Update Sage ERP Fields', 'module' => 'tickets'],

            // Fuel Tickets
            ['name' => 'view-fuel-ticket', 'display_name' => 'View Fuel Tickets', 'module' => 'fuel'],
            ['name' => 'create-fuel-ticket', 'display_name' => 'Create Fuel Tickets', 'module' => 'fuel'],
            ['name' => 'edit-fuel-ticket', 'display_name' => 'Edit Fuel Tickets', 'module' => 'fuel'],
            ['name' => 'edit-own-fuel-ticket', 'display_name' => 'Edit Own Fuel Tickets', 'module' => 'fuel'],

            // Clients
            ['name' => 'view-clients', 'display_name' => 'View Clients', 'module' => 'masters'],
            ['name' => 'create-client', 'display_name' => 'Create Clients', 'module' => 'masters'],
            ['name' => 'edit-client', 'display_name' => 'Edit Clients', 'module' => 'masters'],
            ['name' => 'edit-own-client', 'display_name' => 'Edit Own Clients', 'module' => 'masters'],
            ['name' => 'delete-client', 'display_name' => 'Delete Clients', 'module' => 'masters'],

            // Master Data
            ['name' => 'manage-cost-centers', 'display_name' => 'Manage Cost Centers', 'module' => 'masters'],
            ['name' => 'manage-service-types', 'display_name' => 'Manage Service Types', 'module' => 'masters'],
            ['name' => 'manage-uom', 'display_name' => 'Manage UOM', 'module' => 'masters'],
            ['name' => 'manage-departments', 'display_name' => 'Manage Departments', 'module' => 'masters'],

            // Users
            ['name' => 'manage-users', 'display_name' => 'Manage Users', 'module' => 'users'],
            ['name' => 'manage-roles', 'display_name' => 'Manage Roles', 'module' => 'users'],
            ['name' => 'manage-permissions', 'display_name' => 'Manage Permissions', 'module' => 'users'],

            // Reports
            ['name' => 'view-reports', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'view-own-reports', 'display_name' => 'View Own Reports', 'module' => 'reports'],
            ['name' => 'export-reports', 'display_name' => 'Export Reports', 'module' => 'reports'],

            // System
            ['name' => 'manage-settings', 'display_name' => 'Manage System Settings', 'module' => 'system'],
            ['name' => 'view-audit-logs', 'display_name' => 'View Audit Logs', 'module' => 'system'],
            ['name' => 'system-backup', 'display_name' => 'System Backup', 'module' => 'system'],
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('✓ Created ' . count($permissions) . ' permissions');

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to roles based on access level
     */
    private function assignPermissionsToRoles(): void
    {
        // Get roles
        $userRole = Role::where('name', 'user')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $superAdminRole = Role::where('name', 'super_admin')->first();

        // User permissions
        $userPermissions = Permission::whereIn('name', [
            'view-finance-ticket',
            'create-finance-ticket',
            'edit-own-finance-ticket',
            'delete-own-draft-ticket',
            'view-fuel-ticket',
            'create-fuel-ticket',
            'edit-own-fuel-ticket',
            'view-clients',
            'create-client',
            'edit-own-client',
            'view-own-reports',
        ])->pluck('id');

        $userRole->permissions()->sync($userPermissions);
        $this->command->info('✓ Assigned ' . $userPermissions->count() . ' permissions to User role');

        // Admin permissions
        $adminPermissions = Permission::whereIn('name', [
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
        ])->pluck('id');

        $adminRole->permissions()->sync($adminPermissions);
        $this->command->info('✓ Assigned ' . $adminPermissions->count() . ' permissions to Admin role');

        // Super Admin gets all permissions
        $allPermissions = Permission::pluck('id');
        $superAdminRole->permissions()->sync($allPermissions);
        $this->command->info('✓ Assigned ALL ' . $allPermissions->count() . ' permissions to Super Admin role');
    }
}
