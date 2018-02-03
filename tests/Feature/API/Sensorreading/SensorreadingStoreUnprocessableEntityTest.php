<?php

namespace Tests\Feature\API\Sensorreading;

use App\LogicalSensor;
use App\PhysicalSensor;
use App\Sensorreading;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * class SensorreadingStoreUnprocessableEntityTest
 * @package Tests\Feature
 */
class SensorreadingStoreUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 15.4
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => $ls->id,
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 31
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => Uuid::generate()->string,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => -5
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $ls->delete();
        $ps->delete();

        $this->cleanupUsers();

    }

}
