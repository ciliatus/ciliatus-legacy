<?php

namespace Tests\Feature\Valve;

use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumShowOkTest
 * @package Tests\Feature
 */
class TerrariumShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $terrarium->id,
                'display_name' => $terrarium->display_name
            ]
        ]);

        $terrarium->delete();

        $this->cleanupUsers();

    }

}
