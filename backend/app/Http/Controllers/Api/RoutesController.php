<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distance;
use App\Models\TrainRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoutesController extends Controller
{
    /**
     * Create a route (shortest path) between two station short codes and persist it.
     * Uses Dijkstra over the distances graph.
     */
    public function store(Request $request)
    {
        $payload = $request->validate([
            'fromStationId' => 'required|string',
            'toStationId' => 'required|string',
            'analyticCode' => 'required|string',
        ]);

        $from = $payload['fromStationId'];
        $to = $payload['toStationId'];

        if ($from === $to) {
            return response()->json(['message' => 'from and to must be different'], 422);
        }

        // Build graph in memory: nodes = short codes, edges with distance
        $edges = DB::table('distances')
            ->select('parent_short_name', 'child_short_name', 'distance')
            ->get();

        $graph = [];
        foreach ($edges as $e) {
            $u = $e->parent_short_name;
            $v = $e->child_short_name;
            $d = (float) $e->distance;
            $graph[$u][] = ['node' => $v, 'w' => $d];
            // assume bidirectional (many networks are undirected in practice)
            $graph[$v][] = ['node' => $u, 'w' => $d];
        }

        if (! isset($graph[$from]) || ! isset($graph[$to])) {
            return response()->json(['message' => 'One or both stations unknown in distances graph'], 422);
        }

        // Dijkstra
        [$distances, $previous] = $this->dijkstra($graph, $from);

        if (! isset($distances[$to]) || $distances[$to] === INF) {
            return response()->json(['message' => 'No path found between stations (network may be disconnected)'], 422);
        }

        // reconstruct path
        $path = [];
        $u = $to;
        while ($u) {
            array_unshift($path, $u);
            $u = $previous[$u] ?? null;
        }

        $distanceKm = $distances[$to];

        // persist
        $route = TrainRoute::create([
            'from_station_short' => $from,
            'to_station_short' => $to,
            'analytic_code' => $payload['analyticCode'],
            'distance_km' => $distanceKm,
            'path' => $path,
        ]);

        return response()->json([
            'id' => $route->id,
            'fromStationId' => $from,
            'toStationId' => $to,
            'analyticCode' => $payload['analyticCode'],
            'distanceKm' => $route->distance_km,
            'path' => $route->path,
            'createdAt' => $route->created_at->toDateTimeString(),
        ], 201);
    }

    /**
     * Dijkstra implementation: returns [distances, previous]
     */
    protected function dijkstra(array $graph, string $source): array
    {
        $dist = [];
        $prev = [];
        $Q = [];

        foreach (array_keys($graph) as $v) {
            $dist[$v] = INF;
            $prev[$v] = null;
            $Q[$v] = true;
        }

        $dist[$source] = 0;

        while (! empty($Q)) {
            // extract min
            $u = null;
            $min = INF;
            foreach ($Q as $node => $flag) {
                if ($dist[$node] < $min) {
                    $min = $dist[$node];
                    $u = $node;
                }
            }

            if ($u === null) break;

            unset($Q[$u]);

            if ($min === INF) break;

            foreach ($graph[$u] as $edge) {
                $v = $edge['node'];
                $alt = $dist[$u] + $edge['w'];
                if ($alt < ($dist[$v] ?? INF)) {
                    $dist[$v] = $alt;
                    $prev[$v] = $u;
                }
            }
        }

        return [$dist, $prev];
    }
}
