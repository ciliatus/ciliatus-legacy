<?php

namespace Tests\Feature\Web\PhysicalSensor;

use App\PhysicalSensor;
use App\PhysicalSensorSequence;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransphysical_sensors;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class PhysicalSensorIndexOkTest
 * @package Tests\Feature
 */
class PhysicalSensorDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = PhysicalSensor::create(['display_name' => 'PhysicalSensor01']);
        $this->actingAs($user)->get('/physical_sensors/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
