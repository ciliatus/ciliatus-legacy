<?php

namespace Tests\Feature\Valve;

use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class TerrariumDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->delete('/api/v1/terraria/' . $terrarium->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $terrarium->delete();

        $this->cleanupUsers();

    }

}
