<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleStoreUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleStoreUnauthorizedTest extends TestCase
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

        $response = $this->post('/api/v1/action_sequence_schedules', [
            'name' => 'TestActionSequenceSchedule02_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
