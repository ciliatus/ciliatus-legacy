<?php

namespace Tests\Feature\API\Action;

use App\ActionSequence;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Action;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionUpdateOkTest
 * @package Tests\Feature
 */
class ActionUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);
        $action_sequence = ActionSequence::create(['terrarium_id' => $terrarium->id]);
        $valve = Valve::create(['name' => 'V01']);
        $valve2 = Valve::create(['name' => 'V02']);
        $action = Action::create([
            'action_sequence_id' => $action_sequence->id,
            'target_type' => 'Valve',
            'target_id' => $valve->id,
            'desired_state' => 'running',
            'duration_minutes' => 10,
            'sequence_sort_id' => 1
        ]);
        $action2 = Action::create([
            'action_sequence_id' => $action_sequence->id,
            'target_type' => 'Valve',
            'target_id' => $valve2->id,
            'desired_state' => 'running',
            'duration_minutes' => 10,
            'sequence_sort_id' => 1
        ]);

        $response = $this->json('PUT', '/api/v1/actions/' . $action->id, [
            'wait_for_started_action_id' => $action2->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/actions/' . $action->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action->id,
                'wait_for_started_action_id' => $action2->id
            ]
        ]);

        $action2->delete();
        $action->delete();
        $action_sequence->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
