<div class="overflow-x-auto">
    <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="bg-neutral-50 text-neutral-500 font-bold uppercase tracking-wider text-[10px]">
            <tr>
                <th class="px-6 py-4 border-b">Ride ID</th>
                <th class="px-6 py-4 border-b">Customer</th>
                <th class="px-6 py-4 border-b">Driver</th>
                <th class="px-6 py-4 border-b">Status</th>
                <th class="px-6 py-4 border-b">Fare / Surge</th>
                <th class="px-6 py-4 border-b">Time</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($rides as $ride)
                <tr class="hover:bg-neutral-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-neutral-900 border-r border-neutral-100 border-dashed border-r-2" style="border-right-width: 1px;">#{{ $ride->id }}</td>
                    <td class="px-6 py-4 border-r border-neutral-100 border-dashed">
                        @if($ride->customer)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-neutral-200 flex items-center justify-center text-xs font-bold text-neutral-600">
                                {{ substr($ride->customer->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-neutral-900 font-bold text-[13px]">{{ $ride->customer->name }}</span>
                                <span class="text-neutral-500 text-[11px]">{{ $ride->customer->phone }}</span>
                            </div>
                        </div>
                        @else
                            <span class="text-neutral-400 italic">Deleted User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 border-r border-neutral-100 border-dashed">
                        @if($ride->driver)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-xs font-bold text-emerald-700">
                                    {{ substr($ride->driver->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-emerald-700 font-bold text-[13px]">{{ $ride->driver->name }}</span>
                                    <span class="text-neutral-500 text-[11px]">{{ $ride->driver->phone }}</span>
                                </div>
                            </div>
                        @else
                            <span class="text-neutral-400 font-medium italic">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 border-r border-neutral-100 border-dashed">
                        @php
                            $badgeColor = match($ride->status->value) {
                                'pending' => 'bg-rose-100 text-rose-700 border-rose-200',
                                'accepted', 'driver_arriving', 'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-neutral-100 text-neutral-500 border-neutral-200',
                                default => 'bg-neutral-100 text-neutral-700 border-neutral-200',
                            };
                        @endphp
                        <span class="px-2.5 py-1.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $badgeColor }}">
                            {{ str_replace('_', ' ', $ride->status->value) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 border-r border-neutral-100 border-dashed">
                        <div class="flex flex-col">
                            <span class="font-black text-neutral-900">${{ $ride->estimated_fare }}</span>
                            @if($ride->surge_multiplier > 1.00)
                                <span class="text-[10px] font-bold text-orange-600 bg-orange-100 px-1 py-0.5 rounded ml-[-2px] inline-block w-max mt-1">🔥 {{ $ride->surge_multiplier }}x Surge</span>
                            @else
                                <span class="text-[10px] font-medium text-neutral-400 mt-1">Standard</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-neutral-800 text-[12px] font-medium">{{ $ride->created_at->format('M d, g:i A') }}</span>
                            <span class="text-neutral-400 text-[11px]">{{ $ride->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-neutral-400">
                            <span class="text-4xl mb-3">📭</span>
                            <span class="font-bold text-sm">No rides found</span>
                            <span class="text-xs mt-1">Try changing or clearing the filters above.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($rides->hasPages())
        <div class="p-4 border-t border-neutral-100 bg-neutral-50/50 flex justify-between items-center" id="marketplace-pagination">
            {{ $rides->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
