<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\EventOrganizer;
use Illuminate\Database\Seeder;

class EventOrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::pluck('id');
        $users = User::pluck('id');

        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No events or users found. Please seed events and users first.');
            return;
        }

        $assignments = [];

        for ($i = 0; $i < 15; $i++) {
            $eventId = $events->random();
            $userId = $users->random();

            $alreadyAssigned = EventOrganizer::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyAssigned) {
                continue; // skip duplicate
            }

            $assignments[] = [
                'event_id' => $eventId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        EventOrganizer::insert($assignments);

        $this->command->info('Organizer assignments seeded successfully.');
    }
}
