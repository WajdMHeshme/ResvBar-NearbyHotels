<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>ResvBAR — Map Nearby Hotels</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite('resources/css/app.css')
</head>

<body class="relative bg-gradient-to-br from-sky-100 via-white to-sky-50">

    <!-- Background Logo faint -->
    <div class="bg-logo absolute inset-0 flex items-center justify-center pointer-events-none">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="opacity-10 w-3/4 h-auto object-contain">
    </div>

    <!-- Topbar -->
    @include('layouts.partials.topbar')

    <!-- Map -->
    <div id="map" class="mt-16"></div>

    <!-- Controls -->
    <div id="controls" class="absolute top-[82px] left-4 z-50">
        <div class="flex flex-col gap-2">
            <button id="locateBtn"
                class="px-3 py-2 rounded-full bg-white/90 border border-sky-100 shadow text-sm text-sky-600 hover:bg-white">
                Locate me
            </button>
            <button id="refreshMarkersBtn"
                class="px-3 py-2 rounded-full bg-white/90 border border-sky-100 shadow text-sm text-sky-600 hover:bg-white">
                Refresh markers
            </button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- App JS (modular) -->
    @vite('resources/js/app.js')

</body>

</html>
