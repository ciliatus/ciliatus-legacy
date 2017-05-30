<?php

namespace Tests\Feature\LogicalSensorThreshold;

use App\LogicalSensor;
use App\LogicalSensorThreshold;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdStoreOkTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdStoreOkTest extends TestCase
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

        $response = $this->post('/api/v1/logical_sensor_thresholds', [
            'logical_sensor' => $ls->id,
            'starts_at' => '00:00:00',
            'lowerlimit' => 10,
            'upperlimit' => 20,
            'active' => true
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

        $response = $this->get('/api/v1/logical_sensor_thresholds/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'logical_sensor_id' => $ls->id,
                'rawvalue_lowerlimit' => 10,
                'rawvalue_upperlimit' => 20,
                'timestamps' => [
                    'starts' => '00:00:00'
                ]
            ]
        ]);

        LogicalSensorThreshold::find($id)->delete();
        $ls->delete();
        $ps->delete();

        $this->cleanupUsers();

    }

}
