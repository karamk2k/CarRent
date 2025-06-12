@extends('layouts.app')

@section('title', $car->name)

@push('styles')
<style>
    .car-details {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .car-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
    }
    
    .car-image-container img {
        transition: transform 0.3s ease;
    }
    
    .car-image-container:hover img {
        transform: scale(1.05);
    }
    
    .favorite-btn {
        transition: all 0.3s ease;
    }
    
    .favorite-btn:hover {
        transform: scale(1.1);
    }
    
    .feature-icon {
        transition: all 0.3s ease;
    }
    
    .feature-icon:hover {
        transform: translateY(-2px);
        color: var(--primary-color);
    }
    
    .rental-form {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Car Details --}}
        <div class="lg:col-span-2">
            <div class="car-details rounded-2xl shadow-sm overflow-hidden">
                <div class="car-image-container">
                    <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="w-full h-[500px] object-cover">
                    <button class="favorite-btn absolute top-4 left-4 text-gray-400 hover:text-red-500 focus:outline-none {{ $car->is_favorite ? 'text-red-500' : '' }}" 
                            data-car-id="{{ $car->id }}">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full">
                        <span class="text-lg font-semibold text-primary">${{ number_format($car->price, 2) }}/day</span>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $car->name }}</h1>
                            <p class="text-xl text-gray-600">{{ $car->brand }}</p>
                        </div>
                        <div class="flex items-center bg-gray-50 px-4 py-2 rounded-full">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="ml-2 text-xl font-medium text-gray-700">{{ number_format($car->rating, 1) }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                        <div class="feature-icon flex items-center text-gray-600 bg-gray-50 p-4 rounded-xl">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <div>
                                <span class="block text-sm text-gray-500">Transmission</span>
                                <span class="font-medium">{{ $car->transmission }}</span>
                            </div>
                        </div>
                        <div class="feature-icon flex items-center text-gray-600 bg-gray-50 p-4 rounded-xl">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <span class="block text-sm text-gray-500">Fuel Type</span>
                                <span class="font-medium">{{ $car->fuel_type }}</span>
                            </div>
                        </div>
                        <div class="feature-icon flex items-center text-gray-600 bg-gray-50 p-4 rounded-xl">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <div>
                                <span class="block text-sm text-gray-500">Type</span>
                                <span class="font-medium">{{ $car->type }}</span>
                            </div>
                        </div>
                        <div class="feature-icon flex items-center text-gray-600 bg-gray-50 p-4 rounded-xl">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div>
                                <span class="block text-sm text-gray-500">Category</span>
                                <span class="font-medium category-name" data-category-id="{{ $car->category_id }}">Loading...</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="prose max-w-none">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Description</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $car->description }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Rental Form --}}
        <div class="lg:col-span-1">
            <div class="rental-form rounded-2xl p-6 sticky top-24">
                @include('partials.rental-form')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Fetch category name
    function loadCategoryName() {
        const categoryId = $('.category-name').data('category-id');
        if (categoryId) {
            $.ajax({
                url: `categories/${categoryId}`,
                method: 'GET',
                success: function(response) {
                    if (response.data) {
                        $('.category-name').text(response.data.name);
                    }
                },
                error: function() {
                    $('.category-name').text('Unknown Category');
                }
            });
        }
    }

    // Handle favorite button clicks
    $('.favorite-btn').on('click', function() {
        const carId = $(this).data('car-id');
        const button = $(this);

        $.ajax({
            url: `/cars/${carId}/favorite`,
            method: 'POST',
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                button.toggleClass('text-red-500');
                showToast(response.message, 'success');
            },
            error: function() {
                showToast('Error updating favorite status. Please try again.', 'error');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Load category name on page load
    loadCategoryName();
});
</script>
@endpush
@endsection