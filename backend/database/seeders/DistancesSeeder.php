<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'distances.json';
        if (! file_exists($path)) {
            $this->command->error("distances.json not found at: {$path}");
            return;
        }

        $json = file_get_contents($path);
        $groups = json_decode($json, true) ?: [];

        $now = now();
        $count = 0;

        foreach ($groups as $group) {
            $network = $group['name'] ?? null;
            $rows = $group['distances'] ?? [];

            foreach ($rows as $row) {
                $parent = $row['parent'] ?? null;
                $child = $row['child'] ?? null;
                $distance = isset($row['distance']) ? (float) $row['distance'] : null;

                // try resolve station ids (optional)
                $parentId = DB::table('stations')->where('short_name', $parent)->value('id');
                $childId = DB::table('stations')->where('short_name', $child)->value('id');

                DB::table('distances')->updateOrInsert(
                    [
                        'network' => $network,
                        'parent_short_name' => $parent,
                        'child_short_name' => $child,
                    ],
                    [
                        'parent_station_id' => $parentId,
                        'child_station_id' => $childId,
                        'distance' => $distance,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );

                $count++;
            }
        }

        $this->command->info('Distances seeded: ' . $count);
    }
}
