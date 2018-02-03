<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use App\ActionSequence;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\ActionSequenceTrigger;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerUpdateOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerUpdateOkTest extends TestCase
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

        $response = $this->json('PUT', '/api/v1/action_sequence_triggers/' . $ast->id, [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequenceTrigger01_UPDATED',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $ast->delete();
        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
