@extends('layouts.admin')

@section('title', 'Surge Pricing Engine – RideX Admin')
@section('breadcrumb', 'Surge Pricing')

@section('admin-content')
<div class="p-8 space-y-8" x-data="surgeManager()">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-heading text-neutral-900 tracking-tight flex items-center gap-3">
                Surge Pricing Engine
                <span class="text-[10px] px-2 py-1 bg-amber-100 text-amber-700 border border-amber-200 rounded-lg font-black uppercase tracking-widest">Micro Settings</span>
            </h1>
            <p class="text-sm text-neutral-500 mt-1">Define pricing multipliers.</p>
        </div>
        <button @click="showForm = !showForm"
                class="flex items-center gap-2 px-5 py-2.5 bg-neutral-900 hover:bg-neutral-800 text-white font-bold rounded-xl text-sm transition-all shadow-lg">
            <span x-text="showForm ? '✕ Cancel' : '+ New Rule'"></span>
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 font-bold text-sm">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- ── Add Rule Form ─────────────────────────────────────────── --}}
    <div x-show="showForm" x-cloak x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-3xl border border-neutral-200 shadow-lg p-8">

        <h2 class="text-lg font-black font-heading text-neutral-800 mb-6">New Surge Rule</h2>

        <form action="{{ route('admin.surge-rules.store') }}" method="POST" class="space-y-6" @submit="showForm = false">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Name --}}
                <div class="md:col-span-1">
                    <label class="block text-[11px] font-black uppercase tracking-widest text-neutral-500 mb-2">Rule Name</label>
                    <input type="text" name="name" placeholder="e.g. Morning Rush Hour" required
                           class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-neutral-500 mb-2">Rule Type</label>
                    <select name="type" x-model="form.type" required
                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white">
                        <option value="">Select type…</option>
                        <option value="peak_hour"> Peak Hour</option>
                        <option value="festival"> Festival / Event</option>
                        <option value="demand_based"> Demand Based</option>
                        <option value="manual_override"> Manual Override</option>
                    </select>
                </div>

                {{-- Multiplier --}}
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-neutral-500 mb-2">Multiplier (×)</label>
                    <input type="number" name="multiplier" min="1.0" max="10.0" step="0.1" value="1.5" required
                           class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-medium font-mono focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
            </div>

            {{-- Conditional fields --}}

            {{-- Peak Hour --}}
            <div x-show="form.type === 'peak_hour'" x-cloak class="grid grid-cols-1 md:grid-cols-3 gap-5 p-5 bg-blue-50 border border-blue-100 rounded-2xl">
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-black uppercase tracking-widest text-blue-600 mb-3">Active Days</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $i => $day)
                        <label class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-blue-200 rounded-lg cursor-pointer hover:border-blue-400 transition">
                            <input type="checkbox" name="days[]" value="{{ $i }}" checked class="accent-blue-500">
                            <span class="text-xs font-bold text-blue-700">{{ $day }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-blue-600 mb-2">Start Time</label>
                    <input type="time" name="start_time" value="07:00"
                           class="w-full px-4 py-3 border border-blue-200 rounded-xl text-sm font-medium bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-blue-600 mb-2">End Time</label>
                    <input type="time" name="end_time" value="10:00"
                           class="w-full px-4 py-3 border border-blue-200 rounded-xl text-sm font-medium bg-white focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
            </div>

            {{-- Festival --}}
            <div x-show="form.type === 'festival'" x-cloak class="p-5 bg-purple-50 border border-purple-100 rounded-2xl">
                <label class="block text-[11px] font-black uppercase tracking-widest text-purple-600 mb-2">Festival / Event Date</label>
                <input type="date" name="festival_date"
                       class="w-full md:w-64 px-4 py-3 border border-purple-200 rounded-xl text-sm font-medium bg-white focus:outline-none focus:ring-2 focus:ring-purple-300">
            </div>

            {{-- Demand Based --}}
            <div x-show="form.type === 'demand_based'" x-cloak class="p-5 bg-amber-50 border border-amber-100 rounded-2xl">
                <label class="block text-[11px] font-black uppercase tracking-widest text-amber-600 mb-2">
                    Ratio Threshold <span class="text-amber-500 normal-case font-medium">(riders ÷ available drivers)</span>
                </label>
                <input type="number" name="ratio_threshold" min="1.0" max="20" step="0.5" value="2.0"
                       class="w-full md:w-48 px-4 py-3 border border-amber-200 rounded-xl text-sm font-medium font-mono bg-white focus:outline-none focus:ring-2 focus:ring-amber-300">
                <p class="text-xs text-amber-600 mt-2">Surge activates when live ratio ≥ this value. Default: 2.0×</p>
            </div>

            {{-- Manual Override --}}
            <div x-show="form.type === 'manual_override'" x-cloak class="p-5 bg-rose-50 border border-rose-100 rounded-2xl">
                <p class="text-sm font-bold text-rose-700">
                     Manual Override is always active while the rule is enabled. Toggle off to deactivate.
                </p>
            </div>

            {{-- Global window (optional) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-neutral-100">
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-neutral-500 mb-2">Global Start (optional)</label>
                    <input type="datetime-local" name="starts_at"
                           class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
                <div>
                    <label class="block text-[11px] font-black uppercase tracking-widest text-neutral-500 mb-2">Global End (optional)</label>
                    <input type="datetime-local" name="ends_at"
                           class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="px-8 py-3 bg-amber-400 hover:bg-amber-300 text-neutral-900 font-black rounded-xl transition-all shadow-lg shadow-amber-400/20 active:scale-[0.98]">
                    Create Rule
                </button>
                <label class="flex items-center gap-2 text-sm font-bold text-neutral-700 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="accent-emerald-500 w-4 h-4">
                    Active immediately
                </label>
            </div>
        </form>
    </div>

    {{-- ── Rules Table ───────────────────────────────────────────── --}}
    @if($rules->count())
    <div class="bg-white rounded-3xl border border-neutral-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-neutral-100">
            <p class="text-sm font-black uppercase tracking-widest text-neutral-400">{{ $rules->count() }} Rule(s) Configured</p>
        </div>
        <div class="divide-y divide-neutral-100">
            @foreach($rules as $rule)
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-6 py-5 hover:bg-neutral-50 transition-colors">
                <div class="flex items-center gap-4">
                    {{-- Type badge --}}
                    <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg border {{ $rule->typeBadge() }}">
                        {{ $rule->typeLabel() }}
                    </span>
                    <div>
                        <p class="font-bold text-neutral-900 text-sm">{{ $rule->name }}</p>
                        <p class="text-xs text-neutral-500 mt-0.5">
                            @if($rule->type === 'peak_hour' && $rule->conditions)
                                {{ $rule->conditions['start_time'] ?? '?' }} – {{ $rule->conditions['end_time'] ?? '?' }}
                                · Days: {{ implode(', ', array_map(fn($d) => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$d] ?? $d, $rule->conditions['days'] ?? [])) }}
                            @elseif($rule->type === 'festival' && $rule->conditions)
                                Date: {{ $rule->conditions['date'] ?? '—' }}
                            @elseif($rule->type === 'demand_based' && $rule->conditions)
                                Triggers at ratio ≥ {{ $rule->conditions['ratio_threshold'] ?? '2.0' }}
                            @else
                                Always active while enabled
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap md:flex-nowrap">
                    {{-- Multiplier bubble --}}
                    <span class="px-4 py-1.5 bg-amber-100 text-amber-800 font-black text-lg rounded-xl border border-amber-200">
                        {{ $rule->multiplier }}×
                    </span>

                    {{-- Toggle Switch --}}
                    <button data-rule-id="{{ $rule->id }}"
                            data-toggle-url="{{ route('admin.surge-rules.toggle', $rule) }}"
                            onclick="toggleRule(this)"
                            class="toggle-btn relative inline-flex h-6 w-11 rounded-full transition-colors duration-200 ease-in-out focus:outline-none
                                   {{ $rule->is_active ? 'bg-emerald-500' : 'bg-neutral-300' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow-md transform transition duration-200 ease-in-out
                                     {{ $rule->is_active ? 'translate-x-5' : 'translate-x-0.5' }} mt-0.5"></span>
                    </button>
                    <span class="text-xs font-bold {{ $rule->is_active ? 'text-emerald-600' : 'text-neutral-400' }}" id="toggle-label-{{ $rule->id }}">
                        {{ $rule->is_active ? 'Active' : 'Inactive' }}
                    </span>

                    {{-- Delete --}}
                    <form action="{{ route('admin.surge-rules.destroy', $rule) }}" method="POST"
                          onsubmit="return confirm('Delete rule \'{{ $rule->name }}\'?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-3xl border border-neutral-200 p-16 text-center">
        <p class="text-5xl mb-4">⚡</p>
        <h3 class="font-black font-heading text-neutral-800 text-xl mb-2">No Surge Rules Yet</h3>
        <p class="text-sm text-neutral-500 max-w-xs mx-auto">Create your first rule above to start dynamically adjusting pricing based on time, demand, or events.</p>
    </div>
    @endif

    {{-- Help card --}}
    <div class="bg-white border border-neutral-200 rounded-2xl p-6 shadow-sm">

    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-100 text-amber-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>

        <h3 class="font-bold text-neutral-900 text-sm tracking-wide">
            How Surge Pricing Works
        </h3>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm text-neutral-600">
        <div class="flex gap-3">
            <span class="text-neutral-400">⏱</span>
            <p>
                <span class="font-semibold text-neutral-800">Peak Hour</span>
                activates during predefined high-traffic periods such as weekday rush hours.
            </p>
        </div>

        <div class="flex gap-3">
            <span class="text-neutral-400">📅</span>
            <p>
                <span class="font-semibold text-neutral-800">Festival</span>
                applies on specific calendar dates like Diwali or New Year’s Eve.
            </p>
        </div>

        <div class="flex gap-3">
            <span class="text-neutral-400">📈</span>
            <p>
                <span class="font-semibold text-neutral-800">Demand Based</span>
                triggers automatically when rider demand exceeds available drivers.
            </p>
        </div>

        <div class="flex gap-3">
            <span class="text-neutral-400">⚙</span>
            <p>
                <span class="font-semibold text-neutral-800">Manual Override</span>
                allows administrators to enforce surge pricing manually during special events.
            </p>
        </div>

    </div>

    <div class="mt-6 pt-4 border-t border-neutral-100 text-xs text-neutral-500">
        If multiple surge rules are active simultaneously, the
        <span class="font-semibold text-neutral-800">highest multiplier is applied</span>.
        Surge multipliers are not combined.
    </div>

</div>

</div>
@endsection

@section('scripts')
<script>
    function surgeManager() {
        return { showForm: false, form: { type: '' } };
    }

    async function toggleRule(btn) {
        const url    = btn.dataset.toggleUrl;
        const ruleId = btn.dataset.ruleId;
        const token  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const res  = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
            });
            const data = await res.json();

            // Update toggle button
            if (data.is_active) {
                btn.classList.replace('bg-neutral-300', 'bg-emerald-500');
                btn.firstElementChild.classList.replace('translate-x-0.5', 'translate-x-5');
            } else {
                btn.classList.replace('bg-emerald-500', 'bg-neutral-300');
                btn.firstElementChild.classList.replace('translate-x-5', 'translate-x-0.5');
            }
            const label = document.getElementById('toggle-label-' + ruleId);
            if (label) {
                label.textContent = data.is_active ? 'Active' : 'Inactive';
                label.className = `text-xs font-bold ${data.is_active ? 'text-emerald-600' : 'text-neutral-400'}`;
            }
        } catch(e) { console.error('Toggle failed', e); }
    }
</script>
@endsection
