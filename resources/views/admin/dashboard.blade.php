@extends('layouts.app')

@section('title', 'Admin Dashboard - Taxi-At-Foot')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 md:py-12 w-full">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black font-heading text-neutral-900 tracking-tight mb-2">Platform Overview</h1>
            <p class="text-neutral-500 font-medium text-lg">Manage users, track rides, and monitor platform health.</p>
        </div>
        <div class="flex items-center gap-3 bg-white px-5 py-3 rounded-2xl border border-neutral-200 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-neutral-400 uppercase tracking-widest">Administrator</p>
                <p class="text-sm font-black text-neutral-900">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Customers -->
        <a href="{{ route('admin.users', ['role' => 'customer']) }}" class="bg-white rounded-3xl p-6 border border-neutral-200 shadow-sm relative overflow-hidden flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow cursor-pointer block">
            <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <p class="text-sm font-bold text-neutral-500 mb-1">Total Customers</p>
            <h3 class="text-3xl font-black font-heading text-neutral-900">{{ number_format($totalCustomers) }}</h3>
        </a>

        <!-- Drivers -->
        <a href="{{ route('admin.users', ['role' => 'driver']) }}" class="bg-white rounded-3xl p-6 border border-neutral-200 shadow-sm relative overflow-hidden flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow cursor-pointer block">
            <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
            </div>
            <p class="text-sm font-bold text-neutral-500 mb-1">Total Drivers</p>
            <h3 class="text-3xl font-black font-heading text-neutral-900">{{ number_format($totalDrivers) }}</h3>
        </a>

        <!-- Rides -->
        <a href="{{ route('admin.rides') }}" class="bg-white rounded-3xl p-6 border border-neutral-200 shadow-sm relative overflow-hidden flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow cursor-pointer block">
            <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            </div>
            <p class="text-sm font-bold text-neutral-500 mb-1">Total Rides</p>
            <h3 class="text-3xl font-black font-heading text-neutral-900">{{ number_format($totalRides) }}</h3>
        </a>

        <!-- Revenue -->
        <a href="{{ route('admin.rides', ['status' => 'completed']) }}" class="bg-white rounded-3xl p-6 border border-neutral-200 shadow-sm relative overflow-hidden flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow cursor-pointer block">
            <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-sm font-bold text-neutral-500 mb-1">Gross Revenue (Completed)</p>
            <h3 class="text-3xl font-black font-heading text-neutral-900 tracking-tighter">{{ formatCurrency((float) $totalRevenue) }}</h3>
        </a>
        </div>
    </div>

    <!-- Recent Rides Table -->
    <div class="bg-white border text-left border-neutral-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="p-6 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
            <h2 class="text-xl font-black font-heading text-neutral-900">Recent Rides</h2>
            <a href="{{ route('admin.rides') }}" class="text-emerald-600 hover:text-emerald-700 font-bold text-sm flex items-center gap-1 group">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
        
        @if ($recentRides->isEmpty())
             <div class="p-12 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mb-4 border border-neutral-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                </div>
                <p class="text-neutral-500 font-medium">No rides have been requested yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-neutral-100 text-neutral-400 uppercase tracking-widest text-[10px] font-black">
                            <th class="p-4 pl-6 font-semibold">Ride ID</th>
                            <th class="p-4 font-semibold">Customer</th>
                            <th class="p-4 font-semibold">Driver</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold">Fare</th>
                            <th class="p-4 pr-6 font-semibold text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-50 bg-white">
                        @foreach ($recentRides as $ride)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="p-4 pl-6 text-neutral-500 font-mono text-xs font-bold">
                                    #{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="p-4">
                                    <span class="text-sm font-bold text-neutral-900">{{ $ride->customer->name ?? 'Guest' }}</span>
                                </td>
                                <td class="p-4">
                                    @if($ride->driver)
                                        <span class="text-sm font-bold text-neutral-900">{{ $ride->driver->name }}</span>
                                    @else
                                        <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest bg-neutral-100 px-2 py-1 rounded inline-block">Unassigned</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @php
                                        $statusStr = $ride->status->value;
                                        $badgeClass = match($statusStr) {
                                            'pending'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'accepted'  => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'driver_arriving' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'cancelled' => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                                            default     => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                                        };
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 text-[9px] font-black uppercase rounded border tracking-widest whitespace-nowrap {{ $badgeClass }}">
                                        {{ rideStatusLabel($ride->status) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-sm font-black font-heading text-neutral-900 tracking-tight">{{ formatCurrency((float) $ride->estimated_fare) }}</span>
                                </td>
                                <td class="p-4 pr-6 text-right text-xs font-medium text-neutral-500">
                                    {{ $ride->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
