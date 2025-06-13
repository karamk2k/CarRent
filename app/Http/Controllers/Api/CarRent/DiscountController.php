<?php

namespace App\Http\Controllers\Api\CarRent;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Resources\CarRent\DiscountResource;
use App\Http\Requests\CarRent\DiscountRequest;
use Carbon\Carbon;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::where('end_date', '>', Carbon::now())->get();
        return $this->apiResponse(true, 'Discounts fetched successfully', DiscountResource::collection($discounts));
    }


    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return $this->apiResponse(true, 'Discount fetched successfully', new DiscountResource($discount));
    }



    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */

    public function check($code)
    {
        $discount = Discount::where('name', $code)
            ->where('end_date', '>=', now())
            ->first();

        if (!$discount) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired discount code'
            ]);
        }

        return response()->json([
            'valid' => true,
            'percentage' => $discount->percentage,
            'message' => 'Discount code applied successfully'
        ]);
    }
}
