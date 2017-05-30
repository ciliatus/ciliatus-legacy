<?php

namespace Tests\Feature\LogicalSensorThreshold;

use App\LogicalSensorThreshold;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class LogicalSensorThresholdUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class LogicalSensorThresholdUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $logical_sensor_threshold = LogicalSensorThreshold::create();

        $response = $this->put('/api/v1/logical_sensor_thresholds/' . $logical_sensor_threshold->id, [
            'logical_sensor' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
