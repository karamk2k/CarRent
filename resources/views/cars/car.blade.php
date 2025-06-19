@extends('layouts.app')
@section('title', 'Car Details - ' . $car->name)
@section('content')

<div class="container mx-auto my-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $car->name }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="w-full h-auto rounded-lg">
            </div>
            <div>
                <p class="text-gray-700 mb-4">{{ $car->description }}</p>

                <p><span class="font-semibold">Model:</span> {{ $car->model }}</p>
                <p><span class="font-semibold">Color:</span> {{ $car->color }}</p>
                <p><span class="font-semibold">Year:</span> {{ $car->year }}</p>
                <p><span class="font-semibold">Price:</span> ${{ number_format($car->price, 2) }}</p>
                <p><span class="font-semibold">Category:</span> {{ $car->category }}</p>
                <p><span class="font-semibold">Transmission:</span> {{ $car->transmissions }}</p>
                <p><span class="font-semibold">Seats:</span> {{ $car->seats }}</p>
                <p><span class="font-semibold">Fuel Type:</span> {{ $car->fuel_type }}</p>
                <p><span class="font-semibold">Fuel Capacity:</span> {{ number_format($car->fuel_capacity) }} miles</p>
                <p><span class="font-semibold">Available At:</span> {{ \Carbon\Carbon::parse($car->available_at)->format('F j, Y') }}</p>
                <a href="{{ route('home') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition mt-4">
                    Back to Cars
                </a>

            </div>
        </div>
    </div>
</div>

@endsection
