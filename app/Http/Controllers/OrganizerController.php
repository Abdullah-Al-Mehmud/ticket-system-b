<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        try {
            $organizerId = Auth::id();

            // Total events created by this organizer
            $totalEvents = Event::where('created_by', $organizerId)->count();

            // Events by status
            $eventStatusCounts = Event::where('created_by', $organizerId)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Tickets sold for this organizer's events
            $totalTickets = Ticket::whereHas('event', function ($query) use ($organizerId) {
                $query->where('created_by', $organizerId);
            })->count();

            // Tickets by status
            $ticketStatusCounts = Ticket::whereHas('event', function ($query) use ($organizerId) {
                $query->where('created_by', $organizerId);
            })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Total earnings = sum(ticket_quantity * event.ticket_price)
            $totalEarnings = Ticket::whereHas('event', function ($query) use ($organizerId) {
                $query->where('created_by', $organizerId);
            })->with('event')
                ->get()
                ->sum(function ($ticket) {
                    return $ticket->ticket_quantity * $ticket->event->ticket_price;
                });

            return response()->json([
                'status' => true,
                'message' => 'Organizer dashboard fetched successfully.',
                'data' => [
                    'total_events' => $totalEvents,
                    'event_status' => $eventStatusCounts,
                    'total_tickets_sold' => $totalTickets,
                    'ticket_status' => $ticketStatusCounts,
                    'total_earnings' => number_format($totalEarnings, 2),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load dashboard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
