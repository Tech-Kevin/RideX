@extends('layouts.app')

@section('title', 'Verify Email - Taxi-At-Foot')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-6 lg:px-8 relative overflow-hidden bg-slate-50">
    <!-- Abstract Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-amber-400/10 blur-[60px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-emerald-400/10 blur-[60px] pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center transform -rotate-6 shadow-lg shadow-blue-500/20 border border-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <h2 class="text-center text-3xl font-black font-heading text-neutral-900 tracking-tight">
            Check your email
        </h2>
        <p class="mt-3 text-center text-base text-neutral-500 font-medium px-4">
            We've sent a verification link to your email address. Please click the link to verify your account and gain full access.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="bg-white py-8 px-6 shadow-xl border border-neutral-100 rounded-3xl sm:px-10 text-center">
            @if (session('success'))
                <div class="bg-emerald-50 border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 text-sm font-bold flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-sm font-medium text-neutral-600 mb-6">
                Didn't receive the email? Check your spam folder or request a new link below.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border-2 border-neutral-200 rounded-xl shadow-sm text-base font-bold text-neutral-700 bg-white hover:bg-neutral-50 hover:border-neutral-300 focus:outline-none transition-all active:scale-[0.98]">
                    Resend Verification Email
                </button>
            </form>
            
            <div class="mt-6 pt-6 border-t border-neutral-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-rose-500 hover:text-rose-600 transition-colors">
                        Log out and try a different account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
