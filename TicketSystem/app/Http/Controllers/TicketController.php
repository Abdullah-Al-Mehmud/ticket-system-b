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
            $page = $request->query('page', 1);
            $perPage = $request->query('count', 10);

            $ticket = ticket::with('user', 'event')->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'ticket retrieved successfully',
                'data' => $ticket->items(),
                'total_event' => $ticket->total()

            ]);
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
            $validatedData = $request->validate([
                'event_id' => 'required|exists:events,id',
                'ticket_quantity' => 'required|integer|min:1',
            ], [
                'event_id.required' => 'Event id required.',
                'event_id.exists' => 'Event not found.',
                'ticket_quantity.required' => 'Ticket quantity is required.',
                'ticket_quantity.min' => 'Ticket quantity must be at least 1.',
            ]);

            $userId = Auth::id();

            $event = Event::findOrFail($validatedData['event_id']);

            // Check if the user already has a ticket for this event
            $existingTicket = Ticket::where('user_id', $userId)
                ->where('event_id', $event->id)
                ->first();

            if ($existingTicket) {
                // Update existing ticket quantity (or do other logic like adding to existing)
                $existingTicket->update([
                    'ticket_quantity' => $existingTicket->ticket_quantity + $validatedData['ticket_quantity']
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Ticket quantity updated successfully.',
                    'data' => $existingTicket
                ], 200);
            } else {
                // Create new ticket
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
    public function show(Ticket $ticket)
    {
        //
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
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function myTickets(Request $request)
    {
        try {
            $userId = Auth::id();
            $perPage = $request->query('count', 10);
            $page = $request->query('page', 1);

            $tickets = Ticket::with('event')
                ->where('user_id', $userId)
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'My tickets retrieved successfully',
                'data' => $tickets->items(),
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
