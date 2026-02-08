import L from 'leaflet';

export function createMap(elId) {
  const map = L.map(elId).setView([30.0444, 31.2357], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  return map;
}
