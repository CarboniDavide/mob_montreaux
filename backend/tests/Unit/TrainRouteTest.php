<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TrainRoute;

class TrainRouteTest extends TestCase
{

    public function test_train_route_can_be_created()
    {
        $route = TrainRoute::create([
            'from_station_short' => 'MX',
            'to_station_short' => 'GE',
            'analytic_code' => 'WEB-UI',
            'distance_km' => 85.5,
            'path' => ['MX', 'LS', 'GE'],
        ]);

        $this->assertInstanceOf(TrainRoute::class, $route);
        $this->assertEquals('MX', $route->from_station_short);
        $this->assertEquals('GE', $route->to_station_short);
        $this->assertEquals('WEB-UI', $route->analytic_code);
        $this->assertEquals(85.5, $route->distance_km);
        $this->assertEquals(['MX', 'LS', 'GE'], $route->path);
    }

    public function test_train_route_path_is_cast_to_array()
    {
        $route = TrainRoute::create([
            'from_station_short' => 'A',
            'to_station_short' => 'B',
            'analytic_code' => 'TEST',
            'distance_km' => 10,
            'path' => ['A', 'B'],
        ]);

        $this->assertIsArray($route->path);
        $this->assertCount(2, $route->path);
    }

    public function test_train_route_has_correct_fillable_fields()
    {
        $route = new TrainRoute();

        $this->assertEquals(
            [
                'id',
                'from_station_short',
                'to_station_short',
                'analytic_code',
                'distance_km',
                'path',
            ],
            $route->getFillable()
        );
    }

    public function test_train_route_timestamps_are_enabled()
    {
        $route = TrainRoute::create([
            'from_station_short' => 'A',
            'to_station_short' => 'B',
            'analytic_code' => 'TEST',
            'distance_km' => 10,
            'path' => ['A', 'B'],
        ]);

        $this->assertNotNull($route->created_at);
        $this->assertNotNull($route->updated_at);
    }
}
