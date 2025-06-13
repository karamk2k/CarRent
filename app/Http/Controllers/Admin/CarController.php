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


    public function store(CarRequest $request): JsonResponse // Assuming you want CarRequest for validation
    // OR public function store(Request $request): JsonResponse // If using generic Request
    {
        $validated = $request->validated(); // If using CarRequest
        // OR $validated = $request->all(); // If using generic Request and manually validating or just inspecting

        // --- DEBUGGING START ---
        // Log the entire validated data array
        Log::info('Car store - Validated data:', $validated);

        // Log the status of the image file specifically
        if ($request->hasFile('image')) {
            Log::info('Car store - Image file detected:', [
                'name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'mime' => $request->file('image')->getMimeType(),
                'isValid' => $request->file('image')->isValid(),
            ]);
        } else {
            Log::warning('Car store - No image file found in request.');
        }
        // --- DEBUGGING END ---

        // Now, keep your original logic that might be causing the 500
        // This is the line that was likely line 28 in your original error:
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
        // Delete car image if exists
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
