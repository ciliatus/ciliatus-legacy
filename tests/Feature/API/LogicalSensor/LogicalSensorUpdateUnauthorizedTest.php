<?php

namespace Tests\Feature\API\LogicalSensor;

use App\LogicalSensor;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class LogicalSensorUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $logical_sensor->delete();
        $physical_sensor->delete();

        $this->cleanupUsers();

    }

}
