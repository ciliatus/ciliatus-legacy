<?php

namespace Tests\Feature\API\LogicalSensorThreshold;

use App\LogicalSensorThreshold;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $logical_sensor_threshold = LogicalSensorThreshold::create();

        $response = $this->put('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
