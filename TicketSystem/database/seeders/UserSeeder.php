<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@gmail.com', 'role' => 'admin'],
            ['name' => 'Organizer User', 'email' => 'organizer@gmail.com', 'role' => 'organizer'],
            ['name' => 'Regular User', 'email' => 'user@gmail.com', 'role' => 'user'],
        ];

        foreach ($users as $u) {
            $user = User::where('email', $u['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => Hash::make('password'),
                ]);
            }
            $role = Role::firstOrCreate([
                'name' => $u['role'],
                'guard_name' => 'api',
            ]);
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        }
    }
}
