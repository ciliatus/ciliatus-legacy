<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use App\ActionSequence;
use App\Controlunit;
use App\ActionSequenceTrigger;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerShowOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerShowOkTest extends TestCase
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

        $response = $this->get('/api/v1/action_sequence_triggers/' . $ast->id . '/?with[]=sequence', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $ast->id,
                'action_sequence_id' => $as->id,
                'logical_sensor_id' => $ls->id,
                'reference_value' => $ast->reference_value,
                'reference_value_comparison_type' => $ast->reference_value_comparison_type,
                'minimum_timeout_minutes' => $ast->minimum_timeout_minutes,
                'timeframe' => [
                    'start' => $ast->timeframe_start,
                    'end'   => $ast->timeframe_end,
                ],
                'states'    => [
                    'running' => false,
                    'should_be_started' => false,
                ],
                'sequence' => [
                    'id' => $as->id
                ],
                'logical_sensor' => [
                    'id' => $ls->id
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
