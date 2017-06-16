<?php

namespace Tests\Feature\Valve;

use App\Animal;
use App\Controlunit;
use App\PhysicalSensor;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PhysicalSensorUpdateOkTest
 * @package Tests\Feature
 */
class PhysicalSensorUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions(false);

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPhysicalSensor01'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'PhysicalSensorControlunit01'
        ]);

        $response = $this->put('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'name' => 'TestPhysicalSensor01_Updated',
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/physical_sensors/' . $physical_sensor->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $physical_sensor->id,
                'name' => 'TestPhysicalSensor01_Updated',
                'controlunit' => [
                    'id' => $controlunit->id
                ]
            ]
        ]);

        $physical_sensor->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
