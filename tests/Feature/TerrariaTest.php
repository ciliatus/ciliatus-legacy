<?php

namespace Tests\Feature;

use App\Animal;
use App\Controlunit;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;

/**
 * Class TerrariumTest
 * @package Tests\Feature
 */
class TerrariumTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/terraria');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/terraria');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/terraria', [
            'name' => 'TestTerrarium01'
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

        $response = $this->json('GET', '/api/v1/terraria', [], [
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

        $response = $this->post('/api/v1/terraria', [
            'display_name' => 'TestTerrarium01'
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

        $response = $this->get('/api/v1/terraria/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'display_name' => 'TestTerrarium01'
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

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'name' => 'TestTerrarium01_Updated'
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

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'name' => 'TestTerrarium01_Updated',
            'animals' => ['doesnotexist'],
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

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $animal1 = Animal::create(['display_name' => 'Animal01']);
        $animal2 = Animal::create(['display_name' => 'Animal02']);
        $animal3 = Animal::create(['display_name' => 'Animal03']);
        $valve1 = Valve::create(['name' => 'Valve01']);
        $valve2 = Valve::create(['name' => 'Valve02']);
        $valve3 = Valve::create(['name' => 'Valve03']);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'name' => 'TestTerrarium01_Updated',
            'valves' => [
                $valve1->id, $valve2->id
            ],
            'animals' => [
                $animal1->id, $animal2->id
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'valves' => [
                $valve3->id
            ],
            'animals' => [
                $animal3->id
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $terrarium->id,
                'name' => 'TestTerrarium01_Updated',
                'valves' => [
                    [
                        'id' => $valve3->id
                    ]
                ],
                'animals' => [
                    [
                        'id' => $animal3->id
                    ]
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

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $terrarium->id,
                'display_name' => $terrarium->display_name
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->delete('/api/v1/terraria/' . $terrarium->id, [], [
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

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->delete('/api/v1/terraria/' . $terrarium->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
