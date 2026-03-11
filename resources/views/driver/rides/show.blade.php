@extends('layouts.app')

@section('title', 'Manage Ride #' . $ride->id . ' - Taxi-At-Foot')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container {
            z-index: 1 !important;
        }
        .leaflet-routing-container {
            display: none !important;
        }
    </style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8 md:py-12 w-full">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('driver.rides.my') }}" class="p-2.5 bg-white hover:bg-neutral-50 border border-neutral-200 shadow-sm rounded-xl transition-all text-neutral-500 hover:text-neutral-900 active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black font-heading text-neutral-900 flex items-center gap-3">
                    Mission Control
                    <span class="text-neutral-400 font-mono text-lg font-bold">#{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}</span>
                </h1>
                <p class="text-xs text-neutral-500 font-bold uppercase tracking-widest mt-1">Booked: {{ $ride->created_at->format('M j, Y g:i A') }}</p>
            </div>
        </div>

        @php
            $statusStr = $ride->status->value ?? 'pending';
            $badgeClass = match($statusStr) {
                'pending'   => 'bg-rose-50 text-rose-700 border-rose-200 ring-1 ring-rose-600/10',
                'accepted'  => 'bg-blue-50 text-blue-700 border-blue-200 ring-1 ring-blue-600/10',
                'driver_arriving' => 'bg-indigo-50 text-indigo-700 border-indigo-200 ring-1 ring-indigo-600/10',
                'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200 ring-1 ring-amber-600/10',
                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-1 ring-emerald-600/10 hover:scale-105 transition-transform cursor-default',
                'cancelled' => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                default     => 'bg-neutral-100 border-neutral-200 text-neutral-500',
            };
        @endphp
        <div class="px-4 py-2 rounded-xl {{ $badgeClass }} shadow-sm">
            <span class="text-sm font-black uppercase tracking-widest">{{ rideStatusLabel($ride->status) }}</span>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-6 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl mb-6 flex items-center gap-3">
            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            <p class="text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative">

        <!-- Left Col: Actions and Meta -->
        <div class="space-y-6">
            
            <!-- Dynamic Actions Card -->
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 shadow-sm">
                <h2 class="text-[11px] font-black text-neutral-400 tracking-widest uppercase mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                    Action Center
                </h2>

                <div class="flex flex-col gap-3">
                    @php
                        $statusStr = $ride->status->value ?? 'pending';
                        $allTransitions = allowedRideStatusTransitions();
                        $transitions = $allTransitions[$statusStr] ?? [];
                    @endphp

                    @if(in_array('accepted', $transitions))
                        <form method="POST" action="{{ route('driver.rides.accept', $ride) }}">
                             @csrf
                             <button class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 active:scale-[0.98]">
                                Accept Ride
                             </button>
                        </form>
                    @endif

                    @if(in_array('driver_arriving', $transitions))
                        <form method="POST" action="{{ route('driver.rides.updateStatus', $ride) }}">
                             @csrf
                             <input type="hidden" name="status" value="driver_arriving">
                             <button class="w-full py-4 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-500/20 active:scale-[0.98]">
                                I'm Arriving at Pickup
                             </button>
                        </form>
                    @endif

                    @if(in_array('in_progress', $transitions))
                        <form method="POST" action="{{ route('driver.rides.updateStatus', $ride) }}">
                             @csrf
                             <input type="hidden" name="status" value="in_progress">
                             <button class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-neutral-900 font-black rounded-xl transition-all shadow-md shadow-amber-500/20 active:scale-[0.98]">
                                Start Trip (Passenger Onboard)
                             </button>
                        </form>
                    @endif

                    @if(in_array('completed', $transitions))
                        <form method="POST" action="{{ route('driver.rides.updateStatus', $ride) }}">
                             @csrf
                             <input type="hidden" name="status" value="completed">
                             <button class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-black rounded-xl transition-all shadow-md shadow-emerald-600/20 active:scale-[0.98]">
                                Complete Trip (Passenger Dropped)
                             </button>
                        </form>
                    @endif
                    
                    @if(in_array('cancelled', $transitions))
                        <form method="POST" action="{{ route('driver.rides.updateStatus', $ride) }}">
                             @csrf
                             <input type="hidden" name="status" value="cancelled">
                             <button class="w-full py-3 mt-4 bg-white hover:bg-rose-50 border border-neutral-200 text-rose-600 font-bold rounded-xl transition-all active:scale-[0.98]">
                                Cancel Ride
                             </button>
                        </form>
                    @endif

                    @if(empty($transitions))
                         <div class="text-center p-6 bg-neutral-50 rounded-xl border border-neutral-100">
                             <p class="text-neutral-500 font-bold">No further actions available.</p>
                             <p class="text-sm text-neutral-400 mt-1">This ride has been resolved.</p>
                         </div>
                    @endif
                </div>
            </div>

            <!-- Customer Details -->
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 shadow-sm">
                <h3 class="text-[11px] font-black text-neutral-400 uppercase tracking-widest mb-6 border-b border-neutral-100 pb-4">Customer Details</h3>

                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 bg-neutral-900 text-white rounded-2xl flex items-center justify-center font-black font-heading text-2xl shadow-md border-2 border-white">
                        {{ substr($ride->customer->name ?? 'G', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-black font-heading text-neutral-900 leading-tight mb-0.5">{{ $ride->customer->name ?? 'Guest User' }}</p>
                        <p class="text-[10px] bg-neutral-100 text-neutral-500 font-bold tracking-widest uppercase px-2 py-0.5 rounded inline-block">Rider</p>
                    </div>
                </div>

                @if($ride->customer && $ride->customer->phone)
                <a href="tel:{{ $ride->customer->phone }}" class="bg-white hover:bg-neutral-50 p-4 rounded-xl border border-neutral-200 flex items-center justify-between gap-3 transition-all group active:scale-[0.98]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <span class="text-neutral-900 font-bold tracking-wider">{{ $ride->customer->phone }}</span>
                    </div>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest px-2 group-hover:bg-emerald-50 rounded py-1 transition-all">Call Customer</span>
                </a>
                @endif
            </div>

             <!-- Route Meta Data -->
            <div class="bg-gradient-to-br from-neutral-50 to-neutral-100 border border-neutral-200 rounded-3xl p-6 shadow-inner">
                 <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-neutral-100 mb-2">
                    <span class="text-neutral-500 font-bold text-sm">Distance Base</span>
                    <span class="text-neutral-900 font-bold">{{ $ride->distance_km }} km</span>
                </div>
                <div class="flex justify-between items-center p-4">
                    <span class="text-neutral-500 font-black tracking-widest uppercase text-[10px]">Expected Payout</span>
                    <span class="text-emerald-600 font-black font-heading text-3xl tracking-tighter">{{ formatCurrency((float) $ride->estimated_fare) }}</span>
                </div>
            </div>

        </div>

        <!-- Right Col: Map & Route View -->
        <div class="bg-white border border-neutral-200 rounded-3xl p-4 shadow-sm flex flex-col h-[600px] lg:h-auto">
            <div class="flex-none mb-6 p-2">
                <div class="relative pl-7 space-y-6">
                    <!-- Vertical tracking line -->
                    <div class="absolute left-[0.45rem] top-3 bottom-3 w-0.5 bg-neutral-200"></div>

                    <div class="relative">
                        <div class="absolute -left-[2.1rem] w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-sm ring-1 ring-emerald-400/20"></div>
                        <p class="text-[10px] font-black text-emerald-600 tracking-widest uppercase mb-1">Pickup Marker</p>
                        <p class="text-neutral-900 font-bold text-base leading-tight">{{ $ride->pickup_address }}</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[2.1rem] w-4 h-4 bg-amber-400 border-[3px] border-white shadow-sm ring-1 ring-amber-400/20 transform rotate-45"></div>
                        <p class="text-[10px] font-black text-amber-500 tracking-widest uppercase mb-1">Drop-off Marker</p>
                        <p class="text-neutral-900 font-bold text-base leading-tight">{{ $ride->drop_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Fixed Map Area -->
            <div class="flex-1 bg-neutral-100 rounded-2xl border-2 border-neutral-200 shadow-inner overflow-hidden relative">
                 <div id="map" class="w-full h-full relative z-0"></div>
                 <div class="absolute top-4 right-4 z-[2] pointer-events-none">
                     <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-xl text-[10px] font-black text-neutral-500 tracking-widest uppercase border border-neutral-200 shadow-sm">
                         Route Overview
                     </span>
                 </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const map = L.map('map', {zoomControl: false});
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap & CARTO'
        }).addTo(map);

        const pickupLat = parseFloat("{{ $ride->pickup_lat }}");
        const pickupLng = parseFloat("{{ $ride->pickup_lng }}");
        const dropLat = parseFloat("{{ $ride->drop_lat }}");
        const dropLng = parseFloat("{{ $ride->drop_lng }}");

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
        @if(in_array($ride->status->value, ['accepted', 'driver_arriving', 'in_progress']))
            if ("geolocation" in navigator) {
                setInterval(() => {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        fetch('{{ route("driver.location.update") }}', {
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
        @endif
    });
</script>
@endsection