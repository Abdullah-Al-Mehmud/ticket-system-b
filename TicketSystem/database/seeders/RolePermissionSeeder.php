<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // All Permissions (admin and user only)
        $permissions = [
            // Admin event permissions
            'create events admin',
            'edit events admin',
            'delete events admin',
            'view events admin',

            // Admin category permissions
            'create categories admin',
            'edit categories admin',
            'delete categories admin',
            'view categories admin',

            // Admin user/ticket management
            'manage users admin',
            'manage tickets admin',

            // Admin dashboard
            'view admin dashboard',

            // User permissions
            'create ticket user',
            'view ticket user',
            'view user dashboard',

            // Shared/view permissions
            'view events',
            'view categories',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        // Role-wise permission mapping
        $rolesPermissions = [
            'admin' => $permissions, // Admin gets all

            'user' => [
                'view events',
                'view categories',
                'view user dashboard',
                'create ticket user',
                'view ticket user',
            ],
        ];

        // Assign permissions to roles
        foreach ($rolesPermissions as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'api',
            ]);

            $role->syncPermissions(
                Permission::whereIn('name', $rolePermissions)->get()
            );
        }
    }
}
