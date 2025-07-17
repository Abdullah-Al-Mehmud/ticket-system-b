<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'organizer User1', 'email' => 'organizer1@gmail.com', 'role' => 'organizer'],
            ['name' => 'Organizer User2', 'email' => 'organizer2@gmail.com', 'role' => 'organizer'],
            ['name' => 'Regular User3', 'email' => 'organizer3@gmail.com', 'role' => 'organizer'],
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

        $organizers = User::role('organizer', 'api')->get();

        $categoryNames = ['Music', 'Sports', 'Tech', 'Business', 'Comedy'];
        $categories = [];

        foreach ($categoryNames as $name) {
            $categories[] = Category::firstOrCreate(
                ['name' => $name],
                ['status' => fake()->randomElement(['active', 'inactive'])]
            );
        }

        $bannerImages = [
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',  // Nature
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',  // Concert crowd
            'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80',  // Music instruments
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80',  // Theater stage
            'https://images.unsplash.com/photo-1464375117522-1311f55a04c7?auto=format&fit=crop&w=800&q=80',  // Sports event
        ];



        for ($i = 1; $i <= 15; $i++) {
            Event::create([
                'created_by' => $organizers->random()->id,
                'category_id' => collect($categories)->random()->id,
                'title' => 'Event ' . $i,
                'event_description' => fake()->paragraph(3),
                'location' => fake()->city(),
                'start_date' => Carbon::now()->addDays($i),
                'end_date' => Carbon::now()->addDays($i)->addHours(3),
                'ticket_price' => fake()->randomFloat(2, 100, 1000),
                'status' => fake()->randomElement(['upcoming', 'completed', 'cancelled']),
                'privacy_policy' => 'All tickets are non-refundable unless the event is cancelled.',
                'image_url' => fake()->randomElement($bannerImages),
            ]);
        }
    }
}
