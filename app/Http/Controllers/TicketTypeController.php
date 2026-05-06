<?php

namespace App\Http\Controllers;

use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $getAll = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);
            $page = (int) $request->query('page', 1);
            $count = (int) $request->query('count', 10);
            $search = $request->query('search');

            $query = TicketType::orderByDesc('id');

            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            if ($getAll) {
                $ticketTypes = $query->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Ticket types retrieved successfully',
                    'data' => $ticketTypes,
                    'total' => $ticketTypes->count(),
                ]);
            }

            $ticketTypes = $query->paginate($count, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Ticket types retrieved successfully',
                'data' => $ticketTypes->items(),
                'total' => $ticketTypes->total(),
                'current_page' => $ticketTypes->currentPage(),
                'last_page' => $ticketTypes->lastPage(),
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        try {
            $ticketType = TicketType::create($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Ticket type created successfully',
                'data' => $ticketType,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create ticket type. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $ticketType = TicketType::with('ticketCategories')->find($id);

            if (! $ticketType) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket type not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Ticket type retrieved successfully',
                'data' => $ticketType,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve ticket type.',
                'error' => config('app.debug') ? $e->getMessage() : 'Server Error',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        try {
            $ticketType = TicketType::find($id);

            if (! $ticketType) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket type not found.',
                ], 404);
            }

            $ticketType->update($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Ticket type updated successfully',
                'data' => $ticketType,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update ticket type. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ticketType = TicketType::find($id);

            if (! $ticketType) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket type not found.',
                ], 404);
            }

            $ticketType->delete();

            return response()->json([
                'status' => true,
                'message' => 'Ticket type deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete ticket type.',
                'error' => config('app.debug') ? $e->getMessage() : 'Server Error',
            ], 500);
        }
    }
}
