<?php

namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    protected $model = Station::class;

    public function definition(): array
    {
        $shortName = strtoupper($this->faker->unique()->lexify('???'));
        
        return [
            'id' => $this->faker->unique()->numberBetween(1, 10000),
            'short_name' => $shortName,
            'long_name' => $this->faker->city() . ' Station',
        ];
    }
}
