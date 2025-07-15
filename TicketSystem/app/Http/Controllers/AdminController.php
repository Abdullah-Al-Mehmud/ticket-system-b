<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            // Total users by role
            $totalUsers = User::count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalOrganizers = User::where('role', 'organizer')->count();
            $totalCustomers = User::where('role', 'user')->count();

            // Total events
            $totalEvents = Event::count();

            // Events by status
            $eventStatusCounts = Event::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Total tickets & breakdown
            $totalTickets = Ticket::count();
            $ticketStatusCounts = Ticket::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Active/Inactive categories
            $categoryStatusCounts = Category::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            // Top 5 popular events by ticket sales
            $topEvents = Event::select('id', 'title')
                ->withCount('tickets')
                ->orderBy('tickets_count', 'desc')
                ->take(5)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Admin dashboard data fetched successfully.',
                'data' => [
                    'users' => [
                        'total' => $totalUsers,
                        'admins' => $totalAdmins,
                        'organizers' => $totalOrganizers,
                        'customers' => $totalCustomers,
                    ],
                    'events' => [
                        'total' => $totalEvents,
                        'by_status' => $eventStatusCounts,
                    ],
                    'tickets' => [
                        'total' => $totalTickets,
                        'by_status' => $ticketStatusCounts,
                    ],
                    'categories' => [
                        'by_status' => $categoryStatusCounts
                    ],
                    'top_events' => $topEvents,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch dashboard data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
