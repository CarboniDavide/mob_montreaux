<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distance;
use Illuminate\Http\Request;

class DistancesController extends Controller
{
    public function index(Request $request)
    {
        $q = Distance::with(['parentStation', 'childStation']);

        if ($request->filled('network')) {
            $q->where('network', $request->input('network'));
        }

        if ($request->filled('from')) {
            $from = $request->input('from');
            $q->where(function ($sub) use ($from) {
                $sub->where('parent_short_name', $from)->orWhere('parent_station_id', $from);
            });
        }

        if ($request->filled('to')) {
            $to = $request->input('to');
            $q->where(function ($sub) use ($to) {
                $sub->where('child_short_name', $to)->orWhere('child_station_id', $to);
            });
        }

        if ($request->boolean('paginate')) {
            $perPage = (int) $request->input('per_page', 50);
            return $q->paginate($perPage);
        }

        return $q->get();
    }

    public function show($id)
    {
        $distance = Distance::with(['parentStation', 'childStation'])->find($id);
        if (! $distance) {
            return response()->json(['message' => 'Distance not found'], 404);
        }
        return $distance;
    }

    /**
     * Return the direct distance between two short codes (parent->child or reverse)
     * query: /api/distance-between?from=MX&to=CGE
     */
    public function between(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        if (! $from || ! $to) {
            return response()->json(['message' => 'from and to parameters are required'], 400);
        }

        // try find direct parent->child
        $row = Distance::where('parent_short_name', $from)->where('child_short_name', $to)->first();
        if (! $row) {
            // try reverse
            $row = Distance::where('parent_short_name', $to)->where('child_short_name', $from)->first();
        }

        if (! $row) {
            return response()->json(['message' => 'Direct distance not found'], 404);
        }

        return response()->json([
            'network' => $row->network,
            'from' => $row->parent_short_name,
            'to' => $row->child_short_name,
            'distance' => (float) $row->distance,
        ]);
    }
}
