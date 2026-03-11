@extends('layouts.app')

@section('title', 'Book a Ride - Taxi-At-Foot')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Fix leaflet z-index spilling over the top navbar */
        .leaflet-container {
            z-index: 1 !important;
        }
    </style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8 md:py-12 w-full">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 md:mb-8">
        <a href="{{ route('dashboard') }}" class="p-2.5 bg-white hover:bg-neutral-50 border border-neutral-200 shadow-sm rounded-xl transition-all text-neutral-500 hover:text-neutral-900 active:scale-95 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl md:text-3xl font-black font-heading text-neutral-900 mb-1 leading-tight tracking-tight">Book a Ride</h1>
            <p class="text-neutral-500 text-sm md:text-base font-medium">Select your pickup and drop-off on the map.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 max-h-none lg:h-600px">
        <!-- Booking Form Section -->
        <div class="lg:col-span-2 order-2 lg:order-1 h-full flex flex-col">
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 md:p-8 shadow-xl shadow-neutral-100 flex-1 flex flex-col relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full blur-[40px] -mt-10 -mr-10 pointer-events-none"></div>

                <h2 class="text-xl font-bold font-heading text-neutral-900 mb-6">Trip Summary</h2>

                <form method="POST" action="{{ route('customer.rides.store') }}" class="flex flex-col flex-1 h-full">
                    @csrf
                    
                    <!-- Addresses Container -->
                    <div class="space-y-4 mb-auto">
                        <div class="relative group">
                            <label class="block text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-2 pl-3">Pickup Address</label>
                            <div class="absolute inset-y-0 left-0 top-[28px] pl-3 flex flex-col items-center pointer-events-none relative z-10">
                                <div class="w-3 h-3 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)] border-2 border-white"></div>
                            </div>
                            <input name="pickup_address" id="pickup_address" required readonly placeholder="Tap map exactly twice..."
                                   class="block w-full pl-10 pr-4 py-3.5 bg-neutral-50 border-2 border-neutral-100 rounded-2xl text-neutral-900 placeholder-neutral-400 focus:outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-400/10 transition-all font-semibold text-sm cursor-pointer shadow-sm">
                        </div>

                        <!-- Hidden Map Coordinates -->
                        <div class="hidden">
                            <input type="hidden" name="pickup_lat" id="pickup_lat" readonly required>
                            <input type="hidden" name="pickup_lng" id="pickup_lng" readonly required>
                        </div>

                        <div class="relative group mt-5">
                            <div class="absolute -top-6 left-4 w-0.5 h-6 bg-neutral-200"></div>

                            <label class="block text-[11px] font-black text-amber-500 uppercase tracking-widest mb-2 pl-3">Drop-off Address</label>
                            <div class="absolute inset-y-0 left-0 top-[28px] pl-3 flex items-center pointer-events-none z-10 relative">
                                <div class="w-3 h-3 rounded-none bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.6)] border-2 border-white transform rotate-45"></div>
                            </div>
                            <input name="drop_address" id="drop_address" required readonly placeholder="Tap map exactly twice..."
                                   class="block w-full pl-10 pr-4 py-3.5 bg-neutral-50 border-2 border-neutral-100 rounded-2xl text-neutral-900 placeholder-neutral-400 focus:outline-none focus:bg-white focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all font-semibold text-sm cursor-pointer shadow-sm">
                        </div>

                        <div class="hidden">
                            <input type="hidden" name="drop_lat" id="drop_lat" readonly required>
                            <input type="hidden" name="drop_lng" id="drop_lng" readonly required>
                        </div>
                    </div>

                    <!-- Computed Realtime Details -->
                    <div class="bg-neutral-50 rounded-2xl p-5 border-2 border-neutral-100 space-y-4 shadow-inner mt-8">
                        <div class="flex justify-between items-center pb-4 border-b border-neutral-200">
                            <span class="text-sm font-semibold text-neutral-500">Distance</span>
                            <span id="distance" class="text-neutral-900 font-bold bg-white px-3 py-1 rounded-lg border border-neutral-200 shadow-sm">0.00 km</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-[11px] font-black tracking-widest uppercase text-neutral-500 mb-1">Estimated Fare</span>
                            <span id="fare" class="text-3xl font-black font-heading text-neutral-900 tracking-tighter">₹0.00</span>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" disabled class="mt-6 w-full py-4 bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-lg rounded-2xl shadow-xl shadow-neutral-900/20 transition-all flex justify-center items-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed hover:-translate-y-0.5 active:scale-[0.98] disabled:hover:translate-y-0 disabled:active:scale-100 border border-neutral-800">
                        Confirm Ride
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    </button>
                    <p class="text-xs text-center text-neutral-400 font-medium pt-3 leading-tight hidden lg:block">Fares are estimates. Tolls & wait time extra if applicable.</p>
                </form>
            </div>
        </div>

        <!-- Interactive Map Section -->
        <div class="lg:col-span-3 order-1 lg:order-2 h-[400px] lg:h-full bg-neutral-100 rounded-3xl border-2 border-neutral-200 shadow-inner overflow-hidden relative">
            <div id="map" class="w-full h-full relative z-0"></div>
            
            <div class="absolute top-4 left-4 right-4 z-[2] pointer-events-none">
                <div class="bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-lg border border-neutral-100 flex items-start gap-4 pointer-events-auto">
                    <div class="bg-amber-100 text-amber-600 p-2 rounded-xl mt-1 shrink-0 shadow-sm border border-amber-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-neutral-900 font-heading mb-1">Set your pickup and drop</h4>
                        <p class="text-[13px] text-neutral-500 font-medium leading-relaxed">
                            <span class="inline-block w-2 h-2 bg-emerald-400 rounded-full mr-1"></span> Tap once for Pickup.<br>
                            <span class="inline-block w-2 h-2 bg-amber-400 rotate-45 mr-1"></span> Tap again for Drop-off.
                        </p>
                    </div>
                </div>
            </div>
            
            <button id="reset-map" class="absolute bottom-6 left-1/2 -translate-x-1/2 z-[2] px-6 py-2.5 bg-white text-neutral-800 font-bold rounded-full shadow-lg border-2 border-neutral-200 hover:bg-neutral-50 hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2 hidden animate-[fade-in-up_0.3s_ease-out]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                Clear Map
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
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
        const base = 30;
        const rate = 12;
        return base + (distance * rate);
    }

    async function reverseGeocode(lat, lng) {
        let url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
        try {
            let res = await fetch(url);
            let data = await res.json();
            return data.display_name;
        } catch(e) {
            return `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
        }
    }

    const resetBtn = document.getElementById('reset-map');
    
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
        
        resetBtn.classList.remove('hidden');
        
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
            
            resetBtn.classList.remove('hidden');
            
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
        fetch('{{ route("customer.rides.nearby-drivers") }}')
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
</script>
@endsection