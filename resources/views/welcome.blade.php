@extends('layouts.app')

@section('title', 'Taxi-At-Foot - The Fastest Way to Move')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-white">
    <!-- Abstract Background Decor -->
    <div class="absolute top-0 right-0 -mr-40 -mt-40 w-[600px] h-[600px] rounded-full bg-amber-400/20 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-40 -mb-40 w-[600px] h-[600px] rounded-full bg-emerald-400/10 blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 pt-20 pb-24 lg:pt-32 lg:pb-40 flex flex-col items-center justify-center min-h-[85vh] text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-amber-200 bg-amber-50 text-amber-600 font-bold text-sm mb-8 animate-[fade-in-down_0.5s_ease-out]">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
            Now Serving Ahmedabad
        </div>

        <!-- Headline -->
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black font-heading tracking-tighter text-neutral-900 leading-tight mb-8 drop-shadow-sm">
            Moving people, <br class="hidden md:block"/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-600 inline-block animate-[fade-in-up_0.8s_ease-out_0.2s_both]">faster than ever.</span>
        </h1>

        <!-- Subheading -->
        <p class="text-lg md:text-xl text-neutral-500 font-medium max-w-2xl mx-auto mb-10 leading-relaxed animate-[fade-in-up_0.8s_ease-out_0.4s_both]">
            Book reliable rides instantly at your fingertips. Transparent pricing, professional drivers, and a seamless booking experience engineered for the modern commuter.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-center animate-[fade-in-up_0.8s_ease-out_0.6s_both]">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-amber-500 hover:bg-amber-400 text-neutral-900 font-bold text-xl rounded-2xl shadow-xl shadow-amber-400/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                    Go to Dashboard
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xl rounded-2xl shadow-xl shadow-neutral-900/20 border border-neutral-800 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                    Ride with us
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-neutral-50 border-2 border-neutral-200 text-neutral-800 font-bold text-xl rounded-2xl hover:border-neutral-300 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                    Drive and earn
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Services / Features -->
<div class="bg-neutral-50 py-24 sm:py-32 border-t border-neutral-200" id="features">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-xl font-bold text-amber-600 tracking-wider uppercase mb-2">Why Choose Us</h2>
            <p class="mt-2 text-4xl font-black font-heading text-neutral-900 tracking-tight sm:text-5xl">Built for the urban jungle</p>
        </div>

        <div class="mt-16 sm:mt-20">
            <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-3 lg:gap-y-16">
                <!-- Feature 1 -->
                <div class="relative pl-20 group">
                    <dt class="text-xl font-bold leading-7 text-neutral-900 font-heading">
                        <div class="absolute left-0 top-[-8px] flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-400 group-hover:scale-110 transition-transform shadow-lg shadow-amber-400/30 rotate-3">
                            <svg class="h-8 w-8 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        Lightning Fast
                    </dt>
                    <dd class="mt-2 text-base leading-7 text-neutral-500 font-medium">Get connected to a nearby driver in seconds. Our state-of-the-art matchmaking engine ensures you're never left waiting.</dd>
                </div>

                <!-- Feature 2 -->
                <div class="relative pl-20 group">
                    <dt class="text-xl font-bold leading-7 text-neutral-900 font-heading">
                        <div class="absolute left-0 top-[-8px] flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-400 group-hover:scale-110 transition-transform shadow-lg shadow- emerald-400/30 -rotate-3">
                            <svg class="h-8 w-8 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        Secure & Verified
                    </dt>
                    <dd class="mt-2 text-base leading-7 text-neutral-500 font-medium">Every driver completes a rigorous background check. End-to-end trip tracking keeps you completely secure.</dd>
                </div>

                <!-- Feature 3 -->
                <div class="relative pl-20 group">
                    <dt class="text-xl font-bold leading-7 text-neutral-900 font-heading">
                        <div class="absolute left-0 top-[-8px] flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-400 group-hover:scale-110 transition-transform shadow-lg shadow-indigo-400/30 rotate-3">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </div>
                        Transparent Fares
                    </dt>
                    <dd class="mt-2 text-base leading-7 text-neutral-500 font-medium">No hidden fees, no surprise surges. You know the estimated payout up front before you confirm your booking.</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Reviews -->
<div class="bg-white py-24 sm:py-32" id="reviews">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <h2 class="text-center text-4xl font-black font-heading text-neutral-900 tracking-tight sm:text-5xl mb-16">What our riders say</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Review Card -->
            <div class="bg-neutral-50 p-8 rounded-3xl border border-neutral-200 shadow-sm relative hover:-translate-y-1 transition-transform">
                <div class="flex items-center gap-1 mb-6 text-amber-500">
                    @for($i=0; $i<5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    @endfor
                </div>
                <p class="text-neutral-700 font-medium mb-8 leading-relaxed">"Absolutely the best taxi service in town. The drivers are always punctual and the cars are spotless. Highly recommend!"</p>
                <div class="flex items-center gap-4 mt-auto">
                    <div class="w-12 h-12 bg-rose-500 text-white flex items-center justify-center rounded-full font-bold text-lg">S</div>
                    <div>
                        <p class="font-bold text-neutral-900">Sarah M.</p>
                        <p class="text-sm text-neutral-500 font-medium">Daily Commuter</p>
                    </div>
                </div>
            </div>

            <div class="bg-neutral-50 p-8 rounded-3xl border border-neutral-200 shadow-sm relative hover:-translate-y-1 transition-transform">
                <div class="flex items-center gap-1 mb-6 text-amber-500">
                    @for($i=0; $i<5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    @endfor
                </div>
                <p class="text-neutral-700 font-medium mb-8 leading-relaxed">"As a driver, the app is incredibly intuitive. I know exactly where I am going, and the payout estimates are spot on."</p>
                <div class="flex items-center gap-4 mt-auto">
                    <div class="w-12 h-12 bg-indigo-500 text-white flex items-center justify-center rounded-full font-bold text-lg">R</div>
                    <div>
                        <p class="font-bold text-neutral-900">Rahul K.</p>
                        <p class="text-sm text-neutral-500 font-medium">Partner Driver</p>
                    </div>
                </div>
            </div>

            <div class="bg-neutral-50 p-8 rounded-3xl border border-neutral-200 shadow-sm relative hover:-translate-y-1 transition-transform sm:hidden lg:block">
                <div class="flex items-center gap-1 mb-6 text-amber-500">
                    @for($i=0; $i<5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    @endfor
                </div>
                <p class="text-neutral-700 font-medium mb-8 leading-relaxed">"The pricing is so transparent compared to others. The entire booking journey is just three taps. 10/10."</p>
                <div class="flex items-center gap-4 mt-auto">
                    <div class="w-12 h-12 bg-teal-500 text-white flex items-center justify-center rounded-full font-bold text-lg">E</div>
                    <div>
                        <p class="font-bold text-neutral-900">Emily R.</p>
                        <p class="text-sm text-neutral-500 font-medium">Tourist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
