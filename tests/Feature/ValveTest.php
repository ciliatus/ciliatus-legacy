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
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/valves');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
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
    public function test_200_IndexOk()
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
    public function test_200_StoreOk()
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

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->get('/api/v1/valves/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestValve01'
            ]
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_300_UpdateError()
    {
        $token = $this->createUserReadOnly(false);

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_400_UpdateError()
    {
        $token = $this->createUserFullPermissions(false);

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'terrarium' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'pump' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
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
    public function test_400_UpdateOk()
    {
        $token = $this->createUserFullPermissions(false);

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

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

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'pump' => $pump->id,
            'terrarium' => $terrarium->id,
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/valves/' . $valve->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $valve->id,
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

    }

    /**
     *
     */
    public function test_500_ShowOk()
    {
        $token = $this->createUserReadOnly(false);

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->get('/api/v1/valves/' . $valve->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $valve->id,
                'name' => $valve->name
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->delete('/api/v1/valves/' . $valve->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_600_DeleteOk()
    {
        $token = $this->createUserFullPermissions(false);

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->delete('/api/v1/valves/' . $valve->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/valves/' . $valve->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
