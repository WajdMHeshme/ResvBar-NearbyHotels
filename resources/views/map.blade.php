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

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        :root {
            --topbar-h: 64px;
        }

        #map {
            height: calc(100vh - var(--topbar-h));
            width: 100%;
        }

        .bg-logo img {
            pointer-events: none;
        }

        .leaflet-control-container {
            z-index: 1001;
        }
    </style>
</head>

<body class="relative bg-gradient-to-br from-sky-100 via-white to-sky-50">

    <!-- Background Logo faint -->
    <div class="bg-logo absolute inset-0 flex items-center justify-center pointer-events-none">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="opacity-10 w-3/4 h-auto object-contain">
    </div>

    <!-- Topbar fixed -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-sky-100 h-16 flex items-center">
        <div class="max-w-6xl mx-auto w-full px-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <span class="text-lg font-semibold text-sky-600">ResvBAR</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600 hidden sm:inline">Hello, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-full text-sm font-medium text-white bg-gradient-to-br from-rose-500 to-pink-500 shadow hover:opacity-95 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Map -->
    <div id="map" class="mt-16"></div>

    <!-- Controls -->
    <div id="controls" class="absolute top-[82px] left-4 z-50">
        <div class="flex flex-col gap-2">
            <button id="locateBtn" class="px-3 py-2 rounded-full bg-white/90 border border-sky-100 shadow text-sm text-sky-600 hover:bg-white">
                Locate me
            </button>
            <button id="refreshMarkersBtn" class="px-3 py-2 rounded-full bg-white/90 border border-sky-100 shadow text-sm text-sky-600 hover:bg-white">
                Refresh markers
            </button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map, userMarker = null,
            hotelMarkers = [],
            currentPos = null;
        const SEARCH_RADIUS = 1000; // meters
        const OVERPASS_URL = 'https://overpass-api.de/api/interpreter';

        document.addEventListener('DOMContentLoaded', () => {
            map = L.map('map', {
                zoomControl: true
            }).setView([30.0444, 31.2357], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const locateBtn = document.getElementById('locateBtn');
            const refreshMarkersBtn = document.getElementById('refreshMarkersBtn');

            locateBtn.addEventListener('click', locateAndLoadHotels);
            refreshMarkersBtn.addEventListener('click', refreshHotels);

            locateAndLoadHotels(); // auto load first time
            window.addEventListener('resize', () => setTimeout(() => map.invalidateSize(), 120));
        });

        function addUserMarker(lat, lng) {
            if (userMarker) userMarker.remove();
            userMarker = L.marker([lat, lng]).addTo(map).bindPopup('<b>موقعك الحالي</b>');
        }

        function clearHotels() {
            hotelMarkers.forEach(m => m.remove());
            hotelMarkers = [];
        }

        function addHotelMarker(lat, lon, tags = {}) {
            let name = tags.name || 'Hotel';
            let addr = '';
            if (tags['addr:street']) addr = tags['addr:street'];
            let popup = `<b>${escapeHtml(name)}</b>${addr ? '<br>' + escapeHtml(addr) : ''}`;
            const m = L.marker([lat, lon]).addTo(map).bindPopup(popup);
            hotelMarkers.push(m);
        }

        function escapeHtml(str = '') {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function buildOverpassQuery(lat, lng, radius = SEARCH_RADIUS) {
            return `
        [out:json][timeout:25];
        (
          node["tourism"="hotel"](around:${radius},${lat},${lng});
          way["tourism"="hotel"](around:${radius},${lat},${lng});
          relation["tourism"="hotel"](around:${radius},${lat},${lng});
        );
        out center;
      `;
        }

        async function fetchHotels(lat, lng) {
            const query = encodeURIComponent(buildOverpassQuery(lat, lng));
            const url = `${OVERPASS_URL}?data=${query}`;
            try {
                const res = await fetch(url);
                const data = await res.json();
                return data.elements || [];
            } catch (e) {
                console.error('Overpass error:', e);
                return [];
            }
        }

        function addHotelsFromElements(elements) {
            clearHotels();
            if (!elements.length) return;

            elements.forEach(el => {
                let lat, lon;
                if (el.type === 'node') {
                    lat = el.lat;
                    lon = el.lon;
                } else if (el.center) {
                    lat = el.center.lat;
                    lon = el.center.lon;
                }
                if (lat && lon) addHotelMarker(lat, lon, el.tags);
            });

            const group = L.featureGroup(hotelMarkers.concat(userMarker || []));
            if (group.getLayers().length) map.fitBounds(group.getBounds().pad(0.2));
        }

        async function locateAndLoadHotels() {
            if (!navigator.geolocation) {
                alert('Geolocation غير مدعوم');
                return;
            }
            navigator.geolocation.getCurrentPosition(async pos => {
                const lat = pos.coords.latitude,
                    lng = pos.coords.longitude;
                currentPos = [lat, lng];
                map.setView(currentPos, 15);
                addUserMarker(lat, lng);
                const hotels = await fetchHotels(lat, lng);
                addHotelsFromElements(hotels);
            }, err => {
                console.error(err);
                alert('فشل الحصول على الموقع');
            }, {
                enableHighAccuracy: true,
                timeout: 10000
            });
        }

        async function refreshHotels() {
            if (!currentPos) return locateAndLoadHotels();
            const [lat, lng] = currentPos;
            const hotels = await fetchHotels(lat, lng);
            addHotelsFromElements(hotels);
        }
    </script>
</body>

</html>
