@extends('layouts.app')

@section('title', 'RideX - The Fastest Way to Move')

@section('content')
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <!-- Abstract Background Decor -->
        <div
            class="absolute top-0 right-0 -mr-40 -mt-40 w-[600px] h-[600px] rounded-full bg-amber-400/20 blur-[100px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-40 -mb-40 w-[600px] h-[600px] rounded-full bg-emerald-400/10 blur-[100px] pointer-events-none">
        </div>

        <div
            class="max-w-7xl mx-auto px-6 relative z-10 pt-20 pb-24 lg:pt-32 lg:pb-40 flex flex-col lg:flex-row items-center justify-between min-h-[85vh] gap-12 lg:gap-8">

            <!-- Text Content -->
            <div class="flex-1 text-center lg:text-left pt-10 lg:pt-0">
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-amber-200 bg-amber-50 text-amber-600 font-bold text-sm mb-8 animate-[fade-in-down_0.5s_ease-out]">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    Now Serving Ahmedabad
                </div>

                <!-- Headline -->
                <h1
                    class="text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black font-heading tracking-tighter text-neutral-900 leading-[1.1] mb-8 drop-shadow-sm">
                    Moving people, <br />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-600 inline-block animate-[fade-in-up_0.8s_ease-out_0.2s_both]">faster
                        than ever.</span>
                </h1>

                <!-- Subheading -->
                <p
                    class="text-lg md:text-xl text-neutral-500 font-medium max-w-xl mx-auto lg:mx-0 mb-10 leading-relaxed animate-[fade-in-up_0.8s_ease-out_0.4s_both]">
                    Book reliable rides instantly at your fingertips. Transparent pricing, professional drivers, and a
                    seamless booking experience engineered for the modern commuter.
                </p>

                <!-- CTA Buttons -->
                <div
                    class="flex flex-col sm:flex-row gap-4 items-center justify-center lg:justify-start animate-[fade-in-up_0.8s_ease-out_0.6s_both]">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-amber-500 hover:bg-amber-400 text-neutral-900 font-bold text-xl rounded-2xl shadow-xl shadow-amber-400/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                            Go to Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xl rounded-2xl shadow-xl shadow-neutral-900/20 border border-neutral-800 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                            Ride with us
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="{{ route('register') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-neutral-50 border-2 border-neutral-200 text-neutral-800 font-bold text-xl rounded-2xl hover:border-neutral-300 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                            Drive and earn
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Image Content -->
            <div class="flex-1 w-full max-w-lg lg:max-w-none relative animate-[fade-in-up_1s_ease-out_0.4s_both]">
                <!-- Decorative Backing -->
                <div
                    class="absolute inset-0 bg-gradient-to-tr from-amber-400 to-emerald-400 rounded-[3rem] blur-2xl opacity-20 transform rotate-3 scale-105">
                </div>
                <!-- Image Container -->
                <div
                    class="relative rounded-[2.5rem] overflow-hidden border-[6px] border-white shadow-2xl bg-white transform -rotate-1 hover:rotate-0 transition-transform duration-500">
                    <img src="{{ asset('images/home_hero.png') }}" alt="Happy customer taking a ride"
                        class="w-full h-auto object-cover aspect-[4/3] lg:aspect-[4/3]">
                </div>

                <!-- Floating Badge -->
                
                <!-- Floating Elements Decor -->
            </div>
        </div>
    </div>
    </div>

    <!-- Trust Metrics -->
    <div class="bg-neutral-900 py-8 border-y border-neutral-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-black font-heading text-white">10K+</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.2em] text-neutral-400 mt-2">Rides
                        Completed</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-black font-heading text-white">500+</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.2em] text-neutral-400 mt-2">Active Drivers
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-black font-heading text-white">24/7</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.2em] text-neutral-400 mt-2">Support</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-black font-heading text-white">99%</p>
                    <p class="text-xs md:text-sm font-black uppercase tracking-[0.2em] text-neutral-400 mt-2">Ride
                        Reliability</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Options -->
    <!-- Vehicle Options -->
    <div class="bg-white py-24 sm:py-28 border-t border-neutral-200" id="services">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-xl font-bold text-amber-600 tracking-wider uppercase mb-2">Ride Options</h2>
                <p class="text-4xl sm:text-5xl font-black font-heading tracking-tight text-neutral-900">
                    Choose the right ride for every trip
                </p>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-neutral-500 font-medium leading-relaxed">
                    Built for daily commuters, quick solo travel, and comfortable city journeys with transparent pricing and
                    dependable availability.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Bike -->
                <div
                    class="group relative overflow-hidden rounded-[2.5rem] border border-neutral-200 bg-white p-8 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-amber-500/10 hover:border-amber-200">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
                    <div class="mb-8 flex items-center justify-between">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-amber-50 text-amber-600 transition-all duration-500 group-hover:bg-amber-500 group-hover:text-white">
                            <img src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Uber_Moto_India1.png"
                                alt="Bike Ride" class="h-10 w-auto object-contain">
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.18em] text-neutral-300">01</span>
                    </div>

                    <h3 class="text-3xl font-black font-heading text-neutral-900 mb-3">Bike</h3>
                    <p class="text-neutral-500 font-medium leading-relaxed mb-6">
                        The fastest option for solo commuters who want to move through traffic efficiently and reach their
                        destination sooner.
                    </p>
                    <div
                        class="pt-5 border-t border-neutral-100 text-xs font-black uppercase tracking-[0.18em] text-neutral-400">
                        Best for short-distance urgent travel
                    </div>
                </div>

                <!-- Auto -->
                <div
                    class="group relative overflow-hidden rounded-[2.5rem] border border-neutral-200 bg-white p-8 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-500/10 hover:border-emerald-200">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-500"></div>
                    <div class="mb-8 flex items-center justify-between">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-emerald-50 text-emerald-600 transition-all duration-500 group-hover:bg-emerald-500 group-hover:text-white">
                            <img src="data:image/jpeg;base64,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"
                                alt="Auto Ride" class="h-10 w-auto object-contain">
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.18em] text-neutral-300">02</span>
                    </div>

                    <h3 class="text-3xl font-black font-heading text-neutral-900 mb-3">Auto</h3>
                    <p class="text-neutral-500 font-medium leading-relaxed mb-6">
                        Affordable, practical, and ideal for everyday city travel with balanced comfort, convenience, and
                        value.
                    </p>
                    <div
                        class="pt-5 border-t border-neutral-100 text-xs font-black uppercase tracking-[0.18em] text-neutral-400">
                        Best for daily city commutes
                    </div>
                </div>

                <!-- Cab -->
                <div
                    class="group relative overflow-hidden rounded-[2.5rem] border border-neutral-200 bg-white p-8 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-indigo-200">
                    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-400 to-indigo-500"></div>
                    <div class="mb-8 flex items-center justify-between">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-[1.5rem] bg-indigo-50 text-indigo-600 transition-all duration-500 group-hover:bg-indigo-500 group-hover:text-white">
                            <img src="data:image/jpeg;base64,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"
                                alt="Auto Ride" class="h-10 w-auto object-contain">
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.18em] text-neutral-300">03</span>
                    </div>

                    <h3 class="text-3xl font-black font-heading text-neutral-900 mb-3">Cab</h3>
                    <p class="text-neutral-500 font-medium leading-relaxed mb-6">
                        Comfortable travel for families, office commutes, airport rides, and longer routes where convenience
                        matters most.
                    </p>
                    <div
                        class="pt-5 border-t border-neutral-100 text-xs font-black uppercase tracking-[0.18em] text-neutral-400">
                        Best for comfort and longer trips
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="bg-neutral-50 py-24 sm:py-28 border-y border-neutral-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-xl font-bold text-amber-600 tracking-wider uppercase mb-2">How It Works</h2>
                <p class="text-4xl sm:text-5xl font-black font-heading tracking-tight text-neutral-900">From booking to
                    drop-off in minutes</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative bg-white rounded-[2.5rem] p-8 border border-neutral-100 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-2xl bg-neutral-900 text-white flex items-center justify-center font-black text-xl mb-6">
                        1</div>
                    <h3 class="text-2xl font-black font-heading text-neutral-900 mb-3">Enter your destination</h3>
                    <p class="text-neutral-500 font-medium leading-relaxed">
                        Choose your pickup point, destination, and preferred ride type in just a few taps.
                    </p>
                </div>

                <div class="relative bg-white rounded-[2.5rem] p-8 border border-neutral-100 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-2xl bg-amber-500 text-neutral-900 flex items-center justify-center font-black text-xl mb-6">
                        2</div>
                    <h3 class="text-2xl font-black font-heading text-neutral-900 mb-3">Get matched instantly</h3>
                    <p class="text-neutral-500 font-medium leading-relaxed">
                        Our platform connects you with a nearby verified driver for the fastest possible pickup.
                    </p>
                </div>

                <div class="relative bg-white rounded-[2.5rem] p-8 border border-neutral-100 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-xl mb-6">
                        3</div>
                    <h3 class="text-2xl font-black font-heading text-neutral-900 mb-3">Ride with confidence</h3>
                    <p class="text-neutral-500 font-medium leading-relaxed">
                        Track your trip, view fare details clearly, and arrive safely with professional drivers.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services / Features -->
    <div class="bg-neutral-50 py-24 sm:py-32 border-t border-neutral-200" id="features">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-xl font-bold text-amber-600 tracking-wider uppercase mb-2">Why Choose Us</h2>
                <p class="mt-2 text-4xl font-black font-heading text-neutral-900 tracking-tight sm:text-5xl">Built for the
                    urban jungle</p>
            </div>

            <div class="mt-16 sm:mt-20">
                <div class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-12 lg:max-w-none lg:grid-cols-3 lg:gap-y-16">
                    <!-- Feature 1 -->
                    <div class="flex flex-col group cursor-pointer pt-6">
                        <div
                            class="relative w-full h-64 sm:h-72 rounded-3xl overflow-hidden mb-8 shadow-xl border-4 border-white transform transition-transform duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl">
                            <img src="{{ asset('images/fast_ride.png') }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                alt="Fast ride with premium vehicle">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <div
                                class="absolute -top-6 -right-6 w-24 h-24 bg-amber-400 rounded-full blur-2xl opacity-20 group-hover:opacity-40 transition-opacity">
                            </div>
                        </div>
                        <dt
                            class="text-2xl font-black leading-tight text-neutral-900 font-heading mb-3 transition-colors group-hover:text-amber-500">
                            Lightning Fast
                        </dt>
                        <dd class="text-lg leading-relaxed text-neutral-500 font-medium">Get connected to a nearby driver in
                            seconds. Our state-of-the-art matchmaking engine ensures you're never left waiting.</dd>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col group cursor-pointer lg:mt-12">
                        <div
                            class="relative w-full h-64 sm:h-72 rounded-3xl overflow-hidden mb-8 shadow-xl border-4 border-white transform transition-transform duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl">
                            <img src="{{ asset('images/secure_ride.png') }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                alt="Secure and verified professional driver">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <div
                                class="absolute -bottom-6 -left-6 w-24 h-24 bg-emerald-400 rounded-full blur-2xl opacity-20 group-hover:opacity-40 transition-opacity">
                            </div>
                        </div>
                        <dt
                            class="text-2xl font-black leading-tight text-neutral-900 font-heading mb-3 transition-colors group-hover:text-emerald-500">
                            Secure & Verified
                        </dt>
                        <dd class="text-lg leading-relaxed text-neutral-500 font-medium">Every driver completes a rigorous
                            background check. End-to-end trip tracking keeps you completely secure.</dd>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col group cursor-pointer pt-6">
                        <div
                            class="relative w-full h-64 sm:h-72 rounded-3xl overflow-hidden mb-8 shadow-xl border-4 border-white transform transition-transform duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl">
                            <img src="{{ asset('images/transparent_fare.png') }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                alt="Transparent fare pricing on smartphone app">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <div
                                class="absolute -top-6 -left-6 w-24 h-24 bg-indigo-400 rounded-full blur-2xl opacity-20 group-hover:opacity-40 transition-opacity">
                            </div>
                        </div>
                        <dt
                            class="text-2xl font-black leading-tight text-neutral-900 font-heading mb-3 transition-colors group-hover:text-indigo-500">
                            Transparent Fares
                        </dt>
                        <dd class="text-lg leading-relaxed text-neutral-500 font-medium">No hidden fees, no surprise surges.
                            You know the estimated payout up front before you confirm your booking.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white py-24 sm:py-32 overflow-hidden flex flex-col items-center" id="reviews">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 mb-16 text-center">
            <h2 class="text-xl font-bold text-amber-600 tracking-wider uppercase mb-2">Wall of Love</h2>
            <p class="text-4xl lg:text-5xl font-black font-heading text-neutral-900 tracking-tight">What our riders say</p>
            <p class="text-neutral-500 font-medium mt-4 max-w-2xl mx-auto text-lg leading-relaxed">Don't just take our word
                for it—hear from the thousands of people who use RideX every day.</p>
        </div>

        <div class="w-full flex flex-col gap-6 sm:gap-8">
            <!-- Row 1: Left to Right -->
            <div class="relative flex overflow-x-hidden w-full group py-4">
                <div
                    class="absolute top-0 left-0 h-full w-16 sm:w-48 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 right-0 h-full w-16 sm:w-48 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none">
                </div>

                <div class="flex animate-scroll gap-6 sm:gap-8 px-4">
                    @php
                        $reviewsRow1 = [
                            ['name' => 'Sarah M.', 'role' => 'Daily Commuter', 'color' => 'bg-rose-500', 'initial' => 'S', 'text' => "Absolutely the best taxi service in town. The drivers are always punctual and the cars are spotless. Highly recommend!"],
                            ['name' => 'Rahul K.', 'role' => 'Partner Driver', 'color' => 'bg-indigo-500', 'initial' => 'R', 'text' => "As a driver, the app is incredibly intuitive. I know exactly where I am going, and the payout estimates are spot on."],
                            ['name' => 'Emily R.', 'role' => 'Tourist', 'color' => 'bg-teal-500', 'initial' => 'E', 'text' => "The pricing is so transparent compared to others. The entire booking journey is just three taps. 10/10."],
                            ['name' => 'James D.', 'role' => 'Business Traveler', 'color' => 'bg-amber-500', 'initial' => 'J', 'text' => "I love the new design and seamless booking. It’s way faster than anything I've used before."],
                        ];
                    @endphp

                    <div class="flex gap-6 sm:gap-8 min-w-max">
                        @foreach(array_merge($reviewsRow1, $reviewsRow1) as $review)
                            <div
                                class="bg-neutral-50 p-8 sm:p-10 rounded-[2.5rem] border border-neutral-100 shadow-sm relative hover:-translate-y-2 hover:shadow-xl hover:border-neutral-200 transition-all duration-300 w-[320px] sm:w-[380px] flex flex-col text-left group/card">
                                <div class="flex items-center gap-1 mb-6 text-amber-500">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-neutral-700 font-medium mb-8 leading-relaxed text-lg italic flex-grow">
                                    "{{ $review['text'] }}"</p>
                                <div class="flex items-center gap-4 mt-auto">
                                    <div
                                        class="w-12 h-12 {{ $review['color'] }} text-white flex items-center justify-center rounded-2xl font-black text-xl shrink-0 rotate-3 group-hover/card:rotate-6 transition-transform shadow-md shadow-neutral-900/10">
                                        {{ $review['initial'] }}
                                    </div>
                                    <div>
                                        <p class="font-black text-neutral-900 text-lg leading-tight">{{ $review['name'] }}</p>
                                        <p class="text-xs text-neutral-400 font-black uppercase tracking-widest mt-0.5">
                                            {{ $review['role'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Row 2: Right to Left -->
            <div class="relative flex overflow-x-hidden w-full group py-4">
                <div
                    class="absolute top-0 left-0 h-full w-16 sm:w-48 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 right-0 h-full w-16 sm:w-48 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none">
                </div>

                <div class="flex animate-scroll-reverse gap-6 sm:gap-8 px-4">
                    @php
                        $reviewsRow2 = [
                            ['name' => 'Priya S.', 'role' => 'Student', 'color' => 'bg-purple-500', 'initial' => 'P', 'text' => "Top-level safety features! I feel completely secure sharing my live location with friends."],
                            ['name' => 'Michael B.', 'role' => 'Frequent Rider', 'color' => 'bg-emerald-500', 'initial' => 'M', 'text' => "The driver arrived exactly when the app said he would. Outstanding reliability!"],
                            ['name' => 'Daniel L.', 'role' => 'Tech Lead', 'color' => 'bg-blue-600', 'initial' => 'D', 'text' => "The most streamlined ride software out there. Minimal friction, Maximum speed."],
                            ['name' => 'Anita G.', 'role' => 'Working Mom', 'color' => 'bg-rose-400', 'initial' => 'A', 'text' => "I trust them with my kids' commute. The drivers are professional and well-behaved."],
                        ];
                    @endphp

                    <div class="flex gap-6 sm:gap-8 min-w-max">
                        @foreach(array_merge($reviewsRow2, $reviewsRow2) as $review)
                            <div
                                class="bg-neutral-50 p-8 sm:p-10 rounded-[2.5rem] border border-neutral-100 shadow-sm relative hover:-translate-y-2 hover:shadow-xl hover:border-neutral-200 transition-all duration-300 w-[320px] sm:w-[380px] flex flex-col text-left group/card">
                                <div class="flex items-center gap-1 mb-6 text-emerald-500">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-neutral-700 font-medium mb-8 leading-relaxed text-lg italic flex-grow">
                                    "{{ $review['text'] }}"</p>
                                <div class="flex items-center gap-4 mt-auto">
                                    <div
                                        class="w-12 h-12 {{ $review['color'] }} text-white flex items-center justify-center rounded-2xl font-black text-xl shrink-0 -rotate-3 group-hover/card:-rotate-6 transition-transform shadow-md shadow-neutral-900/10">
                                        {{ $review['initial'] }}
                                    </div>
                                    <div>
                                        <p class="font-black text-neutral-900 text-lg leading-tight">{{ $review['name'] }}</p>
                                        <p class="text-xs text-neutral-400 font-black uppercase tracking-widest mt-0.5">
                                            {{ $review['role'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="bg-neutral-900 py-24 sm:py-28 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/10 via-transparent to-emerald-400/10"></div>
        <div class="relative max-w-5xl mx-auto px-6 text-center">
            <h2 class="text-4xl sm:text-5xl font-black font-heading text-white tracking-tight mb-6">
                Ready to move with RideX?
            </h2>
            <p class="max-w-2xl mx-auto text-lg text-neutral-300 font-medium leading-relaxed mb-10">
                Join thousands of riders choosing faster booking, transparent fares, and dependable drivers every day.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-8 py-4 bg-amber-500 hover:bg-amber-400 text-neutral-900 font-bold rounded-2xl transition-all hover:-translate-y-1">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-amber-500 hover:bg-amber-400 text-neutral-900 font-bold rounded-2xl transition-all hover:-translate-y-1">
                        Book a Ride
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-8 py-4 border border-white/20 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl transition-all hover:-translate-y-1">
                        Become a Driver
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection