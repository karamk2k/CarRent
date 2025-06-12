@props(['car'])

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="relative">
        <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="w-full h-48 object-cover">
        <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-sm font-medium">
            {{ $car->type }}
        </div>
    </div>
    
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">{{ $car->name }}</h3>
                <p class="text-gray-500">{{ $car->brand }}</p>
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="ml-1 text-gray-600">{{ number_format($car->rating, 1) }}</span>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                {{ $car->transmission }}
            </div>
            <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $car->fuel_type }}
            </div>
        </div>
        
        <div class="flex justify-between items-center">
            <div>
                <span class="text-2xl font-bold text-primary">${{ number_format($car->price, 2) }}</span>
                <span class="text-gray-500">/day</span>
            </div>
            <a href="{{ route('cars.show', $car) }}" class="btn-primary">
                Rent Now
            </a>
        </div>
    </div>
</div> 