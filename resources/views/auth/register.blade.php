@extends('layouts.app')

@section('title', 'Register - Taxi-At-Foot')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-6 lg:px-8 relative overflow-hidden bg-slate-50">
    <!-- Abstract Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-amber-400/10 blur-[60px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-emerald-400/10 blur-[60px] pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-amber-400 rounded-2xl flex items-center justify-center transform rotate-6 shadow-lg shadow-amber-400/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-neutral-900" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <h2 class="text-center text-3xl font-black font-heading text-neutral-900 tracking-tight">
            Create an account
        </h2>
        <p class="mt-2 text-center text-sm text-neutral-500 font-medium">
            Join Taxi-At-Foot and start riding today
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white py-8 px-6 shadow-xl border border-neutral-100 rounded-3xl sm:px-10">
            @if (session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('register.submit') }}" method="POST">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-bold text-neutral-700 mb-2">I want to...</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="customer" class="peer sr-only" checked>
                            <div class="rounded-xl border-2 border-neutral-200 bg-white p-4 text-center hover:bg-neutral-50 peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-700 transition-all font-bold group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-neutral-400 group-hover:text-amber-500 peer-checked:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Ride with us
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="driver" class="peer sr-only">
                            <div class="rounded-xl border-2 border-neutral-200 bg-white p-4 text-center hover:bg-neutral-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all font-bold group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-neutral-400 group-hover:text-emerald-500 peer-checked:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                Drive for us
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-bold text-neutral-700">Full Name</label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" autocomplete="name" required class="block w-full appearance-none rounded-xl border border-neutral-300 px-4 py-3 text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 sm:text-sm font-medium transition-colors" placeholder="John Doe">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-neutral-700">Email Address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full appearance-none rounded-xl border border-neutral-300 px-4 py-3 text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 sm:text-sm font-medium transition-colors" placeholder="john@example.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-bold text-neutral-700">Phone Number</label>
                    <div class="mt-2 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-neutral-500 sm:text-sm font-bold">+91</span>
                        </div>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" required class="block w-full appearance-none rounded-xl border border-neutral-300 pl-14 pr-4 py-3 text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 sm:text-sm font-medium transition-colors" placeholder="9876543210">
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-neutral-700">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="new-password" required class="block w-full appearance-none rounded-xl border border-neutral-300 px-4 py-3 text-neutral-900 placeholder-neutral-400 focus:border-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 sm:text-sm font-medium transition-colors" placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-neutral-900/10 text-base font-bold text-white bg-neutral-900 hover:bg-neutral-800 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 transition-all active:scale-[0.98]">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-neutral-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white text-neutral-500 font-medium">Already have an account?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" class="w-full flex justify-center py-3.5 px-4 border-2 border-neutral-200 rounded-xl shadow-sm bg-white text-base font-bold text-neutral-700 hover:bg-neutral-50 hover:border-neutral-300 focus:outline-none transition-all active:scale-[0.98]">
                        Log in instead
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
