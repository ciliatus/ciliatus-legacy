<?php

namespace Tests\Feature\Valve;


use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class TerrariumUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'name' => 'TestTerrarium01_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $terrarium->delete();

        $this->cleanupUsers();

    }

}
