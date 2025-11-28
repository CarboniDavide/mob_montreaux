<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StationsTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);
        return $token;
    }

    public function test_authenticated_user_can_list_all_stations()
    {
        $token = $this->authenticatedUser();

        Station::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_authenticated_user_can_search_stations()
    {
        $token = $this->authenticatedUser();

        Station::factory()->create(['short_name' => 'MX', 'long_name' => 'Montreux']);
        Station::factory()->create(['short_name' => 'GE', 'long_name' => 'Geneva']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations?search=Mont');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['short_name' => 'MX']);
    }

    public function test_authenticated_user_can_get_station_by_id()
    {
        $token = $this->authenticatedUser();

        $station = Station::factory()->create(['short_name' => 'MX']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations/' . $station->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $station->id,
                'short_name' => 'MX',
            ]);
    }

    public function test_get_station_returns_404_for_nonexistent_id()
    {
        $token = $this->authenticatedUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations/99999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Station not found']);
    }

    public function test_authenticated_user_can_get_station_by_short_name()
    {
        $token = $this->authenticatedUser();

        $station = Station::factory()->create(['short_name' => 'MX']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations/p/MX');

        $response->assertStatus(200)
            ->assertJson([
                'short_name' => 'MX',
            ]);
    }

    public function test_get_station_by_short_returns_404_for_nonexistent()
    {
        $token = $this->authenticatedUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations/p/UNKNOWN');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Station not found']);
    }

    public function test_unauthenticated_user_cannot_access_stations()
    {
        $response = $this->getJson('/api/v1/stations');

        $response->assertStatus(401);
    }

    public function test_stations_can_be_paginated()
    {
        $token = $this->authenticatedUser();

        Station::factory()->count(100)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/stations?paginate=true&per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'current_page',
                'per_page',
                'total',
            ])
            ->assertJsonCount(10, 'data');
    }
}
