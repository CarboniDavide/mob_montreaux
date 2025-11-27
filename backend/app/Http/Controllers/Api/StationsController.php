<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsController extends Controller
{
    public function index(Request $request)
    {
        $q = Station::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $q->where('short_name', 'like', "%{$search}%")
              ->orWhere('long_name', 'like', "%{$search}%");
        }

        // optional pagination
        if ($request->boolean('paginate')) {
            $perPage = (int) $request->input('per_page', 50);
            return $q->orderBy('short_name')->paginate($perPage);
        }

        return $q->orderBy('short_name')->get();
    }

    public function show($id)
    {
        $station = Station::find($id);
        if (! $station) {
            return response()->json(['message' => 'Station not found'], 404);
        }
        return $station;
    }

    public function findByShort($short)
    {
        $station = Station::where('short_name', $short)->first();
        if (! $station) {
            return response()->json(['message' => 'Station not found'], 404);
        }
        return $station;
    }
}
