@extends('layouts.app')

@section('title', 'My Rides - RideX')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8 md:py-12 w-full">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black font-heading text-neutral-900 mb-2 tracking-tight">Trip History</h1>
            <p class="text-neutral-500 font-medium">View your entire ride history and track current trips.</p>
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <a href="{{ route('dashboard') }}" class="flex-1 md:flex-none justify-center px-5 py-3 bg-white hover:bg-neutral-50 border border-neutral-200 text-neutral-700 font-bold rounded-xl transition-all shadow-sm active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Dashboard
            </a>
            <a href="{{ route('customer.rides.create') }}" class="flex-1 md:flex-none justify-center px-6 py-3 bg-neutral-900 hover:bg-neutral-800 text-white font-bold rounded-xl shadow-lg shadow-neutral-900/20 border border-neutral-800 transition-all hover:-translate-y-0.5 active:scale-[0.98] flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Book Ride
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-8 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white border text-center border-neutral-200 rounded-3xl overflow-hidden shadow-sm">
        @if ($rides->isEmpty())
            <div class="p-16 flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mb-6 border-2 border-dashed border-neutral-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-2xl font-bold font-heading text-neutral-900 mb-2">No rides found</h3>
                <p class="text-neutral-500 text-center max-w-sm mx-auto mb-8 font-medium font-medium">You haven't requested any rides yet. Head over to the booking map to get moving!</p>
                <a href="{{ route('customer.rides.create') }}" class="inline-flex items-center justify-center px-8 py-4 bg-amber-400 hover:bg-amber-300 text-neutral-900 font-black font-heading tracking-wide rounded-2xl shadow-lg shadow-amber-400/30 transition-all hover:-translate-y-1 active:scale-[0.98]">
                    Book a trip now
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border-slate-50">
                    <thead>
                        <tr class="bg-neutral-50 border-b border-neutral-200 text-neutral-500 uppercase tracking-wider text-xs font-black">
                            <th class="p-4 pl-6 hidden md:table-cell">Reg. ID</th>
                            <th class="p-4">Route Info</th>
                            <th class="p-4 hidden lg:table-cell">Summary</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 hidden sm:table-cell">Partner</th>
                            <th class="p-4 pr-6 text-right">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        @foreach ($rides as $ride)
                            <tr class="hover:bg-neutral-50/80 transition-colors group cursor-pointer" onclick="window.location='{{ route('customer.rides.show', $ride) }}'">
                                <td class="p-4 pl-6 text-neutral-500 font-mono text-sm font-bold hidden md:table-cell">
                                    #{{ str_pad($ride->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                
                                <td class="p-4">
                                    <div class="flex flex-col gap-2.5 max-w-[200px] sm:max-w-xs">
                                        <div class="flex items-start gap-2.5 group/loc">
                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 mt-1.5 shrink-0 shadow-sm border border-emerald-200"></div>
                                            <p class="text-sm font-medium text-neutral-700 truncate group-hover/loc:text-neutral-900 transition-colors" title="{{ $ride->pickup_address }}">{{ $ride->pickup_address }}</p>
                                        </div>
                                        <div class="flex items-start gap-2.5 group/loc">
                                            <div class="w-2.5 h-2.5 bg-amber-400 mt-1.5 shrink-0 shadow-sm border border-amber-200 rotate-45 transform"></div>
                                            <p class="text-sm font-medium text-neutral-700 truncate group-hover/loc:text-neutral-900 transition-colors" title="{{ $ride->drop_address }}">{{ $ride->drop_address }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="p-4 hidden lg:table-cell">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-neutral-400">{{ $ride->distance_km }} km</span>
                                        <span class="text-sm font-black font-heading text-neutral-900">{{ formatCurrency((float) $ride->estimated_fare) }}</span>
                                    </div>
                                </td>

                                <td class="p-4 mt-3 xl:mt-0 inline-flex xl:table-cell items-center align-middle">
                                    @php
                                        $statusStr = $ride->status->value;
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
                                    <span class="inline-block px-3 py-1.5 text-[10px] font-black uppercase rounded-lg border tracking-widest whitespace-nowrap {{ $badgeClass }}">
                                        {{ rideStatusLabel($ride->status) }}
                                    </span>
                                </td>

                                <td class="p-4 hidden sm:table-cell">
                                    @if($ride->driver)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-neutral-900 flex items-center justify-center text-xs font-bold text-white shadow-sm ring-2 ring-white">
                                                {{ substr($ride->driver->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-bold text-neutral-800">{{ $ride->driver->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-neutral-400 uppercase tracking-widest bg-neutral-50 px-2 py-1 rounded inline-block">Waiting</span>
                                    @endif
                                </td>

                                <td class="p-4 pr-6 text-right">
                                    <div class="inline-flex items-center justify-center w-10 h-10 bg-white group-hover:bg-neutral-900 group-hover:text-white border border-neutral-200 text-neutral-500 rounded-xl transition-all shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </div>
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