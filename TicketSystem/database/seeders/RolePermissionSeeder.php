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

        Permission::create(['name' => 'create_events']);
        Permission::create(['name' => 'edit_events']);
        Permission::create(['name' => 'delete_events']);
        Permission::create(['name' => 'view_events']);


        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $organizerRole = Role::create(['name' => 'organizer']);
        $organizerRole->givePermissionTo(['create_events', 'edit_events', 'view_events']);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo(['view_events']);
    }
}
