<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Distance;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DistanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_distance_can_be_created()
    {
        // Create stations first to satisfy foreign key constraints
        Station::create([
            'id' => 1,
            'short_name' => 'MX',
            'long_name' => 'Montreux',
        ]);

        Station::create([
            'id' => 2,
            'short_name' => 'VV',
            'long_name' => 'Vevey',
        ]);

        $distance = Distance::create([
            'network' => 'SBB',
            'parent_short_name' => 'MX',
            'child_short_name' => 'VV',
            'parent_station_id' => 1,
            'child_station_id' => 2,
            'distance' => 12.5,
        ]);

        $this->assertInstanceOf(Distance::class, $distance);
        $this->assertEquals('SBB', $distance->network);
        $this->assertEquals('MX', $distance->parent_short_name);
        $this->assertEquals('VV', $distance->child_short_name);
        $this->assertEquals(12.5, $distance->distance);
    }

    public function test_distance_has_correct_fillable_fields()
    {
        $distance = new Distance();

        $this->assertEquals(
            [
                'network',
                'parent_short_name',
                'child_short_name',
                'parent_station_id',
                'child_station_id',
                'distance',
            ],
            $distance->getFillable()
        );
    }

    public function test_distance_has_parent_station_relationship()
    {
        $parentStation = Station::create([
            'id' => 1,
            'short_name' => 'MX',
            'long_name' => 'Montreux',
        ]);

        Station::create([
            'id' => 2,
            'short_name' => 'VV',
            'long_name' => 'Vevey',
        ]);

        $distance = Distance::create([
            'parent_short_name' => 'MX',
            'child_short_name' => 'VV',
            'parent_station_id' => 1,
            'child_station_id' => 2,
            'distance' => 10,
        ]);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $distance->parentStation()
        );

        $this->assertEquals($parentStation->id, $distance->parentStation->id);
    }

    public function test_distance_has_child_station_relationship()
    {
        Station::create([
            'id' => 1,
            'short_name' => 'MX',
            'long_name' => 'Montreux',
        ]);

        $childStation = Station::create([
            'id' => 2,
            'short_name' => 'VV',
            'long_name' => 'Vevey',
        ]);

        $distance = Distance::create([
            'parent_short_name' => 'MX',
            'child_short_name' => 'VV',
            'parent_station_id' => 1,
            'child_station_id' => 2,
            'distance' => 10,
        ]);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $distance->childStation()
        );

        $this->assertEquals($childStation->id, $distance->childStation->id);
    }
}
