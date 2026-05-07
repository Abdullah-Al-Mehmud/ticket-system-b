<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function getCoupon(Request $request)
    {
        try {
            $getAll = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);
            $status = $request->query('status');
            $search = $request->query('search');

            $query = Coupon::query();

            if (! is_null($status)) {
                $query->where('is_active', $status === 'true');
            }

            if (! is_null($search)) {
                $query->where('code', 'LIKE', '%' . $search . '%');
            }

            $query->orderBy('created_at', 'desc');

            if ($getAll) {
                $coupons = $query->get();

                return response()->json([
                    'status' => true,
                    'message' => 'All coupons retrieved successfully',
                    'data' => $coupons,
                    'total_coupons' => $coupons->count(),
                ]);
            }

            $page = $request->query('page', 1);
            $perPage = $request->query('count', 10);
            $paginated = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => true,
                'message' => 'Coupons retrieved successfully',
                'data' => $paginated->items(),
                'total_coupons' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSingleCoupon($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Coupon retrieved successfully',
                'data' => $coupon,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found.',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function addCoupon(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|unique:coupons,code',
                'description' => 'nullable|string',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|integer|min:1',
                'min_purchase_amount' => 'nullable|integer|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'valid_from' => 'required|date',
                'valid_until' => 'required|date|after:valid_from',
                'is_active' => 'nullable|boolean',
            ], [
                'code.unique' => 'A coupon with this code already exists.',
                'discount_type.in' => 'The discount type must be either percentage or fixed.',
                'valid_until.after' => 'The valid until date must be after the valid from date.',
            ]);

            $coupon = Coupon::create([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'min_purchase_amount' => $validated['min_purchase_amount'] ?? null,
                'max_uses' => $validated['max_uses'] ?? null,
                'used_count' => 0,
                'valid_from' => $validated['valid_from'],
                'valid_until' => $validated['valid_until'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Coupon created successfully',
                'data' => $coupon,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create coupon due to an unexpected server error. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCoupon(Request $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            $validated = $request->validate([
                'code' => 'sometimes|string|unique:coupons,code,' . $coupon->id,
                'description' => 'nullable|string',
                'discount_type' => 'sometimes|in:percentage,fixed',
                'discount_value' => 'sometimes|integer|min:1',
                'min_purchase_amount' => 'nullable|integer|min:0',
                'max_uses' => 'nullable|integer|min:1',
                'valid_from' => 'sometimes|date',
                'valid_until' => 'sometimes|date',
                'is_active' => 'nullable|boolean',
            ], [
                'code.unique' => 'A coupon with this code already exists.',
                'discount_type.in' => 'The discount type must be either percentage or fixed.',
                'valid_until.after' => 'The valid until date must be after the valid from date.',
            ]);

            $updateData = [];

            if (isset($validated['code'])) {
                $updateData['code'] = strtoupper($validated['code']);
            }
            if (isset($validated['description'])) {
                $updateData['description'] = $validated['description'];
            }
            if (isset($validated['discount_type'])) {
                $updateData['discount_type'] = $validated['discount_type'];
            }
            if (isset($validated['discount_value'])) {
                $updateData['discount_value'] = $validated['discount_value'];
            }
            if (array_key_exists('min_purchase_amount', $validated)) {
                $updateData['min_purchase_amount'] = $validated['min_purchase_amount'];
            }
            if (array_key_exists('max_uses', $validated)) {
                $updateData['max_uses'] = $validated['max_uses'];
            }
            if (isset($validated['valid_from'])) {
                $updateData['valid_from'] = $validated['valid_from'];
            }
            if (isset($validated['valid_until'])) {
                $updateData['valid_until'] = $validated['valid_until'];
            }
            if (array_key_exists('is_active', $validated)) {
                $updateData['is_active'] = $validated['is_active'];
            }

            $coupon->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Coupon updated successfully.',
                'data' => $coupon->fresh(),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update coupon due to an unexpected server error. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteCoupon($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            $coupon->delete();

            return response()->json([
                'status' => true,
                'message' => 'Coupon deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found.',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function validateCoupon(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string',
                'order_amount' => 'nullable|integer|min:0',
            ]);

            $code = strtoupper($validated['code']);
            $orderAmount = $validated['order_amount'] ?? 0;

            $coupon = Coupon::where('code', $code)->first();

            if (! $coupon) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid coupon code. Please check and try again.',
                ], 404);
            }

            if (! $coupon->is_active) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon is no longer active.',
                ], 400);
            }

            $now = now();
            if ($coupon->valid_from && $now->lt($coupon->valid_from)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon is not yet valid. It will be available from ' . $coupon->valid_from->format('Y-m-d') . '.',
                ], 400);
            }

            if ($coupon->valid_until && $now->gt($coupon->valid_until)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon has expired. It was valid until ' . $coupon->valid_until->format('Y-m-d') . '.',
                ], 400);
            }

            if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon has reached its maximum usage limit.',
                ], 400);
            }

            if ($coupon->min_purchase_amount !== null && $orderAmount < $coupon->min_purchase_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Minimum purchase amount of ৳' . number_format($coupon->min_purchase_amount) . ' required to use this coupon.',
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Coupon validated successfully',
                'data' => [
                    'discount_type' => $coupon->discount_type,
                    'discount_value' => $coupon->discount_value,
                    'code' => $coupon->code,
                    'description' => $coupon->description,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
