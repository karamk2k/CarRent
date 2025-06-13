@extends('layouts.admin')

@section('title', 'Banned Users')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Banned Users</h2>
                <p class="text-sm text-gray-500 mt-1">All users who are currently banned from the platform.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="banned-users-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banned By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banned At</th>
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
                <p class="text-lg font-medium">No banned users</p>
                <p class="text-sm">All users are currently in good standing.</p>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200" id="pagination">
            <!-- Pagination will be loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let selectedBanId = null;

    // Function to load banned users
    function loadBannedUsers(page = 1) {
        $.ajax({
            url: '{{ route("admin.users.banned") }}',
            type: 'GET',
            data: { page: page },
            success: function(response) {
                const data = response.data;
                let tbody = '';

                if (data.data.length === 0) {
                    $('#banned-users-table tbody').html('');
                    $('#empty-state').removeClass('hidden');
                } else {
                    $('#empty-state').addClass('hidden');
                    data.data.forEach(ban => {
                        tbody += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">${ban.user.name}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${ban.banned_by.name}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">${ban.reason}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${ban.is_permanent ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${ban.is_permanent ? 'Permanent' : 'Temporary'}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${ban.expires_at ? new Date(ban.expires_at).toLocaleString() : 'Never'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${new Date(ban.created_at).toLocaleString()}
                                </td>
                            </tr>
                        `;
                    });
                    $('#banned-users-table tbody').html(tbody);
                }

                // Update pagination
                let pagination = '';
                if (data.last_page > 1) {
                    pagination = `
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button onclick="loadBannedUsers(${data.current_page - 1})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${data.current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                                    Previous
                                </button>
                                <button onclick="loadBannedUsers(${data.current_page + 1})" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${data.current_page === data.last_page ? 'opacity-50 cursor-not-allowed' : ''}">
                                    Next
                                </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">${data.from}</span> to <span class="font-medium">${data.to}</span> of <span class="font-medium">${data.total}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        ${data.current_page > 1 ? `
                                            <button onclick="loadBannedUsers(${data.current_page - 1})" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        ` : ''}
                                        ${Array.from({length: data.last_page}, (_, i) => i + 1).map(page => `
                                            <button onclick="loadBannedUsers(${page})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium ${page === data.current_page ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50'}">
                                                ${page}
                                            </button>
                                        `).join('')}
                                        ${data.current_page < data.last_page ? `
                                            <button onclick="loadBannedUsers(${data.current_page + 1})" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        ` : ''}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    `;
                }

                $('#pagination').html(pagination);
                currentPage = data.current_page;
            },
            error: function(xhr) {
                console.error('Error loading banned users:', xhr);
                Toast.fire({
                    icon: 'error',
                    title: 'Failed to load banned users. Please try again.'
                });
            }
        });
    }

    // Function to show unban modal
    window.showUnbanModal = function(banId) {
        selectedBanId = banId;
        const template = document.getElementById('unban-modal-template');
        const content = template.content.cloneNode(true);
        window.modal.show('Confirm Unban', content, () => {
            // Attach click handler to Unban button
            setTimeout(function() {
                const btn = document.querySelector('.modal.active #confirm-unban-btn');
                if (btn) {
                    btn.onclick = function() {
                        if (!selectedBanId) return;
                        $.ajax({
                            url: `/admin/users/${selectedBanId}/unban`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    window.modal.hide();
                                    loadBannedUsers(currentPage);
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'User has been unbanned successfully'
                                    });
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: response.message || 'Failed to unban user'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.error('Error unbanning user:', xhr);
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to unban user. Please try again.'
                                });
                            }
                        });
                    };
                }
            }, 0);
        });
    };

    // Initial load
    loadBannedUsers();
});
</script>

<template id="unban-modal-template">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900">Confirm Unban</h3>
        <p class="mb-6 text-gray-700">Are you sure you want to unban this user?</p>
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="window.modal.hide()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Cancel
            </button>
            <button type="button" id="confirm-unban-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Unban
            </button>
        </div>
    </div>
</template>
@endpush
