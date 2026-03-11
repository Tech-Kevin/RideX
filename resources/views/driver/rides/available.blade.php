@extends('layouts.app')

@section('title', 'Available Rides - Taxi-At-Foot')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8 md:py-12 w-full">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black font-heading text-neutral-900 mb-2 tracking-tight">Available Jobs</h1>
            <p class="text-neutral-500 font-medium">Listening for new ride requests in your area.</p>
        </div>
        
        <!-- Live Indicator -->
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm rounded-full font-bold text-sm">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            Online & Ready
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-8 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl mb-8 flex items-center gap-3">
            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            <p class="text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="rides-container">
        <!-- New Rides Will Be Prepended Here by JS -->

        @forelse ($rides as $ride)
            <div class="bg-white border border-neutral-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden flex flex-col group" id="ride-card-{{ $ride->id }}">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/10 rounded-full blur-[30px] -mt-10 -mr-10 pointer-events-none"></div>

                <!-- Header -->
                <div class="flex justify-between items-start mb-6 border-b border-neutral-100 pb-4">
                    <div>
                        <span class="inline-block px-2 py-0.5 bg-neutral-100 text-neutral-500 font-bold font-mono text-[10px] uppercase tracking-widest rounded mb-1 border border-neutral-200">
                            #{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        <p class="text-xs text-neutral-400 font-medium">{{ $ride->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black tracking-widest uppercase text-emerald-600 mb-0.5">Estimated Payout</p>
                        <p class="text-2xl font-black font-heading text-neutral-900 tracking-tighter">{{ formatCurrency((float) $ride->estimated_fare) }}</p>
                    </div>
                </div>

                <!-- Locations -->
                <div class="relative pl-7 space-y-5 mb-auto">
                    <div class="absolute left-[0.45rem] top-3 bottom-3 w-0.5 bg-neutral-200"></div>

                    <div class="relative">
                        <div class="absolute -left-[2.1rem] w-4 h-4 rounded-full bg-emerald-400 border-[3px] border-white shadow-sm ring-1 ring-emerald-400/20"></div>
                        <p class="text-[10px] font-black text-emerald-600 tracking-widest uppercase mb-1">Pickup</p>
                        <p class="text-sm font-bold text-neutral-800 leading-snug line-clamp-2" title="{{ $ride->pickup_address }}">{{ $ride->pickup_address }}</p>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[2.1rem] w-4 h-4 bg-amber-400 border-[3px] border-white shadow-sm ring-1 ring-amber-400/20 transform rotate-45"></div>
                        <p class="text-[10px] font-black text-amber-500 tracking-widest uppercase mb-1">Drop-off</p>
                        <p class="text-sm font-bold text-neutral-800 leading-snug line-clamp-2" title="{{ $ride->drop_address }}">{{ $ride->drop_address }}</p>
                    </div>
                </div>

                <!-- Footer Metas -->
                <div class="flex justify-between items-center mt-6 py-4 border-t border-neutral-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black tracking-widest uppercase text-neutral-400 leading-none mb-0.5">Rider</p>
                            <p class="text-xs font-bold text-neutral-900">{{ $ride->customer->name ?? 'Guest' }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] font-black tracking-widest uppercase text-neutral-400 leading-none mb-0.5">Distance</p>
                        <p class="text-sm font-bold font-mono text-neutral-900">{{ $ride->distance_km }} km</p>
                    </div>
                </div>

                <!-- Action -->
                <form method="POST" action="{{ route('driver.rides.accept', $ride) }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-neutral-900 hover:bg-neutral-800 text-white border border-neutral-800 font-bold rounded-xl shadow-lg shadow-neutral-900/20 transition-all hover:-translate-y-0.5 active:scale-[0.98] flex items-center justify-center gap-2">
                        Accept Ride
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    </button>
                </form>
            </div>
        @empty
            <div id="no-rides-message" class="col-span-1 md:col-span-2 lg:col-span-3">
                <div class="bg-white border border-neutral-200 rounded-3xl p-16 text-center shadow-sm flex flex-col items-center">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                        <div class="relative flex h-8 w-8">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-40"></span>
                          <span class="relative inline-flex rounded-full h-8 w-8 bg-emerald-500"></span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold font-heading text-neutral-900 mb-2">Searching for nearby riders...</h3>
                    <p class="text-neutral-500 font-medium max-w-sm mx-auto">Keep this page open. New ride requests in your vicinity will appear here instantly.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.config = {
        knownRideIds: [@foreach($rides as $r) {{ $r->id }}, @endforeach],
        pollAvailableRoute: '{{ route("driver.rides.poll-available") }}',
        locationUpdateRoute: '{{ route("driver.location.update") }}'
    };
</script>
<script src="{{ asset('js/driver/available.js') }}"></script>
@endsection