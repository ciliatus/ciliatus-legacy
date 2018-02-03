<?php

namespace Tests\Feature\API\Action;

use App\Action;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ActionStoreUnprocessableEntityTest
 * @package Tests\Feature
 */
class ActionStoreUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->json('POST', '/api/v1/actions', [
            'action_sequence_id' => 'NO_EXISTING'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $terrarium->delete();

        $this->cleanupUsers();

    }

}
