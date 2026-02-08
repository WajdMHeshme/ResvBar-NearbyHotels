<x-guest-layout>

    <!-- Logo -->
    <div class="flex justify-center mb-4">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-28 h-28 object-contain">
    </div>

    <!-- Description -->
    <div class="mb-6 text-sm text-slate-600 text-center leading-relaxed">
        {{ __('Forgot your password? No problem. Just enter your email address and we’ll send you a link to reset it.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status
        class="mb-4 text-sm text-green-600 text-center"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-5">
            <x-input-label
                for="email"
                :value="__('Email')"
                class="text-sky-600 font-medium text-start"
            />

            <x-text-input
                id="email"
                class="block mt-1 w-full rounded-lg border border-sky-300
                       focus:border-sky-500 focus:ring
                       focus:ring-sky-200 focus:ring-opacity-50"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-1 text-sm text-red-600"
            />
        </div>

        <!-- Action -->
        <div class="flex justify-center">
            <x-primary-button
                class="w-full bg-gradient-to-br from-sky-500 to-sky-600
                       hover:from-sky-600 hover:to-sky-700
                       text-white font-semibold py-2.5 rounded-lg
                       shadow-md transition"
            >
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
