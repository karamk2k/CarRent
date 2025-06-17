@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    .login-container {
        background: linear-gradient(135deg, #f6f8fd 0%, #f1f4f9 100%);
    }

    .login-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-group input {
        transition: all 0.3s ease;
        padding-left: 2.5rem;
    }

    .input-group input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(53, 99, 233, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        transition: color 0.3s ease;
    }

    .input-group input:focus + .input-icon {
        color: var(--primary-color);
    }

    .btn-login {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(53, 99, 233, 0.2);
    }

    .btn-login .spinner {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .error-message {
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .error-message.show {
        transform: translateY(0);
        opacity: 1;
    }

    .remember-checkbox {
        position: relative;
        display: inline-block;
    }

    .remember-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: relative;
        display: inline-block;
        width: 18px;
        height: 18px;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .remember-checkbox input:checked ~ .checkmark {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .checkmark:after {
        content: '';
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .remember-checkbox input:checked ~ .checkmark:after {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="min-h-[90vh] flex items-center justify-center login-container py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 login-card p-8 rounded-2xl">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Welcome Back
            </h2>
            <p class="mt-3 text-gray-600">
                Sign in to your account to continue
            </p>
        </div>

        <div id="error-container" class="hidden">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p id="error-message" class="text-sm text-red-700"></p>
                    </div>
                </div>
            </div>
        </div>

        <form id="login-form" class="mt-8 space-y-6 login" >

            <div class="space-y-4">
                <div class="input-group">
                    <input id="email" name="email" type="email" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm"
                           placeholder="Email address"
                           value="{{ old('email') }}">
                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>

                <div class="input-group">
                    <input id="password" name="password" type="password" required
                           class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm"
                           placeholder="Password">
                    <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" class="form-checkbox">
                        <span class="checkmark"></span>
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-primary hover:text-primary/80 transition-colors duration-200">
                        Forgot password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary btn-login w-full flex justify-center items-center py-3 px-4 rounded-lg text-white font-medium">
                    <span class="button-text">Sign in</span>
                    <div class="spinner hidden"></div>
                </button>
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-primary hover:text-primary/80 transition-colors duration-200">
                    Create one now
                </a>
            </p>
        </div>
    </div>
</div>
@endsection


<script type="module">
$(document).ready(function() {
   const form = $('#login-form');
    const errorContainer = $('#error-container');
    const errorMessage = $('#error-message');
    const button = form.find('button[type="submit"]');
    const buttonText = button.find('.button-text');
    const spinner = button.find('.spinner');
    console.log(form)
    form.on('submit', function(e) {

        e.preventDefault();
        console.log(e)


        errorContainer.addClass('hidden');
        errorMessage.text('');
        button.prop('disabled', true);
        buttonText.addClass('opacity-0');
        spinner.removeClass('hidden');

        $.ajax({
            url: "/login",
            method: "POST",
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                window.location.href = "/";
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                errorContainer.removeClass('hidden');
                errorMessage.text(res?.message || 'Invalid credentials');

                errorContainer.addClass('animate-shake');
                setTimeout(() => errorContainer.removeClass('animate-shake'), 500);

                button.prop('disabled', false);
                buttonText.removeClass('opacity-0');
                spinner.addClass('hidden');
            }
        });

        return false;
    });

    $('.input-group input').on('focus blur', function(e) {
        const group = $(this).closest('.input-group');
        if (e.type === 'focus') {
            group.addClass('focused');
        } else {
            group.removeClass('focused');
        }
    });
});
</script>

