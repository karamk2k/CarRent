@extends('layouts.admin')

@section('title', 'Rentals')
@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Rentals</h2>
            {{-- <a href="{{ route('admin.rentals.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Create New Rental
            </a> --}}
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="rentals-table-body">
                    @foreach($rentals as $rental)
                    <tr id="rental-row-{{ $rental->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $rental->user->profile_picture ? asset('storage/' . $rental->user->profile_picture) : asset('storage/defaults/images.png') }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $rental->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $rental->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $rental->car->name }} {{ $rental->car->model }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $rental->start_date }}</div>
                            <div>to {{ $rental->end_date }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($rental->status)
                                @case('pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Confirmed
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Completed
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${{ number_format($rental->total_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            {{-- <a href="{{ route('admin.rentals.show', $rental->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a> --}}
                            @if($rental->status === 'pending')
                                <button onclick="cancelRental({{ $rental->id }})" class="text-red-600 hover:text-red-900">Cancel</button>
                            @elseif($rental->status === 'confirmed')
                                <button onclick="startRental({{ $rental->id }})" class="text-green-600 hover:text-green-900 mr-3">complete</button>
                                <button onclick="cancelRental({{ $rental->id }})" class="text-red-600 hover:text-red-900">Cancel</button>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $rentals->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Only declare Toast if it doesn't exist
    if (typeof Toast === 'undefined') {
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
    }

    // Make functions available in global scope
    window.confirmRental = function(rentalId) {
        Swal.fire({
            title: 'Confirm Rental',
            text: "Are you sure you want to confirm this rental?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, confirm!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/rentals/${rentalId}/confirmation`,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        updateRentalStatus(rentalId, 'confirmed');
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Something went wrong!'
                        });
                    }
                });
            }
        });
    };

    window.startRental = function(rentalId) {
        Swal.fire({
            title: 'Complete Rental',
            text: "Are you sure you want to mark this rental as completed?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, complete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/rentals/${rentalId}/completed`,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        updateRentalStatus(rentalId, 'completed');
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Something went wrong!'
                        });
                    }
                });
            }
        });
    };

    window.cancelRental = function(rentalId) {
        Swal.fire({
            title: 'Cancel Rental',
            text: "Are you sure you want to cancel this rental?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/rentals/${rentalId}/cancellation`,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        updateRentalStatus(rentalId, 'cancelled');
                    },
                    error: function(xhr) {
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Something went wrong!'
                        });
                    }
                });
            }
        });
    };

    function updateRentalStatus(rentalId, status) {
        const row = document.getElementById(`rental-row-${rentalId}`);
        if (!row) return;

        // Update status badge
        const statusCell = row.querySelector('td:nth-child(4)');
        let badgeClass = '';
        let badgeText = '';

        switch(status) {
            case 'pending':
                badgeClass = 'bg-yellow-100 text-yellow-800';
                badgeText = 'Pending';
                break;
            case 'confirmed':
                badgeClass = 'bg-blue-100 text-blue-800';
                badgeText = 'Confirmed';
                break;
            case 'completed':
                badgeClass = 'bg-gray-100 text-gray-800';
                badgeText = 'Completed';
                break;
            case 'cancelled':
                badgeClass = 'bg-red-100 text-red-800';
                badgeText = 'Cancelled';
                break;
        }

        statusCell.innerHTML = `
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeClass}">
                ${badgeText}
            </span>
        `;

        // Update action buttons
        const actionsCell = row.querySelector('td:last-child');
        let actionsHtml = '';

        if (status === 'pending') {
            actionsHtml = `
                <button onclick="confirmRental(${rentalId})" class="text-green-600 hover:text-green-900 mr-3">Confirm</button>
                <button onclick="cancelRental(${rentalId})" class="text-red-600 hover:text-red-900">Cancel</button>
            `;
        } else if (status === 'confirmed') {
            actionsHtml = `
                <button onclick="startRental(${rentalId})" class="text-green-600 hover:text-green-900 mr-3">Complete</button>
                <button onclick="cancelRental(${rentalId})" class="text-red-600 hover:text-red-900">Cancel</button>
            `;
        }

        actionsCell.innerHTML = actionsHtml;
    }
</script>
@endpush
@endsection
