<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Taxi-At-Foot'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:500,600,700,800" rel="stylesheet" />

    <!-- Leaflet/Other Styles -->
    @yield('styles')

    <!-- Scripts (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-slate-50 text-neutral-900 antialiased min-h-screen flex flex-col selection:bg-amber-300 selection:text-neutral-900">
    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-neutral-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group transition-transform hover:scale-105 active:scale-95 duration-200">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-400 rounded-2xl flex items-center justify-center transform rotate-3 group-hover:rotate-6 transition-all shadow-md overflow-hidden relative">
                            <!-- Shine effect -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/40 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-neutral-900" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-xl sm:text-2xl font-black font-heading tracking-tight text-neutral-900">Taxi-At-Foot</span>
                    </a>
                </div>

                <!-- Navigation Links & Profile -->
                <div class="flex items-center gap-2 sm:gap-6">
                    @auth
                        @php $user = Auth::user(); @endphp
                        
                        <div class="hidden md:flex items-center gap-1 mr-4">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-neutral-600 font-semibold hover:bg-neutral-100 hover:text-neutral-900 transition-colors">Dashboard</a>
                            
                            @if ($user->isCustomer())
                                <a href="{{ route('customer.rides.create') }}" class="px-4 py-2 rounded-xl text-amber-600 font-bold hover:bg-amber-50 hover:text-amber-700 transition-colors">Book Ride</a>
                                <a href="{{ route('customer.rides.index') }}" class="px-4 py-2 rounded-xl text-neutral-600 font-semibold hover:bg-neutral-100 hover:text-neutral-900 transition-colors">My Rides</a>
                            @elseif ($user->isDriver())
                                <a href="{{ route('driver.rides.available') }}" class="px-4 py-2 rounded-xl text-emerald-600 font-bold hover:bg-emerald-50 hover:text-emerald-700 transition-colors">Available</a>
                                <a href="{{ route('driver.rides.my') }}" class="px-4 py-2 rounded-xl text-neutral-600 font-semibold hover:bg-neutral-100 hover:text-neutral-900 transition-colors">My Jobs</a>
                            @endif
                        </div>

                        <!-- User Profile Dropdown / Info -->
                        <div class="flex items-center gap-3 pl-4 border-l border-neutral-200 relative group cursor-pointer lg:pr-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-neutral-900 leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] font-black tracking-widest uppercase {{ $user->isDriver() ? 'text-emerald-600' : 'text-amber-500' }}">
                                    {{ $user->role }}
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-neutral-100 border border-neutral-200 flex items-center justify-center text-neutral-700 font-bold uppercase shadow-sm">
                                {{ substr($user->name, 0, 1) }}
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-neutral-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all transform origin-top translate-y-2 group-hover:translate-y-0 py-2">
                                <a href="{{ route('dashboard') }}" class="block md:hidden px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 font-medium">Dashboard</a>
                                
                                @if ($user->isCustomer())
                                    <a href="{{ route('customer.rides.create') }}" class="block md:hidden px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 font-medium whitespace-nowrap">Book Ride</a>
                                    <a href="{{ route('customer.rides.index') }}" class="block md:hidden px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 font-medium whitespace-nowrap">My Rides</a>
                                @elseif ($user->isDriver())
                                    <a href="{{ route('driver.rides.available') }}" class="block md:hidden px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 font-medium whitespace-nowrap">Available Rides</a>
                                    <a href="{{ route('driver.rides.my') }}" class="block md:hidden px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 font-medium whitespace-nowrap">My Jobs</a>
                                @endif
                                
                                <div class="h-px bg-neutral-100 my-1 md:hidden"></div>

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 font-bold hover:bg-rose-50 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Links -->
                        <div class="flex items-center gap-2 sm:gap-4">
                            <a href="{{ route('login') }}" class="px-4 sm:px-5 py-2 sm:py-2.5 text-neutral-600 font-bold hover:text-neutral-900 transition-colors text-sm sm:text-base">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-4 sm:px-6 py-2 sm:py-2.5 bg-neutral-900 hover:bg-neutral-800 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 transform text-sm sm:text-base whitespace-nowrap border border-neutral-800">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow flex flex-col relative w-full overflow-hidden">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="bg-white border-t border-neutral-200 mt-auto py-8 lg:py-12 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-1 bg-amber-400 rounded-t-full"></div>
        
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center">
            <div class="flex items-center gap-2 mb-6 grayscale opacity-60">
                <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-900" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
                </div>
                <span class="text-xl font-bold font-heading tracking-tight text-neutral-900">Taxi-At-Foot</span>
            </div>
            
            <p class="text-center text-neutral-500 font-medium text-sm">
                &copy; {{ date('Y') }} Taxi-At-Foot, Inc. All rights reserved.
            </p>
            <div class="flex gap-4 mt-6">
                <a href="#" class="text-neutral-400 hover:text-neutral-900 transition-colors">Privacy Policy</a>
                <span class="text-neutral-300">&bull;</span>
                <a href="#" class="text-neutral-400 hover:text-neutral-900 transition-colors">Terms of Service</a>
                <span class="text-neutral-300">&bull;</span>
                <a href="#" class="text-neutral-400 hover:text-neutral-900 transition-colors">Contact Us</a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @yield('scripts')
</body>
</html>
