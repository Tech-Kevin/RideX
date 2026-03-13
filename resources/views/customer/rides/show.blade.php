@extends('layouts.app')

@section('title', 'Ride Details #' . $ride->id . ' - RideX')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/customer/ride-show.css') }}" />
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 md:py-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.rides.index') }}" class="p-2.5 bg-white hover:bg-neutral-50 border border-neutral-200 shadow-sm rounded-xl transition-all text-neutral-500 hover:text-neutral-900 active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black font-heading text-neutral-900 flex items-center gap-3">
                    Trip Ticket
                    <span class="text-neutral-400 font-mono text-lg font-bold">#{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}</span>
                </h1>
                <p class="text-xs text-neutral-500 font-bold uppercase tracking-widest mt-1">Booked: {{ $ride->created_at->format('M j, Y g:i A') }}</p>
            </div>
        </div>

        <a href="{{ route('customer.rides.create') }}" class="px-5 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold font-heading rounded-xl border border-amber-200 transition-all flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Book Another
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-6 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details Column -->
        <div class="lg:col-span-2 flex flex-col gap-6">

            <!-- Interactive Map -->
            <div class="bg-white border text-center border-neutral-200 rounded-3xl overflow-hidden shadow-sm h-[350px] relative">
                <div id="map" class="w-full h-full relative z-0"></div>
                <div class="absolute top-4 left-4 z-[2] pointer-events-none">
                    <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-xl text-[10px] font-black text-neutral-500 tracking-widest uppercase border border-neutral-200 shadow-sm">
                        Live Tracking
                    </span>
                </div>
            </div>
            
            <!-- Route Card -->
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-24 h-24 bg-amber-400/20 rounded-full blur-[30px] pointer-events-none"></div>

                <h2 class="text-[11px] font-black text-neutral-400 tracking-widest uppercase mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                    Trip Route Overview
                </h2>

                <div class="relative pl-7 space-y-8">
                    <!-- Vertical tracking line -->
                    <div class="absolute left-[0.45rem] top-3 bottom-3 w-0.5 bg-neutral-200"></div>

                    <div class="relative">
                        <div class="absolute -left-[2.1rem] w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-sm ring-1 ring-emerald-400/20"></div>
                        <p class="text-[10px] font-black text-emerald-600 tracking-widest uppercase mb-1">Pickup</p>
                        <p class="text-neutral-900 font-bold text-lg sm:text-xl leading-tight">{{ $ride->pickup_address }}</p>
                        <p class="text-xs text-neutral-400 mt-1 font-mono font-medium opacity-80">{{ $ride->pickup_lat }}, {{ $ride->pickup_lng }}</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[2.1rem] w-4 h-4 bg-amber-400 border-[3px] border-white shadow-sm ring-1 ring-amber-400/20 transform rotate-45"></div>
                        <p class="text-[10px] font-black text-amber-500 tracking-widest uppercase mb-1">Drop-off</p>
                        <p class="text-neutral-900 font-bold text-lg sm:text-xl leading-tight">{{ $ride->drop_address }}</p>
                        <p class="text-xs text-neutral-400 mt-1 font-mono font-medium opacity-80">{{ $ride->drop_lat }}, {{ $ride->drop_lng }}</p>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white border border-neutral-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-neutral-100 bg-neutral-50/50">
                    <h2 class="text-[11px] font-black text-neutral-400 tracking-widest uppercase flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Status & Logistics Timeline
                    </h2>
                </div>

                <div class="p-0">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-white text-neutral-400 border-b border-neutral-100 uppercase tracking-wider text-[10px] font-black">
                                <th class="p-4 pl-6">Status Log</th>
                                <th class="p-4 hidden sm:table-cell">Triggered By</th>
                                <th class="p-4 hidden md:table-cell">System Remarks</th>
                                <th class="p-4 pr-6 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody id="status-logs-tbody" class="divide-y divide-neutral-100/80 bg-white">
                            @forelse ($ride->statusLogs as $log)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="p-4 pl-6 font-bold text-neutral-800">{{ rideStatusLabel($log->status) }}</td>
                                    <td class="p-4 text-neutral-500 font-medium hidden sm:table-cell">{{ $log->changedByUser?->name ?? 'System Automated' }}</td>
                                    <td class="p-4 text-neutral-400 font-medium hidden md:table-cell">{{ $log->remarks ?? '-' }}</td>
                                    <td class="p-4 pr-6 text-neutral-500 text-right font-mono text-xs font-semibold">{{ $log->created_at->format('H:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-neutral-400 font-medium italic">Internal status logs are not available for this trip yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column (Meta & Driver) -->
        <div class="flex flex-col gap-6">
            
            <!-- Trip Meta Card -->
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-400/10 rounded-full blur-[30px] pointer-events-none"></div>

                @php
                    $statusStr = $ride->status->value ?? 'pending';
                    $badgeClass = match($statusStr) {
                        'pending'   => 'bg-rose-50 text-rose-700 border-rose-200 ring-1 ring-rose-600/10',
                        'accepted'  => 'bg-blue-50 text-blue-700 border-blue-200 ring-1 ring-blue-600/10',
                        'driver_arriving' => 'bg-indigo-50 text-indigo-700 border-indigo-200 ring-1 ring-indigo-600/10',
                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200 ring-1 ring-amber-600/10',
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-1 ring-emerald-600/10',
                        'cancelled' => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                        default     => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                    };
                @endphp

                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-[11px] font-black text-neutral-400 uppercase tracking-widest">Financials</h3>
                    <span id="ride-status-badge" class="inline-block px-3 py-1 text-[10px] font-black uppercase rounded-lg border tracking-widest whitespace-nowrap shadow-sm {{ $badgeClass }}">
                        {{ rideStatusLabel($ride->status) }}
                    </span>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-center bg-neutral-50 p-4 rounded-2xl border border-neutral-100">
                        <span class="text-neutral-500 font-semibold text-sm tracking-wide">Distance Calculation</span>
                        <span class="text-neutral-900 font-black font-heading">{{ $ride->distance_km }} km</span>
                    </div>

                    <div class="flex justify-between items-center bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100 mt-2">
                        <span class="text-emerald-600 font-black tracking-widest uppercase text-[10px]">Estimated Price</span>
                        <span class="text-emerald-600 font-black font-heading text-3xl tracking-tighter drop-shadow-sm">{{ formatCurrency((float) $ride->estimated_fare) }}</span>
                    </div>
                </div>

                @if(in_array($ride->status->value, ['pending', 'accepted']))
                    <div class="mt-6 pt-6 border-t border-neutral-100 flex justify-end">
                        <form action="{{ route('customer.rides.cancel', $ride) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this ride?');">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-sm rounded-xl border border-red-200 transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Cancel Ride
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Driver Card -->
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                <h3 class="text-[11px] font-black text-neutral-400 uppercase tracking-widest mb-6">Assigned Driver</h3>

                <div id="driver-card-content">
                @if ($ride->driver)
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-neutral-800 to-neutral-900 rounded-2xl flex items-center justify-center text-white font-black font-heading text-2xl shadow-md border border-neutral-700">
                            {{ substr($ride->driver->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-lg font-black font-heading text-neutral-900 leading-tight mb-1">{{ $ride->driver->name }}</p>
                            <p class="text-[10px] border border-blue-200 bg-blue-50 text-blue-700 font-black tracking-widest uppercase px-2 py-0.5 rounded inline-block">Pro Partner</p>
                        </div>
                    </div>

                    <a href="tel:{{ $ride->driver->phone }}" class="bg-neutral-50 hover:bg-neutral-100 p-4 rounded-2xl border border-neutral-200 flex items-center justify-between gap-3 mt-4 transition-colors group">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span class="text-neutral-700 font-bold tracking-wider">{{ $ride->driver->phone }}</span>
                        </div>
                        <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Call</span>
                    </a>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-16 h-16 rounded-full border-2 border-dashed border-amber-300 bg-amber-50 flex items-center justify-center mb-4 relative z-10">
                            <span class="relative flex h-4 w-4">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                            </span>
                        </div>
                        <p class="text-neutral-900 font-bold mb-1">Connecting to drivers...</p>
                        <p class="text-xs text-neutral-500 font-medium max-w-[150px]">We're searching the area for the best partner.</p>
                    </div>
                @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    window.config = {
        pickupLat: '{{ $ride->pickup_lat }}',
        pickupLng: '{{ $ride->pickup_lng }}',
        dropLat: '{{ $ride->drop_lat }}',
        dropLng: '{{ $ride->drop_lng }}',
        currentStatus: '{{ $ride->status->value }}',
        nearbyDriversRoute: '{{ route("customer.rides.nearby-drivers") }}',
        isDriverActive: {{ (in_array($ride->status->value, ['accepted', 'driver_arriving', 'in_progress']) && $ride->driver_id) ? 'true' : 'false' }},
        driverLocationRoute: '{{ route("customer.rides.driver-location", $ride) }}',
        rideStatusRoute: '{{ route("customer.rides.status", $ride) }}',
        rideId: '{{ $ride->id }}'
    };
</script>
<script src="{{ asset('js/customer/ride-show.js') }}"></script>
@endsection