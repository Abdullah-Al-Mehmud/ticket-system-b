<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketCategory;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();

        if ($events->isEmpty()) {
            $this->command->warn('⚠️ No events found. Please run EventSeeder first.');
            return;
        }

        foreach ($events as $event) {
            $numCategories = rand(1, 3); 

            for ($i = 1; $i <= $numCategories; $i++) {
                $salesStart = Carbon::parse($event->start_date)->subDays(rand(3, 10));
                $salesEnd = Carbon::parse($event->start_date)->subDays(rand(0, 2));

                TicketCategory::create([
                    'event_id'       => $event->id,
                    'name'           => 'Ticket ' . strtoupper(Str::random(4)),
                    'price'          => rand(100, 1000),
                    'sales_start'    => $salesStart,
                    'sales_end'      => $salesEnd->greaterThan($salesStart) ? $salesEnd : $salesStart->copy()->addDays(2),
                    'total_quantity' => rand(50, 300),
                    'sold_quantity'  => rand(0, 30),
                ]);
            }
        }

        $this->command->info('✅ Ticket categories seeded successfully for all events.');
    }
}
