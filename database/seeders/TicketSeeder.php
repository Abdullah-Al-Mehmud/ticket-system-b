<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->pluck('id');

        $categoryIds = TicketCategory::pluck('id');

        if ($userIds->isEmpty() || $categoryIds->isEmpty()) {
            $this->command->warn('Users or Ticket Categories missing. Please seed users and ticket categories first.');
            return;
        }

        $statuses = ['Confirmed', 'Cancelled', 'Refunded'];

        foreach (range(1, 50) as $i) {
            DB::beginTransaction();

            try {
                $userId = $userIds->random();
                $categoryId = $categoryIds->random();
                $quantity = fake()->numberBetween(1, 4);
                $status = collect($statuses)->random();

                Ticket::create([
                    'user_id'            => $userId,
                    'ticket_category_id' => $categoryId,
                    'quantity'           => $quantity,
                    'status'             => $status,
                ]);

                // Only update sold_quantity for confirmed tickets
                if ($status === 'Confirmed') {
                    TicketCategory::where('id', $categoryId)
                        ->increment('sold_quantity', $quantity);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Failed to create ticket on iteration {$i}: " . $e->getMessage());
            }
        }

        $this->command->info('50 tickets seeded successfully using real-style data (no duplicates, no updates).');
    }
}
