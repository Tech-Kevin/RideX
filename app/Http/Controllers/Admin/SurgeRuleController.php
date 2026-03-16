<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DriverStatus;
use App\Http\Controllers\Controller;
use App\Models\SurgeRule;
use App\Models\User;
use App\Models\Ride;
use App\Services\SurgePricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SurgeRuleController extends Controller
{
    public function __construct(private SurgePricingService $surgeService) {}

    public function index(): View
    {
        $rules = SurgeRule::orderByDesc('priority')->orderByDesc('created_at')->get();
        return view('admin.surge.index', compact('rules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'type'       => 'required|in:peak_hour,festival,demand_based,manual_override',
            'multiplier' => 'required|numeric|min:1.0|max:10.0',
            'is_active'  => 'boolean',
            'priority'   => 'integer|min:0|max:100',
            'starts_at'  => 'nullable|date',
            'ends_at'    => 'nullable|date|after_or_equal:starts_at',
            // Typed condition fields (assembled into JSON below)
            'days'              => 'nullable|array',
            'days.*'            => 'integer|min:0|max:6',
            'start_time'        => 'nullable|date_format:H:i',
            'end_time'          => 'nullable|date_format:H:i',
            'festival_date'     => 'nullable|date',
            'ratio_threshold'   => 'nullable|numeric|min:1.0',
        ]);

        $conditions = $this->buildConditions($data);

        SurgeRule::create([
            'name'       => $data['name'],
            'type'       => $data['type'],
            'multiplier' => $data['multiplier'],
            'is_active'  => $data['is_active'] ?? true,
            'priority'   => $data['priority'] ?? 0,
            'starts_at'  => $data['starts_at'] ?? null,
            'ends_at'    => $data['ends_at'] ?? null,
            'conditions' => $conditions,
        ]);

        return redirect()->route('admin.surge-rules.index')->with('success', 'Surge rule created successfully.');
    }

    public function update(Request $request, SurgeRule $surgeRule): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'type'       => 'required|in:peak_hour,festival,demand_based,manual_override',
            'multiplier' => 'required|numeric|min:1.0|max:10.0',
            'priority'   => 'integer|min:0|max:100',
            'starts_at'  => 'nullable|date',
            'ends_at'    => 'nullable|date|after_or_equal:starts_at',
            'days'            => 'nullable|array',
            'days.*'          => 'integer|min:0|max:6',
            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i',
            'festival_date'   => 'nullable|date',
            'ratio_threshold' => 'nullable|numeric|min:1.0',
        ]);

        $surgeRule->update([
            'name'       => $data['name'],
            'type'       => $data['type'],
            'multiplier' => $data['multiplier'],
            'priority'   => $data['priority'] ?? $surgeRule->priority,
            'starts_at'  => $data['starts_at'] ?? null,
            'ends_at'    => $data['ends_at'] ?? null,
            'conditions' => $this->buildConditions($data),
        ]);

        return redirect()->route('admin.surge-rules.index')->with('success', 'Surge rule updated.');
    }

    public function toggleActive(SurgeRule $surgeRule): JsonResponse
    {
        $surgeRule->update(['is_active' => !$surgeRule->is_active]);
        return response()->json(['is_active' => $surgeRule->is_active]);
    }

    public function destroy(SurgeRule $surgeRule): RedirectResponse
    {
        $surgeRule->delete();
        return redirect()->route('admin.surge-rules.index')->with('success', 'Surge rule deleted.');
    }

    private function buildConditions(array $data): ?array
    {
        return match($data['type']) {
            'peak_hour'    => [
                'days'       => array_map('intval', $data['days'] ?? range(0, 6)),
                'start_time' => $data['start_time'] ?? '00:00',
                'end_time'   => $data['end_time']   ?? '23:59',
            ],
            'festival'     => ['date' => $data['festival_date'] ?? null],
            'demand_based' => ['ratio_threshold' => (float) ($data['ratio_threshold'] ?? 2.0)],
            default        => null,
        };
    }
}