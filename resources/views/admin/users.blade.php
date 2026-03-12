@extends('layouts.admin')

@section('title', 'User Management - RideX Admin')

@section('admin-content')
<div class="px-6 py-8 md:py-12 w-full">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black font-heading text-neutral-900 tracking-tight mb-2">User Management</h1>
            <p class="text-neutral-500 font-medium text-lg">View and manage all registered customers, drivers, and administrators.</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            <a href="{{ route('admin.users') }}" class="whitespace-nowrap px-4 py-2 {{ !request('role') ? 'bg-neutral-900 text-white shadow-md' : 'bg-white text-neutral-600 hover:bg-neutral-50 border border-neutral-200' }} rounded-xl font-bold text-sm transition-all">
                All Users
            </a>
            <a href="{{ route('admin.users', ['role' => 'customer']) }}" class="whitespace-nowrap px-4 py-2 {{ request('role') === 'customer' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-white text-neutral-600 hover:bg-neutral-50 border border-neutral-200' }} rounded-xl font-bold text-sm transition-all">
                Customers
            </a>
            <a href="{{ route('admin.users', ['role' => 'driver']) }}" class="whitespace-nowrap px-4 py-2 {{ request('role') === 'driver' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-white text-neutral-600 hover:bg-neutral-50 border border-neutral-200' }} rounded-xl font-bold text-sm transition-all">
                Drivers
            </a>
            <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="whitespace-nowrap px-4 py-2 {{ request('role') === 'admin' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-neutral-600 hover:bg-neutral-50 border border-neutral-200' }} rounded-xl font-bold text-sm transition-all">
                Admins
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white border text-left border-neutral-200 rounded-[2.5rem] overflow-hidden shadow-sm mb-12">
        
        @if ($users->isEmpty())
             <div class="p-16 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mb-6 border-2 border-dashed border-neutral-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <h3 class="text-2xl font-bold font-heading text-neutral-900 mb-2">No users found</h3>
                <p class="text-neutral-500 max-w-sm mx-auto font-medium">There are no users matching the current filter criteria.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-neutral-50/80 border-b border-neutral-200 text-neutral-400 uppercase tracking-widest text-[10px] font-black">
                            <th class="p-4 pl-6 font-semibold">User</th>
                            <th class="p-4 font-semibold">Contact Info</th>
                            <th class="p-4 font-semibold">Role</th>
                            <th class="p-4 font-semibold">Joined Date</th>
                            <th class="p-4 pr-6 font-semibold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 bg-white">
                        @foreach ($users as $user)
                            <tr class="hover:bg-neutral-50/50 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full @if($user->isAdmin()) bg-emerald-100 text-emerald-600 @elseif($user->isDriver()) bg-amber-100 text-amber-600 @else bg-blue-100 text-blue-600 @endif flex items-center justify-center font-bold text-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-neutral-900 leading-tight">{{ $user->name }}</p>
                                            <p class="text-[10px] font-mono font-bold text-neutral-400 mt-0.5">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-sm font-medium text-neutral-600">
                                    <p class="mb-0.5 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ $user->email ?? 'N/A' }} 
                                        @if($user->hasVerifiedEmail()) 
                                            <span class="text-emerald-500" title="Verified">✓</span> 
                                        @endif
                                    </p>
                                    <p class="flex items-center gap-1.5 text-xs text-neutral-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                        {{ $user->phone }}
                                    </p>
                                </td>
                                <td class="p-4">
                                     @php
                                        $roleClass = match($user->role) {
                                            'admin'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'driver'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'customer' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            default     => 'bg-neutral-100 border-neutral-200 text-neutral-500',
                                        };
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-black uppercase rounded border tracking-widest whitespace-nowrap {{ $roleClass }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="p-4 text-xs font-medium text-neutral-500">
                                    <div class="flex flex-col">
                                        <span>{{ $user->created_at->format('M d, Y') }}</span>
                                        <span class="text-[10px] text-neutral-400">{{ $user->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <button onclick="toggleUserStatus({{ $user->id }}, this)" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm border {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' }}"
                                            {{ $user->id === auth()->id() ? 'disabled opacity-50 cursor-not-allowed title="Cannot deactivate yourself"' : '' }}>
                                        <span class="status-dot w-2 h-2 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span class="status-text">{{ $user->is_active ? 'Active' : 'Blocked' }}</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="p-4 border-t border-neutral-100 bg-neutral-50/30">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function toggleUserStatus(userId, buttonElement) {
    if (buttonElement.disabled) return;
    
    // Optimistic UI update (visual only)
    const isCurrentlyActive = buttonElement.querySelector('.status-text').innerText === 'Active';
    const statusText = buttonElement.querySelector('.status-text');
    const statusDot = buttonElement.querySelector('.status-dot');
    
    // Add loading state
    buttonElement.classList.add('opacity-75', 'cursor-wait');

    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        buttonElement.classList.remove('opacity-75', 'cursor-wait');
        
        if (data.success) {
            // Apply real state from server
            const isActive = data.is_active;
            
            // Update Text
            statusText.innerText = isActive ? 'Active' : 'Blocked';
            
            // Update Classes
            if(isActive) {
                buttonElement.className = buttonElement.className.replace(/rose/g, 'emerald');
                statusDot.className = statusDot.className.replace('bg-rose-500', 'bg-emerald-500');
            } else {
                buttonElement.className = buttonElement.className.replace(/emerald/g, 'rose');
                statusDot.className = statusDot.className.replace('bg-emerald-500', 'bg-rose-500');
            }
        } else {
            alert(data.message || 'Error updating status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        buttonElement.classList.remove('opacity-75', 'cursor-wait');
        alert('A network error occurred');
    });
}
</script>
@endsection
