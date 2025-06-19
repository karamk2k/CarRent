@extends('layouts.app')

@section('title', 'My Rental History')

@section('content')
@if(request()->has('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Oops!</strong>
        <span class="block sm:inline">{{ request('error') }}</span>
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Rental History</h1>

        @if($histories->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No rental history found</h3>
                <p class="mt-1 text-gray-500">Start renting cars to see your history here.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="btn-primary">
                        Browse Cars
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Period</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($histories as $history)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' .$history->car->image) }}" alt="{{ $history->car->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $history->car->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $history->car->model }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $history->rent_date->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">to {{ $history->return_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">${{ number_format($history->total_price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($history->status === 'completed') bg-green-100 text-green-800
                                            @elseif($history->status === 'active') bg-blue-100 text-blue-800
                                            @elseif($history->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($history->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ ucfirst($history->payment_method) }}</div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($history->payment_status === 'paid') bg-green-100 text-green-800
                                            @elseif($history->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($history->payment_status) }}
                                        </span>
                                        @if ($history->payment_status === 'pending')
                                            <div class="mt-2">
                                              <button
                                                    class="mt-1 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 pay-now-btn"
                                                    data-id="{{ $history->rental->id }}"
                                                    data-secret="{{ $history->rental->client_secret ?? '' }}"
                                                >
                                                    Pay Now
                                                </button>

                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="stripeModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Complete Your Payment</h2>
        <form id="stripe-payment-form">
            <div id="card-element" class="mb-4 p-2 border border-gray-300 rounded"></div>
            <div id="card-errors" class="text-red-500 text-sm mb-3"></div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelStripeModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" id="confirmStripePayment" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Pay</button>
            </div>
        </form>
    </div>
</div>

        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe('{{ config("services.stripe.key") }}');
    let elements = stripe.elements();
    let card = elements.create('card');
    card.mount('#card-element');

    let selectedClientSecret = null;
    let rentalId = null;

    $('.pay-now-btn').on('click', function () {
        selectedClientSecret = $(this).data('secret');
        console.log('Selected Client Secret:', selectedClientSecret);
        rentalId = $(this).data('id');
        $('#stripeModal').removeClass('hidden');
    });

    $('#cancelStripeModal').on('click', function () {
        $('#stripeModal').addClass('hidden');
        card.clear();
        $('#card-errors').text('');
    });

    $('#stripe-payment-form').on('submit', async function (e) {
        e.preventDefault();
        $('#confirmStripePayment').prop('disabled', true);

        const { paymentIntent, error } = await stripe.confirmCardPayment(selectedClientSecret, {
            payment_method: {
                card: card,
            }
        });

        if (error) {
            $('#card-errors').text(error.message);
            $('#confirmStripePayment').prop('disabled', false);
            return;
        }

        if (paymentIntent.status === 'succeeded') {
          $.ajax({
            url: `/rentals/${rentalId}/confirm-payment`,
            method: 'POST',
            success: function(response) {
                $('#stripeModal').addClass('hidden');
                card.clear();
                $('#card-errors').text('');
                showToast('Payment successful!', 'success');
                location.reload();
            },
            error: function(xhr) {
                $('#card-errors').text('Payment confirmation failed. Please try again.');
                $('#confirmStripePayment').prop('disabled', false);
            }
          })
        } else {
            $('#card-errors').text('Payment was not successful.');
        }

        $('#confirmStripePayment').prop('disabled', false);
    });
</script>
@endpush

