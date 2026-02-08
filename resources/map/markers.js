import L from 'leaflet';

let hotelMarkers = [];
let userMarker = null;

export function setUserMarker(map, lat, lng) {
  if (userMarker) userMarker.remove();
  userMarker = L.marker([lat, lng])
    .addTo(map)
    .bindPopup('<b>موقعك الحالي</b>');
}

export function clearHotels() {
  hotelMarkers.forEach(m => m.remove());
  hotelMarkers = [];
}

export function addHotel(map, el) {
  let lat, lng;
  if (el.type === 'node') {
    lat = el.lat; lng = el.lon;
  } else if (el.center) {
    lat = el.center.lat; lng = el.center.lon;
  } else return;

  const name = el.tags?.name || 'Hotel';
  const marker = L.marker([lat, lng])
    .addTo(map)
    .bindPopup(name);

  hotelMarkers.push(marker);
}
