    {{-- Car Grid Container --}}
    <div id="cars-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

    {{-- Loading State --}}
    <div id="loading-state" class="hidden">
        <div class="col-span-full text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading cars...</p>
        </div>
    </div>

    {{-- No Results State --}}
    <div id="no-results" class="hidden">
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No cars found</h3>
            <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
    </div>

    {{-- Rental Modal --}}
    <div id="rentalModal" class="modal">
        <div class="modal-content">
            {{-- Error Message Section --}}
            <div id="modal-error" class="hidden mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                        <p id="modal-error-message" class="mt-1 text-sm text-red-700"></p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" onclick="hideModalError()" class="text-red-400 hover:text-red-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-header">
                <h2 class="text-xl font-semibold">Rent Car</h2>
                <span class="close" onclick="closeRentalModal()">&times;</span>
            </div>

            <form id="rentalForm">
                @csrf
                <input type="hidden" id="modalCarId" name="car_id">
                <input type="hidden" id="modalCarPrice" name="car_price">
                <input type="hidden" name="payment_method" value="stripe">

                <div class="form-group">
                    <label for="modalStartDate">Start Date</label>
                    <input type="date" id="modalStartDate" name="start_date" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="modalEndDate">End Date</label>
                    <input type="date" id="modalEndDate" name="end_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>

                <div class="form-group">
                    <label for="modalDiscount">Discount Code (Optional)</label>
                    <input type="text" id="modalDiscount" name="discount_name" placeholder="Enter discount code">
                </div>

                {{-- Stripe Payment Section --}}
                <div id="stripe-payment-section" class="mt-4 p-4 border rounded-lg">
                    <h3 class="text-lg font-medium mb-3">Payment Information</h3>
                    <div id="card-element" class="p-3 border rounded bg-gray-50"></div>
                    <div id="card-errors" class="text-red-600 mt-2 text-sm"></div>
                    <div id="stripe-blocked-message" class="hidden mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
                        <p class="font-medium">Payment system blocked</p>
                        <p class="text-sm mt-1">It seems your browser is blocking our payment system. Please:</p>
                        <ul class="text-sm list-disc list-inside mt-2">
                            <li>Disable your ad blocker for this site</li>
                            <li>Or try using a different browser</li>
                            <li>Or contact support for assistance</li>
                        </ul>
                    </div>
                </div>

                <div class="price-summary mt-4">
                    <div class="price-row">
                        <span>Base Price:</span>
                        <span id="modalBasePrice">$0.00</span>
                    </div>
                    <div class="price-row">
                        <span>Discount:</span>
                        <span id="modalDiscountAmount">-$0.00</span>
                    </div>
                    <div class="price-row total">
                        <span>Total:</span>
                        <span id="modalTotalPrice">$0.00</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeRentalModal()">Cancel</button>
                    <button type="submit" id="submit-rental" class="btn-primary">
                        <span class="spinner hidden"></span>
                        <span>Pay & Confirm Rental</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Car Card Template --}}
    <template id="car-card-template">
        <div class="car-card bg-white rounded-lg shadow-md overflow-hidden">
            <div class="relative">
                <img class="w-full h-48 object-cover" src="" alt="Car Image">
                <button class="favorite-btn absolute top-2 right-2 p-2 rounded-full bg-white/80 hover:bg-white transition-colors" data-car-id="">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2"></h3>
                <p class="text-gray-600 mb-4"></p>
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-primary"></span>
                    <button class="rent-btn btn-primary" data-car-id="">Rent Now</button>
                </div>
            </div>
        </div>
    </template>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
    // Set authentication state
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};


    // Initialize Stripe with error handling
    let stripe;
    try {
        stripe = Stripe('{{ config('services.stripe.key') }}');
    } catch (error) {
        console.error('Stripe initialization error:', error);
        document.getElementById('stripe-blocked-message').classList.remove('hidden');
        document.getElementById('card-element').style.display = 'none';
    }

    // Only initialize elements if Stripe loaded successfully
    let card;
    if (stripe) {
        const elements = stripe.elements();
        card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#dc2626',
                    iconColor: '#dc2626'
                }
            }
        });

        // Mount the card element
        card.mount('#card-element');

        // Handle real-time validation errors
        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    }

    // Add these functions at the top of your script section
    function showModalError(message) {
        const errorDiv = document.getElementById('modal-error');
        const errorMessage = document.getElementById('modal-error-message');
        errorMessage.textContent = message;
        errorDiv.classList.remove('hidden');
        // Scroll to the top of the modal
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideModalError() {
        const errorDiv = document.getElementById('modal-error');
        errorDiv.classList.add('hidden');
    }

$('#rentalForm').on('submit', async function(e) {
    e.preventDefault();
    hideModalError();

    if (!stripe || !card) {
        showModalError('Payment system is not available. Please try a different browser or disable your ad blocker.');
        return;
    }

    const $submitBtn = $('#submit-rental');
    const $spinner = $submitBtn.find('.spinner');
    $submitBtn.prop('disabled', true);
    $spinner.removeClass('hidden');

    try {
        let formData = $(this).serializeArray();
        console.log('Form data before processing:', formData);

        const response = await $.ajax({
            url: '/rentals',
            method: 'POST',
            data: formData
        });

        if (!response.data || !response.data.client_secret) {
            console.error('Invalid response from server:', response);
            throw new Error('Invalid response from server');
        }

        const result = await stripe.confirmCardPayment(response.data.client_secret, {
            payment_method: {
                card: card,
            }
        });

        if (result.error) {
            throw new Error(result.error.message);

        }

        if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
            const confirmResponse = await $.ajax({
                url: `/rentals/${response.data.rental.id}/confirm-payment`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });

            // Reset form and UI
            $('#rentalForm')[0].reset();
            card.clear();
            $('#card-errors').text('');
            $('#modalBasePrice').text('$0.00');
            $('#modalDiscountAmount').text('-$0.00');
            $('#modalTotalPrice').text('$0.00');

            showToast('Car rented successfully!', 'success');
            closeRentalModal();

            if (typeof CarsGrid !== 'undefined') {
                CarsGrid.loadCars();
            }
        } else {
            throw new Error('Payment was not successful');
        }

    } catch (error) {
        console.error('Rental error:', error);
        showModalError(error.message || 'Error processing rental');
        window.location.href = "/my-history?error=" + encodeURIComponent("payment_error");

    } finally {
        $submitBtn.prop('disabled', false);
        $spinner.addClass('hidden');
    }
});



    function closeRentalModal() {
        document.getElementById('rentalModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('rentalForm').reset();
        document.getElementById('card-errors').textContent = '';
        hideModalError(); // Hide any errors when closing
        if (card) {
            card.clear();
        }
        document.getElementById('modalBasePrice').textContent = '$0.00';
        document.getElementById('modalDiscountAmount').textContent = '-$0.00';
        document.getElementById('modalTotalPrice').textContent = '$0.00';
    }

    // Global function to open modal
    function showRentalModal(carId, carPrice) {
        try {
            // Reset form
            document.getElementById('rentalForm').reset();
            document.getElementById('card-errors').textContent = '';

            // Set car data
            document.getElementById('modalCarId').value = carId;
            document.getElementById('modalCarPrice').value = carPrice;

            // Show modal
            document.getElementById('rentalModal').style.display = 'block';
            document.body.style.overflow = 'hidden';


            updatePrice();
        } catch (error) {
            console.error('Error opening modal:', error);
            showToast('Error opening rental form', 'error');
        }
    }


    document.getElementById('modalStartDate').addEventListener('change', updatePrice);
    document.getElementById('modalEndDate').addEventListener('change', updatePrice);
    document.getElementById('modalDiscount').addEventListener('change', updatePrice);

    function updatePrice() {
        const startDate = new Date(document.getElementById('modalStartDate').value);
        const endDate = new Date(document.getElementById('modalEndDate').value);
        const basePrice = parseFloat(document.getElementById('modalCarPrice').value);

        if (startDate && endDate && !isNaN(startDate) && !isNaN(endDate)) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            const totalBasePrice = basePrice * days;

            document.getElementById('modalBasePrice').textContent = `$${totalBasePrice.toFixed(2)}`;

            const discountCode = document.getElementById('modalDiscount').value;
            if (discountCode) {
                checkDiscount(discountCode, totalBasePrice);
            } else {
                document.getElementById('modalDiscountAmount').textContent = '-$0.00';
                document.getElementById('modalTotalPrice').textContent = `$${totalBasePrice.toFixed(2)}`;
            }
        }
    }

    function checkDiscount(code, basePrice) {
        fetch(`/discounts/check/${code}`)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    const discountAmount = (basePrice * data.percentage / 100);
                    const totalPrice = basePrice - discountAmount;

                    document.getElementById('modalDiscountAmount').textContent = `-$${discountAmount.toFixed(2)}`;
                    document.getElementById('modalTotalPrice').textContent = `$${totalPrice.toFixed(2)}`;
                } else {
                    showToast('Invalid discount code', 'error');
                    document.getElementById('modalDiscountAmount').textContent = '-$0.00';
                    document.getElementById('modalTotalPrice').textContent = `$${basePrice.toFixed(2)}`;
                }
            })
            .catch(() => {
                showToast('Error checking discount', 'error');
            });
    }

    const CarsGrid = {
        init() {
            // Initialize favorites
            this.initFavorites();
            // Initialize rental modal
            this.initRentalModal();
            // Load initial cars
            this.loadCars();
        },

        initFavorites() {
            $(document).on('click', '.favorite-btn', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const button = $(e.currentTarget);
                const carId = button.data('car-id');
                const isFavorite = button.hasClass('text-red-500');

                // Only allow favorites for authenticated users
                if (!window.isAuthenticated) {
                    showToast('Please login to manage favorites', 'warning');
                    return;
                }

                const url = isFavorite ? '/favorites/remove' : '/favorites/add';
                const method = isFavorite ? 'DELETE' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: JSON.stringify({ car_id: carId }),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    success: (response) => {
                        if (response.success) {
                            button.toggleClass('text-red-500 text-gray-400');
                            showToast(response.message, 'success');
                        } else {
                            showToast(response.message || 'Error updating favorite status', 'error');
                        }
                    },
                    error: (xhr) => {
                        console.error('Favorite error:', xhr);
                        showToast(xhr.responseJSON?.message || 'Error updating favorite status', 'error');
                    }
                });
            });
        },


        createCarCard(car) {
            const card = document.createElement('div');
            const carData = car.car || car;


            const isAvailable = carData.available_at === null;
            const availabilityClass = isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';


            const hasPendingRental = {{ auth()->check() ? (auth()->user()->hasPendingRental() ? 'true' : 'false') : 'false' }};
            const hasOngoingRental = {{ auth()->check() ? (auth()->user()->hasOngoingRental() ? 'true' : 'false') : 'false' }};
            const canRent = isAvailable && !hasPendingRental && !hasOngoingRental;

            // Format availability text
            let availabilityText = isAvailable ?
                `Available from ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}` :
                carData.available_at ?
                    `Available from ${new Date(carData.available_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}` :
                    'Not Available';

            // Add card classes
            card.className = `bg-white rounded-xl shadow-sm overflow-hidden car-card hover:shadow-lg transition-shadow duration-300 ${!canRent ? 'opacity-75' : ''}`;

            // Escape special characters
            const safeId = String(carData.id).replace(/"/g, '&quot;');
            const safePrice = String(carData.price).replace(/"/g, '&quot;');

            // Set card HTML
            card.innerHTML = `
                <div class="relative">
                    <img src="${carData.image_url || ''}" alt="${carData.name || ''}" class="w-full h-48 object-cover car-image ${!canRent ? 'grayscale' : ''}">
                    <div class="absolute top-4 right-4 flex flex-col gap-2">
                        <div class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium car-card-category">
                            ${carData.type || carData.category || ''}
                        </div>
                        <div class="${availabilityClass} backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                            ${availabilityText}
                        </div>
                        ${hasPendingRental ? `
                        <div class="bg-yellow-100 text-yellow-800 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                            You have a pending rental
                        </div>
                        ` : ''}
                        ${hasOngoingRental ? `
                        <div class="bg-orange-100 text-orange-800 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                            You have an ongoing rental
                        </div>
                        ` : ''}
                    </div>
                    <button class="favorite-btn absolute top-4 left-4 p-2 rounded-full bg-white/80 hover:bg-white transition-colors ${carData.is_favorite ? 'text-red-500' : 'text-gray-400'}"
                            data-car-id="${safeId}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 car-card-name ">${carData.name || ''}</h3>
                            <p class="text-gray-500 car-card-details " >
                                ${carData.brand || carData.model || ''} • ${carData.year ? `  ${carData.year}` : ''}
                            </p>
                        </div>
                        <div class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="ml-1 text-gray-600">${carData.rating || '4.5'}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="car-card-transmissions">${carData.transmission || carData.transmissions || ''}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ${carData.fuel_type || ''}
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-2xl font-bold text-primary car-card-price" >$${parseFloat(carData.price || 0).toFixed(2)}</span>
                            <span class="text-gray-500">/day</span>
                        </div>
                        <button type="button"
                                class="btn-primary rent-now-btn ${!canRent ? 'opacity-50 cursor-not-allowed' : ''}"
                                data-car-id="${safeId}"
                                data-car-price="${safePrice}"

                                ${!canRent ? 'disabled' : ''}>
                            ${hasOngoingRental ? 'Ongoing Rental' : (hasPendingRental ? 'Pending Rental' : (isAvailable ? 'Rent Now' : 'Not Available'))}
                        </button>
                    </div>
                </div>
            `;


            const rentBtn = card.querySelector('.rent-now-btn');
            if (rentBtn && canRent) {
                rentBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (hasPendingRental) {
                        showModalError('You have a pending rental. Please complete or cancel your pending rental before renting another car.');
                        return;
                    }
                    if (hasOngoingRental) {
                        showModalError('You have an ongoing rental. Please return your current car before renting another one.');
                        return;
                    }
                    const id = rentBtn.getAttribute('data-car-id');
                    const price = rentBtn.getAttribute('data-car-price');
                    showRentalModal(id, price);
                });
            }

            return card;
        },

        // Render the entire grid of cars
        renderCarsGrid(cars) {
            const $carsGrid = $('#cars-grid');
            const $noResults = $('#no-results');

            if (!cars || cars.length === 0) {
                $carsGrid.html($noResults.html());
                return;
            }

            $carsGrid.empty();
            cars.forEach(car => {
                $carsGrid.append(this.createCarCard(car));
            });

        },

        // Load cars from the API
        loadCars(url = '/cars') {
            const $loadingState = $('#loading-state');
            const $carsGrid = $('#cars-grid');
            const $noResults = $('#no-results');

            $loadingState.removeClass('hidden');
            $carsGrid.addClass('opacity-50');
            $noResults.addClass('hidden');

            $.ajax({
                url: url,
                method: 'GET',
                success: (response) => {
                    if (response.data && response.data.length > 0) {
                        this.renderCarsGrid(response.data);
                        $noResults.addClass('hidden');
                         const categories = response.data;
                        const $categorySelect = $('#category');

                        // Clear existing options except the default one
                        $categorySelect.find('option:not([value=""])').remove();

                        categories.forEach(category => {
                            $categorySelect.append(
                                $('<option></option>')
                                    .val(category.category)
                                    .text(category.category)
                            );
                        });
                    } else {
                        this.renderCarsGrid([]);
                        $noResults.removeClass('hidden');
                    }
                },
                error: (xhr) => {
                    console.error('Error loading cars:', xhr);
                    $carsGrid.html(`
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">Error Loading Cars</h3>
                            <p class="mt-1 text-gray-500">Please try again later</p>
                        </div>
                    `);
                },
                complete: () => {
                    $loadingState.addClass('hidden');
                    $carsGrid.removeClass('opacity-50');
                }
            });
        },

        // Add rental modal functions
        openRentalModal(car) {
            if (!window.isAuthenticated) {
                showToast('Please login to rent a car', 'warning');
                window.location.href = '/login';
                return;
            }

            if (!car || !car.id) {
                showToast('Invalid car data', 'error');
                return;
            }

            try {
                // Reset form
                $('#rental-form')[0].reset();

                // Set car data
                $('#rental-car-id').val(car.id);
                $('#rental-car-price').val(car.price);

                // Show modal
                $('#rental-modal').fadeIn(200);
                $('body').addClass('overflow-hidden');

                // Update price summary
                this.updatePriceSummary();
            } catch (error) {
                console.error('Error opening rental modal:', error);
                showToast('Error opening rental form', 'error');
            }
        },

        closeRentalModal() {
            $('#rental-modal').fadeOut(200);
            $('body').removeClass('overflow-hidden');
            $('#rental-form')[0].reset();
        },

        updatePriceSummary() {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($('#end_date').val());
            const basePrice = parseFloat($('#rental-car-price').val());

            if (startDate && endDate && !isNaN(startDate) && !isNaN(endDate)) {
                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                const totalBasePrice = basePrice * days;

                $('#base-price').text(`$${totalBasePrice.toFixed(2)}`);


                const discountCode = $('#discount_name').val();
                if (discountCode) {
                    this.checkDiscount(discountCode, totalBasePrice);
                } else {
                    $('#discount-amount').text('-$0.00');
                    $('#total-price').text(`$${totalBasePrice.toFixed(2)}`);
                }
            }
        },

        checkDiscount(code, basePrice) {
            $.get(`/discounts/check/${code}`, (response) => {
                if (response.valid) {
                    const discountAmount = (basePrice * response.percentage / 100);
                    const totalPrice = basePrice - discountAmount;

                    $('#discount-amount').text(`-$${discountAmount.toFixed(2)}`);
                    $('#total-price').text(`$${totalPrice.toFixed(2)}`);
                } else {
                    showToast('Invalid discount code', 'error');
                    $('#discount-amount').text('-$0.00');
                    $('#total-price').text(`$${basePrice.toFixed(2)}`);
                }
            }).fail(() => {
                showToast('Error checking discount', 'error');
            });
        },

        // Initialize rental modal handlers
        initRentalModal() {
            const $modal = $('#rental-modal');
            const $form = $('#rental-form');

            // Close modal handlers
            $('#close-modal, #cancel-rental, #rental-modal').on('click', (e) => {
                if (e.target === e.currentTarget) {
                    this.closeRentalModal();
                }
            });

            // Prevent modal close when clicking inside the modal content
            $modal.find('.relative').on('click', (e) => {
                e.stopPropagation();
            });

            // Date change handlers
            $('#start_date, #end_date').on('change', () => {
                this.updatePriceSummary();
            });

            // Discount code handler
            $('#discount_name').on('change', () => {
                this.updatePriceSummary();
            });

            // Form submission

        }






    };

    // Initialize when document is ready
    $(document).ready(() => {
        CarsGrid.init();
    });



function search(name = "",categorySer="",price = "", transmission = "") {
  const $cards = $('.car-card');
  console.log('Total cards found:', $cards.length);

  $cards.each(function (i) {
    const $card = $(this);

    const nameEl = $card.find('.car-card-name').text().toLowerCase().trim();
    const priceEl = $card.find('.car-card-price').text().trim();
    const transmissionEl = $card.find('.car-card-transmissions').text().toLowerCase().trim();
    const priceInt = Math.floor(parseFloat(priceEl.replace(/[^0-9.]/g, '')));
    const categoryEl = $card.find('.car-card-category').text().toLowerCase().trim();


    if (
      nameEl.toLowerCase().includes(name.toLowerCase()) &&
      transmissionEl.toLowerCase().includes(transmission.toLowerCase())&&
      categoryEl.toLowerCase().includes(categorySer.toLowerCase()
  )

    ) {
      if (!price || priceInt <= parseInt(price)) {
        console.log(`Card #${i + 1} matches filter: name="${nameEl}", price="${priceEl}", transmission="${transmissionEl}"`);
        $card.removeClass('hidden'); // Show it
      } else {
        console.log(`Card #${i + 1} does not match price filter: ${priceInt} > ${price}`);
        $card.addClass("hidden"); // Hide it
      }
    } else {
      console.log(`Hiding card #${i + 1} with name "${nameEl}" due to name or transmission mismatch`);
      $card.addClass("hidden"); // Hide it
    }
  });
}

$("#quick-search").on("input", function () {
  const name = $(this).val();
  search(name);
});

$("#filter-form").on("submit", function (e) {
  e.preventDefault();
    const name = $("#quick-search").val();
    const category = $("#category").val();
    const price = $("#price").val();
    const transmission = $("#transmission").val();
    search(name, category, price, transmission);

});

    </script>
    @endpush

    @push('styles')
    <style>
           .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500;
    }
    </style>
    @endpush
