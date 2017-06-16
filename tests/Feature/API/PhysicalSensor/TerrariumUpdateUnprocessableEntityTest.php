<?php

namespace Tests\Feature\Valve;

use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PhysicalSensorUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class PhysicalSensorUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $response = $this->put('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'name' => 'TestPhysicalSensor01_Updated',
            'controlunit' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
