document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map', {
        zoomControl: false // Move zoom control out of the way
    }).setView([23.0225, 72.5714], 13);
    
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Light Theme Map Tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap & CARTO'
    }).addTo(map);

    let pickup = null;
    let drop = null;
    let routeLine = null;
    let driverMarkers = {};

    const pickupIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div class="w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-md"></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const dropIcon = L.divIcon({
        className: 'custom-div-icon',
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

    function calculateFare(distance) {
        const vehicleType = document.querySelector('input[name="vehicle_type"]:checked')?.value || 'bike';
        const rates = window.vehicleRates && window.vehicleRates[vehicleType] ? window.vehicleRates[vehicleType] : { base_fare: 30, rate_per_km: 9 };
        
        const base = parseFloat(rates.base_fare);
        const rate = parseFloat(rates.rate_per_km);
        let fare = base + (distance * rate);
        
        if (window.surgeMultiplier && window.surgeMultiplier > 1) {
            fare = fare * parseFloat(window.surgeMultiplier);
        }
        
        return fare;
    }

    window.updateFareEstimation = function() {
        if (!pickup || !drop) return;
        
        // Re-calculate using current distance string or stored distance
        const distanceText = document.getElementById("distance").innerText;
        const distanceMatch = distanceText.match(/(\d+\.?\d*)/);
        
        if (distanceMatch) {
            const distance = parseFloat(distanceMatch[0]);
            const fare = calculateFare(distance);
            document.getElementById("fare").innerText = "₹" + fare.toFixed(2);
        }
    }

    async function reverseGeocode(lat, lng) {
        let url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
        try {
            let res = await fetch(url);
            let data = await res.json();
            
            if (data && data.address) {
                const addr = data.address;
                // Try to build a more localized, human-friendly name
                const primary = addr.road || addr.neighbourhood || addr.suburb || addr.residential || addr.city_district || addr.village;
                const secondary = addr.city || addr.town || addr.county || addr.state_district;
                
                if (primary && secondary && primary !== secondary) {
                    return `${primary}, ${secondary}`;
                } else if (primary) {
                    return primary;
                }
            }
            
            // Fallback to the display name if specific parts aren't found, but split it to keep it short
            if (data && data.display_name) {
                return data.display_name.split(',').slice(0, 2).join(', ');
            }
            
            return `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
        } catch(e) {
            return `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
        }
    }

    const resetBtn = document.getElementById('reset-map');
    
    if (resetBtn) {
        resetBtn.addEventListener('click', (e) => {
            e.preventDefault();
            pickup = null;
            drop = null;
            
            map.eachLayer((layer) => {
                if (layer instanceof L.Marker || layer instanceof L.GeoJSON) {
                    map.removeLayer(layer);
                }
            });
            
            document.getElementById("pickup_lat").value = "";
            document.getElementById("pickup_lng").value = "";
            document.getElementById("pickup_address").value = "";
            document.getElementById("drop_lat").value = "";
            document.getElementById("drop_lng").value = "";
            document.getElementById("drop_address").value = "";
            
            document.getElementById("distance").innerText = "0.00 km";
            document.getElementById("fare").innerText = "₹0.00";
            document.getElementById('submit-btn').disabled = true;
            resetBtn.classList.add('hidden');
        });
    }

    function drawRoute() {
        if (!pickup || !drop) return;
        
        // Immediate UI feedback
        if (routeLine) {
            map.removeLayer(routeLine);
        }
        
        // Draw straight dashed line immediately
        routeLine = L.polyline([[pickup.lat, pickup.lng], [drop.lat, drop.lng]], {
            color: '#a3a3a3', weight: 4, dashArray: '10, 10', opacity: 0.7
        }).addTo(map);
        
        map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

        // Quick straight-line estimate (multiply by 1.3 for typical driving detour)
        let estDistanceMeters = map.distance([pickup.lat, pickup.lng], [drop.lat, drop.lng]);
        let estDistance = (estDistanceMeters / 1000) * 1.3;
        
        document.getElementById("distance").innerText = "~" + estDistance.toFixed(2) + " km";
        let estFare = calculateFare(estDistance);
        document.getElementById("fare").innerText = "₹" + estFare.toFixed(2);
        
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = `Calculating exact route...`;
        
        if (resetBtn) resetBtn.classList.remove('hidden');
        
        // Async OSRM fetch
        let url = `https://router.project-osrm.org/route/v1/driving/${pickup.lng},${pickup.lat};${drop.lng},${drop.lat}?overview=full&geometries=geojson`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!data.routes || !data.routes[0]) throw new Error("No route");

                let route = data.routes[0];
                let distance = route.distance / 1000;
                
                document.getElementById("distance").innerText = distance.toFixed(2) + " km";
                let fare = calculateFare(distance);
                document.getElementById("fare").innerText = "₹" + fare.toFixed(2);
                
                if (routeLine) {
                    map.removeLayer(routeLine);
                }
                
                routeLine = L.geoJSON(route.geometry, {
                    style: { color: "#171717", weight: 5, opacity: 0.8, lineCap: "round", lineJoin: "round" }
                }).addTo(map);

                map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

                btn.disabled = false;
                btn.innerHTML = `Confirm Ride <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>`;
            })
            .catch(e => {
                console.error("Routing error:", e);
                // Fallback to straight line estimate if OSRM fails
                btn.disabled = false;
                btn.innerHTML = `Confirm Ride (Estimate) <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>`;
            });
    }

    function setPickupLocation(lat, lng) {
        if (pickup) {
            // Already set, could clear or ignore. For safety, let's reset map if they force it
            if (resetBtn) resetBtn.click();
        }

        pickup = {lat, lng};
        L.marker([lat, lng], {icon: pickupIcon}).addTo(map);
        map.setView([lat, lng], 16);
        
        document.getElementById("pickup_lat").value = lat;
        document.getElementById("pickup_lng").value = lng;
        document.getElementById("pickup_address").value = "Loading location...";
        
        reverseGeocode(lat, lng).then(addr => {
            document.getElementById("pickup_address").value = addr;
        });
        
        if (resetBtn) resetBtn.classList.remove('hidden');
    }

    const currentLocBtn = document.getElementById('use-current-location');
    if (currentLocBtn) {
        currentLocBtn.addEventListener('click', function() {
            if (!("geolocation" in navigator)) {
                alert("Geolocation is not supported by your browser.");
                return;
            }

            const btn = this;
            const originalHtml = btn.innerHTML;
            
            // Setup spinner icon
            btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    setPickupLocation(position.coords.latitude, position.coords.longitude);
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                },
                function(error) {
                    console.error("Geolocation error:", error);
                    alert("Unable to fetch your location. Please check your browser permissions.");
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    }

    map.on("click", function (e) {
        let lat = e.latlng.lat;
        let lng = e.latlng.lng;

        if (!pickup) {
            pickup = {lat, lng};
            L.marker([lat, lng], {icon: pickupIcon}).addTo(map);
            
            document.getElementById("pickup_lat").value = lat;
            document.getElementById("pickup_lng").value = lng;
            document.getElementById("pickup_address").value = "Loading location...";
            
            reverseGeocode(lat, lng).then(addr => {
                document.getElementById("pickup_address").value = addr;
            });
            
            if (resetBtn) resetBtn.classList.remove('hidden');
            
        } else if (!drop) {
            drop = {lat, lng};
            L.marker([lat, lng], {icon: dropIcon}).addTo(map);
            
            document.getElementById("drop_lat").value = lat;
            document.getElementById("drop_lng").value = lng;
            document.getElementById("drop_address").value = "Loading location...";
            
            reverseGeocode(lat, lng).then(addr => {
                document.getElementById("drop_address").value = addr;
            });
            
            drawRoute();
        }
    });

    function fetchNearbyDrivers() {
        if (!window.routes || !window.routes.nearbyDrivers) return;

        fetch(window.routes.nearbyDrivers)
            .then(res => res.json())
            .then(data => {
                if(data.drivers) {
                    // Remove markers of drivers that are no longer in the list
                    const latestDriverIds = data.drivers.map(d => d.id);
                    for (let id in driverMarkers) {
                        if (!latestDriverIds.includes(parseInt(id))) {
                            map.removeLayer(driverMarkers[id]);
                            delete driverMarkers[id];
                        }
                    }

                    // Update or Add Markers
                    data.drivers.forEach(driver => {
                        const latLng = [parseFloat(driver.current_lat), parseFloat(driver.current_lng)];
                        if (driverMarkers[driver.id]) { // update existing
                            driverMarkers[driver.id].setLatLng(latLng);
                        } else { // add new
                            const marker = L.marker(latLng, {icon: driverIcon}).addTo(map);
                            driverMarkers[driver.id] = marker;
                        }
                    });
                }
            })
            .catch(console.error);
    }

    // Initial fetch and set interval
    fetchNearbyDrivers();
    setInterval(fetchNearbyDrivers, 10000);

    // Automatically fix map size on load
    setTimeout(() => { map.invalidateSize(); }, 200);
});
