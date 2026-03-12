@extends('layouts.admin')

@section('title', 'Admin Dashboard - RideX')

@section('admin-content')
<div class="px-6 py-8 md:py-12 w-full">
    
    <!-- Dashboard Header -->
    <div class="mb-10 animate-[fade-in-up_0.6s_ease-out]">
        <h1 class="text-4xl font-black font-heading text-neutral-900 tracking-tight mb-2">Dashboard</h1>
        <div class="flex items-center gap-2">
            <span class="text-neutral-400 font-bold text-sm">Platform</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
            <span class="text-amber-500 font-black text-sm uppercase tracking-widest">Overview</span>
        </div>
    </div>

    <!-- Main Stat Cards (Reference Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Welcome/Users -->
        <div class="bg-white rounded-[2.5rem] p-10 border border-neutral-100 shadow-xl shadow-neutral-900/[0.02] flex flex-col items-center group hover:scale-105 transition-all duration-500">
            <div class="w-20 h-20 rounded-[1.8rem] bg-amber-50 text-amber-500 flex items-center justify-center mb-6 group-hover:bg-amber-400 group-hover:text-neutral-900 transition-colors duration-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <h3 class="text-4xl font-black font-heading text-neutral-900 tracking-tighter mb-1">{{ number_format($totalCustomers + $totalDrivers) }}</h3>
            <p class="text-[11px] font-black text-neutral-400 uppercase tracking-[0.2em]">Total Users</p>
        </div>

        <!-- Average Time/Rides -->
        <div class="bg-white rounded-[2.5rem] p-10 border border-neutral-100 shadow-xl shadow-neutral-900/[0.02] flex flex-col items-center group hover:scale-105 transition-all duration-500 text-center">
            <div class="w-20 h-20 rounded-[1.8rem] bg-indigo-50 text-indigo-500 flex items-center justify-center mb-6 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-4xl font-black font-heading text-neutral-900 tracking-tighter mb-1">{{ number_format($totalRides) }}</h3>
            <p class="text-[11px] font-black text-neutral-400 uppercase tracking-[0.2em]">Total Rides</p>
        </div>

        <!-- Collections/Revenue -->
        <div class="bg-white rounded-[2.5rem] p-10 border border-neutral-100 shadow-xl shadow-neutral-900/[0.02] flex flex-col items-center group hover:scale-105 transition-all duration-500">
            <div class="w-20 h-20 rounded-[1.8rem] bg-emerald-50 text-emerald-500 flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-4xl font-black font-heading text-neutral-900 tracking-tighter mb-1">{{ formatCurrency((float) $totalRevenue) }}</h3>
            <p class="text-[11px] font-black text-neutral-400 uppercase tracking-[0.2em]">Gross Revenue</p>
        </div>

        <!-- Comments/Verifications -->
        <div class="bg-white rounded-[2.5rem] p-10 border border-neutral-100 shadow-xl shadow-neutral-900/[0.02] flex flex-col items-center group hover:scale-105 transition-all duration-500">
            <div class="w-20 h-20 rounded-[1.8rem] bg-rose-50 text-rose-500 flex items-center justify-center mb-6 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-500 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </div>
            <h3 class="text-4xl font-black font-heading text-neutral-900 tracking-tighter mb-1">Queue</h3>
            <p class="text-[11px] font-black text-neutral-400 uppercase tracking-[0.2em]">Verifications</p>
        </div>
    </div>

<div x-data="{ selectedVehicle: 'none' }" x-cloak>
    <!-- Color Cards (Vehicle Type Metrics) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Bike Card -->
        <button @click="selectedVehicle = (selectedVehicle === 'bike' ? 'none' : 'bike')" 
                class="rounded-[2.5rem] p-8 text-white relative overflow-hidden group transition-all duration-500 shadow-xl border-2"
                :class="selectedVehicle === 'bike' ? 'bg-amber-500 border-amber-300 scale-[1.02]' : 'bg-[#1f2937] border-white/5 hover:border-amber-400/50'">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-6 relative z-10 text-left">
                <div class="w-16 h-16 rounded-[1.2rem] bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10 shadow-xl"
                     :class="selectedVehicle === 'bike' ? 'bg-white/20' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <h4 class="text-3xl font-black font-heading tracking-tighter" :class="selectedVehicle === 'bike' ? 'text-neutral-900' : 'text-white'" style="color: black;">BIKE</h4>
                    <p class="text-xs font-black uppercase tracking-widest" :class="selectedVehicle === 'bike' ? 'text-neutral-900/60' : 'text-neutral-400'">{{ \App\Models\Ride::where('vehicle_type', 'bike')->count() }} Total Rides</p>
                </div>
            </div>
            <div class="absolute bottom-4 right-8 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity" x-show="selectedVehicle !== 'bike'">
                <span class="text-[9px] font-black uppercase tracking-widest text-amber-400">View Riders</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </div>
        </button>

        <!-- Auto Card -->
        <button @click="selectedVehicle = (selectedVehicle === 'auto' ? 'none' : 'auto')" 
                class="rounded-[2.5rem] p-8 text-white relative overflow-hidden group transition-all duration-500 shadow-xl border-2"
                :class="selectedVehicle === 'auto' ? 'bg-amber-500 border-amber-300 scale-[1.02]' : 'bg-[#1f2937] border-white/5 hover:border-amber-400/50'">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-6 relative z-10 text-left">
                <div class="w-16 h-16 rounded-[1.2rem] bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10 shadow-xl"
                     :class="selectedVehicle === 'auto' ? 'bg-white/20' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <h4 class="text-3xl font-black font-heading tracking-tighter" :class="selectedVehicle === 'auto' ? 'text-neutral-900' : 'text-white'" style="color: black;">AUTO</h4>
                    <p class="text-xs font-black uppercase tracking-widest" :class="selectedVehicle === 'auto' ? 'text-neutral-900/60' : 'text-neutral-400'">{{ \App\Models\Ride::where('vehicle_type', 'auto')->count() }} Total Rides</p>
                </div>
            </div>
            <div class="absolute bottom-4 right-8 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity" x-show="selectedVehicle !== 'auto'">
                <span class="text-[9px] font-black uppercase tracking-widest text-amber-400">View Riders</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </div>
        </button>

        <!-- Car Card -->
        <button @click="selectedVehicle = (selectedVehicle === 'car' ? 'none' : 'car')" 
                class="rounded-[2.5rem] p-8 text-white relative overflow-hidden group transition-all duration-500 shadow-xl border-2"
                :class="selectedVehicle === 'car' ? 'bg-amber-500 border-amber-300 scale-[1.02]' : 'bg-[#1f2937] border-white/5 hover:border-amber-400/50'">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center gap-6 relative z-10 text-left">
                <div class="w-16 h-16 rounded-[1.2rem] bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10 shadow-xl"
                     :class="selectedVehicle === 'car' ? 'bg-white/20' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
                <div>
                    <h4 class="text-3xl font-black font-heading tracking-tighter" :class="selectedVehicle === 'car' ? 'text-neutral-900' : 'text-white'" style="color: black;">CAR</h4>
                    <p class="text-xs font-black uppercase tracking-widest" :class="selectedVehicle === 'car' ? 'text-neutral-900/60' : 'text-neutral-400'">{{ \App\Models\Ride::where('vehicle_type', 'car')->count() }} Total Rides</p>
                </div>
            </div>
            <div class="absolute bottom-4 right-8 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity" x-show="selectedVehicle !== 'car'">
                <span class="text-[9px] font-black uppercase tracking-widest text-amber-400">View Riders</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </div>
        </button>
    </div>

    <!-- Dynamic Section: Recent Rides OR Selected Vehicle Drivers -->
    <div class="bg-white border border-neutral-100 rounded-[2.5rem] overflow-hidden shadow-xl shadow-neutral-900/[0.02]">
        <!-- Table Header -->
        <div class="p-8 border-b border-neutral-100 flex justify-between items-center bg-white">
            <div>
                <h2 class="text-2xl font-black font-heading text-neutral-900" x-text="selectedVehicle === 'none' ? 'Recent Rides' : selectedVehicle.toUpperCase() + ' Riders'">Recent Rides</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                    <p class="text-[10px] font-black text-neutral-400 uppercase tracking-widest" x-text="selectedVehicle === 'none' ? 'Live Platform Activity' : 'Verified ' + selectedVehicle + ' partners'">Live Platform Activity</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button x-show="selectedVehicle !== 'none'" @click="selectedVehicle = 'none'" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-600 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    Clear Filter
                </button>
                <a href="{{ route('admin.rides') }}" class="text-amber-500 hover:text-amber-600 font-black text-xs uppercase tracking-widest flex items-center gap-2 group transition-all">
                    View Comprehensive Log
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>
        
        <!-- Recent Rides Table (Visible when selectedVehicle is 'none') -->
        <template x-if="selectedVehicle === 'none'">
            <div>
                @if ($recentRides->isEmpty())
                    <div class="p-20 flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center mb-6 border-2 border-amber-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-black text-neutral-900 mb-2 tracking-tight">Quiet on the front...</h3>
                        <p class="text-neutral-500 max-w-xs leading-relaxed font-medium">No ride requests have hit the platform yet today.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 border-b border-neutral-100 text-neutral-400 uppercase tracking-[0.2em] text-[10px] font-black">
                                    <th class="px-8 py-5 text-left">Internal ID</th>
                                    <th class="px-6 py-5 text-left">Customer</th>
                                    <th class="px-6 py-5 text-left">Assigned Driver</th>
                                    <th class="px-6 py-5 text-center">Platform Status</th>
                                    <th class="px-6 py-5 text-right">Fare (Est)</th>
                                    <th class="px-8 py-5 text-right">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-50 bg-white">
                                @foreach ($recentRides as $ride)
                                    <tr class="hover:bg-slate-50 transition-all duration-300 group">
                                        <td class="px-8 py-6 text-neutral-400 font-mono text-xs font-black group-hover:text-amber-500">
                                            #{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-6 font-bold text-neutral-900">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-[10px] text-neutral-400">
                                                    {{ substr($ride->customer->name ?? 'G', 0, 1) }}
                                                </div>
                                                {{ $ride->customer->name ?? 'Guest User' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 font-bold text-neutral-900">
                                            @if($ride->driver)
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-[10px] text-amber-500">
                                                        {{ substr($ride->driver->name, 0, 1) }}
                                                    </div>
                                                    {{ $ride->driver->name }}
                                                </div>
                                            @else
                                                <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200">Waiting for Driver</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            @php
                                                $statusStr = $ride->status->value;
                                                $badgeClass = match($statusStr) {
                                                    'pending'   => 'bg-rose-50 text-rose-700 border-rose-200 shadow-sm shadow-rose-900/5',
                                                    'accepted'  => 'bg-info-50 text-blue-700 border-blue-200',
                                                    'driver_arriving' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'in_progress' => 'bg-amber-400 text-neutral-900 border-amber-500 shadow-lg shadow-amber-400/20',
                                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'cancelled' => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                                                    default     => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                                                };
                                            @endphp
                                            <span class="inline-block px-4 py-2 text-[9px] font-black uppercase rounded-xl border tracking-[0.1em] whitespace-nowrap {{ $badgeClass }}">
                                                {{ rideStatusLabel($ride->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-6 text-right">
                                            <span class="text-sm font-black font-heading text-neutral-900 tracking-tight">{{ formatCurrency((float) $ride->estimated_fare) }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-right text-[11px] font-bold text-neutral-400">
                                            {{ $ride->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </template>

        <!-- Dynamic Content: Vehicle Riders Table -->
        <template x-if="selectedVehicle !== 'none'">
            <div class="p-8">
                <div class="bg-slate-50 rounded-[2rem] p-8 border border-neutral-100 animate-[fade-in-up_0.4s_ease-out]">
                    @php
                        $drivers = \App\Models\User::where('role', 'driver')->get();
                    @endphp
                    
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-400 flex items-center justify-center text-neutral-900 shadow-xl shadow-amber-400/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-neutral-900 leading-none mb-1">Active Partners</h3>
                                <p class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">Listing all verified drivers registered with <span x-text="selectedVehicle" class="text-amber-500"></span>s</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($drivers as $driver)
                            <div x-show="selectedVehicle === '{{ $driver->vehicle_type?->value }}'" 
                                 class="bg-white p-6 rounded-3xl border border-neutral-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-neutral-100 flex items-center justify-center text-neutral-400 font-black relative">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=f8f9fa&color=111827" class="w-full h-full rounded-2xl object-cover">
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white {{ $driver->is_active ? 'bg-emerald-500' : 'bg-neutral-300' }}"></div>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-neutral-900 leading-none mb-1 group-hover:text-amber-500 transition-colors">{{ $driver->name }}</h4>
                                            <p class="text-[10px] font-black text-neutral-400 uppercase tracking-widest">{{ $driver->vehicle_number ?? 'NO-PLATE' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        <span class="text-[10px] font-black text-amber-600">4.8</span>
                                    </div>
                                </div>
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-neutral-400 font-bold">Verification</span>
                                        <span class="font-black uppercase tracking-widest {{ $driver->verification_status === 'approved' ? 'text-emerald-500' : 'text-amber-500' }}">
                                            {{ $driver->verification_status ?? 'Pending' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-neutral-400 font-bold">Total Rides</span>
                                        <span class="text-neutral-900 font-black">{{ $driver->driverRides()->count() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-neutral-50">
                                    <span class="text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 {{ $driver->is_active ? 'text-emerald-500' : 'text-neutral-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $driver->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-neutral-300' }}"></span>
                                        {{ $driver->is_active ? 'Online' : 'Offline' }}
                                    </span>
                                    <button class="text-[10px] font-bold text-amber-500 hover:text-amber-600 uppercase tracking-widest transition-colors">Details</button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center">
                                <p class="text-neutral-400 font-bold uppercase tracking-widest text-[11px]">No drivers found in the system</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
</div>
@endsection
