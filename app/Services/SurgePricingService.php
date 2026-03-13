<?php

namespace App\Services;

use App\Models\SurgeRule;
use Carbon\Carbon;

class SurgePricingService
{
    /**
     * Returns the highest applicable surge multiplier for the current moment.
     * Accepts optional supply/demand counts for demand_based rule evaluation.
     *
     * @param  int|null  $availableDrivers
     * @param  int|null  $activeRiders
     * @return array{multiplier: float, rule_name: string|null}
     */
    public function getActiveMultiplier(?int $availableDrivers = null, ?int $activeRiders = null): array
    {
        $rules = SurgeRule::active()->orderByDesc('multiplier')->get();

        $bestMultiplier = 1.00;
        $bestRuleName   = null;
        $now            = Carbon::now();

        foreach ($rules as $rule) {
            if (!$this->ruleApplies($rule, $now, $availableDrivers, $activeRiders)) {
                continue;
            }

            if ((float) $rule->multiplier > $bestMultiplier) {
                $bestMultiplier = (float) $rule->multiplier;
                $bestRuleName   = $rule->name;
            }
        }

        return [
            'multiplier' => $bestMultiplier,
            'rule_name'  => $bestRuleName,
        ];
    }

    private function ruleApplies(SurgeRule $rule, Carbon $now, ?int $availableDrivers, ?int $activeRiders): bool
    {
        // Check global starts_at / ends_at window first
        if ($rule->starts_at && $now->isBefore($rule->starts_at)) return false;
        if ($rule->ends_at   && $now->isAfter($rule->ends_at))    return false;

        $conditions = $rule->conditions ?? [];

        return match($rule->type) {
            'manual_override' => true,                           // always on if active

            'peak_hour'       => $this->checkPeakHour($conditions, $now),

            'festival'        => $this->checkFestival($conditions, $now),

            'demand_based'    => $this->checkDemandBased($conditions, $availableDrivers, $activeRiders),

            default           => false,
        };
    }

    /**
     * conditions: { days: [1..7], start_time: "HH:MM", end_time: "HH:MM" }
     * days follow Carbon: 0=Sunday … 6=Saturday
     */
    private function checkPeakHour(array $conditions, Carbon $now): bool
    {
        $days      = $conditions['days']       ?? range(0, 6);
        $startTime = $conditions['start_time'] ?? '00:00';
        $endTime   = $conditions['end_time']   ?? '23:59';

        if (!in_array($now->dayOfWeek, array_map('intval', $days))) {
            return false;
        }

        $todayStart = Carbon::createFromFormat('Y-m-d H:i', $now->toDateString() . ' ' . $startTime);
        $todayEnd   = Carbon::createFromFormat('Y-m-d H:i', $now->toDateString() . ' ' . $endTime);

        return $now->between($todayStart, $todayEnd);
    }

    /**
     * conditions: { date: "YYYY-MM-DD" }  OR  { dates: ["YYYY-MM-DD", ...] }
     */
    private function checkFestival(array $conditions, Carbon $now): bool
    {
        $today = $now->toDateString();

        if (isset($conditions['date'])) {
            return $conditions['date'] === $today;
        }

        if (isset($conditions['dates'])) {
            return in_array($today, $conditions['dates']);
        }

        return false;
    }

    /**
     * conditions: { ratio_threshold: 2.0 }
     * Fires when (activeRiders / availableDrivers) >= threshold
     */
    private function checkDemandBased(array $conditions, ?int $availableDrivers, ?int $activeRiders): bool
    {
        if ($availableDrivers === null || $activeRiders === null) return false;
        if ($availableDrivers === 0) return $activeRiders > 0;   // infinite ratio

        $threshold = (float) ($conditions['ratio_threshold'] ?? 2.0);
        $ratio     = $activeRiders / $availableDrivers;

        return $ratio >= $threshold;
    }
}
