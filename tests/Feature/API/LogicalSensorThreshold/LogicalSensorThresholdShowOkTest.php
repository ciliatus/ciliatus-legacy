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
 * Class LogicalSensorThresholdShowOkTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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
            'adjusted_value_lowerlimit' => 10,
            'adjusted_value_upperlimit' => 20,
            'active' => true
        ]);

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $logical_sensor_threshold->id,
                'logical_sensor_id' => $ls->id,
                'adjusted_value_lowerlimit' => 10,
                'adjusted_value_upperlimit' => 20,
                'active' => true,
                'timestamps' => [
                    'starts' => '00:00:00'
                ]
            ]
        ]);

        $logical_sensor_threshold->delete();
        $ls->delete();
        $ps->delete();

        $this->cleanupUsers();

    }

}
