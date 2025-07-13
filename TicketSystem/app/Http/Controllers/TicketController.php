<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'ticket' => $ticket
            ], 201);
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
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
