export function getCurrentLocation(options = {}) {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      reject('Geolocation غير مدعوم');
    }

    navigator.geolocation.getCurrentPosition(
      pos => resolve({
        lat: pos.coords.latitude,
        lng: pos.coords.longitude
      }),
      err => reject(err),
      { enableHighAccuracy: true, timeout: 10000, ...options }
    );
  });
}
