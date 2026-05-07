<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $getAll = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);
            $status = $request->query('status');
            $name = $request->query('search');
            $query = Category::query();

            if (!is_null($status)) {
                $query->where('status', $status);
            }

            if (!is_null($name)) {
                // Simple LIKE search for name (case-insensitive)
                $query->where('name', 'LIKE', '%' . $name . '%');
            }

            $query->orderBy('created_at', 'desc');

            if ($getAll) {
                $categories = $query->get();

                return response()->json([
                    'status' => true,
                    'message' => 'All categories retrieved successfully',
                    'data' => $categories,
                    'total_categories' => $categories->count(),
                ]);
            }

            $page = $request->query('page', 1);
            $perPage = $request->query('count', 10);
            $paginated = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $paginated->items(),
                'total_categories' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:categories,name',
                'status' => 'required|in:active,inactive',
            ]);

            $category = Category::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        }
    }

    public function show($id)
    {
        $category = Category::query();

        $category->orderBy('created_at', 'desc');

        $category->find($id);

        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Category not found'], 404);
        }

        return response()->json(['status' => true, 'message' => 'Category show successfully', 'data' => $category], 200);
    }
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|unique:categories,name,' . $category->id,
                'status' => 'sometimes|string|in:active,inactive',
            ], [
                'name.unique' => 'A category with this name already exists.',
                'status.in' => 'The status must be either active or inactive.',
            ]);
            $category->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully.',
                'data' => $category
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update category due to an unexpected server error. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
