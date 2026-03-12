@extends('layouts.admin')

@section('title', 'Ride Oversight - Taxi-At-Foot Admin')

@section('admin-content')
<div class="px-6 py-8 md:py-12 w-full">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black font-heading text-neutral-900 tracking-tight mb-2">Ride Oversight</h1>
            <p class="text-neutral-500 font-medium text-lg">Monitor all ride requests, tracking their status and assigned drivers.</p>
        </div>
        <div>
           <a href="{{ route('admin.dashboard') }}" class="px-5 py-3 bg-white hover:bg-neutral-50 border border-neutral-200 text-neutral-700 font-bold rounded-xl shadow-sm transition-all flex items-center gap-2 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Rides Table -->
    <div class="bg-white border text-left border-neutral-200 rounded-3xl overflow-hidden shadow-sm">
        
        <!-- Filter Tabs -->
        <div class="border-b border-neutral-100 flex overflow-x-auto hide-scrollbar bg-neutral-50/50">
            <a href="{{ route('admin.rides') }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ !$currentStatus ? 'text-indigo-600 border-b-2 border-indigo-600 bg-white' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100' }}">
                All Rides
            </a>
            <a href="{{ route('admin.rides', ['status' => 'completed']) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $currentStatus == 'completed' ? 'text-indigo-600 border-b-2 border-indigo-600 bg-white' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100' }}">
                Completed (Revenue)
            </a>
            <a href="{{ route('admin.rides', ['status' => 'cancelled']) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $currentStatus == 'cancelled' ? 'text-indigo-600 border-b-2 border-indigo-600 bg-white' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100' }}">
                Cancelled
            </a>
             <a href="{{ route('admin.rides', ['status' => 'pending']) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $currentStatus == 'pending' ? 'text-indigo-600 border-b-2 border-indigo-600 bg-white' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100' }}">
                Pending
            </a>
        </div>
        
        @if ($rides->isEmpty())
             <div class="p-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mb-6 border-2 border-dashed border-neutral-200">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <h3 class="text-2xl font-bold font-heading text-neutral-900 mb-2">No rides recorded yet</h3>
                <p class="text-neutral-500 max-w-sm mx-auto font-medium">Once customers start requesting rides, they will appear here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                     <thead>
                        <tr class="bg-neutral-50/80 border-b border-neutral-200 text-neutral-400 uppercase tracking-widest text-[10px] font-black">
                            <th class="p-4 pl-6 font-semibold">Ride Info</th>
                            <th class="p-4 font-semibold">Route Overview</th>
                            <th class="p-4 font-semibold">Parties Involved</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 pr-6 text-right font-semibold">Financials</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        @foreach ($rides as $ride)
                            <tr class="hover:bg-neutral-50/50 transition-colors">
                                <!-- Info -->
                                <td class="p-4 pl-6">
                                     <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-neutral-900 leading-tight">#{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}</p>
                                            <p class="text-[10px] font-bold text-neutral-400 mt-0.5">{{ $ride->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Route -->
                                <td class="p-4">
                                    <div class="flex flex-col gap-1.5 max-w-[200px]">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></div>
                                            <p class="text-[11px] font-bold text-neutral-600 truncate" title="{{ $ride->pickup_address }}">{{ $ride->pickup_address }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 bg-amber-400 shrink-0 rotate-45 transform"></div>
                                            <p class="text-[11px] font-bold text-neutral-600 truncate" title="{{ $ride->drop_address }}">{{ $ride->drop_address }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Parties -->
                                <td class="p-4">
                                     <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-block shrink-0 text-[10px] font-black uppercase tracking-widest text-neutral-400 w-14">Rider</span>
                                            <span class="text-sm font-bold text-neutral-900 truncate">{{ $ride->customer->name ?? 'Guest' }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                             <span class="inline-block shrink-0 text-[10px] font-black uppercase tracking-widest text-neutral-400 w-14">Driver</span>
                                             @if($ride->driver)
                                                <span class="text-sm font-bold text-neutral-900 truncate">{{ $ride->driver->name }}</span>
                                             @else
                                                <span class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest bg-neutral-100 px-1.5 py-0.5 rounded border border-neutral-200 inline-block">Unassigned</span>
                                             @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
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
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase rounded border tracking-widest whitespace-nowrap {{ $badgeClass }}">
                                        {{ rideStatusLabel($ride->status) }}
                                    </span>
                                </td>

                                <!-- Financials -->
                                <td class="p-4 pr-6 text-right">
                                    <p class="text-sm font-black font-heading tracking-tight text-neutral-900">{{ formatCurrency((float) $ride->estimated_fare) }}</p>
                                    <p class="text-[10px] font-bold text-neutral-500 mt-0.5">{{ $ride->distance_km }} km</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($rides->hasPages())
                <div class="p-4 border-t border-neutral-100 bg-neutral-50/30">
                    {{ $rides->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
