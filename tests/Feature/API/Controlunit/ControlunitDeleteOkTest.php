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
 * Class ControlunitDeleteOkTest
 * @package Tests\Feature
 */
class ControlunitDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->delete('/api/v1/controlunits/' . $controlunit->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/controlunits/' . $controlunit->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $this->cleanupUsers();
    }

}
