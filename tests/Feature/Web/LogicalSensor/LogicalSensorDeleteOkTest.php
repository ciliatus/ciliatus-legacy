<?php

namespace Tests\Feature\Web\LogicalSensor;

use App\LogicalSensor;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorIndexOkTest
 * @package Tests\Feature
 */
class LogicalSensorDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $ps = PhysicalSensor::create(['display_name' => 'LogicalSensor01']);
        $obj = LogicalSensor::create(['display_name' => 'LogicalSensor01', 'physical_sensor_id' => $ps->id]);
        $this->actingAs($user)->get('/logical_sensors/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
