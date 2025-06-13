<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Http\Resources\RentalResource;
use App\Services\RentalService;

class RentalController extends Controller
{
    public function __construct(protected RentalService $rentalService)
    {

    }
    public function index()
    {
        $rentals = Rental::with('user', 'car')->paginate(10);
        return view('admin.rentals.index', compact('rentals'));
    }
    public function updateConfirmation( Rental $rental)
    {
        if($rental->status !== 'pending') {
            return $this->apiResponse(false, 'Rental status is not pending', null, 400);
        }
        $rental = $this->rentalService->confirmPayment($rental);
        return $this->apiResponse(true, 'Rental status updated successfully', $rental);
    }
    public function updateCancellation( Rental $rental)
    {
        if($rental->status !== 'pending') {
            return $this->apiResponse(false, 'Rental status is not pending', null, 400);
        }
        $rental = $this->rentalService->cancelRental($rental);
        return $this->apiResponse(true, 'Rental status updated successfully', $rental);
    }
    public function updateCompleted( Rental $rental)
    {
        if($rental->status !== 'confirmed') {
            return $this->apiResponse(false, 'Rental status is not confirmed', null, 400);
        }
        $rental = $this->rentalService->completeRental($rental);
        return $this->apiResponse(true, 'Rental status updated successfully', $rental);
    }

}
