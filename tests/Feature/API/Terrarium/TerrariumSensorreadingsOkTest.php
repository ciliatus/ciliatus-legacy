<?php

namespace Tests\Feature\API\Terrarium;

use App\CustomComponent;
use App\CustomComponentType;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Sensorreading;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumSensorreadingsOkTest
 * @package Tests\Feature
 */
class TerrariumSensorreadingsOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $ps = PhysicalSensor::create([
            'name' => 'V',
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $terrarium->id
        ]);

        $lsh = LogicalSensor::create([
            'name' => 'LS',
            'physical_sensor_id' => $ps->id,
            'type' => 'humidity_percent'
        ]);

        $lst = LogicalSensor::create([
            'name' => 'LS',
            'physical_sensor_id' => $ps->id,
            'type' => 'temperature_celsius'
        ]);

        $uuid = Uuid::uuid4();

        Sensorreading::create([
            'sensorreadinggroup_id' => $uuid,
            'logical_sensor_id' => $lsh->id,
            'rawvalue' => 99
        ]);

        Sensorreading::create([
            'sensorreadinggroup_id' => $uuid,
            'logical_sensor_id' => $lst->id,
            'rawvalue' => 20
        ]);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id . '/sensorreadings', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id . '/sensorreadingsByType/humidity', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id . '/sensorreadingsByType/temperature', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $ps->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
