<?php

namespace Tests\Feature\Valve;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class SensorreadingIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class SensorreadingIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/sensorreadings');
        $response->assertStatus(401);

    }

}
