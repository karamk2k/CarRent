<?php

namespace App\Http\Controllers\Api\CarRent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Http\Resources\CarRent\RentalResource;
use App\Http\Requests\CarRent\CreateRentalRequest;
use App\Services\RentalService;

class RentalController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    public function store(CreateRentalRequest $request)
    {
        try {
            $result = $this->rentalService->createRental($request->validated(), auth()->user());
            
            return $this->apiResponse(true, 'Rental created successfully', [
                'rental' => new RentalResource($result['rental']),
                'client_secret' => $result['client_secret']
            ]);
        } catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function confirmPayment(Rental $rental)
    {
        try {
            $rental = $this->rentalService->confirmPayment($rental);
            return $this->apiResponse(true, 'Payment confirmed and rental activated', new RentalResource($rental));
        } catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function cancelRental(Rental $rental)
    {
        try {
            $rental = $this->rentalService->cancelRental($rental);
            return $this->apiResponse(true, 'Rental cancelled successfully', new RentalResource($rental));
        } catch (\Exception $e) {
            return $this->apiResponse(false, $e->getMessage(), null, 500);
        }
    }
}
