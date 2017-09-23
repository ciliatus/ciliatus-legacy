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
 * Class LogicalSensorDeleteOkTest
 * @package Tests\Feature
 */
class LogicalSensorDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLogicalSensor01'
        ]);

        $response = $this->delete('/api/v1/logical_sensors/' . $logical_sensor->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(LogicalSensor::find($logical_sensor->id));

        $this->cleanupUsers();
    }

}
