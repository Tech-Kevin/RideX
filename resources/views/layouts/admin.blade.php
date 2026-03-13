<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - RideX')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; letter-spacing: -0.02em; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #fbbf24; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#0f172a] text-neutral-900 antialiased">

<div class="flex h-screen w-full overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- ═══════════════════════ SIDEBAR ═══════════════════════ -->
    <aside class="bg-[#111827] border-r border-white/5 flex-shrink-0 flex flex-col transition-all duration-300 z-50"
           :class="sidebarOpen ? 'w-[240px]' : 'w-[70px]'" style="overflow-y: auto; overflow-x: hidden;">

        <!-- Brand Logo -->
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/5">
            <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center transform rotate-3 shadow-lg shadow-amber-400/20 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-900" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                </svg>
            </div>
            <span class="text-lg font-black font-heading text-white tracking-tight transition-all" x-show="sidebarOpen" x-cloak>
                RideX <span class="text-amber-400 text-[10px] align-top ml-0.5 font-bold uppercase tracking-widest">Admin</span>
            </span>
        </div>

        <!-- Admin Profile -->
        <div class="border-b border-white/5 transition-all" :class="sidebarOpen ? 'p-5' : 'py-4 px-3'">
            <div class="flex items-center gap-3" :class="sidebarOpen ? '' : 'justify-center'">
                <div class="relative flex-shrink-0">
                    <div class="w-9 h-9 rounded-xl overflow-hidden border-2 border-amber-400/30">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1f2937&color=fbbf24&size=128"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#111827] rounded-full"></div>
                </div>
                <div x-show="sidebarOpen" x-cloak>
                    <p class="text-white font-bold text-sm leading-none mb-1">{{ auth()->user()->name }}</p>
                    <p class="text-emerald-400 text-[9px] font-black uppercase tracking-widest">● Online</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-grow py-5 scrollbar-hide">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-neutral-600 mb-3 transition-all"
               :class="sidebarOpen ? 'px-6' : 'hidden'" x-show="sidebarOpen" x-cloak>Main Menu</p>

            <nav class="space-y-0.5 px-3">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',    'label' => 'Dashboard',    'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['route' => 'admin.operations',   'label' => 'Operations',   'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'live' => true],
                        ['route' => 'admin.users',         'label' => 'Users',        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['route' => 'admin.verifications', 'label' => 'Verifications','icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['route' => 'admin.rates',         'label' => 'Vehicle Rates','icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'admin.rides',         'label' => 'Ride History', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                        ['separator' => true, 'label' => 'Micro Settings'],
                        ['route' => 'admin.surge-rules.index', 'label' => 'Surge Pricing', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @if(isset($item['separator']))
                        <div x-show="sidebarOpen" x-cloak class="pt-5 pb-1 px-4">
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-neutral-600">{{ $item['label'] }}</p>
                        </div>
                    @else
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all duration-200 group"
                       :class="sidebarOpen ? 'px-4' : 'justify-center px-2'"
                       title="{{ $item['label'] }}">
                        <div class="relative w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center transition-all duration-200
                            {{ $active ? 'bg-amber-400 text-neutral-900 shadow-lg shadow-amber-400/30' : 'bg-white/5 text-neutral-400 group-hover:bg-amber-400/10 group-hover:text-amber-400' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            @if(isset($item['live']) && $item['live'])
                                <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-emerald-400 rounded-full border border-[#111827] animate-pulse"></span>
                            @endif
                        </div>
                        <span class="text-xs tracking-tight transition-colors duration-200 whitespace-nowrap"
                              x-show="sidebarOpen" x-cloak
                              style="{{ $active ? 'color: white;' : '' }}"
                              :class="{{ $active ? '\'text-white\'' : '\'text-neutral-400 group-hover:text-amber-400\'' }}">
                            {{ $item['label'] }}
                        </span>
                    </a>
                    @endif
                @endforeach
            </nav>
        </div>

        <!-- Sidebar Footer: Logout -->
        <div class="p-3 border-t border-white/5 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 py-2.5 rounded-xl font-bold transition-all duration-200 group text-left"
                        :class="sidebarOpen ? 'px-4' : 'justify-center px-2'"
                        title="Logout">
                    <div class="w-8 h-8 flex-shrink-0 rounded-lg flex items-center justify-center bg-white/5 text-neutral-400 group-hover:bg-rose-500/10 group-hover:text-rose-400 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <span class="text-xs tracking-tight text-neutral-400 group-hover:text-rose-400 transition-colors whitespace-nowrap"
                          x-show="sidebarOpen" x-cloak>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ═══════════════════════ MAIN AREA ═══════════════════════ -->
    <div class="flex-grow flex flex-col min-w-0 overflow-hidden">

        <!-- Top Header Bar -->
        <header class="h-14 bg-[#1e293b] border-b border-white/5 flex items-center justify-between px-5 flex-shrink-0 z-40">
            <div class="flex items-center gap-4">
                <!-- Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen"
                        class="w-8 h-8 rounded-lg bg-white/5 hover:bg-amber-400/10 text-neutral-400 hover:text-amber-400 flex items-center justify-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Breadcrumb -->
                <div class="hidden sm:flex items-center gap-2 text-[11px] font-bold">
                    <span class="text-neutral-500">Admin</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-amber-400 uppercase tracking-widest">@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Notification Bell -->
                <button class="relative w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-neutral-400 hover:text-white flex items-center justify-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-2 px-2 py-1 rounded-lg bg-white/5 hover:bg-white/10 border border-white/5 transition-all">
                        <div class="w-6 h-6 rounded-md overflow-hidden flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1f2937&color=fbbf24&size=64"
                                 class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs font-bold text-neutral-300 hidden sm:block max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-neutral-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute right-0 top-full mt-2 w-48 bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl py-2 z-50">
                        <a href="{{ route('profile') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-neutral-300 hover:text-white hover:bg-white/5 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Profile
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-neutral-300 hover:text-white hover:bg-white/5 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Main Site
                        </a>
                        <div class="border-t border-white/5 mx-3 my-1.5"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-400 hover:text-rose-300 hover:bg-rose-500/5 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow overflow-y-auto bg-slate-50">
            @yield('admin-content')
        </main>
    </div>
</div>

@yield('scripts')
</body>
</html>
