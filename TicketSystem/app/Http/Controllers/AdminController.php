<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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


    public function index(Request $request)
    {
        try {
            // Pagination parameters
            $page = $request->query('page', 1);
            $perPage = $request->query('count', 10);

            // Base query
            $query = User::query();

            // Role filtering
            if ($request->has('role')) {
                $role = $request->input('role');
                $query->where('role', $role);
            }

            // Search by name
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where('name', 'like', '%' . $searchTerm . '%');
            }

            // Execute pagination
            $users = $query->paginate($perPage, ['*'], 'page', $page);
            $count = $query->count();

            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully.',
                'data' => $users->items(),
                'total_user' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch users.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Single user Data fetched Successfully',
            'data' => $user
        ]);
    }
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|in:user,organizer,admin',
            ]);

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'User creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Validate incoming data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email,' . $user->id,
                'role' => 'required|in:user,organizer,admin',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            // Update fields
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            // Update password if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'User update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // user delete
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
