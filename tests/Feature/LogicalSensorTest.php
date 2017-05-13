<?php

namespace Tests\Feature;

use App\Controlunit;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\LogicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;

/**
 * Class LogicalSensorTest
 * @package Tests\Feature
 */
class LogicalSensorTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/logical_sensors');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/logical_sensors');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/logical_sensors', [
            'name' => 'TestLogicalSensor01'
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

        $response = $this->json('GET', '/api/v1/logical_sensors', [], [
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

        $physical_sensor = PhysicalSensor::create([
            'name' => 'LogicalSensorPhysicalSensor01'
        ]);

        $response = $this->post('/api/v1/logical_sensors', [
            'name' => 'TestLogicalSensor01',
            'physical_sensor' => $physical_sensor->id
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

        $response = $this->get('/api/v1/logical_sensors/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestLogicalSensor01',
                'physical_sensor' => [
                    'id' => $physical_sensor->id
                ]
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
            'name' => 'LogicalSensorPhysicalSensor01'
        ]);

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01',
            'physical_sensor_id' => $physical_sensor->id
        ]);

        $response = $this->put('/api/v1/logical_sensors/' . $logical_sensor->id, [
            'name' => 'TestLogicalSensor01_Updated'
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
            'name' => 'LogicalSensorPhysicalSensor01'
        ]);

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01',
            'physical_sensor_id' => $physical_sensor->id
        ]);

        $response = $this->put('/api/v1/logical_sensors/' . $logical_sensor->id, [
            'name' => 'TestLogicalSensor01_Updated',
            'physical_sensor' => 'doesnotexist',
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

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01'
        ]);

        $physical_sensor = PhysicalSensor::create([
            'name' => 'LogicalSensorPhysicalSensor01'
        ]);

        $response = $this->put('/api/v1/logical_sensors/' . $logical_sensor->id, [
            'name' => 'TestLogicalSensor01_Updated',
            'physical_sensor' => $physical_sensor->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/logical_sensors/' . $logical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $logical_sensor->id,
                'name' => 'TestLogicalSensor01_Updated',
                'physical_sensor' => [
                    'id' => $physical_sensor->id
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

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01'
        ]);

        $response = $this->get('/api/v1/logical_sensors/' . $logical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $logical_sensor->id,
                'name' => $logical_sensor->name
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01'
        ]);

        $response = $this->delete('/api/v1/logical_sensors/' . $logical_sensor->id, [], [
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

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01'
        ]);

        $response = $this->delete('/api/v1/logical_sensors/' . $logical_sensor->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/logical_sensors/' . $logical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
