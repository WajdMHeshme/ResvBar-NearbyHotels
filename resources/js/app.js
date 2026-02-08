import L from 'leaflet';

document.addEventListener('DOMContentLoaded', () => {
  const locateBtn = document.getElementById('locateBtn');
  const refreshMarkersBtn = document.getElementById('refreshMarkersBtn');
  const mapEl = document.getElementById('map');

  if (!mapEl) return;

  // Overpass endpoint
  const OVERPASS_URL = 'https://overpass-api.de/api/interpreter';

  // Map init (fallback center)
  const map = L.map('map', { zoomControl: true }).setView([30.0444, 31.2357], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // State
  let userMarker = null;
  let hotelMarkers = [];
  let currentPos = null; // [lat, lng]
  const SEARCH_RADIUS = 1000; // meters (يمكن تغييره)

  // small toast helper
  function showToast(text, timeout = 2500) {
    const id = 'toast-' + Date.now();
    const container = document.createElement('div');
    container.id = id;
    container.className = 'fixed bottom-6 left-1/2 transform -translate-x-1/2 z-60';
    container.innerHTML = `
      <div class="inline-block bg-slate-800 text-white text-sm px-4 py-2 rounded-md shadow-lg">
        ${text}
      </div>
    `;
    document.body.appendChild(container);
    setTimeout(() => {
      const el = document.getElementById(id);
      if (el) el.remove();
    }, timeout);
  }

  // Add/Remove markers
  function clearHotels() {
    hotelMarkers.forEach(m => m.remove());
    hotelMarkers = [];
  }
  function addHotelMarker(lat, lon, props = {}) {
    const name = props.name || props.tags?.name || 'Hotel';
    // build address if available
    let addr = '';
    const tags = props.tags || {};
    if (tags['addr:housenumber'] || tags['addr:street'] || tags['addr:city']) {
      addr = `${tags['addr:street'] || ''} ${tags['addr:housenumber'] || ''}`.trim();
      if (tags['addr:city']) addr += `, ${tags['addr:city']}`;
    } else if (tags['street'] || tags['city']) {
      addr = `${tags['street'] || ''} ${tags['housenumber'] || ''}`.trim();
    }

    let popup = `<b>${escapeHtml(name)}</b>`;
    if (addr) popup += `<br>${escapeHtml(addr)}`;
    if (tags['phone'] || tags['contact:phone']) popup += `<br>Tel: ${escapeHtml(tags['phone'] || tags['contact:phone'])}`;
    if (tags['website']) popup += `<br><a href="${escapeHtml(tags['website'])}" target="_blank" rel="noopener">Website</a>`;

    const marker = L.marker([lat, lon]).addTo(map).bindPopup(popup);
    hotelMarkers.push(marker);
  }

  // simple escaper for popup HTML
  function escapeHtml(str = '') {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  // Build Overpass query using 'around' to include nodes/ways/relations
  function buildOverpassQuery(lat, lng, radiusMeters = SEARCH_RADIUS) {
    return `
      [out:json][timeout:25];
      (
        node["tourism"="hotel"](around:${radiusMeters},${lat},${lng});
        way["tourism"="hotel"](around:${radiusMeters},${lat},${lng});
        relation["tourism"="hotel"](around:${radiusMeters},${lat},${lng});
      );
      out center;
    `;
  }

  // Fetch hotels from Overpass
  async function fetchHotelsOverpass(lat, lng, radiusMeters = SEARCH_RADIUS) {
    const q = buildOverpassQuery(lat, lng, radiusMeters);
    const url = `${OVERPASS_URL}?data=${encodeURIComponent(q)}`;

    try {
      const res = await fetch(url);
      if (!res.ok) {
        throw new Error(`Overpass returned ${res.status}`);
      }
      const data = await res.json();
      return data.elements || [];
    } catch (err) {
      console.error('Overpass fetch error:', err);
      throw err;
    }
  }

  // Convert elements to markers and add to map
  function addHotelsFromOverpassElements(elements) {
    clearHotels();
    if (!elements.length) {
      showToast('ما في فنادق خلال النطاق المحدد');
      return;
    }

    elements.forEach(el => {
      // nodes: lat/lon, ways/relations: center
      let lat, lon;
      if (el.type === 'node' && el.lat && el.lon) {
        lat = el.lat; lon = el.lon;
      } else if ((el.type === 'way' || el.type === 'relation') && el.center) {
        lat = el.center.lat; lon = el.center.lon;
      } else {
        return; // skip if no coords
      }
      addHotelMarker(lat, lon, el);
    });

    // fit bounds to markers + user marker
    const group = L.featureGroup(hotelMarkers.concat(userMarker || []));
    try {
      if (group.getLayers().length) map.fitBounds(group.getBounds().pad(0.2));
    } catch (e) { /* ignore */ }
  }

  // High level: get current pos -> fetch -> display
  async function locateAndLoadHotels() {
    if (!navigator.geolocation) {
      showToast('Geolocation غير مدعوم في المتصفح');
      return;
    }

    showToast('جاري تحديد الموقع...');
    navigator.geolocation.getCurrentPosition(async (pos) => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      currentPos = [lat, lng];

      // set view + user marker
      map.setView(currentPos, 15);
      if (userMarker) userMarker.remove();
      userMarker = L.marker(currentPos).addTo(map).bindPopup('<b>موقعك الحالي</b>');

      try {
        showToast('جلب الفنادق القريبة...');
        const elements = await fetchHotelsOverpass(lat, lng);
        addHotelsFromOverpassElements(elements);
        showToast(`تم جلب ${elements.length} نتيجة`);
      } catch (err) {
        showToast('فشل جلب بيانات الفنادق — حاول لاحقاً');
      } finally {
        // ensure tiles/layout are okay
        setTimeout(() => map.invalidateSize(), 150);
      }
    }, (err) => {
      console.error('geolocation error', err);
      showToast('فشل الحصول على الموقع — تسمح بالوصول للموقع؟');
    }, { enableHighAccuracy: true, timeout: 10000 });
  }

  // refresh using currentPos or map center
  async function refreshHotels() {
    let lat, lng;
    if (currentPos) {
      [lat, lng] = currentPos;
    } else {
      const c = map.getCenter();
      lat = c.lat; lng = c.lng;
    }
    try {
      showToast('تحديث الفنادق...');
      const elements = await fetchHotelsOverpass(lat, lng);
      addHotelsFromOverpassElements(elements);
      showToast(`تم تحديث (${elements.length})`);
    } catch (err) {
      showToast('فشل تحديث البيانات');
    }
  }

  // Wire buttons
  if (locateBtn) locateBtn.addEventListener('click', (e) => { e.stopPropagation(); locateAndLoadHotels(); });
  if (refreshMarkersBtn) refreshMarkersBtn.addEventListener('click', (e) => { e.stopPropagation(); refreshHotels(); });

  // Optional: click map to search around clicked point
  map.on('click', async (e) => {
    const { lat, lng } = e.latlng;
    // center and add a temporary marker
    map.setView([lat, lng], map.getZoom());
    showToast('جاري جلب الفنادق للموقع الذي ضغطت عليه...');
    try {
      const elements = await fetchHotelsOverpass(lat, lng);
      addHotelsFromOverpassElements(elements);
    } catch (err) {
      showToast('فشل جلب بيانات للموقع الذي اخترته');
    }
  });

  // start automatically once
  locateAndLoadHotels();

  // ensure resize works
  window.addEventListener('resize', () => {
    setTimeout(() => map.invalidateSize(), 120);
  });
});
