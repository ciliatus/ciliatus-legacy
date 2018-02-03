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
 * Class LogicalSensorStoreOkTest
 * @package Tests\Feature
 */
class LogicalSensorStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $physical_sensor = PhysicalSensor::create([
            'name' => 'LogicalSensorPhysicalSensor01'
        ]);

        $response = $this->post('/api/v1/logical_sensors', [
            'name' => 'TestLogicalSensor01',
            'physical_sensor' => $physical_sensor->id
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

        $response = $this->get('/api/v1/logical_sensors/' . $id . '/?with[]=physical_sensor', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestLogicalSensor01',
                'physical_sensor' => [
                    'id' => $physical_sensor->id
                ]
            ]
        ]);

        LogicalSensor::find($id)->delete();
        $physical_sensor->delete();

        $this->cleanupUsers();

    }

}
