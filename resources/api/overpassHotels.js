const OVERPASS_URL = 'https://overpass-api.de/api/interpreter';
const SEARCH_RADIUS = 2000;

function buildQuery(lat, lng, radius = SEARCH_RADIUS) {
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

export async function fetchNearbyHotels(lat, lng, radius) {
  const query = buildQuery(lat, lng, radius);
  const res = await fetch(
    `${OVERPASS_URL}?data=${encodeURIComponent(query)}`
  );

  if (!res.ok) {
    throw new Error(`Overpass error ${res.status}`);
  }

  const data = await res.json();
  return data.elements || [];
}
