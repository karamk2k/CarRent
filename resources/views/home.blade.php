@extends('layouts.app')

@section('title', 'Find Your Perfect Ride')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/images/hero-pattern.svg') center/cover;
        opacity: 0.1;
    }
    
    .search-container {
        position: relative;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .filter-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
    }
    
    .car-card {
        transition: all 0.3s ease;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .car-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }
    
    .car-image {
        transition: transform 0.3s ease;
    }
    
    .car-card:hover .car-image {
        transform: scale(1.05);
    }
    
    .favorite-btn {
        transition: all 0.3s ease;
    }
    
    .favorite-btn:hover {
        transform: scale(1.1);
    }
    
    .filter-select {
        transition: all 0.2s ease;
    }
    
    .filter-select:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(53, 99, 233, 0.1);
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    .skeleton-loading {
        animation: shimmer 1.5s infinite;
        background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
        background-size: 200% 100%;
    }
</style>
@endpush

@section('content')
{{-- Hero Section with Search --}}
<div class="hero-section py-20">
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Find Your Perfect Ride</h1>
            <p class="text-xl text-white/90 mb-8">Discover and rent the perfect car for your journey</p>
            
            {{-- Quick Search Bar --}}
            <div class="search-container rounded-xl p-2">
                <div class="relative">
                    <input type="text" 
                           id="quick-search" 
                           class="w-full px-6 py-4 rounded-lg bg-white/90 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-white focus:border-transparent"
                           placeholder="Search by car name, brand, or type...">
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 -mt-8">
    {{-- Advanced Filters --}}
    <div class="filter-card rounded-xl p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Advanced Filters</h2>
            <button id="toggle-filters" class="text-primary hover:text-primary/80 flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-50">
                <span>Show Filters</span>
                <svg class="h-5 w-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        
        <form id="filter-form" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Category Filter --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category" name="category" class="filter-select w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">All Categories</option>
                        {{-- Categories will be loaded via AJAX --}}
                    </select>
                </div>

                {{-- Transmission Filter --}}
                <div>
                    <label for="transmission" class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select id="transmission" name="transmission" class="filter-select w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">All Transmissions</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>

                {{-- Price Range Filter --}}
                <div>
                    <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                    <select id="price_range" name="price_range" class="filter-select w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="">All Prices</option>
                        <option value="0-50">$0 - $50</option>
                        <option value="50-100">$50 - $100</option>
                        <option value="100-150">$100 - $150</option>
                        <option value="150-200">$150 - $200</option>
                        <option value="200+">$200+</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-primary flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Apply Filters</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Loading State --}}
    <div id="loading-state" class="hidden">
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>
    </div>

    {{-- Cars Grid --}}
    <div id="cars-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-opacity duration-300">
        {{-- Initial loading skeleton --}}
        <div class="col-span-full">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @for ($i = 0; $i < 6; $i++)
                    <div class="car-card rounded-xl overflow-hidden skeleton-loading">
                        <div class="h-48 bg-gray-200"></div>
                        <div class="p-6 space-y-4">
                            <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                            <div class="h-10 bg-gray-200 rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- No Results State --}}
    <div id="no-results" class="hidden text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No cars found</h3>
        <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
    </div>

    {{-- Pagination --}}
    <div id="pagination" class="mt-12"></div>
</div>

@include('partials.cars-grid')

@push('scripts')
<script type="module">
$(document).ready(function() {
    const loadingState = $('#loading-state');
    const carsGrid = $('#cars-grid');
    const noResults = $('#no-results');
    const filterForm = $('#filter-form');
    const toggleFilters = $('#toggle-filters');
    const quickSearch = $('#quick-search');
    const categorySelect = $('#category');
    let searchTimeout;

    // Load categories from API
    function loadCategories() {
        $.ajax({
            url: '/categories',
            method: 'GET',
            success: function(response) {
                if (response.data) {
                    const categories = response.data;
                    categories.forEach(category => {
                        categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                    });
                }
            },
            error: function() {
                showToast('Error loading categories', 'error');
            }
        });
    }

    // Toggle filters visibility
    toggleFilters.on('click', function() {
        filterForm.slideToggle(200);
        const isVisible = filterForm.is(':visible');
        $(this).find('span').text(isVisible ? 'Hide Filters' : 'Show Filters');
        $(this).find('svg').toggleClass('rotate-180', isVisible);
    });

    // Function to load cars via AJAX
    function loadCars(url) {
        loadingState.removeClass('hidden');
        carsGrid.addClass('opacity-50');
        noResults.addClass('hidden');

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    const cars = response.data.map(car => ({
                        id: car.id,
                        name: car.name,
                        brand: car.model,
                        type: car.category,
                        year: car.year,
                        image_url: car.image_url,
                        price: car.price,
                        rating: car.rating || 4.5,
                        transmission: car.transmissions,
                        fuel_type: car.fuel_type,
                        is_favorite: car.is_favorite || false,
                        available_at: car.available_at
                    }));
                    
                    carsGrid.html(CarsGrid.renderCarsGrid(cars));
                    noResults.addClass('hidden');
                } else {
                    carsGrid.html(CarsGrid.renderCarsGrid([]));
                    noResults.removeClass('hidden');
                }
            },
            error: function(xhr) {
                console.error('Error loading cars:', xhr);
                showToast('Error loading cars. Please try again.', 'error');
                carsGrid.html(`
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Error Loading Cars</h3>
                        <p class="mt-1 text-gray-500">Please try again later</p>
                    </div>
                `);
            },
            complete: function() {
                loadingState.addClass('hidden');
                carsGrid.removeClass('opacity-50');
            }
        });
    }

    // Load categories and cars on page load
    loadCategories();
    loadCars('/cars');

    // Handle filter form submission
    filterForm.on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        loadCars('/cars?' + formData);
    });

    // Handle quick search
    quickSearch.on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().trim();
        
        searchTimeout = setTimeout(() => {
            if (searchTerm.length >= 2) {
                loadCars('/cars?search=' + encodeURIComponent(searchTerm));
            } else if (searchTerm.length === 0) {
                loadCars('/cars');
            }
        }, 300);
    });
});
</script>
@endpush 