@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Categories</h2>
            <button id="add-category-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                Add New Category
            </button>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="categories-table-body">
                    @foreach($categories as $category)
                    <tr id="category-row-{{ $category->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $category->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="edit-category-btn text-blue-600 hover:text-blue-900 mr-3" data-id="{{ $category->id }}">Edit</button>
                            <button class="delete-category-btn text-red-600 hover:text-red-900" data-id="{{ $category->id }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- Custom Modal Structure -->
<div id="custom-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal container -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                        <div class="mt-2" id="modal-content">
                            <!-- Dynamic content will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="modal-confirm-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirm
                </button>
                <button type="button" id="modal-cancel-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Form Templates -->
<div id="create-category-form-template" class="hidden">
    <form id="create-category-form" class="space-y-4">
        <div>
            <label for="create-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="create-name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div>
            <label for="create-description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="create-description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>
    </form>
</div>

<div id="edit-category-form-template" class="hidden">
    <form id="update-category-form" class="space-y-4">
        <input type="hidden" name="category_id" id="edit-category-id">
        <div>
            <label for="edit-name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="edit-name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div>
            <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="edit-description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Toast notifications
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Custom Modal Controller
    const modal = {
        show: function(title, content, confirmCallback = null) {
            $('#modal-title').text(title);
            $('#modal-content').html(content);

            // Show the modal
            $('#custom-modal').removeClass('hidden').addClass('block');

            // Set up confirm button
            $('#modal-confirm-btn').off('click').on('click', function() {
                if (typeof confirmCallback === 'function') {
                    confirmCallback();
                } else {
                    modal.hide();
                }
            });

            // Set up cancel button
            $('#modal-cancel-btn').off('click').on('click', function() {
                modal.hide();
            });
        },

        hide: function() {
            $('#custom-modal').removeClass('block').addClass('hidden');
        }
    };

    // Error handler for AJAX requests
    function handleAjaxError(xhr) {
        const responseJson = xhr.responseJSON || {};
        let errorMessage = 'Something went wrong!';

        if (responseJson.errors) {
            // Handle validation errors
            errorMessage = Object.values(responseJson.errors).join('<br>');
        } else if (responseJson.message) {
            errorMessage = responseJson.message;
        }

        Toast.fire({
            icon: 'error',
            title: errorMessage
        });
    }

    // Add category button handler
    $('#add-category-btn').on('click', function() {
        const formContent = $('#create-category-form-template').html();

        modal.show('Create New Category', formContent, function() {
            const formData = $('#create-category-form').serialize();

            $.ajax({
                url: '{{ route("admin.categories.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Failed to create category.'
                        });
                    }
                },
                error: handleAjaxError
            });
        });
    });

    // Edit category button handler (using event delegation)
    $('#categories-table-body').on('click', '.edit-category-btn', function() {
        const categoryId = $(this).data('id');


        $.get(`/admin/categories/${categoryId}/edit`, function(response) {
            if (response.success && response.data) {
                console.log(response.data.name);
                const formContent = $('#edit-category-form-template').html();
                modal.show('Edit Category', formContent, function() {
                    // Set form values
                    $('#edit-category-id').val(categoryId);
                $('#edit-name').val(response.data.name);
                $('#edit-description').val(response.data.description);

                    // Handle form submission
                    const formData = $('#update-category-form').serialize();

                    $.ajax({
                        url: `/admin/categories/${categoryId}`,
                        method: 'POST',
                        data: formData + '&_method=PUT',
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message || 'Failed to update category.'
                                });
                            }
                        },
                        error: handleAjaxError
                    });
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: response.message || 'Failed to load category data.'
                });
            }
        }).fail(handleAjaxError);
    });

    // Delete category button handler (using event delegation)
    $('#categories-table-body').on('click', '.delete-category-btn', function() {
        const categoryId = $(this).data('id');

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
                    url: `/admin/categories/${categoryId}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            $(`#category-row-${categoryId}`).remove();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Failed to delete category.'
                            });
                        }
                    },
                    error: handleAjaxError
                });
            }
        });
    });
});
</script>
@endpush
@endsection