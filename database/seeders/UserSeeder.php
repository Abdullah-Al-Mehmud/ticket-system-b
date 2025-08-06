<?php

namespace Database\Seeders;

use App\Models\User;
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
            ['name' => 'Regular User', 'email' => 'user@gmail.com', 'role' => 'user'],
            ['name' => 'Bob Smith', 'email' => 'bob@gmail.com', 'role' => 'user'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@gmail.com', 'role' => 'user'],
            ['name' => 'Diana Lee', 'email' => 'diana@gmail.com', 'role' => 'user'],
            ['name' => 'Ethan Brown', 'email' => 'ethan@gmail.com', 'role' => 'user'],
            ['name' => 'Fiona Clark', 'email' => 'fiona@gmail.com', 'role' => 'user'],
            ['name' => 'George Wilson', 'email' => 'george@gmail.com', 'role' => 'user'],
            ['name' => 'Hannah Martin', 'email' => 'hannah@gmail.com', 'role' => 'user'],
            ['name' => 'Ian Thompson', 'email' => 'ian@gmail.com', 'role' => 'user'],
        ];

        foreach ($users as $u) {
            $user = User::where('email', $u['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => Hash::make('password'), // Default password
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
