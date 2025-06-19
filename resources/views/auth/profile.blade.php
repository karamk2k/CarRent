@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">


        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Profile Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Profile Information</h3>

                        @if (session('status'))
                            <div class="mt-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded relative" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="PUT" class="mt-6 space-y-6" id="profile-form">


                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                       value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                <input type="email" name="email" id="email"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="phone_number" id="phone"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                       value="{{ old('phone', auth()->user()->phone_number) }}">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" id="address"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                          rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Profile Picture -->
   <div class="mt-6 bg-white shadow-sm rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('storage/defaults/images.png') }}"
                    alt="Profile Picture"
                    class="h-16 w-16 rounded-full object-cover border border-gray-300 shadow-sm" />
            </div>

            <div class="text-right">
                <label for="profile_picture" class="inline-block cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm text-sm">
                    Upload New
                    <input type="file" name="profile_picture" id="profile_picture"
                           accept="image/*"
                           class="hidden">
                </label>
            </div>
        </div>

        @error('profile_picture')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>




                <!-- Change Password -->
                <div class="mt-6 bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Change Password</h3>
                        <form  class="mt-6 space-y-6" id="change-password-form">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="new_password" id="password"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="password_confirmation"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Status -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Account Status</h3>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Email Verified</span>
                                @if(auth()->user()->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Not Verified
                                    </span>
                                    <button id="verify-email" class="px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded shadow-sm transition duration-150 ease-in-out">
                                        Verify Email
                                    </button>

                                @endif
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Member Since</span>
                                    <span class="text-sm text-gray-900">
                                        {{ auth()->user()->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Links</h3>
                        <div class="mt-4 space-y-4">
                            <a href="{{ route('home') }}" class="block text-sm text-primary hover:text-primary/80">
                                My Rentals
                            </a>
                            <a href="{{ route('favorites.index') }}" class="block text-sm text-primary hover:text-primary/80">
                                Favorite Cars
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify email model -->
<div id="verifyEmailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Verify Your Email</h2>

        <p class="text-sm text-gray-600 mb-4">
            Enter the 4-digit verification code sent to your email.
        </p>

        <!-- Code input -->
        <form method="POST" action="" id="verifyCodeForm">
            @csrf
            <input type="text" name="otp" maxlength="4" pattern="\d{4}" required
                class="w-full border border-gray-300 rounded px-3 py-2 mb-4 text-center tracking-widest text-lg"
                placeholder="----" inputmode="numeric">

            <div class="flex justify-end gap-2">
                <button type="button" id="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Close
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Verify Code
                </button>
            </div>
        </form>

        <!-- Resend Button (hidden initially) -->
        <div class="mt-4 text-sm text-gray-600">
            <button id="resendOtp" class="text-blue-500 hover:text-blue-700 hidden">
                Resend Code
            </button>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function(){

    // Handle profile update form submission
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const method = "PUT"
        const data = form.serialize();

        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
                showToast('Profile updated successfully!', 'success');
                location.reload(); // Reload the page to reflect changes
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                console.log(xhr.responseJSON.message);
                let errorMessage = '';
                $.each(errors, function(key, messages) {
                    errorMessage += messages.join(', ') + '\n';
                });
                showToast(errorMessage.trim(), 'error');
            }
        });
    });

    function send_otp(){
         $.ajax({
            url:"{{ route('auth.send-otp') }}",
            method: "POST",
            success: function(response) {
                showToast('Verification code sent to your email!', 'success');
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showToast(res?.message || 'Failed to send verification code', 'error');
            }
        });
    }

    $('#verify-email').on('click', function(e) {
        e.preventDefault();
        send_otp();
        $('#verifyEmailModal').removeClass('hidden');
    });

    $('#verifyCodeForm').on('submit',function(e){
        e.preventDefault();
        const form = $(this);
        const url = "{{ route('auth.verify-otp') }}";
        const data = form.serialize();

        $.ajax({
            url: url,
            method: "POST",
            data: data,
            success: function(response) {
                showToast('Email verified successfully!', 'success');
                $('#verifyEmailModal').addClass('hidden');
                location.reload(); // Reload the page to reflect changes
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                $("#resendOtp").removeClass('hidden');
                $("#resendOtp").on('click', function() {
                    send_otp();
                });
                showToast(res?.message || 'Invalid verification code', 'error');
            }
        });
    });

    $('#closeModal').on('click', function() {
        $('#verifyEmailModal').addClass('hidden');
    });
    $("#change-password-form").on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = "{{ route('auth.change-password') }}";
        const data = form.serialize();

        $.ajax({
            url: url,
            method: "PUT",
            data: data,
            success: function(response) {
                showToast('Password changed successfully!', 'success');
                form[0].reset();
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = '';
                $.each(errors, function(key, messages) {
                    errorMessage += messages.join(', ') + '\n';
                });
                showToast(errorMessage.trim(), 'error');
            }
        });
    });

    $("#profile_picture").on('change', function() {
        const file = this.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('profile_picture', file);

            $.ajax({
                url: "{{ route('profile.change-image') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    showToast('Profile picture updated successfully!', 'success');
                    location.reload();
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    showToast(res?.message || 'Failed to update profile picture', 'error');
                }
            });
        }
    });

});
</script>
@endpush
