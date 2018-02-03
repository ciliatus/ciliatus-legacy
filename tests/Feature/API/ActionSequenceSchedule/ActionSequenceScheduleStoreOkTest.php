<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequence;
use App\LogicalSensor;
use App\ActionSequenceSchedule;
use App\PhysicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleStoreOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleStoreOkTest extends TestCase
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

        $response = $this->json('POST', '/api/v1/action_sequence_schedules', [
            'action_sequence' => $as->id,
            'name' => 'TestActionSequenceSchedule01',
            'runonce' => 'on',
            'starts_at' => Carbon::tomorrow()->toDateTimeString()
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $action_sequence_schedule_id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/action_sequence_schedules/' . $action_sequence_schedule_id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_schedule_id
            ]
        ]);

        ActionSequenceSchedule::find($action_sequence_schedule_id)->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
