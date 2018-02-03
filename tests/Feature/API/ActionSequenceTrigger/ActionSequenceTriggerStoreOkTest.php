<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use App\ActionSequence;
use App\LogicalSensor;
use App\ActionSequenceTrigger;
use App\PhysicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerStoreOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $ps = PhysicalSensor::create(['name' => 'PS01']);
        $ls = LogicalSensor::create(['name' => 'LS01', 'type' => 'humidity_percent', 'physical_sensor_id' => $ps->id]);

        $as = ActionSequence::create([
            'terrarium_id' => $terrarium->id,
            'name' => 'TestActionSequenceTrigger01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $response = $this->json('POST', '/api/v1/action_sequence_triggers', [
            'name' => 'TestActionSequenceTrigger01_Schedule',
            'action_sequence' => $as->id,
            'logical_sensor' => $ls->id,
            'reference_value' => 30,
            'minimum_timeout_minutes' => 120,
            'reference_value_comparison_type' => 'lesser',
            'reference_value_duration_threshold' => 30,
            'reference_value_duration_threshold_minutes' => 60,
            'timeframe_start' => '20:00:00',
            'timeframe_end' => '08:00:00',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $action_sequence_trigger_id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/action_sequence_triggers/' . $action_sequence_trigger_id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_trigger_id
            ]
        ]);

        ActionSequenceTrigger::find($action_sequence_trigger_id)->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
