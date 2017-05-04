<?php

namespace Tests\Feature;

use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\LogicalSensorThreshold;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;

/**
 * Class LogicalSensorThresholdTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/logical_sensor_thresholds');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/logical_sensor_thresholds');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/logical_sensor_thresholds', [
            'name' => 'TestLogicalSensorThreshold01'
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

        $response = $this->json('GET', '/api/v1/logical_sensor_thresholds', [], [
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

        $ps = PhysicalSensor::create([
            'name' => 'PhysicalSensorTest01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LogicalSensorTest01',
            'physical_sensor_id' => $ps->id
        ]);

        $response = $this->post('/api/v1/logical_sensor_thresholds', [
            'logical_sensor' => $ls->id,
            'starts_at' => '00:00:00',
            'lowerlimit' => 10,
            'upperlimit' => 20,
            'active' => true
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

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'logical_sensor_id' => $ls->id,
                'rawvalue_lowerlimit' => 10,
                'rawvalue_upperlimit' => 20,
                'timestamps' => [
                    'starts' => '00:00:00'
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

        $logical_sensor_threshold = LogicalSensorThreshold::create();

        $response = $this->put('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [], [
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

        $logical_sensor_threshold = LogicalSensorThreshold::create();

        $response = $this->put('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'logical_sensor' => 'doesnotexist',
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

        $ps = PhysicalSensor::create([
            'name' => 'PhysicalSensorTest01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LogicalSensorTest01',
            'physical_sensor_id' => $ps->id
        ]);

        $logical_sensor_threshold = LogicalSensorThreshold::create([
            'logical_sensor' => $ls->id,
            'starts_at' => '01:00:00',
            'rawvalue_lowerlimit' => 10,
            'rawvalue_upperlimit' => 20,
            'active' => true
        ]);

        $ps = PhysicalSensor::create([
            'name' => 'PhysicalSensorTest02'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LogicalSensorTest02',
            'physical_sensor_id' => $ps->id
        ]);

        $response = $this->put('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'logical_sensor' => $ls->id,
            'starts_at' => '00:00:00',
            'lowerlimit' => 15,
            'upperlimit' => 25,
            'active' => true
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $logical_sensor_threshold->id,
                'logical_sensor_id' => $ls->id,
                'rawvalue_lowerlimit' => 15,
                'rawvalue_upperlimit' => 25,
                'active' => true,
                'timestamps' => [
                    'starts' => '00:00:00'
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

        $ps = PhysicalSensor::create([
            'name' => 'PhysicalSensorTest01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LogicalSensorTest01',
            'physical_sensor_id' => $ps->id
        ]);

        $logical_sensor_threshold = LogicalSensorThreshold::create([
            'logical_sensor_id' => $ls->id,
            'starts_at' => '00:00:00',
            'rawvalue_lowerlimit' => 10,
            'rawvalue_upperlimit' => 20,
            'active' => true
        ]);

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $logical_sensor_threshold->id,
                'logical_sensor_id' => $ls->id,
                'rawvalue_lowerlimit' => 10,
                'rawvalue_upperlimit' => 20,
                'active' => true,
                'timestamps' => [
                    'starts' => '00:00:00'
                ]
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $ps = PhysicalSensor::create([
            'name' => 'PhysicalSensorTest01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LogicalSensorTest01',
            'physical_sensor_id' => $ps->id
        ]);

        $logical_sensor_threshold = LogicalSensorThreshold::create([
            'logical_sensor_id' => $ls->id,
            'starts_at' => '01:00:00',
            'rawvalue_lowerlimit' => 10,
            'rawvalue_upperlimit' => 20,
            'active' => true
        ]);

        $response = $this->delete('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [], [
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

        $ps = PhysicalSensor::create([
            'name' => 'PhysicalSensorTest01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LogicalSensorTest01',
            'physical_sensor_id' => $ps->id
        ]);

        $logical_sensor_threshold = LogicalSensorThreshold::create([
            'logical_sensor_id' => $ls->id,
            'starts_at' => '01:00:00',
            'rawvalue_lowerlimit' => 10,
            'rawvalue_upperlimit' => 20,
            'active' => true
        ]);

        $response = $this->delete('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
