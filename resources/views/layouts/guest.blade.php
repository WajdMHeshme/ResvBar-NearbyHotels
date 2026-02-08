<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ResvBar') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-sky-100 via-white to-sky-50 overflow-hidden font-inter">

    <!-- Background Logo faint -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="opacity-10 w-3/4 h-auto object-contain">
    </div>

    <!-- Card -->
    <div class="relative z-10 w-full max-w-md p-10 sm:p-12 rounded-3xl bg-white/95 border border-cyan-200 text-center transition-transform duration-500 ease-in-out">
        {{ $slot }}
    </div>

</body>
</html>
