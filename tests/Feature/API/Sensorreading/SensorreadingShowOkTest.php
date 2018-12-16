<?php

namespace Tests\Feature\API\Sensorreading;

use App\LogicalSensor;
use App\PhysicalSensor;
use App\Sensorreading;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * class SensorreadingShowOkTest
 * @package Tests\Feature
 */
class SensorreadingShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $ps = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $ls = LogicalSensor::create([
            'name' => 'TestLogicalSensor01',
            'physical_sensor_id' => $ps->id
        ]);
        $ls->rawvalue_lowerlimit = 0;
        $ls->rawvalue_upperlimit = 30;
        $ls->save();

        $group_id = Uuid::generate()->string;

        $sr = Sensorreading::create([
            'sensorreadinggroup_id' => $group_id,
            'logical_sensor_id' => $ls->id,
            'adjusted_value' => 15.4
        ]);

        $response = $this->get('/api/v1/sensorreadings/' . $sr->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $sr->id,
                'group_id' => $group_id,
                'logical_sensor_id' => $ls->id,
                'adjusted_value' => 15.4
            ]
        ]);

        $sr->delete();
        $ls->delete();
        $ps->delete();

        $this->cleanupUsers();

    }

}
