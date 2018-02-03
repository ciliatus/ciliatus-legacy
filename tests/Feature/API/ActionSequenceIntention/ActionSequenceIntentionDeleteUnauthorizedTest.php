<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use App\ActionSequence;
use App\ActionSequenceIntention;
use App\Terrarium;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
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

        $response = $this->delete('/api/v1/action_sequence_intentions/' . $asi->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
