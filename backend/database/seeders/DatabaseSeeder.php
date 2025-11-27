<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Mob User',
            'email' => 'user@mob.ch',
        ]);

        // seed stations and distances from JSON files
        $this->call([
            StationsSeeder::class,
            DistancesSeeder::class,
        ]);
    }
}
