<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $totalUsers = User::count();
            $totalAdmins = User::role('admin')->count();
            $totalOrganizers = 0;
            $totalCustomers = User::role('user')->count();

            $totalEvents = Event::count();
            $eventStatusCounts = Event::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            $totalTickets = Ticket::count();
            $ticketStatusCounts = Ticket::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            $categoryStatusCounts = Category::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

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
                        'users' => $totalCustomers,
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
                        'by_status' => $categoryStatusCounts,
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
            $page = (int) $request->query('page', 1);
            $perPage = (int) $request->query('count', 10);
            $role = $request->query('role');
            $searchTerm = $request->query('search');
            $getAll = filter_var($request->query('all'), FILTER_VALIDATE_BOOLEAN);

            $query = User::with('roles')->latest();

            if ($role) {
                $query->whereHas('roles', fn($q) => $q->where('name', $role));
            }

            if ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            }

            $transformUser = function ($user) {
                $userArray = $user->toArray();
                $role = $userArray['roles'][0] ?? null;

                $userArray['role'] = $role
                    ? ['id' => $role['id'], 'name' => $role['name']]
                    : null;

                unset($userArray['roles']);
                return $userArray;
            };

            if ($getAll) {
                $users = $query->get();
                $formattedUsers = $users->map($transformUser);

                return response()->json([
                    'status' => true,
                    'message' => 'All users fetched successfully.',
                    'data' => $formattedUsers,
                    'total_users' => $formattedUsers->count(),
                ], 200);
            }

            $users = $query->paginate($perPage, ['*'], 'page', $page);
            $users->getCollection()->transform($transformUser);

            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully (with pagination).',
                'data' => $users->items(),
                'total_users' => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch users.',
                'error' => $e->getMessage(),
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
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image_url' => $user->image_url,
                'role' => $user->getRoleNames()->first(), // ✅ Spatie Role
            ]
        ]);
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role'     => 'required|string|in:user,organizer,admin',
            ]);


            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $role = Role::where([
                ['name', $validated['role']],
                ['guard_name', 'api']
            ])->first();

            if (!$role) {
                $role = Role::create([
                    'name'       => $validated['role'],
                    'guard_name' => 'api',
                ]);
            }


            $user->assignRole($role);

            return response()->json([
                'status'  => true,
                'message' => 'User created and role assigned successfully',
                'data'    => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'role' => $user->getRoleNames()->first(), // ✅ Spatie Role
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'User creation failed',
                'error'   => $e->getMessage()
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

            $validated = $request->validate([
                'name'     => 'sometimes|string|max:255',
                'email'    => 'sometimes|string|email|unique:users,email,' . $user->id,
                'role'     => 'sometimes|in:user,admin',
                'password' => 'sometimes|string|min:6|confirmed',
                'image_url' => 'sometimes|nullable|image|mimes:jpg,jpeg,png',
            ]);

            $updatedFields = [];

            if (isset($validated['name'])) {
                $user->name = $validated['name'];
                $updatedFields['name'] = $user->name;
            }

            if (isset($validated['email'])) {
                $user->email = $validated['email'];
                $updatedFields['email'] = $user->email;
            }

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
                $updatedFields['password'] = '********';
            }
            if ($request->hasFile('image_url')) {
                if ($user->image_url && Storage::disk('public')->exists(str_replace('storage/', '', $user->image_url))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $user->image_url));
                }


                $image = $request->file('image_url');
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/users', $filename, 'public');

                $user->image_url = 'storage/' . $path;
                $updatedFields['image_url'] = $user->image_url;
            }

            $user->save();

            if (isset($validated['role'])) {
                $role = Role::firstOrCreate(
                    ['name' => $validated['role'], 'guard_name' => 'api']
                );
                $user->syncRoles([$role]);
                $updatedFields['role'] = $role->name;
            }

            return response()->json([
                'status'  => true,
                'message' => 'User updated successfully',
                'updated_fields' => $updatedFields
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'User update failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            $user->roles()->detach();

            if ($user->image_url && Storage::disk('public')->exists(str_replace('storage/', '', $user->image_url))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->image_url));
            }


            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
