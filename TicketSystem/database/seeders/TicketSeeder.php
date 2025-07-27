<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id');
        $categoryIds = TicketCategory::pluck('id');

        if ($userIds->isEmpty() || $categoryIds->isEmpty()) {
            $this->command->warn('⚠️ Users or Ticket Categories missing. Please seed users and ticket categories first.');
            return;
        }

        foreach (range(1, 30) as $i) {
            $quantity = rand(1, 5);

            $statuses = ["Confirmed", "Cancelled", "Refunded"];

            Ticket::create([
                'user_id' => $userIds->random(),
                'ticket_category_id' => $categoryIds->random(),
                'quantity' => $quantity,
                // Removed 'unit_price' as per your provided table schema
                'status' => $statuses[array_rand($statuses)],
            ]);
        }

        $this->command->info('✅ Tickets seeded successfully.');
    }
}
