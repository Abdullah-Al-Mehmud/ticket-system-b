<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'create events',
            'edit events',
            'delete events',
            'view events',

            'create categories',
            'edit categories',
            'delete categories',
            'view categories',

            'manage users',
            'manage tickets',

            'view dashboard',
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
                'create events',
                'edit events',
                'delete events',
                'view events',
                'view categories',
                'view dashboard',
            ],

            'user' => [
                'view events',
                'view categories',
                'view dashboard',
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
