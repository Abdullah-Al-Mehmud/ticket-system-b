<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        try {
            $userId = Auth::id();

            // Total tickets purchased
            $totalTickets = Ticket::where('user_id', $userId)->count();

            // Tickets by status
            $ticketStatusCounts = Ticket::where('user_id', $userId)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Total events participated
            $uniqueEventCount = Ticket::where('user_id', $userId)
                ->distinct('event_id')
                ->count('event_id');

            // Total spent
            $totalSpent = Ticket::with('event')
                ->where('user_id', $userId)
                ->get()
                ->sum(function ($ticket) {
                    return $ticket->ticket_quantity * $ticket->event->ticket_price;
                });

            return response()->json([
                'status' => true,
                'message' => 'User dashboard data fetched successfully.',
                'data' => [
                    'total_tickets' => $totalTickets,
                    'tickets_by_status' => $ticketStatusCounts,
                    'events_participated' => $uniqueEventCount,
                    'total_spent' => number_format($totalSpent, 2)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load user dashboard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
