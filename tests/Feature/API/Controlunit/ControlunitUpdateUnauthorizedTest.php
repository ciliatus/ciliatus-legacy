<?php

namespace Tests\Feature\API\Controlunit;

use App\Controlunit;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class ControlunitUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->put('/api/v1/controlunits/' . $controlunit->id, [
            'name' => 'TestControlunit01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $controlunit->delete();

        $this->cleanupUsers();

    }

}
