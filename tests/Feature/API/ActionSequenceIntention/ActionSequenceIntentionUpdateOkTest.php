<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use App\ActionSequence;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\ActionSequenceIntention;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionUpdateOkTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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

        $response = $this->json('PUT', '/api/v1/action_sequence_intentions/' . $asi->id, [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequenceIntention01_UPDATED',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $asi->delete();
        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
