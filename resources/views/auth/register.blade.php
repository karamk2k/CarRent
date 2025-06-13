@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        {{-- Logo or Brand --}}
        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                Join Us
            </h2>
            <p class="mt-3 text-lg text-gray-600">
                Create your account and start your journey
            </p>
        </div>

        {{-- Registration Form Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6 transform transition-all duration-300 hover:shadow-2xl">
            <form id="register-form" class="space-y-6" novalidate>
                @csrf

                {{-- Name Field --}}
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" required
                               class="form-input pl-10 @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}"
                               placeholder="John Doe">
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Email Field --}}
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="form-input pl-10 @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com">
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Phone Number Field --}}
                <div class="space-y-2">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <input id="phone_number" name="phone_number" type="tel" required
                               class="form-input pl-10 @error('phone_number') border-red-500 @enderror"
                               placeholder="+1234567890"
                               minlength="10"
                               maxlength="20"
                               value="{{ old('phone_number') }}">
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Address Field --}}
                <div class="space-y-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input id="address" name="address" type="text" required
                               class="form-input pl-10 @error('address') border-red-500 @enderror"
                               placeholder="Enter your full address"
                               maxlength="255"
                               value="{{ old('address') }}">
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Date of Birth Field --}}
                <div class="space-y-2">
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="date_of_birth" name="date_of_birth" type="date" required
                               class="form-input pl-10 @error('date_of_birth') border-red-500 @enderror"
                               max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                               value="{{ old('date_of_birth') }}">
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Gender Field --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="gender" value="male" required
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300"
                                   {{ old('gender') == 'male' ? 'checked' : '' }}>
                            <span class="ml-3 block text-sm font-medium text-gray-700">Male</span>
                        </label>
                        <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="gender" value="female" required
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300"
                                   {{ old('gender') == 'female' ? 'checked' : '' }}>
                            <span class="ml-3 block text-sm font-medium text-gray-700">Female</span>
                        </label>
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Password Fields --}}
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="form-input pl-10 @error('password') border-red-500 @enderror"
                               placeholder="Create a strong password">
                    </div>
                    <div class="password-requirements text-sm text-gray-500 mt-1">
                        <p>Password must contain:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li class="requirement" data-requirement="length">At least 8 characters</li>
                            <li class="requirement" data-requirement="uppercase">One uppercase letter</li>
                            <li class="requirement" data-requirement="lowercase">One lowercase letter</li>
                            <li class="requirement" data-requirement="number">One number</li>
                            <li class="requirement" data-requirement="symbol">One special character</li>
                        </ul>
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="form-input pl-10"
                               placeholder="Confirm your password">
                    </div>
                    <div class="error-message text-sm text-red-500 hidden"></div>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit" class="btn-primary w-full relative" id="submit-button">
                        <span class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="submit-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="submit-text">Create Account</span>
                        </span>
                    </button>
                </div>
            </form>

            {{-- Login Link --}}
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary-dark transition-colors duration-200">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Notification utility function
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = $(`
        <div class="fixed top-4 right-4 z-50 transform transition-all duration-300 translate-x-full">
            <div class="rounded-lg shadow-lg p-4 max-w-sm w-full ${type === 'success' ? 'bg-green-50' : 'bg-red-50'} border ${type === 'success' ? 'border-green-200' : 'border-red-200'}">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success'
                            ? '<svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                            : '<svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                        }
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}">
                            ${message}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" class="inline-flex rounded-md p-1.5 ${type === 'success' ? 'text-green-500 hover:bg-green-100' : 'text-red-500 hover:bg-red-100'} focus:outline-none focus:ring-2 focus:ring-offset-2 ${type === 'success' ? 'focus:ring-green-600' : 'focus:ring-red-600'}">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);

    // Add to body
    $('body').append(notification);

    // Show notification
    setTimeout(() => {
        notification.removeClass('translate-x-full');
    }, 100);

    // Add click handler for dismiss button
    notification.find('button').on('click', function() {
        hideNotification(notification);
    });

    // Auto hide after 5 seconds
    setTimeout(() => {
        hideNotification(notification);
    }, 5000);
}

function hideNotification(notification) {
    notification.addClass('translate-x-full');
    setTimeout(() => {
        notification.remove();
    }, 300);
}

$(document).ready(function() {
    const form = $('#register-form');
    const submitButton = $('#submit-button');
    const submitSpinner = $('#submit-spinner');
    const submitText = $('#submit-text');
    const passwordInput = $('#password');
    const requirements = $('.requirement');

    // Password validation
    const passwordValidation = {
        length: (password) => password.length >= 8,
        uppercase: (password) => /[A-Z]/.test(password),
        lowercase: (password) => /[a-z]/.test(password),
        number: (password) => /[0-9]/.test(password),
        symbol: (password) => /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    // Update password requirements UI
    function updatePasswordRequirements(password) {
        Object.keys(passwordValidation).forEach(requirement => {
            const element = $(`.requirement[data-requirement="${requirement}"]`);
            if (passwordValidation[requirement](password)) {
                element.addClass('text-green-500').removeClass('text-gray-500');
            } else {
                element.addClass('text-gray-500').removeClass('text-green-500');
            }
        });
    }

    // Handle password input
    passwordInput.on('input', function() {
        updatePasswordRequirements($(this).val());
    });

    // Form submission
    form.on('submit', function(e) {
        e.preventDefault();

        // Reset previous errors
        $('.error-message').addClass('hidden').text('');
        $('.form-input').removeClass('border-red-500');

        // Show loading state
        submitButton.prop('disabled', true);
        submitSpinner.removeClass('hidden');
        submitText.text('Creating Account...');

        // Collect form data
        const formData = new FormData(this);

        // Submit form via AJAX
        $.ajax({
            url: '{{ route("register") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Show success message
                showNotification('Account created successfully! Redirecting...', 'success');

                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = response.redirect || '{{ route("home") }}';
                }, 1500);
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        const input = $(`[name="${field}"]`);
                        const errorMessage = errors[field][0];

                        input.addClass('border-red-500');
                        input.siblings('.error-message')
                            .removeClass('hidden')
                            .text(errorMessage);
                    });
                } else {
                    // Show general error
                    showNotification('An error occurred. Please try again.', 'error');
                }

                // Reset button state
                submitButton.prop('disabled', false);
                submitSpinner.addClass('hidden');
                submitText.text('Create Account');
            }
        });
    });

    // Real-time validation
    $('.form-input').on('input', function() {
        const input = $(this);
        const errorMessage = input.siblings('.error-message');

        if (input.val()) {
            input.removeClass('border-red-500');
            errorMessage.addClass('hidden');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.form-input {
    @apply block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400
           focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent
           transition duration-150 ease-in-out;
}

.btn-primary {
    @apply w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white
           bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary
           transition duration-150 ease-in-out;
}

.requirement {
    @apply transition-colors duration-200;
}

.requirement.text-green-500::before {
    content: '✓';
    @apply mr-1;
}

.requirement.text-gray-500::before {
    content: '○';
    @apply mr-1;
}

/* Add notification styles */
.notification-enter {
    transform: translateX(100%);
}

.notification-enter-active {
    transform: translateX(0);
    transition: transform 300ms ease-in-out;
}

.notification-exit {
    transform: translateX(0);
}

.notification-exit-active {
    transform: translateX(100%);
    transition: transform 300ms ease-in-out;
}
</style>
@endpush
@endsection