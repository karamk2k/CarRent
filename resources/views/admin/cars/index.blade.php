@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Cars</h2>
            <button onclick="showCreateModal()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Add New Car
            </button>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand/Model</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Day</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="cars-table-body">
                    @foreach($cars as $car)
                    <tr id="car-row-{{ $car->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($car->image)
                                <img src="{{ Storage::url($car->image) }}" alt="{{ $car->name }}" class="h-12 w-12 object-cover rounded">
                            @else
                                <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $car->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->categoryRes->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->brand }} {{ $car->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($car->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ is_null($car->available_at) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ is_null($car->available_at) ? 'Available' : 'Not Available' }}
                        </span>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="showEditModal({{ $car->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button onclick="deleteCar({{ $car->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $cars->links() }}
        </div>
    </div>
</div>

<template id="create-car-form-template">
    <form id="car-form" class="space-y-6 p-4 bg-white rounded-xl shadow-md" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="category" class="block mb-1 text-sm font-medium text-gray-700">Category</label>
                <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="color" class="block mb-1 text-sm font-medium text-gray-700">Color</label>
                <input type="text" name="color" id="color" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="model" class="block mb-1 text-sm font-medium text-gray-700">Model</label>
                <input type="text" name="model" id="model" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="year" class="block mb-1 text-sm font-medium text-gray-700">Year</label>
                <input type="number" name="year" id="year" min="1900" max="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="price" class="block mb-1 text-sm font-medium text-gray-700">Price per Day</label>
                <input type="number" name="price" id="price" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="transmissions" class="block mb-1 text-sm font-medium text-gray-700">Transmission</label>
                <select name="transmissions" id="transmissions" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Transmission</option>
                    <option value="manual">Manual</option>
                    <option value="automatic">Automatic</option>
                </select>
            </div>

            <div>
                <label for="seats" class="block mb-1 text-sm font-medium text-gray-700">Seats</label>
                <input type="number" name="seats" id="seats" min="1" max="20" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label for="fuel_type" class="block mb-1 text-sm font-medium text-gray-700">Fuel Type</label>
                <select name="fuel_type" id="fuel_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Fuel Type</option>
                    <option value="petrol">Petrol</option>
                    <option value="diesel">Diesel</option>
                    <option value="electric">Electric</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>

            <div>
                <label for="fuel_capacity" class="block mb-1 text-sm font-medium text-gray-700">Fuel Capacity (Liters)</label>
                <input type="number" name="fuel_capacity" id="fuel_capacity" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
        </div>

        <div class="mt-6">
            <label for="image" class="block mb-1 text-sm font-medium text-gray-700">Image</label>
            <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mt-4">
            <label for="status" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="available">Available</option>
                <option value="rented">Rented</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" onclick="modal.hide()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Create Car</button>
        </div>
    </form>
</template>


<template id="edit-car-form-template">
    <form id="edit-car-form" class="space-y-4">
        <input type="hidden" id="edit-car-id" name="id">
        <input type="hidden" id="edit-current-image" name="current_image">

        <div>
            <label for="edit-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="edit-name" name="name" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-category" class="block text-sm font-medium text-gray-700">Category</label>
            <select id="edit-category" name="category" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="edit-color" class="block text-sm font-medium text-gray-700">Color</label>
            <input type="text" id="edit-color" name="color" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-model" class="block text-sm font-medium text-gray-700">Model</label>
            <input type="text" id="edit-model" name="model" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-year" class="block text-sm font-medium text-gray-700">Year</label>
            <input type="number" id="edit-year" name="year" required min="1900" max="{{ date('Y') + 1 }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-price" class="block text-sm font-medium text-gray-700">Price per Day</label>
            <input type="number" id="edit-price" name="price" required min="0" step="0.01"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-transmissions" class="block text-sm font-medium text-gray-700">Transmission</label>
            <select id="edit-transmissions" name="transmissions" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="automatic">Automatic</option>
                <option value="manual">Manual</option>
            </select>
        </div>

        <div>
            <label for="edit-seats" class="block text-sm font-medium text-gray-700">Seats</label>
            <input type="number" id="edit-seats" name="seats" required min="2" max="10"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-fuel_type" class="block text-sm font-medium text-gray-700">Fuel Type</label>
            <select id="edit-fuel_type" name="fuel_type" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="petrol">Petrol</option>
                <option value="diesel">Diesel</option>
                <option value="electric">Electric</option>
                <option value="hybrid">Hybrid</option>
            </select>
        </div>

        <div>
            <label for="edit-fuel_capacity" class="block text-sm font-medium text-gray-700">Fuel Capacity (Liters)</label>
            <input type="number" id="edit-fuel_capacity" name="fuel_capacity" required min="0" step="0.1"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="edit-available_at" class="block text-sm font-medium text-gray-700">Availability</label>
            <select id="edit-available_at" name="available_at" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="available">Available</option>
                <option value="rented">Rented</option>
            </select>
        </div>

        <div>
            <label for="edit-image" class="block text-sm font-medium text-gray-700">Car Image</label>
            <div id="current-image" class="mt-2 mb-2"></div>
            <input type="file" id="edit-image" name="image" accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-1 text-sm text-gray-500">Leave empty to keep the current image</p>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" onclick="modal.hide()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Save Changes</button>
        </div>
    </form>
</template>

@push('scripts')
<script>
    // Show create modal
    function showCreateModal() {
        const template = document.getElementById('create-car-form-template'); // Corrected ID
        const content = template.content.cloneNode(true);

        // Define the callback function for form submission
        const handleSubmit = () => {
            const form = document.getElementById('car-form');
            const formData = new FormData(form);
            $.ajax({
                url: '{{ route("admin.cars.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    // Reload the page to show new car
                    window.location.reload();
                },
                // In both create and edit modals:
error: function(xhr) {
    // Safely access responseJSON, providing a default empty object if it's null/undefined
    const responseJson = xhr.responseJSON || {};
    let errorMessage = 'Something went wrong!'; // Default message

    if (responseJson.errors) {
        // If validation errors are returned (e.g., 422 status)
        // responseJson.errors will be an object, so Object.keys will work
        Object.keys(responseJson.errors).forEach(key => {
            Toast.fire({
                icon: 'error',
                title: responseJson.errors[key][0]
            });
        });
    } else if (responseJson.message) {
        // If a general error message is returned (e.g., from a try/catch in controller)
        errorMessage = responseJson.message;
        Toast.fire({
            icon: 'error',
            title: errorMessage
        });
    } else if (xhr.status === 500) {
        // Fallback for a generic 500 error without specific JSON
        Toast.fire({
            icon: 'error',
            title: 'An internal server error occurred. Please check server logs.'
        });
    } else {
        // For other unhandled errors or empty responses
        Toast.fire({
            icon: 'error',
            title: errorMessage + ' (Status: ' + xhr.status + ')'
        });
    }
    console.error('AJAX Error:', xhr); // Log the full XHR object for detailed debugging
}
            });
        };

        // Pass the form element and the submit handler to the modal.show function
        modal.show('Create New Car', content, handleSubmit);

        // Attach event listener to the form's submit button within the modal
        // This ensures the button inside the modal triggers the handleSubmit
        document.querySelector('.modal.active #car-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            handleSubmit();
        });
    }

    // Show edit modal
    function showEditModal(carId) {
        $.get(`{{ url('admin/cars') }}/${carId}/edit`, function(response) {
            const template = document.getElementById('edit-car-form-template'); // Corrected ID
            const content = template.content.cloneNode(true);
            const car = response.data;

            // Fill form with car data using correct field IDs
            content.getElementById('edit-car-id').value = car.id;
            content.getElementById('edit-current-image').value = car.image || ''; // Store current image path
            content.getElementById('edit-name').value = car.name;
            content.getElementById('edit-category').value = car.category_id;
            content.getElementById('edit-color').value = car.color;
            content.getElementById('edit-model').value = car.model;
            content.getElementById('edit-year').value = car.year;
            content.getElementById('edit-price').value = car.price;
            content.getElementById('edit-transmissions').value = car.transmissions;
            content.getElementById('edit-seats').value = car.seats;
            content.getElementById('edit-fuel_type').value = car.fuel_type;
            content.getElementById('edit-fuel_capacity').value = car.fuel_capacity;
            content.getElementById('edit-available_at').value = car.available_at ? 'rented' : 'available';

            // Show current image if exists
            if (car.image) {
                const currentImage = content.getElementById('current-image');
                currentImage.innerHTML = `
                    <img src="{{ Storage::url('') }}${car.image}" alt="Current image" class="h-32 w-32 object-cover rounded">
                    <p class="text-sm text-gray-500 mt-1">Current image</p>
                `;
            }

            // Define the callback function for form submission
            const handleSubmit = () => {
                // Get the form element from the active modal
                const form = document.querySelector('.modal.active #edit-car-form');
                if (!form) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Form not found'
                    });
                    return;
                }

                const formData = new FormData(form);

                // If no new image is selected, remove the image field from FormData
                // This will make the backend keep the existing image
                if (!formData.get('image').size) {
                    formData.delete('image');
                }

                $.ajax({
                    url: `{{ url('admin/cars') }}/${carId}`,
                    method: 'POST', // Use POST for PUT requests with FormData
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT' // Spoof PUT method
                    },
                    success: function(response) {
                        modal.hide();
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        // Reload the page to show updated car
                        window.location.reload();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            Object.values(errors).forEach(error => {
                                Toast.fire({
                                    icon: 'error',
                                    title: error[0]
                                });
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong!'
                            });
                        }
                    }
                });
            };

            modal.show('Edit Car', content, handleSubmit);

            // Attach event listener to the form's submit button within the modal
            // This ensures the button inside the modal triggers the handleSubmit
            document.querySelector('.modal.active #edit-car-form').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                handleSubmit();
            });

        }).fail(function(xhr) {
            Toast.fire({
                icon: 'error',
                title: 'Failed to load car data'
            });
        });
    }

    // Delete car
    function deleteCar(carId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/cars') }}/${carId}`,
                    method: 'DELETE',
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        // Remove the row from the table
                        document.getElementById(`car-row-${carId}`).remove();
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Something went wrong!'
                        });
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection