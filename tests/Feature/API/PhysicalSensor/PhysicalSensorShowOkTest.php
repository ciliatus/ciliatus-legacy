<?php

namespace Tests\Feature\PhysicalSensor;

use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PhysicalSensorShowOkTest
 * @package Tests\Feature
 */
class PhysicalSensorShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->get('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $physical_sensor->id,
                'name' => $physical_sensor->name
            ]
        ]);

        $physical_sensor->delete();

        $this->cleanupUsers();

    }

}
