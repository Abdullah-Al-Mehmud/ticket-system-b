<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create organizer users
        $organizersData = [
            ['name' => 'Organizer One', 'email' => 'organizer1@example.com'],
            ['name' => 'Organizer Two', 'email' => 'organizer2@example.com'],
        ];

        $organizerRole = Role::firstOrCreate(['name' => 'organizer', 'guard_name' => 'api']);
        foreach ($organizersData as $data) {
            $organizer = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $organizer->assignRole($organizerRole);
        }

        $organizers = User::role('organizer', 'api')->get();

        // Create categories
        $categoryNames = ['Music', 'Business', 'Sports', 'Tech'];
        $categories = [];

        foreach ($categoryNames as $name) {
            $categories[] = Category::firstOrCreate(
                ['name' => $name],
                ['status' => 'active']
            );
        }

        // Create events
        $bannerImages = [
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80',
        ];

        $events = [];
        for ($i = 1; $i <= 10; $i++) {
            $events[] = Event::create([
                'created_by' => $organizers->random()->id,
                'category_id' => collect($categories)->random()->id,
                'title' => 'Event ' . $i,
                'event_description' => fake()->paragraph(),
                'location' => fake()->city(),
                'start_date' => Carbon::now()->addDays(rand(1, 15)),
                'end_date' => Carbon::now()->addDays(rand(16, 30)),
                'ticket_price' => fake()->randomFloat(2, 100, 1000),
                'status' => fake()->randomElement(['upcoming', 'completed']),
                'privacy_policy' => 'Tickets are non-refundable unless canceled.',
                'image_url' => fake()->randomElement($bannerImages),
            ]);
        }

        // Create ticket buyer users
        $buyersData = [
            ['name' => 'User One', 'email' => 'user1@example.com'],
            ['name' => 'User Two', 'email' => 'user2@example.com'],
            ['name' => 'User Three', 'email' => 'user3@example.com'],
        ];

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);

        foreach ($buyersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole($userRole);
        }

        $users = User::role('user', 'api')->get();

        // Create tickets
        foreach ($users as $user) {
            $purchasedEvents = collect($events)->random(rand(2, 4));
            foreach ($purchasedEvents as $event) {
                Ticket::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'ticket_quantity' => rand(1, 3),
                    'price_per_ticket' => $event->ticket_price,
                    'status' => fake()->randomElement(['booked', 'canceled', 'refunded']),
                    'purchased_at' => Carbon::now()->subDays(rand(1, 5)),
                ]);
            }
        }
    }
}
