<?php

namespace Tests\Feature\API\Terrarium;

use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class TerrariumUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'name' => 'TestTerrarium01_Updated',
            'animals' => ['doesnotexist'],
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $terrarium->delete();

        $this->cleanupUsers();

    }

}
