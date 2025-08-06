<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();

        if ($events->isEmpty()) {
            $this->command->warn('No events found. Please run EventSeeder first.');
            return;
        }

        // Realistic ticket category names with common pricing tiers
        $ticketTypes = [
            ['name' => 'General Admission', 'price' => 200],
            ['name' => 'VIP Pass', 'price' => 500],
            ['name' => 'Early Bird', 'price' => 150],
            ['name' => 'Student Pass', 'price' => 100],
            ['name' => 'Group Package', 'price' => 800],
            ['name' => 'Premium Seat', 'price' => 700],
            ['name' => 'Backstage Access', 'price' => 1200],
        ];

        foreach ($events as $event) {
            $numCategories = rand(1, 3);
            $usedIndexes = [];

            for ($i = 1; $i <= $numCategories; $i++) {
                // Ensure no duplicate ticket names for the same event
                do {
                    $index = array_rand($ticketTypes);
                } while (in_array($index, $usedIndexes));
                $usedIndexes[] = $index;

                $ticketType = $ticketTypes[$index];

                $salesStart = Carbon::parse($event->start_date)->subDays(rand(5, 15));
                $salesEnd = Carbon::parse($event->start_date)->subDays(rand(1, 3));

                TicketCategory::create([
                    'event_id'       => $event->id,
                    'name'           => $ticketType['name'],
                    'price'          => $ticketType['price'],
                    'sales_start'    => $salesStart,
                    'sales_end'      => $salesEnd->greaterThan($salesStart) ? $salesEnd : $salesStart->copy()->addDays(2),
                    'total_quantity' => rand(50, 300),
                    'sold_quantity'  => rand(0, 30),
                ]);
            }
        }

        $this->command->info('Ticket categories seeded successfully with real data.');
    }
}
