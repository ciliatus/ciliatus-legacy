<?php

namespace Tests\Feature\API\Controlunit;

use App\Controlunit;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitUpdateOkTest
 * @package Tests\Feature
 */
class ControlunitUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $controlunit = Controlunit::create([
            'name' => 'ControlunitControlunit01'
        ]);

        $response = $this->put('/api/v1/controlunits/' . $controlunit->id, [
            'name' => 'TestControlunit01_Updated',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/controlunits/' . $controlunit->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $controlunit->id,
                'name' => 'TestControlunit01_Updated'
            ]
        ]);

        $controlunit->delete();

        $this->cleanupUsers();

    }

}
