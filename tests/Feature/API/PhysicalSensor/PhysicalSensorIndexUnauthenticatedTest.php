<?php

namespace Tests\Feature\PhysicalSensor;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PhysicalSensorIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class PhysicalSensorIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/physical_sensors');
        $response->assertStatus(401);

    }

}
