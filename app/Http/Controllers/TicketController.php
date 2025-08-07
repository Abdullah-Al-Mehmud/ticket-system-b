<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        try {
            $getAll = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);
            $page = (int) $request->query('page', 1);
            $count = $request->query('count', 10);
            $status = $request->query('status');
            $search = $request->query('search');

            $query = Ticket::with([
                'user',
                'ticketCategory',
                'ticketCategory.event'
            ])->orderByDesc('id');

            if ($status) {
                $query->where('status', $status);
            }

            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }
            if ($getAll) {
                $tickets = $query->get();
                return response()->json([
                    'status' => true,
                    'message' => 'All tickets retrieved successfully',
                    'data' => $tickets,
                    'total' => $tickets->count(),
                ]);
            }

            $tickets = $query->paginate((int) $count, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Tickets retrieved successfully',
                'data' => $tickets->items(),
                'total' => $tickets->total(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function store(Request $request)
    {
        try {
            $authUser = Auth::guard('api')->user();

            $validated = $request->validate([
                'ticket_category_id' => 'required|exists:ticket_categories,id',
                'quantity' => 'required|integer|min:1',
                'status' => 'sometimes|in:Confirmed,Cancelled,Refunded',
                'user_id' => 'sometimes|exists:users,id',
            ]);

            $userId = isset($validated['user_id']) && $authUser->hasRole('admin')
                ? $validated['user_id']
                : $authUser->id;

            $existingTicket = Ticket::where('user_id', $userId)
                ->where('ticket_category_id', $validated['ticket_category_id'])
                ->where('status', $validated['status'] ?? 'Confirmed')
                ->first();

            if ($existingTicket) {
                $existingTicket->quantity += $validated['quantity'];
                $existingTicket->save();
                $ticket = $existingTicket;
            } else {
                $ticket = Ticket::create([
                    'user_id' => $userId,
                    'ticket_category_id' => $validated['ticket_category_id'],
                    'quantity' => $validated['quantity'],
                    'status' => $validated['status'] ?? 'Confirmed',
                ]);
            }

            $TicketCategory = TicketCategory::find($validated['ticket_category_id']);
            $TicketCategory->sold_quantity += $validated['quantity'];
            $TicketCategory->save();

            return response()->json([
                'status' => true,
                'message' => $existingTicket ? 'Ticket updated successfully' : 'Ticket created successfully',
                'data' => $ticket
            ], $existingTicket ? 200 : 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create/update ticket', 'error' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {
            $ticket = Ticket::with([
                'ticketCategory:id,event_id,name,price',
                'ticketCategory.event:id,title,location,start_date,end_date,category_id',
                'ticketCategory.event.category:id,name'
            ])->findOrFail($id);

            $event = $ticket->ticketCategory?->event;

            $ticketPrice = $ticket->ticketCategory?->price;

            $response = [
                'ticket_id' => $ticket->id,
                'ticket_number' => 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT),
                'quantity' => $ticket->quantity,
                'status' => $ticket->status,
                'event' => [
                    'title' => $event?->title,
                    'location' => $event?->location,
                    'start_date' => $event?->start_date,
                    'end_date' => $event?->end_date,
                    'category' => [
                        'name' => $event?->category?->name
                    ],
                ],
                'price_per_ticket' => number_format($ticketPrice, 2),
                'total_price' => number_format($ticketPrice * $ticket->quantity, 2),
            ];

            return response()->json([
                'status' => true,
                'message' => 'Ticket data retrieved successfully.',
                'data' => $response
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }







    public function update(Request $request, $id)
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user->hasRole('admin')) {
                return response()->json(['status' => false, 'message' => 'Unauthorized. Only admins can update tickets.'], 403);
            }

            $validated = $request->validate([
                'ticket_category_id' => 'required|exists:ticket_categories,id',
                'quantity' => 'required|integer|min:1',
                'status' => 'sometimes|in:Confirmed,Cancelled,Refunded',
            ]);
            $ticket = Ticket::where('id', $id)->first();

            // dd($ticket);
            if (!$ticket) {
                return response()->json(['status' => false, 'message' => 'Ticket not found'], 404);
            }

            $oldQuantity = $ticket->quantity;
            $oldCategoryId = $ticket->ticket_category_id;


            $ticket->ticket_category_id = $validated['ticket_category_id'];
            $ticket->quantity = $validated['quantity'];
            $ticket->status = $validated['status'] ?? $ticket->status;
            $ticket->save();

            if ($oldCategoryId != $validated['ticket_category_id']) {
                $oldCategory = TicketCategory::find($oldCategoryId);
                $oldCategory->sold_quantity -= $oldQuantity;
                $oldCategory->save();

                $newCategory = TicketCategory::find($validated['ticket_category_id']);
                $newCategory->sold_quantity += $validated['quantity'];
                $newCategory->save();
            } else {
                $difference = $validated['quantity'] - $oldQuantity;
                $category = TicketCategory::find($validated['ticket_category_id']);
                $category->sold_quantity += $difference;
                $category->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Ticket updated successfully',
                'data' => $ticket
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update ticket', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        $ticket = Ticket::find($id);


        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found'
            ], 404);
        }


        try {
            $ticket->delete();


            return response()->json([
                'status' => true,
                'message' => 'Ticket deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Optional: Log the error for debugging purposes
            // \Log::error('Error deleting event (ID: ' . $id . '): ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete ticket. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myTickets(Request $request)
    {
        try {
            $requestedUserId = (int) $request->query('user_id');
            $authUser = Auth::guard('api')->user();
            $userId = $requestedUserId ?: ($authUser ? $authUser->id : null);

            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication or valid user_id is required to view tickets.'
                ], 401);
            }

            $perPage = (int) $request->query('count', 10);
            $page = (int) $request->query('page', 1);

            $tickets = Ticket::with(['ticketCategory', 'ticketCategory.event'])
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Tickets retrieved successfully.',
                'data' => $tickets->items(),
                'total' => $tickets->total(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve tickets.',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error',
            ], 500);
        }
    }
}
