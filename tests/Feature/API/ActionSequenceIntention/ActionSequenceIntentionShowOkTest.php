<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use App\ActionSequence;
use App\Controlunit;
use App\ActionSequenceIntention;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionShowOkTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionShowOkTest extends TestCase
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

        $as = ActionSequence::create([
            'terrarium_id' => $terrarium->id,
            'name' => 'TestActionSequenceIntention01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $asi = ActionSequenceIntention::create([
            'name' => 'TestActionSequenceIntention01_Schedule',
            'action_sequence_id' => $as->id,
            'timeframe_start' => '20:00:00',
            'timeframe_end' => '08:00:00',
            'intention' => 'increase',
            'type' => 'humidity_percent',
            'minimum_timeout_minutes' => 120
        ]);

        $response = $this->get('/api/v1/action_sequence_intentions/' . $asi->id . '/?with[]=sequence', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $asi->id,
                'action_sequence_id' => $as->id,
                'type' => $asi->type,
                'intention' => $asi->intention,
                'minimum_timeout_minutes' => $asi->minimum_timeout_minutes,
                'timeframe' => [
                    'start' => $asi->timeframe_start,
                    'end'   => $asi->timeframe_end,
                ],
                'states'    => [
                    'running' => false,
                    'should_be_started' => false,
                ],
                'sequence' => [
                    'id' => $as->id
                ]
            ]
        ]);

        $as->delete();
        $valve->delete();
        $pump->delete();
        $terrarium->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
