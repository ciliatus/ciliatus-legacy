<?php

namespace Tests\Feature\API\Terrarium;

use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumStoreOkTest
 * @package Tests\Feature
 */
class TerrariumStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->post('/api/v1/terraria', [
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->get('/api/v1/terraria/' . $id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
            ]
        ]);

        Terrarium::find($id)->delete();

        $this->cleanupUsers();

    }

}
