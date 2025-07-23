<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketCategory;
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
        $userIds = User::pluck('id');
        $categoryIds = TicketCategory::pluck('id');

        if ($userIds->isEmpty() || $categoryIds->isEmpty()) {
            $this->command->warn('⚠️ Users or Ticket Categories missing. Please seed users and ticket categories first.');
            return;
        }

        foreach (range(1, 30) as $i) {
            $quantity = rand(1, 5);

            // unit_price could come from related TicketCategory price or random here
            // To keep simple, random price between 100 and 1000
            $unitPrice = rand(100, 1000);

            $statuses = ["Confirmed", "Cancelled", "Refunded"];

            Ticket::create([
                'user_id' => $userIds->random(),
                'ticket_category_id' => $categoryIds->random(),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }

        $this->command->info('✅ Tickets seeded successfully.');
    }
}
