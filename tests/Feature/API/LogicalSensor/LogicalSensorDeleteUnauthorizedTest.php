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
 * Class LogicalSensorDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class LogicalSensorDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01'
        ]);

        $response = $this->delete('/api/v1/logical_sensors/' . $logical_sensor->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $logical_sensor->delete();

        $this->cleanupUsers();

    }

}
