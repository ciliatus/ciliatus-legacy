<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleDeleteOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleDeleteOkTest extends TestCase
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
            'name' => 'TestActionSequenceSchedule01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $ass = ActionSequenceSchedule::create([
            'name' => 'TestActionSequenceSchedule01_Schedule',
            'runonce' => false,
            'starts_at' => Carbon::tomorrow(),
            'action_sequence_id' => $as->id
        ]);

        $response = $this->delete('/api/v1/action_sequence_schedules/' . $ass->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/action_sequence_schedules/' . $ass->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $terrarium->delete();

        $this->cleanupUsers();
    }

}
