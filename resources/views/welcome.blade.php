<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>ResvBAR</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="relative flex items-center justify-center min-h-screen
             bg-gradient-to-br from-sky-100 via-white to-sky-50 overflow-hidden">

    <!-- Background Logo faint (unchanged) -->
    <div class="absolute inset-0 flex items-center justify-center">
        <img
            src="{{ asset('assets/images/logo.png') }}"
            alt="Logo"
            class="opacity-10 w-3/4 h-auto object-contain">
    </div>

    <!-- Card -->
    <div
        class="relative z-10 w-full max-w-md
           p-10 sm:p-12
           rounded-3xl
           bg-white/95 backdrop-blur-lg
           shadow-[0_40px_80px_rgba(14,165,233,0.2),0_15px_30px_rgba(14,165,233,0.12)]
           text-center transition-all duration-300">

        <!-- Logo -->
        <div class="flex justify-center">
            <img
                src="{{ asset('assets/images/logo.png') }}"
                alt="Logo"
                class="w-32 h-32 object-contain">
        </div>

        <h1 class="text-3xl font-bold text-sky-600 mb-4">
            Welcome to ResvBAR
        </h1>

        <p class="text-sm text-slate-600 mb-10 leading-relaxed">
            Sign in to discover hotels near you
        </p>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-6">
            <a
                href="{{ route('login') }}"
                class="px-8 py-3 rounded-full text-sm font-semibold text-white
               bg-gradient-to-br from-sky-500 to-blue-500
               shadow-lg hover:-translate-y-0.5 hover:shadow-xl
               transition-transform duration-300">
                Login
            </a>

            <a
                href="{{ route('register') }}"
                class="px-8 py-3 rounded-full text-sm font-semibold
               text-sky-600 bg-white/90
               border border-sky-200
               hover:bg-sky-50 transition-colors duration-300">
                Register
            </a>
        </div>

        <footer class="text-xs text-slate-400">
            © {{ date('Y') }} ResvBAR | Created by Wajd Heshme
        </footer>

    </div>

</body>

</html>
