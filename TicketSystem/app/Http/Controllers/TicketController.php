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
            $count = $request->query('count');
            $tickets = $count
                ? Ticket::with('user', 'ticketCategory')->paginate($count)
                : Ticket::with('user', 'ticketCategory')->get();

            return response()->json([
                'status' => true,
                'message' => 'Tickets retrieved successfully',
                'data' => $tickets,
                'total' => $count ? $tickets->total() : count($tickets)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error fetching tickets', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            $validated = $request->validate([
                'ticket_category_id' => 'required|exists:ticket_categories,id',
                'quantity' => 'required|integer|min:1',
                'status' => 'sometimes|in:Confirmed,Cancelled,Refunded',
            ]);

            $existingTicket = Ticket::where('user_id', $user->id)
                ->where('ticket_category_id', $validated['ticket_category_id'])
                ->where('status', $validated['status'] ?? 'Confirmed')
                ->first();

            if ($existingTicket) {

                $existingTicket->quantity += $validated['quantity'];
                $existingTicket->save();

                $ticket = $existingTicket;
            } else {
                $ticket = Ticket::create([
                    'user_id' => $user->id,
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
            $user = Auth::guard('api')->user();
            $perPage = $request->query('count', 10);

            $tickets = Ticket::with('ticketCategory', 'ticketCategory.event')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'message' => 'My tickets retrieved successfully',
                'data' => $tickets->items(),
                'total' => $tickets->total()
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to retrieve your tickets', 'error' => $e->getMessage()], 500);
        }
    }
}
