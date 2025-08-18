<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                'ticketCategory.event.category:id,name',
                'user',
            ])->findOrFail($id);

            $event = $ticket->ticketCategory?->event;

            $ticketPrice = $ticket->ticketCategory?->price;
            $ticket_category_id = $ticket->ticketCategory?->id;
            $ticket_category_name = $ticket->ticketCategory?->name;

            $response = [
                'ticket_id' => $ticket->id,
                'ticket_category_id' => $ticket_category_id,
                'ticket_number' => 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT),
                'quantity' => $ticket->quantity,
                'status' => $ticket->status,
                'event' => [
                    'id' => $event->id,
                    'title' => $event?->title,
                    'location' => $event?->location,
                    'start_date' => $event?->start_date,
                    'end_date' => $event?->end_date,
                    'category' => [
                        'name' => $event?->category?->name
                    ],
                ],
                'ticket_category_name' => $ticket_category_name,
                'price_per_ticket' => number_format($ticketPrice, 2),
                'total_price' => number_format($ticketPrice * $ticket->quantity, 2),
                'user' => [
                    'id' => $ticket->user?->id,
                    'name' => $ticket->user?->name,
                    'email' => $ticket->user?->email,
                ],
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

    public function checkTicket(Request $request)
    {
        try {
            $loggedInUser = Auth::guard('api')->user();

            $validatedData = $request->validate([
                'user_name' => 'required',
                'event_id' => 'required',
                'ticket_id' => 'required',
            ]);

            // Step 2: Check if logged-in user is organizer of the given event
            $isOrganizer = EventOrganizer::where('event_id', $validatedData['event_id'])
                ->where('user_id', $loggedInUser->id)
                ->exists();

            if (!$isOrganizer) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to check tickets for this event.',
                ], 403);
            }

            $ticket = Ticket::with(['user', 'ticketCategory.event'])
                ->where('id', $validatedData['ticket_id'])
                ->whereHas('ticketCategory.event', function ($query) use ($validatedData) {
                    $query->where('id', $validatedData['event_id']);
                })
                ->whereHas('user', function ($query) use ($validatedData) {
                    $query->where('name', $validatedData['user_name']);
                })
                ->first();

            if (!$ticket) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket not found for the given user and event.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Ticket found.',
                'data' => [
                    'user_name' => $ticket->user->name ?? null,
                    'event_name' => $ticket->ticketCategory->event->title ?? null,
                    'is_verify' => $ticket->is_verify,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
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


    public function verifyTicket(Request $request)
    {
        try {
            $loggedInUser = Auth::guard('api')->user();
            $validatedData = $request->validate([
                'user_name' => 'required',
                'event_id' => 'required',
                'ticket_id' => 'required'
            ]);
            $isOrganizer = EventOrganizer::where('event_id', $validatedData['event_id'])
                ->where('user_id', $loggedInUser->id)
                ->exists();

            if (!$isOrganizer) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to check tickets for this event.',
                ], 403);
            }

            $ticket = Ticket::with(['user', 'ticketCategory.event'])
                ->where('id', $validatedData['ticket_id'])
                ->whereHas('ticketCategory.event', function ($query) use ($validatedData) {
                    $query->where('id', $validatedData['event_id']);
                })
                ->first();

            if (!$ticket) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket not found or does not belong to this event.'
                ], 404);
            }

            if ($ticket->is_verify) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket has already been verified.'
                ], 400);
            }

            $ticket->update(['is_verify' => true]);

            $ticketData = [
                'user_name' => $ticket->user->name ?? null,
                'event_name' => $ticket->ticketCategory->event->title ?? null,
                'is_verify' => $ticket->is_verify,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Ticket successfully verified.',
                'ticket' => $ticketData
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function download($id)
    {
        try {
            $ticket = Ticket::with([
                'ticketCategory:id,event_id,name,price',
                'ticketCategory.event:id,title,location,start_date,end_date,category_id',
                'ticketCategory.event.category:id,name',
                'user',
            ])->findOrFail($id);

            $event = $ticket->ticketCategory?->event;
            $ticketPrice = $ticket->ticketCategory?->price;
            $ticketCategoryId = $ticket->ticketCategory?->id;
            $ticketCategoryName = $ticket->ticketCategory?->name;

            $ticketData = (object) [
                'ticket_id' => $ticket->id,
                'ticket_category_id' => $ticketCategoryId,
                'ticket_number' => 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT),
                'quantity' => $ticket->quantity,
                'status' => $ticket->status,
                'event' => $event ? (object) [
                    'id' => $event->id,
                    'title' => $event->title,
                    'location' => $event->location,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'category' => $event->category ? (object) ['name' => $event->category->name] : null,
                ] : null,
                'ticket_category_name' => $ticketCategoryName,
                'price_per_ticket' => number_format($ticketPrice ?? 0, 2),
                'total_price' => number_format(($ticketPrice ?? 0) * $ticket->quantity, 2),
                'user' => $ticket->user ? (object) [
                    'id' => $ticket->user->id,
                    'name' => $ticket->user->name,
                    'email' => $ticket->user->email,
                ] : null,
            ];
            $qrPayload = json_encode([
                'ticket_id' => $ticketData->ticket_id,
                'user_name' => $ticketData->user->name ?? '',
                'event_id' => $ticketData->event->id ?? '',
            ]);

            $qrImage = $this->generateFromPayload($qrPayload);
            $ticket = $ticketData;

            $pdf = Pdf::loadView('tickets.template', compact('ticket', 'qrImage'))->setPaper('a4', 'landscape');

            return $pdf->download('ticket_' . $ticketData->ticket_number . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate ticket PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function generateFromPayload($qrPayload)
    {
        try {
            $qrPng = QrCode::format('svg')->size(200)->generate($qrPayload);
            $qrImage = base64_encode($qrPng);

            if (!$qrPng) {
                return response()->json([
                    'message' => 'QR Code generation failed',
                ], 500);
            }

            return $qrImage;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return null;
        }
    }

    // public function generateFromPayload($qrPayload)
    // {
    //     $filename = 'qr_' . time() . '_' . Str::random(6) . '.svg';
    //     $path = 'public/uploads/' . $filename;

    //     try {
    //         // QR code generate to variable first
    //         $qrContent = QrCode::format('svg')->size(200)->generate($qrPayload);
    //         if (!$qrContent) {
    //             return response()->json([
    //                 'message' => 'QR Code generation failed',
    //             ], 500);
    //         }
    //         // dd($qrContent);

    //         file_put_contents(storage_path('app/' . $path), $qrContent);
    //         $url = asset(str_replace('public/', 'storage/', $path));
    //         return  $qrContent;
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }
}
