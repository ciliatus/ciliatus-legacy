<?php

namespace Tests\Feature\LogicalSensor;

use App\LogicalSensor;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorUpdateOkTest
 * @package Tests\Feature
 */
class LogicalSensorUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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

        $logical_sensor->delete();
        $physical_sensor->delete();

        $this->cleanupUsers();

    }

}
