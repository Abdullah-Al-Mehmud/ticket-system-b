<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'event_description' => 'required|string',
            'location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'ticket_price' => 'required|numeric|min:0',
            'status' => 'required',
            'privacy_policy' => 'required|string',
            'image_url' => 'required|url',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $event = Event::create([
                'created_by' => Auth::guard('api')->id(),
                'title' => $request->title,
                'category' => $request->category,
                'event_description' => $request->event_description,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'ticket_price' => $request->ticket_price,
                'status' => $request->status,
                'privacy_policy' => $request->privacy_policy,
                'image_url' => $request->image_url,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Event created successfully!',
                'event' => $event
            ], 201);
        } catch (\Exception $e) {
            // Optional: Log the error for debugging
            // \Log::error('Error creating event: ' . $e->getMessage());

            // Return a more generic error message to the client
            return response()->json([
                'status' => false,
                'message' => 'Failed to create event. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
