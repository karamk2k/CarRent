<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Http\Requests\CarRent\DiscountRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CarRent\DiscountResource;
class DiscountController extends Controller
{
    // Show all discounts (for admin view)

    public function index()
    {
        return view('admin.discounts.index');
    }

    public function getDiscounts()
    {
        $discounts = Discount::latest()->paginate(10);
        return $this->apiResponse(true, 'Discounts fetched successfully', DiscountResource::collection($discounts));
    }

    // Show create discount form (for admin view)


    // Add a new discount
    public function store(DiscountRequest $request): JsonResponse
    {
        $discount = Discount::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Discount added successfully',
            'data' => new DiscountResource($discount)
        ]);
    }

    // Delete a discount
    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();
        return response()->json([
            'success' => true,
            'message' => 'Discount deleted successfully'
        ]);
    }
}
