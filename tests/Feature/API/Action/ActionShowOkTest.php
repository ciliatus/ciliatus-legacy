<?php

namespace Tests\Feature\API\Action;

use App\ActionSequence;
use App\Controlunit;
use App\LogicalSensor;
use App\Action;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionShowOkTest
 * @package Tests\Feature
 */
class ActionShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $action_sequence = ActionSequence::create(['terrarium_id' => $terrarium->id]);
        $valve = Valve::create(['name' => 'V01']);
        $action = Action::create([
            'action_sequence_id' => $action_sequence->id,
            'target_type' => 'Valve',
            'target_id' => $valve->id,
            'desired_state' => 'running',
            'duration_minutes' => 10,
            'sequence_sort_id' => 1
        ]);

        $response = $this->get('/api/v1/actions/' . $action->id . '?with[]=sequence', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action->id,
                'sequence' => [
                    'id' => $action_sequence->id
                ]
            ]
        ]);

        $action_sequence->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
