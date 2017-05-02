<?php

namespace Tests\Feature;

use App\Controlunit;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;

/**
 * Class ValveTest
 * @package Tests\Feature
 */
class ValveTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/valves');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_Index401()
    {
        $response = $this->json('GET', '/api/v1/valves');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_Store401()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/valves', [
            'name' => 'TestValve01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();
    }

    /**
     *
     */
    public function test_200_Index200()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('GET', '/api/v1/valves', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_200_Store200()
    {
        $token = $this->createUserFullPermissions(false);

        $response = $this->post('/api/v1/valves', [
            'name' => 'TestValve01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_300_Update401()
    {
        $token = $this->createUserReadOnly(false);

        $valve_id = Valve::first()->id;

        $response = $this->put('/api/v1/valves/' . $valve_id, [
            'name' => 'TestValve01_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    public function test_400_Update422()
    {
        $token = $this->createUserFullPermissions(false);

        $valve_id = Valve::first()->id;
        
        $response = $this->put('/api/v1/valves/' . $valve_id, [
            'name' => 'TestValve01_Updated',
            'terrarium' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/valves/' . $valve_id, [
            'name' => 'TestValve01_Updated',
            'pump' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/valves/' . $valve_id, [
            'name' => 'TestValve01_Updated',
            'controlunit' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);
    }

    /**
     *
     */
    public function test_400_Update200()
    {
        $token = $this->createUserFullPermissions(false);

        $valve_id = Valve::first()->id;

        $terrarium = Terrarium::create([
            'name' => 'ValveTerrarium01',
            'display_name' => 'ValveTerrarium01'
        ]);
        $pump = Pump::create([
            'name' => 'ValvePump01'
        ]);
        $controlunit = Controlunit::create([
            'name' => 'ValveControlunit01'
        ]);

        $response = $this->put('/api/v1/valves/' . $valve_id, [
            'name' => 'TestValve01_Updated',
            'pump' => $pump->id,
            'terrarium' => $terrarium->id,
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/valves/' . $valve_id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $valve_id,
                'name' => 'TestValve01_Updated',
                'terrarium' => [
                    'id' => $terrarium->id
                ],
                'pump' => [
                    'id' => $pump->id
                ],
                'controlunit' => [
                    'id' => $controlunit->id
                ]
            ]
        ]);

        $terrarium->delete();
        $pump->delete();
        $controlunit->delete();

    }

    /**
     *
     */
    public function test_500_Delete401()
    {
        $token = $this->createUserReadOnly(false);

        $valve_id = Valve::first()->id;

        $response = $this->delete('/api/v1/valves/' . $valve_id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_600_Delete200()
    {
        $token = $this->createUserFullPermissions(false);

        $valve_id = Valve::first()->id;

        $response = $this->delete('/api/v1/valves/' . $valve_id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/valves/' . $valve_id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
