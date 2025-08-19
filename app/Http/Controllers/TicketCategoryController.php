<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketCategoryController extends Controller
{

    public function index(Request $request)
    {
        try {
            $getAll = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);
            $page = (int) $request->query('page', 1);
            $count = (int) $request->query('count', 10);
            $search = $request->query('search');

            $query = TicketCategory::with('event')->orderByDesc('id');

            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            if ($getAll) {
                $ticketCategories = $query->get();
                return response()->json([
                    'status' => true,
                    'message' => 'Ticket categories retrieved successfully',
                    'data' => $ticketCategories,
                    'total' => $ticketCategories->count(),
                ]);
            }

            $ticketCategories = $query->paginate($count, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Ticket categories retrieved successfully',
                'data' => $ticketCategories->items(),
                'total' => $ticketCategories->total(),
                'current_page' => $ticketCategories->currentPage(),
                'last_page' => $ticketCategories->lastPage(),
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
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sales_start' => 'nullable|date',
            'sales_end' => 'nullable|date|after_or_equal:sales_start',
            'total_quantity' => 'required|integer|min:0',
            'sold_quantity' => 'nullable|integer|min:0',
            'max_per_purchase' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        try {
            $category = TicketCategory::create($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Ticket category created successfully',
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create ticket category. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function show($id)
    {
        try {
            $category = TicketCategory::with('event', 'tickets')->find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket category not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Ticket category retrieved successfully',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve ticket category.',
                'error' => config('app.debug') ? $e->getMessage() : 'Server Error',
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'sometimes|exists:events,id',
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'sales_start' => 'nullable|date',
            'sales_end' => 'nullable|date|after_or_equal:sales_start',
            'total_quantity' => 'sometimes|integer|min:0',
            'sold_quantity' => 'sometimes|integer|min:0',
            'max_per_purchase' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        try {
            $category = TicketCategory::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket category not found.',
                ], 404);
            }

            $category->update($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Ticket category updated successfully',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update ticket category. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $category = TicketCategory::find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket category not found.',
                ], 404);
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Ticket category deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete ticket category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
