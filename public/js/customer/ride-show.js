document.addEventListener('DOMContentLoaded', async function () {
    if (!window.config) return;

    const map = L.map('map', {zoomControl: false});
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap &amp; CARTO'
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

    let currentStatus = window.config.currentStatus;
    const TERMINAL_STATUSES = ['completed', 'cancelled'];

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

    // ─── AJAX Status Poller ───────────────────────────────────────────────────
    // Polls every 5 seconds while ride is not in a terminal state.
    // Updates: driver card, status badge, and status log table.

    const BADGE_CLASSES = {
        pending:          'bg-rose-50 text-rose-700 border-rose-200 ring-1 ring-rose-600/10',
        accepted:         'bg-blue-50 text-blue-700 border-blue-200 ring-1 ring-blue-600/10',
        driver_arriving:  'bg-indigo-50 text-indigo-700 border-indigo-200 ring-1 ring-indigo-600/10',
        in_progress:      'bg-amber-50 text-amber-700 border-amber-200 ring-1 ring-amber-600/10',
        completed:        'bg-emerald-50 text-emerald-700 border-emerald-200 ring-1 ring-emerald-600/10',
        cancelled:        'bg-neutral-100 border-neutral-200 text-neutral-500',
    };

    const BASE_BADGE_CLASSES = 'inline-block px-3 py-1 text-[10px] font-black uppercase rounded-lg border tracking-widest whitespace-nowrap shadow-sm';

    function buildDriverCardHTML(driver) {
        if (!driver) {
            return `
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-16 h-16 rounded-full border-2 border-dashed border-amber-300 bg-amber-50 flex items-center justify-center mb-4 relative z-10">
                        <span class="relative flex h-4 w-4">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                        </span>
                    </div>
                    <p class="text-neutral-900 font-bold mb-1">Connecting to drivers...</p>
                    <p class="text-xs text-neutral-500 font-medium max-w-[150px]">We're searching the area for the best partner.</p>
                </div>`;
        }
        return `
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-neutral-800 to-neutral-900 rounded-2xl flex items-center justify-center text-white font-black font-heading text-2xl shadow-md border border-neutral-700">
                    ${driver.initial}
                </div>
                <div>
                    <p class="text-lg font-black font-heading text-neutral-900 leading-tight mb-1">${driver.name}</p>
                    <p class="text-[10px] border border-blue-200 bg-blue-50 text-blue-700 font-black tracking-widest uppercase px-2 py-0.5 rounded inline-block">Pro Partner</p>
                </div>
            </div>
            <a href="tel:${driver.phone}" class="bg-neutral-50 hover:bg-neutral-100 p-4 rounded-2xl border border-neutral-200 flex items-center justify-between gap-3 mt-4 transition-colors group">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    <span class="text-neutral-700 font-bold tracking-wider">${driver.phone}</span>
                </div>
                <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Call</span>
            </a>`;
    }

    function buildStatusLogsHTML(logs) {
        if (!logs || logs.length === 0) {
            return `<tr><td colspan="4" class="p-8 text-center text-neutral-400 font-medium italic">Internal status logs are not available for this trip yet.</td></tr>`;
        }
        return logs.map(log => `
            <tr class="hover:bg-neutral-50 transition-colors">
                <td class="p-4 pl-6 font-bold text-neutral-800">${log.status}</td>
                <td class="p-4 text-neutral-500 font-medium hidden sm:table-cell">${log.by}</td>
                <td class="p-4 text-neutral-400 font-medium hidden md:table-cell">${log.remarks}</td>
                <td class="p-4 pr-6 text-neutral-500 text-right font-mono text-xs font-semibold">${log.timestamp}</td>
            </tr>`).join('');
    }

    let statusPollInterval = null;

    function pollRideStatus() {
        if (!window.config.rideStatusRoute) return;

        fetch(window.config.rideStatusRoute)
            .then(res => res.json())
            .then(data => {
                const newStatus = data.status;

                // Update driver card if status changed or driver has been assigned
                const driverCard = document.getElementById('driver-card-content');
                if (driverCard) {
                    driverCard.innerHTML = buildDriverCardHTML(data.driver);
                }

                // Update status badge
                const badge = document.getElementById('ride-status-badge');
                if (badge) {
                    const badgeCls = BADGE_CLASSES[newStatus] || BADGE_CLASSES['cancelled'];
                    badge.className = `${BASE_BADGE_CLASSES} ${badgeCls}`;
                    badge.textContent = data.status_label;
                }

                // Update status log table
                const tbody = document.getElementById('status-logs-tbody');
                if (tbody) {
                    tbody.innerHTML = buildStatusLogsHTML(data.status_logs);
                }

                // If driver just became active, start location polling
                if (data.is_driver_active && !window.config.isDriverActive) {
                    window.config.isDriverActive = true;
                    function fetchDriverLocationLive() {
                        fetch(window.config.driverLocationRoute)
                            .then(r => r.json())
                            .then(loc => {
                                if (loc.lat && loc.lng) {
                                    const latLng = [parseFloat(loc.lat), parseFloat(loc.lng)];
                                    if (assignedDriverMarker) {
                                        assignedDriverMarker.setLatLng(latLng);
                                    } else {
                                        assignedDriverMarker = L.marker(latLng, {icon: activeDriverIcon}).addTo(map);
                                    }
                                }
                            }).catch(console.error);
                    }
                    fetchDriverLocationLive();
                    setInterval(fetchDriverLocationLive, 10000);
                }

                currentStatus = newStatus;

                // Stop polling once ride is terminal
                if (TERMINAL_STATUSES.includes(newStatus) && statusPollInterval) {
                    clearInterval(statusPollInterval);
                    statusPollInterval = null;
                }
            })
            .catch(console.error);
    }

    // Only poll if ride is not already in a terminal state
    if (!TERMINAL_STATUSES.includes(currentStatus)) {
        pollRideStatus(); // immediate first call
        statusPollInterval = setInterval(pollRideStatus, 5000);
    }
});


