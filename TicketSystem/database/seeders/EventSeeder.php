<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizer = User::create([
            'name' => 'Organizer',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password'),
            'role' => 'organizer'
        ]);
        $category = Category::create([
            'name' => fake()->randomElement(['Music', 'Sports', 'Tech', 'Business']),
            'status' => fake()->randomElement(['active', 'inactive'])
        ]);

        for ($i = 1; $i <= 15; $i++) {
            Event::create([
                'created_by' => $organizer->id,
                'category_id' => $category->id,
                'title' => 'Event ' . $i,
                'event_description' => fake()->paragraph(3),
                'location' => fake()->city(),
                'start_date' => Carbon::now()->addDays($i),
                'end_date' => Carbon::now()->addDays($i)->addHours(3),
                'ticket_price' => fake()->randomFloat(2, 100, 1000),
                'status' => fake()->randomElement(['upcoming', 'completed', 'cancelled']),
                'privacy_policy' => 'All tickets are non-refundable unless the event is cancelled.',
                'image_url' => 'https://floral-mountain-2867.fly.storage.tigris.dev/media/events/banner/ONI_HASAN_KV_1200x630.png',
            ]);
        }
    }
}
