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
 * Class LogicalSensorShowOkTest
 * @package Tests\Feature
 */
class LogicalSensorShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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

        $logical_sensor->delete();

        $this->cleanupUsers();

    }

}
