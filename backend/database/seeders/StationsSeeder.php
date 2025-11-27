<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'stations.json';
        if (! file_exists($path)) {
            $this->command->error("stations.json not found at: {$path}");
            return;
        }

        $json = file_get_contents($path);
        $items = json_decode($json, true) ?: [];

        $now = now();

        foreach ($items as $item) {
            // keep the JSON id as primary key
            DB::table('stations')->updateOrInsert(
                ['id' => $item['id']],
                [
                    'short_name' => $item['shortName'] ?? null,
                    'long_name' => $item['longName'] ?? null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $this->command->info('Stations seeded: ' . count($items));
    }
}
