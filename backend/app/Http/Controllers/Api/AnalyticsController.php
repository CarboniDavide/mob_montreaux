<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * GET /api/v1/stats/distances
     * Returns aggregated distances grouped by analytic code and optionally grouped by time.
     */
    public function distances(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $groupBy = $request->query('groupBy', 'none');

        $q = TrainRoute::query();

        if ($from) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $q->whereDate('created_at', '<=', $to);
        }

        // Depending on groupBy we group by date expression
        $selects = [];
        $items = [];

        if ($groupBy === 'day') {
            $q->select(
                DB::raw("DATE(created_at) as period"),
                'analytic_code',
                DB::raw('SUM(distance_km) as total')
            )->groupBy('period', 'analytic_code');
        } elseif ($groupBy === 'month') {
            $q->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                'analytic_code',
                DB::raw('SUM(distance_km) as total')
            )->groupBy('period', 'analytic_code');
        } elseif ($groupBy === 'year') {
            $q->select(
                DB::raw("DATE_FORMAT(created_at, '%Y') as period"),
                'analytic_code',
                DB::raw('SUM(distance_km) as total')
            )->groupBy('period', 'analytic_code');
        } else {
            // none — single aggregate per analytic_code
            $q->select('analytic_code', DB::raw('SUM(distance_km) as total'))->groupBy('analytic_code');
        }

        $results = $q->get();

        $responseItems = [];
        foreach ($results as $r) {
            $responseItems[] = [
                'analyticCode' => $r->analytic_code,
                'totalDistanceKm' => (float) $r->total,
                'periodStart' => $from ?? null,
                'periodEnd' => $to ?? null,
                'group' => $groupBy === 'none' ? null : ($r->period ?? null),
            ];
        }

        return response()->json([
            'from' => $from ?? null,
            'to' => $to ?? null,
            'groupBy' => $groupBy,
            'items' => $responseItems,
        ]);
    }
}
