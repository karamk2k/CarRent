<?php

namespace App\Http\Controllers\Api\CarRent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CarRent\CarRequest;
use App\Http\Resources\CarRent\CarResource;
use App\Models\Car;

class CarController extends Controller
{


    public function index(){
        $cars = Car::all();
        return $this->apiResponse(true, 'Cars fetched successfully', CarResource::collection($cars));
    }

    public function show(Car $car){
        return $this->apiResponse(true, 'Car fetched successfully', new CarResource($car));
    }

    public function show_blade(Car $car){
        return view('cars.car', [
            'car' => $car
        ]);
    }

}
