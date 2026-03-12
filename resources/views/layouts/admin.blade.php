@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-slate-50 w-full overflow-hidden" x-data="{ sidebarOpen: true }">
    <!-- Sidebar -->
    <aside class="bg-[#111827] border-r border-neutral-800 flex-shrink-0 flex flex-col transition-all duration-300 z-50 overflow-y-auto"
           :class="sidebarOpen ? 'w-[240px]' : 'w-0 lg:w-[70px]'">
        
        <!-- Sidebar User Profile -->
        <div class="p-6 border-b border-white/5 flex flex-col items-center text-center animate-[fade-in_0.5s_ease-out]" x-show="sidebarOpen">
            <div class="relative mb-4 group">
                <div class="w-14 h-14 rounded-full border-2 border-amber-400 p-1 group-hover:scale-105 transition-transform duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1f2937&color=fbbf24&size=128" 
                         class="w-full h-full rounded-full object-cover shadow-xl">
                </div>
                <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-[#111827] rounded-full shadow-sm animate-pulse"></div>
            </div>
            <h3 class="text-white font-bold text-sm tracking-tight mb-0.5">{{ auth()->user()->name }}</h3>
            <p class="text-emerald-400 text-[9px] font-black uppercase tracking-widest flex items-center gap-1 justify-center">
                <span class="w-1 h-1 bg-emerald-500 rounded-full"></span> Online
            </p>
        </div>

        <!-- Sidebar Navigation -->
        <div class="flex-grow py-6 scrollbar-hide overflow-x-hidden">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-neutral-500 mb-6 px-8" x-show="sidebarOpen">Navigation</p>
            
            <nav class="space-y-1 px-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all group overflow-hidden"
                   :class="sidebarOpen ? 'px-4' : 'justify-center'"
                   title="Dashboard">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center transition-all duration-300
                        {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-neutral-900 shadow-lg shadow-amber-400/20' : 'bg-white/5 text-neutral-400 group-hover:bg-white/10 group-hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    </div>
                    <span class="text-xs tracking-tight transition-colors duration-300" x-show="sidebarOpen" :class="request()->routeIs('admin.dashboard') ? 'text-white' : 'text-neutral-300 group-hover:text-white'">Dashboard</span>
                </a>

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all group overflow-hidden"
                   :class="sidebarOpen ? 'px-4' : 'justify-center'"
                   title="Users">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center transition-all duration-300
                        {{ request()->routeIs('admin.users') ? 'bg-amber-400 text-neutral-900 shadow-lg shadow-amber-400/20' : 'bg-white/5 text-neutral-400 group-hover:bg-white/10 group-hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <span class="text-xs tracking-tight transition-colors duration-300" x-show="sidebarOpen" :class="request()->routeIs('admin.users') ? 'text-white' : 'text-neutral-300 group-hover:text-white'">Users</span>
                </a>

                <a href="{{ route('admin.verifications') }}" 
                   class="flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all group overflow-hidden"
                   :class="sidebarOpen ? 'px-4' : 'justify-center'"
                   title="Verifications">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center transition-all duration-300
                        {{ request()->routeIs('admin.verifications') ? 'bg-amber-400 text-neutral-900 shadow-lg shadow-amber-400/20' : 'bg-white/5 text-neutral-400 group-hover:bg-white/10 group-hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <span class="text-xs tracking-tight transition-colors duration-300" x-show="sidebarOpen" :class="request()->routeIs('admin.verifications') ? 'text-white' : 'text-neutral-300 group-hover:text-white'">Pending</span>
                </a>

                <a href="{{ route('admin.rates') }}" 
                   class="flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all group overflow-hidden"
                   :class="sidebarOpen ? 'px-4' : 'justify-center'"
                   title="Rates">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center transition-all duration-300
                        {{ request()->routeIs('admin.rates') ? 'bg-amber-400 text-neutral-900 shadow-lg shadow-amber-400/20' : 'bg-white/5 text-neutral-400 group-hover:bg-white/10 group-hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs tracking-tight transition-colors duration-300" x-show="sidebarOpen" :class="request()->routeIs('admin.rates') ? 'text-white' : 'text-neutral-300 group-hover:text-white'">Rates</span>
                </a>

                <a href="{{ route('admin.rides') }}" 
                   class="flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all group overflow-hidden"
                   :class="sidebarOpen ? 'px-4' : 'justify-center'"
                   title="History">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center transition-all duration-300
                        {{ request()->routeIs('admin.rides') ? 'bg-amber-400 text-neutral-900 shadow-lg shadow-amber-400/20' : 'bg-white/5 text-neutral-400 group-hover:bg-white/10 group-hover:text-white' }}">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <span class="text-xs tracking-tight transition-colors duration-300" x-show="sidebarOpen" :class="request()->routeIs('admin.rides') ? 'text-white' : 'text-neutral-300 group-hover:text-white'">History</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-6 border-t border-white/5 mt-auto" x-show="sidebarOpen">
            <div class="bg-white/5 rounded-2xl p-4 relative overflow-hidden group border border-white/5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-6 h-6 rounded-lg bg-amber-400 flex items-center justify-center text-neutral-900 shadow-lg shadow-amber-400/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Support</p>
                </div>
                <p class="text-[9px] font-bold text-neutral-400 leading-relaxed uppercase tracking-wider">Help Center</p>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-grow flex flex-col min-w-0 h-full overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-[#1f2937] border-b border-white/5 flex items-center justify-between px-6 flex-shrink-0 z-40">
            <div class="flex items-center gap-5">
                <!-- Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-neutral-900 shadow-lg shadow-amber-400/20 hover:scale-105 active:scale-95 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" x-show="sidebarOpen" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" x-show="!sidebarOpen" />
                    </svg>
                </button>

                <!-- Logo Section -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-400 rounded-lg flex items-center justify-center transform rotate-3 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-900" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xl font-black font-heading text-white tracking-tight hidden sm:block">Taxi-At-Foot <span class="text-amber-400 text-xs align-top ml-0.5 font-bold uppercase tracking-widest">Admin</span></span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Action Icons -->
                <div class="flex items-center gap-2 px-3 py-1 bg-white/5 rounded-xl border border-white/5">
                    <button class="p-2 text-neutral-300 hover:text-white transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-amber-400 rounded-full border border-[#1f2937]"></span>
                    </button>
                    <button class="p-2 text-neutral-300 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </button>
                </div>

                <!-- User Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-2 p-1.5 bg-white/10 hover:bg-white/15 rounded-xl border border-white/5 transition-all">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-neutral-800 flex-shrink-0">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=111827&color=fbbf24" class="w-full h-full object-cover">
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-neutral-400 group-hover:text-amber-400 transition-colors mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-grow overflow-y-auto scrollbar-hide bg-slate-50/50">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection
