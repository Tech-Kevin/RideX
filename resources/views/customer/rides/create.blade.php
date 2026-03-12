@extends('layouts.app')

@section('title', 'Book a Ride - RideX')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/customer/ride-create.css') }}" />
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
            <div class="bg-white border border-neutral-200 rounded-[2.5rem] p-6 md:p-8 shadow-xl shadow-neutral-100 flex-1 flex flex-col relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full blur-[40px] -mt-10 -mr-10 pointer-events-none"></div>

                <h2 class="text-xl font-bold font-heading text-neutral-900 mb-6">Trip Summary</h2>

                <form method="POST" action="{{ route('customer.rides.store') }}" id="ride-form" class="flex flex-col flex-1 h-full">
                    @csrf
                    
                    <!-- Vehicle Selection -->
                    <div class="mb-8">
                        <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest mb-4 pl-1">Select Vehicle Type</label>
                        <div class="space-y-3">
                            @foreach(['bike', 'auto', 'car'] as $type)
                                @php 
                                    $vType = \App\Enums\VehicleType::from($type);
                                    $rate = $rates[$type] ?? null;
                                @endphp
                                <label class="relative cursor-pointer group block">
                                    <input type="radio" name="vehicle_type" value="{{ $type }}" class="peer sr-only vehicle-input" {{ $type === 'bike' ? 'checked' : '' }} onchange="updateFareEstimation()">
                                    <div class="vehicle-card bg-neutral-50/50 border-2 border-neutral-100 rounded-[1.5rem] p-4 flex items-center gap-4 transition-all relative overflow-hidden">
                                        <!-- Selection Badge -->
                                        <div class="check-badge absolute top-3 right-3 w-5 h-5 bg-neutral-900 rounded-full flex items-center justify-center text-white scale-0 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </div>

                                        <!-- Icon -->
                                        <div class="icon-container w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 @if($type === 'bike') bg-blue-100/50 text-blue-600 @elseif($type === 'auto') bg-amber-100/50 text-amber-600 @else bg-emerald-100/50 text-emerald-600 @endif">
                                            @if($type === 'bike')
                                                <!-- Bike Icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M19 18v-4l-2 -3l-3 -4h-3l3 4l-2 3" /><path d="M17 14h-6.5l-1.5 -3" /></svg>
                                            @elseif($type === 'auto')
                                                <!-- Auto Rickshaw Icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h14v-6a3 3 0 0 0 -3 -3h-10a3 3 0 0 0 -3 3z" /><path d="M5 11h14" /><path d="M9 8v-1a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1" /></svg>
                                            @else
                                                <!-- Car Icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" /></svg>
                                            @endif
                                        </div>

                                        <!-- Details -->
                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start mb-0.5">
                                                <h4 class="text-base font-black text-neutral-900 tracking-tight">{{ $vType->label() }}</h4>
                                                @if($rate)
                                                    <span class="text-xs font-bold text-neutral-400">₹{{ number_format($rate->rate_per_km, 0) }}/km</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest bg-neutral-100 px-2 py-0.5 rounded-full">Max {{ $vType->maxPassengers() }} Pax</span>
                                                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest px-2 py-0.5 rounded-full bg-emerald-50">Instant</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Addresses Container -->
                    <div class="space-y-4 mb-auto">
                        <div class="relative group">
                            <label class="block text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-2 pl-3">Pickup Address</label>
                            <input name="pickup_address" id="pickup_address" required readonly placeholder="Tap map exactly twice..."
                                   class="block w-full px-4 py-3.5 bg-neutral-50 border-2 border-neutral-100 rounded-2xl text-neutral-900 placeholder-neutral-400 focus:outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-400/10 transition-all font-semibold text-sm cursor-pointer shadow-sm pr-12">
                            
                            <!-- Current Location Button -->
                            <button type="button" id="use-current-location" class="absolute right-2 top-[30px] w-9 h-9 flex items-center justify-center bg-white border border-neutral-200 rounded-xl text-neutral-500 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400/20" title="Use My Current Location">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Hidden Map Coordinates -->
                        <div class="hidden">
                            <input type="hidden" name="pickup_lat" id="pickup_lat" readonly required>
                            <input type="hidden" name="pickup_lng" id="pickup_lng" readonly required>
                        </div>

                        <div class="relative group mt-5">
                            <div class="absolute -top-6 left-4 w-0.5 h-6 bg-neutral-200"></div>

                            <label class="block text-[11px] font-black text-amber-500 uppercase tracking-widest mb-2 pl-3">Drop-off Address</label>
                            <input name="drop_address" id="drop_address" required readonly placeholder="Tap map exactly twice..."
                                   class="block w-full px-4 py-3.5 bg-neutral-50 border-2 border-neutral-100 rounded-2xl text-neutral-900 placeholder-neutral-400 focus:outline-none focus:bg-white focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all font-semibold text-sm cursor-pointer shadow-sm">
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
        <div class="lg:col-span-3 order-1 lg:order-2 h-[400px] lg:h-full bg-neutral-100 rounded-[2.5rem] border-2 border-neutral-200 shadow-inner overflow-hidden relative">
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
    window.routes = {
        nearbyDrivers: '{{ route("customer.rides.nearby-drivers") }}'
    };
    window.vehicleRates = @json($rates);
</script>
<script src="{{ asset('js/customer/ride-create.js') }}"></script>
@endsection