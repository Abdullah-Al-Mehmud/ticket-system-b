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
    // ✅ List all events (GET /event)

    public function index(Request $request)
    {
        try {
            $query = Event::with('organizer', 'category');

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }


            if ($request->has('search')) {
                $search = strtolower($request->query('search'));
                $query->whereRaw('LOWER(title) LIKE ?', ["%$search%"]);
            }

            $query->orderBy('created_at', 'desc');

            $page = $request->query('page', 1);
            $perPage = $request->query('count', 10);

            if ($page === 'all' || $perPage === 'all') {
                $events = $query->get();
            } else {
                $events = $query->paginate($perPage, ['*'], 'page', $page);
            }

            return response()->json([
                'status' => true,
                'message' => 'Events retrieved successfully',
                'data' => $events->items(),
                'total_event' => $events->total(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
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
    // ✅ Create event (POST /event)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id', // ✅ Updated
            'title' => 'required|string|max:255',
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
                'errors' => $validator->errors()->first()
            ], 422);
        }

        try {
            $event = Event::create([
                'created_by' => Auth::guard('api')->id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
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
                'data' => $event
            ], 201);
        } catch (\Exception $e) {
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
    // ✅ Show single event by ID (GET /event/{id})
    public function show($id)
    {
        try {
            $event = Event::with('organizer', 'category')->find($id);

            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Event details retrieved successfully',
                'data' => $event
            ], 200);
        } catch (\Exception $e) {
            // Optional: Log the error for debugging purposes
            // \Log::error('Error retrieving event (ID: ' . $id . '): ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve event. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
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
    // ✅ Update (PATCH /event/{id})
    public function update(Request $request, $id)
    {
        // 1. Find the event
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // ✅ Updated
            'event_description' => 'required|string',
            'location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'ticket_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'privacy_policy' => 'required|string',
            'image_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->first()
            ], 422);
        }

        try {
            $event->update([
                'title' => $request->title,
                'category_id' => $request->category_id, // ✅ Updated
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
                'message' => 'Event updated successfully',
                'data' => $event
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update event. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // ✅ Delete event (DELETE /event/{id})
    public function destroy($id)
    {
        $event = Event::find($id);


        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }


        try {
            $event->delete();


            return response()->json([
                'status' => true,
                'message' => 'Event deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Optional: Log the error for debugging purposes
            // \Log::error('Error deleting event (ID: ' . $id . '): ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete event. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myEvent()
    {
        try {
            $userId = Auth::id();
            // dd($userId);

            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication required to view your events.'
                ], 401);
            }

            $events = Event::where('created_by', $userId)
                ->with('organizer', 'category')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Your events retrieved successfully',
                'data' => $events
            ], 200);
        } catch (\Exception $e) {
            // Optional: Log the error for debugging purposes
            // \Log::error('Error retrieving events for user ' . Auth::id() . ': ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve your events. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
