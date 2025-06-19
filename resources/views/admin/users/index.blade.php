@extends('layouts.admin')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Users</h2>
            <a href="{{ route('admin.users.ban.index') }}" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                View Banned Users
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rentals</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
                    @foreach($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <select onchange="updateUserRole({{ $user->id }}, this.value)"
                                    class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                <option value="customer" {{ $user->role->name === 'user' ? 'selected' : '' }}>customer</option>
                                <option value="admin" {{ $user->role->name === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_banned  ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->is_banned ? 'Banned' : 'Active' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->rentals->count() }} rentals
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($user->id !== auth()->id())
                                @if($user->is_banned)
                                    <button onclick="unbanUser({{ $user->id }})" class="text-green-600 hover:text-green-900">Unban</button>
                                @else
                                    <button onclick="banUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Ban</button>
                                @endif
                            @else
                                <span class="text-gray-400">Current User</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Ban User Modal -->
<div class="modal fade" id="banUserModal" tabindex="-1" role="dialog" aria-labelledby="banUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banUserModalLabel">Ban User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateUserRole(userId, role) {
        if (userId === {{ auth()->id() }}) {
            Toast.fire({
                icon: 'error',
                title: 'You cannot change your own role!'
            });
            return;
        }

        $.ajax({
            url: `{{ url('admin/users') }}/${userId}/role`,
            method: 'PUT',
            data: { role },
            success: function(response) {
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
            },
            error: function() {
                Toast.fire({
                    icon: 'error',
                    title: 'Something went wrong!'
                });
            }
        });
    }

    function banUser(userId) {
        if (userId === {{ auth()->id() }}) {
            Toast.fire({
                icon: 'error',
                title: 'You cannot ban yourself!'
            });
            return;
        }

        // Create modal content
        const modalContent = `
    <form id="banUserForm" class="space-y-4">
        <input type="hidden" name="user_id" value="${userId}">

        <div>
            <label for="banReason" class="block text-sm font-medium text-gray-700">Reason for Ban</label>
            <textarea id="banReason" name="reason" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required></textarea>
            <p class="mt-1 text-sm text-gray-500">Please provide a reason for banning this user</p>
        </div>

        <div>
            <div class="flex items-center">
                <input type="checkbox" id="isPermanent" name="is_permanent"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <label for="isPermanent" class="ml-2 block text-sm text-gray-900">Permanent Ban</label>
            </div>
        </div>

        <div id="expiryDateContainer">
            <label for="expiresAt" class="block text-sm font-medium text-gray-700">Ban Expiry Date</label>
            <input type="datetime-local" id="expiresAt" name="expires_at"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                min="${new Date().toISOString().slice(0, 16)}">
            <p class="mt-1 text-sm text-gray-500">Required if not a permanent ban</p>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <button type="button" onclick="window.modal.hide()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Ban User
            </button>
        </div>
    </form>
`;

// Show modal
window.modal.show('Ban User', modalContent, () => {
    const form = document.getElementById('banUserForm');
    if (!form) {
        Toast.fire({
            icon: 'error',
            title: 'Form not found'
        });
        return;
    }

});
$('#banUserForm').on('submit', function(e) {
        e.preventDefault();
        const form = document.getElementById('banUserForm');
 // Handle permanent ban checkbox toggle
 const isPermanentCheckbox = form.querySelector('#isPermanent');
    const expiryDateContainer = form.querySelector('#expiryDateContainer');

    isPermanentCheckbox.addEventListener('change', function() {
        if (this.checked) {
            expiryDateContainer.style.display = 'none';
            form.querySelector('#expiresAt').required = false;
        } else {
            expiryDateContainer.style.display = 'block';
            form.querySelector('#expiresAt').required = true;
        }
    });

    // Initialize state
    if (isPermanentCheckbox.checked) {
        expiryDateContainer.style.display = 'none';
        form.querySelector('#expiresAt').required = false;
    }
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = {
            reason: formData.get('reason'),
            is_permanent: formData.get('is_permanent') === 'on' ? 1 : 0,
            expires_at: formData.get('is_permanent') === 'on' ? null : formData.get('expires_at')
        };

        // Additional validation
        if (!data.reason.trim()) {
            Toast.fire({
                icon: 'error',
                title: 'Please provide a reason for the ban'
            });
            return;
        }

        if (!data.is_permanent && !data.expires_at) {
            Toast.fire({
                icon: 'error',
                title: 'Please provide an expiry date for temporary ban'
            });
            return;
        }

        // Submit the form via AJAX
        $.ajax({
            url: `{{ url('admin/users') }}/${userId}/ban`,
            method: 'POST',
            data: data,
            success: function(response) {
                window.modal.hide();
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                // Update the row
                const row = document.getElementById(`user-row-${userId}`);
                if (row) {
                    row.querySelector('td:nth-child(4) span').className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                    row.querySelector('td:nth-child(4) span').textContent = 'Banned';
                    row.querySelector('td:last-child').innerHTML = `
                        <button onclick="unbanUser(${userId})" class="text-green-600 hover:text-green-900 mr-3">Unban</button>
                        <button onclick="showEditModal(${userId})" class="text-blue-600 hover:text-blue-900">Edit</button>
                    `;
                }
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
                        title: xhr.responseJSON?.message || 'Something went wrong!'
                    });
                }
            }
        });
    });

        // Handle permanent ban checkbox
        $(document).on('change', '#isPermanent', function() {
            const expiryContainer = $('#expiryDateContainer');
            const expiresAt = $('#expiresAt');

            if ($(this).is(':checked')) {
                expiryContainer.hide();
                expiresAt.prop('required', false);
                expiresAt.val('');
            } else {
                expiryContainer.show();
                expiresAt.prop('required', true);
            }
        });
    }

    function unbanUser(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be unbanned from the system!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, unban user!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/users') }}/${userId}/unban`,
                    method: 'POST',
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        // Update the row
                        const row = document.getElementById(`user-row-${userId}`);
                        row.querySelector('td:nth-child(4) span').className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                        row.querySelector('td:nth-child(4) span').textContent = 'Active';
                        row.querySelector('td:last-child').innerHTML = '<button onclick="banUser(' + userId + ')" class="text-red-600 hover:text-red-900">Ban</button>';
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
