<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

/**
 * PermissionSeeder
 * 
 * Seeds all granular permissions organized by module
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Ticket Permissions
            ['name' => 'view-tickets', 'display_name' => 'View Tickets', 'module' => 'tickets'],
            ['name' => 'create-ticket', 'display_name' => 'Create Ticket', 'module' => 'tickets'],
            ['name' => 'edit-own-ticket', 'display_name' => 'Edit Own Ticket', 'module' => 'tickets'],
            ['name' => 'edit-any-ticket', 'display_name' => 'Edit Any Ticket', 'module' => 'tickets'],
            ['name' => 'delete-own-draft-ticket', 'display_name' => 'Delete Own Draft Ticket', 'module' => 'tickets'],
            ['name' => 'delete-any-draft-ticket', 'display_name' => 'Delete Any Draft Ticket', 'module' => 'tickets'],
            ['name' => 'post-ticket', 'display_name' => 'Post Ticket', 'module' => 'tickets'],
            ['name' => 'unpost-ticket', 'display_name' => 'Unpost Ticket', 'module' => 'tickets'],
            ['name' => 'cancel-ticket', 'display_name' => 'Cancel Ticket', 'module' => 'tickets'],
            ['name' => 'view-all-departments-tickets', 'display_name' => 'View All Departments Tickets', 'module' => 'tickets'],

            // Client Permissions
            ['name' => 'view-clients', 'display_name' => 'View Clients', 'module' => 'clients'],
            ['name' => 'create-client', 'display_name' => 'Create Client', 'module' => 'clients'],
            ['name' => 'edit-client', 'display_name' => 'Edit Client', 'module' => 'clients'],
            ['name' => 'delete-client', 'display_name' => 'Delete Client', 'module' => 'clients'],

            // Cost Center Permissions
            ['name' => 'view-cost-centers', 'display_name' => 'View Cost Centers', 'module' => 'cost-centers'],
            ['name' => 'manage-cost-centers', 'display_name' => 'Manage Cost Centers', 'module' => 'cost-centers'],

            // Service Type Permissions
            ['name' => 'view-service-types', 'display_name' => 'View Service Types', 'module' => 'service-types'],
            ['name' => 'manage-service-types', 'display_name' => 'Manage Service Types', 'module' => 'service-types'],

            // UOM Permissions
            ['name' => 'view-uom', 'display_name' => 'View UOM', 'module' => 'uom'],
            ['name' => 'manage-uom', 'display_name' => 'Manage UOM', 'module' => 'uom'],

            // Department Permissions
            ['name' => 'view-departments', 'display_name' => 'View Departments', 'module' => 'departments'],
            ['name' => 'manage-departments', 'display_name' => 'Manage Departments', 'module' => 'departments'],

            // User Management Permissions
            ['name' => 'view-users', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'create-user', 'display_name' => 'Create User', 'module' => 'users'],
            ['name' => 'edit-user', 'display_name' => 'Edit User', 'module' => 'users'],
            ['name' => 'delete-user', 'display_name' => 'Delete User', 'module' => 'users'],
            ['name' => 'manage-user-departments', 'display_name' => 'Manage User Departments', 'module' => 'users'],

            // Role & Permission Management
            ['name' => 'view-roles', 'display_name' => 'View Roles', 'module' => 'roles'],
            ['name' => 'manage-roles', 'display_name' => 'Manage Roles', 'module' => 'roles'],
            ['name' => 'manage-permissions', 'display_name' => 'Manage Permissions', 'module' => 'permissions'],

            // Report Permissions
            ['name' => 'view-reports', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'export-reports', 'display_name' => 'Export Reports', 'module' => 'reports'],

            // Settings Permissions
            ['name' => 'manage-settings', 'display_name' => 'Manage Settings', 'module' => 'settings'],
            ['name' => 'view-activity-logs', 'display_name' => 'View Activity Logs', 'module' => 'logs'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('✓ Permissions created successfully!');
    }
}
