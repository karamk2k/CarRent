<form id="rental-form" class="bg-white rounded-xl shadow-sm p-6">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                       class="form-input" 
                       min="{{ date('Y-m-d') }}" 
                       required>
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                       class="form-input" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                       required>
            </div>
        </div>

        <div>
            <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-2">Discount Code (Optional)</label>
            <input type="text" name="discount_code" id="discount_code" 
                   class="form-input" 
                   placeholder="Enter discount code">
        </div>

        <div class="border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Duration:</span>
                <span id="duration" class="font-medium">0 days</span>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Price per day:</span>
                <span class="font-medium">${{ number_format($car->price, 2) }}</span>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Discount:</span>
                <span id="discount-amount" class="font-medium text-green-600">$0.00</span>
            </div>
            
            <div class="flex justify-between items-center text-lg font-bold">
                <span>Total Price:</span>
                <span id="total-price" class="text-primary">${{ number_format($car->price, 2) }}</span>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-primary w-full" id="rent-button">
                <span class="spinner hidden"></span>
                <span>Rent Now</span>
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
$(document).ready(function() {
    const carPrice = {{ $car->price }};
    let discountAmount = 0;

    function calculateTotal() {
        const startDate = new Date($('#start_date').val());
        const endDate = new Date($('#end_date').val());
        
        if (startDate && endDate && startDate < endDate) {
            const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const total = (carPrice * duration) - discountAmount;
            
            $('#duration').text(duration + ' days');
            $('#total-price').text('$' + total.toFixed(2));
        }
    }

    $('#start_date, #end_date').on('change', function() {
        calculateTotal();
    });

    $('#discount_code').on('change', function() {
        const code = $(this).val();
        
        if (code) {
            $.ajax({
                url: '{{ route("discounts.validate") }}',
                method: 'POST',
                data: {
                    code: code,
                    car_id: {{ $car->id }},
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#discount_code').prop('disabled', true);
                },
                success: function(response) {
                    discountAmount = response.discount_amount;
                    $('#discount-amount').text('-$' + discountAmount.toFixed(2));
                    calculateTotal();
                    showNotification('Discount applied successfully!', 'success');
                },
                error: function() {
                    discountAmount = 0;
                    $('#discount-amount').text('$0.00');
                    calculateTotal();
                    showNotification('Invalid discount code', 'error');
                },
                complete: function() {
                    $('#discount_code').prop('disabled', false);
                }
            });
        } else {
            discountAmount = 0;
            $('#discount-amount').text('$0.00');
            calculateTotal();
        }
    });

    $('#rental-form').on('submit', function(e) {
        e.preventDefault();
        const button = $('#rent-button');
        const spinner = button.find('.spinner');
        
        $.ajax({
            url: '{{ route("rentals.store") }}',
            method: 'POST',
            data: $(this).serialize() + '&car_id={{ $car->id }}',
            beforeSend: function() {
                button.prop('disabled', true);
                spinner.removeClass('hidden');
            },
            success: function(response) {
                showNotification('Car rented successfully!', 'success');
                window.location.href = response.redirect_url;
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Error processing rental. Please try again.';
                showNotification(message, 'error');
            },
            complete: function() {
                button.prop('disabled', false);
                spinner.addClass('hidden');
            }
        });
    });
});
</script>
@endpush 