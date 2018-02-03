<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequence;
use App\Controlunit;
use App\ActionSequenceSchedule;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleShowOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleShowOkTest extends TestCase
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
            'name' => 'TestActionSequenceSchedule01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $ass = ActionSequenceSchedule::create([
            'name' => 'TestActionSequenceSchedule01_Schedule',
            'runonce' => false,
            'starts_at' => Carbon::today(),
            'action_sequence_id' => $as->id
        ]);

        $response = $this->get('/api/v1/action_sequence_schedules/' . $ass->id . '/?with[]=sequence&with[]=sequence', [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id'            =>  $ass->id,
                'runonce'       =>  $ass->runonce,
                'states'        => [
                    'willRunToday' => false,
                    'ran_today' => false,
                    'running' => false,
                    'is_overdue' => true,
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
