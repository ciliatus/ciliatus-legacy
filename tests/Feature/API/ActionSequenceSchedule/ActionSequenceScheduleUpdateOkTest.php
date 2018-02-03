<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequence;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\ActionSequenceSchedule;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleUpdateOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleUpdateOkTest extends TestCase
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

        $response = $this->json('PUT', '/api/v1/action_sequence_schedules/' . $ass->id, [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequenceSchedule01_UPDATED',
            'starts_at' => Carbon::today()->toDateTimeString()
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $ass->delete();
        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
