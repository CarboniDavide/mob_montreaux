<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Station;
use App\Models\Distance;
use App\Models\TrainRoute;

class RoutesTest extends TestCase
{

    protected function authenticatedUser()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);
        return $token;
    }

    protected function setupGraph()
    {
        // Create simple graph: A -- 5 -- B -- 10 -- C
        Distance::create([
            'parent_short_name' => 'A',
            'child_short_name' => 'B',
            'distance' => 5,
        ]);

        Distance::create([
            'parent_short_name' => 'B',
            'child_short_name' => 'C',
            'distance' => 10,
        ]);
    }

    public function test_authenticated_user_can_calculate_route()
    {
        $token = $this->authenticatedUser();
        $this->setupGraph();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/routes', [
            'fromStationId' => 'A',
            'toStationId' => 'C',
            'analyticCode' => 'TEST',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'fromStationId',
                'toStationId',
                'analyticCode',
                'distanceKm',
                'path',
                'createdAt',
            ])
            ->assertJson([
                'fromStationId' => 'A',
                'toStationId' => 'C',
                'analyticCode' => 'TEST',
                'distanceKm' => 15,
                'path' => ['A', 'B', 'C'],
            ]);

        $this->assertDatabaseHas('train_routes', [
            'from_station_short' => 'A',
            'to_station_short' => 'C',
            'analytic_code' => 'TEST',
            'distance_km' => 15,
        ]);
    }

    public function test_route_calculation_fails_when_from_equals_to()
    {
        $token = $this->authenticatedUser();
        $this->setupGraph();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/routes', [
            'fromStationId' => 'A',
            'toStationId' => 'A',
            'analyticCode' => 'TEST',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'from and to must be different']);
    }

    public function test_route_calculation_fails_for_unknown_station()
    {
        $token = $this->authenticatedUser();
        $this->setupGraph();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/routes', [
            'fromStationId' => 'X',
            'toStationId' => 'A',
            'analyticCode' => 'TEST',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'One or both stations unknown in distances graph']);
    }

    public function test_route_calculation_fails_when_no_path_exists()
    {
        $token = $this->authenticatedUser();

        // Create disconnected graph: A -- B   C -- D
        Distance::create([
            'parent_short_name' => 'A',
            'child_short_name' => 'B',
            'distance' => 5,
        ]);

        Distance::create([
            'parent_short_name' => 'C',
            'child_short_name' => 'D',
            'distance' => 8,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/routes', [
            'fromStationId' => 'A',
            'toStationId' => 'D',
            'analyticCode' => 'TEST',
        ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'No path found between stations (network may be disconnected)']);
    }

    public function test_route_calculation_requires_authentication()
    {
        $this->setupGraph();

        $response = $this->postJson('/api/v1/routes', [
            'fromStationId' => 'A',
            'toStationId' => 'C',
            'analyticCode' => 'TEST',
        ]);

        $response->assertStatus(401);
    }

    public function test_route_calculation_validates_required_fields()
    {
        $token = $this->authenticatedUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/routes', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['fromStationId', 'toStationId', 'analyticCode']);
    }

    public function test_dijkstra_finds_shortest_path_in_complex_graph()
    {
        $token = $this->authenticatedUser();

        // Create graph with alternative paths:
        // A -- 10 -- B -- 5 -- C
        // A -- 3 -- D -- 4 -- C
        // Shortest should be A -> D -> C (7)
        Distance::create(['parent_short_name' => 'A', 'child_short_name' => 'B', 'distance' => 10]);
        Distance::create(['parent_short_name' => 'B', 'child_short_name' => 'C', 'distance' => 5]);
        Distance::create(['parent_short_name' => 'A', 'child_short_name' => 'D', 'distance' => 3]);
        Distance::create(['parent_short_name' => 'D', 'child_short_name' => 'C', 'distance' => 4]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v1/routes', [
            'fromStationId' => 'A',
            'toStationId' => 'C',
            'analyticCode' => 'TEST',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'distanceKm' => 7,
                'path' => ['A', 'D', 'C'],
            ]);
    }
}
