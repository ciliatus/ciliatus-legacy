<?php

namespace Tests\Feature\Web\ActionSequenceSchedule;

use App\ActionSequence;
use App\ActionSequenceSchedule;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $as = ActionSequence::create();
        $obj = ActionSequenceSchedule::create(['action_sequence_id' => $as->id]);
        $this->actingAs($user)->get('/action_sequence_schedules/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();
        $as->delete();

        $this->cleanupUsers();

    }

}
