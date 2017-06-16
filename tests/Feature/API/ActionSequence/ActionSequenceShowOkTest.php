<?php

namespace Tests\Feature\ActionSequence;

use App\Controlunit;
use App\LogicalSensor;
use App\ActionSequence;
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
 * Class ActionSequenceShowOkTest
 * @package Tests\Feature
 */
class ActionSequenceShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();
        $token_write = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'Controlunit01'
        ]);

        $pump = Pump::create([
            'name' => 'TestValve01',
            'controlunit_id' => $controlunit->id
        ]);

        $valve = Valve::create([
            'name' => 'TestValve01',
            'terrarium_id' => $terrarium->id,
            'controlunit_id' => $controlunit->id,
            'pump_id' => $pump->id
        ]);

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token_write
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $action_sequence_id = $response->decodeResponseJson()['data']['id'];
        $action_sequence = ActionSequence::find($action_sequence_id);

        $response = $this->get('/api/v1/action_sequences/' . $action_sequence_id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_id,
                'name' => 'TestActionSequence01',
                'duration_minutes' => 1,
                'actions' => [
                    [
                        'id' => $action_sequence->actions->first()->id,
                        'target_type' => 'Valve',
                        'target_id' => $valve->id,
                        'desired_state' => 'running',
                        'duration_minutes' => 1
                    ]
                ],
                'terrarium' => [
                    'id' => $terrarium->id,
                    'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
                ]
            ]
        ]);

        $action_sequence->delete();
        $valve->delete();
        $pump->delete();
        $terrarium->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
