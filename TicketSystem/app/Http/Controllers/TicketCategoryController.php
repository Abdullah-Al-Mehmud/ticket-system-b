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
            $query = TicketCategory::with(['event']);


            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $query->orderBy('created_at', 'desc');

            if ($request->boolean('all')) {
                $ticketCategories = $query->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Ticket categories retrieved successfully',
                    'data' => $ticketCategories,
                    'total' => $ticketCategories->count(),
                ]);
            }

            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('count', 10);

            $ticketCategories = $query->paginate($perPage, ['*'], 'page', $page);

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
            'sold_quantity' => 'required|integer|min:0',
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


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sales_start' => 'nullable|date',
            'sales_end' => 'nullable|date|after_or_equal:sales_start',
            'total_quantity' => 'required|integer|min:0',
            'sold_quantity' => 'required|integer|min:0',
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
