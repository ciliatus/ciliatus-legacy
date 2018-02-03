<?php

namespace Tests\Feature\API\Action;

use App\Action;
use App\ActionSequence;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionStoreUnauthorizedTest
 * @package Tests\Feature
 */
class ActionStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);
        $action_sequence = ActionSequence::create(['terrarium_id' => $terrarium->id]);

        $response = $this->json('POST', '/api/v1/actions', [
            'action_sequence_id' => $action_sequence->id
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $action_sequence->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
