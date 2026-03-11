document.addEventListener('DOMContentLoaded', async function () {
    if (!window.config) return;

    const map = L.map('map', {zoomControl: false});
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap & CARTO'
    }).addTo(map);

    const pickupLat = parseFloat(window.config.pickupLat);
    const pickupLng = parseFloat(window.config.pickupLng);
    const dropLat = parseFloat(window.config.dropLat);
    const dropLng = parseFloat(window.config.dropLng);

    const pickupIcon = L.divIcon({
        className: 'custom-icon',
        html: `<div class="w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-md"></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const dropIcon = L.divIcon({
        className: 'custom-icon',
        html: `<div class="w-4 h-4 bg-amber-400 border-[3px] border-white shadow-md transform rotate-45"></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const pMarker = L.marker([pickupLat, pickupLng], {icon: pickupIcon}).addTo(map);
    const dMarker = L.marker([dropLat, dropLng], {icon: dropIcon}).addTo(map);

    try {
        const url = `https://router.project-osrm.org/route/v1/driving/${pickupLng},${pickupLat};${dropLng},${dropLat}?overview=full&geometries=geojson`;
        const res = await fetch(url);
        const data = await res.json();
        
        if (data.routes && data.routes.length > 0) {
            const routeLine = L.geoJSON(data.routes[0].geometry, {
                style: { color: "#171717", weight: 5, opacity: 0.8, lineCap: "round", lineJoin: "round" }
            }).addTo(map);
            
            map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
        } else {
            map.fitBounds(L.latLngBounds([pMarker.getLatLng(), dMarker.getLatLng()]), { padding: [50, 50] });
        }
    } catch(e) {
        console.error("OSRM Routing error", e);
        map.fitBounds(L.latLngBounds([pMarker.getLatLng(), dMarker.getLatLng()]), { padding: [50, 50] });
    }

    setTimeout(() => map.invalidateSize(), 300);
    
    // Location updating if ride is active
    if (window.config.isActiveRide && "geolocation" in navigator && window.config.locationUpdateRoute) {
        setInterval(() => {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                fetch(window.config.locationUpdateRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ lat: lat, lng: lng })
                }).catch(console.error);
            });
        }, 10000);
    }
});
