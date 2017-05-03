<?php

namespace Tests\Feature;

use App\LogicalSensor;
use App\PhysicalSensor;
use App\Sensorreading;
use Tests\CiliatusCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Webpatser\Uuid\Uuid;

/**
 * Class SensorreadingTest
 * @package Tests\Feature
 */
class SensorreadingTest extends CiliatusCase
{
    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/sensorreadings');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/sensorreadings');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/sensorreadings', [], [
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

        $response = $this->json('GET', '/api/v1/sensorreadings', [], [
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
    public function test_200_StoreError()
    {
        $token = $this->createUserFullPermissions(false);

        $ps = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'TestLogicalSensor01',
            'physical_sensor_id' => $ps->id
        ]);
        $ls->rawvalue_lowerlimit = 0;
        $ls->rawvalue_upperlimit = 30;


        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => Uuid::generate()->string,
            'rawvalue' => 15.4
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 15.4
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => $ls->id,
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 31
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => -5
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_200_StoreOk()
    {
        $token = $this->createUserFullPermissions(false);

        $ps = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);
        $ls = LogicalSensor::create([
            'name' => 'TestLogicalSensor01',
            'physical_sensor_id' => $ps->id
        ]);
        $ls->rawvalue_lowerlimit = 0;
        $ls->rawvalue_upperlimit = 30;
        $ls->save();

        $group_id = Uuid::generate()->string;

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => $group_id,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 15.4
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

        $response = $this->get('/api/v1/sensorreadings/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'group_id' => $group_id,
                'logical_sensor_id' => $ls->id,
                'rawvalue' => 15.4
            ]
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_500_ShowOk()
    {
        $token = $this->createUserReadOnly(false);

        $ps = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $ls = LogicalSensor::create([
            'name' => 'TestLogicalSensor01',
            'physical_sensor_id' => $ps->id
        ]);
        $ls->rawvalue_lowerlimit = 0;
        $ls->rawvalue_upperlimit = 30;
        $ls->save();

        $group_id = Uuid::generate()->string;

        $sr = Sensorreading::create([
            'sensorreadinggroup_id' => $group_id,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 15.4
        ]);

        $response = $this->get('/api/v1/sensorreadings/' . $sr->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $sr->id,
                'group_id' => $group_id,
                'logical_sensor_id' => $ls->id,
                'rawvalue' => 15.4
            ]
        ]);
    }
}
