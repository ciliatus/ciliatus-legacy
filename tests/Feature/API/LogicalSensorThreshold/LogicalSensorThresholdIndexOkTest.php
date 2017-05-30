<?php

namespace Tests\Feature\LogicalSensorThreshold;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdIndexOkTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdIndexOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->json('GET', '/api/v1/logical_sensor_thresholds', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers();

    }

}
