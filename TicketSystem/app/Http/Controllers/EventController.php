<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = Event::with('category','organizer');

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            $query->orderBy('id', 'desc');

            if ($request->boolean('all')) {
                $events = $query->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Events retrieved successfully',
                    'data' => $events,
                    'total' => $events->count(),
                ]);
            }

            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('count', 10);
            $events = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Events retrieved successfully',
                'data' => $events->items(),
                'total' => $events->total(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => config('app.debug') ? $e->getMessage() : 'Server Error',
            ], 500);
        }
    }







    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'event_description' => 'required|string',
            'location'          => 'required|string',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'privacy_policy'    => 'required|string',
            'image_url'         => 'nullable|url',
            'status'            => 'nullable|in:Upcoming,Live,Done,Cancelled',
            'category_id'       => 'required|exists:categories,id',
            'created_by'        => 'sometimes|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->first()
            ], 422);
        }

        try {
            $createdBy = $user->id;

            if ($user->hasRole('admin') && $request->filled('created_by')) {
                $createdBy = $request->created_by;
            }

            if (!$user->hasRole('admin') && $request->filled('created_by')) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not allowed to set created_by.'
                ], 403);
            }

            $event = Event::create([
                'created_by'        => $createdBy,
                'category_id'       => $request->category_id,
                'title'             => $request->title,
                'event_description' => $request->event_description,
                'location'          => $request->location,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'privacy_policy'    => $request->privacy_policy,
                'image_url'         => $request->image_url,
                'status'            => $request->status ?? 'Upcoming',
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


    public function show($id)
    {
        try {
            $event = Event::with(['organizer', 'category', 'ticketCategories'])->find($id);

            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Event details retrieved successfully',
                'data' => $event,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve event. An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $user = Auth::guard('api')->user();

        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found.'
            ], 404);
        }

        if (!$user->hasRole('admin') && $event->created_by !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to update this event.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'             => 'sometimes|required|string|max:255',
            'event_description' => 'sometimes|required|string',
            'location'          => 'sometimes|required|string',
            'start_date'        => 'sometimes|required|date',
            'end_date'          => 'sometimes|required|date|after_or_equal:start_date',
            'privacy_policy'    => 'sometimes|required|string',
            'image_url'         => 'sometimes|nullable|url',
            'status'            => 'sometimes|required|in:Upcoming,Live,Done,Cancelled',
            'category_id'       => 'sometimes|required|exists:categories,id',
            'created_by'        => 'prohibited',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->first()
            ], 422);
        }

        try {
            $event->update($request->only([
                'title',
                'event_description',
                'location',
                'start_date',
                'end_date',
                'privacy_policy',
                'image_url',
                'status',
                'category_id'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Event updated successfully!',
                'data' => $event
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



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
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete event. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myEvent(Request $request)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication required to view your events.'
                ], 401);
            }

            $query = Event::with('category')->where('created_by', $userId);
            $query->orderBy('created_at', 'desc');

            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('count', 10);

            $events = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Your events retrieved successfully',
                'data' => $events->items(),
                'total' => $events->total(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve your events. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
