<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleUpdateUnauthorizedTest extends TestCase
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

        $response = $this->json('PUT', '/api/v1/action_sequence_schedules/' . $ass->id, [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequenceSchedule01_UPDATED'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $ass->delete();
        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
