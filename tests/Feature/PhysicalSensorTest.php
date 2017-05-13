<?php

namespace Tests\Feature;

use App\Controlunit;
use App\Pump;
use App\Terrarium;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;

/**
 * Class PhysicalSensorTest
 * @package Tests\Feature
 */
class PhysicalSensorTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/physical_sensors');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/physical_sensors');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/physical_sensors', [
            'name' => 'TestPhysicalSensor01'
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

        $response = $this->json('GET', '/api/v1/physical_sensors', [], [
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

        $response = $this->post('/api/v1/physical_sensors', [
            'name' => 'TestPhysicalSensor01'
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

        $response = $this->get('/api/v1/physical_sensors/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestPhysicalSensor01'
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

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->put('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'name' => 'TestPhysicalSensor01_Updated'
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

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->put('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'name' => 'TestPhysicalSensor01_Updated',
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

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'PhysicalSensorControlunit01'
        ]);

        $response = $this->put('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'name' => 'TestPhysicalSensor01_Updated',
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $physical_sensor->id,
                'name' => 'TestPhysicalSensor01_Updated',
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

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->get('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $physical_sensor->id,
                'name' => $physical_sensor->name
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->delete('/api/v1/physical_sensors/' . $physical_sensor->id, [], [
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

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->delete('/api/v1/physical_sensors/' . $physical_sensor->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
