<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $count = $request->query('count');

            if ($count) {
                // ✅ Pagination enabled
                $page = $request->query('page', 1);
                $ticket = Ticket::with('user', 'event')
                    ->paginate($count, ['*'], 'page', $page);

                return response()->json([
                    'status' => true,
                    'message' => 'Tickets retrieved successfully (paginated)',
                    'data' => $ticket->items(),
                    'total_ticket' => $ticket->total()
                ]);
            } else {
                // ✅ No pagination, return all
                $tickets = Ticket::with('user', 'event')->get();

                return response()->json([
                    'status' => true,
                    'message' => 'All tickets retrieved successfully',
                    'data' => $tickets,
                    'total_ticket' => $tickets->count()
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            // ✅ Step 1: Validate common fields
            $rules = [
                'event_id' => 'required|exists:events,id',
                'ticket_quantity' => 'required|integer|min:1',
            ];

            // ✅ Step 2: If admin → allow optional `user_id`
            if ($user->hasRole('admin')) {
                $rules['user_id'] = 'sometimes|exists:users,id';
            }

            $validatedData = $request->validate($rules, [
                'event_id.required' => 'Event ID is required.',
                'event_id.exists' => 'Event not found.',
                'ticket_quantity.required' => 'Ticket quantity is required.',
                'ticket_quantity.min' => 'Ticket quantity must be at least 1.',
                'user_id.exists' => 'User not found.',
            ]);

            // ✅ Step 3: Determine the ticket's owner
            $userId = $user->id;

            // If admin & user_id is passed → allow override
            if ($user->hasRole('admin') && $request->filled('user_id')) {
                $userId = $request->user_id;
            }

            // ❌ If non-admin tries to send user_id → block
            if (!$user->hasRole('admin') && $request->filled('user_id')) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to set user_id.'
                ], 403);
            }

            // ✅ Step 4: Find event
            $event = Event::findOrFail($validatedData['event_id']);

            // ✅ Step 5: Check existing ticket
            $existingTicket = Ticket::where('user_id', $userId)
                ->where('event_id', $event->id)
                ->first();

            if ($existingTicket) {
                $existingTicket->update([
                    'ticket_quantity' => $existingTicket->ticket_quantity + $validatedData['ticket_quantity'],
                    'purchased_at' => now(),
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Ticket quantity updated successfully.',
                    'data' => $existingTicket
                ], 200);
            } else {
                $ticket = Ticket::create([
                    'user_id' => $userId,
                    'event_id' => $event->id,
                    'ticket_quantity' => $validatedData['ticket_quantity'],
                    'price_per_ticket' => $event->ticket_price,
                    'status' => 'booked',
                    'purchased_at' => now(),
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Ticket booked successfully.',
                    'data' => $ticket
                ], 201);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to book ticket due to a server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $ticket = Ticket::with('user', 'event', 'event.category')->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Ticket retrieved successfully.',
                'data' => $ticket
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve ticket due to a server error.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $validatedData = $request->validate([
                'event_id' => 'sometimes|exists:events,id',
                'ticket_quantity' => 'sometimes|integer|min:1',
                'status' => 'sometimes|string|in:booked,refunded,canceled',
            ], [
                'event_id.exists' => 'The selected event does not exist.',
                'ticket_quantity.integer' => 'The ticket quantity must be a whole number.',
                'ticket_quantity.min' => 'The ticket quantity must be at least 1.',
                'status.in' => 'The status must be one of the allowed values (e.g., booked, refunded, canceled).',
            ]);

            $ticket->update($validatedData);
            return response()->json([
                'status' => true,
                'message' => 'Ticket booking updated successfully.',
                'data' => $ticket
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket booking not found.',
            ], 404);
        } catch (ValidationException $e) {
            // Catches validation errors from $request->validate()
            // Returns all validation errors, which is standard for APIs
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update ticket booking due to an unexpected server error. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
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
            $userId = Auth::id();
            $perPage = $request->query('count', 10);
            $page = $request->query('page', 1);

            // Eager load event and event.category relationships
            $tickets = Ticket::with('event.category')
                ->where('user_id', $userId)
                ->paginate($perPage, ['*'], 'page', $page);

            // Transform the paginated collection to include category name only
            $data = $tickets->getCollection()->transform(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'user_id' => $ticket->user_id,
                    'event_id' => $ticket->event_id,
                    'ticket_quantity' => $ticket->ticket_quantity,
                    'price_per_ticket' => $ticket->price_per_ticket,
                    'status' => $ticket->status,
                    'purchased_at' => $ticket->purchased_at,
                    'created_at' => $ticket->created_at,
                    'updated_at' => $ticket->updated_at,
                    'event' => [
                        'id' => $ticket->event->id,
                        'title' => $ticket->event->title,
                        'category_name' => $ticket->event->category->name ?? null, // category name here
                        'event_description' => $ticket->event->event_description,
                        'location' => $ticket->event->location,
                        'start_date' => $ticket->event->start_date,
                        'end_date' => $ticket->event->end_date,
                        'ticket_price' => $ticket->event->ticket_price,
                        'status' => $ticket->event->status,
                        'privacy_policy' => $ticket->event->privacy_policy,
                        'image_url' => $ticket->event->image_url,
                        'created_at' => $ticket->event->created_at,
                        'updated_at' => $ticket->event->updated_at,
                    ],
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'My tickets retrieved successfully',
                'data' => $data,
                'total' => $tickets->total(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
