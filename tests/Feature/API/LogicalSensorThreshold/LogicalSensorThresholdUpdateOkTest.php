<?php

namespace Tests\Feature\API\LogicalSensorThreshold;

use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\LogicalSensorThreshold;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdUpdateOkTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdUpdateOkTest extends TestCase
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
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'Authorization' => 'Bearer ' . $token
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

        $logical_sensor_threshold->delete();
        $ls->delete();
        $ps->delete();

        $this->cleanupUsers();

    }

}
