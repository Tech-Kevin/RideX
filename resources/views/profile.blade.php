@extends('layouts.app')

@section('title', 'My Profile - RideX')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 md:py-12 w-full">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard') }}" class="p-2.5 bg-white hover:bg-neutral-50 border border-neutral-200 shadow-sm rounded-xl transition-all text-neutral-500 hover:text-neutral-900 active:scale-95 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-3xl font-black font-heading text-neutral-900 mb-1 tracking-tight">Account Settings</h1>
            <p class="text-neutral-500 text-base font-medium">Manage your personal information and documents.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold flex items-center gap-3 animate-[fade-in-down_0.3s_ease-out]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Verification Status Banner -->
    <div class="mb-8 p-6 rounded-[2rem] border-2 flex flex-col md:flex-row items-center gap-4 transition-all
        @if($user->verification_status === 'approved') bg-emerald-50 border-emerald-100 text-emerald-800
        @elseif($user->verification_status === 'rejected') bg-rose-50 border-rose-100 text-rose-800
        @else bg-amber-50 border-amber-100 text-amber-800 @endif">
        
        <div class="p-3 rounded-2xl bg-white shadow-sm shrink-0">
            @if($user->verification_status === 'approved')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            @elseif($user->verification_status === 'rejected')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            @endif
        </div>

        <div class="flex-grow text-center md:text-left">
            <h4 class="text-lg font-black font-heading mb-0.5">
                @if($user->verification_status === 'approved') Account Verified
                @elseif($user->verification_status === 'rejected') Verification Rejected
                @else Verification Pending @endif
            </h4>
            <p class="text-sm font-bold opacity-80">
                @if($user->verification_status === 'approved') Your account is active and you can use all features.
                @elseif($user->verification_status === 'rejected') Please review the rejection reason and update your documents.
                @else Admin is currently reviewing your profile. Changes may take up to 24 hours. @endif
            </p>
        </div>

        @if($user->verification_status === 'pending')
            <div class="px-4 py-2 bg-amber-400 text-neutral-900 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm">In Review</div>
        @endif
    </div>

    @if($user->verification_status === 'rejected' && $user->rejection_note)
        <div class="mb-8 p-6 bg-rose-600 rounded-[2rem] text-white shadow-xl shadow-rose-900/20 animate-pulse">
            <div class="flex items-start gap-4">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-1">Message from Admin</p>
                    <p class="text-lg font-bold leading-relaxed">"{{ $user->rejection_note }}"</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Profile Photo & Basic Info -->
        <div class="bg-white border border-neutral-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-neutral-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-amber-400/5 rounded-full blur-[60px] -mt-20 -mr-20 pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row gap-10 items-center md:items-start text-center md:text-left">
                <!-- Avatar Upload -->
                <div class="relative group">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-neutral-100 border-4 border-white shadow-lg overflow-hidden relative">
                        <img id="avatar-preview" src="{{ $user->getFirstMediaUrl('avatar') ?: 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f5f5f5&color=171717&size=256' }}" class="w-full h-full object-cover">
                        <label for="avatar" class="absolute inset-0 bg-neutral-900/60 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </label>
                    </div>
                    <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage(this, 'avatar-preview')">
                    <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-neutral-400">Profile Photo</p>
                </div>

                <div class="flex-grow space-y-6 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-5 py-4 bg-neutral-50 border-2 border-neutral-100 rounded-2xl font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-neutral-900/5 transition-all">
                            @error('name') <p class="text-rose-600 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob', $user->dob?->format('Y-m-d')) }}"
                                   class="w-full px-5 py-4 bg-neutral-50 border-2 border-neutral-100 rounded-2xl font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-neutral-900/5 transition-all">
                            @error('dob') <p class="text-rose-600 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="p-4 bg-neutral-50 rounded-2xl border border-neutral-100">
                         <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-neutral-500">Phone Number</span>
                            <span class="text-sm font-black text-neutral-900">{{ $user->phone }}</span>
                         </div>
                    </div>
                </div>
            </div>
        </div>

        @if($user->isDriver())
            <!-- Driver Specific Info -->
            <div class="bg-white border border-neutral-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-neutral-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-blue-400/5 rounded-full blur-[60px] -mt-20 -mr-20 pointer-events-none"></div>
                
                <h3 class="text-xl font-black font-heading text-neutral-900 mb-8 flex items-center gap-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    Vehicle & Documents
                </h3>

                <div class="space-y-8">
                    <!-- Vehicle Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">Vehicle Type</label>
                            <select name="vehicle_type" class="w-full px-5 py-4 bg-neutral-50 border-2 border-neutral-100 rounded-2xl font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 transition-all appearance-none cursor-pointer">
                                @foreach(\App\Enums\VehicleType::cases() as $type)
                                    <option value="{{ $type->value }}" {{ old('vehicle_type', $user->vehicle_type?->value ?? '') === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">Vehicle Number</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $user->vehicle_number) }}" placeholder="e.g. GJ-01-AB-1234"
                                   class="w-full px-5 py-4 bg-neutral-50 border-2 border-neutral-100 rounded-2xl font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-neutral-900/5 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">Licence Number</label>
                            <input type="text" name="licence_number" value="{{ old('licence_number', $user->licence_number) }}"
                                   class="w-full px-5 py-4 bg-neutral-50 border-2 border-neutral-100 rounded-2xl font-bold text-neutral-900 focus:outline-none focus:bg-white focus:border-neutral-900 focus:ring-4 focus:ring-neutral-900/5 transition-all">
                        </div>
                    </div>

                    <!-- Document Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach([
                            ['id' => 'rc_book', 'label' => 'RC Book Photo', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['id' => 'driving_licence', 'label' => 'Driving Licence Photo', 'icon' => 'M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14'],
                            ['id' => 'vehicle_photo', 'label' => 'Vehicle Photo', 'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z']
                        ] as $doc)
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-neutral-400 uppercase tracking-widest pl-1">{{ $doc['label'] }}</label>
                                <div class="relative group">
                                    <input type="file" name="{{ $doc['id'] }}" id="{{ $doc['id'] }}" class="peer sr-only" accept="image/*" onchange="previewImage(this, 'preview-{{ $doc['id'] }}')">
                                    <label for="{{ $doc['id'] }}" class="flex items-center gap-5 p-5 bg-neutral-50/50 border-2 border-neutral-100 rounded-[1.5rem] cursor-pointer hover:bg-neutral-50 transition-all group-hover:border-neutral-200">
                                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-neutral-400 overflow-hidden relative border border-neutral-100">
                                            @if($user->hasMedia($doc['id']))
                                                <img id="preview-{{ $doc['id'] }}" src="{{ $user->getFirstMediaUrl($doc['id']) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $doc['icon'] }}" /></svg>
                                                <img id="preview-{{ $doc['id'] }}" class="w-full h-full object-cover hidden">
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <p class="text-sm font-black text-neutral-900">Upload {{ $doc['label'] }}</p>
                                            <p class="text-xs font-bold text-neutral-400 uppercase tracking-widest mt-0.5">JPG, PNG up to 5MB</p>
                                        </div>
                                        <div class="p-2 bg-neutral-100 rounded-xl text-neutral-400 group-hover:bg-neutral-900 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white border border-neutral-200 text-neutral-600 font-black rounded-2xl hover:bg-neutral-50 transition-all active:scale-95">Cancel</a>
            <button type="submit" class="px-10 py-4 bg-neutral-900 text-white font-black rounded-2xl shadow-xl shadow-neutral-900/20 hover:bg-neutral-800 transition-all active:scale-95 flex items-center gap-3 group">
                Save Changes
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
