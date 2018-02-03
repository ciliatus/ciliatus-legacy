<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use App\ActionSequence;
use App\LogicalSensor;
use App\ActionSequenceIntention;
use App\PhysicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionStoreOkTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionStoreOkTest extends TestCase
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

        $response = $this->json('POST', '/api/v1/action_sequence_intentions', [
            'action_sequence' => $as->id,
            'name' => 'TestActionSequenceIntention01',
            'timeframe_start' => '20:00:00',
            'timeframe_end' => '08:00:00',
            'intention' => 'increase',
            'type' => 'humidity_percent',
            'minimum_timeout_minutes' => 120
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $action_sequence_intention_id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/action_sequence_intentions/' . $action_sequence_intention_id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_intention_id
            ]
        ]);

        ActionSequenceIntention::find($action_sequence_intention_id)->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
