<?php

namespace Tests\Feature\API\Sensorreading;

use App\LogicalSensor;
use App\PhysicalSensor;
use App\Sensorreading;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * class SensorreadingStoreOkTest
 * @package Tests\Feature
 */
class SensorreadingStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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

        $response = $this->post('/api/v1/sensorreadings', [
            'group_id' => $group_id,
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 15.4
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->get('/api/v1/sensorreadings/' . $id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'group_id' => $group_id,
                'logical_sensor_id' => $ls->id,
                'rawvalue' => 15.4
            ]
        ]);

        Sensorreading::find($id)->delete();
        $ls->delete();
        $ps->delete();

        $this->cleanupUsers();

    }

}
