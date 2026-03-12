@extends('layouts.admin')

@section('title', 'Verifications - RideX Admin')
@section('breadcrumb', 'Verifications')

@section('admin-content')
<div class="px-6 py-8 md:py-12 w-full">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black font-heading text-neutral-900 mb-2 tracking-tight">Pending Verifications</h1>
            <p class="text-neutral-500 text-base font-medium">Review and approve user profiles and driver documents.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-5 py-2.5 bg-amber-100 text-amber-700 rounded-2xl font-black text-sm border border-amber-200 shadow-sm flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                {{ $users->total() }} Pending Requests
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold flex items-center gap-3 animate-[fade-in-down_0.3s_ease-out]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            {{ session('success') }}
        </div>
    @endif
    <div class="space-y-6">
        @forelse($users as $user)
            <div class="bg-white border border-neutral-200 rounded-[2.5rem] p-6 md:p-8 shadow-xl shadow-neutral-100/50 hover:shadow-neutral-100 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/5 rounded-full blur-[80px] -mt-32 -mr-32 pointer-events-none"></div>
                
                <div class="flex flex-col lg:flex-row gap-8 items-start lg:items-center relative">
                    <!-- User Primary Info -->
                    <div class="flex items-center gap-6 min-w-[300px]">
                        <div class="w-20 h-20 rounded-[1.5rem] bg-neutral-100 border border-neutral-200 overflow-hidden shrink-0 shadow-sm">
                            <img src="{{ $user->getFirstMediaUrl('avatar') ?: 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f5f5f5&color=171717' }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-neutral-900 mb-1 leading-tight">{{ $user->name }}</h3>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-3 py-1 bg-neutral-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $user->role }}</span>
                                <span class="text-xs font-bold text-neutral-400">• Joined {{ $user->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm font-bold text-neutral-500 mt-2 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                {{ $user->phone }}
                            </p>
                        </div>
                    </div>

                    <!-- Verification Details -->
                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
                        @if($user->isDriver())
                            <div class="p-4 bg-neutral-50 rounded-2xl border border-neutral-100 flex flex-col justify-center">
                                <span class="text-[10px] font-black text-neutral-400 uppercase tracking-widest mb-1">Vehicle Details</span>
                                <p class="text-sm font-black text-neutral-900">{{ $user->vehicle_type?->label() ?? 'Not Set' }}</p>
                                <p class="text-[11px] font-bold text-neutral-500 uppercase">{{ $user->vehicle_number ?? 'No Number' }}</p>
                            </div>
                        @endif
                        
                        <!-- Document Thumbnails -->
                        <div class="lg:col-span-2 flex flex-wrap gap-4 items-center">
                            @foreach(['rc_book', 'driving_licence', 'vehicle_photo'] as $collection)
                                @if($user->hasMedia($collection))
                                    <button onclick="openImageModal('{{ $user->getFirstMediaUrl($collection) }}', '{{ ucwords(str_replace('_', ' ', $collection)) }}')" class="group/img relative">
                                        <div class="w-20 h-20 rounded-xl bg-neutral-100 border border-neutral-200 overflow-hidden shadow-sm hover:scale-105 transition-transform cursor-zoom-in">
                                            <img src="{{ $user->getFirstMediaUrl($collection) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-neutral-900/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                            </div>
                                        </div>
                                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center bg-emerald-500 rounded-full border-2 border-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 w-full lg:w-auto shrink-0 pt-6 lg:pt-0 lg:border-l lg:pl-8 border-neutral-100">
                        <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="w-full px-6 py-4 bg-emerald-500 text-white font-black rounded-2xl hover:bg-emerald-600 transition-all active:scale-95 shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 group">
                                Approve
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </form>
                        <button onclick="openRejectModal({{ $user->id }}, '{{ $user->name }}')" class="w-full sm:w-auto px-6 py-4 bg-rose-50 text-rose-600 font-black rounded-2xl hover:bg-rose-100 transition-all active:scale-95 flex items-center justify-center gap-2 group">
                            Reject
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                @if($user->verification_status === 'rejected' && $user->rejection_note)
                    <div class="mt-8 p-4 bg-rose-50/50 border border-rose-100 rounded-2xl flex items-start gap-4">
                        <div class="p-2 bg-white text-rose-500 rounded-xl shadow-sm border border-rose-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest leading-none mb-1">Last Rejection Reason</p>
                            <p class="text-sm font-bold text-rose-900 italic">"{{ $user->rejection_note }}"</p>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border-2 border-dashed border-neutral-200 rounded-[2.5rem] p-20 text-center">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-neutral-900 mb-2">All Caught Up!</h3>
                <p class="text-neutral-500 font-medium max-w-sm mx-auto">There are no pending profile verifications at the moment.</p>
            </div>
        @endforelse

        <div class="pt-10">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-neutral-900/60 backdrop-blur-sm" onclick="closeRejectModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
        <form id="reject-form" action="" method="POST" class="bg-white rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden">
            @csrf
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-400/5 rounded-full blur-[60px] -mt-16 -mr-16 pointer-events-none"></div>
            
            <h3 class="text-2xl font-black text-neutral-900 mb-2">Reject Application</h3>
            <p class="text-neutral-500 font-medium mb-8">Please provide a reason why you are rejecting <span id="reject-user-name" class="font-black text-neutral-900"></span>'s verification.</p>
            
            <input type="hidden" name="status" value="rejected">
            <div class="space-y-2 mb-8">
                <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">Rejection Reason</label>
                <textarea name="rejection_note" required rows="4" placeholder="e.g. Identity photo is blurry, Please upload RC book again..."
                          class="w-full px-5 py-4 bg-neutral-50 border-2 border-neutral-100 rounded-2xl font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-rose-900/5 transition-all outline-none"></textarea>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeRejectModal()" class="flex-grow py-4 bg-white border border-neutral-200 text-neutral-600 font-black rounded-2xl hover:bg-neutral-50 transition-all active:scale-95">Cancel</button>
                <button type="submit" class="flex-grow py-4 bg-rose-600 text-white font-black rounded-2xl shadow-xl shadow-rose-900/20 hover:bg-rose-700 transition-all active:scale-95">Send Note</button>
            </div>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 z-[110] hidden">
    <div class="absolute inset-0 bg-neutral-900/95 backdrop-blur-md" onclick="closeImageModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-6">
        <div class="bg-white rounded-[2.5rem] p-2 shadow-2xl overflow-hidden relative group">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 z-10 p-3 bg-neutral-900/50 hover:bg-neutral-900 text-white rounded-2xl transition-all hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <div class="absolute top-6 left-6 z-10 px-4 py-2 bg-neutral-900/50 backdrop-blur-md text-white rounded-xl text-sm font-black tracking-widest uppercase" id="img-modal-title"></div>
            <img id="img-modal-src" src="" class="w-full h-auto max-h-[85vh] object-contain rounded-[2rem]">
        </div>
    </div>
</div>

<script>
function openRejectModal(userId, userName) {
    document.getElementById('reject-form').action = `/admin/users/${userId}/verify`;
    document.getElementById('reject-user-name').innerText = userName;
    document.getElementById('reject-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openImageModal(src, title) {
    document.getElementById('img-modal-src').src = src;
    document.getElementById('img-modal-title').innerText = title;
    document.getElementById('image-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
