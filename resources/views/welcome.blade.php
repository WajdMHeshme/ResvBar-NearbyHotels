<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>ResvBAR</title>
      <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-sky-100 via-white to-sky-50 overflow-hidden">

  <!-- Background Logo faint -->
  <div class="absolute inset-0 flex items-center justify-center">
    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="opacity-10 w-3/4 h-auto object-contain">
  </div>

  <!-- Card -->
  <div class="relative z-10 w-96 p-12 rounded-3xl bg-white/95 backdrop-blur-lg shadow-[0_40px_80px_rgba(14,165,233,0.2),0_15px_30px_rgba(14,165,233,0.12)] text-center transition-transform duration-500 ease-in-out hover:-translate-y-2 hover:shadow-[0_60px_120px_rgba(14,165,233,0.25),0_25px_50px_rgba(14,165,233,0.16)]">

    <h1 class="text-3xl font-bold text-sky-600 mb-4">Welcome</h1>
    <p class="text-sm text-slate-600 mb-10 leading-relaxed">
      Smart reservations platform with secure authentication
      and interactive location-based features.
    </p>

    <!-- Actions -->
    <div class="flex justify-center gap-4 mb-6">
      <a href="{{ route('login') }}" class="px-8 py-3 rounded-full text-sm font-medium text-white bg-gradient-to-br from-sky-500 to-blue-500 shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-transform duration-300">
        Login
      </a>
      <a href="{{ route('register') }}" class="px-8 py-3 rounded-full text-sm font-medium text-sky-600 bg-white/90 border border-sky-200 hover:bg-sky-50 transition-colors duration-300">
        Register
      </a>
    </div>

    <footer class="text-xs text-slate-400 mt-2">
      © ResvBAR · Laravel Application
    </footer>
  </div>

</body>
</html>
