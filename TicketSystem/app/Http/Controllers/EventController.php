<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventOrganizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = Event::with('category', 'organizers', 'creator');

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
            'image_url'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
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
            // Image Upload
            $imageUrl = null;
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/events', $filename, 'public');
                $imageUrl = 'storage/' . $path;
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
                'image_url'         => $imageUrl,
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
            $event = Event::with(['organizers', 'creator', 'category', 'ticketCategories'])->find($id);

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
            'image_url'         => 'sometimes|nullable|image|mimes:jpeg,png,jpg',
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
            $updateData = $request->only([
                'title',
                'event_description',
                'location',
                'start_date',
                'end_date',
                'privacy_policy',
                'status',
                'category_id'
            ]);

            if ($request->hasFile('image_url')) {
                if ($event->image_url && Storage::disk('public')->exists(str_replace('storage/', '', $event->image_url))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $event->image_url));
                }

                $image = $request->file('image_url');
                $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('uploads/events', $filename, 'public');

                $updateData['image_url'] = 'storage/' . $path;
            }

            $event->update($updateData);

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
            if ($event->image_url) {
                $imagePath = str_replace('storage/', '', $event->image_url);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $event->delete();

            return response()->json([
                'status' => true,
                'message' => 'Event and associated image deleted successfully'
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

            $perPage = (int) $request->get('count', 10);
            $page = (int) $request->get('page', 1);

            // Only those events where user is an organizer
            $events = Event::with(['category', 'organizers'])
                ->whereHas('organizers', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Your organized events retrieved successfully',
                'data' => $events->items(),
                'total' => $events->total(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve your organized events. An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function assignOrganizers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|integer|exists:events,id',
                'user_id' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()->first(),
                ], 422);
            }

            $validated = $validator->validated();

            $eventId = $validated['event_id'];
            $userId = $validated['user_id'];

            $event = Event::find($eventId);
            if (!$event) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event not found.',
                ], 404);
            }

            $alreadyExists = EventOrganizer::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyExists) {
                return response()->json([
                    'status' => false,
                    'message' => 'User is already an organizer for this event.',
                ], 200);
            }

            EventOrganizer::create([
                'event_id' => $eventId,
                'user_id' => $userId,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Organizer assigned successfully.',
                'data' => [
                    'event_id' => $eventId,
                    'user_id' => $userId,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
