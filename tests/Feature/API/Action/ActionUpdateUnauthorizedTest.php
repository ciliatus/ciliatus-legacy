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
 * Class ActionUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class ActionUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);
        $action_sequence = ActionSequence::create(['terrarium_id' => $terrarium->id]);
        $action = Action::create(['action_sequence_id' => $action_sequence->id]);
        $action2 = Action::create(['action_sequence_id' => $action_sequence->id]);

        $response = $this->json('PUT', '/api/v1/actions/' . $action->id, [
            'wait_for_started_action_id' => $action2->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $action2->delete();
        $action->delete();
        $action_sequence->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
