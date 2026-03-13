document.addEventListener('DOMContentLoaded', async function () {
    if (!window.config) return;

    // ─── Map Setup ─────────────────────────────────────────────────────────────
    const map = L.map('map', { zoomControl: false });
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap &amp; CARTO'
    }).addTo(map);

    const pickupLat = parseFloat(window.config.pickupLat);
    const pickupLng = parseFloat(window.config.pickupLng);
    const dropLat   = parseFloat(window.config.dropLat);
    const dropLng   = parseFloat(window.config.dropLng);

    const driverLat = window.config.driverLat ? parseFloat(window.config.driverLat) : null;
    const driverLng = window.config.driverLng ? parseFloat(window.config.driverLng) : null;

    // ─── Icons ─────────────────────────────────────────────────────────────────
    const pickupIcon = L.divIcon({
        className: 'custom-icon',
        html: `<div class="w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-md"></div>`,
        iconSize: [20, 20], iconAnchor: [10, 10]
    });
    const dropIcon = L.divIcon({
        className: 'custom-icon',
        html: `<div class="w-4 h-4 bg-amber-400 border-[3px] border-white shadow-md transform rotate-45"></div>`,
        iconSize: [20, 20], iconAnchor: [10, 10]
    });
    const driverIconEl = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="w-8 h-8 flex items-center justify-center bg-neutral-900 rounded-full border-[3px] border-white shadow-xl text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>`,
        iconSize: [34, 34], iconAnchor: [17, 17]
    });

    // ─── Markers ───────────────────────────────────────────────────────────────
    const pMarker = L.marker([pickupLat, pickupLng], { icon: pickupIcon }).addTo(map);
    const dMarker = L.marker([dropLat, dropLng],     { icon: dropIcon }).addTo(map);
    let driverMarker = null;

    let currentDriverLat = driverLat;
    let currentDriverLng = driverLng;

    // Place driver marker if we have a location
    if (currentDriverLat && currentDriverLng) {
        driverMarker = L.marker([currentDriverLat, currentDriverLng], { icon: driverIconEl })
            .addTo(map)
            .bindPopup('<b>Your Position</b>');
    }

    // ─── Route Drawing ─────────────────────────────────────────────────────────
    let routeToPickup = null;   // Blue: driver → pickup
    let routeToDropoff = null;  // Dark: pickup → drop-off

    async function fetchRoute(fromLng, fromLat, toLng, toLat) {
        const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLat}?overview=full&geometries=geojson`;
        const res  = await fetch(url);
        const data = await res.json();
        return data.routes?.[0]?.geometry ?? null;
    }

    async function drawRoutes(dLat, dLng) {
        // Always draw pickup→dropoff (the customer's trip)
        if (!routeToDropoff) {
            try {
                const tripGeo = await fetchRoute(pickupLng, pickupLat, dropLng, dropLat);
                if (tripGeo) {
                    routeToDropoff = L.geoJSON(tripGeo, {
                        style: { color: '#171717', weight: 5, opacity: 0.85, lineCap: 'round', lineJoin: 'round' }
                    }).addTo(map);
                }
            } catch(e) { console.error('Trip route error', e); }
        }

        // Draw driver→pickup if we are in accepted / driver_arriving
        const activeStatuses = ['accepted', 'driver_arriving'];
        if (dLat && dLng && activeStatuses.includes(window.config.currentStatus)) {
            try {
                // Remove old driver route
                if (routeToPickup) { map.removeLayer(routeToPickup); routeToPickup = null; }
                const pickupGeo = await fetchRoute(dLng, dLat, pickupLng, pickupLat);
                if (pickupGeo) {
                    routeToPickup = L.geoJSON(pickupGeo, {
                        style: { color: '#3B82F6', weight: 4, opacity: 0.9, lineCap: 'round', lineJoin: 'round', dashArray: '8 6' }
                    }).addTo(map);
                }
            } catch(e) { console.error('Pickup route error', e); }
        }

        // Fit bounds to encompass all visible content
        const points = [ [pickupLat, pickupLng], [dropLat, dropLng] ];
        if (dLat && dLng) points.push([dLat, dLng]);
        map.fitBounds(L.latLngBounds(points), { padding: [50, 50] });
    }

    // Draw initial routes
    await drawRoutes(currentDriverLat, currentDriverLng);
    setTimeout(() => map.invalidateSize(), 300);

    // ─── Location Broadcasting ─────────────────────────────────────────────────
    async function pushLocation(lat, lng) {
        return fetch(window.config.locationUpdateRoute, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.config.csrfToken },
            body: JSON.stringify({ lat, lng })
        });
    }

    if (window.config.isActiveRide && 'geolocation' in navigator && window.config.locationUpdateRoute) {
        setInterval(() => {
            navigator.geolocation.getCurrentPosition(pos => {
                pushLocation(pos.coords.latitude, pos.coords.longitude).catch(console.error);
            });
        }, 10000);
    }

    // ─── Shared: update driver marker + re-draw routes ──────────────────────────
    async function applyNewLocation(lat, lng, source) {
        currentDriverLat = lat;
        currentDriverLng = lng;

        // Move / place driver marker
        if (driverMarker) {
            driverMarker.setLatLng([lat, lng]);
        } else {
            driverMarker = L.marker([lat, lng], { icon: driverIconEl })
                .addTo(map)
                .bindPopup('<b>Your Position</b>');
        }

        // Push to server
        try {
            await pushLocation(lat, lng);
        } catch(e) { console.error('Location push failed', e); }

        // Redraw routes
        await drawRoutes(lat, lng);

        // Update input fields
        const latInput = document.getElementById('input-lat');
        const lngInput = document.getElementById('input-lng');
        if (latInput) latInput.value = lat.toFixed(6);
        if (lngInput) lngInput.value = lng.toFixed(6);

        showTestStatus(`✓ Location set via ${source}: ${lat.toFixed(5)}, ${lng.toFixed(5)}`);
    }

    // ─── AJAX Action Center ────────────────────────────────────────────────────
    const BUTTON_CONFIGS = {
        accepted:        { label: 'Accept Ride',                 action: 'accept',        status: null,             cls: 'w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-[0.98]' },
        driver_arriving: { label: "I'm Arriving at Pickup",      action: 'update-status', status: 'driver_arriving', cls: 'w-full py-4 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-500/20 active:scale-[0.98]' },
        in_progress:     { label: 'Start Trip (Passenger Onboard)', action: 'update-status', status: 'in_progress', cls: 'w-full py-4 bg-amber-500 hover:bg-amber-400 text-neutral-900 font-black rounded-xl transition-all shadow-md shadow-amber-500/20 active:scale-[0.98]' },
        completed:       { label: 'Complete Trip (Passenger Dropped)', action: 'update-status', status: 'completed', cls: 'w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl transition-all shadow-md shadow-emerald-600/20 active:scale-[0.98]' },
        cancelled:       { label: 'Cancel Ride',                action: 'update-status', status: 'cancelled',      cls: 'w-full py-3 mt-4 bg-white hover:bg-rose-50 border border-neutral-200 text-rose-600 font-bold rounded-xl transition-all active:scale-[0.98]' },
    };

    const BADGE_CONFIGS = {
        pending:         'px-4 py-2 rounded-xl shadow-sm bg-rose-50 text-rose-700 border border-rose-200 ring-1 ring-rose-600/10',
        accepted:        'px-4 py-2 rounded-xl shadow-sm bg-blue-50 text-blue-700 border border-blue-200 ring-1 ring-blue-600/10',
        driver_arriving: 'px-4 py-2 rounded-xl shadow-sm bg-indigo-50 text-indigo-700 border border-indigo-200 ring-1 ring-indigo-600/10',
        in_progress:     'px-4 py-2 rounded-xl shadow-sm bg-amber-50 text-amber-700 border border-amber-200 ring-1 ring-amber-600/10',
        completed:       'px-4 py-2 rounded-xl shadow-sm bg-emerald-50 text-emerald-700 border border-emerald-200 ring-1 ring-emerald-600/10',
        cancelled:       'px-4 py-2 rounded-xl shadow-sm bg-neutral-100 border border-neutral-200 text-neutral-500',
    };

    function renderActionButtons(transitions) {
        const container = document.getElementById('action-center-buttons');
        if (!container) return;
        if (!transitions || transitions.length === 0) {
            container.innerHTML = `<div class="text-center p-6 bg-neutral-50 rounded-xl border border-neutral-100"><p class="text-neutral-500 font-bold">No further actions available.</p><p class="text-sm text-neutral-400 mt-1">This ride has been resolved.</p></div>`;
            return;
        }
        container.innerHTML = transitions.filter(t => BUTTON_CONFIGS[t]).map(t => {
            const cfg = BUTTON_CONFIGS[t];
            return `<button data-action="${cfg.action}" ${cfg.status ? `data-status="${cfg.status}"` : ''} class="${cfg.cls}">${cfg.label}</button>`;
        }).join('');
        attachButtonListeners(container);
    }

    function updateStatusBadge(status, label) {
        const badge = document.getElementById('ride-status-badge');
        if (!badge) return;
        badge.className = BADGE_CONFIGS[status] || BADGE_CONFIGS['cancelled'];
        badge.querySelector('span').textContent = label;
    }

    function setButtonsLoading(container, loading) {
        container.querySelectorAll('button').forEach(btn => {
            btn.disabled = loading;
            btn.style.opacity = loading ? '0.6' : '1';
            btn.style.cursor  = loading ? 'not-allowed' : '';
        });
    }

    async function handleAction(action, status) {
        const container = document.getElementById('action-center-buttons');
        if (!container) return;
        setButtonsLoading(container, true);

        try {
            const url  = action === 'accept' ? window.config.acceptAjaxRoute : window.config.updateStatusAjaxRoute;
            const body = action === 'accept' ? {} : { status };

            const res  = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.config.csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });
            const data = await res.json();

            if (!res.ok) { showToast(data.error || 'Something went wrong.', 'error'); setButtonsLoading(container, false); return; }

            window.config.currentStatus = data.status;

            updateStatusBadge(data.status, data.status_label);
            renderActionButtons(data.transitions);

            // Re-draw routes if status changed to one needing driver→pickup line
            await drawRoutes(currentDriverLat, currentDriverLng);

            if (data.is_active && !window.config.isActiveRide) {
                window.config.isActiveRide = true;
                if ('geolocation' in navigator) {
                    setInterval(() => {
                        navigator.geolocation.getCurrentPosition(pos => {
                            pushLocation(pos.coords.latitude, pos.coords.longitude).catch(console.error);
                        });
                    }, 10000);
                }
            }
            showToast('Status updated!', 'success');
        } catch(e) {
            console.error(e);
            showToast('Network error. Please try again.', 'error');
            setButtonsLoading(container, false);
        }
    }

    function attachButtonListeners(container) {
        container.querySelectorAll('button[data-action]').forEach(btn => {
            btn.addEventListener('click', () => handleAction(btn.dataset.action, btn.dataset.status ?? null));
        });
    }

    const initialContainer = document.getElementById('action-center-buttons');
    if (initialContainer) attachButtonListeners(initialContainer);

    // ─── Test Location Panel ───────────────────────────────────────────────────
    let mapClickMode = false;

    function showTestStatus(msg) {
        const el = document.getElementById('test-location-status');
        if (!el) return;
        el.textContent = msg;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 4000);
    }

    // GPS Button
    const btnGPS = document.getElementById('btn-use-gps');
    if (btnGPS) {
        btnGPS.addEventListener('click', () => {
            if (!('geolocation' in navigator)) { showTestStatus('GPS not supported on this browser'); return; }
            btnGPS.textContent = 'Locating…';
            btnGPS.disabled = true;
            navigator.geolocation.getCurrentPosition(
                async pos => {
                    await applyNewLocation(pos.coords.latitude, pos.coords.longitude, 'GPS');
                    btnGPS.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Use My GPS`;
                    btnGPS.disabled = false;
                },
                err => {
                    showTestStatus('GPS error: ' + err.message);
                    btnGPS.textContent = 'Use My GPS';
                    btnGPS.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }

    // Map Click Toggle
    const btnMapClick = document.getElementById('btn-map-click-toggle');
    const mapClickLabel = document.getElementById('map-click-label');

    if (btnMapClick) {
        btnMapClick.addEventListener('click', () => {
            mapClickMode = !mapClickMode;
            if (mapClickMode) {
                map.getContainer().style.cursor = 'crosshair';
                btnMapClick.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-200');
                btnMapClick.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                mapClickLabel.textContent = '✓ Click on the map…';
                showTestStatus('Click anywhere on the map to place yourself there.');
            } else {
                map.getContainer().style.cursor = '';
                btnMapClick.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');
                btnMapClick.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                mapClickLabel.textContent = 'Click Map to Place Me';
            }
        });
    }

    map.on('click', async function (e) {
        if (!mapClickMode) return;
        await applyNewLocation(e.latlng.lat, e.latlng.lng, 'map click');
        // Auto-disable map click mode after one placement
        mapClickMode = false;
        map.getContainer().style.cursor = '';
        if (btnMapClick) {
            btnMapClick.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200');
            btnMapClick.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            if (mapClickLabel) mapClickLabel.textContent = 'Click Map to Place Me';
        }
    });

    // Manual Coords
    const btnManual = document.getElementById('btn-set-manual');
    if (btnManual) {
        btnManual.addEventListener('click', async () => {
            const lat = parseFloat(document.getElementById('input-lat')?.value);
            const lng = parseFloat(document.getElementById('input-lng')?.value);
            if (isNaN(lat) || isNaN(lng)) { showTestStatus('Enter valid lat & lng values'); return; }
            await applyNewLocation(lat, lng, 'manual input');
        });
    }

    // ─── Toast ─────────────────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const existing = document.getElementById('action-toast');
        if (existing) existing.remove();
        const colors = type === 'success'
            ? 'bg-emerald-50 border-emerald-400 text-emerald-800'
            : 'bg-rose-50 border-rose-400 text-rose-800';
        const toast = document.createElement('div');
        toast.id = 'action-toast';
        toast.className = `fixed bottom-6 left-6 z-[9998] flex items-center gap-3 px-5 py-3.5 rounded-2xl border shadow-xl text-sm font-semibold ${colors} transition-all duration-300 opacity-0 translate-y-2`;
        toast.textContent = message;
        document.body.appendChild(toast);
        requestAnimationFrame(() => { toast.style.opacity = '1'; toast.style.transform = 'translateY(0)'; });
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(8px)'; setTimeout(() => toast.remove(), 300); }, 3000);
    }
});
