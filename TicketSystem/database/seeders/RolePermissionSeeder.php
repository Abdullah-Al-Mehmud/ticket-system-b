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
        $permissions = ['create_events', 'edit_events', 'delete_events', 'view_events'];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'api',
            ]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminRole->syncPermissions(Permission::where('guard_name', 'api')->get());

        $organizerRole = Role::firstOrCreate(['name' => 'organizer', 'guard_name' => 'api']);
        $organizerRole->syncPermissions(
            Permission::whereIn('name', ['create_events', 'edit_events', 'view_events'])
                ->where('guard_name', 'api')->get()
        );

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $userRole->syncPermissions(
            Permission::where('name', 'view_events')
                ->where('guard_name', 'api')->get()
        );
    }
}
