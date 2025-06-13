@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Favorite Cars</h1>
            <p class="text-gray-600">Browse and manage your favorite cars</p>
        </div>

        <!-- Loading State -->
        <div id="favorites-loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>

        <!-- Empty State -->
        <div id="favorites-empty" class="hidden text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No favorites yet</h3>
            <p class="text-gray-600 mb-4">Start adding cars to your favorites to see them here</p>
            <a href="{{ route('home') }}" class="btn-primary inline-flex items-center">
                Browse Cars
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <!-- Favorites Grid -->
        <div id="favorites-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Favorites will be loaded here via AJAX -->
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Load favorites on page load
    loadFavorites();

    function loadFavorites() {
        $('#favorites-loading').show();
        $('#favorites-empty').hide();
        $('#favorites-grid').hide();

        $.ajax({
            url: '/favorites',
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                $('#favorites-loading').hide();

                if (response.success && response.data && response.data.length > 0) {
                    $('#favorites-grid').show();
                    renderFavorites(response.data);
                } else {
                    $('#favorites-empty').show();
                }
            },
            error: function(xhr) {
                $('#favorites-loading').hide();
                $('#favorites-empty').show();
                showToast('Error loading favorites', 'error');
            }
        });
    }

    function renderFavorites(favorites) {
        const grid = $('#favorites-grid');
        grid.empty();

        favorites.forEach(favorite => {
            const car = favorite.car;
            const card = createFavoriteCard(car);
            grid.append(card);
        });
    }

    function createFavoriteCard(car) {
        const isAvailable = car.available_at === null;
        const availabilityClass = isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';

        // Format availability text
        const availabilityText = isAvailable ?
            'Available Now' :
            `Available from ${new Date(car.available_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}`;

        return `
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative">
                    <img src="${car.image_url || ''}" alt="${car.name || ''}"
                         class="w-full h-48 object-cover ${!isAvailable ? 'grayscale' : ''}">

                    <!-- Availability Badge -->
                    <div class="absolute top-4 right-4">
                        <span class="${availabilityClass} backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                            ${availabilityText}
                        </span>
                    </div>

                    <!-- Remove Favorite Button -->
                    <button class="favorite-btn absolute top-4 left-4 p-2 rounded-full bg-white/80 hover:bg-white transition-colors text-red-500"
                            data-car-id="${car.id}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">${car.name || ''}</h3>
                            <p class="text-gray-500">
                                ${car.brand || car.model || ''} • ${car.year ? `${car.year}` : ''}
                            </p>
                        </div>
                        <div class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="ml-1 text-gray-600">${car.rating || '4.5'}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            ${car.transmission || car.transmissions || ''}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ${car.fuel_type || ''}
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-2xl font-bold text-primary">$${parseFloat(car.price || 0).toFixed(2)}</span>
                            <span class="text-gray-500">/day</span>
                        </div>
                        <a href="/cars/${car.id}" class="btn-primary">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        `;
    }

    // Handle favorite button clicks
    $('#favorites-grid').on('click', '.favorite-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = $(this);
        const carId = button.data('car-id');
        const card = button.closest('.bg-white');

        $.ajax({
            url: '/favorites/remove',
            method: 'DELETE',
            data: JSON.stringify({ car_id: carId }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    // Remove the card with animation
                    card.fadeOut(300, function() {
                        $(this).remove();
                        // Check if grid is empty
                        if ($('#favorites-grid').children().length === 0) {
                            $('#favorites-grid').hide();
                            $('#favorites-empty').show();
                        }
                    });
                    showToast('Car removed from favorites', 'success');
                } else {
                    showToast(response.message || 'Error removing from favorites', 'error');
                }
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || 'Error removing from favorites', 'error');
            }
        });
    });
});
</script>
@endpush
@endsection