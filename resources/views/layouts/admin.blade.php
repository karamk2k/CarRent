<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="storge_path" content="{{ asset('storage/') }}">
    <!-- Common Admin Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .modal { display: none; }
        .modal.active { display: block; }
        .nav-item.active { background-color: rgb(17, 24, 39); }
        .nav-item.active svg { color: white; }
        .nav-item.active span { color: white; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transition-transform duration-300 transform" id="sidebar">
            <div class="flex items-center justify-between h-16 bg-gray-900 px-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span class="text-xl font-semibold">CarRent</span>
                </a>
                <button id="sidebar-close" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- User Info -->
            <div class="px-4 py-3 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center">
                            <span class="text-lg font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-5 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Cars Management -->
                <div class="space-y-1">
                    <div class="px-2 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Cars Management
                    </div>
                    <a href="{{ route('admin.cars.index') }}"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.cars.*') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Cars</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Categories</span>
                    </a>
                </div>

                <!-- User Management -->
                <div class="space-y-1">
                    <div class="px-2 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        User Management
                    </div>
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.users.index') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.users.ban.index') }}"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.users.ban.index') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        <span>Banned Users</span>
                    </a>
                </div>

                <!-- Promotions -->
                <div class="space-y-1">
                    <div class="px-2 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Promotions
                    </div>
                    <a href="{{ route('admin.discounts.index') }}"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.discounts.*') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Discounts</span>
                    </a>
                </div>

                <!-- Rentals Management -->
                <div class="space-y-1">
                    <div class="px-2 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Rentals Management
                    </div>
                    <a href="{{ route('admin.rentals.index') }}"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md {{ request()->routeIs('admin.rentals.index') ? 'active' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Rentals</span>
                    </a>
                </div>

                <!-- Quick Links -->
                <div class="space-y-1">
                    <div class="px-2 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Quick Links
                    </div>
                    <a href="{{ route('home') }}" target="_blank"
                       class="nav-item group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>View Site</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <button id="sidebar-toggle" class="px-4 text-gray-500 focus:outline-none focus:bg-gray-100 focus:text-gray-600 lg:hidden">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <div class="flex items-center px-4">
                                <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->


                            <!-- Profile Dropdown -->
                            <div class="relative" id="profile-dropdown">
                                <button type="button" id="profile-button" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                        <form id="logout-form"  class="block">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Common JavaScript -->
    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Profile Dropdown
        $(document).ready(function() {
            const $profileButton = $('#profile-button');
            const $profileMenu = $('#profile-menu');

            // Toggle dropdown
            $profileButton.on('click', function(e) {
                e.stopPropagation();
                $profileMenu.toggleClass('hidden');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#profile-dropdown').length) {
                    $profileMenu.addClass('hidden');
                }
            });

            // Prevent dropdown from closing when clicking inside
            $profileMenu.on('click', function(e) {
                e.stopPropagation();
            });
        });

        // Modal handling
        class Modal {
            constructor() {
                this.modal = null;
                this.submitCallback = null;
            }

            bindEvents() {
                this.modal.find('#modal-close').on('click', () => this.hide());
                this.modal.find('#modal-submit').on('click', () => this.submit());
            }

            show(title, content, submitCallback, hideFooter = false) {
                // Remove any existing modals except the template
                $('.modal').not('#modal-template').remove();

                // Clone the template and append to body
                this.modal = $('#modal-template').clone().attr('id', '');
                this.modal.appendTo('body');
                this.bindEvents();

                this.modal.find('#modal-title').text(title);
                this.modal.find('#modal-content').html(content);
                this.submitCallback = submitCallback;
                this.modal.addClass('active');

                // Hide the default footer if requested
                if (hideFooter) {
                    this.modal.find('#modal-footer').hide();
                } else {
                    this.modal.find('#modal-footer').show();
                }
            }

            hide() {
                if (this.modal) {
                    this.modal.removeClass('active');
                    this.submitCallback = null;
                    setTimeout(() => {
                        if (this.modal) this.modal.remove();
                        this.modal = null;
                    }, 300);
                }
            }

            submit() {
                if (this.submitCallback) {
                    this.submitCallback();
                }
            }
        }

        // Toast notifications
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Sidebar toggle for mobile
        $('#sidebar-toggle').on('click', function() {
            $('#sidebar').toggleClass('-translate-x-full');
        });

        $('#sidebar-close').on('click', function() {
            $('#sidebar').addClass('-translate-x-full');
        });

        // Initialize modal
        window.modal = new Modal();

        // Handle AJAX errors globally
        $(document).ajaxError(function(event, jqXHR, settings, error) {
            if (jqXHR.status === 401) {
                window.location.href = '/login';
            } else if (jqXHR.status === 403) {
                Toast.fire({
                    icon: 'error',
                    title: 'You do not have permission to perform this action'
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: jqXHR.responseJSON?.message || 'An error occurred'
                });
            }
        });

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

    <!-- Minimal Modal Template (no footer/buttons) -->
    <div id="modal-template" class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title"></h3>
                <div class="mt-2 px-7 py-3">
                    <div id="modal-content"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
