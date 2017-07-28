<?php

namespace Tests\Feature\API\LogicalSensorThreshold;

use App\LogicalSensor;
use App\LogicalSensorThreshold;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdDeleteOkTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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

        $this->cleanupUsers();
    }

}
