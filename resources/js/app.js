import { createMap } from '../map/initMap';
import { setUserMarker, clearHotels, addHotel } from '../map/markers';
import { fetchNearbyHotels } from '../api/overpassHotels';
import { getCurrentLocation } from '../services/geolocation';
import { showToast } from '../utils/toast';

document.addEventListener('DOMContentLoaded', async () => {
  const mapEl = document.getElementById('map');
  if (!mapEl) return;

  const map = createMap('map');

  try {
    showToast('جاري تحديد الموقع...');
    const { lat, lng } = await getCurrentLocation();

    map.setView([lat, lng], 15);
    setUserMarker(map, lat, lng);

    showToast('جلب الفنادق القريبة...');
    const hotels = await fetchNearbyHotels(lat, lng);

    clearHotels();
    hotels.forEach(h => addHotel(map, h));

    showToast(`تم جلب ${hotels.length} فندق`);
  } catch (err) {
    console.error(err);
    showToast('حصل خطأ أثناء تحميل البيانات');
  }
});
