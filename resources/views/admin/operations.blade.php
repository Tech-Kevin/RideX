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
                <div id="surge-banner"
                    class="{{ $metrics['surge_active'] ? '' : 'hidden' }} flex items-center gap-2 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-700 rounded-xl font-black text-sm shadow-sm">
                    🔥 <span id="surge-banner-text">Surge Active ({{ $metrics['surge_multiplier'] }}x)</span>
                </div>
                {{-- Live indicator --}}
                <div
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 bg-emerald-500 rounded-full"></span>
                    </span>
                    Live &bull; <span id="last-updated">Just&nbsp;now</span>
                </div>
                <a href="{{ route('admin.surge-rules.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-neutral-900 hover:bg-neutral-800 text-white rounded-xl font-bold text-sm transition-all">
                    ⚡ Manage Surge Rules
                </a>
            </div>
        </div>

        {{-- ── Supply / Demand Ratio ─────────────────────────────────── --}}
        <div id="ratio-card"
            class="relative overflow-hidden rounded-3xl border p-6 {{ $metrics['ratio'] >= 2 ? 'bg-rose-50 border-rose-200' : ($metrics['ratio'] >= 1 ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200') }}">
            <div class="flex items-center justify-between">
                <div>
                    <p
                        class="text-[11px] font-black uppercase tracking-widest {{ $metrics['ratio'] >= 2 ? 'text-rose-500' : ($metrics['ratio'] >= 1 ? 'text-amber-500' : 'text-emerald-600') }} mb-1">
                        Supply / Demand Ratio
                    </p>
                    <p class="text-5xl font-black font-heading {{ $metrics['ratio'] >= 2 ? 'text-rose-700' : ($metrics['ratio'] >= 1 ? 'text-amber-700' : 'text-emerald-700') }}"
                        id="stat-ratio">
                        {{ $metrics['ratio'] }}×
                    </p>
                    <p
                        class="text-sm mt-2 {{ $metrics['ratio'] >= 2 ? 'text-rose-600' : ($metrics['ratio'] >= 1 ? 'text-amber-600' : 'text-emerald-600') }}">
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
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xs font-black uppercase tracking-[0.18em] text-neutral-400">
                    Driver Fleet Status
                </h2>
                <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-neutral-300">
                    Live snapshot
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @php
                    $driverCards = [
                        [
                            'key' => 'totalDrivers',
                            'label' => 'Total Drivers',
                            'icon' => '👥',
                            'wrap' => 'bg-white border border-neutral-200',
                            'iconWrap' => 'bg-violet-50 text-violet-600',
                            'valueClass' => 'text-neutral-900',
                            'labelClass' => 'text-neutral-500',
                            'val' => $metrics['totalDrivers']
                        ],
                        [
                            'key' => 'onlineAvailable',
                            'label' => 'Online & Available',
                            'icon' => '🟢',
                            'wrap' => 'bg-emerald-50 border border-emerald-200',
                            'iconWrap' => 'bg-white text-emerald-500',
                            'valueClass' => 'text-emerald-700',
                            'labelClass' => 'text-emerald-700/80',
                            'val' => $metrics['onlineAvailable']
                        ],
                        [
                            'key' => 'onTrip',
                            'label' => 'On Trip',
                            'icon' => '🚗',
                            'wrap' => 'bg-blue-50 border border-blue-200',
                            'iconWrap' => 'bg-white text-blue-500',
                            'valueClass' => 'text-blue-700',
                            'labelClass' => 'text-blue-700/80',
                            'val' => $metrics['onTrip']
                        ],
                        [
                            'key' => 'onBreak',
                            'label' => 'On Break',
                            'icon' => '☕',
                            'wrap' => 'bg-amber-50 border border-amber-200',
                            'iconWrap' => 'bg-white text-amber-500',
                            'valueClass' => 'text-amber-700',
                            'labelClass' => 'text-amber-700/80',
                            'val' => $metrics['onBreak']
                        ],
                        [
                            'key' => 'offline',
                            'label' => 'Offline',
                            'icon' => '⚫',
                            'wrap' => 'bg-neutral-100 border border-neutral-200',
                            'iconWrap' => 'bg-white text-neutral-500',
                            'valueClass' => 'text-neutral-800',
                            'labelClass' => 'text-neutral-500',
                            'val' => $metrics['offline']
                        ],
                        [
                            'key' => 'suspended',
                            'label' => 'Suspended',
                            'icon' => '🚫',
                            'wrap' => 'bg-rose-50 border border-rose-200',
                            'iconWrap' => 'bg-white text-rose-500',
                            'valueClass' => 'text-rose-700',
                            'labelClass' => 'text-rose-700/80',
                            'val' => $metrics['suspended']
                        ],
                    ];
                @endphp

                @foreach($driverCards as $card)
                    <div
                        class="group rounded-[1.75rem] p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $card['wrap'] }}">
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl shadow-sm {{ $card['iconWrap'] }}">
                                <span>{{ $card['icon'] }}</span>
                            </div>

                            <span class="text-[10px] font-black uppercase tracking-[0.16em] text-neutral-300">
                                {{ $loop->iteration < 10 ? '0' . $loop->iteration : $loop->iteration }}
                            </span>
                        </div>

                        <div class="mt-6">
                            <p class="text-4xl font-black font-heading leading-none {{ $card['valueClass'] }}"
                                id="stat-{{ $card['key'] }}">
                                {{ $card['val'] }}
                            </p>
                            <p class="mt-3 text-[11px] font-black uppercase tracking-[0.14em] {{ $card['labelClass'] }}">
                                {{ $card['label'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── Marketplace Activity ───────────────────────────────────── --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-black uppercase tracking-widest text-neutral-400">Marketplace Activity Filters</h2>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 lg:gap-4 mb-8">
                @php
                    $activityCards = [
                        ['key' => 'totalRides', 'filter' => 'all', 'label' => 'All Rides', 'accent' => 'border-neutral-200 bg-neutral-50 hover:border-neutral-300 text-neutral-500', 'active_class' => 'ring-2 ring-neutral-400 bg-neutral-100 border-neutral-300', 'num_color' => 'text-neutral-800', 'val' => $metrics['totalRides']],
                        ['key' => 'activeRiders', 'filter' => 'pending', 'label' => 'Active Requests', 'accent' => 'border-rose-100 bg-rose-50/50 hover:border-rose-200 text-rose-400', 'active_class' => 'ring-2 ring-rose-500 bg-rose-50 border-rose-300', 'num_color' => 'text-rose-700', 'val' => $metrics['activeRiders']],
                        ['key' => 'ongoingTrips', 'filter' => 'ongoing', 'label' => 'Ongoing Trips', 'accent' => 'border-blue-100 bg-blue-50/50 hover:border-blue-200 text-blue-400', 'active_class' => 'ring-2 ring-blue-500 bg-blue-50 border-blue-300', 'num_color' => 'text-blue-700', 'val' => $metrics['ongoingTrips']],
                        ['key' => 'completedToday', 'filter' => 'completed', 'label' => 'Completed (Today)', 'accent' => 'border-emerald-100 bg-emerald-50/50 hover:border-emerald-200 text-emerald-400', 'active_class' => 'ring-2 ring-emerald-500 bg-emerald-50 border-emerald-300', 'num_color' => 'text-emerald-700', 'val' => $metrics['completedToday']],
                        ['key' => 'cancelledTrips', 'filter' => 'cancelled', 'label' => 'Cancelled', 'accent' => 'border-neutral-100 bg-neutral-50/50 hover:border-neutral-200 text-neutral-400', 'active_class' => 'ring-2 ring-neutral-400 bg-neutral-100 border-neutral-300', 'num_color' => 'text-neutral-600', 'val' => $metrics['cancelledTrips'] ?? 0],
                        ['key' => 'surgeRides', 'filter' => 'surge', 'label' => 'Surge Active', 'accent' => 'border-orange-100 bg-orange-50/50 hover:border-orange-200 text-orange-400', 'active_class' => 'ring-2 ring-orange-500 bg-orange-50 border-orange-300', 'num_color' => 'text-orange-700', 'val' => $metrics['surgeRides'] ?? 0],
                    ];
                @endphp
                @foreach($activityCards as $card)
                    <button type="button" onclick="setFilter('{{ $card['filter'] }}')" id="filter-card-{{ $card['filter'] }}"
                        class="filter-card text-left rounded-[1.25rem] border {{ $card['accent'] }} p-5 shadow-sm transition-all duration-300 outline-none relative overflow-hidden group hover:shadow-md
                        {{ $loop->first ? $card['active_class'] . ' is-active' : '' }}"
                        data-active-class="{{ $card['active_class'] }}"
                        data-inactive-class="{{ $card['accent'] }}">
                        <p class="text-2xl mb-3 opacity-90 group-hover:scale-110 transition-transform origin-left">{{ $card['label'] }}</p>
                        <p class="text-3xl font-black font-heading {{ $card['num_color'] }}" id="stat-{{ $card['key'] }}">
                            {{ $card['val'] }}</p>
                        <p class="text-[10px] font-bold text-neutral-700 mt-1 uppercase tracking-widest"></p>
                    </button>
                @endforeach
            </div>

            <div id="marketplace-table-wrapper" class="bg-white border text-neutral-800 rounded-3xl overflow-hidden shadow-sm min-h-[300px] relative mb-8">
                <!-- Loading Overlay -->
                <div id="table-loading" class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-10 hidden">
                    <div class="h-10 w-10 border-4 border-neutral-200 border-t-neutral-800 rounded-full animate-spin"></div>
                </div>
                <div id="marketplace-table-content">
                    <!-- Loaded via AJAX -->
                </div>
            </div>
        </div>

        {{-- Surge Info row --}}
        <div id="surge-detail-row"
            class="{{ $metrics['surge_active'] ? '' : 'hidden' }} rounded-2xl border border-orange-200 bg-orange-50 p-5 flex items-center gap-4">
            <span class="text-4xl">🔥</span>
            <div>
                <p class="font-black text-orange-800">Surge Pricing is Active</p>
                <p class="text-sm text-orange-700 mt-0.5">
                    Rule: <span id="surge-rule-name" class="font-bold">{{ $metrics['surge_rule_name'] ?? '—' }}</span>
                    &bull; Multiplier: <span id="surge-multiplier-display"
                        class="font-bold text-orange-900">{{ $metrics['surge_multiplier'] }}x</span>
                    &bull; All new rides are priced at this rate.
                </p>
            </div>
            <a href="{{ route('admin.surge-rules.index') }}"
                class="ml-auto px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white font-bold rounded-xl text-sm transition-all whitespace-nowrap">
                Manage Rules →
            </a>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        const METRICS_URL = '{{ route("admin.surge-rules.index") }}';
        const METRICS_AJAX = '{{ route("admin.operations.metrics") }}';

        function updateStat(id, value) {
            const el = document.getElementById(id);
            if (!el) return;
            if (el.textContent.trim() !== String(value)) {
                el.classList.add('scale-110', 'text-amber-500');
                setTimeout(() => el.classList.remove('scale-110', 'text-amber-500'), 600);
                el.textContent = value;
            }
        }

        let currentFilter = 'all';

        function setFilter(filterName) {
            if (currentFilter === filterName) return;
            currentFilter = filterName;
            
            document.querySelectorAll('.filter-card').forEach(card => {
                const activeClasses = card.getAttribute('data-active-class').split(' ').filter(c => c);
                // Also manage inactive classes so they don't conflict
                const inactiveClasses = card.getAttribute('data-inactive-class').split(' ').filter(c => c);
                
                if (card.id === 'filter-card-' + filterName) {
                    card.classList.remove(...inactiveClasses);
                    card.classList.add(...activeClasses);
                    card.classList.add('is-active');
                } else {
                    card.classList.remove(...activeClasses);
                    card.classList.add(...inactiveClasses);
                    card.classList.remove('is-active');
                }
            });

            loadTableData();
        }

        async function loadTableData(pageUrl = null) {
            let url = pageUrl || `{{ route('admin.operations.marketplace-table') }}?filter=${currentFilter}`;
            if (pageUrl && !pageUrl.includes('filter=')) {
                url = pageUrl + (pageUrl.includes('?') ? '&' : '?') + `filter=${currentFilter}`;
            }

            document.getElementById('table-loading').classList.remove('hidden');
            
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                document.getElementById('marketplace-table-content').innerHTML = html;
                
                const paginationLinks = document.querySelectorAll('#marketplace-table-content nav a');
                if (paginationLinks) {
                    paginationLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            loadTableData(this.href);
                        });
                    });
                }
            } catch (e) {
                console.error('Table load error', e);
            } finally {
                document.getElementById('table-loading').classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadTableData();
        });

        async function fetchMetrics() {
            try {
                const res = await fetch(METRICS_AJAX, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                updateStat('stat-totalDrivers', data.totalDrivers);
                updateStat('stat-onlineAvailable', data.onlineAvailable);
                updateStat('stat-onTrip', data.onTrip);
                updateStat('stat-onBreak', data.onBreak);
                updateStat('stat-offline', data.offline);
                updateStat('stat-suspended', data.suspended);
                
                updateStat('stat-activeRiders', data.activeRiders);
                updateStat('stat-ongoingTrips', data.ongoingTrips);
                updateStat('stat-completedToday', data.completedToday);
                updateStat('stat-cancelledTrips', data.cancelledTrips);
                updateStat('stat-surgeRides', data.surgeRides);
                updateStat('stat-totalRides', data.totalRides);

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

            } catch (e) { console.error('Metrics fetch error', e); }
        }

        setInterval(fetchMetrics, 10000);
    </script>
@endsection