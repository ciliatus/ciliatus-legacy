<?php

namespace Tests\Feature\Web\PhysicalSensor;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class PhysicalSensorIndexOkTest
 * @package Tests\Feature
 */
class PhysicalSensorCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/physical_sensors/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
