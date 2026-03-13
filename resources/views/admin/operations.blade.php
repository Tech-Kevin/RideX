@extends('layouts.admin')

@section('title', 'Operations Panel – RideX Admin')
@section('breadcrumb', 'Operations')

@section('admin-content')
<div class="p-8 space-y-8">

    {{-- Header: title + surge banner + last updated --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-heading text-neutral-900 tracking-tight">Operations Panel</h1>
            <p class="text-sm text-neutral-500 mt-1">Real-time platform health.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Surge Badge --}}
            <div id="surge-banner" class="{{ $metrics['surge_active'] ? '' : 'hidden' }} flex items-center gap-2 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-700 rounded-xl font-black text-sm shadow-sm">
                🔥 <span id="surge-banner-text">Surge Active ({{ $metrics['surge_multiplier'] }}x)</span>
            </div>
            {{-- Live indicator --}}
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 bg-emerald-500 rounded-full"></span>
                </span>
                Live  &bull; <span id="last-updated">Just&nbsp;now</span>
            </div>
            <a href="{{ route('admin.surge-rules.index') }}"
               class="flex items-center gap-2 px-4 py-2 bg-neutral-900 hover:bg-neutral-800 text-white rounded-xl font-bold text-sm transition-all">
                ⚡ Manage Surge Rules
            </a>
        </div>
    </div>

    {{-- ── Supply / Demand Ratio ─────────────────────────────────── --}}
    <div id="ratio-card" class="relative overflow-hidden rounded-3xl border p-6 {{ $metrics['ratio'] >= 2 ? 'bg-rose-50 border-rose-200' : ($metrics['ratio'] >= 1 ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200') }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-black uppercase tracking-widest {{ $metrics['ratio'] >= 2 ? 'text-rose-500' : ($metrics['ratio'] >= 1 ? 'text-amber-500' : 'text-emerald-600') }} mb-1">
                    Supply / Demand Ratio
                </p>
                <p class="text-5xl font-black font-heading {{ $metrics['ratio'] >= 2 ? 'text-rose-700' : ($metrics['ratio'] >= 1 ? 'text-amber-700' : 'text-emerald-700') }}" id="stat-ratio">
                    {{ $metrics['ratio'] }}×
                </p>
                <p class="text-sm mt-2 {{ $metrics['ratio'] >= 2 ? 'text-rose-600' : ($metrics['ratio'] >= 1 ? 'text-amber-600' : 'text-emerald-600') }}">
                    @if($metrics['ratio'] >= 2)
                        ⚠ High demand — consider activating surge pricing
                    @elseif($metrics['ratio'] >= 1)
                        ⚡ Moderate demand — monitor supply
                    @else
                        ✓ Healthy balance — drivers available
                    @endif
                </p>
            </div>
            <div class="text-7xl opacity-10 font-black font-heading">⚖</div>
        </div>
    </div>

    {{-- ── 6 Driver Status Cards ──────────────────────────────────── --}}
    <div>
        <h2 class="text-xs font-black uppercase tracking-widest text-neutral-400 mb-4">Driver Fleet Status</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $driverCards = [
                    ['key'=>'totalDrivers',    'label'=>'Total Drivers',       'icon'=>'👥', 'bg'=>'bg-gray-800 text-white', 'val' => $metrics['totalDrivers']],
                    ['key'=>'onlineAvailable', 'label'=>'Online & Available',  'icon'=>'🟢', 'bg'=>'bg-emerald-500 text-white','val' => $metrics['onlineAvailable']],
                    ['key'=>'onTrip',          'label'=>'On Trip',             'icon'=>'🚗', 'bg'=>'bg-blue-500 text-white',   'val' => $metrics['onTrip']],
                    ['key'=>'onBreak',         'label'=>'On Break',            'icon'=>'☕', 'bg'=>'bg-amber-500 text-white',  'val' => $metrics['onBreak']],
                    ['key'=>'offline',         'label'=>'Offline',             'icon'=>'⚫', 'bg'=>'bg-neutral-200 text-neutral-700','val' => $metrics['offline']],
                    ['key'=>'suspended',       'label'=>'Suspended',           'icon'=>'🚫', 'bg'=>'bg-rose-500 text-white',   'val' => $metrics['suspended']],
                ];
            @endphp
            @foreach($driverCards as $card)
            <div class="rounded-2xl p-5 {{ $card['bg'] }} shadow-sm flex flex-col items-start gap-2" style="background-color: lightgray; color:black;">
                <span class="text-2xl">{{ $card['icon'] }}</span>
                <p class="text-3xl font-black font-heading leading-none" id="stat-{{ $card['key'] }}">{{ $card['val'] }}</p>
                <p class="text-[11px] font-bold uppercase tracking-wider opacity-80">{{ $card['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Marketplace Activity ───────────────────────────────────── --}}
    <div>
        <h2 class="text-xs font-black uppercase tracking-widest text-neutral-400 mb-4">Marketplace Activity</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @php
                $activityCards = [
                    ['key'=>'activeRiders',   'label'=>'Riders Requesting',  'icon'=>'📍', 'desc'=>'Pending ride requests right now', 'accent'=>'border-rose-300 bg-rose-50',   'num_color'=>'text-rose-700',   'val'=>$metrics['activeRiders']],
                    ['key'=>'ongoingTrips',   'label'=>'Ongoing Trips',       'icon'=>'🛣️', 'desc'=>'Accepted, arriving, or in progress','accent'=>'border-blue-300 bg-blue-50',  'num_color'=>'text-blue-700',   'val'=>$metrics['ongoingTrips']],
                    ['key'=>'completedToday', 'label'=>'Completed Today',     'icon'=>'✅', 'desc'=>'Trips finished since midnight',     'accent'=>'border-emerald-300 bg-emerald-50','num_color'=>'text-emerald-700','val'=>$metrics['completedToday']],
                ];
            @endphp
            @foreach($activityCards as $card)
            <div class="rounded-2xl border {{ $card['accent'] }} p-6 shadow-sm">
                <p class="text-3xl mb-3">{{ $card['icon'] }}</p>
                <p class="text-4xl font-black font-heading {{ $card['num_color'] }}" id="stat-{{ $card['key'] }}">{{ $card['val'] }}</p>
                <p class="text-sm font-bold text-neutral-700 mt-2">{{ $card['label'] }}</p>
                <p class="text-xs text-neutral-500 mt-0.5">{{ $card['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Surge Info row --}}
    <div id="surge-detail-row" class="{{ $metrics['surge_active'] ? '' : 'hidden' }} rounded-2xl border border-orange-200 bg-orange-50 p-5 flex items-center gap-4">
        <span class="text-4xl">🔥</span>
        <div>
            <p class="font-black text-orange-800">Surge Pricing is Active</p>
            <p class="text-sm text-orange-700 mt-0.5">
                Rule: <span id="surge-rule-name" class="font-bold">{{ $metrics['surge_rule_name'] ?? '—' }}</span>
                &bull; Multiplier: <span id="surge-multiplier-display" class="font-bold text-orange-900">{{ $metrics['surge_multiplier'] }}x</span>
                &bull; All new rides are priced at this rate.
            </p>
        </div>
        <a href="{{ route('admin.surge-rules.index') }}" class="ml-auto px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white font-bold rounded-xl text-sm transition-all whitespace-nowrap">
            Manage Rules →
        </a>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const METRICS_URL  = '{{ route("admin.surge-rules.index") }}';
    const METRICS_AJAX = '{{ route("admin.operations.metrics") }}';

    function updateStat(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.textContent.trim() !== String(value)) {
            el.classList.add('scale-110', 'text-amber-500');
            setTimeout(() => el.classList.remove('scale-110','text-amber-500'), 600);
            el.textContent = value;
        }
    }

    async function fetchMetrics() {
        try {
            const res  = await fetch(METRICS_AJAX, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();

            updateStat('stat-totalDrivers',    data.totalDrivers);
            updateStat('stat-onlineAvailable', data.onlineAvailable);
            updateStat('stat-onTrip',          data.onTrip);
            updateStat('stat-onBreak',         data.onBreak);
            updateStat('stat-offline',         data.offline);
            updateStat('stat-suspended',       data.suspended);
            updateStat('stat-activeRiders',    data.activeRiders);
            updateStat('stat-ongoingTrips',    data.ongoingTrips);
            updateStat('stat-completedToday',  data.completedToday);

            // Ratio card
            const ratioEl = document.getElementById('stat-ratio');
            if (ratioEl) ratioEl.textContent = data.ratio + '×';

            // Surge banner
            const banner = document.getElementById('surge-banner');
            const bannerText = document.getElementById('surge-banner-text');
            const surgeRow = document.getElementById('surge-detail-row');
            if (data.surge_active) {
                banner.classList.remove('hidden');
                if (bannerText) bannerText.textContent = `Surge Active (${data.surge_multiplier}x)`;
                surgeRow?.classList.remove('hidden');
                const ruleName = document.getElementById('surge-rule-name');
                const multiplierDisplay = document.getElementById('surge-multiplier-display');
                if (ruleName) ruleName.textContent = data.surge_rule_name ?? '—';
                if (multiplierDisplay) multiplierDisplay.textContent = data.surge_multiplier + 'x';
            } else {
                banner.classList.add('hidden');
                surgeRow?.classList.add('hidden');
            }

            // Last updated
            const now = new Date();
            document.getElementById('last-updated').textContent =
                now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        } catch(e) { console.error('Metrics fetch error', e); }
    }

    setInterval(fetchMetrics, 10000);
</script>
@endsection
