@extends('layouts.app')

@section('title', 'Dashboard - RideX')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12">
    
    @if (session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-8 flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Profile Column -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl p-8 border border-neutral-200 shadow-sm relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-400/20 rounded-full blur-[30px] transition-all group-hover:bg-amber-400/30"></div>
                
                <div class="flex items-center justify-center w-24 h-24 bg-neutral-100 border-2 border-white shadow-md rounded-full mb-6 relative z-10 text-3xl font-bold font-heading text-neutral-700">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                
                <h2 class="text-2xl font-black font-heading text-neutral-900 leading-tight">{{ Auth::user()->name }}</h2>
                <div class="inline-block px-3 py-1 bg-neutral-100 border border-neutral-200 text-neutral-600 text-xs font-black uppercase tracking-widest rounded-full mt-3 mb-6">
                    {{ Auth::user()->role }}
                </div>
                
                <div class="space-y-4 pt-6 border-t border-neutral-100">
                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        <span class="text-neutral-700 font-medium">{{ Auth::user()->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Grid Column -->
        <div class="md:col-span-2 flex flex-col gap-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xl font-black font-heading text-neutral-900">Quick Actions</h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 h-full">
                @if (Auth::user()->isCustomer())
                    <!-- Customer Actions -->
                    <a href="{{ route('customer.rides.create') }}" class="group bg-amber-400 p-8 rounded-3xl shadow-md border hover:border-amber-300 transition-all hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between min-h-[200px]">
                        <div class="absolute -bottom-8 -right-8 opacity-20 transform group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <div class="bg-white/90 p-3 rounded-xl w-14 h-14 flex items-center justify-center shadow-sm relative z-10 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-2xl font-black text-neutral-900 font-heading tracking-tight mb-1">Book a Ride</h4>
                            <p class="text-amber-900/80 font-medium text-sm">Where are you heading?</p>
                        </div>
                    </a>

                    <a href="{{ route('customer.rides.index') }}" class="group bg-white p-8 rounded-3xl shadow-sm border border-neutral-200 hover:border-neutral-300 transition-all hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between min-h-[200px]">
                        <div class="bg-neutral-100 p-3 rounded-xl w-14 h-14 flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-neutral-900 font-heading mb-1">Ride History</h4>
                            <p class="text-neutral-500 font-medium text-sm">View all your past trips</p>
                        </div>
                    </a>
                @elseif (Auth::user()->isDriver())
                    <!-- Driver Actions -->
                    <a href="{{ route('driver.rides.available') }}" class="group bg-emerald-500 p-8 rounded-3xl shadow-md border border-emerald-400 hover:border-emerald-300 transition-all hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between min-h-[200px]">
                        <div class="absolute -bottom-8 -right-8 opacity-20 transform group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <div class="bg-white/90 p-3 rounded-xl w-14 h-14 flex items-center justify-center shadow-sm relative z-10 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-2xl font-black text-white font-heading tracking-tight mb-1">Find Work</h4>
                            <p class="text-emerald-100 font-medium text-sm">See available ride requests</p>
                        </div>
                    </a>

                    <a href="{{ route('driver.rides.my') }}" class="group bg-white p-8 rounded-3xl shadow-sm border border-neutral-200 hover:border-neutral-300 transition-all hover:-translate-y-1 relative overflow-hidden flex flex-col justify-between min-h-[200px]">
                         <div class="bg-neutral-100 p-3 rounded-xl w-14 h-14 flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-neutral-900 font-heading mb-1">My Active Jobs</h4>
                            <p class="text-neutral-500 font-medium text-sm">Manage accepted and past rides</p>
                        </div>
                    </a>
                @endif
            </div>

            <!-- Global Action Logout Wrapper just to show off form aesthetic in Light mode -->
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-6 py-4 bg-white hover:bg-rose-50 border border-neutral-200 hover:border-rose-200 text-neutral-700 hover:text-rose-600 font-bold rounded-2xl transition-all shadow-sm flex items-center justify-center gap-2 group active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 bg-neutral-100 group-hover:bg-transparent rounded" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout from Dashboard
                </button>
            </form>
        </div>

    </div>
</div>
@endsection