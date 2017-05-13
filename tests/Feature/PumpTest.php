<?php

namespace Tests\Feature;

use App\Controlunit;
use App\Pump;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;

/**
 * Class PumpTest
 * @package Tests\Feature
 */
class PumpTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/pumps');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/pumps');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/pumps', [
            'name' => 'TestPump01'
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

        $response = $this->json('GET', '/api/v1/pumps', [], [
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

        $response = $this->post('/api/v1/pumps', [
            'name' => 'TestPump01'
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

        $response = $this->get('/api/v1/pumps/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestPump01'
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

        $pump = Pump::create([
            'name' => 'TestPump01'
        ]);

        $response = $this->put('/api/v1/pumps/' . $pump->id, [
            'name' => 'TestPump01_Updated'
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

        $pump = Pump::create([
            'name' => 'TestPump01'
        ]);

        $response = $this->put('/api/v1/pumps/' . $pump->id, [
            'name' => 'TestPump01_Updated',
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

        $pump = Pump::create([
            'name' => 'Pump01'
        ]);
        $controlunit = Controlunit::create([
            'name' => 'PumpControlunit01'
        ]);

        $response = $this->put('/api/v1/pumps/' . $pump->id, [
            'name' => 'TestPump01_Updated',
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/pumps/' . $pump->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $pump->id,
                'name' => 'TestPump01_Updated',
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

        $pump = Pump::create([
            'name' => 'TestPump01'
        ]);

        $response = $this->get('/api/v1/pumps/' . $pump->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $pump->id,
                'name' => $pump->name
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $pump = Pump::create([
            'name' => 'Pump01'
        ]);

        $response = $this->delete('/api/v1/pumps/' . $pump->id, [], [
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

        $pump = Pump::create([
            'name' => 'Pump01'
        ]);

        $response = $this->delete('/api/v1/pumps/' . $pump->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/pumps/' . $pump->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
