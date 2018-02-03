<?php

namespace Tests\Feature\API\LogicalSensorThreshold;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdStoreUnauthorizedTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->json('POST', '/api/v1/logical_sensor_thresholds', [
            'name' => 'TestLogicalSensorThreshold01'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
