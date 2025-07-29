<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user(s) to associate with events
        $admins = User::role('admin', 'api')->get();

        if ($admins->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');
            return;
        }

        // Get all categories (assumes CategorySeeder has run)
        $allCategories = Category::all();
        if ($allCategories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        // Sample banner images
        $bannerImages = [
            'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1464375117522-1311f55a04c7?auto=format&fit=crop&w=800&q=80',
        ];

        // Event status options
        $statusOptions = ['Upcoming', 'Live', 'Done', 'Cancelled'];

        // Realistic event titles
        $eventTitles = [
            'Global Tech Conference 2025',
            'Startup Pitch Night',
            'Live Jazz & Wine Evening',
            'AI & Machine Learning Workshop',
            'Health & Wellness Fair',
            'Art & Culture Exhibition',
            'Digital Marketing Summit',
            'Blockchain for Business Seminar',
            'Photography Masterclass',
            'Online Coding Bootcamp',
            'Women in Leadership Conference',
            'Sustainable Living Expo',
            'Gaming and Esports Meetup',
            'Creative Writing Workshop',
            'Finance & Investment Forum',
            'Indie Film Screening Night',
            'UX/UI Design Sprint',
            'Mobile App Hackathon',
            'Public Speaking Bootcamp',
            'VR & AR Innovation Showcase',
        ];

        foreach ($eventTitles as $index => $title) {
            Event::create([
                'created_by'        => $admins->random()->id,
                'category_id'       => $allCategories->random()->id,
                'title'             => $title,
                'event_description' => fake()->paragraph(3),
                'location'          => fake()->city(),
                'start_date'        => Carbon::now()->addDays($index + 1),
                'end_date'          => Carbon::now()->addDays($index + 1)->addHours(4),
                'privacy_policy'    => 'All tickets are non-refundable unless the event is cancelled.',
                'image_url'         => fake()->randomElement($bannerImages),
                'status'            => fake()->randomElement($statusOptions),
            ]);
        }
    }
}
