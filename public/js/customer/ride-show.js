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

    const driverIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="w-6 h-6 flex items-center justify-center bg-white rounded-full border-2 border-neutral-900 shadow-lg text-neutral-900 font-bold text-[10px] transform transition-transform duration-500 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    const activeDriverIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="w-8 h-8 flex items-center justify-center bg-blue-500 rounded-full border-[3px] border-white shadow-xl text-white font-bold text-[10px] transform transition-transform duration-500 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>`,
        iconSize: [34, 34],
        iconAnchor: [17, 17]
    });

    const pMarker = L.marker([pickupLat, pickupLng], {icon: pickupIcon}).addTo(map);
    const dMarker = L.marker([dropLat, dropLng], {icon: dropIcon}).addTo(map);

    let driverMarkers = {};
    let assignedDriverMarker = null;

    // Instant visual feedback
    map.fitBounds(L.latLngBounds([pMarker.getLatLng(), dMarker.getLatLng()]), { padding: [50, 50] });

    const url = `https://router.project-osrm.org/route/v1/driving/${pickupLng},${pickupLat};${dropLng},${dropLat}?overview=full&geometries=geojson`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.routes && data.routes.length > 0) {
                const routeLine = L.geoJSON(data.routes[0].geometry, {
                    style: { color: "#171717", weight: 5, opacity: 0.8, lineCap: "round", lineJoin: "round" }
                }).addTo(map);
                
                map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
            } else {
                map.fitBounds(L.latLngBounds([pMarker.getLatLng(), dMarker.getLatLng()]), { padding: [50, 50] });
            }
        })
        .catch(e => {
            console.error("OSRM Routing error", e);
            map.fitBounds(L.latLngBounds([pMarker.getLatLng(), dMarker.getLatLng()]), { padding: [50, 50] });
        });

    setTimeout(() => map.invalidateSize(), 300);

    const currentStatus = window.config.currentStatus;

    // Poll nearby drivers only if ride is pending
    if (currentStatus === 'pending') {
        function fetchNearbyDrivers() {
            fetch(window.config.nearbyDriversRoute)
                .then(res => res.json())
                .then(data => {
                    if(data.drivers) {
                        const latestDriverIds = data.drivers.map(d => d.id);
                        for (let id in driverMarkers) {
                            if (!latestDriverIds.includes(parseInt(id))) {
                                map.removeLayer(driverMarkers[id]);
                                delete driverMarkers[id];
                            }
                        }
                        data.drivers.forEach(driver => {
                            const latLng = [parseFloat(driver.current_lat), parseFloat(driver.current_lng)];
                            if (driverMarkers[driver.id]) {
                                driverMarkers[driver.id].setLatLng(latLng);
                            } else {
                                const marker = L.marker(latLng, {icon: driverIcon}).addTo(map);
                                driverMarkers[driver.id] = marker;
                            }
                        });
                    }
                })
                .catch(console.error);
        }

        fetchNearbyDrivers();
        const pollInterval = setInterval(fetchNearbyDrivers, 10000);
    }

    // Listen for live specific driver location if accepted
    if (window.config.isDriverActive && window.config.driverLocationRoute) {
        
        function updateAssignedDriverMarker(lat, lng) {
            const latLng = [parseFloat(lat), parseFloat(lng)];
            if (assignedDriverMarker) {
                assignedDriverMarker.setLatLng(latLng);
            } else {
                assignedDriverMarker = L.marker(latLng, {icon: activeDriverIcon}).addTo(map);
            }
        }

        // Initial fetch
        function fetchDriverLocation() {
            fetch(window.config.driverLocationRoute)
                .then(res => res.json())
                .then(data => {
                    if (data.lat && data.lng) {
                        updateAssignedDriverMarker(data.lat, data.lng);
                    }
                })
                .catch(console.error);
        }

        fetchDriverLocation();
        
        // Poll every 10 seconds as a fallback
        setInterval(fetchDriverLocation, 10000);

        // Optional: Listen via WebSockets if Echo is configured
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel(`ride.${window.config.rideId}`)
                .listen('.driver.location.updated', (e) => {
                    updateAssignedDriverMarker(e.lat, e.lng);
                });
        }
    }
});
