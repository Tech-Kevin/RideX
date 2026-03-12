@extends('layouts.admin')

@section('title', 'Vehicle Rates - RideX Admin')

@section('admin-content')
<div class="px-6 py-8 md:py-12 w-full">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black font-heading text-neutral-900 tracking-tight mb-2">Vehicle Rates</h1>
            <p class="text-neutral-500 font-medium text-lg">Manage the base fare and rate per kilometer for different vehicle types.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($rates as $rate)
            <div class="bg-white border border-neutral-200 rounded-[2.5rem] p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <!-- Decoration -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-neutral-50 rounded-full group-hover:bg-neutral-100 transition-colors duration-500"></div>

                <div class="flex items-center gap-5 mb-8 relative z-10">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 @if($rate->vehicle_type?->value === 'bike') bg-blue-100/80 text-blue-600 @elseif($rate->vehicle_type?->value === 'auto') bg-amber-100/80 text-amber-600 @else bg-emerald-100/80 text-emerald-600 @endif flex items-center justify-center shadow-inner">
                        @if($rate->vehicle_type?->value === 'bike')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M19 18v-4l-2 -3l-3 -4h-3l3 4l-2 3" /><path d="M17 14h-6.5l-1.5 -3" /></svg>
                        @elseif($rate->vehicle_type?->value === 'auto')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h14v-6a3 3 0 0 0 -3 -3h-10a3 3 0 0 0 -3 3z" /><path d="M5 11h14" /><path d="M9 8v-1a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1" /></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" /></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-2xl font-black font-heading text-neutral-900 leading-tight">{{ $rate->vehicle_type?->label() ?? 'Unknown' }}</h3>
                        <p class="text-neutral-400 font-bold text-xs uppercase tracking-widest mt-1">Max {{ $rate->vehicle_type?->maxPassengers() ?? '?' }} Passengers</p>
                    </div>
                </div>

                <form action="{{ route('admin.rates.update', $rate) }}" method="POST" class="space-y-6 relative z-10">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-5">
                        <div class="group/input">
                            <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-2 px-1 transition-colors group-focus-within/input:text-neutral-900">Base Fare (₹)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-400 font-bold">₹</span>
                                <input type="number" step="0.01" name="base_fare" value="{{ old('base_fare', $rate->base_fare) }}" 
                                       class="w-full bg-neutral-50/50 border-2 border-neutral-100 rounded-2xl pl-10 pr-5 py-4 font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-neutral-900/5 transition-all">
                            </div>
                            @error('base_fare') <p class="text-rose-600 text-[10px] mt-1.5 font-bold pl-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="group/input">
                            <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-2 px-1 transition-colors group-focus-within/input:text-neutral-900">Price Per KM (₹)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-400 font-bold">₹</span>
                                <input type="number" step="0.01" name="rate_per_km" value="{{ old('rate_per_km', $rate->rate_per_km) }}" 
                                       class="w-full bg-neutral-50/50 border-2 border-neutral-100 rounded-2xl pl-10 pr-5 py-4 font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-neutral-900/5 transition-all">
                            </div>
                            @error('rate_per_km') <p class="text-rose-600 text-[10px] mt-1.5 font-bold pl-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-neutral-900 text-white rounded-2xl py-4 font-black hover:bg-neutral-800 transition-all shadow-xl shadow-neutral-900/10 active:scale-[0.98] mt-4">
                        Update {{ $rate->vehicle_type?->label() ?? 'Vehicle' }} Rates
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
