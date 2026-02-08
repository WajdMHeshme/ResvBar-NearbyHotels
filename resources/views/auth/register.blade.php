
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ResvBar</title>
</head>
<body>
<x-guest-layout>

    <!-- Logo -->
    <div class="flex justify-center">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-32 h-32 object-contain">
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" class="text-sky-600 font-medium text-start" />
            <x-text-input
                id="name"
                class="block mt-1 w-full rounded-lg border border-sky-300 focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-sky-600 font-medium text-start" />
            <x-text-input
                id="email"
                class="block mt-1 w-full rounded-lg border border-sky-300 focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-sky-600 font-medium text-start" />
            <x-text-input
                id="password"
                class="block mt-1 w-full rounded-lg border border-sky-300 focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 shadow-sm"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sky-600 font-medium text-start" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full rounded-lg border border-sky-300 focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 shadow-sm"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <a class="text-sm text-sky-600 hover:text-sky-800 underline" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="w-full sm:w-auto bg-gradient-to-br from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white font-semibold py-2 px-6 rounded-lg">
                {{ __('Register') }}
            </x-primary-button>
        </div>

    </form>

</x-guest-layout>

</body>
</html>













