<?php

namespace App\Http\Controllers\Api\CarRent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CarRent\CarRequest;
use App\Http\Resources\CarRent\CarResource;
use App\Models\Car;

class CarController extends Controller
{
    public function store(CarRequest $request){
        $data = $request->validated();
        $path = $request->file('image')->store('cars', 'public');
        $data['image'] = $path;
        $car = Car::create($data);
        return $this->apiResponse(true, 'Car created successfully', new CarResource($car));

    }

    public function index(){
        $cars = Car::all();
        return $this->apiResponse(true, 'Cars fetched successfully', CarResource::collection($cars));
    }

    public function show(Car $car){
        return $this->apiResponse(true, 'Car fetched successfully', new CarResource($car));
    }

    public function update(CarRequest $request, Car $car){
        $data = $request->validated();
        $path = $request->file('image')->store('cars', 'public');
        $data['image'] = $path;
        $car->update($data);
        return $this->apiResponse(true, 'Car updated successfully', new CarResource($car));
    }
    public function destroy(Car $car){
        $car->delete();
        return $this->apiResponse(true, 'Car deleted successfully');
    }

}
