<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Station;
use App\Models\Distance;

class StationTest extends TestCase
{

    public function test_station_can_be_created()
    {
        $station = Station::create([
            'id' => 1,
            'short_name' => 'MX',
            'long_name' => 'Montreux',
        ]);

        $this->assertInstanceOf(Station::class, $station);
        $this->assertEquals('MX', $station->short_name);
        $this->assertEquals('Montreux', $station->long_name);
    }

    public function test_station_has_correct_fillable_fields()
    {
        $station = new Station();

        $this->assertEquals(
            ['id', 'short_name', 'long_name'],
            $station->getFillable()
        );
    }

    public function test_station_id_is_not_auto_incrementing()
    {
        $station = new Station();

        $this->assertFalse($station->incrementing);
        $this->assertEquals('int', $station->getKeyType());
    }

    public function test_station_has_parent_distances_relationship()
    {
        $station = Station::create([
            'id' => 1,
            'short_name' => 'MX',
            'long_name' => 'Montreux',
        ]);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $station->parentDistances()
        );
    }

    public function test_station_has_child_distances_relationship()
    {
        $station = Station::create([
            'id' => 1,
            'short_name' => 'MX',
            'long_name' => 'Montreux',
        ]);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $station->childDistances()
        );
    }
}
