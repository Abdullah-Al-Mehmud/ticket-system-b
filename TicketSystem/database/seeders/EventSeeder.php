<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Organizer User1', 'email' => 'organizer1@gmail.com', 'role' => 'organizer'],
            ['name' => 'Organizer User2', 'email' => 'organizer2@gmail.com', 'role' => 'organizer'],
            ['name' => 'Organizer User3', 'email' => 'organizer3@gmail.com', 'role' => 'organizer'],
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

            // Assuming default guard 'web' here, change if you use 'api'
            $role = Role::firstOrCreate([
                'name' => $u['role'],
                'guard_name' => 'api',
            ]);

            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        }

        $organizers = User::role('organizer','api')->get();

        $bannerImages = [
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1464375117522-1311f55a04c7?auto=format&fit=crop&w=800&q=80',
        ];

        $statusOptions = ['Upcoming', 'Live', 'Done', 'Cancelled'];

        for ($i = 1; $i <= 15; $i++) {
            Event::create([
                'created_by' => $organizers->random()->id,
                'title' => 'Event ' . $i,
                'event_description' => fake()->paragraph(3),
                'location' => fake()->city(),
                'start_date' => Carbon::now()->addDays($i),
                'end_date' => Carbon::now()->addDays($i)->addHours(3),
                'privacy_policy' => 'All tickets are non-refundable unless the event is cancelled.',
                'image_url' => fake()->randomElement($bannerImages),
                'status' => fake()->randomElement($statusOptions),
            ]);
        }
    }
}
