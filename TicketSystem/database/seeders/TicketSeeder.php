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
        $userIds = User::pluck('id');
        $categoryIds = TicketCategory::pluck('id');

        if ($userIds->isEmpty() || $categoryIds->isEmpty()) {
            $this->command->warn('⚠️ Users or Ticket Categories missing. Please seed users and ticket categories first.');
            return;
        }

        foreach (range(1, 30) as $i) {
            DB::beginTransaction();

            try {
                $quantity = rand(1, 5);
                $status = collect(["Confirmed", "Cancelled", "Refunded"])->random();
                $categoryId = $categoryIds->random();
                $userId = $userIds->random();

                // Check if a ticket already exists for same user, category, and status
                $existingTicket = Ticket::where('user_id', $userId)
                    ->where('ticket_category_id', $categoryId)
                    ->where('status', $status)
                    ->first();

                if ($existingTicket) {
                    // Update the quantity of the existing ticket
                    $existingTicket->quantity += $quantity;
                    $existingTicket->save();
                    $ticket = $existingTicket;
                } else {
                    // Create new ticket
                    $ticket = Ticket::create([
                        'user_id' => $userId,
                        'ticket_category_id' => $categoryId,
                        'quantity' => $quantity,
                        'status' => $status,
                    ]);
                }

                // Update sold_quantity if status is Confirmed
                if ($status === 'Confirmed') {
                    TicketCategory::where('id', $categoryId)->increment('sold_quantity', $quantity);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("❌ Failed to create/update ticket on iteration {$i}: " . $e->getMessage());
            }
        }

        $this->command->info('✅ Tickets seeded successfully (avoided duplicates, updated quantity, and sold_quantity).');
    }
}
