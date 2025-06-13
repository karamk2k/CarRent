@extends('layouts.admin')

@section('title', 'Discounts')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Discounts</h2>
            <button id="add-discount-btn" class="mt-2 sm:mt-0 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Discount</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="discounts-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
            <div id="empty-state" class="hidden py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-lg font-medium">No discounts found</p>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200" id="pagination">
            <!-- Pagination will be loaded via AJAX -->
        </div>
    </div>
</div>

<!-- Add Discount Modal -->
<template id="add-discount-modal-template">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative">
        <button type="button" onclick="window.modal.hide()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 focus:outline-none text-2xl" aria-label="Close">&times;</button>
        <h3 class="text-2xl font-bold mb-6 text-gray-900">Add Discount</h3>
        <form id="add-discount-form" class="space-y-6">
            <div>
                <label for="discount-name" class="block text-sm font-semibold text-gray-700">Name</label>
                <input type="text" id="discount-name" name="name" class="mt-2 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2" required>
            </div>
            <div>
                <label for="discount-percentage" class="block text-sm font-semibold text-gray-700">Percentage</label>
                <input type="number" id="discount-percentage" name="percentage" min="0" max="100" step="0.01" class="mt-2 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2" required>
            </div>
            <div>
                <label for="discount-start-date" class="block text-sm font-semibold text-gray-700">Start Date</label>
                <input type="date" id="discount-start-date" name="start_date" class="mt-2 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2" required>
            </div>
            <div>
                <label for="discount-end-date" class="block text-sm font-semibold text-gray-700">End Date</label>
                <input type="date" id="discount-end-date" name="end_date" class="mt-2 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2" required>
            </div>
            <div class="flex justify-end space-x-3 pt-6">
                <button type="button" onclick="window.modal.hide()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Cancel</button>
                <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Add Discount</button>
            </div>
        </form>
    </div>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function loadDiscounts(page = 1) {
        $.ajax({
            url: '{{ route('admin.discounts.get') }}',
            type: 'GET',
            data: { page: page },
            success: function(response) {
                const data = response.data;

                let tbody = '';
                if (!data || data.length === 0) {
                    $('#discounts-table tbody').html('');
                    $('#empty-state').removeClass('hidden');
                } else {
                    $('#empty-state').addClass('hidden');
                    data.forEach(discount => {
                        tbody += `
                            <tr id="discount-row-${discount.id}">
                                <td class="px-6 py-4 whitespace-nowrap">${discount.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${discount.percentage}%</td>
                                <td class="px-6 py-4 whitespace-nowrap">${discount.start_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${discount.end_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button onclick="deleteDiscount(${discount.id})" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#discounts-table tbody').html(tbody);
                }
                // Pagination (simple, can be improved)
                let pagination = '';
                if (data.last_page > 1) {
                    pagination = `<div class='flex justify-center space-x-2'>`;
                    for (let i = 1; i <= data.last_page; i++) {
                        pagination += `<button onclick='loadDiscounts(${i})' class='px-3 py-1 rounded ${i === data.current_page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}'>${i}</button>`;
                    }
                    pagination += `</div>`;
                }
                $('#pagination').html(pagination);
            },
            error: function(xhr) {
                Toast.fire({ icon: 'error', title: 'Failed to load discounts.' });
            }
        });
    }

    // Show add discount modal
    $('#add-discount-btn').on('click', function() {
        const template = document.getElementById('add-discount-modal-template');
        const content = template.content.cloneNode(true);
        window.modal.show('Add Discount', content, function() {
            const form = document.querySelector('.modal.active #add-discount-form');
    });
    $('#add-discount-form').on('submit', function(e) {
        const form = document.querySelector('.modal.active #add-discount-form');
            if (!form) return;
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            const formData = {
                name: form.name.value,
                percentage: form.percentage.value,
                start_date: form.start_date.value,
                end_date: form.end_date.value
            };
                e.preventDefault();
                console.log(formData);
                $.ajax({
                url: '{{ route('admin.discounts.store') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    window.modal.hide();
                    Toast.fire({ icon: 'success', title: response.message });
                    loadDiscounts();
                },
                error: function(xhr) {
                    let msg = 'Failed to add discount.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('<br>');
                    }
                    Toast.fire({ icon: 'error', title: msg });
                }
            });
        });
            });

    // Expose deleteDiscount globally
    window.deleteDiscount = function(id) {
        // if (!confirm('Are you sure you want to delete this discount?')) return;
        $.ajax({
            url: `/admin/discounts/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(`#discount-row-${id}`).remove();
                    Toast.fire({ icon: 'success', title: response.message });
                } else {
                    Toast.fire({ icon: 'error', title: response.message || 'Failed to delete discount' });
                }
                loadDiscounts();
            },
            error: function(xhr) {
                Toast.fire({ icon: 'error', title: 'Failed to delete discount. Please try again.' });
            }
        });
    };

    // Initial load
    loadDiscounts();
});
</script>
@endpush
