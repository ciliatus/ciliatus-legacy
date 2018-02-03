<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use App\ActionSequence;
use App\ActionSequenceTrigger;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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

        $ast = ActionSequenceTrigger::create([
            'name' => 'TestActionSequenceTrigger01_Schedule',
            'action_sequence_id' => $as->id,
            'logical_sensor_id' => $ls->id,
            'reference_value' => 30,
            'minimum_timeout_minutes' => 120,
            'reference_value_comparison_type' => 'lesser',
            'reference_value_duration_threshold_minutes' => 60,
            'timeframe_start' => '20:00:00',
            'timeframe_end' => '08:00:00'
        ]);

        $response = $this->delete('/api/v1/action_sequence_triggers/' . $ast->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
