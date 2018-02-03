<?php

namespace Tests\Feature\API\Terrarium;

use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumDeleteOkTest
 * @package Tests\Feature
 */
class TerrariumDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->delete('/api/v1/terraria/' . $terrarium->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $this->cleanupUsers();

    }

}
