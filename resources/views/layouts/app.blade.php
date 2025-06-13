<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Add Stripe permissions policy -->
    <meta http-equiv="Permissions-Policy" content="private-state-token-redemption=(), private-state-token-issuance=()">

    <title>Car Rent - @yield('title')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #3563E9;
            --secondary-color: #1A202C;
            --accent-color: #54A6FF;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* Loading Spinner */
        .spinner {
            display: none;
            width: 24px;
            height: 24px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast Notifications */
        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            z-index: 50;
            display: none;
        }

        .toast-success {
            background-color: #10B981;
        }

        .toast-error {
            background-color: #EF4444;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Toast Container -->
    <div id="toast" class="toast"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md shadow-sm z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="text-2xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">CarRent</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">

                        <a href="{{ route('favorites.index.page') }}" class="text-gray-600 hover:text-primary transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-50 relative">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>Favorites</span>
                            </div>
                        </a>
                        <a href="{{ route('user.history') }}" class="text-gray-600 hover:text-primary transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>My History</span>
                            </div>
                        </a>
                        <a href="{{ route('profile') }}" class="text-gray-600 hover:text-primary transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Profile</span>
                            </div>
                        </a>
                        <button type="button" id="logout-form"
                                class="text-gray-600 hover:text-red-500 transition-colors duration-200 px-3 py-2 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>



                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-500">
                <p>&copy; {{ date('Y') }} CarRent. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <script>
        // AJAX Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = $('#toast');
            toast.removeClass('toast-success toast-error')
                 .addClass(`toast-${type}`)
                 .html(message)
                 .fadeIn();

            setTimeout(() => {
                toast.fadeOut();
            }, 3000);
        }

        // Loading State
        function setLoading(element, isLoading) {
            const $element = $(element);
            const $spinner = $element.find('.spinner');

            if (isLoading) {
                $element.prop('disabled', true);
                $spinner.show();
            } else {
                $element.prop('disabled', false);
                $spinner.hide();
            }
        }

        // AJAX Form Submission
        function submitForm(form, options = {}) {
            const $form = $(form);
            const $submitButton = $form.find('[type="submit"]');

            $form.on('submit', function(e) {
                e.preventDefault();

                setLoading($submitButton, true);

                $.ajax({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: $form.serialize(),
                    success: function(response) {
                        if (options.success) {
                            options.success(response);
                        } else {
                            showToast(response.message || 'Operation successful');
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'An error occurred';
                        showToast(message, 'error');

                        if (options.error) {
                            options.error(xhr);
                        }
                    },
                    complete: function() {
                        setLoading($submitButton, false);

                        if (options.complete) {
                            options.complete();
                        }
                    }
                });
            });
        }

        // AJAX GET Request
        function fetchData(url, options = {}) {
            const $element = $(options.target);

            if ($element) {
                setLoading($element, true);
            }

            return $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (options.success) {
                        options.success(response);
                    }
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to fetch data', 'error');

                    if (options.error) {
                        options.error(xhr);
                    }
                },
                complete: function() {
                    if ($element) {
                        setLoading($element, false);
                    }

                    if (options.complete) {
                        options.complete();
                    }
                }
            });
        }

        // Handle Logout
        $('#logout-form').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('logout') }}",
                method: 'POST',
                success: function() {
                    window.location.href = '/login';
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.message || 'Failed to logout', 'error');
                }
            });
        });



    </script>

    @stack('scripts')
</body>
</html>