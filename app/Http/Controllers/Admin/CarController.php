<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CarRent\CarRequest;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function index()
    {
        return view('admin.cars.index', [
            'cars' => Car::with('categoryRes')->latest()->paginate(10),
            'categories' => Category::all()
        ]);
    }


    public function store(CarRequest $request): JsonResponse

    {
        $validated = $request->validated();


        $validated['image'] = $request->file('image')->store('cars', 'public');


        $car = Car::create($validated);

        return $this->apiResponse(
            success: true,
            message: 'Car created successfully',
            data: $car->load('categoryRes')
        );
    }

    public function edit(Car $car): JsonResponse
    {
        return $this->apiResponse(
            success: true,
            data: $car->load('categoryRes')
        );
    }

    public function update(CarRequest $request, Car $car): JsonResponse
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $validated['image'] = $request->file('image')->store('cars', 'public');
        }

        $car->update($validated);

        return $this->apiResponse(
            success: true,
            message: 'Car updated successfully',
            data: $car->load('categoryRes')
        );
    }

    public function destroy(Car $car): JsonResponse
    {

        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return $this->apiResponse(
            success: true,
            message: 'Car deleted successfully'
        );
    }


}
