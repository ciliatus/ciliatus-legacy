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
class LogicalSensorEditOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $ps = PhysicalSensor::create(['display_name' => 'LogicalSensor01']);
        $obj = LogicalSensor::create(['display_name' => 'LogicalSensor01', 'physical_sensor_id' => $ps->id]);
        $this->actingAs($user)->get('/physical_sensors/' . $obj->id . '/edit')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
