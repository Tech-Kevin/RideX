@extends('layouts.app')

@section('title', 'Verify OTP - Taxi-At-Foot')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-6 lg:px-8 relative overflow-hidden bg-slate-50">
    <!-- Abstract Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-emerald-400/10 blur-[60px] pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center transform rotate-6 shadow-lg shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>

        <h2 class="text-center text-3xl font-black font-heading text-neutral-900 tracking-tight">
            Verify your identity
        </h2>
        <p class="mt-2 text-center text-sm text-neutral-500 font-medium">
            We've sent a 6-digit code to <span class="font-bold text-neutral-900">{{ session('otp_phone') ?? 'your phone' }}</span>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white py-8 px-6 shadow-xl border border-neutral-100 rounded-3xl sm:px-10">
            
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6 rounded-r-xl">
                    <div class="flex leading-relaxed">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('auth.verify.otp') }}" method="POST">
                @csrf
                <div>
                    <label for="otp" class="block text-sm font-bold text-neutral-700">Enter OTP</label>
                    <div class="mt-2 text-center">
                        <input id="otp" name="otp" type="text" autocomplete="one-time-code" required class="block w-full appearance-none rounded-xl border border-neutral-300 px-4 py-4 text-center text-3xl tracking-[0.5em] text-neutral-900 placeholder-neutral-300 focus:border-neutral-900 focus:outline-none focus:ring-2 focus:ring-neutral-900 font-bold transition-all" placeholder="000000" maxlength="6">
                    </div>
                    @error('otp')
                        <p class="mt-2 text-sm text-center text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-emerald-500/20 text-lg font-bold text-white bg-emerald-500 hover:bg-emerald-400 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all active:scale-[0.98]">
                        Verify & Login
                    </button>
                </div>
            </form>

            <div class="mt-6 flex justify-between items-center px-2">
                 <a href="{{ route('auth.phone.form') }}" class="text-sm font-bold text-neutral-500 hover:text-neutral-900 transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Change Number
                 </a>

                 <form method="POST" action="{{ route('auth.send.otp') }}" class="inline">
                    @csrf
                    <input type="hidden" name="phone" value="{{ session('otp_phone') }}">
                    <button type="submit" class="text-sm font-bold text-amber-600 hover:text-amber-500 transition-colors">
                        Resend Code
                    </button>
                 </form>
            </div>
        </div>
    </div>
</div>
@endsection