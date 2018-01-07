<?php

namespace Tests\Feature\Web\LogicalSensor;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorIndexOkTest
 * @package Tests\Feature
 */
class LogicalSensorCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/physical_sensors/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
