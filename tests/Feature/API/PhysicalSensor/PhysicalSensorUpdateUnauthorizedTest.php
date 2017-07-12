<?php

namespace Tests\Feature\PhysicalSensor;


use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PhysicalSensorUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class PhysicalSensorUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly(false);

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->put('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'name' => 'TestPhysicalSensor01_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $physical_sensor->delete();

        $this->cleanupUsers();

    }

}
