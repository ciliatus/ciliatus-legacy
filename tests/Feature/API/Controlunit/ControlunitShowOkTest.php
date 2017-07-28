<?php

namespace Tests\Feature\API\Controlunit;

use App\LogicalSensor;
use App\Controlunit;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitShowOkTest
 * @package Tests\Feature
 */
class ControlunitShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->get('/api/v1/controlunits/' . $controlunit->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $controlunit->id,
                'name' => $controlunit->name
            ]
        ]);

        $controlunit->delete();

        $this->cleanupUsers();

    }

}
