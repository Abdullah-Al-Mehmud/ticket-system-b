<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $getAll = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);

            if ($getAll) {
                $categories = Category::latest()->get();
                return response()->json([
                    'status' => true,
                    'message' => 'All categories retrieved successfully',
                    'data' => $categories,
                    'total_categories' => $categories->count()
                ]);
            } else {
                // Continue with pagination
                $page = $request->query('page', 1);
                $perPage = $request->query('count', 10);

                $categories = Category::latest()->paginate($perPage, ['*'], 'page', $page);

                return response()->json([
                    'status' => true,
                    'message' => 'Categories retrieved successfully',
                    'data' => $categories->items(),
                    'total_categories' => $categories->total()
                ]);
            }
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => false, 'message' => 'Category not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
