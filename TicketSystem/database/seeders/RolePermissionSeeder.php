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
        // All Permissions
        $permissions = [
            'create events admin',
            'edit events admin',
            'delete events admin',
            'view events admin',

            'create events organizer',
            'edit events organizer',
            'delete events organizer',
            'view events organizer',

            'create categories admin',
            'edit categories admin',
            'delete categories admin',
            'view categories admin',

            'manage users admin',
            'manage tickets admin',

            'create ticket user',
            'view ticket user',

            'view admin dashboard',
            'view organizer dashboard',
            'view_user_dashboard',

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

            'organizer' => [
                'create events organizer',
                'edit events organizer',
                'view organizer dashboard',
                'delete events organizer',
                'view events organizer',
                'view categories',
            ],

            'user' => [
                'view events',
                'view categories',
                'view user dashboard',
                'create ticket user',
                'View ticket user',
                'view_user_dashboard',
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
